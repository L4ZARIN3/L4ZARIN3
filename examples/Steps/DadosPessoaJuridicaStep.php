<?php

require_once __DIR__ . '/../../src/Components/StepComponent.php';

/**
 * Step para dados de Pessoa Jurídica
 * Acessado apenas se escolheu "pessoa_juridica" no step anterior
 */
class DadosPessoaJuridicaStep extends BranchingWizard\Components\StepComponent
{
    public string $razaoSocial = '';
    public string $nomeFantasia = '';
    public string $cnpj = '';
    public string $inscricaoEstadual = '';
    public string $telefone = '';
    public string $responsavel = '';

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
            'razao_social' => $this->razaoSocial,
            'nome_fantasia' => $this->nomeFantasia,
            'cnpj' => $this->cnpj,
            'inscricao_estadual' => $this->inscricaoEstadual,
            'telefone' => $this->telefone,
            'responsavel' => $this->responsavel,
        ];
    }

    public function getStepName(): string
    {
        return 'dados-pessoa-juridica';
    }

    public function render(): string
    {
        return <<<HTML
        <div class="step-container">
            <h2>Dados da Empresa (Pessoa Jurídica)</h2>
            <p>Preencha os dados da sua empresa:</p>
            
            <form class="form-group">
                <div class="form-field">
                    <label for="razaoSocial">Razão Social *</label>
                    <input type="text" id="razaoSocial" wire:model="razaoSocial" required>
                </div>
                
                <div class="form-field">
                    <label for="nomeFantasia">Nome Fantasia</label>
                    <input type="text" id="nomeFantasia" wire:model="nomeFantasia">
                </div>
                
                <div class="form-field">
                    <label for="cnpj">CNPJ *</label>
                    <input type="text" id="cnpj" wire:model="cnpj" 
                           placeholder="00.000.000/0000-00" required>
                </div>
                
                <div class="form-field">
                    <label for="inscricaoEstadual">Inscrição Estadual</label>
                    <input type="text" id="inscricaoEstadual" wire:model="inscricaoEstadual">
                </div>
                
                <div class="form-field">
                    <label for="telefone">Telefone *</label>
                    <input type="tel" id="telefone" wire:model="telefone" 
                           placeholder="(00) 0000-0000" required>
                </div>
                
                <div class="form-field">
                    <label for="responsavel">Nome do Responsável *</label>
                    <input type="text" id="responsavel" wire:model="responsavel" required>
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
