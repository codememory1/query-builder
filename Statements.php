<?php

namespace Codememory\Components\Database\QueryBuilder;

use Codememory\Components\Database\QueryBuilder\Interfaces\StatementsInterface;
use Codememory\Components\Database\Schema\Interfaces\DeleteInterface;
use Codememory\Components\Database\Schema\Interfaces\InsertInterface;
use Codememory\Components\Database\Schema\Interfaces\SchemaInterface;
use Codememory\Components\Database\Schema\Interfaces\SelectInterface;
use Codememory\Components\Database\Schema\Interfaces\UpdateInterface;
use Codememory\Components\Database\Schema\Schema;

/**
 * Class Statements
 *
 * @package Codememory\Components\Database\QueryBuilder2
 *
 * @author  Codememory
 */
class Statements implements StatementsInterface
{

    /**
     * @var SchemaInterface
     */
    private SchemaInterface $schema;

    /**
     * @var SelectInterface
     */
    private SelectInterface $select;

    /**
     * @var InsertInterface
     */
    private InsertInterface $insert;

    /**
     * @var UpdateInterface
     */
    private UpdateInterface $update;

    /**
     * @var DeleteInterface
     */
    private DeleteInterface $delete;

    /**
     * Statements constructor.
     */
    public function __construct()
    {

        $this->schema = new Schema();
        $this->select = $this->schema->select();
        $this->insert = $this->schema->insert();
        $this->update = $this->schema->update();
        $this->delete = $this->schema->delete();
    }

    /**
     * @inheritDoc
     */
    public function getSelect(): SelectInterface
    {

        return clone $this->select;

    }

    /**
     * @inheritDoc
     */
    public function getInsert(): InsertInterface
    {

        return clone $this->insert;

    }

    /**
     * @inheritDoc
     */
    public function getUpdate(): UpdateInterface
    {

        return clone $this->update;

    }

    /**
     * @inheritDoc
     */
    public function getDelete(): DeleteInterface
    {

        return clone $this->delete;

    }

    /**
     * @return SchemaInterface
     */
    public function getSchema(): SchemaInterface
    {

        return $this->schema;

    }

}