<?php

namespace Codememory\Components\Database\QueryBuilder\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class QueryNotGeneratedException
 *
 * @package Codememory\Components\Database\QueryBuilder2\Exceptions
 *
 * @author  Codememory
 */
class QueryNotGeneratedException extends QueryBuilderException
{

    /**
     * QueryNotGeneratedException constructor.
     */
    #[Pure]
    public function __construct()
    {

        parent::__construct('Unable to execute SQL query due to its absence');

    }

}