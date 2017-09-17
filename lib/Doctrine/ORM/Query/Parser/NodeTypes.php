<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query\Parser;

final class NodeTypes
{
    public const STATEMENTS = [
        Nodes::SELECT_STATEMENT,
        Nodes::UPDATE_STATEMENT,
        Nodes::DELETE_STATEMENT,
    ];

    private function __construct()
    {
    }
}
