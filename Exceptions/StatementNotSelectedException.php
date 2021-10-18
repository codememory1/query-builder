<?php

namespace Codememory\Components\Database\QueryBuilder\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class StatementNotSelectedException
 *
 * @package Codememory\Components\Database\QueryBuilder\Exceptions
 *
 * @author  Codememory
 */
class StatementNotSelectedException extends AbstractQueryBuilderException
{

    #[Pure]
    public function __construct()
    {

        parent::__construct('To use query operators, you must select one of the "select, update, delete" queries.');

    }

}