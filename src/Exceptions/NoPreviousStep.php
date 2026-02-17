<?php

namespace BranchingWizard\Exceptions;

use Exception;

class NoPreviousStep extends Exception
{
    public static function make(string $wizardClass, string $currentStep): self
    {
        return new self("O wizard '{$wizardClass}' não tem um step anterior antes de '{$currentStep}'");
    }
}
