<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query\Parser;

final class Tokens
{
    public const T_OPEN_CURLY_BRACE = 'open_curly_brace';
    public const T_CLOSE_CURLY_BRACE = 'close_curly_brace';
    public const T_OPEN_PARENTHESIS = 'open_parenthesis';
    public const T_CLOSE_PARENTHESIS = 'close_parenthesis';
    public const T_DOT = 'dot';
    public const T_COMMA = 'comma';
    public const T_DIVIDE = 'divide';
    public const T_MULTIPLY = 'multiply';
    public const T_PLUS = 'plus';
    public const T_MINUS = 'minus';
    public const T_NOT_EQUALS = 'not_equals';
    public const T_EQUALS = 'equals';
    public const T_GREATER_THAN = 'greater_than';
    public const T_LOWER_THAN = 'lower_than';
    public const T_DIFFERENT = 'different';
    public const T_GREATER = 'greater';
    public const T_LOWER = 'lower';
    public const T_NEGATE = 'negate';
    public const T_FLOAT = 'float';
    public const T_INTEGER = 'integer';
    public const T_BOOLEAN = 'boolean';
    public const T_OPEN_APOSTROPHE = 'open_apostrophe';
    public const T_STRING_CHAR = 'string:char';
    public const T_STRING_CLOSE_APOSTROPHE = 'string:close_apostrophe';
    public const T_COLON = 'colon';
    public const T_QUESTION_MARK = 'question_mark';
    public const T_ALL = 'all';
    public const T_AND = 'and';
    public const T_ANY = 'any';
    public const T_ASC = 'asc';
    public const T_AS = 'as';
    public const T_AVG = 'avg';
    public const T_BETWEEN = 'between';
    public const T_BOTH = 'both';
    public const T_BY = 'by';
    public const T_CASE = 'case';
    public const T_COALESCE = 'coalesce';
    public const T_COUNT = 'count';
    public const T_DELETE = 'delete';
    public const T_DESC = 'desc';
    public const T_DISTINCT = 'distinct';
    public const T_ELSE = 'else';
    public const T_EMPTY = 'empty';
    public const T_END = 'end';
    public const T_ESCAPE = 'escape';
    public const T_EXISTS = 'exists';
    public const T_FROM = 'from';
    public const T_GROUP = 'group';
    public const T_HAVING = 'having';
    public const T_HIDDEN = 'hidden';
    public const T_INSTANCE = 'instance';
    public const T_INDEX = 'index';
    public const T_INNER = 'inner';
    public const T_IN = 'in';
    public const T_IS = 'is';
    public const T_JOIN = 'join';
    public const T_LEADING = 'leading';
    public const T_LEFT = 'left';
    public const T_LIKE = 'like';
    public const T_MAX = 'max';
    public const T_MEMBER = 'member';
    public const T_MIN = 'min';
    public const T_NEW = 'new';
    public const T_NOT = 'not';
    public const T_NULLIF = 'nullif';
    public const T_NULL = 'null';
    public const T_OF = 'of';
    public const T_ORDER = 'order';
    public const T_OR = 'or';
    public const T_OUTER = 'outer';
    public const T_PARTIAL = 'partial';
    public const T_SELECT = 'select';
    public const T_SET = 'set';
    public const T_SOME = 'some';
    public const T_SUM = 'sum';
    public const T_THEN = 'then';
    public const T_TRAILING = 'trailing';
    public const T_UPDATE = 'update';
    public const T_WHEN = 'when';
    public const T_WHERE = 'where';
    public const T_WITH = 'with';
    public const T_FUNCTION_ABS = 'function_abs';
    public const T_FUNCTION_BIT_AND = 'function_bit_and';
    public const T_FUNCTION_BIT_OR = 'function_bit_or';
    public const T_FUNCTION_CONCAT = 'function_concat';
    public const T_FUNCTION_CURRENT_DATE = 'function_current_date';
    public const T_FUNCTION_CURRENT_TIME = 'function_current_time';
    public const T_FUNCTION_CURRENT_TIMESTAMP = 'function_current_timestamp';
    public const T_FUNCTION_DATE_ADD = 'function_date_add';
    public const T_FUNCTION_DATE_DIFF = 'function_date_diff';
    public const T_FUNCTION_DATE_SUB = 'function_date_sub';
    public const T_FUNCTION_LOWER = 'function_lower';
    public const T_FUNCTION_LENGTH = 'function_length';
    public const T_FUNCTION_LOCATE = 'function_locate';
    public const T_FUNCTION_IDENTITY = 'function_identity';
    public const T_FUNCTION_MOD = 'function_mod';
    public const T_FUNCTION_SIZE = 'function_size';
    public const T_FUNCTION_SQRT = 'function_sqrt';
    public const T_FUNCTION_SUBSTRING = 'function_substring';
    public const T_FUNCTION_TRIM = 'function_trim';
    public const T_FUNCTION_UPPER = 'function_upper';
    public const T_ALIASED_NAME = 'aliased_name';
    public const T_FULLY_QUALIFIED_NAME = 'fully_qualified_name';
    public const T_IDENTIFIER = 'identifier';

    private function __construct()
    {
    }
}
