<?php

namespace Codememory\Components\Database\QueryBuilder;

use Codememory\Components\Database\Connection\Interfaces\ConnectorInterface;
use Codememory\Components\Database\QueryBuilder\Interfaces\QueryInterface;
use Codememory\Components\Database\QueryBuilder\Interfaces\QueryResultInterface;
use PDO;
use PDOStatement;

/**
 * Class Query
 *
 * @package Codememory\Components\Database\QueryBuilder2
 *
 * @author  Codememory
 */
class Query implements QueryInterface, QueryResultInterface
{

    /**
     * @var ConnectorInterface
     */
    private ConnectorInterface $connector;

    /**
     * @var string
     */
    private string $query;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * Query constructor.
     *
     * @param ConnectorInterface $connector
     * @param string             $query
     * @param array              $parameters
     */
    public function __construct(ConnectorInterface $connector, string $query, array $parameters = [])
    {

        $this->connector = $connector;
        $this->query = $query;
        $this->parameters = $parameters;

    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {

        return $this->execute()->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @inheritDoc
     */
    public function toObject(): array
    {

        return $this->execute()->fetchAll(PDO::FETCH_OBJ);

    }

    /**
     * @inheritDoc
     */
    public function execute(): PDOStatement
    {

        $sth = $this->connector->getConnection()->prepare($this->query);
        $sth->execute($this->parameters);

        return $sth;

    }

}