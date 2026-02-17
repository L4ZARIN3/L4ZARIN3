<?php

require_once __DIR__ . '/../../src/Components/StepComponent.php';

/**
 * Step inicial - Escolha do tipo de cadastro
 * Aqui acontece a BIFURCAÇÃO
 */
class ChooseTypeStep extends BranchingWizard\Components\StepComponent
{
    public string $tipo = '';

    /**
     * BIFURCAÇÃO: Define o próximo step baseado na escolha
     */
    public function getNextStepName(array $currentState): ?string
    {
        $tipo = $currentState['tipo'] ?? $this->tipo;

        return match($tipo) {
            'pessoa_fisica' => 'dados-pessoa-fisica',
            'pessoa_juridica' => 'dados-pessoa-juridica',
            default => null, // Se não escolheu, não avança
        };
    }

    public function getStepData(): array
    {
        return [
            'tipo' => $this->tipo,
        ];
    }

    public function getStepName(): string
    {
        return 'choose-type';
    }

    public function render(): string
    {
        return <<<HTML
        <div class="step-container">
            <h2>Escolha o tipo de cadastro</h2>
            <p>Selecione se você é Pessoa Física ou Jurídica:</p>
            
            <div class="radio-group">
                <label>
                    <input type="radio" name="tipo" value="pessoa_fisica" required>
                    <span class="option-title">Pessoa Física (CPF)</span>
                    <span class="option-description">Para pessoas individuais</span>
                </label>
                
                <label>
                    <input type="radio" name="tipo" value="pessoa_juridica" required>
                    <span class="option-title">Pessoa Jurídica (CNPJ)</span>
                    <span class="option-description">Para empresas e organizações</span>
                </label>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" disabled>Anterior</button>
                <button type="button" wire:click="nextStep">Próximo</button>
            </div>
        </div>
        HTML;
    }
}
