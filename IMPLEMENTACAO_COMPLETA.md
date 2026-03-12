# 🎉 Implementação Concluída: Wizard Form Bifurcante

## 📋 Resumo

Foi implementado um sistema completo de **Wizard Form com Bifurcação** (branching wizard) baseado no conceito do pacote [spatie/laravel-livewire-wizard](https://github.com/spatie/laravel-livewire-wizard), mas com a adição de **navegação condicional** que permite que o fluxo mude dinamicamente baseado nas escolhas do usuário.

## ✅ O que foi implementado

### 1. Core Components (src/)

#### WizardComponent.php
- Componente base do wizard com suporte a bifurcação
- Métodos `nextStep()` e `previousStep()` modificados para consultar os steps sobre qual é o próximo/anterior
- Gerenciamento de estado compartilhado entre steps
- Fallback para navegação linear quando step não define navegação condicional

#### StepComponent.php
- Componente base para cada step do wizard
- Novos métodos para bifurcação:
  - `getNextStepName()` - Define qual é o próximo step baseado no estado
  - `getPreviousStepName()` - Define qual é o step anterior baseado no estado
- Acesso ao estado global via método `state()`

#### State.php
- Gerenciamento de estado compartilhado
- Permite qualquer step acessar dados de outros steps
- API simples: `$state->get('step-name', 'field')`

### 2. Exemplo Completo (examples/)

#### Wizard de Cadastro PF/PJ
Um wizard completo que demonstra bifurcação:

**Fluxo:**
```
Step 1: ChooseType
    ↓
    ├─ pessoa_fisica → Step 2a: DadosPessoaFisica
    └─ pessoa_juridica → Step 2b: DadosPessoaJuridica
    ↓
Step 3: Confirmacao (comum para ambos)
```

#### Steps Implementados

1. **ChooseTypeStep** - Ponto de bifurcação
   - Escolha entre Pessoa Física ou Jurídica
   - Método `getNextStepName()` retorna step diferente baseado na escolha

2. **DadosPessoaFisicaStep** - Caminho PF
   - Campos: Nome, CPF, Data Nascimento, Telefone
   - Navegação condicional para confirmação

3. **DadosPessoaJuridicaStep** - Caminho PJ
   - Campos: Razão Social, Nome Fantasia, CNPJ, IE, Telefone, Responsável
   - Navegação condicional para confirmação

4. **ConfirmacaoStep** - Step final
   - Mostra resumo dos dados
   - Navegação reversa condicional (volta para PF ou PJ dependendo da escolha inicial)

### 3. Views Blade (examples/Views/)

Templates prontos para Livewire:
- `wizard.blade.php` - Template principal com barra de progresso
- `choose-type.blade.php` - UI para seleção de tipo
- `dados-pessoa-fisica.blade.php` - Formulário PF
- `dados-pessoa-juridica.blade.php` - Formulário PJ
- `confirmacao.blade.php` - Tela de confirmação

Todos com CSS inline e design responsivo.

### 4. Documentação (docs/)

#### WIZARD_README.md (Arquivo Principal)
- Explicação completa do conceito
- Como funciona a bifurcação
- Exemplos de código
- Casos de uso
- Comparação com Spatie original

#### WIZARD_BIFURCANTE.md
- Conceitos teóricos
- Vantagens da abordagem
- Diferenças da implementação linear

#### DIAGRAMA_FLUXO.md
- Diagramas ASCII detalhados
- Fluxo visual completo
- Sequência de eventos
- Comparações visuais

### 5. Script de Demonstração

`examples/exemplo_uso.php` - Script funcional que demonstra:
- Fluxo completo de Pessoa Física
- Fluxo completo de Pessoa Jurídica
- Acesso ao estado compartilhado
- Navegação reversa
- Output com estado completo do wizard

## 🎯 Conceitos-Chave Implementados

### 1. Bifurcação (Branching)
```php
// O step decide qual é o próximo baseado no estado
public function getNextStepName(array $currentState): ?string
{
    return match($currentState['tipo']) {
        'pessoa_fisica' => 'dados-pessoa-fisica',
        'pessoa_juridica' => 'dados-pessoa-juridica',
        default => null,
    };
}
```

### 2. Navegação Condicional
- Steps não precisam seguir ordem fixa
- Caminhos diferentes para diferentes usuários
- Steps podem ser pulados ou visitados condicionalmente

### 3. Estado Compartilhado
```php
// Qualquer step pode acessar dados de outros
$tipo = $this->state()->get('choose-type', 'tipo');
```

### 4. Navegação Reversa Inteligente
```php
// Step de confirmação sabe para onde voltar
public function getPreviousStepName(array $currentState): ?string
{
    $tipo = $this->state()->get('choose-type', 'tipo');
    return $tipo === 'pessoa_fisica' 
        ? 'dados-pessoa-fisica'
        : 'dados-pessoa-juridica';
}
```

## 📊 Estrutura de Arquivos

```
L4ZARIN3/
├── WIZARD_README.md              # README principal
├── docs/
│   ├── WIZARD_BIFURCANTE.md      # Conceitos
│   └── DIAGRAMA_FLUXO.md         # Diagramas
├── src/
│   ├── Components/
│   │   ├── WizardComponent.php   # Base wizard
│   │   └── StepComponent.php     # Base step
│   ├── Support/
│   │   └── State.php             # Estado
│   └── Exceptions/               # 3 exceções
└── examples/
    ├── Wizards/
    │   └── RegistrationWizard.php
    ├── Steps/                    # 4 steps
    ├── Views/                    # 5 templates
    └── exemplo_uso.php           # Demo
```

## 🧪 Testado e Funcionando

O script `examples/exemplo_uso.php` foi executado com sucesso:

✅ Navegação para frente funciona  
✅ Bifurcação baseada em escolha funciona  
✅ Estado é compartilhado entre steps  
✅ Fluxo PF completo  
✅ Fluxo PJ completo  

Output do teste:
```
Steps disponíveis: choose-type, dados-pessoa-fisica, dados-pessoa-juridica, confirmacao

--- FLUXO 1: PESSOA FÍSICA ---
Step 1: Escolhendo tipo = pessoa_fisica
Navegou para: dados-pessoa-fisica ✓

Step 2: Preenchendo dados de PF
Navegou para: confirmacao ✓

--- FLUXO 2: PESSOA JURÍDICA ---
Step 1: Escolhendo tipo = pessoa_juridica
Navegou para: dados-pessoa-juridica ✓

Step 2: Preenchendo dados de PJ
Navegou para: confirmacao ✓
```

## 🎓 Como Usar

### Executar o Exemplo
```bash
php examples/exemplo_uso.php
```

### Em uma Aplicação Laravel/Livewire
```php
// Definir rota
Route::get('/registro', RegistrationWizard::class);

// Na view
<livewire:registration-wizard />
```

### Criar seu Próprio Wizard Bifurcante

1. Extenda `WizardComponent`:
```php
class MeuWizard extends WizardComponent {
    public function steps(): array {
        return [/* seus steps */];
    }
}
```

2. Crie steps que extendem `StepComponent`:
```php
class MeuStep extends StepComponent {
    public function getNextStepName(array $state): ?string {
        // Sua lógica de bifurcação
    }
}
```

## 🎁 Benefícios

1. **Flexibilidade** - Caminhos diferentes para diferentes tipos de usuários
2. **Experiência Otimizada** - Mostra apenas campos relevantes
3. **Manutenibilidade** - Lógica encapsulada nos steps
4. **Reutilização** - Steps podem ser compartilhados entre wizards
5. **Baseado em Padrão** - Inspirado no pacote Spatie amplamente usado

## 📚 Referências

- [Spatie Laravel Livewire Wizard](https://github.com/spatie/laravel-livewire-wizard)
- [Livewire Documentation](https://laravel-livewire.com/)
- [Laravel Documentation](https://laravel.com/)

## 🏆 Conclusão

Foi implementado um sistema completo e funcional de wizard bifurcante que permite:
- ✅ Navegação condicional baseada em escolhas
- ✅ Múltiplos caminhos no mesmo wizard
- ✅ Estado compartilhado entre steps
- ✅ Navegação reversa inteligente
- ✅ Código limpo e bem documentado
- ✅ Exemplo completo e testado
- ✅ Templates UI prontos para uso

O código está pronto para ser usado como base para implementações em aplicações Laravel/Livewire reais.
