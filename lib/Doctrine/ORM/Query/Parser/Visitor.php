<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query\Parser;

use Doctrine\ORM\Query\AST\AggregateExpression;
use Doctrine\ORM\Query\AST\ArithmeticExpression;
use Doctrine\ORM\Query\AST\ArithmeticFactor;
use Doctrine\ORM\Query\AST\ArithmeticTerm;
use Doctrine\ORM\Query\AST\BetweenExpression;
use Doctrine\ORM\Query\AST\CoalesceExpression;
use Doctrine\ORM\Query\AST\CollectionMemberExpression;
use Doctrine\ORM\Query\AST\ComparisonExpression;
use Doctrine\ORM\Query\AST\ConditionalExpression;
use Doctrine\ORM\Query\AST\ConditionalFactor;
use Doctrine\ORM\Query\AST\ConditionalPrimary;
use Doctrine\ORM\Query\AST\ConditionalTerm;
use Doctrine\ORM\Query\AST\DeleteClause;
use Doctrine\ORM\Query\AST\DeleteStatement;
use Doctrine\ORM\Query\AST\EmptyCollectionComparisonExpression;
use Doctrine\ORM\Query\AST\ExistsExpression;
use Doctrine\ORM\Query\AST\FromClause;
use Doctrine\ORM\Query\AST\Functions\AbsFunction;
use Doctrine\ORM\Query\AST\Functions\BitAndFunction;
use Doctrine\ORM\Query\AST\Functions\BitOrFunction;
use Doctrine\ORM\Query\AST\Functions\ConcatFunction;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Doctrine\ORM\Query\AST\Functions\CurrentTimeFunction;
use Doctrine\ORM\Query\AST\Functions\CurrentTimestampFunction;
use Doctrine\ORM\Query\AST\Functions\DateAddFunction;
use Doctrine\ORM\Query\AST\Functions\DateDiffFunction;
use Doctrine\ORM\Query\AST\Functions\DateSubFunction;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Functions\IdentityFunction;
use Doctrine\ORM\Query\AST\Functions\LengthFunction;
use Doctrine\ORM\Query\AST\Functions\LocateFunction;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use Doctrine\ORM\Query\AST\Functions\ModFunction;
use Doctrine\ORM\Query\AST\Functions\SizeFunction;
use Doctrine\ORM\Query\AST\Functions\SqrtFunction;
use Doctrine\ORM\Query\AST\Functions\SubstringFunction;
use Doctrine\ORM\Query\AST\Functions\TrimFunction;
use Doctrine\ORM\Query\AST\Functions\UpperFunction;
use Doctrine\ORM\Query\AST\GeneralCaseExpression;
use Doctrine\ORM\Query\AST\GroupByClause;
use Doctrine\ORM\Query\AST\HavingClause;
use Doctrine\ORM\Query\AST\IdentificationVariableDeclaration;
use Doctrine\ORM\Query\AST\IndexBy;
use Doctrine\ORM\Query\AST\InExpression;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\AST\InstanceOfExpression;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\AST\JoinAssociationDeclaration;
use Doctrine\ORM\Query\AST\JoinAssociationPathExpression;
use Doctrine\ORM\Query\AST\LikeExpression;
use Doctrine\ORM\Query\AST\NewObjectExpression;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\AST\NullComparisonExpression;
use Doctrine\ORM\Query\AST\NullIfExpression;
use Doctrine\ORM\Query\AST\OrderByClause;
use Doctrine\ORM\Query\AST\OrderByItem;
use Doctrine\ORM\Query\AST\PartialObjectExpression;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\AST\QuantifiedExpression;
use Doctrine\ORM\Query\AST\RangeVariableDeclaration;
use Doctrine\ORM\Query\AST\SelectClause;
use Doctrine\ORM\Query\AST\SelectExpression;
use Doctrine\ORM\Query\AST\SelectStatement;
use Doctrine\ORM\Query\AST\SimpleArithmeticExpression;
use Doctrine\ORM\Query\AST\SimpleCaseExpression;
use Doctrine\ORM\Query\AST\SimpleSelectClause;
use Doctrine\ORM\Query\AST\SimpleSelectExpression;
use Doctrine\ORM\Query\AST\SimpleWhenClause;
use Doctrine\ORM\Query\AST\Subselect;
use Doctrine\ORM\Query\AST\SubselectFromClause;
use Doctrine\ORM\Query\AST\UpdateClause;
use Doctrine\ORM\Query\AST\UpdateItem;
use Doctrine\ORM\Query\AST\UpdateStatement;
use Doctrine\ORM\Query\AST\WhenClause;
use Doctrine\ORM\Query\AST\WhereClause;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;

final class Visitor implements Visit
{
    /**
     * @param TreeNode $node
     * @param mixed $handle
     * @param mixed $eldnah
     * @return mixed
     */
    public function visit(Element $node, &$handle = null, $eldnah = null)
    {
        switch ($node->getId()) {
            case Nodes::QUERY_LANGUAGE:
                return $this->visitQueryLanguage($node, $handle, $eldnah);

            case Nodes::SELECT_STATEMENT:
                return $this->visitSelectStatement($node, $handle, $eldnah);

            case Nodes::UPDATE_STATEMENT:
                return $this->visitUpdateStatement($node, $handle, $eldnah);

            case Nodes::DELETE_STATEMENT:
                return $this->visitDeleteStatement($node, $handle, $eldnah);

            case Nodes::SELECT_CLAUSE:
                return $this->visitSelectClause($node, $handle, $eldnah);

            case Nodes::SIMPLE_SELECT_CLAUSE:
                return $this->visitSimpleSelectClause($node, $handle, $eldnah);

            case Nodes::UPDATE_CLAUSE:
                return $this->visitUpdateClause($node, $handle, $eldnah);

            case Nodes::DELETE_CLAUSE:
                return $this->visitDeleteClause($node, $handle, $eldnah);

            case Nodes::FROM_CLAUSE:
                return $this->visitFromClause($node, $handle, $eldnah);

            case Nodes::SUBSELECT_FROM_CLAUSE:
                return $this->visitSubselectFromClause($node, $handle, $eldnah);

            case Nodes::WHERE_CLAUSE:
                return $this->visitWhereClause($node, $handle, $eldnah);

            case Nodes::HAVING_CLAUSE:
                return $this->visitHavingClause($node, $handle, $eldnah);

            case Nodes::GROUP_BY_CLAUSE:
                return $this->visitGroupByClause($node, $handle, $eldnah);

            case Nodes::ORDER_BY_CLAUSE:
                return $this->visitOrderByClause($node, $handle, $eldnah);

            case Nodes::SUBSELECT:
                return $this->visitSubselect($node, $handle, $eldnah);

            case Nodes::UPDATE_ITEM:
                return $this->visitUpdateItem($node, $handle, $eldnah);

            case Nodes::ORDER_BY_ITEM:
                return $this->visitOrderByItem($node, $handle, $eldnah);

            case Nodes::NEW_VALUE:
                return $this->visitNewValue($node, $handle, $eldnah);

            case Nodes::IDENTIFICATION_VARIABLE_DECLARATION:
                return $this->visitIdentificationVariableDeclaration($node, $handle, $eldnah);

            case Nodes::SUBSELECT_IDENTIFICATION_VARIABLE_DECLARATION:
                return $this->visitSubselectIdentificationVariableDeclaration($node, $handle, $eldnah);

            case Nodes::RANGE_VARIABLE_DECLARATION:
                return $this->visitRangeVariableDeclaration($node, $handle, $eldnah);

            case Nodes::JOIN_ASSOCIATION_DECLARATION:
                return $this->visitJoinAssociationDeclaration($node, $handle, $eldnah);

            case Nodes::JOIN:
                return $this->visitJoin($node, $handle, $eldnah);

            case Nodes::INDEX_BY:
                return $this->visitIndexBy($node, $handle, $eldnah);

            case Nodes::SELECT_EXPRESSION:
                return $this->visitSelectExpression($node, $handle, $eldnah);

            case Nodes::SIMPLE_SELECT_EXPRESSION:
                return $this->visitSimpleSelectExpression($node, $handle, $eldnah);

            case Nodes::PARTIAL_OBJECT_EXPRESSION:
                return $this->visitPartialObjectExpression($node, $handle, $eldnah);

            case Nodes::PARTIAL_FIELD_SET:
                return $this->visitPartialFieldSet($node, $handle, $eldnah);

            case Nodes::NEW_OBJECT_EXPRESSION:
                return $this->visitNewObjectExpression($node, $handle, $eldnah);

            case Nodes::CONDITIONAL_EXPRESSION:
                return $this->visitConditionalExpression($node, $handle, $eldnah);

            case Nodes::CONDITIONAL_TERM:
                return $this->visitConditionalTerm($node, $handle, $eldnah);

            case Nodes::CONDITIONAL_FACTOR:
                return $this->visitConditionalFactor($node, $handle, $eldnah);

            case Nodes::CONDITIONAL_PRIMARY:
                return $this->visitConditionalPrimary($node, $handle, $eldnah);

            case Nodes::SIMPLE_CONDITIONAL_EXPRESSION:
                return $this->visitSimpleConditionalExpression($node, $handle, $eldnah);

            case Nodes::EMPTY_COLLECTION_COMPARISON_EXPRESSION:
                return $this->visitEmptyCollectionComparisonExpression($node, $handle, $eldnah);

            case Nodes::COLLECTION_MEMBER_EXPRESSION:
                return $this->visitCollectionMemberExpression($node, $handle, $eldnah);

            case Nodes::INPUT_PARAMETER:
                return $this->visitInputParameter($node, $handle, $eldnah);

            case Nodes::POSITIONAL_PARAMETER:
                return $this->visitPositionalParameter($node, $handle, $eldnah);

            case Nodes::NAMED_PARAMETER:
                return $this->visitNamedParameter($node, $handle, $eldnah);

            case Nodes::ARITHMETIC_EXPRESSION:
                return $this->visitArithmeticExpression($node, $handle, $eldnah);

            case Nodes::SIMPLE_ARITHMETIC_EXPRESSION:
                return $this->visitSimpleArithmeticExpression($node, $handle, $eldnah);

            case Nodes::ARITHMETIC_TERM:
                return $this->visitArithmeticTerm($node, $handle, $eldnah);

            case Nodes::ARITHMETIC_FACTOR:
                return $this->visitArithmeticFactor($node, $handle, $eldnah);

            case Nodes::STRING_PRIMARY:
                return $this->visitStringPrimary($node, $handle, $eldnah);

            case Nodes::AGGREGATE_EXPRESSION:
                return $this->visitAggregateExpression($node, $handle, $eldnah);

            case Nodes::GENERAL_CASE_EXPRESSION:
                return $this->visitGeneralCaseExpression($node, $handle, $eldnah);

            case Nodes::WHEN_CLAUSE:
                return $this->visitWhenClause($node, $handle, $eldnah);

            case Nodes::SIMPLE_CASE_EXPRESSION:
                return $this->visitSimpleCaseExpression($node, $handle, $eldnah);

            case Nodes::SIMPLE_WHEN_CLAUSE:
                return $this->visitSimpleWhenClause($node, $handle, $eldnah);

            case Nodes::COALESCE_EXPRESSION:
                return $this->visitCoalesceExpression($node, $handle, $eldnah);

            case Nodes::NULL_IF_EXPRESSION:
                return $this->visitNullIfExpression($node, $handle, $eldnah);

            case Nodes::QUANTIFIED_EXPRESSION:
                return $this->visitQuantifiedExpression($node, $handle, $eldnah);

            case Nodes::BETWEEN_EXPRESSION:
                return $this->visitBetweenExpression($node, $handle, $eldnah);

            case Nodes::COMPARISON_EXPRESSION:
                return $this->visitComparisonExpression($node, $handle, $eldnah);

            case Nodes::IN_EXPRESSION:
                return $this->visitInExpression($node, $handle, $eldnah);

            case Nodes::INSTANCE_OF_EXPRESSION:
                return $this->visitInstanceOfExpression($node, $handle, $eldnah);

            case Nodes::INSTANCE_OF_PARAMETER:
                return $this->visitInstanceOfParameter($node, $handle, $eldnah);

            case Nodes::LIKE_EXPRESSION:
                return $this->visitLikeExpression($node, $handle, $eldnah);

            case Nodes::NULL_COMPARISON_EXPRESSION:
                return $this->visitNullComparisonExpression($node, $handle, $eldnah);

            case Nodes::EXISTS_EXPRESSION:
                return $this->visitExistsExpression($node, $handle, $eldnah);

            case Nodes::JOIN_ASSOCIATION_PATH_EXPRESSION:
                return $this->visitJoinAssociationPathExpression($node, $handle, $eldnah);

            case Nodes::SINGLE_VALUED_PATH_EXPRESSION:
                return $this->visitSingleValuedPathExpression($node, $handle, $eldnah);

            case Nodes::STATE_FIELD_PATH_EXPRESSION:
                return $this->visitStateFieldPathExpression($node, $handle, $eldnah);

            case Nodes::SINGLE_VALUED_ASSOCIATION_PATH_EXPRESSION:
                return $this->visitSingleValuedAssociationPathExpression($node, $handle, $eldnah);

            case Nodes::COLLECTION_VALUED_PATH_EXPRESSION:
                return $this->visitCollectionValuedPathExpression($node, $handle, $eldnah);

            case Nodes::STATE_FIELD:
                return $this->visitStateField($node, $handle, $eldnah);

            case Nodes::FUNCTIONS_RETURNING_NUMERICS:
                return $this->visitFunctionsReturningNumerics($node, $handle, $eldnah);

            case Nodes::FUNCTIONS_RETURNING_DATE_TIME:
                return $this->visitFunctionsReturningDateTime($node, $handle, $eldnah);

            case Nodes::FUNCTIONS_RETURNING_STRINGS:
                return $this->visitFunctionsReturningStrings($node, $handle, $eldnah);


            case Nodes::GROUP_BY_ITEM:
            case Nodes::IN_PARAMETER:
            case Nodes::NEW_OBJECT_ARG:
            case Nodes::ARITHMETIC_PRIMARY:
            case Nodes::SCALAR_EXPRESSION:
            case Nodes::STRING_EXPRESSION:
            case Nodes::BOOLEAN_EXPRESSION:
            case Nodes::BOOLEAN_PRIMARY:
            case Nodes::ENTITY_EXPRESSION:
            case Nodes::SIMPLE_ENTITY_EXPRESSION:
            case Nodes::DATETIME_EXPRESSION:
            case Nodes::DATE_TIME_PRIMARY:
            case Nodes::COLLECTION_VALUED_ASSOCIATION_FIELD:
            case Nodes::SINGLE_VALUED_ASSOCIATION_FIELD:
            case Nodes::EMBEDDED_CLASS_STATE_FIELD:
            case Nodes::SIMPLE_STATE_FIELD:
            case Nodes::ASSOCIATION_PATH_EXPRESSION:
            case Nodes::CASE_EXPRESSION:
            case Nodes::CASE_OPERAND:
            case Nodes::FUNCTION_DECLARATION:
                return $this->visitGenericNode($node, $handle, $eldnah);

            case Nodes::COMPARISON_OPERATOR:
            case Nodes::IDENTIFICATION_VARIABLE:
            case Nodes::ALIAS_IDENTIFICATION_VARIABLE:
            case Nodes::ABSTRACT_SCHEMA_NAME:
            case Nodes::ALIAS_RESULT_VARIABLE:
            case Nodes::RESULT_VARIABLE:
            case Nodes::FIELD_IDENTIFICATION_VARIABLE:
            case Nodes::LITERAL:
                return $this->visitGenericToken($node, $handle, $eldnah);
            default:
                assert(false, 'Node #' . $node->getId() . ' not supported.');
        }
    }

    /**
     * QueryLanguage ::= SelectStatement | UpdateStatement | DeleteStatement
     *
     * @return SelectStatement|UpdateStatement|DeleteStatement
     */
    public function visitQueryLanguage(TreeNode $node, &$handle, $eldnah)
    {
        $statementNode = $node->getChild(0);
        assert(in_array($statementNode->getId(), NodeTypes::STATEMENTS, true));

        return $statementNode->accept($this, $handle, $eldnah);
    }

    /**
     * SelectStatement ::= SelectClause FromClause [WhereClause] [GroupByClause] [HavingClause] [OrderByClause]
     */
    public function visitSelectStatement(TreeNode $node, &$handle, $eldnah) : SelectStatement
    {
        $selectClauseNode  = $node->getChild(0);
        $fromClauseNode    = $node->getChild(1);
        $whereClauseNode   = $node->getChild(2);
        $groupByClauseNode = $node->getChild(3);
        $havingClauseNode  = $node->getChild(4);
        $orderByClauseNode = $node->getChild(5);

        $selectStatement = new SelectStatement(
            $selectClauseNode->accept($this, $handle, $eldnah),
            $fromClauseNode->accept($this, $handle, $eldnah)
        );

        $selectStatement->whereClause   = $whereClauseNode !== null ? $whereClauseNode->accept($this, $handle, $eldnah) : null;
        $selectStatement->groupByClause = $groupByClauseNode !== null ? $groupByClauseNode->accept($this, $handle, $eldnah) : null;
        $selectStatement->havingClause  = $handle ? $havingClauseNode->accept($this, $handle, $eldnah) : null;
        $selectStatement->orderByClause = $orderByClauseNode ? $orderByClauseNode->accept($this, $handle, $eldnah) : null;

        return $selectStatement;
    }

    /**
     * UpdateStatement ::= UpdateClause [WhereClause]
     */
    public function visitUpdateStatement(TreeNode $node, &$handle, $eldnah) : UpdateStatement
    {
        $updateClauseNode = $node->getChild(0);
        $whereClauseNode  = $node->getChild(1);

        $updateStatement = new UpdateStatement($updateClauseNode->accept($this, $handle, $eldnah));

        $updateStatement->whereClause = $whereClauseNode !== null ? $whereClauseNode->accept($this, $handle, $eldnah) : null;

        return $updateStatement;
    }

    public function visitDeleteStatement(TreeNode $node, &$handle, $eldnah) : DeleteStatement
    {
        $deleteClauseNode = $node->getChild(0);
        $whereClauseNode  = $node->getChild(1);

        $deleteStatement = new DeleteStatement($deleteClauseNode->accept($this, $handle, $eldnah));

        $deleteStatement->whereClause = $whereClauseNode !== null ? $whereClauseNode->accept($this, $handle, $eldnah) : null;

        return $deleteStatement;
    }

    /**
     * DeleteClause ::= "DELETE" ["FROM"] AbstractSchemaName ["AS"] AliasIdentificationVariable
     */
    public function visitDeleteClause(TreeNode $node, &$handle, $eldnah) : DeleteClause
    {
        $abstractSchemaNameNode          = $node->getChild(0);
        $aliasIdentificationVariableNode = $node->getChild(1);

        $clause = new DeleteClause($abstractSchemaNameNode->getChild(0)->getValueValue());

        $clause->aliasIdentificationVariable = $aliasIdentificationVariableNode !== null
            ? $aliasIdentificationVariableNode->getChild(0)->getValueValue()
            : null;

        return $clause;
    }

    /**
     * WhereClause ::= "WHERE" ConditionalExpression
     */
    public function visitWhereClause(TreeNode $node, &$handle, $eldnah) : WhereClause
    {
        $conditionalExpressionNode = $node->getChild(0);

        return new WhereClause($conditionalExpressionNode->accept($this, $handle, $eldnah));
    }

    /**
     * ConditionalExpression ::= ConditionalTerm {"OR" ConditionalTerm}*
     */
    public function visitConditionalExpression(TreeNode $node, &$handle, $eldnah) : ConditionalExpression
    {
        /** @var TreeNode[] $conditionalTermNodes */
        $conditionalTermNodes = $node->getChildren();

        $conditionalTerms = [];
        foreach ($conditionalTermNodes as $conditionalTermNode) {
            $conditionalTerms[] = $conditionalTermNode->accept($this, $handle, $eldnah);
        }

        return new ConditionalExpression($conditionalTerms);
    }

    /**
     * ConditionalTerm ::= ConditionalFactor {"AND" ConditionalFactor}*
     */
    public function visitConditionalTerm(TreeNode $node, &$handle, $eldnah) : ConditionalTerm
    {
        /** @var TreeNode[] $conditionalFactorNodes */
        $conditionalFactorNodes = $node->getChildren();

        $conditionalFactors = [];
        foreach ($conditionalFactorNodes as $conditionalFactorNode) {
            $conditionalFactors[] = $conditionalFactorNode->accept($this, $handle, $eldnah);
        }

        return new ConditionalTerm($conditionalFactors);
    }

    /**
     * ConditionalFactor ::= ["NOT"] ConditionalPrimary
     */
    public function visitConditionalFactor(TreeNode $node, &$handle, $eldnah) : ConditionalFactor
    {
        $negated = $node->getChild(0)->isToken() && $node->getChild(0)->getValueToken() === Tokens::T_NOT;

        $conditionalFactorNode = $node->getChild($negated ? 1 : 0);

        $conditionalFactor      = new ConditionalFactor($conditionalFactorNode->accept($this, $handle, $eldnah));
        $conditionalFactor->not = $negated;

        return $conditionalFactor;
    }

    /**
     * ConditionalPrimary ::= SimpleConditionalExpression | "(" ConditionalExpression ")"
     */
    public function visitConditionalPrimary(TreeNode $node, &$handle, $eldnah) : ConditionalPrimary
    {
        $conditionalExpressionNode = $node->getChild(0);

        $conditionalPrimary    = new ConditionalPrimary();
        $conditionalExpression = $conditionalExpressionNode->accept($this, $handle, $eldnah);

        if ($conditionalExpressionNode->getId() === Nodes::SIMPLE_CONDITIONAL_EXPRESSION) {
            $conditionalPrimary->simpleConditionalExpression = $conditionalExpression;
        }
        if ($conditionalExpressionNode->getId() === Nodes::CONDITIONAL_EXPRESSION) {
            $conditionalPrimary->conditionalExpression = $conditionalExpression;
        }

        return $conditionalPrimary;
    }

    /**
     * SimpleConditionalExpression ::=
     *     ComparisonExpression | BetweenExpression | LikeExpression |
     *     InExpression | NullComparisonExpression | ExistsExpression |
     *     EmptyCollectionComparisonExpression | CollectionMemberExpression |
     *     InstanceOfExpression
     */
    public function visitSimpleConditionalExpression(TreeNode $node, &$handle, $eldnah) : Node
    {
        $expressionNode = $node->getChild(0);

        return $expressionNode->accept($this, $handle, $eldnah);
    }

    /**
     * NullComparisonExpression ::= (
     *     InputParameter | NullIfExpression | CoalesceExpression |
     *     AggregateExpression | FunctionDeclaration | IdentificationVariable |
     *     SingleValuedPathExpression | ResultVariable
     * ) "IS" ["NOT"] "NULL"
     */
    public function visitNullComparisonExpression(TreeNode $node, &$handle, $eldnah) : NullComparisonExpression
    {
        $expressionNode = $node->getChild(0);
        $negated        = $node->getChild(1);

        $expression = $expressionNode->accept($this, $handle, $eldnah);

        $nullComparisonExpression      = new NullComparisonExpression($expression);
        $nullComparisonExpression->not = $negated !== null;

        return $nullComparisonExpression;
    }

    /**
     * SingleValuedPathExpression ::= StateFieldPathExpression | SingleValuedAssociationPathExpression
     */
    public function visitSingleValuedPathExpression(TreeNode $node, &$handle, $eldnah) : PathExpression
    {
        $pathExpressionNode = $node->getChild(0);

        // TODO specialize path visitors?

        [$identificationVariable, $field] = $pathExpressionNode->accept($this, $handle, $eldnah);

        return new PathExpression(
            PathExpression::TYPE_STATE_FIELD | PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION,
            $identificationVariable,
            $field
        );
    }

    /**
     * StateFieldPathExpression ::= IdentificationVariable "." StateField
     */
    public function visitStateFieldPathExpression(TreeNode $node, &$handle, $eldnah) : array
    {
        $identificationVariableNode = $node->getChild(0);
        $stateFieldNode = $node->getChild(1);

        $identificationVariable = $identificationVariableNode->accept($this, $handle, $eldnah);
        $stateField = $stateFieldNode->accept($this, $handle, $eldnah);

        // TODO don't return array
        return [$identificationVariable, $stateField];
    }

    /**
     * StateField ::= {EmbeddedClassStateField "."}* SimpleStateField
     */
    public function visitStateField(TreeNode $node, &$handle, $eldnah) : string
    {
        /** @var TreeNode[] $embeddedClassStateFieldNodes */
        $embeddedClassStateFieldNodes = array_slice($node->getChildren(), 0, -1);
        $simpleStateFieldNode         = $node->getChild($node->getChildrenNumber() - 1);

        $embeddedClassStateFields = [];
        foreach ($embeddedClassStateFieldNodes as $embeddedClassStateFieldNode) {
            $embeddedClassStateFields[] = $embeddedClassStateFieldNode->accept($this, $handle, $eldnah);
        }

        $simpleStateField = $simpleStateFieldNode->accept($this, $handle, $eldnah);

        // TODO don't return string
        return ltrim(implode('.', $embeddedClassStateFields) . '.' . $simpleStateField, '.');
    }

    /**
     * BetweenExpression ::= ArithmeticExpression ["NOT"] "BETWEEN" ArithmeticExpression "AND" ArithmeticExpression
     */
    public function visitBetweenExpression(TreeNode $node, &$handle, $eldnah) : BetweenExpression
    {
        $arithmeticExpressionNode    = $node->getChild(0);
        $notNode                     = $node->getChildrenNumber() === 4 ? $node->getChild(1) : null;
        $leftBoundaryExpressionNode  = $node->getChild($notNode !== null ? 2 : 1);
        $rightBoundaryExpressionNode = $node->getChild($notNode !== null ? 3 : 2);

        $arithmeticExpression    = $arithmeticExpressionNode->accept($this, $handle, $eldnah);
        $leftBoundaryExpression  = $leftBoundaryExpressionNode->accept($this, $handle, $eldnah);
        $rightBoundaryExpression = $rightBoundaryExpressionNode->accept($this, $handle, $eldnah);

        $betweenExpression = new BetweenExpression(
            $arithmeticExpression,
            $leftBoundaryExpression,
            $rightBoundaryExpression
        );
        $betweenExpression->not = $notNode !== null;

        return $betweenExpression;
    }

    /**
     * ArithmeticExpression ::= SimpleArithmeticExpression | "(" Subselect ")"
     */
    public function visitArithmeticExpression(TreeNode $node, &$handle, $eldnah) : ArithmeticExpression
    {
        $childNode = $node->getChild(0);

        $child = $childNode->accept($this, $handle, $eldnah);

        $arithmeticExpression = new ArithmeticExpression();

        if ($childNode->getId() === Nodes::SIMPLE_ARITHMETIC_EXPRESSION) {
            $arithmeticExpression->simpleArithmeticExpression = $child;
        }
        if ($childNode->getId() === Nodes::SUBSELECT) {
            $arithmeticExpression->subselect = $child;
        }

        return $arithmeticExpression;
    }

    /**
     * SimpleArithmeticExpression ::= ArithmeticTerm {("+" | "-") ArithmeticTerm}*
     */
    public function visitSimpleArithmeticExpression(TreeNode $node, &$handle, $eldnah) : SimpleArithmeticExpression
    {
        /** @var TreeNode[] $arithmeticTermNodes */
        $arithmeticTermNodes      = $node->getChildren();
        $rootArithmeticFactorNode = $arithmeticTermNodes[0];

        $arithmeticTerms = [$rootArithmeticFactorNode->accept($this, $handle, $eldnah)];
        foreach (array_chunk(array_slice($arithmeticTermNodes, 1), 2) as [$operatorToken, $secondaryArithmeticTermNode]) {
            /** @var TreeNode $operatorToken */
            /** @var TreeNode $secondaryArithmeticTermNode */

            $arithmeticTerms[] = $operatorToken->getValueValue();
            $arithmeticTerms[] = $secondaryArithmeticTermNode->accept($this, $handle, $eldnah);
        }

        return new SimpleArithmeticExpression($arithmeticTerms);
    }

    /**
     * ArithmeticTerm ::= ArithmeticFactor {("*" | "/") ArithmeticFactor}*
     */
    public function visitArithmeticTerm(TreeNode $node, &$handle, $eldnah) : ArithmeticTerm
    {
        /** @var TreeNode[] $arithmeticFactorNodes */
        $arithmeticFactorNodes    = $node->getChildren();
        $rootArithmeticFactorNode = $arithmeticFactorNodes[0];

        $arithmeticFactors = [$rootArithmeticFactorNode->accept($this, $handle, $eldnah)];
        foreach (array_chunk(array_slice($arithmeticFactorNodes, 1), 2) as [$operationToken, $arithmeticFactorNode]) {
            /** @var TreeNode $operationToken */
            /** @var TreeNode $arithmeticFactorNode */
            $arithmeticFactors[] = $operationToken->getValueValue();
            $arithmeticFactors[] = $arithmeticFactorNode->accept($this, $handle, $eldnah);
        }

        return new ArithmeticTerm($arithmeticFactors);
    }

    /**
     * ArithmeticFactor ::= [("+" | "-")] ArithmeticPrimary
     */
    public function visitArithmeticFactor(TreeNode $node, &$handle, $eldnah) : ArithmeticFactor
    {
        $signToken             = $node->getChildrenNumber() === 2 ? $node->getChild(0) : null;
        $arithmeticPrimaryNode = $signToken !== null ? $node->getChild(1) : $node->getChild(0);

        $sign = $signToken !== null ? $signToken->getValueValue() : null;
        $arithmeticPrimary = $arithmeticPrimaryNode->accept($this, $handle, $eldnah);

        return new ArithmeticFactor($arithmeticPrimary, $sign);
    }

    /**
     * SelectClause ::= "SELECT" ["DISTINCT"] SelectExpression {"," SelectExpression}*
     */
    public function visitSelectClause(TreeNode $node, &$handle, $eldnah) : SelectClause
    {
        /** @var TreeNode[] $childrenNodes */
        $childrenNodes = $node->getChildren();

        $isDistinct = $childrenNodes[0]->getValueToken() === Tokens::T_DISTINCT;
        if ($isDistinct) {
            array_shift($childrenNodes);
        }


        $selectClauses = [];
        foreach ($childrenNodes as $selectClauseNode) {
            $selectClauses[] = $selectClauseNode->accept($this, $handle, $eldnah);
        }

        return new SelectClause($selectClauses, $isDistinct);
    }

    /**
     * SelectExpression ::= (
     *     IdentificationVariable | ScalarExpression | AggregateExpression
     *     FunctionDeclaration | PartialObjectExpression | "(" Subselect ")"
     *     CaseExpression | NewObjectExpression
     * ) [["AS"] ["HIDDEN"] AliasResultVariable]
     */
    public function visitSelectExpression(TreeNode $node, &$handle, $eldnah) : SelectExpression
    {
        $childrenNumber      = $node->getChildrenNumber();
        $childExpressionNode = $node->getChild(0);
        $isHidden            = $childrenNumber > 2 && $node->getChild(1)->getValueToken() === Tokens::T_HIDDEN;
        $aliasVariableNode   = $childrenNumber > 1 ? $node->getChild($childrenNumber - 1) : null;

        return new SelectExpression(
            $childExpressionNode->accept($this, $handle, $eldnah),
            $aliasVariableNode !== null ? $aliasVariableNode->accept($this, $handle, $eldnah) : null,
            $isHidden
        );
    }

    /**
     * FromClause ::= "FROM" IdentificationVariableDeclaration {"," IdentificationVariableDeclaration}*
     */
    public function visitFromClause(TreeNode $node, &$handle, $eldnah) : FromClause
    {
        /** @var TreeNode[] $identificationVariableDeclarationNodes */
        $identificationVariableDeclarationNodes = $node->getChildren();

        $fromClauses = [];
        foreach ($identificationVariableDeclarationNodes as $identificationVariableDeclarationNode) {
            $fromClauses[] = $identificationVariableDeclarationNode->accept($this, $handle, $eldnah);
        }

        return new FromClause($fromClauses);
    }

    /**
     * IdentificationVariableDeclaration ::= RangeVariableDeclaration [IndexBy] {Join}*
     */
    public function visitIdentificationVariableDeclaration(
        TreeNode $node,
        &$handle,
        $eldnah
    ) : IdentificationVariableDeclaration {
        $rangeVariableDeclarationNode = $node->getChild(0);
        $indexByNode                  = $node->childExists(1) && $node->getChild(1)->getId() === Nodes::INDEX_BY
            ? $node->getChild(1)
            : null;
        /** @var TreeNode[] $joinNodes */
        $joinNodes = array_slice($node->getChildren(), $indexByNode !== null ? 2 : 1);

        $joins = [];
        foreach ($joinNodes as $joinNode) {
            $joins[] = $joinNode->accept($this, $handle, $eldnah);
        }

        return new IdentificationVariableDeclaration(
            $rangeVariableDeclarationNode->accept($this, $handle, $eldnah),
            $indexByNode !== null ? $indexByNode->accept($this, $handle, $eldnah) : null,
            $joins
        );
    }

    /**
     * RangeVariableDeclaration ::= AbstractSchemaName ["AS"] AliasIdentificationVariable
     */
    public function visitRangeVariableDeclaration(TreeNode $node, &$handle, $eldnah) : RangeVariableDeclaration
    {
        $abstractSchemaNameNode          = $node->getChild(0);
        $aliasIdentificationVariableNode = $node->getChild(1);

        return new RangeVariableDeclaration(
            $abstractSchemaNameNode->accept($this, $handle, $eldnah),
            $aliasIdentificationVariableNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * ComparisonExpression ::= ArithmeticExpression ComparisonOperator ( QuantifiedExpression | ArithmeticExpression )
     */
    public function visitComparisonExpression(TreeNode $node, &$handle, $eldnah) : ComparisonExpression
    {
        $leftExpressionNode  = $node->getChild(0);
        $operatorToken       = $node->getChild(1);
        $rightExpressionNode = $node->getChild(2);

        return new ComparisonExpression(
            $leftExpressionNode->accept($this, $handle, $eldnah),
            $operatorToken->getValueValue(),
            $rightExpressionNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * InputParameter ::= PositionalParameter | NamedParameter
     */
    public function visitInputParameter(TreeNode $node, &$handle, $eldnah) : InputParameter
    {
        return new InputParameter($node->getChild(0)->accept($this, $handle, $eldnah));
    }

    /**
     * PositionalParameter ::= "?" integer
     */
    public function visitPositionalParameter(TreeNode $node, &$handle, $eldnah) : string
    {
        // TODO specialize InputParameter AST node
        return '?' . $node->getChild(0)->getValueValue();
    }

    /**
     * NamedParameter ::= ":" string
     */
    public function visitNamedParameter(TreeNode $node, &$handle, $eldnah) : string
    {
        // TODO specialize InputParameter AST node
        return ':' . $node->getChild(0)->getValueValue();
    }

    /**
     * Join ::= ["LEFT" ["OUTER"] | "INNER"] "JOIN"
     *     (JoinAssociationDeclaration | RangeVariableDeclaration)
     *     ["WITH" ConditionalExpression]
     */
    public function visitJoin(TreeNode $node, &$handle, $eldnah) : Join
    {
        $joinTypeToken             = $node->getChildrenNumber() > 1 && $node->getChild(0)->isToken()
            ? $node->getChild(0)
            : null;
        $joinTargetNode            = $node->getChild($joinTypeToken !== null ? 1 : 0);
        $conditionalExpressionNode = $node->getChildrenNumber() > 1
            && $node->getChild($node->getChildrenNumber() - 1)
            && $node->getChild($node->getChildrenNumber() - 1)->getId() === Nodes::CONDITIONAL_EXPRESSION
                ? $node->getChild($node->getChildrenNumber() - 1)
                : null;

        $join = new Join(
            $joinTypeToken !== null ? $joinTypeToken->getValueValue() : null,
            $joinTargetNode->accept($this, $handle, $eldnah)
        );

        if ($conditionalExpressionNode !== null) {
            $join->conditionalExpression = $conditionalExpressionNode->accept($this, $handle, $eldnah);
        }

        return $join;
    }

    /**
     * Subselect ::= SimpleSelectClause SubselectFromClause [WhereClause] [GroupByClause] [HavingClause] [OrderByClause]
     */
    public function visitSubselect(TreeNode $node, &$handle, $eldnah) : Subselect
    {
        $simpleSelectClauseNode  = $node->getChild(0);
        $subselectFromClauseNode = $node->getChild(0);

        /** @var TreeNode[] $optionalClauseNodes */
        $optionalClauseNodes = array_slice($node->getChildren(), 2);
        $whereClauseNode = $groupByClauseNode = $havingClauseNode = $orderByClauseNode = null;

        if (count($optionalClauseNodes) !== 0 && $optionalClauseNodes[0]->getId() === Nodes::WHERE_CLAUSE) {
            $whereClauseNode = array_shift($optionalClauseNodes);
        }
        if (count($optionalClauseNodes) !== 0 && $optionalClauseNodes[0]->getId() === Nodes::GROUP_BY_CLAUSE) {
            $groupByClauseNode = array_shift($optionalClauseNodes);
        }
        if (count($optionalClauseNodes) !== 0 && $optionalClauseNodes[0]->getId() === Nodes::HAVING_CLAUSE) {
            $havingClauseNode = array_shift($optionalClauseNodes);
        }
        if (count($optionalClauseNodes) !== 0 && $optionalClauseNodes[0]->getId() === Nodes::ORDER_BY_CLAUSE) {
            $orderByClauseNode = array_shift($optionalClauseNodes);
        }

        $subselect = new Subselect(
            $simpleSelectClauseNode->accept($this, $handle, $eldnah),
            $subselectFromClauseNode->accept($this, $handle, $eldnah)
        );

        if ($whereClauseNode !== null) {
            $subselect->whereClause = $whereClauseNode->accept($this, $handle, $eldnah);
        }
        if ($groupByClauseNode !== null) {
            $subselect->groupByClause = $groupByClauseNode->accept($this, $handle, $eldnah);
        }
        if ($havingClauseNode !== null) {
            $subselect->havingClause = $havingClauseNode->accept($this, $handle, $eldnah);
        }
        if ($orderByClauseNode !== null) {
            $subselect->orderByClause = $orderByClauseNode->accept($this, $handle, $eldnah);
        }

        return $subselect;
    }

    /**
     * SimpleSelectClause ::= "SELECT" ["DISTINCT"] SimpleSelectExpression
     */
    public function visitSimpleSelectClause(TreeNode $node, &$handle, $eldnah) : SimpleSelectClause
    {
        $isDistinct             = $node->getChild(0)->isToken() && $node->getChild(0)->getValueToken() === Tokens::T_DISTINCT;
        $simpleSelectExpression = $node->getChild($isDistinct ? 1 : 0);

        return new SimpleSelectClause(
            $simpleSelectExpression->accept($this, $handle, $eldnah),
            $isDistinct
        );
    }

    /**
     * SimpleSelectExpression ::= (
     *     StateFieldPathExpression | IdentificationVariable | FunctionDeclaration
     *     | AggregateExpression | "(" Subselect ")" | ScalarExpression
     * ) [["AS"] AliasResultVariable]
     */
    public function visitSimpleSelectExpression(TreeNode $node, &$handle, $eldnah) : SimpleSelectExpression
    {
        $selectExpressionNode    = $node->getChild(0);
        $aliasResultVariableNode = $node->getChild(1);

        $simpleSelectExpression = new SimpleSelectExpression($selectExpressionNode->accept($this, $handle, $eldnah));

        if ($aliasResultVariableNode !== null) {
            $simpleSelectExpression->fieldIdentificationVariable = $aliasResultVariableNode->accept($this, $handle, $eldnah);
        }

        return $simpleSelectExpression;
    }

    /**
     * AggregateExpression ::= ("AVG" | "MAX" | "MIN" | "SUM" | "COUNT") "(" ["DISTINCT"] SimpleArithmeticExpression ")"
     */
    public function visitAggregateExpression(TreeNode $node, &$handle, $eldnah) : AggregateExpression
    {
        $isDistinct                     = $node->getChildrenNumber() === 3;
        $functionNameToken              = $node->getChild(0);
        $simpleArithmeticExpressionNode = $node->getChild($isDistinct ? 2 : 1);

        return new AggregateExpression(
            $functionNameToken->getValueValue(),
            $simpleArithmeticExpressionNode->accept($this, $handle, $eldnah),
            $isDistinct
        );
    }

    /**
     * GeneralCaseExpression ::= "CASE" WhenClause {WhenClause}* "ELSE" ScalarExpression "END"
     */
    public function visitGeneralCaseExpression(TreeNode $node, &$handle, $eldnah) : GeneralCaseExpression
    {
        /** @var TreeNode[] $whenClauseNodes */
        $whenClauseNodes = array_slice($node->getChildren(), 0, -1);
        $elseExpressionNode = $node->getChild($node->getChildrenNumber() - 1);

        $whenClauses = [];
        foreach ($whenClauseNodes as $whenClauseNode) {
            $whenClauses[] = $whenClauseNode->accept($this, $handle, $eldnah);
        }

        return new GeneralCaseExpression(
            $whenClauses,
            $elseExpressionNode->accept($this, $handle, $elseExpressionNode)
        );
    }

    /**
     * WhenClause ::= "WHEN" ConditionalExpression "THEN" ScalarExpression
     */
    public function visitWhenClause(TreeNode $node, &$handle, $eldnah) : WhenClause
    {
        $whenExpressionNode = $node->getChild(0);
        $thenExpressionNode = $node->getChild(1);

        return new WhenClause(
            $whenExpressionNode->accept($this, $handle, $eldnah),
            $thenExpressionNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * StringPrimary ::= StateFieldPathExpression | string | InputParameter
     *     | FunctionsReturningStrings | AggregateExpression | CaseExpression
     */
    public function visitStringPrimary(TreeNode $node, &$handle, $eldnah)
    {
        $child = $node->getChild(0);
        return $child->isToken() ? $child->getValueValue() : $child->accept($this, $handle, $eldnah);
    }

    /**
     * ExistsExpression ::= ["NOT"] "EXISTS" "(" Subselect ")"
     */
    public function visitExistsExpression(TreeNode $node, &$handle, $eldnah) : ExistsExpression
    {
        $negated       = $node->getChildrenNumber() === 2;
        $subselectNode = $node->getChild($negated ? 1 : 0);

        $existsExpression      = new ExistsExpression($subselectNode->accept($this, $handle, $eldnah));
        $existsExpression->not = $negated;

        return $existsExpression;
    }

    /**
     * UpdateClause ::= "UPDATE" AbstractSchemaName ["AS"] AliasIdentificationVariable "SET" UpdateItem {"," UpdateItem}*
     */
    public function visitUpdateClause(TreeNode $node, &$handle, $eldnah) : UpdateClause
    {
        $abstractSchemaNameNode          = $node->getChild(0);
        $aliasIdentificationVariableNode = $node->getChild(1);
        /** @var TreeNode[] $updateItemNodes */
        $updateItemNodes                 = array_slice($node->getChildren(), 2);

        $updateItems = [];
        foreach ($updateItemNodes as $updateItemNode) {
            $updateItems[] = $updateItemNode->accept($this, $handle, $eldnah);
        }

        $updateClause = new UpdateClause(
            $abstractSchemaNameNode->accept($this, $handle, $eldnah),
            $updateItems
        );
        $updateClause->aliasIdentificationVariable = $aliasIdentificationVariableNode->accept($this, $handle, $eldnah);

        return $updateClause;
    }

    /**
     * UpdateItem ::= SingleValuedPathExpression "=" NewValue
     */
    public function visitUpdateItem(TreeNode $node, &$handle, $eldnah) : UpdateItem
    {
        $singleValuedPathExpressionNode = $node->getChild(0);
        $newValueNode                   = $node->getChild(1);

        return new UpdateItem(
            $singleValuedPathExpressionNode->accept($this, $handle, $eldnah),
            $newValueNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * TODO 2.x EBNF specifies SimpleArithmeticExpression
     * NewValue ::= ArithmeticExpression | "NULL"
     */
    public function visitNewValue(TreeNode $node, &$handle, $eldnah) : ?ArithmeticExpression
    {
        $valueNode = $node->getChild(0);

        if ($valueNode->isToken() && $valueNode->getValueToken() === Tokens::T_NULL) {
            return null;
        }

        return $valueNode->accept($this, $handle, $eldnah);
    }

    /**
     * SubselectFromClause ::= "FROM" SubselectIdentificationVariableDeclaration {"," SubselectIdentificationVariableDeclaration}*
     */
    public function visitSubselectFromClause(TreeNode $node, &$handle, $eldnah) : SubselectFromClause
    {
        /** @var TreeNode[] $subselectIdentificationVariableDeclarationNodes */
        $subselectIdentificationVariableDeclarationNodes = $node->getChildren();

        $subselectIdentificationVariableDeclarations = [];
        foreach ($subselectIdentificationVariableDeclarationNodes as $subselectIdentificationVariableDeclarationNode) {
            $subselectIdentificationVariableDeclarations[] = $subselectIdentificationVariableDeclarationNode->accept(
                $this,
                $handle,
                $eldnah
            );
        }

        return new SubselectFromClause($subselectIdentificationVariableDeclarations);
    }

    /**
     * HavingClause ::= "HAVING" ConditionalExpression
     */
    public function visitHavingClause(TreeNode $node, &$handle, $eldnah) : HavingClause
    {
        $conditionalExpressionNode = $node->getChild(0);

        return new HavingClause($conditionalExpressionNode->accept($this, $handle, $eldnah));
    }

    /**
     * GroupByClause ::= "GROUP" "BY" GroupByItem {"," GroupByItem}*
     */
    public function visitGroupByClause(TreeNode $node, &$handle, $eldnah) : GroupByClause
    {
        /** @var TreeNode[] $groupByItemNodes */
        $groupByItemNodes = $node->getChildren();

        $groupByItems = [];
        foreach ($groupByItemNodes as $groupByItemNode) {
            $groupByItems[] = $groupByItemNode->accept($this, $handle, $eldnah);
        }

        return new GroupByClause($groupByItems);
    }

    /**
     * OrderByClause ::= "ORDER" "BY" OrderByItem {"," OrderByItem}*
     */
    public function visitOrderByClause(TreeNode $node, &$handle, $eldnah) : OrderByClause
    {
        /** @var TreeNode[] $orderByItemNodes */
        $orderByItemNodes = $node->getChildren();

        $orderByItems = [];
        foreach ($orderByItemNodes as $orderByItemNode) {
            $orderByItems[] = $orderByItemNode->accept($this, $handle, $eldnah);
        }

        return new OrderByClause($orderByItems);
    }

    /**
     * OrderByItem ::= (
     *     SimpleArithmeticExpression | SingleValuedPathExpression
     *     | ScalarExpression | ResultVariable | FunctionDeclaration
     * ) ["ASC" | "DESC"]
     */
    public function visitOrderByItem(TreeNode $node, &$handle, $eldnah) : OrderByItem
    {
        $orderByNode = $node->getChild(0);
        $typeToken   = $node->getChild(1);

        $orderByItem = new OrderByItem($orderByNode->accept($this, $handle, $eldnah));

        if ($typeToken !== null) {
            $orderByItem->type = $typeToken->getValueValue();
        }

        return $orderByItem;
    }

    /**
     * SubselectIdentificationVariableDeclaration ::= IdentificationVariableDeclaration
     */
    public function visitSubselectIdentificationVariableDeclaration(
        TreeNode $node,
        &$handle,
        $eldnah
    ) : IdentificationVariableDeclaration {
        $identificationVariableDeclarationNode = $node->getChild(0);

        /** @var IdentificationVariableDeclaration $identificationVariableDeclaration */
        $identificationVariableDeclaration = $identificationVariableDeclarationNode->accept($this, $handle, $eldnah);

        // TODO this is somewhat nastily hacky, inherited from original parser though
        // return new SubselectIdentificationVariableDeclaration(...);
        return $identificationVariableDeclaration;
    }

    /**
     * JoinAssociationDeclaration ::= JoinAssociationPathExpression ["AS"] AliasIdentificationVariable [IndexBy]
     */
    public function visitJoinAssociationDeclaration(TreeNode $node, &$handle, $eldnah) : JoinAssociationDeclaration
    {
        $joinAssociationPathExpressionNode = $node->getChild(0);
        $aliasIdentificationVariableNode   = $node->getChild(1);
        $indexByNode                       = $node->getChild(2);

        return new JoinAssociationDeclaration(
            $joinAssociationPathExpressionNode->accept($this, $handle, $eldnah),
            $aliasIdentificationVariableNode->accept($this, $handle, $eldnah),
            $indexByNode !== null ? $indexByNode->accept($this, $handle, $eldnah) : null
        );
    }

    /**
     * IndexBy ::= "INDEX" "BY" StateFieldPathExpression
     */
    public function visitIndexBy(TreeNode $node, &$handle, $eldnah) : IndexBy
    {
        $stateFieldPathExpressionNode = $node->getChild(0);

        return new IndexBy($stateFieldPathExpressionNode->accept($this, $handle, $eldnah));
    }

    /**
     * PartialObjectExpression ::= "PARTIAL" IdentificationVariable "." PartialFieldSet
     */
    public function visitPartialObjectExpression(TreeNode $node, &$handle, $eldnah) : PartialObjectExpression
    {
        $identificationVariableNode = $node->getChild(0);
        $partialFieldSetNode        = $node->getChild(1);

        return new PartialObjectExpression(
            $identificationVariableNode->accept($this, $handle, $eldnah),
            $partialFieldSetNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * PartialFieldSet ::= "{" SimpleStateField {"," SimpleStateField}* "}"
     * TODO feels hacky, but we have no AST node for fields
     */
    public function visitPartialFieldSet(TreeNode $node, &$handle, $eldnah) : array
    {
        /** @var TreeNode[] $fieldNodes */
        $fieldNodes = $node->getChildren();

        $fields = [];
        foreach ($fieldNodes as $fieldNode) {
            $fields[] = $fieldNode->accept($this, $handle, $eldnah);
        }

        return $fields;
    }

    /**
     * NewObjectExpression ::= "NEW" AbstractSchemaName "(" NewObjectArg {"," NewObjectArg}* ")"
     */
    public function visitNewObjectExpression(TreeNode $node, &$handle, $eldnah) : NewObjectExpression
    {
        $abstractSchemaNameNode = $node->getChild(0);
        /** @var TreeNode[] $newObjectArgNodes */
        $newObjectArgNodes      = array_slice($node->getChildren(), 1);

        $newObjectArgs = [];
        foreach ($newObjectArgNodes as $newObjectArgNode) {
            $newObjectArgs[] = $newObjectArgNode->accept($this, $handle, $eldnah);
        }

        return new NewObjectExpression(
            $abstractSchemaNameNode->accept($this, $handle, $eldnah),
            $newObjectArgs
        );
    }

    /**
     * EmptyCollectionComparisonExpression ::= CollectionValuedPathExpression "IS" ["NOT"] "EMPTY"
     */
    public function visitEmptyCollectionComparisonExpression(
        TreeNode $node,
        &$handle,
        $eldnah
    ) : EmptyCollectionComparisonExpression {
        $collectionValuedPathExpressionNode = $node->getChild(0);
        $negated                            = $node->childExists(1);

        $emptyCollectionComparisonExpression      = new EmptyCollectionComparisonExpression(
            $collectionValuedPathExpressionNode->accept($this, $handle, $eldnah)
        );
        $emptyCollectionComparisonExpression->not = $negated;

        return $emptyCollectionComparisonExpression;
    }

    /**
     * CollectionMemberExpression ::= EntityExpression ["NOT"] "MEMBER" ["OF"] CollectionValuedPathExpression
     */
    public function visitCollectionMemberExpression(TreeNode $node, &$handle, $eldnah) : CollectionMemberExpression
    {
        $negated                            = $node->getChildrenNumber() === 3;
        $entityExpressionNode               = $node->getChild(0);
        $collectionValuedPathExpressionNode = $node->getChild($negated ? 2 : 1);

        $collectionMemberExpression = new CollectionMemberExpression(
            $entityExpressionNode->accept($this, $handle, $eldnah),
            $collectionValuedPathExpressionNode->accept($this, $handle, $eldnah)
        );
        $collectionMemberExpression->not = $negated;

        return $collectionMemberExpression;
    }

    /**
     * BooleanPrimary ::= StateFieldPathExpression | boolean | InputParameter
     */
    public function visitBooleanPrimary(TreeNode $node, &$handle, $eldnah)
    {
        $primaryNode = $node->getChild(0);

        if ($primaryNode->isToken()) {
            return $primaryNode->getValueValue();
        }

        return $primaryNode->accept($this, $handle, $eldnah);
    }

    /**
     * SimpleCaseExpression ::= "CASE" CaseOperand SimpleWhenClause {SimpleWhenClause}* "ELSE" ScalarExpression "END"
     */
    public function visitSimpleCaseExpression(TreeNode $node, &$handle, $eldnah) : SimpleCaseExpression
    {
        $caseOperandNode       = $node->getChild(0);
        /** @var TreeNode[] $simpleWhenClauseNodes */
        $simpleWhenClauseNodes = array_slice($node->getChildren(), 1, -2);
        $elseExpressionNode    = $node->getChild($node->getChildrenNumber() - 1);

        $simpleWhenClauses = [];
        foreach ($simpleWhenClauseNodes as $simpleWhenClauseNode) {
            $simpleWhenClauses[] = $simpleWhenClauseNode->accept($this, $handle, $eldnah);
        }

        return new SimpleCaseExpression(
            $caseOperandNode->accept($this, $handle, $eldnah),
            $simpleWhenClauses,
            $elseExpressionNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * SimpleWhenClause ::= "WHEN" ScalarExpression "THEN" ScalarExpression
     */
    public function visitSimpleWhenClause(TreeNode $node, &$handle, $eldnah) : SimpleWhenClause
    {
        $whenExpressionNode = $node->getChild(0);
        $thenExpressionNode = $node->getChild(1);

        return new SimpleWhenClause(
            $whenExpressionNode->accept($this, $handle, $eldnah),
            $thenExpressionNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * CoalesceExpression ::= "COALESCE" "(" ScalarExpression {"," ScalarExpression}* ")"
     */
    public function visitCoalesceExpression(TreeNode $node, &$handle, $eldnah) : CoalesceExpression
    {
        /** @var TreeNode[] $expressionNodes */
        $expressionNodes = $node->getChildren();

        $expressions = [];
        foreach ($expressionNodes as $expressionNode) {
            $expressions[] = $expressionNode->accept($this, $handle, $eldnah);
        }

        return new CoalesceExpression($expressions);
    }

    /**
     * NullifExpression ::= "NULLIF" "(" ScalarExpression "," ScalarExpression ")"
     */
    public function visitNullIfExpression(TreeNode $node, &$handle, $eldnah) : NullIfExpression
    {
        $firstExpressionNode  = $node->getChild(0);
        $secondExpressionNode = $node->getChild(1);

        return new NullIfExpression(
            $firstExpressionNode->accept($this, $handle, $eldnah),
            $secondExpressionNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * QuantifiedExpression ::= ("ALL" | "ANY" | "SOME") "(" Subselect ")"
     */
    public function visitQuantifiedExpression(TreeNode $node, &$handle, $eldnah) : QuantifiedExpression
    {
        $typeToken     = $node->getChild(0);
        $subselectNode = $node->getChild(1);

        $quantifiedExpression       = new QuantifiedExpression($subselectNode->accept($this, $handle, $eldnah));
        $quantifiedExpression->type = $typeToken->getValueValue();

        return $quantifiedExpression;
    }

    /**
     * InExpression ::= SingleValuedPathExpression ["NOT"] "IN" "(" (InParameter {"," InParameter}* | Subselect) ")"
     * TODO specialize parameters / subselect
     */
    public function visitInExpression(TreeNode $node, &$handle, $eldnah) : InExpression
    {
        $negated                        = $node->getChild(1)->isToken() && $node->getChild(1)->getValueToken() === Tokens::T_NOT;
        $singleValuedPathExpressionNode = $node->getChild(0);


        $inExpression      = new InExpression($singleValuedPathExpressionNode->accept($this, $handle, $eldnah));
        $inExpression->not = $negated;

        if ($node->getChild($node->getChildrenNumber() - 1)->getId() === Nodes::SUBSELECT) {
            $inExpression->subselect = $node->getChild($node->getChildrenNumber() - 1)->accept($this, $handle, $eldnah);
            return $inExpression;
        }

        /** @var TreeNode[] $inParameterNodes */
        $inParameterNodes = array_slice($node->getChildren(), $negated ? 2 : 1);

        $inParameters = [];
        foreach ($inParameterNodes as $inParameterNode) {
            $inParameters[] = $inParameterNode->accept($this, $handle, $eldnah);
        }

        $inExpression->literals = $inParameters;

        return $inExpression;
    }

    /**
     * InstanceOfExpression ::= IdentificationVariable ["NOT"] "INSTANCE" ["OF"]
     *     (InstanceOfParameter | "(" InstanceOfParameter {"," InstanceOfParameter}* ")")
     */
    public function visitInstanceOfExpression(TreeNode $node, &$handle, $eldnah) : InstanceOfExpression
    {
        $negated                    = $node->getChild(1)->isToken() && $node->getChild(1)->getValueToken() === Tokens::T_NOT;
        $identificationVariableNode = $node->getChild(0);
        /** @var TreeNode[] $instanceOfParameterNodes */
        $instanceOfParameterNodes   = array_slice($node->getChildren(), $negated ? 2 : 1);

        $instanceOfParameters = [];
        foreach ($instanceOfParameterNodes as $instanceOfParameterNode) {
            $instanceOfParameters[] = $instanceOfParameterNode->accept($this, $handle, $eldnah);
        }

        $instanceOfExpression        = new InstanceOfExpression(
            $identificationVariableNode->accept($this, $handle, $eldnah)
        );
        $instanceOfExpression->not   = $negated;
        $instanceOfExpression->value = $instanceOfParameters;

        return $instanceOfExpression;
    }

    /**
     * InstanceOfParameter ::= AbstractSchemaName | InputParameter
     */
    public function visitInstanceOfParameter(TreeNode $node, &$handle, $eldnah)
    {
        return $node->getChild(0)->accept($this, $http_response_header, $eldnah);
    }

    /**
     * LikeExpression ::= StringExpression ["NOT"] "LIKE" StringPrimary ["ESCAPE" char]
     */
    public function visitLikeExpression(TreeNode $node, &$handle, $eldnah) : LikeExpression
    {
        $negated              = $node->getChild(1)->isToken() && $node->getChild(1)->getValueToken() === Tokens::T_NOT;
        $stringExpressionNode = $node->getChild(0);
        $stringPrimaryNode    = $node->getChild($negated ? 2 : 1);
        $escapeCharToken      = $node->getChild($negated ? 3 : 2);

        return new LikeExpression(
            $stringExpressionNode->accept($this, $handle, $eldnah),
            $stringPrimaryNode->accept($this, $handle, $eldnah),
            $escapeCharToken !== null ? $escapeCharToken->getValueValue() : null
        );
    }

    /**
     * JoinAssociationPathExpression ::= IdentificationVariable "." (CollectionValuedAssociationField | SingleValuedAssociationField)
     */
    public function visitJoinAssociationPathExpression(TreeNode $node, &$handle, $eldnah) : JoinAssociationPathExpression
    {
        $identificationVariableNode = $node->getChild(0);
        $associationFieldNode       = $node->getChild(1);

        return new JoinAssociationPathExpression(
            $identificationVariableNode->accept($this, $handle, $eldnah),
            $associationFieldNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * SingleValuedAssociationPathExpression ::= IdentificationVariable "." SingleValuedAssociationField
     */
    public function visitSingleValuedAssociationPathExpression(TreeNode $node, &$handle, $eldnah) : PathExpression
    {
        $identificationVariableNode       = $node->getChild(0);
        $singleValuedAssociationFieldNode = $node->getChild(1);

        return new PathExpression(
            PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION,
            $identificationVariableNode->accept($this, $handle, $eldnah),
            $singleValuedAssociationFieldNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * CollectionValuedPathExpression ::= IdentificationVariable "." CollectionValuedAssociationField
     */
    public function visitCollectionValuedPathExpression(TreeNode $node, &$handle, $eldnah) : PathExpression
    {
        $identificationVariableNode           = $node->getChild(0);
        $collectionValuedAssociationFieldNode = $node->getChild(1);

        return new PathExpression(
            PathExpression::TYPE_COLLECTION_VALUED_ASSOCIATION,
            $identificationVariableNode->accept($this, $handle, $eldnah),
            $collectionValuedAssociationFieldNode->accept($this, $handle, $eldnah)
        );
    }

    /**
     * FunctionsReturningNumerics ::=
     *     | "LENGTH" "(" StringPrimary ")"
     *     | "LOCATE" "(" StringPrimary "," StringPrimary ["," SimpleArithmeticExpression]")"
     *     | "ABS" "(" SimpleArithmeticExpression ")"
     *     | "SQRT" "(" SimpleArithmeticExpression ")"
     *     | "MOD" "(" SimpleArithmeticExpression "," SimpleArithmeticExpression ")"
     *     | "SIZE" "(" CollectionValuedPathExpression ")"
     *     | "DATE_DIFF" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
     *     | "BIT_AND" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
     *     | "BIT_OR" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
     */
    public function visitFunctionsReturningNumerics(TreeNode $node, &$handle, $eldnah) : FunctionNode
    {
        $functionNameToken = $node->getChild(0);
        $functionName      = $functionNameToken->getValueValue();

        switch ($functionNameToken->getValueToken()) {
            case Tokens::T_FUNCTION_LENGTH:
                $lengthParameterNode = $node->getChild(1);

                $function                = new LengthFunction($functionName);
                $function->stringPrimary = $lengthParameterNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_LOCATE:
                $firstStringPrimaryNode         = $node->getChild(1);
                $secondStringPrimaryNode        = $node->getChild(2);
                $simpleArithmeticExpressionNode = $node->getChild(3);

                $function = new LocateFunction($functionName);
                $function->firstStringPrimary = $firstStringPrimaryNode->accept($this, $handle, $eldnah);
                $function->secondStringPrimary = $secondStringPrimaryNode->accept($this, $handle, $eldnah);
                $function->simpleArithmeticExpression = $simpleArithmeticExpressionNode !== null
                    ? $simpleArithmeticExpressionNode->accept($this, $handle, $eldnah)
                    : false;

                return $function;

            case Tokens::T_FUNCTION_ABS:
                $simpleArithmeticExpressionNode = $node->getChild(1);

                $function = new AbsFunction($functionName);
                $function->simpleArithmeticExpression = $simpleArithmeticExpressionNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_SQRT:
                $simpleArithmeticExpressionNode = $node->getChild(1);

                $function = new SqrtFunction($functionName);
                $function->simpleArithmeticExpression = $simpleArithmeticExpressionNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_MOD:
                $firstSimpleArithmeticExpressionNode  = $node->getChild(1);
                $secondSimpleArithmeticExpressionNode = $node->getChild(2);

                $function = new ModFunction($functionName);
                $function->firstSimpleArithmeticExpression  = $firstSimpleArithmeticExpressionNode->accept($this, $handle, $eldnah);
                $function->secondSimpleArithmeticExpression = $secondSimpleArithmeticExpressionNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_SIZE:
                $collectionValuedPathExpressionNode = $node->getChild(1);

                $function = new SizeFunction($functionName);
                $function->collectionPathExpression = $collectionValuedPathExpressionNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_BIT_OR:
                $firstArithmeticPrimaryNode  = $node->getChild(1);
                $secondArithmeticPrimaryNode = $node->getChild(2);

                $function = new BitOrFunction($functionName);
                $function->firstArithmetic  = $firstArithmeticPrimaryNode->accept($this, $handle, $eldnah);
                $function->secondArithmetic = $secondArithmeticPrimaryNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_BIT_AND:
                $firstArithmeticPrimaryNode  = $node->getChild(1);
                $secondArithmeticPrimaryNode = $node->getChild(2);

                $function = new BitAndFunction($functionName);
                $function->firstArithmetic  = $firstArithmeticPrimaryNode->accept($this, $handle, $eldnah);
                $function->secondArithmetic = $secondArithmeticPrimaryNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_DATE_DIFF:
                $firstDateNode  = $node->getChild(1);
                $secondDateNode = $node->getChild(2);

                $function = new DateDiffFunction($functionName);
                $function->date1 = $firstDateNode->accept($this, $handle, $eldnah);
                $function->date2 = $secondDateNode->accept($this, $handle, $eldnah);

                return $function;
        }

        assert(false, 'Unsupported function ' . $functionName . '.');
    }

    /**
     * FunctionsReturningStrings ::=
     *    "CONCAT" "(" StringPrimary "," StringPrimary ")"
     *    | "SUBSTRING" "(" StringPrimary "," SimpleArithmeticExpression "," SimpleArithmeticExpression ")"
     *    | "TRIM" "(" [["LEADING" | "TRAILING" | "BOTH"] [char] "FROM"] StringPrimary ")"
     *    | "LOWER" "(" StringPrimary ")"
     *    | "UPPER" "(" StringPrimary ")"
     *    | "IDENTITY" "(" SingleValuedAssociationPathExpression {"," string} ")"
     */
    public function visitFunctionsReturningStrings(TreeNode $node, &$handle, $eldnah) : FunctionNode
    {
        $functionNameToken = $node->getChild(0);
        $functionName      = $functionNameToken->getValueValue();

        switch ($functionNameToken->getValueToken()) {
            case Tokens::T_FUNCTION_CONCAT:
                /** @var TreeNode[] $stringPrimaryNodes */
                $stringPrimaryNodes = array_slice($node->getChildren(), 1);

                $stringPrimaries = [];
                foreach ($stringPrimaryNodes as $stringPrimaryNode) {
                    $stringPrimaries[] = $stringPrimaryNode->accept($this, $handle, $eldnah);
                }

                $function                      = new ConcatFunction($functionName);
                $function->firstStringPrimary  = $stringPrimaries[0];
                $function->secondStringPrimary = $stringPrimaries[1];
                $function->concatExpressions   = $stringPrimaries;

                return $function;

            case Tokens::T_FUNCTION_SUBSTRING:
                $stringPrimaryNode                    = $node->getChild(1);
                $firstSimpleArithmeticExpressionNode  = $node->getChild(2);
                $secondSimpleArithmeticExpressionNode = $node->getChild(3);

                $function = new SubstringFunction($functionName);
                $function->stringPrimary = $stringPrimaryNode->accept($this, $handle, $eldnah);
                $function->firstSimpleArithmeticExpressionNode = $firstSimpleArithmeticExpressionNode->accept($this, $handle, $eldnah);
                $function->secondSimpleArithmeticExpression = $secondSimpleArithmeticExpressionNode !== null
                    ? $secondSimpleArithmeticExpressionNode->accept($this, $handle, $eldnah)
                    : null;

                return $function;

            case Tokens::T_FUNCTION_TRIM:
                $typeToken         = $node->getChild(1)->isToken()
                && in_array($node->getChild(1)->getValueToken(), [Tokens::T_LEADING, Tokens::T_TRAILING, Tokens::T_BOTH], true)
                    ? $node->getChild(1)
                    : null;
                $charToken         = $node->getChild($typeToken !== null ? 2 : 1)->isToken()
                && $node->getChild($typeToken !== null ? 2 : 1)->getValueToken() === Tokens::T_STRING_CHAR
                    ? $node->getChild($typeToken !== null ? 2 : 1)
                    : null;
                $stringPrimaryNode = $node->getChild($node->getChildrenNumber() - 1);

                $function                = new TrimFunction($functionName);
                $function->leading       = $typeToken !== null && $typeToken->getValueToken() === Tokens::T_LEADING;
                $function->trailing      = $typeToken !== null && $typeToken->getValueToken() === Tokens::T_TRAILING;
                $function->both          = $typeToken !== null && $typeToken->getValueToken() === Tokens::T_BOTH;
                $function->stringPrimary = $stringPrimaryNode->accept($this, $handle, $eldnah);
                $function->trimChar      = $charToken !== null ? $charToken->getValueValue() : null;

                return $function;

            case Tokens::T_FUNCTION_LOWER:
                $stringPrimaryNode = $node->getChild(1);

                $function                = new LowerFunction($functionName);
                $function->stringPrimary = $stringPrimaryNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_UPPER:
                $stringPrimaryNode = $node->getChild(1);

                $function                = new UpperFunction($functionName);
                $function->stringPrimary = $stringPrimaryNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_IDENTITY:
                $singleValuedAssociationPathExpressionNode = $node->getChild(1);
                $fieldMappingToken                         = $node->getChild(2);

                $function                 = new IdentityFunction($functionName);
                $function->pathExpression = $singleValuedAssociationPathExpressionNode->accept($this, $handle, $eldnah);
                $function->fieldMapping   = $fieldMappingToken !== null
                    ? $fieldMappingToken->getValueValue()
                    : null;

                return $function;
        }

        assert(false, 'Unsupported function ' . $functionName . '.');
    }

    /**
     * FunctionsReturningDateTime ::=
     *     "CURRENT_DATE"
     *     | "CURRENT_TIME"
     *     | "CURRENT_TIMESTAMP"
     *     | "DATE_ADD" "(" ArithmeticPrimary "," ArithmeticPrimary "," StringPrimary ")"
     *     | "DATE_SUB" "(" ArithmeticPrimary "," ArithmeticPrimary "," StringPrimary ")"
     */
    public function visitFunctionsReturningDateTime(TreeNode $node, &$handle, $eldnah) : FunctionNode
    {
        $functionNameToken = $node->getChild(0);
        $functionName      = $functionNameToken->getValueValue();

        switch ($functionNameToken->getValueToken()) {
            case Tokens::T_FUNCTION_CURRENT_DATE:
                return new CurrentDateFunction($functionName);

            case Tokens::T_FUNCTION_CURRENT_TIME:
                return new CurrentTimeFunction($functionName);

            case Tokens::T_FUNCTION_CURRENT_TIMESTAMP:
                return new CurrentTimestampFunction($functionName);

            case Tokens::T_FUNCTION_DATE_ADD:
                $firstDateExpressionNode = $node->getChild(1);
                $intervalExpressionNode  = $node->getChild(2);
                $unitNode                = $node->getChild(3);

                $function = new DateAddFunction($functionName);
                $function->firstDateExpression = $firstDateExpressionNode->accept($this, $handle, $eldnah);
                $function->intervalExpression  = $intervalExpressionNode->accept($this, $handle, $eldnah);
                $function->unit                = $unitNode->accept($this, $handle, $eldnah);

                return $function;

            case Tokens::T_FUNCTION_DATE_SUB:
                $firstDateExpressionNode = $node->getChild(1);
                $intervalExpressionNode  = $node->getChild(2);
                $unitNode                = $node->getChild(3);

                $function = new DateSubFunction($functionName);
                $function->firstDateExpression = $firstDateExpressionNode->accept($this, $handle, $eldnah);
                $function->intervalExpression  = $intervalExpressionNode->accept($this, $handle, $eldnah);
                $function->unit                = $unitNode->accept($this, $handle, $eldnah);

                return $function;
        }

        assert(false, 'Unsupported function ' . $functionName . '.');
    }

    /**
     * InParameter ::= Literal | InputParameter
     * StringExpression ::= StringPrimary | ResultVariable | "(" Subselect ")"
     * BooleanExpression ::= BooleanPrimary | "(" Subselect ")"
     * EmbeddedClassStateField ::= FieldIdentificationVariable
     * SimpleStateField ::= FieldIdentificationVariable
     * ArithmeticPrimary ::= SingleValuedPathExpression | Literal | ParenthesisExpression
     *     | FunctionsReturningNumerics | AggregateExpression | FunctionsReturningStrings
     *     | FunctionsReturningDatetime | IdentificationVariable | ResultVariable
     *     | InputParameter | CaseExpression
     * ScalarExpression ::= SimpleArithmeticExpression | StringPrimary
     *     | DateTimePrimary | StateFieldPathExpression | BooleanPrimary
     *     | CaseExpression | InstanceOfExpression
     * CaseExpression ::= GeneralCaseExpression | SimpleCaseExpression | CoalesceExpression | NullifExpression
     * GroupByItem ::= IdentificationVariable | ResultVariable | SingleValuedPathExpression
     * NewObjectArg ::= ScalarExpression | "(" Subselect ")"
     * EntityExpression ::= SingleValuedAssociationPathExpression | SimpleEntityExpression
     * SimpleEntityExpression ::= IdentificationVariable | InputParameter
     * DatetimeExpression ::= DatetimePrimary | "(" Subselect ")"
     * DatetimePrimary ::= StateFieldPathExpression | InputParameter | FunctionsReturningDatetime | AggregateExpression
     * CaseOperand ::= StateFieldPathExpression | TypeDiscriminator
     * CollectionValuedAssociationField ::= FieldIdentificationVariable
     * SingleValuedAssociationField ::= FieldIdentificationVariable
     * AssociationPathExpression ::= CollectionValuedPathExpression | SingleValuedAssociationPathExpression
     * FunctionDeclaration ::= FunctionsReturningStrings | FunctionsReturningNumerics | FunctionsReturningDateTime
     *
     * @return Node|string|null
     */
    public function visitGenericNode(TreeNode $node, &$handle, $eldnah)
    {
        return $node->getChild(0)->accept($this, $handle, $eldnah);
    }

    /**
     * AliasResultVariable = identifier
     * FieldIdentificationVariable ::= identifier
     * IdentificationVariable ::= identifier
     * Literal ::= string | char | integer | float | boolean
     * AbstractSchemaName ::= fully_qualified_name | aliased_name | identifier
     * AliasIdentificationVariable :: = identifier
     * PositionalParameter ::= "?" integer
     * ComparisonOperator ::= "=" | "<" | "<=" | "<>" | ">" | ">=" | "!="
     * ResultVariable = identifier
     */
    public function visitGenericToken(TreeNode $node, &$handle, $eldnah) : ?string
    {
        return $node->getValueValue();
    }
}
