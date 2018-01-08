<?php


declare(strict_types=1);

namespace Doctrine\ORM\Mapping\Exporter;

use Doctrine\ORM\Mapping\TableMetadata;
use function str_repeat;
use function ltrim;
use function implode;
use const PHP_EOL;
use function sprintf;

class TableMetadataExporter implements Exporter
{
    const VARIABLE = '$table';

    /**
     * {@inheritdoc}
     */
    public function export($value, int $indentationLevel = 0) : string
    {
        /** @var TableMetadata $value */
        $variableExporter = new VariableExporter();
        $indentation      = str_repeat(self::INDENTATION, $indentationLevel);
        $objectReference  = $indentation . static::VARIABLE;
        $lines = [];

        $lines[] = $objectReference . ' = ' . $this->exportInstantiation($value);

        if (! empty($value->getSchema())) {
            $lines[] = $objectReference . '->setSchema("' . $value->getSchema() . '");';
        }

        foreach ($value->getIndexes() as $index) {
            $lines[] = $objectReference . '->addIndex(' . ltrim($variableExporter->export($index, $indentationLevel + 1)) . ');';
        }

        foreach ($value->getUniqueConstraints() as $uniqueConstraint) {
            $lines[] = $objectReference . '->addUniqueConstraint(' . ltrim($variableExporter->export($uniqueConstraint, $indentationLevel + 1)) . ');';
        }

        $lines[] = $objectReference . '->setOptions(' . ltrim($variableExporter->export($value->getOptions(), $indentationLevel + 1)) . ');';

        return implode(PHP_EOL, $lines);
    }

    /**
     * @param TableMetadata $metadata
     *
     * @return string
     */
    protected function exportInstantiation(TableMetadata $metadata) : string
    {
        return sprintf(
            'new Mapping\TableMetadata("%s");',
            $metadata->getName()
        );
    }
}
