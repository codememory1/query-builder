<?php

namespace Codememory\Components\Database\QueryBuilder;

use Codememory\Components\Database\Schema\Interfaces\ExpressionInterface;
use Codememory\Components\Database\Schema\Interfaces\GroupInterface;
use Codememory\Components\Database\Schema\Interfaces\JoinInterface;
use Codememory\Components\Database\Schema\Interfaces\OrderInterface;
use Codememory\Components\Database\Schema\StatementComponents\Expression;
use Codememory\Components\Database\Schema\StatementComponents\Group;
use Codememory\Components\Database\Schema\StatementComponents\Join;
use Codememory\Components\Database\Schema\StatementComponents\Order;
use JetBrains\PhpStorm\Pure;

/**
 * Class StatementComponents
 *
 * @package Codememory\Components\Database\QueryBuilder2
 *
 * @author  Codememory
 */
class StatementComponents
{

    /**
     * @var ExpressionInterface
     */
    private ExpressionInterface $expression;

    /**
     * @var JoinInterface
     */
    private JoinInterface $join;

    /**
     * @var Order
     */
    private OrderInterface $order;

    /**
     * @var GroupInterface
     */
    private GroupInterface $group;

    /**
     * StatementComponents constructor.
     */
    #[Pure]
    public function __construct()
    {

        $this->expression = new Expression();
        $this->join = new Join();
        $this->order = new Order();
        $this->group = new Group();

    }

    /**
     * @return ExpressionInterface
     */
    public function getExpression(): ExpressionInterface
    {

        return clone $this->expression;

    }

    /**
     * @return JoinInterface
     */
    public function getJoin(): JoinInterface
    {

        return clone $this->join;

    }

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {

        return clone $this->order;

    }

    /**
     * @return GroupInterface
     */
    public function getGroup(): GroupInterface
    {

        return $this->group;

    }

}