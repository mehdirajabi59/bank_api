<?php

namespace App\Exceptions;

use Exception;

class NoSufficientMoneyException extends ServiceException
{
    protected int $customCode = 422;
}
