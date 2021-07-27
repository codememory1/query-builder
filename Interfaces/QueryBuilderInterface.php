<?php

namespace Codememory\Components\Database\QueryBuilder\Interfaces;

use Codememory\Components\Database\Schema\Interfaces\DeleteInterface;
use Codememory\Components\Database\Schema\Interfaces\ExpressionInterface;
use Codememory\Components\Database\Schema\Interfaces\GroupInterface;
use Codememory\Components\Database\Schema\Interfaces\InsertInterface;
use Codememory\Components\Database\Schema\Interfaces\JoinInterface;
use Codememory\Components\Database\Schema\Interfaces\JoinSpecificationInterface;
use Codememory\Components\Database\Schema\Interfaces\OrderInterface;
use Codememory\Components\Database\Schema\Interfaces\SelectInterface;
use Codememory\Components\Database\Schema\Interfaces\StatementInterface;
use Codememory\Components\Database\Schema\Interfaces\UpdateInterface;

/**
 * Interface QueryBuilderInterface
 *
 * @package Codememory\Components\Database\QueryBuilder2\Interfaces
 *
 * @author  Codememory
 */
interface QueryBuilderInterface
{

    /**
     * @param array $columns
     *
     * @return SelectInterface
     */
    public function select(array $columns = []): SelectInterface;

    /**
     * @return SelectInterface
     */
    public function customSelect(): SelectInterface;

    /**
     * @param string $tableName
     *
     * @return InsertInterface
     */
    public function insert(string $tableName): InsertInterface;

    /**
     * @return InsertInterface
     */
    public function customInsert(): InsertInterface;

    /**
     * @param array|string $tables
     *
     * @return UpdateInterface
     */
    public function update(array|string $tables): UpdateInterface;

    /**
     * @param string|array $tables
     *
     * @return DeleteInterface
     */
    public function delete(string|array $tables): DeleteInterface;

    /**
     * @return DeleteInterface
     */
    public function customDelete(): DeleteInterface;

    /**
     * @param array  $tables
     * @param string $specification
     *
     * @return JoinInterface
     */
    public function innerJoin(array $tables, string $specification): JoinInterface;

    /**
     * @param array  $tables
     * @param string $specification
     *
     * @return JoinInterface
     */
    public function leftJoin(array $tables, string $specification): JoinInterface;

    /**
     * @param array  $tables
     * @param string $specification
     *
     * @return JoinInterface
     */
    public function rightJoin(array $tables, string $specification): JoinInterface;

    /**
     * @return JoinSpecificationInterface
     */
    public function joinSpecification(): JoinSpecificationInterface;

    /**
     * @return ExpressionInterface
     */
    public function expression(): ExpressionInterface;

    /**
     * @param array|string $columns
     * @param array|string $types
     *
     * @return OrderInterface
     */
    public function order(array|string $columns, array|string $types = 'asc'): OrderInterface;

    /**
     * @param string ...$columns
     *
     * @return GroupInterface
     */
    public function group(string ...$columns): GroupInterface;

    /**
     * @param StatementInterface $statement
     *
     * @return string
     */
    public function generateSubquery(StatementInterface $statement): string;

    /**
     * @param string $name
     * @param string $value
     *
     * @return QueryBuilderInterface
     */
    public function setParameter(string $name, string $value): QueryBuilderInterface;

    /**
     * @param array $parameters
     *
     * @return QueryBuilderInterface
     */
    public function setParameters(array $parameters): QueryBuilderInterface;

    /**
     * @param string           $name
     * @param int|float|string $value
     *
     * @return QueryBuilderInterface
     */
    public function setVariable(string $name, int|float|string $value): QueryBuilderInterface;

    /**
     * @param string $name
     *
     * @return string|int|bool|float|null
     */
    public function getVariable(string $name): string|int|bool|null|float;

    /**
     * @return QueryBuilderInterface
     */
    public function generateQuery(): QueryBuilderInterface;

    /**
     * @return void
     */
    public function execute(): void;

    /**
     * @return QueryResultInterface
     */
    public function getResult(): QueryResultInterface;

}