<?php

require_once __DIR__ . '/../../src/Components/WizardComponent.php';
require_once __DIR__ . '/../Steps/ChooseTypeStep.php';
require_once __DIR__ . '/../Steps/DadosPessoaFisicaStep.php';
require_once __DIR__ . '/../Steps/DadosPessoaJuridicaStep.php';
require_once __DIR__ . '/../Steps/ConfirmacaoStep.php';

/**
 * Wizard de Registro com Bifurcação
 * 
 * Este wizard demonstra como criar um fluxo que bifurca baseado na escolha do usuário.
 * 
 * Fluxo:
 * 1. ChooseTypeStep - Usuário escolhe entre PF ou PJ
 * 2a. DadosPessoaFisicaStep - Se escolheu PF
 * 2b. DadosPessoaJuridicaStep - Se escolheu PJ
 * 3. ConfirmacaoStep - Step final comum
 */
class RegistrationWizard extends BranchingWizard\Components\WizardComponent
{
    /**
     * Define todos os steps possíveis do wizard
     * 
     * Importante: Todos os steps devem ser listados aqui, mesmo que
     * nem todos sejam visitados em um único fluxo.
     */
    public function steps(): array
    {
        return [
            ChooseTypeStep::class,           // Step 1: Escolha (comum a todos)
            DadosPessoaFisicaStep::class,    // Step 2a: Dados PF (condicional)
            DadosPessoaJuridicaStep::class,  // Step 2b: Dados PJ (condicional)
            ConfirmacaoStep::class,          // Step 3: Confirmação (comum a todos)
        ];
    }

    /**
     * Estado inicial do wizard
     */
    public function initialState(): ?array
    {
        return [
            'wizard_started_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Renderiza o wizard principal
     */
    public function render(): string
    {
        // Mostra o progresso do wizard
        $stepNames = $this->stepNames();
        $currentIndex = array_search($this->currentStepName, $stepNames);
        $progress = ($currentIndex + 1) / count($stepNames) * 100;
        $currentStep = $currentIndex + 1;

        $currentStepContent = parent::render();

        return <<<HTML
        <div class="wizard-container">
            <div class="wizard-header">
                <h1>Cadastro de Usuário</h1>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {$progress}%"></div>
                </div>
                <p class="progress-text">Passo {$currentStep} de 3</p>
            </div>
            
            <div class="wizard-content">
                {$currentStepContent}
            </div>
            
            <div class="wizard-footer">
                <p class="help-text">
                    <small>Dúvidas? Entre em contato com nosso suporte.</small>
                </p>
            </div>
        </div>
        HTML;
    }

    /**
     * Callback chamado quando o wizard é concluído
     */
    public function onComplete(): void
    {
        // Aqui você pode salvar os dados no banco, enviar email, etc.
        echo "Wizard concluído! Dados salvos com sucesso.\n";
        
        // Exemplo: acessar todos os dados
        $allData = $this->allStepState;
        // Processar $allData...
    }
}
