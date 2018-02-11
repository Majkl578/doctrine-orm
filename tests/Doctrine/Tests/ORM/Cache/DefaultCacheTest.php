<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Cache;

use Doctrine\ORM\Cache;
use Doctrine\ORM\Cache\CollectionCacheEntry;
use Doctrine\ORM\Cache\CollectionCacheKey;
use Doctrine\ORM\Cache\DefaultCache;
use Doctrine\ORM\Cache\EntityCacheEntry;
use Doctrine\ORM\Cache\EntityCacheKey;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Tests\Models\Cache\Country;
use Doctrine\Tests\Models\Cache\State;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\OrmTestCase;
use function array_merge;

/**
 * @group DDC-2183
 */
class DefaultCacheTest extends OrmTestCase
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected function setUp()
    {
        $this->enableSecondLevelCache();

        parent::setUp();

        $this->em    = $this->getTestEntityManager();
        $this->cache = new DefaultCache($this->em);
    }

    /**
     * @param string $className
     * @param array  $identifier
     * @param array  $data
     */
    private function putEntityCacheEntry($className, array $identifier, array $data)
    {
        $metadata   = $this->em->getClassMetadata($className);
        $cacheKey   = new EntityCacheKey($metadata->getClassName(), $identifier);
        $cacheEntry = new EntityCacheEntry($metadata->getClassName(), $data);
        $persister  = $this->em->getUnitOfWork()->getEntityPersister($metadata->getRootClassName());

        $persister->getCacheRegion()->put($cacheKey, $cacheEntry);
    }

    /**
     * @param string $className
     * @param string $association
     * @param array  $ownerIdentifier
     * @param array  $data
     */
    private function putCollectionCacheEntry($className, $association, array $ownerIdentifier, array $data)
    {
        $metadata   = $this->em->getClassMetadata($className);
        $cacheKey   = new CollectionCacheKey($metadata->getClassName(), $association, $ownerIdentifier);
        $cacheEntry = new CollectionCacheEntry($data);
        $persister  = $this->em->getUnitOfWork()->getCollectionPersister($metadata->getProperty($association));

        $persister->getCacheRegion()->put($cacheKey, $cacheEntry);
    }

    public function testImplementsCache()
    {
        self::assertInstanceOf(Cache::class, $this->cache);
    }

    public function testGetEntityCacheRegionAccess()
    {
        self::assertInstanceOf(Cache\Region::class, $this->cache->getEntityCacheRegion(State::class));
        self::assertNull($this->cache->getEntityCacheRegion(CmsUser::class));
    }

    public function testGetCollectionCacheRegionAccess()
    {
        self::assertInstanceOf(Cache\Region::class, $this->cache->getCollectionCacheRegion(State::class, 'cities'));
        self::assertNull($this->cache->getCollectionCacheRegion(CmsUser::class, 'phonenumbers'));
    }

    public function testContainsEntity()
    {
        $identifier = ['id' => 1];
        $cacheEntry = array_merge($identifier, ['name' => 'Brazil']);

        self::assertFalse($this->cache->containsEntity(Country::class, 1));

        $this->putEntityCacheEntry(Country::class, $identifier, $cacheEntry);

        self::assertTrue($this->cache->containsEntity(Country::class, 1));
        self::assertFalse($this->cache->containsEntity(CmsUser::class, 1));
    }

    public function testEvictEntity()
    {
        $identifier = ['id' => 1];
        $cacheEntry = array_merge($identifier, ['name' => 'Brazil']);

        $this->putEntityCacheEntry(Country::class, $identifier, $cacheEntry);

        self::assertTrue($this->cache->containsEntity(Country::class, 1));

        $this->cache->evictEntity(Country::class, 1);
        $this->cache->evictEntity(CmsUser::class, 1);

        self::assertFalse($this->cache->containsEntity(Country::class, 1));
    }

    public function testEvictEntityRegion()
    {
        $identifier = ['id' => 1];
        $cacheEntry = array_merge($identifier, ['name' => 'Brazil']);

        $this->putEntityCacheEntry(Country::class, $identifier, $cacheEntry);

        self::assertTrue($this->cache->containsEntity(Country::class, 1));

        $this->cache->evictEntityRegion(Country::class);
        $this->cache->evictEntityRegion(CmsUser::class);

        self::assertFalse($this->cache->containsEntity(Country::class, 1));
    }

    public function testEvictEntityRegions()
    {
        $identifier = ['id' => 1];
        $cacheEntry = array_merge($identifier, ['name' => 'Brazil']);

        $this->putEntityCacheEntry(Country::class, $identifier, $cacheEntry);

        self::assertTrue($this->cache->containsEntity(Country::class, 1));

        $this->cache->evictEntityRegions();

        self::assertFalse($this->cache->containsEntity(Country::class, 1));
    }

    public function testContainsCollection()
    {
        $ownerId     = ['id' => 1];
        $association = 'cities';
        $cacheEntry  = [
            ['id' => 11],
            ['id' => 12],
        ];

        self::assertFalse($this->cache->containsCollection(State::class, $association, 1));

        $this->putCollectionCacheEntry(State::class, $association, $ownerId, $cacheEntry);

        self::assertTrue($this->cache->containsCollection(State::class, $association, 1));
        self::assertFalse($this->cache->containsCollection(CmsUser::class, 'phonenumbers', 1));
    }

    public function testEvictCollection()
    {
        $ownerId     = ['id' => 1];
        $association = 'cities';
        $cacheEntry  = [
            ['id' => 11],
            ['id' => 12],
        ];

        $this->putCollectionCacheEntry(State::class, $association, $ownerId, $cacheEntry);

        self::assertTrue($this->cache->containsCollection(State::class, $association, 1));

        $this->cache->evictCollection(State::class, $association, $ownerId);
        $this->cache->evictCollection(CmsUser::class, 'phonenumbers', 1);

        self::assertFalse($this->cache->containsCollection(State::class, $association, 1));
    }

    public function testEvictCollectionRegion()
    {
        $ownerId     = ['id' => 1];
        $association = 'cities';
        $cacheEntry  = [
            ['id' => 11],
            ['id' => 12],
        ];

        $this->putCollectionCacheEntry(State::class, $association, $ownerId, $cacheEntry);

        self::assertTrue($this->cache->containsCollection(State::class, $association, 1));

        $this->cache->evictCollectionRegion(State::class, $association);
        $this->cache->evictCollectionRegion(CmsUser::class, 'phonenumbers');

        self::assertFalse($this->cache->containsCollection(State::class, $association, 1));
    }

    public function testEvictCollectionRegions()
    {
        $ownerId     = ['id' => 1];
        $association = 'cities';
        $cacheEntry  = [
            ['id' => 11],
            ['id' => 12],
        ];

        $this->putCollectionCacheEntry(State::class, $association, $ownerId, $cacheEntry);

        self::assertTrue($this->cache->containsCollection(State::class, $association, 1));

        $this->cache->evictCollectionRegions();

        self::assertFalse($this->cache->containsCollection(State::class, $association, 1));
    }

    public function testQueryCache()
    {
        self::assertFalse($this->cache->containsQuery('foo'));

        $defaultQueryCache = $this->cache->getQueryCache();
        $fooQueryCache     = $this->cache->getQueryCache('foo');

        self::assertInstanceOf(Cache\QueryCache::class, $defaultQueryCache);
        self::assertInstanceOf(Cache\QueryCache::class, $fooQueryCache);
        self::assertSame($defaultQueryCache, $this->cache->getQueryCache());
        self::assertSame($fooQueryCache, $this->cache->getQueryCache('foo'));

        $this->cache->evictQueryRegion();
        $this->cache->evictQueryRegion('foo');
        $this->cache->evictQueryRegions();

        self::assertTrue($this->cache->containsQuery('foo'));

        self::assertSame($defaultQueryCache, $this->cache->getQueryCache());
        self::assertSame($fooQueryCache, $this->cache->getQueryCache('foo'));
    }

    public function testToIdentifierArrayShouldLookupForEntityIdentifier()
    {
        $identifier = 123;
        $entity     = new Country('Foo');
        $metadata   = $this->em->getClassMetadata(Country::class);
        $method     = new \ReflectionMethod($this->cache, 'toIdentifierArray');
        $property   = new \ReflectionProperty($entity, 'id');

        $property->setAccessible(true);
        $method->setAccessible(true);
        $property->setValue($entity, $identifier);

        self::assertEquals(['id' => $identifier], $method->invoke($this->cache, $metadata, $identifier));
    }
}
