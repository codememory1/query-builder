<?php

namespace Codememory\Components\Database\QueryBuilder;

use Codememory\Components\Database\Connection\Interfaces\ConnectorInterface;
use Codememory\Components\Database\QueryBuilder\Exceptions\StatementNotSelectedException;
use Codememory\Components\Database\QueryBuilder\Interfaces\ExecutorInterface;
use Codememory\Components\Database\Schema\Interfaces\ExpressionInterface;
use Codememory\Components\Database\Schema\Interfaces\JoinInterface;
use Codememory\Components\Database\Schema\Interfaces\StatementInterface;
use Codememory\Components\Database\Schema\Schema;
use Codememory\Components\Database\Schema\StatementComponents\Expression;
use Codememory\Components\Database\Schema\StatementComponents\Join;
use Codememory\Components\Database\Schema\Statements\Manipulation\Delete;
use Codememory\Components\Database\Schema\Statements\Transaction\StartTransaction;
use Codememory\Support\ConvertType;
use Codememory\Support\Str;
use JetBrains\PhpStorm\Pure;
use PDO;

/**
 * Class QueryBuilder
 *
 * @package Codememory\Components\Database\QueryBuilder
 *
 * @author  Codememory
 */
class QueryBuilder
{

    /**
     * @var ConnectorInterface
     */
    protected ConnectorInterface $connector;

    /**
     * @var object
     */
    protected object $qbCreator;

    /**
     * @var Executor
     */
    protected Executor $executor;

    /**
     * @var Schema
     */
    protected Schema $schema;

    /**
     * @var ConvertType
     */
    protected ConvertType $convertType;

    /**
     * @var StatementInterface|null
     */
    protected ?StatementInterface $statement = null;

    /**
     * @var string|null
     */
    protected ?string $sql = null;

    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * @var ExpressionInterface|null
     */
    private ?ExpressionInterface $expression = null;

    /**
     * @var JoinInterface|null
     */
    private ?JoinInterface $join = null;

    /**
     * @param ConnectorInterface $connector
     * @param object             $queryBuilderCreator
     */
    #[Pure]
    public function __construct(ConnectorInterface $connector, object $queryBuilderCreator)
    {

        $this->connector = $connector;
        $this->qbCreator = $queryBuilderCreator;

        $this->executor = new Executor($connector, $queryBuilderCreator);
        $this->schema = new Schema();
        $this->convertType = new ConvertType();

    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function select(array $columns = []): static
    {

        $statement = $this->schema->select();

        $statement->columns($columns);

        $this->statement = $statement;

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function distinct(): static
    {

        $this->getStatement()->distinct();

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function distinctrow(): static
    {

        $this->getStatement()->distinctrow();

        return $this;

    }

    /**
     * @param string      $tableName
     * @param string|null $alias
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function from(string $tableName, ?string $alias = null): static
    {

        if ($this->getStatement() instanceof Delete) {
            $this->getStatement()->from([$alias ?: 0 => $tableName]);
        } else {
            $this->getStatement()->from($tableName, $alias);
        }

        return $this;

    }

    /**
     * @param array $tables
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function manyFrom(array $tables): static
    {

        $this->getStatement()->from($tables);

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function union(): static
    {

        $this->getStatement()->union();

        return $this;

    }

    /**
     * @param string      $tableName
     * @param string|null $alias
     *
     * @return $this
     */
    public function update(string $tableName, ?string $alias = null): static
    {

        $statement = $this->schema->update();

        $statement->tables([$alias ?: 0 => $tableName]);

        $this->statement = $statement;

        return $this;

    }

    /**
     * @param array $tables
     *
     * @return $this
     */
    public function manyUpdate(array $tables): static
    {

        $statement = $this->schema->update();

        $statement->tables($tables);

        $this->statement = $statement;

        return $this;

    }

    /**
     * @param array $columns
     * @param array $values
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function updateData(array $columns, array $values): static
    {

        $this->getStatement()->setData($columns, $values);

        return $this;

    }

    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function insert(string $tableName): static
    {

        $statement = $this->schema->insert();

        $statement->table($tableName);

        $this->statement = $statement;

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function lowPriority(): static
    {

        $this->getStatement()->lowPriority();

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function highPriority(): static
    {

        $this->getStatement()->highPriority();

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function ignore(): static
    {

        $this->getStatement()->ignore();

        return $this;

    }

    /**
     * @param array $columns
     * @param mixed ...$records
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function setRecords(array $columns, array ...$records): static
    {

        $this->getStatement()->columns(...$columns)->records(...$records);

        return $this;

    }

    /**
     * @param array $columns
     * @param mixed ...$records
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function setRowRecords(array $columns, array ...$records): static
    {

        $this->getStatement()->columns(...$columns)->rowRecords(...$records);

        return $this;

    }

    /**
     * @return $this
     */
    public function delete(): static
    {

        $this->statement = $this->schema->delete();

        return $this;

    }

    /**
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function quick(): static
    {

        $this->getStatement()->quick();

        return $this;

    }

    /**
     * @return ExpressionInterface
     */
    public function expression(): ExpressionInterface
    {

        if ($this->expression instanceof ExpressionInterface) {
            return $this->expression;
        }

        return $this->expression = new Expression();

    }

    /**
     * @param ExpressionInterface $expression
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function where(ExpressionInterface $expression): static
    {

        $this->getStatement()->where($expression);

        return $this;

    }

    /**
     * @param array|string           $column
     * @param array|string|int|float $value
     * @param string                 $conditionalOperator
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function comparisonCondition(array|string $column, array|string|int|float $value, string $conditionalOperator = 'and'): static
    {

        $columns = is_array($column) ? $column : [$column];
        $values = is_array($value) ? $value : [$value];
        $conditionalOperatorMethod = sprintf('expr%s', ucfirst($conditionalOperator));

        $conditions = [];

        foreach ($columns as $index => $columnName) {
            $conditions[] = $this->expression()->condition($columnName, '=', $values[$index] ?: '');
        }

        $this->where($this->expression()->$conditionalOperatorMethod(...$conditions));

        return $this;

    }

    /**
     * @param array|string $columns
     * @param array|string $types
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function order(array|string $columns, array|string $types = 'asc'): static
    {

        $order = $this->getStatement()->getOrder($columns, $types);

        $this->getStatement()->orderBy($order);

        return $this;

    }

    /**
     * @param string ...$columns
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function group(string ...$columns): static
    {

        $group = $this->getStatement()->getGroup()->columns(...$columns);

        $this->getStatement()->group($group);

        return $this;

    }

    /**
     * @param int      $from
     * @param int|null $before
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function limit(int $from, ?int $before = null): static
    {

        $this->getStatement()->limit($from, $before);

        return $this;

    }

    /**
     * @param ExpressionInterface $expression
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function having(ExpressionInterface $expression): static
    {

        $this->getStatement()->having($expression);

        return $this;

    }

    /**
     * @param string|array $table
     * @param string       $specification
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function innerJoin(string|array $table, string $specification): static
    {

        $this->joinCollector($table, $specification, 'inner');

        return $this;

    }

    /**
     * @param string|array $table
     * @param string       $specification
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function leftJoin(string|array $table, string $specification): static
    {

        $this->joinCollector($table, $specification, 'left');

        return $this;

    }

    /**
     * @param string|array $table
     * @param string       $specification
     *
     * @return $this
     * @throws StatementNotSelectedException
     */
    public function rightJoin(string|array $table, string $specification): static
    {

        $this->joinCollector($table, $specification, 'right');

        return $this;

    }

    /**
     * @param ExpressionInterface $expression
     *
     * @return string
     */
    public function onJoin(ExpressionInterface $expression): string
    {

        return $this->getJoin()->on($expression);

    }

    /**$parameters
     * @param array $columns
     *
     * @return string
     */
    public function using(array $columns): string
    {

        return $this->getJoin()->using($columns);

    }

    /**
     * @param string           $name
     * @param float|int|string $value
     *
     * @return $this
     */
    public function setVariable(string $name, float|int|string $value): static
    {

        $sql = sprintf('SET @%s = \'%s\'', $name, $value);

        $this->connector->getConnection()->query($sql);

        return $this;

    }

    /**
     * @param string $name
     *
     * @return string|int|bool|float|null
     */
    public function getVariable(string $name): string|int|bool|null|float
    {

        $variable = $this->connector->getConnection()
            ->query(sprintf('SELECT @%s', $name))
            ->fetchAll(PDO::FETCH_COLUMN);

        return [] !== $variable ? $this->convertType->auto($variable[0]) : false;

    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function beginTransaction(callable $callback): static
    {

        $transaction = new StartTransaction();

        $transaction->start();

        call_user_func($callback, $transaction);

        $this->statement = $transaction;

        return $this;

    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function setParameter(string $name, string $value): static
    {

        $this->parameters[$name] = $value;

        return $this;

    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters): static
    {

        foreach ($parameters as $name => $value) {
            $this->setParameter((string) $name, (string) $value);
        }

        return $this;

    }

    /**
     * @return array
     */
    public function getParameters(): array
    {

        return $this->parameters;

    }

    /**
     * @return StatementInterface
     * @throws StatementNotSelectedException
     */
    public function getStatement(): StatementInterface
    {

        if (null === $this->statement) {
            throw new StatementNotSelectedException();
        }

        return $this->statement;

    }

    /**
     * @return ExecutorInterface
     */
    public function getExecutor(): ExecutorInterface
    {

        return $this->executor;

    }

    /**
     * @return JoinInterface
     */
    protected function getJoin(): JoinInterface
    {

        if ($this->join instanceof JoinInterface) {
            return $this->join;
        }

        return $this->join = new Join();

    }

    /**
     * @param string|array $table
     * @param string       $specification
     * @param string       $joinType
     *
     * @throws StatementNotSelectedException
     */
    private function joinCollector(string|array $table, string $specification, string $joinType): void
    {

        $tables = is_array($table) ? $table : [$table];
        $joinTypeMethod = sprintf('%sJoin', Str::toLowercase($joinType));

        $this->getStatement()->join($this->getJoin()->$joinTypeMethod($tables, $specification));

    }

}