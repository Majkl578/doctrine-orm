<?php


declare(strict_types=1);

namespace Doctrine\ORM\Mapping\Exporter;

use Doctrine\ORM\Mapping\TransientMetadata;
use function str_repeat;
use function sprintf;

class TransientMetadataExporter implements Exporter
{
    const VARIABLE = '$property';

    /**
     * {@inheritdoc}
     */
    public function export($value, int $indentationLevel = 0) : string
    {
        /** @var TransientMetadata $value */
        $indentation      = str_repeat(self::INDENTATION, $indentationLevel);
        $objectReference  = $indentation . static::VARIABLE;

        return $objectReference . ' = ' . $this->exportInstantiation($value);
    }

    /**
     * @param TransientMetadata $metadata
     *
     * @return string
     */
    protected function exportInstantiation(TransientMetadata $metadata) : string
    {
        return sprintf(
            'new Mapping\TransientMetadata("%s");',
            $metadata->getName()
        );
    }
}
