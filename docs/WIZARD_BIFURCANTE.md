# Wizard Form com Bifurcação (Branching Wizard)

## O que é um Wizard Bifurcante?

Um wizard bifurcante é um formulário multi-step onde o fluxo de navegação muda dinamicamente baseado nas escolhas do usuário. Ao invés de seguir uma sequência linear de steps (A → B → C → D), o wizard pode ramificar para diferentes caminhos.

## Conceito

```
                    ┌─────────────┐
                    │   Step 1    │
                    │   Escolha   │
                    │   o tipo    │
                    └──────┬──────┘
                           │
              ┌────────────┴────────────┐
              │                         │
              ▼                         ▼
      ┌───────────────┐         ┌───────────────┐
      │  Tipo A       │         │  Tipo B       │
      │  (Ex: PF)     │         │  (Ex: PJ)     │
      └───────┬───────┘         └───────┬───────┘
              │                         │
              ▼                         ▼
      ┌───────────────┐         ┌───────────────┐
      │  Dados PF     │         │  Dados PJ     │
      │  (CPF, etc)   │         │  (CNPJ, etc)  │
      └───────┬───────┘         └───────┬───────┘
              │                         │
              └────────────┬────────────┘
                           │
                           ▼
                   ┌───────────────┐
                   │ Confirmação   │
                   │    Final      │
                   └───────────────┘
```

## Como Funciona

### 1. Base Wizard Component

O wizard base gerencia o estado e a navegação entre steps:

```php
abstract class WizardComponent extends Component
{
    public array $allStepState = [];
    public ?string $currentStepName = null;
    
    abstract public function steps(): array;
    
    // Método modificado para suportar bifurcação
    public function nextStep(array $currentStepState)
    {
        $currentStep = $this->getCurrentStepInstance();
        
        // Permite que o step determine o próximo dinamicamente
        if (method_exists($currentStep, 'getNextStepName')) {
            $nextStepName = $currentStep->getNextStepName($currentStepState);
        } else {
            // Fallback para navegação linear
            $nextStepName = $this->getLinearNextStep();
        }
        
        $this->showStep($nextStepName, $currentStepState);
    }
}
```

### 2. Step Component com Lógica de Bifurcação

Cada step pode definir qual será o próximo step baseado no estado atual:

```php
class ChooseTypeStepComponent extends StepComponent
{
    public string $tipo = '';
    
    // Define o próximo step baseado na escolha
    public function getNextStepName(array $state): string
    {
        return match($this->tipo) {
            'pessoa_fisica' => 'dados-pessoa-fisica-step',
            'pessoa_juridica' => 'dados-pessoa-juridica-step',
            default => throw new \Exception('Tipo não selecionado'),
        };
    }
    
    public function render()
    {
        return view('steps.choose-type');
    }
}
```

### 3. Wizard Concreto com Todos os Steps

```php
class RegistrationWizardComponent extends WizardComponent
{
    public function steps(): array
    {
        return [
            // Step inicial - comum a todos
            ChooseTypeStepComponent::class,
            
            // Steps para Pessoa Física
            DadosPessoaFisicaStepComponent::class,
            EnderecoPFStepComponent::class,
            
            // Steps para Pessoa Jurídica
            DadosPessoaJuridicaStepComponent::class,
            EnderecoPJStepComponent::class,
            
            // Step final - comum a todos
            ConfirmacaoStepComponent::class,
        ];
    }
}
```

## Vantagens

1. **Flexibilidade**: Caminhos diferentes para diferentes tipos de usuários
2. **Experiência Otimizada**: Mostra apenas campos relevantes para cada caso
3. **Manutenibilidade**: Lógica de decisão encapsulada nos steps
4. **Reutilização**: Steps podem ser compartilhados entre diferentes wizards

## Casos de Uso

- **Cadastro com tipos diferentes**: PF/PJ, Cliente/Fornecedor
- **Checkout personalizado**: Frete Normal/Expresso, Pagamento diferente
- **Onboarding adaptativo**: Diferentes fluxos por perfil de usuário
- **Formulários complexos**: Tributação, processos governamentais

## Implementação Baseada em Spatie

Esta implementação é inspirada no pacote [spatie/laravel-livewire-wizard](https://github.com/spatie/laravel-livewire-wizard), mas adiciona a capacidade de navegação condicional (bifurcação).

### Diferenças Principais

**Spatie Original (Linear):**
```php
public function nextStep() {
    // Sempre vai para o próximo step na lista
    $nextStep = $steps->after(current);
}
```

**Nossa Implementação (Bifurcante):**
```php
public function nextStep() {
    // Pergunta ao step atual qual deve ser o próximo
    $nextStep = $currentStep->getNextStepName($state);
}
```

## Exemplo Completo

Veja o diretório `/examples` para uma implementação completa com:
- Wizard de cadastro PF/PJ
- Steps com bifurcação
- Views Blade
- Validação em cada step
