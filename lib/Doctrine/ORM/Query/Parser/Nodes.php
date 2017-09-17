<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query\Parser;

final class Nodes
{
    public const QUERY_LANGUAGE = '#QueryLanguage';
    public const SELECT_STATEMENT = '#SelectStatement';
    public const UPDATE_STATEMENT = '#UpdateStatement';
    public const DELETE_STATEMENT = '#DeleteStatement';
    public const SELECT_CLAUSE = '#SelectClause';
    public const SIMPLE_SELECT_CLAUSE = '#SimpleSelectClause';
    public const UPDATE_CLAUSE = '#UpdateClause';
    public const DELETE_CLAUSE = '#DeleteClause';
    public const FROM_CLAUSE = '#FromClause';
    public const SUBSELECT_FROM_CLAUSE = '#SubselectFromClause';
    public const WHERE_CLAUSE = '#WhereClause';
    public const HAVING_CLAUSE = '#HavingClause';
    public const GROUP_BY_CLAUSE = '#GroupByClause';
    public const ORDER_BY_CLAUSE = '#OrderByClause';
    public const SUBSELECT = '#Subselect';
    public const UPDATE_ITEM = '#UpdateItem';
    public const ORDER_BY_ITEM = '#OrderByItem';
    public const GROUP_BY_ITEM = '#GroupByItem';
    public const NEW_VALUE = '#NewValue';
    public const IDENTIFICATION_VARIABLE_DECLARATION = '#IdentificationVariableDeclaration';
    public const SUBSELECT_IDENTIFICATION_VARIABLE_DECLARATION = '#SubselectIdentificationVariableDeclaration';
    public const RANGE_VARIABLE_DECLARATION = '#RangeVariableDeclaration';
    public const JOIN_ASSOCIATION_DECLARATION = '#JoinAssociationDeclaration';
    public const JOIN = '#Join';
    public const INDEX_BY = '#IndexBy';
    public const SELECT_EXPRESSION = '#SelectExpression';
    public const SIMPLE_SELECT_EXPRESSION = '#SimpleSelectExpression';
    public const PARTIAL_OBJECT_EXPRESSION = '#PartialObjectExpression';
    public const PARTIAL_FIELD_SET = '#PartialFieldSet';
    public const NEW_OBJECT_EXPRESSION = '#NewObjectExpression';
    public const NEW_OBJECT_ARG = '#NewObjectArg';
    public const CONDITIONAL_EXPRESSION = '#ConditionalExpression';
    public const CONDITIONAL_TERM = '#ConditionalTerm';
    public const CONDITIONAL_FACTOR = '#ConditionalFactor';
    public const CONDITIONAL_PRIMARY = '#ConditionalPrimary';
    public const SIMPLE_CONDITIONAL_EXPRESSION = '#SimpleConditionalExpression';
    public const EMPTY_COLLECTION_COMPARISON_EXPRESSION = '#EmptyCollectionComparisonExpression';
    public const COLLECTION_MEMBER_EXPRESSION = '#CollectionMemberExpression';
    public const LITERAL = '#Literal';
    public const IN_PARAMETER = '#InParameter';
    public const INPUT_PARAMETER = '#InputParameter';
    public const POSITIONAL_PARAMETER = '#PositionalParameter';
    public const NAMED_PARAMETER = '#NamedParameter';
    public const ARITHMETIC_EXPRESSION = '#ArithmeticExpression';
    public const SIMPLE_ARITHMETIC_EXPRESSION = '#SimpleArithmeticExpression';
    public const ARITHMETIC_TERM = '#ArithmeticTerm';
    public const ARITHMETIC_FACTOR = '#ArithmeticFactor';
    public const ARITHMETIC_PRIMARY = '#ArithmeticPrimary';
    public const SCALAR_EXPRESSION = '#ScalarExpression';
    public const STRING_EXPRESSION = '#StringExpression';
    public const STRING_PRIMARY = '#StringPrimary';
    public const BOOLEAN_EXPRESSION = '#BooleanExpression';
    public const BOOLEAN_PRIMARY = '#BooleanPrimary';
    public const ENTITY_EXPRESSION = '#EntityExpression';
    public const SIMPLE_ENTITY_EXPRESSION = '#SimpleEntityExpression';
    public const DATETIME_EXPRESSION = '#DatetimeExpression';
    public const DATE_TIME_PRIMARY = '#DateTimePrimary';
    public const AGGREGATE_EXPRESSION = '#AggregateExpression';
    public const CASE_EXPRESSION = '#CaseExpression';
    public const GENERAL_CASE_EXPRESSION = '#GeneralCaseExpression';
    public const WHEN_CLAUSE = '#WhenClause';
    public const SIMPLE_CASE_EXPRESSION = '#SimpleCaseExpression';
    public const CASE_OPERAND = '#CaseOperand';
    public const SIMPLE_WHEN_CLAUSE = '#SimpleWhenClause';
    public const COALESCE_EXPRESSION = '#CoalesceExpression';
    public const NULL_IF_EXPRESSION = '#NullIfExpression';
    public const QUANTIFIED_EXPRESSION = '#QuantifiedExpression';
    public const BETWEEN_EXPRESSION = '#BetweenExpression';
    public const COMPARISON_EXPRESSION = '#ComparisonExpression';
    public const IN_EXPRESSION = '#InExpression';
    public const INSTANCE_OF_EXPRESSION = '#InstanceOfExpression';
    public const INSTANCE_OF_PARAMETER = '#InstanceOfParameter';
    public const LIKE_EXPRESSION = '#LikeExpression';
    public const NULL_COMPARISON_EXPRESSION = '#NullComparisonExpression';
    public const EXISTS_EXPRESSION = '#ExistsExpression';
    public const COMPARISON_OPERATOR = '#ComparisonOperator';
    public const IDENTIFICATION_VARIABLE = '#IdentificationVariable';
    public const ALIAS_IDENTIFICATION_VARIABLE = '#AliasIdentificationVariable';
    public const ABSTRACT_SCHEMA_NAME = '#AbstractSchemaName';
    public const ALIAS_RESULT_VARIABLE = '#AliasResultVariable';
    public const RESULT_VARIABLE = '#ResultVariable';
    public const FIELD_IDENTIFICATION_VARIABLE = '#FieldIdentificationVariable';
    public const COLLECTION_VALUED_ASSOCIATION_FIELD = '#CollectionValuedAssociationField';
    public const SINGLE_VALUED_ASSOCIATION_FIELD = '#SingleValuedAssociationField';
    public const EMBEDDED_CLASS_STATE_FIELD = '#EmbeddedClassStateField';
    public const SIMPLE_STATE_FIELD = '#SimpleStateField';
    public const JOIN_ASSOCIATION_PATH_EXPRESSION = '#JoinAssociationPathExpression';
    public const ASSOCIATION_PATH_EXPRESSION = '#AssociationPathExpression';
    public const SINGLE_VALUED_PATH_EXPRESSION = '#SingleValuedPathExpression';
    public const STATE_FIELD_PATH_EXPRESSION = '#StateFieldPathExpression';
    public const SINGLE_VALUED_ASSOCIATION_PATH_EXPRESSION = '#SingleValuedAssociationPathExpression';
    public const COLLECTION_VALUED_PATH_EXPRESSION = '#CollectionValuedPathExpression';
    public const STATE_FIELD = '#StateField';
    public const FUNCTION_DECLARATION = '#FunctionDeclaration';
    public const FUNCTIONS_RETURNING_NUMERICS = '#FunctionsReturningNumerics';
    public const FUNCTIONS_RETURNING_DATE_TIME = '#FunctionsReturningDateTime';
    public const FUNCTIONS_RETURNING_STRINGS = '#FunctionsReturningStrings';

    private function __construct()
    {
    }
}
