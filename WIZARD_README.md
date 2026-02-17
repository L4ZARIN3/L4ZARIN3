# 🧙‍♂️ Wizard Form Bifurcante (Branching Wizard)

> Implementação de um wizard multi-step com navegação condicional (bifurcação) baseado no pacote [spatie/laravel-livewire-wizard](https://github.com/spatie/laravel-livewire-wizard)

## 📖 O que é?

Um **Wizard Bifurcante** é um formulário multi-etapas onde o fluxo de navegação **muda dinamicamente** baseado nas escolhas do usuário. Ao invés de seguir uma sequência linear fixa (Step 1 → Step 2 → Step 3), o wizard pode ramificar para diferentes caminhos.

## 🎯 Caso de Uso: Cadastro PF/PJ

Este exemplo implementa um wizard de cadastro que bifurca baseado no tipo de pessoa:

```
                    ┌─────────────────────┐
                    │   Step 1: Escolha   │
                    │  PF ou PJ?          │
                    └──────────┬──────────┘
                               │
                ┌──────────────┴──────────────┐
                │                             │
                ▼                             ▼
        ┌──────────────┐            ┌──────────────┐
        │   PF Path    │            │   PJ Path    │
        │              │            │              │
        │ Step 2a:     │            │ Step 2b:     │
        │ Dados PF     │            │ Dados PJ     │
        │ (CPF, etc)   │            │ (CNPJ, etc)  │
        └──────┬───────┘            └──────┬───────┘
               │                           │
               └──────────┬────────────────┘
                          │
                          ▼
                 ┌────────────────┐
                 │ Step 3: Final  │
                 │  Confirmação   │
                 └────────────────┘
```

## 🚀 Como Funciona

### 1. Base Components

#### WizardComponent (Base do Wizard)

```php
abstract class WizardComponent
{
    public array $allStepState = [];
    public ?string $currentStepName = null;
    
    abstract public function steps(): array;
    
    // MODIFICADO: Suporta bifurcação
    public function nextStep(array $currentStepState)
    {
        $currentStepInstance = $this->getCurrentStepInstance();
        
        // Pergunta ao step qual é o próximo
        if (method_exists($currentStepInstance, 'getNextStepName')) {
            $nextStepName = $currentStepInstance->getNextStepName($currentStepState);
            
            if ($nextStepName !== null) {
                $this->showStep($nextStepName, $currentStepState);
                return;
            }
        }
        
        // Fallback: navegação linear
        $this->showStep($this->getLinearNextStep(), $currentStepState);
    }
}
```

#### StepComponent (Base dos Steps)

```php
abstract class StepComponent
{
    /**
     * Define qual será o próximo step
     * Override para implementar bifurcação
     */
    public function getNextStepName(array $currentState): ?string
    {
        return null; // null = navegação linear padrão
    }
    
    /**
     * Define qual foi o step anterior
     * Útil para navegação reversa em wizards bifurcados
     */
    public function getPreviousStepName(array $currentState): ?string
    {
        return null;
    }
}
```

### 2. Implementando a Bifurcação

#### Step com Escolha (Ponto de Bifurcação)

```php
class ChooseTypeStep extends StepComponent
{
    public string $tipo = '';
    
    /**
     * 🔀 BIFURCAÇÃO: Decide o caminho baseado na escolha
     */
    public function getNextStepName(array $currentState): ?string
    {
        $tipo = $currentState['tipo'] ?? $this->tipo;
        
        return match($tipo) {
            'pessoa_fisica' => 'dados-pessoa-fisica',
            'pessoa_juridica' => 'dados-pessoa-juridica',
            default => null, // Não avança se não escolheu
        };
    }
}
```

#### Steps Condicionais

```php
class DadosPessoaFisicaStep extends StepComponent
{
    // Este step só é visitado se escolheu "pessoa_fisica"
    
    public function getPreviousStepName(array $currentState): ?string
    {
        return 'choose-type'; // Volta para a escolha
    }
    
    public function getNextStepName(array $currentState): ?string
    {
        return 'confirmacao'; // Vai para confirmação
    }
}

class DadosPessoaJuridicaStep extends StepComponent
{
    // Este step só é visitado se escolheu "pessoa_juridica"
    
    public function getPreviousStepName(array $currentState): ?string
    {
        return 'choose-type';
    }
    
    public function getNextStepName(array $currentState): ?string
    {
        return 'confirmacao';
    }
}
```

#### Step de Convergência

```php
class ConfirmacaoStep extends StepComponent
{
    /**
     * 🔀 BIFURCAÇÃO REVERSA: Volta ao step correto
     */
    public function getPreviousStepName(array $currentState): ?string
    {
        $tipo = $this->state()->get('choose-type', 'tipo');
        
        return match($tipo) {
            'pessoa_fisica' => 'dados-pessoa-fisica',
            'pessoa_juridica' => 'dados-pessoa-juridica',
            default => null,
        };
    }
}
```

### 3. Wizard Concreto

```php
class RegistrationWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            ChooseTypeStep::class,           // Comum
            DadosPessoaFisicaStep::class,    // Condicional (PF)
            DadosPessoaJuridicaStep::class,  // Condicional (PJ)
            ConfirmacaoStep::class,          // Comum
        ];
    }
}
```

## 📁 Estrutura do Projeto

```
L4ZARIN3/
├── docs/
│   └── WIZARD_BIFURCANTE.md          # Documentação detalhada
├── src/
│   ├── Components/
│   │   ├── WizardComponent.php       # Base do wizard com bifurcação
│   │   └── StepComponent.php         # Base dos steps
│   ├── Support/
│   │   └── State.php                 # Gerenciamento de estado
│   └── Exceptions/
│       ├── NoNextStep.php
│       ├── NoPreviousStep.php
│       └── StepDoesNotExist.php
└── examples/
    ├── Wizards/
    │   └── RegistrationWizard.php    # Wizard de exemplo
    ├── Steps/
    │   ├── ChooseTypeStep.php        # Step de escolha (bifurcação)
    │   ├── DadosPessoaFisicaStep.php # Step PF
    │   ├── DadosPessoaJuridicaStep.php # Step PJ
    │   └── ConfirmacaoStep.php       # Step final
    ├── Views/
    │   ├── wizard.blade.php          # Template do wizard
    │   ├── choose-type.blade.php
    │   ├── dados-pessoa-fisica.blade.php
    │   ├── dados-pessoa-juridica.blade.php
    │   └── confirmacao.blade.php
    └── exemplo_uso.php               # Script de demonstração
```

## 🎮 Como Usar

### 1. Executar o Exemplo

```bash
php examples/exemplo_uso.php
```

Este script demonstra:
- Fluxo completo de Pessoa Física
- Fluxo completo de Pessoa Jurídica
- Navegação reversa condicional
- Acesso ao estado compartilhado

### 2. Em uma Aplicação Livewire

```php
// Em uma rota ou controller
Route::get('/registro', RegistrationWizard::class);
```

```blade
<!-- Na view -->
<livewire:registration-wizard />
```

## 🎨 Features

### ✅ Navegação Condicional
- Steps decidem dinamicamente o próximo/anterior
- Suporte a múltiplos caminhos
- Navegação reversa inteligente

### ✅ Estado Compartilhado
- Todos os steps podem acessar dados de outros steps
- Estado persistido durante toda a jornada
- API simples: `$this->state()->get('step-name', 'field')`

### ✅ Validação por Step
- Cada step pode ter suas próprias regras
- Validação antes de avançar
- Mensagens de erro contextuais

### ✅ UI Responsiva
- Templates Blade prontos
- Estilos CSS incluídos
- Indicador de progresso visual

## 🔑 Conceitos-Chave

### Bifurcação vs Linear

**Linear (Spatie Original):**
```php
Steps: A → B → C → D
// Sempre segue essa ordem
```

**Bifurcante (Esta Implementação):**
```php
Steps: A → (B1 ou B2) → C
// Escolhe o caminho baseado no estado
```

### Métodos Principais

| Método | Propósito | Quando Override |
|--------|-----------|-----------------|
| `getNextStepName()` | Define próximo step | Quando step bifurca |
| `getPreviousStepName()` | Define step anterior | Em steps convergentes |
| `state()->get()` | Acessa dados de outros steps | Para decisões condicionais |
| `state()->forStep()` | Pega todo estado de um step | Para exibição de resumo |

## 🎯 Casos de Uso

### 1. Tipos de Cadastro
- Pessoa Física vs Jurídica
- Cliente vs Fornecedor
- Usuário vs Admin

### 2. Checkout Personalizado
- Tipo de entrega (Padrão/Expresso/Retirada)
- Forma de pagamento (Cartão/Boleto/Pix)
- Tipo de fatura (Simples/Detalhada)

### 3. Onboarding Adaptativo
- Perfil do usuário (Iniciante/Avançado)
- Tipo de conta (Gratuita/Premium)
- Área de interesse

### 4. Processos Complexos
- Declaração de impostos
- Solicitação de crédito
- Formulários governamentais

## 🆚 Diferenças do Spatie Original

| Aspecto | Spatie Original | Esta Implementação |
|---------|----------------|-------------------|
| Navegação | Linear fixa | Condicional/Bifurcante |
| Ordem dos Steps | Sempre sequencial | Pode pular steps |
| Lógica de Roteamento | No wizard | Nos steps |
| Complexidade | Simples | Média |
| Casos de Uso | Fluxos simples | Fluxos complexos |

## 🔧 Extensões Possíveis

### 1. Skip Steps
```php
public function getNextStepName(array $currentState): ?string
{
    if ($this->shouldSkipValidation()) {
        return 'final-step'; // Pula validação
    }
    return 'validation-step';
}
```

### 2. Loops
```php
public function getNextStepName(array $currentState): ?string
{
    if ($this->wantsAddMore()) {
        return 'add-item-step'; // Volta para adicionar mais
    }
    return 'summary-step';
}
```

### 3. Caminhos Complexos
```php
public function getNextStepName(array $currentState): ?string
{
    $score = $this->calculateScore($currentState);
    
    return match(true) {
        $score > 80 => 'premium-offer-step',
        $score > 50 => 'standard-offer-step',
        default => 'basic-offer-step',
    };
}
```

## 📚 Referências

- [Spatie Laravel Livewire Wizard](https://github.com/spatie/laravel-livewire-wizard) - Pacote original
- [Livewire](https://laravel-livewire.com/) - Framework de componentes
- [Laravel](https://laravel.com/) - Framework PHP

## 🤝 Contribuindo

Este é um exemplo educacional. Sinta-se livre para:
- Adaptar para seus projetos
- Melhorar a implementação
- Adicionar novos exemplos
- Criar casos de uso diferentes

## 📝 Licença

MIT - Projeto educacional baseado no trabalho da Spatie

---

<div align="center">

**Feito com ❤️ por [L4ZARIN3](https://github.com/L4ZARIN3)**

*"Quando o caminho não é linear, a bifurcação é a solução"*

</div>
