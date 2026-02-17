<?php

namespace BranchingWizard\Exceptions;

use Exception;

class NoNextStep extends Exception
{
    public static function make(string $wizardClass, string $currentStep): self
    {
        return new self("O wizard '{$wizardClass}' não tem um próximo step após '{$currentStep}'");
    }
}
