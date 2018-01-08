<?php


declare(strict_types=1);

namespace Doctrine\ORM\Mapping\Exporter;

use Doctrine\ORM\Mapping\ManyToOneAssociationMetadata;
use function sprintf;

class ManyToOneAssociationMetadataExporter extends ToOneAssociationMetadataExporter
{
    /**
     * {@inheritdoc}
     */
    protected function exportInstantiation(ManyToOneAssociationMetadata $metadata) : string
    {
        return sprintf(
            'new Mapping\ManyToOneAssociationMetadata("%s");',
            $metadata->getName()
        );
    }
}
