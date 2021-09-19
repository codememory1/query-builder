<?php

namespace Codememory\Components\Database\QueryBuilder;

use Codememory\Components\Database\Connection\Connector;
use Codememory\Components\Database\Connection\Exceptions\DriverNotSupportedException;
use Codememory\Components\Database\Connection\Exceptions\NoDatabaseConnectionException;
use Codememory\Components\Database\Connection\Interfaces\ConnectorInterface;
use Codememory\Components\Database\QueryBuilder\Exceptions\NotSelectedStatementException;
use Codememory\Components\Database\QueryBuilder\Exceptions\QueryNotGeneratedException;
use Codememory\Components\Database\QueryBuilder\Interfaces\QueryBuilderInterface;
use Codememory\Components\Database\QueryBuilder\Interfaces\QueryResultInterface;
use Codememory\Components\Database\QueryBuilder\Interfaces\StatementsInterface;
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
use Codememory\Components\Database\Schema\StatementComponents\Subquery;
use Codememory\Support\ConvertType;
use JetBrains\PhpStorm\Pure;
use PDO;

/**
 * Class QueryBuilder
 *
 * @package Codememory\Components\Database\QueryBuilder
 *
 * @author  Codememory
 */
class QueryBuilder implements QueryBuilderInterface
{

    /**
     * @var ConnectorInterface
     */
    private ConnectorInterface $connector;

    /**
     * @var StatementsInterface
     */
    private StatementsInterface $statements;

    /**
     * @var StatementComponents
     */
    private StatementComponents $statementComponents;

    /**
     * @var Subquery
     */
    private Subquery $subquery;

    /**
     * @var ConvertType
     */
    private ConvertType $convertType;

    /**
     * @var StatementInterface|null
     */
    private ?StatementInterface $statement = null;

    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @var string|null
     */
    private ?string $query = null;

    /**
     * QueryBuilder2 constructor.
     *
     * @param Connector $connector
     */
    public function __construct(ConnectorInterface $connector)
    {

        $this->connector = $connector;
        $this->statements = new Statements();
        $this->statementComponents = new StatementComponents();
        $this->subquery = new Subquery();
        $this->convertType = new ConvertType();

    }

    /**
     * @inheritDoc
     */
    public function select(array $columns = []): SelectInterface
    {

        return $this->customSelect()->columns($columns);

    }

    /**
     * @inheritDoc
     */
    public function customSelect(): SelectInterface
    {

        $select = $this->statements->getSelect();

        $this->statement = $select;

        return $select;

    }

    /**
     * @inheritDoc
     */
    public function insert(string $tableName): InsertInterface
    {

        $insert = $this->customInsert();

        $this->statement = $insert->table($tableName);

        return $insert;

    }

    /**
     * @inheritDoc
     */
    public function customInsert(): InsertInterface
    {

        $insert = $this->statements->getInsert();

        $this->statement = $insert;

        return $insert;

    }

    /**
     * @inheritDoc
     */
    public function update(array|string $tables): UpdateInterface
    {

        $update = $this->statements->getUpdate();

        $this->statement = $update->tables(is_string($tables) ? [$tables] : $tables);

        return $update;

    }

    /**
     * @inheritDoc
     */
    public function delete(array|string $tables): DeleteInterface
    {

        $delete = $this->customDelete();

        $this->statement = $delete->from(is_string($tables) ? [$tables] : $tables);

        return $delete;

    }

    /**
     * @inheritDoc
     */
    public function customDelete(): DeleteInterface
    {

        $delete = $this->statements->getDelete();

        $this->statement = $delete;

        return $delete;

    }

    /**
     * @inheritDoc
     */
    public function innerJoin(array $tables, string $specification): JoinInterface
    {

        return $this->statementComponents->getJoin()->innerJoin($tables, $specification);

    }

    /**
     * @inheritDoc
     */
    public function leftJoin(array $tables, string $specification): JoinInterface
    {

        return $this->statementComponents->getJoin()->leftJoin($tables, $specification);

    }

    /**
     * @inheritDoc
     */
    public function rightJoin(array $tables, string $specification): JoinInterface
    {

        return $this->statementComponents->getJoin()->rightJoin($tables, $specification);

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function joinSpecification(): JoinSpecificationInterface
    {

        return $this->statementComponents->getJoin();

    }

    /**
     * @inheritDoc
     */
    public function joinComparison(string|array $column, string|array $withColumn): string
    {

        $conditions = [];
        $column = is_string($column) ? [$column] : $column;
        $withColumn = is_string($withColumn) ? [$withColumn] : $withColumn;

        foreach ($column as $index => $firstColumn) {
            $conditions[] = $this->expression()->condition($firstColumn, '=', $withColumn[$index] ?? $firstColumn);
        }

        return $this->joinSpecification()->on(
            $this->expression()->exprAnd(...$conditions)
        );

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function expression(): ExpressionInterface
    {

        return $this->statementComponents->getExpression();

    }

    /**
     * @inheritDoc
     */
    public function order(array|string $columns, array|string $types = 'asc'): OrderInterface
    {

        return $this->statementComponents->getOrder()->columns($columns, $types);

    }

    /**
     * @inheritDoc
     */
    public function group(string ...$columns): GroupInterface
    {

        return $this->statementComponents->getGroup()->columns(...$columns);

    }

    /**
     * @inheritDoc
     */
    public function generateSubquery(StatementInterface $statement): string
    {

        return $this->subquery->create($statement);

    }

    /**
     * @inheritDoc
     */
    public function setParameter(string $name, string $value): QueryBuilderInterface
    {

        $this->parameters[$name] = $value;

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function setParameters(array $parameters): QueryBuilderInterface
    {

        foreach ($parameters as $name => $value) {
            $this->setParameter((string) $name, (string) $value);
        }

        return $this;

    }

    /**
     * @inheritDoc
     * @throws DriverNotSupportedException
     * @throws NoDatabaseConnectionException
     */
    public function setVariable(string $name, float|int|string $value): QueryBuilderInterface
    {

        $sql = sprintf('SET @%s = \'%s\'', $name, $value);

        $this->connector->getConnection()->query($sql);

        return $this;

    }

    /**
     * @inheritDoc
     * @throws DriverNotSupportedException
     * @throws NoDatabaseConnectionException
     */
    public function getVariable(string $name): string|int|bool|null|float
    {

        $variable = $this->connector->getConnection()
            ->query(sprintf('SELECT @%s', $name))
            ->fetchAll(PDO::FETCH_COLUMN);

        return [] !== $variable ? $this->convertType->auto($variable[0]) : false;

    }

    /**
     * @inheritDoc
     * @throws NotSelectedStatementException
     */
    public function generateQuery(): QueryBuilderInterface
    {

        if (null === $this->statement) {
            throw new NotSelectedStatementException();
        }

        $this->query = $this->statement->getQuery();
        $this->statement = null;

        return $this;

    }

    /**
     * @inheritDoc
     * @throws QueryNotGeneratedException
     */
    public function execute(): void
    {

        if (null === $this->query) {
            throw new QueryNotGeneratedException();
        }

        $query = new Query($this->connector, $this->query, $this->parameters);

        $query->execute();

    }

    /**
     * @inheritDoc
     * @throws QueryNotGeneratedException
     */
    public function getResult(): QueryResultInterface
    {

        if (null === $this->query) {
            throw new QueryNotGeneratedException();
        }

        return new Query($this->connector, $this->query, $this->parameters);

    }

    /**
     * @inheritDoc
     * @throws NotSelectedStatementException
     */
    public function generateResult(): QueryResultInterface
    {

        return $this->generateQuery()->getResult();

    }

}