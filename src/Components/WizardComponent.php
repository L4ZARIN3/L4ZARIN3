<?php

namespace BranchingWizard\Components;

use BranchingWizard\Support\State;
use BranchingWizard\Exceptions\NoNextStep;
use BranchingWizard\Exceptions\NoPreviousStep;
use BranchingWizard\Exceptions\StepDoesNotExist;

/**
 * Componente base para o Wizard com suporte a bifurcação
 * 
 * Em uma aplicação Livewire real, isso extenderia Livewire\Component
 * Para fins de exemplo, esta é uma implementação standalone
 */
abstract class WizardComponent
{
    public array $allStepState = [];
    public ?string $currentStepName = null;
    public ?array $initialState = null;

    /**
     * Define os steps do wizard
     * Retorna array com as classes dos steps
     * 
     * @return array<int, class-string<StepComponent>>
     */
    abstract public function steps(): array;

    /**
     * Estado inicial do wizard (opcional)
     */
    public function initialState(): ?array
    {
        return null;
    }

    /**
     * Retorna os nomes de todos os steps
     */
    public function stepNames(): array
    {
        $steps = [];
        foreach ($this->steps() as $stepClass) {
            $steps[] = $this->getStepNameFromClass($stepClass);
        }
        return $steps;
    }

    /**
     * Navega para o step anterior
     * 
     * MODIFICADO: Suporta bifurcação - pergunta ao step atual qual é o anterior
     */
    public function previousStep(array $currentStepState): void
    {
        $currentStepInstance = $this->getCurrentStepInstance();
        
        // Verifica se o step define o anterior dinamicamente (bifurcação)
        if (method_exists($currentStepInstance, 'getPreviousStepName')) {
            $previousStepName = $currentStepInstance->getPreviousStepName($currentStepState);
            
            if ($previousStepName !== null) {
                $this->showStep($previousStepName, $currentStepState);
                return;
            }
        }

        // Navegação linear padrão
        $stepNames = $this->stepNames();
        $currentIndex = array_search($this->currentStepName, $stepNames);
        
        if ($currentIndex === 0 || $currentIndex === false) {
            throw new NoPreviousStep(static::class, $this->currentStepName);
        }

        $previousStep = $stepNames[$currentIndex - 1];
        $this->showStep($previousStep, $currentStepState);
    }

    /**
     * Navega para o próximo step
     * 
     * MODIFICADO: Suporta bifurcação - pergunta ao step atual qual é o próximo
     */
    public function nextStep(array $currentStepState): void
    {
        $currentStepInstance = $this->getCurrentStepInstance();
        
        // Verifica se o step define o próximo dinamicamente (bifurcação)
        if (method_exists($currentStepInstance, 'getNextStepName')) {
            $nextStepName = $currentStepInstance->getNextStepName($currentStepState);
            
            if ($nextStepName !== null) {
                $this->showStep($nextStepName, $currentStepState);
                return;
            }
        }

        // Navegação linear padrão
        $stepNames = $this->stepNames();
        $currentIndex = array_search($this->currentStepName, $stepNames);
        
        if ($currentIndex === false || $currentIndex >= count($stepNames) - 1) {
            throw new NoNextStep(static::class, $this->currentStepName);
        }

        $nextStep = $stepNames[$currentIndex + 1];
        $this->showStep($nextStep, $currentStepState);
    }

    /**
     * Mostra um step específico
     */
    public function showStep(string $toStepName, array $currentStepState = []): void
    {
        if ($this->currentStepName) {
            $this->setStepState($this->currentStepName, $currentStepState);
        }

        $this->currentStepName = $toStepName;
    }

    /**
     * Define o estado de um step
     */
    public function setStepState(string $step, array $state = []): void
    {
        if (!in_array($step, $this->stepNames())) {
            throw new StepDoesNotExist($step);
        }

        $this->allStepState[$step] = $state;
    }

    /**
     * Retorna o estado de um step
     */
    public function getCurrentStepState(?string $step = null): array
    {
        $stepName = $step ?? $this->currentStepName;

        if (!in_array($stepName, $this->stepNames())) {
            throw new StepDoesNotExist($stepName);
        }

        return array_merge(
            $this->allStepState[$stepName] ?? [],
            [
                'allStepNames' => $this->stepNames(),
                'allStepsState' => $this->allStepState,
                'stateClassName' => $this->stateClass(),
                'wizardClassName' => static::class,
            ]
        );
    }

    /**
     * Retorna a instância do step atual
     */
    protected function getCurrentStepInstance(): StepComponent
    {
        $stepClass = $this->getStepClassFromName($this->currentStepName);
        return new $stepClass();
    }

    /**
     * Converte nome do step para classe
     */
    protected function getStepClassFromName(string $stepName): string
    {
        foreach ($this->steps() as $stepClass) {
            if ($this->getStepNameFromClass($stepClass) === $stepName) {
                return $stepClass;
            }
        }
        throw new StepDoesNotExist($stepName);
    }

    /**
     * Converte classe do step para nome
     */
    protected function getStepNameFromClass(string $stepClass): string
    {
        // Converte "App\Steps\MyStepComponent" para "my-step"
        $parts = explode('\\', $stepClass);
        $className = end($parts);
        $name = str_replace('Component', '', $className);
        $name = str_replace('Step', '', $name);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
    }

    /**
     * Retorna a classe State usada
     */
    public function stateClass(): string
    {
        return State::class;
    }

    /**
     * Renderiza o wizard
     */
    public function render(): string
    {
        $currentStepState = $this->getCurrentStepState();
        $stepClass = $this->getStepClassFromName($this->currentStepName);
        $stepInstance = new $stepClass();
        
        return $stepInstance->render();
    }
}
