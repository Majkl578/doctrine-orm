<?php


declare(strict_types=1);

namespace Doctrine\ORM\Tools\Pagination;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use function trim;
use Doctrine\ORM\Query\Parser;

/**
 * RowNumberOverFunction
 *
 * Provides ROW_NUMBER() OVER(ORDER BY...) construct for use in LimitSubqueryOutputWalker
 *
 * @since   2.5
 * @author  Bill Schaller <bill@zeroedin.com>
 */
class RowNumberOverFunction extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\OrderByClause
     */
    public $orderByClause;

    /**
     * @override
     * @inheritdoc
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'ROW_NUMBER() OVER(' . trim($sqlWalker->walkOrderByClause(
            $this->orderByClause
        )) . ')';
    }

    /**
     * @override
     * @inheritdoc
     *
     * @throws ORMException
     */
    public function parse(Parser $parser)
    {
        throw new ORMException("The RowNumberOverFunction is not intended for, nor is it enabled for use in DQL.");
    }
}
