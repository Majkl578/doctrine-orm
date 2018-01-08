<?php

declare(strict_types=1);

namespace Doctrine\ORM\Cache;

use Doctrine\ORM\ORMException;
use function sprintf;

/**
 * Exception for cache.
 *
 * @since   2.5
 * @author  Fabio B. Silva <fabio.bat.silva@gmail.com>
 */
class CacheException extends ORMException
{
    /**
     * @param string $sourceEntity
     * @param string $fieldName
     *
     * @return \Doctrine\ORM\Cache\CacheException
     */
    public static function updateReadOnlyCollection($sourceEntity, $fieldName)
    {
        return new self(sprintf('Cannot update a readonly collection "%s#%s"', $sourceEntity, $fieldName));
    }

    /**
     * @param string $entityName
     *
     * @return \Doctrine\ORM\Cache\CacheException
     */
    public static function updateReadOnlyEntity($entityName)
    {
        return new self(sprintf('Cannot update a readonly entity "%s"', $entityName));
    }

    /**
     * @param string $entityName
     *
     * @return \Doctrine\ORM\Cache\CacheException
     */
    public static function nonCacheableEntity($entityName)
    {
        return new self(sprintf('Entity "%s" not configured as part of the second-level cache.', $entityName));
    }

    /**
     * @param string $entityName
     * @param string $field
     *
     * @return CacheException
     */
    public static function nonCacheableEntityAssociation($entityName, $field)
    {
        return new self(sprintf('Entity association field "%s#%s" not configured as part of the second-level cache.', $entityName, $field));
    }
}
