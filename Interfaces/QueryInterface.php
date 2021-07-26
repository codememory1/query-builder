<?php

namespace Codememory\Components\Database\QueryBuilder\Interfaces;

use PDOStatement;

/**
 * Interface QueryInterface
 *
 * @package Codememory\Components\Database\QueryBuilder2\Interfaces
 *
 * @author  Codememory
 */
interface QueryInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Executes an SQL query and returns a PDOStatement object
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return PDOStatement
     */
    public function execute(): PDOStatement;

}