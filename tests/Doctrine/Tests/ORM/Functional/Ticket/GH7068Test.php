<?php

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Annotation as ORM;
use Doctrine\ORM\TransactionRequiredException;
use Doctrine\Tests\OrmFunctionalTestCase;

final class GH7068Test extends OrmFunctionalTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setUpEntitySchema(
            [
                SomeEntity::class,
            ]
        );
    }

    public function testLockModeIsRespected()
    {
        $entity = new SomeEntity();
        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $this->em->find(SomeEntity::class, 1);

        $this->expectException(TransactionRequiredException::class);
        $this->em->find(SomeEntity::class, 1, LockMode::PESSIMISTIC_WRITE);
    }
}

/** @ORM\Entity */
final class SomeEntity {
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    public $id;
}
