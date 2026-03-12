<?php

namespace BranchingWizard\Components;

use BranchingWizard\Support\State;

/**
 * Componente base para cada step do wizard
 * 
 * Em uma aplicação Livewire real, isso extenderia Livewire\Component
 * Para fins de exemplo, esta é uma implementação standalone
 */
abstract class StepComponent
{
    public ?string $wizardClassName = null;
    public array $allStepNames = [];
    public array $allStepsState = [];
    public string $stateClassName = State::class;

    /**
     * Define qual será o próximo step
     * Override este método para implementar bifurcação
     * 
     * @param array $currentState Estado atual do step
     * @return string|null Nome do próximo step, ou null para navegação linear padrão
     */
    public function getNextStepName(array $currentState): ?string
    {
        // Por padrão, retorna null para usar navegação linear
        // Override em subclasses para implementar lógica de bifurcação
        return null;
    }

    /**
     * Define qual será o step anterior
     * Útil para navegação reversa em wizards bifurcados
     * 
     * @param array $currentState Estado atual do step
     * @return string|null Nome do step anterior, ou null para navegação linear padrão
     */
    public function getPreviousStepName(array $currentState): ?string
    {
        // Por padrão, retorna null para usar navegação linear
        return null;
    }

    /**
     * Navega para o próximo step
     */
    public function nextStep(): void
    {
        // Em Livewire real, isso seria:
        // $this->dispatch('nextStep', $this->state()->currentStep())->to($this->wizardClassName);
        
        echo "Navegando para próximo step...\n";
    }

    /**
     * Navega para o step anterior
     */
    public function previousStep(): void
    {
        // Em Livewire real, isso seria:
        // $this->dispatch('previousStep', $this->state()->currentStep())->to($this->wizardClassName);
        
        echo "Navegando para step anterior...\n";
    }

    /**
     * Navega para um step específico
     */
    public function showStep(string $stepName): void
    {
        // Em Livewire real, isso seria:
        // $this->dispatch('showStep', toStepName: $stepName, currentStepState: $this->state()->currentStep())->to($this->wizardClassName);
        
        echo "Navegando para step: {$stepName}\n";
    }

    /**
     * Verifica se existe um step anterior
     */
    public function hasPreviousStep(): bool
    {
        return !empty($this->allStepNames) && $this->allStepNames[0] !== $this->getStepName();
    }

    /**
     * Verifica se existe um próximo step
     */
    public function hasNextStep(): bool
    {
        return end($this->allStepNames) !== $this->getStepName();
    }

    /**
     * Retorna o objeto State com todo o contexto do wizard
     */
    public function state(): State
    {
        $stateClass = new $this->stateClassName();
        $stepName = $this->getStepName();

        $allState = array_merge(
            $this->allStepsState ?? [],
            [$stepName => $this->getStepData()]
        );

        $stateClass
            ->setAllState($allState)
            ->setCurrentStepName($stepName);

        return $stateClass;
    }

    /**
     * Retorna os dados do step atual
     */
    abstract public function getStepData(): array;

    /**
     * Retorna o nome do step
     */
    abstract public function getStepName(): string;

    /**
     * Renderiza a view do step
     */
    abstract public function render(): string;
}
