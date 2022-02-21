<?php

namespace Dowilcox\KnpMenu\Exceptions;

use Exception;

class MenuNotFoundException extends Exception
{

    public function __construct(string $name)
    {
        parent::__construct("Menu '$name' not found");
    }
}
