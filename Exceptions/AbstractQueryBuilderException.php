<?php

namespace Codememory\Components\Database\QueryBuilder\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class AbstractQueryBuilderException
 *
 * @package Codememory\Components\Database\QueryBuilder\Exceptions
 *
 * @author  Codememory
 */
abstract class AbstractQueryBuilderException extends ErrorException
{

    /**
     * @param string $message
     */
    #[Pure]
    public function __construct(string $message = '')
    {

        parent::__construct($message);

    }

}