<?php

namespace Codememory\Components\Database\QueryBuilder;

use Codememory\Components\Database\Connection\Interfaces\ConnectorInterface;
use Codememory\Components\Database\QueryBuilder\Interfaces\ExecutorInterface;
use Codememory\Components\Profiling\Exceptions\BuilderNotCurrentSectionException;
use Codememory\Components\Profiling\ReportCreators\DatabaseReportCreator;
use Codememory\Components\Profiling\Resource;
use Codememory\Components\Profiling\Sections\Builders\DatabaseBuilder;
use Codememory\Components\Profiling\Sections\DatabaseSection;
use Codememory\Routing\Router;
use PDOStatement;

/**
 * Class Executor
 *
 * @package Codememory\Components\Database\QueryBuilder
 *
 * @author  Codememory
 */
class Executor implements ExecutorInterface
{

    /**
     * @var ConnectorInterface
     */
    private ConnectorInterface $connector;

    /**
     * @var string
     */
    private string $queryBuilderCreator;

    /**
     * @param ConnectorInterface $connector
     * @param string             $queryBuilderCreator
     */
    public function __construct(ConnectorInterface $connector, string $queryBuilderCreator)
    {

        $this->connector = $connector;
        $this->queryBuilderCreator = $queryBuilderCreator;

    }

    /**
     * @inheritDoc
     * @throws BuilderNotCurrentSectionException
     */
    public function execute(string $query, array $parameters = []): bool|PDOStatement
    {

        $microTime = microtime(true);

        // Prepare and execute the request
        $sth = $this->connector->getConnection()->prepare($query);
        $sth->execute($parameters);

        // Profiled executed query
        $this->profile($microTime, $query);

        return $sth;

    }

    /**
     * @param float  $microTime
     * @param string $query
     *
     * @throws BuilderNotCurrentSectionException
     */
    private function profile(float $microTime, string $query): void
    {

        $duration = round((microtime(true) - $microTime) * 1000);
        $currentRoute = Router::getCurrentRoute();

        $databaseBuilder = new DatabaseBuilder();

        if (null !== $currentRoute) {
            $databaseReportCreator = new DatabaseReportCreator($currentRoute, new DatabaseSection(new Resource()));

            $databaseBuilder
                ->setRepository($this->queryBuilderCreator)
                ->setConnector($this->connector->getConnectorName())
                ->setQuery($query)
                ->setDuration($duration);

            $databaseReportCreator->create($databaseBuilder);
        }

    }

}