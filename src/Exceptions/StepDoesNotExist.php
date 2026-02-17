<?php

namespace BranchingWizard\Exceptions;

use Exception;

class StepDoesNotExist extends Exception
{
    public function __construct(string $stepName)
    {
        parent::__construct("O step '{$stepName}' não existe neste wizard");
    }
}
