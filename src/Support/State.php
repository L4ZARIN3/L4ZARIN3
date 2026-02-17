<?php

namespace BranchingWizard\Support;

/**
 * Gerencia o estado compartilhado entre todos os steps do wizard
 */
class State
{
    protected array $allState = [];
    protected ?string $currentStepName = null;

    public function setAllState(array $allState): self
    {
        $this->allState = $allState;
        return $this;
    }

    public function setCurrentStepName(string $stepName): self
    {
        $this->currentStepName = $stepName;
        return $this;
    }

    /**
     * Retorna o estado do step atual
     */
    public function currentStep(): array
    {
        return $this->allState[$this->currentStepName] ?? [];
    }

    /**
     * Retorna o estado de um step específico
     */
    public function forStep(string $stepName): array
    {
        return $this->allState[$stepName] ?? [];
    }

    /**
     * Retorna todo o estado do wizard
     */
    public function all(): array
    {
        return $this->allState;
    }

    /**
     * Verifica se um step específico possui um valor
     */
    public function has(string $stepName, string $key): bool
    {
        return isset($this->allState[$stepName][$key]);
    }

    /**
     * Retorna um valor específico de um step
     */
    public function get(string $stepName, string $key, $default = null)
    {
        return $this->allState[$stepName][$key] ?? $default;
    }
}
