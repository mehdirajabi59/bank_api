<?php

namespace App\Exceptions;

use Exception;

class ServiceException extends Exception
{
    protected int $customCode = 422;

    /**
     * @param int $customCode
     */
    public function __construct($message, int $customCode)
    {
        parent::__construct($message, 422);
        $this->customCode = $customCode;
    }

    public function getCustomCode(): int
    {
        return $this->customCode;
    }


}
