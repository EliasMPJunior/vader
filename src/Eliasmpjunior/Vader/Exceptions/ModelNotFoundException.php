<?php

namespace Eliasmpjunior\Vader\Exceptions;


class ModelNotFoundException extends VaderException
{
    protected $modelName;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    public function printException()
    {
        $this->printMessage('The model '.$this->modelName.' has not be found.');
    }
}