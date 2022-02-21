<?php

namespace Dowilcox\KnpMenu\Exceptions;

use Exception;

class PathNotFoundException extends Exception
{

    public function __construct(string $path)
    {
        parent::__construct("Path '$path' not found");
    }
}
