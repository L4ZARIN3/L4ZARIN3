<?php

require_once __DIR__ . '/../../src/Components/StepComponent.php';

/**
 * Step para dados de Pessoa Física
 * Acessado apenas se escolheu "pessoa_fisica" no step anterior
 */
class DadosPessoaFisicaStep extends BranchingWizard\Components\StepComponent
{
    public string $nome = '';
    public string $cpf = '';
    public string $dataNascimento = '';
    public string $telefone = '';

    /**
     * BIFURCAÇÃO REVERSA: Volta para o step de escolha
     */
    public function getPreviousStepName(array $currentState): ?string
    {
        return 'choose-type';
    }

    /**
     * Próximo step: confirmação
     */
    public function getNextStepName(array $currentState): ?string
    {
        return 'confirmacao';
    }

    public function getStepData(): array
    {
        return [
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'data_nascimento' => $this->dataNascimento,
            'telefone' => $this->telefone,
        ];
    }

    public function getStepName(): string
    {
        return 'dados-pessoa-fisica';
    }

    public function render(): string
    {
        return <<<HTML
        <div class="step-container">
            <h2>Dados Pessoais (Pessoa Física)</h2>
            <p>Preencha seus dados pessoais:</p>
            
            <form class="form-group">
                <div class="form-field">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" wire:model="nome" required>
                </div>
                
                <div class="form-field">
                    <label for="cpf">CPF *</label>
                    <input type="text" id="cpf" wire:model="cpf" 
                           placeholder="000.000.000-00" required>
                </div>
                
                <div class="form-field">
                    <label for="dataNascimento">Data de Nascimento *</label>
                    <input type="date" id="dataNascimento" wire:model="dataNascimento" required>
                </div>
                
                <div class="form-field">
                    <label for="telefone">Telefone *</label>
                    <input type="tel" id="telefone" wire:model="telefone" 
                           placeholder="(00) 00000-0000" required>
                </div>
            </form>
            
            <div class="wizard-navigation">
                <button type="button" wire:click="previousStep">Anterior</button>
                <button type="button" wire:click="nextStep">Próximo</button>
            </div>
        </div>
        HTML;
    }
}
