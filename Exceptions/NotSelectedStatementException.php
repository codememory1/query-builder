<?php

namespace Codememory\Components\Database\QueryBuilder\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class NotSelectedStatementException
 *
 * @package Codememory\Components\Database\QueryBuilder2\Exceptions
 *
 * @author  Codememory
 */
class NotSelectedStatementException extends QueryBuilderException
{

    /**
     * NotSelectedStatementException constructor.
     */
    #[Pure]
    public function __construct()
    {

        parent::__construct('Unable to generate sql query, statement not selected');

    }

}