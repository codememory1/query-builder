<?php

namespace Codememory\Components\Database\QueryBuilder\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class QueryBuilderException
 *
 * @package Codememory\Components\Database\QueryBuilder2\Exceptions
 *
 * @author  Codememory
 */
abstract class QueryBuilderException extends ErrorException
{

    /**
     * QueryBuilderException constructor.
     *
     * @param string $message
     */
    #[Pure]
    public function __construct(string $message = '')
    {

        parent::__construct($message);

    }

}