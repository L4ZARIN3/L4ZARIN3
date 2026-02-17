# Diagrama de Fluxo - Wizard Bifurcante

## Fluxo Visual Completo

```
┌─────────────────────────────────────────────────────────────────────┐
│                         INÍCIO DO WIZARD                             │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
                   ┌──────────────────┐
                   │   Step 1         │
                   │  ChooseType      │
                   │                  │
                   │  Radio Options:  │
                   │  ○ Pessoa Física │
                   │  ○ Pessoa Jurid. │
                   └─────────┬────────┘
                             │
                             │ User selects
                             │
              ┌──────────────┴───────────────┐
              │                              │
      if tipo == "pessoa_fisica"    if tipo == "pessoa_juridica"
              │                              │
              ▼                              ▼
    ┌──────────────────┐           ┌──────────────────┐
    │   Step 2a        │           │   Step 2b        │
    │ DadosPessoaFisica│           │ DadosPessoaJur.  │
    │                  │           │                  │
    │ Fields:          │           │ Fields:          │
    │ - Nome           │           │ - Razão Social   │
    │ - CPF            │           │ - Nome Fantasia  │
    │ - Data Nasc.     │           │ - CNPJ           │
    │ - Telefone       │           │ - Insc. Estadual │
    │                  │           │ - Telefone       │
    │                  │           │ - Responsável    │
    └─────────┬────────┘           └─────────┬────────┘
              │                              │
              │                              │
              └──────────────┬───────────────┘
                             │
                             ▼
                   ┌──────────────────┐
                   │   Step 3         │
                   │  Confirmacao     │
                   │                  │
                   │  Mostra resumo   │
                   │  dos dados       │
                   │  preenchidos     │
                   └─────────┬────────┘
                             │
                             ▼
                   ┌──────────────────┐
                   │   SUBMIT         │
                   │   Dados salvos   │
                   └──────────────────┘
```

## Navegação Reversa (Previous)

```
┌──────────────────┐
│  Confirmacao     │ getPreviousStepName() decide:
└─────────┬────────┘    ↓
          │             if tipo == "pessoa_fisica"
          │                 return "dados-pessoa-fisica"
          │             if tipo == "pessoa_juridica"
          │                 return "dados-pessoa-juridica"
          │
          ├────────────→ (PF) ┌──────────────────┐
          │                   │ DadosPessoaFisica│
          │                   └─────────┬────────┘
          │                             │ previousStep()
          │                             ▼
          └────────────→ (PJ) ┌──────────────────┐
                              │ DadosPessoaJur.  │
                              └─────────┬────────┘
                                        │ previousStep()
                                        ▼
                              ┌──────────────────┐
                              │   ChooseType     │
                              └──────────────────┘
```

## Decisões Condicionais

### ChooseTypeStep

```php
getNextStepName($state) {
    switch ($state['tipo']) {
        case 'pessoa_fisica':
            return 'dados-pessoa-fisica';
        case 'pessoa_juridica':
            return 'dados-pessoa-juridica';
        default:
            return null; // Bloqueia navegação
    }
}
```

### DadosPessoaFisicaStep

```php
getPreviousStepName($state) {
    return 'choose-type'; // Sempre volta para escolha
}

getNextStepName($state) {
    return 'confirmacao'; // Sempre vai para confirmação
}
```

### DadosPessoaJuridicaStep

```php
getPreviousStepName($state) {
    return 'choose-type'; // Sempre volta para escolha
}

getNextStepName($state) {
    return 'confirmacao'; // Sempre vai para confirmação
}
```

### ConfirmacaoStep

```php
getPreviousStepName($state) {
    $tipo = $state->get('choose-type', 'tipo');
    
    switch ($tipo) {
        case 'pessoa_fisica':
            return 'dados-pessoa-fisica';
        case 'pessoa_juridica':
            return 'dados-pessoa-juridica';
    }
}
```

## Comparação: Linear vs Bifurcante

### Wizard Linear (Spatie Original)

```
Step 1 → Step 2 → Step 3 → Step 4

- Ordem fixa
- Todos os steps são visitados
- Navegação simples
```

### Wizard Bifurcante (Esta Implementação)

```
            ┌→ Step 2a →┐
Step 1 ─────┤           ├→ Step 4
            └→ Step 2b →┘

- Ordem condicional
- Apenas steps relevantes são visitados
- Navegação inteligente
```

## Casos de Uso por Complexidade

### Simples (Linear) ✓ Spatie Original
- Checkout padrão
- Formulário de contato
- Pesquisa de satisfação

### Médio (Bifurcação Simples) ✓ Esta Implementação
- Cadastro PF/PJ
- Tipo de entrega diferente
- Plano Free/Premium

### Avançado (Bifurcação Complexa) ✓ Possível Estender
- Árvore de decisão com múltiplos níveis
- Wizard com loops (adicionar mais itens)
- Caminhos com validações condicionais

## Fluxo de Dados (State)

```
┌─────────────────────────────────────────┐
│         State Global do Wizard          │
│                                         │
│  allStepState = {                       │
│    'choose-type': {                     │
│      tipo: 'pessoa_fisica'              │
│    },                                   │
│    'dados-pessoa-fisica': {             │
│      nome: 'João Silva',                │
│      cpf: '123.456.789-00',             │
│      ...                                │
│    },                                   │
│    'confirmacao': {                     │
│      confirmado: true                   │
│    }                                    │
│  }                                      │
└─────────────────────────────────────────┘
         ▲                    │
         │                    │
         │ write              │ read
         │                    ▼
┌────────┴────────┐   ┌──────────────┐
│  Current Step   │   │  Any Step    │
│  Salva dados    │   │  Lê dados    │
└─────────────────┘   └──────────────┘
```

## Métodos de Navegação

```
┌────────────────────────────────────┐
│       WizardComponent              │
├────────────────────────────────────┤
│                                    │
│  nextStep(currentStepState)        │
│    │                               │
│    ├─→ getCurrentStepInstance()    │
│    │                               │
│    ├─→ hasMethod('getNextStepName')?
│    │    │                          │
│    │    ├─→ YES: Chama método      │
│    │    │         e usa retorno    │
│    │    │                          │
│    │    └─→ NO: Usa navegação      │
│    │              linear            │
│    │                               │
│    └─→ showStep(nextStepName)      │
│                                    │
└────────────────────────────────────┘
```

## Sequência de Eventos

```
1. User clicks "Próximo"
   ↓
2. Livewire chama $this->nextStep()
   ↓
3. Wizard salva estado do step atual
   ↓
4. Wizard obtém instância do step atual
   ↓
5. Wizard verifica se step tem getNextStepName()
   ↓
6a. SIM: Chama método com estado atual
   ↓    ↓
   │    Método retorna nome do próximo step
   │    ↓
   │    Wizard navega para esse step
   │
6b. NÃO: Usa navegação linear
   ↓    ↓
   │    Pega próximo step na lista
   │    ↓
   │    Wizard navega para esse step
   │
7. Novo step é renderizado
   ↓
8. Estado é passado para novo step
```
