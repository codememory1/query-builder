<?php

namespace Codememory\Components\Database\QueryBuilder\Interfaces;

use Codememory\Components\Database\Schema\Interfaces\ExpressionInterface;
use Codememory\Components\Database\Schema\Interfaces\GroupInterface;
use Codememory\Components\Database\Schema\Interfaces\JoinInterface;
use Codememory\Components\Database\Schema\Interfaces\OrderInterface;
use Codememory\Components\Database\Schema\Interfaces\StatementInterface;

/**
 * Interface ExtendedStatementInterface
 *
 * @package Codememory\Components\Database\QueryBuilder\Interfaces
 *
 * @author  Codememory
 */
interface ExtendedStatementInterface extends StatementInterface
{

    /**
     * @return void
     */
    public function distinct(): void;

    /**
     * @return void
     */
    public function distinctrow(): void;


    /**
     * @param string|array $tables
     * @param string|null  $alias
     *
     * @return void
     */
    public function from(string|array $tables, ?string $alias = null): void;


    /**
     * @return void
     */
    public function union(): void;

    /**
     * @param array $columns
     * @param array $values
     *
     * @return void
     */
    public function setData(array $columns, array $values): void;

    /**
     * @return void
     */
    public function lowPriority(): void;

    /**
     * @return void
     */
    public function highPriority(): void;

    /**
     * @return void
     */
    public function ignore(): void;

    /**
     * @param string ...$columns
     *
     * @return object
     */
    public function columns(string ...$columns): object;

    /**
     * @param mixed ...$records
     *
     * @return void
     */
    public function records(array ...$records): void;

    /**
     * @param mixed ...$records
     *
     * @return void
     */
    public function rowRecords(array ...$records): void;

    /**
     * @return void
     */
    public function quick(): void;

    /**
     * @param ExpressionInterface $expression
     *
     * @return void
     */
    public function where(ExpressionInterface $expression): void;

    /**
     * @param array|string $columns
     * @param array|string $types
     *
     * @return OrderInterface
     */
    public function getOrder(array|string $columns, array|string $types = 'asc'): OrderInterface;

    /**
     * @param OrderInterface $order
     */
    public function orderBy(OrderInterface $order): void;

    /**
     * @param int      $from
     * @param int|null $before
     *
     * @return void
     */
    public function limit(int $from, ?int $before = null): void;

    /**
     * @param ExpressionInterface $expression
     *
     * @return void
     */
    public function having(ExpressionInterface $expression): void;

    /**
     * @param JoinInterface $join
     *
     * @return void
     */
    public function join(JoinInterface $join): void;

    /**
     * @return GroupInterface
     */
    public function getGroup(): GroupInterface;

    /**
     * @param GroupInterface $group
     */
    public function group(GroupInterface $group): void;

}