<?php

require_once __DIR__ . '/../../src/Components/StepComponent.php';

/**
 * Step final - Confirmação
 * Este step é comum para ambos os fluxos (PF e PJ)
 */
class ConfirmacaoStep extends BranchingWizard\Components\StepComponent
{
    /**
     * BIFURCAÇÃO REVERSA: Volta para o step correto baseado no tipo
     */
    public function getPreviousStepName(array $currentState): ?string
    {
        // Verifica qual foi o tipo escolhido para voltar ao step correto
        $state = $this->state();
        $tipo = $state->get('choose-type', 'tipo');

        return match($tipo) {
            'pessoa_fisica' => 'dados-pessoa-fisica',
            'pessoa_juridica' => 'dados-pessoa-juridica',
            default => null,
        };
    }

    public function getStepData(): array
    {
        return [
            'confirmado' => true,
        ];
    }

    public function getStepName(): string
    {
        return 'confirmacao';
    }

    public function render(): string
    {
        // Obtém o estado para exibir os dados na confirmação
        $state = $this->state();
        $tipo = $state->get('choose-type', 'tipo');

        $dadosHTML = '';

        if ($tipo === 'pessoa_fisica') {
            $dados = $state->forStep('dados-pessoa-fisica');
            $dadosHTML = <<<HTML
            <div class="dados-resumo">
                <h3>Dados Pessoais</h3>
                <p><strong>Nome:</strong> {$dados['nome']}</p>
                <p><strong>CPF:</strong> {$dados['cpf']}</p>
                <p><strong>Data de Nascimento:</strong> {$dados['data_nascimento']}</p>
                <p><strong>Telefone:</strong> {$dados['telefone']}</p>
            </div>
            HTML;
        } elseif ($tipo === 'pessoa_juridica') {
            $dados = $state->forStep('dados-pessoa-juridica');
            $dadosHTML = <<<HTML
            <div class="dados-resumo">
                <h3>Dados da Empresa</h3>
                <p><strong>Razão Social:</strong> {$dados['razao_social']}</p>
                <p><strong>Nome Fantasia:</strong> {$dados['nome_fantasia']}</p>
                <p><strong>CNPJ:</strong> {$dados['cnpj']}</p>
                <p><strong>Inscrição Estadual:</strong> {$dados['inscricao_estadual']}</p>
                <p><strong>Telefone:</strong> {$dados['telefone']}</p>
                <p><strong>Responsável:</strong> {$dados['responsavel']}</p>
            </div>
            HTML;
        }

        return <<<HTML
        <div class="step-container">
            <h2>Confirmação dos Dados</h2>
            <p>Revise os dados informados:</p>
            
            {$dadosHTML}
            
            <div class="alert alert-info">
                <strong>Atenção:</strong> Ao confirmar, seus dados serão enviados para análise.
            </div>
            
            <div class="wizard-navigation">
                <button type="button" wire:click="previousStep">Anterior</button>
                <button type="submit" class="btn-primary">Confirmar Cadastro</button>
            </div>
        </div>
        HTML;
    }
}
