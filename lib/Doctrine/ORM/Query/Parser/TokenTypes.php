<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query\Parser;

final class TokenTypes
{
    public const FUNCTIONS_RETURNING_STRINGS = [
        Tokens::T_FUNCTION_CONCAT,
        Tokens::T_FUNCTION_SUBSTRING,
        Tokens::T_FUNCTION_LOWER,
        Tokens::T_FUNCTION_UPPER,
        Tokens::T_FUNCTION_IDENTITY,
    ];
    public const FUNCTIONS_RETURNING_NUMERICS = [
        Tokens::T_FUNCTION_LENGTH,
        Tokens::T_FUNCTION_LOCATE,
        Tokens::T_FUNCTION_ABS,
        Tokens::T_FUNCTION_SORT,
        Tokens::T_FUNCTION_MOD,
        Tokens::T_FUNCTION_SIZE,
        Tokens::T_FUNCTION_DATE_DIFF,
        Tokens::T_FUNCTION_BIT_AND,
        Tokens::T_FUNCTION_BIT_OR,
    ];
    public const FUNCTIONS_RETURNING_DATE_TIME = [
        Tokens::T_FUNCTION_CURRENT_DATE,
        Tokens::T_FUNCTION_CURRENT_TIME,
        Tokens::T_FUNCTION_CURRENT_TIMESTAMP,
        Tokens::T_FUNCTION_DATE_ADD,
        Tokens::T_FUNCTION_DATE_SUB,
    ];

    private function __construct()
    {
    }
}
