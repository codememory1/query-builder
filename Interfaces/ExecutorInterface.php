<?php

namespace Codememory\Components\Database\QueryBuilder\Interfaces;

use PDOStatement;

/**
 * Interface ExecutorInterface
 *
 * @package Codememory\Components\Database\QueryBuilder\Interfaces
 *
 * @author  Codememory
 */
interface ExecutorInterface
{

    /**
     * @param string $query
     * @param array  $parameters
     *
     * @return bool|PDOStatement
     */
    public function execute(string $query, array $parameters = []): bool|PDOStatement;

}