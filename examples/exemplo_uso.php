<?php

/**
 * Exemplo de uso do Wizard Bifurcante
 * 
 * Este arquivo demonstra como usar o wizard de registro
 * com bifurcação baseada em escolha do usuário.
 */

// Autoload das classes (em produção, use Composer)
require_once __DIR__ . '/../src/Support/State.php';
require_once __DIR__ . '/../src/Exceptions/NoNextStep.php';
require_once __DIR__ . '/../src/Exceptions/NoPreviousStep.php';
require_once __DIR__ . '/../src/Exceptions/StepDoesNotExist.php';
require_once __DIR__ . '/../src/Components/StepComponent.php';
require_once __DIR__ . '/../src/Components/WizardComponent.php';
require_once __DIR__ . '/Wizards/RegistrationWizard.php';

echo "=== WIZARD BIFURCANTE - EXEMPLO DE USO ===\n\n";

// Cria instância do wizard
$wizard = new RegistrationWizard();

// Inicia o wizard no primeiro step
$wizard->showStep('choose-type');

echo "Wizard iniciado no step: {$wizard->currentStepName}\n";
echo "Steps disponíveis: " . implode(', ', $wizard->stepNames()) . "\n\n";

// === SIMULAÇÃO DE FLUXO 1: PESSOA FÍSICA ===
echo "--- FLUXO 1: PESSOA FÍSICA ---\n\n";

// Step 1: Escolhe Pessoa Física
echo "Step 1: Escolhendo tipo = pessoa_fisica\n";
$wizard->nextStep(['tipo' => 'pessoa_fisica']);
echo "Navegou para: {$wizard->currentStepName}\n\n";

// Step 2: Preenche dados de Pessoa Física
echo "Step 2: Preenchendo dados de PF\n";
$wizard->nextStep([
    'nome' => 'João da Silva',
    'cpf' => '123.456.789-00',
    'data_nascimento' => '1990-01-15',
    'telefone' => '(11) 98765-4321',
]);
echo "Navegou para: {$wizard->currentStepName}\n\n";

// Step 3: Confirmação
echo "Step 3: Confirmação final\n";
echo "Estado completo do wizard:\n";
print_r($wizard->allStepState);
echo "\n";

// Testa navegação reversa
echo "Voltando um step...\n";
$wizard->previousStep(['confirmado' => true]);
echo "Voltou para: {$wizard->currentStepName}\n\n";

echo "\n" . str_repeat("=", 50) . "\n\n";

// === SIMULAÇÃO DE FLUXO 2: PESSOA JURÍDICA ===
echo "--- FLUXO 2: PESSOA JURÍDICA ---\n\n";

// Reinicia o wizard
$wizard = new RegistrationWizard();
$wizard->showStep('choose-type');

// Step 1: Escolhe Pessoa Jurídica
echo "Step 1: Escolhendo tipo = pessoa_juridica\n";
$wizard->nextStep(['tipo' => 'pessoa_juridica']);
echo "Navegou para: {$wizard->currentStepName}\n\n";

// Step 2: Preenche dados de Pessoa Jurídica
echo "Step 2: Preenchendo dados de PJ\n";
$wizard->nextStep([
    'razao_social' => 'Empresa XYZ Ltda',
    'nome_fantasia' => 'XYZ Tech',
    'cnpj' => '12.345.678/0001-90',
    'inscricao_estadual' => '123456789',
    'telefone' => '(11) 3456-7890',
    'responsavel' => 'Maria Santos',
]);
echo "Navegou para: {$wizard->currentStepName}\n\n";

// Step 3: Confirmação
echo "Step 3: Confirmação final\n";
echo "Estado completo do wizard:\n";
print_r($wizard->allStepState);
echo "\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "\n✅ Exemplo executado com sucesso!\n\n";

echo "PONTOS-CHAVE DA BIFURCAÇÃO:\n";
echo "1. O step 'choose-type' determina o próximo step via getNextStepName()\n";
echo "2. Baseado na escolha, vai para 'dados-pessoa-fisica' OU 'dados-pessoa-juridica'\n";
echo "3. O step de confirmação é comum aos dois fluxos\n";
echo "4. A navegação reversa também é condicional via getPreviousStepName()\n";
echo "\n";
