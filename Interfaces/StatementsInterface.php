<?php

namespace Codememory\Components\Database\QueryBuilder\Interfaces;

use Codememory\Components\Database\Schema\Interfaces\DeleteInterface;
use Codememory\Components\Database\Schema\Interfaces\InsertInterface;
use Codememory\Components\Database\Schema\Interfaces\SelectInterface;
use Codememory\Components\Database\Schema\Interfaces\UpdateInterface;

/**
 * Interface StatementsInterface
 *
 * @package Codememory\Components\Database\QueryBuilder2\Interfaces
 *
 * @author  Codememory
 */
interface StatementsInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the clone of the select schema
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return SelectInterface
     */
    public function getSelect(): SelectInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the clone of the insert schema
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return InsertInterface
     */
    public function getInsert(): InsertInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the clone of the update schema
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return UpdateInterface
     */
    public function getUpdate(): UpdateInterface;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the clone of the delete schema
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return DeleteInterface
     */
    public function getDelete(): DeleteInterface;

}