<?php

namespace la_cremallera\err;

use Error;

class FuncionesDBException extends Error{
    public function __construct(string $message = "")
    {
        return parent::__construct($message);
    }
}