<?php


declare(strict_types=1);

namespace Doctrine\ORM\Mapping\Exporter;

use Doctrine\ORM\Mapping\OneToManyAssociationMetadata;
use function sprintf;

class OneToManyAssociationMetadataExporter extends ToManyAssociationMetadataExporter
{
    /**
     * {@inheritdoc}
     */
    protected function exportInstantiation(OneToManyAssociationMetadata $metadata) : string
    {
        return sprintf(
            'new Mapping\OneToManyAssociationMetadata("%s");',
            $metadata->getName()
        );
    }
}
