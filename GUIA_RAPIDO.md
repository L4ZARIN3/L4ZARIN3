# 🚀 Guia Rápido - Wizard Bifurcante

## 📖 Onde Começar?

### 1️⃣ Ler a Documentação Principal
```
📄 WIZARD_README.md
   ↳ Guia completo com todos os conceitos
   ↳ Exemplos de código
   ↳ Como usar
```

### 2️⃣ Ver o Exemplo Funcionando
```bash
php examples/exemplo_uso.php
```

### 3️⃣ Entender o Código
```
📁 src/Components/
   ↳ WizardComponent.php  - Leia os comentários aqui
   ↳ StepComponent.php    - Entenda os métodos de bifurcação
```

### 4️⃣ Explorar o Exemplo
```
📁 examples/Steps/
   ↳ ChooseTypeStep.php          - Veja como bifurcar
   ↳ DadosPessoaFisicaStep.php   - Caminho A
   ↳ DadosPessoaJuridicaStep.php - Caminho B
   ↳ ConfirmacaoStep.php         - Convergência
```

## 📚 Índice de Documentação

| Documento | O que contém |
|-----------|--------------|
| `WIZARD_README.md` | 📘 README principal - comece aqui |
| `IMPLEMENTACAO_COMPLETA.md` | 📊 Resumo do que foi implementado |
| `docs/WIZARD_BIFURCANTE.md` | 📖 Conceitos teóricos |
| `docs/DIAGRAMA_FLUXO.md` | 📐 Diagramas visuais de fluxo |
| Este arquivo | 🗺️ Guia de navegação rápida |

## 🎯 Casos de Uso para se Inspirar

### ✅ Já Implementado
- Cadastro Pessoa Física / Pessoa Jurídica

### 💡 Você pode criar
- Checkout com diferentes tipos de entrega
- Onboarding por perfil de usuário
- Solicitação de crédito com tipos diferentes
- Formulário de imposto com regimes diferentes
- Processo de contratação por cargo

## 🔑 Conceitos-Chave

### Bifurcação
```php
// Em ChooseTypeStep.php
public function getNextStepName(array $currentState): ?string
{
    return match($currentState['tipo']) {
        'pessoa_fisica' => 'dados-pessoa-fisica',
        'pessoa_juridica' => 'dados-pessoa-juridica',
    };
}
```

### Estado Compartilhado
```php
// Em qualquer step
$tipo = $this->state()->get('choose-type', 'tipo');
$nome = $this->state()->get('dados-pessoa-fisica', 'nome');
```

### Navegação Reversa
```php
// Em ConfirmacaoStep.php
public function getPreviousStepName(array $currentState): ?string
{
    $tipo = $this->state()->get('choose-type', 'tipo');
    return $tipo === 'pessoa_fisica' ? 'dados-pessoa-fisica' : 'dados-pessoa-juridica';
}
```

## 🛠️ Como Adaptar para Seu Projeto

### Passo 1: Entenda a Estrutura
```
WizardComponent (base)
    ├─ define steps()
    └─ gerencia navegação
    
StepComponent (base)
    ├─ getNextStepName() - para bifurcação
    ├─ getPreviousStepName() - para voltar
    └─ state() - para acessar dados
```

### Passo 2: Crie seu Wizard
```php
class MeuWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            MeuPrimeiroStep::class,
            MeuSegundoStepA::class,
            MeuSegundoStepB::class,
            MeuTerceiroStep::class,
        ];
    }
}
```

### Passo 3: Implemente Bifurcação
```php
class MeuPrimeiroStep extends StepComponent
{
    public function getNextStepName(array $currentState): ?string
    {
        // Sua lógica aqui
        if ($currentState['escolha'] === 'opcao_a') {
            return 'meu-segundo-step-a';
        }
        return 'meu-segundo-step-b';
    }
}
```

## 📝 Checklist para Criar Seu Wizard

- [ ] Definir o fluxo no papel (desenhe o diagrama)
- [ ] Identificar pontos de bifurcação
- [ ] Criar a classe Wizard (extends WizardComponent)
- [ ] Criar classes para cada Step (extends StepComponent)
- [ ] Implementar getNextStepName() nos steps que bifurcam
- [ ] Implementar getPreviousStepName() nos steps de convergência
- [ ] Criar views Blade para cada step
- [ ] Testar o fluxo completo

## 🎨 Personalizando as Views

As views estão em `examples/Views/` com CSS inline.
Você pode:

1. Mover o CSS para arquivo separado
2. Usar Tailwind CSS
3. Integrar com seu framework de UI
4. Adicionar JavaScript/Alpine.js
5. Personalizar os componentes visuais

## ⚡ Dicas e Truques

### Validação Antes de Avançar
```php
public function nextStep()
{
    $this->validate([
        'campo' => 'required',
    ]);
    
    parent::nextStep();
}
```

### Pular Steps
```php
public function getNextStepName(array $currentState): ?string
{
    if ($this->pularValidacao) {
        return 'confirmacao'; // Pula steps intermediários
    }
    return 'proximo-step-normal';
}
```

### Loops (Adicionar Mais)
```php
public function getNextStepName(array $currentState): ?string
{
    if ($this->adicionarMais) {
        return 'adicionar-item'; // Volta para adicionar
    }
    return 'resumo';
}
```

### Decisões Complexas
```php
public function getNextStepName(array $currentState): ?string
{
    $score = $this->calcularScore($currentState);
    
    return match(true) {
        $score > 80 => 'oferta-premium',
        $score > 50 => 'oferta-padrao',
        default => 'oferta-basica',
    };
}
```

## 🐛 Problemas Comuns

### Step não aparece na lista
- Certifique-se que está no array `steps()`
- Verifique se a classe existe e pode ser instanciada

### Navegação não funciona
- Verifique se `getNextStepName()` retorna nome válido
- Confirme que o nome do step está correto (use `stepNames()`)

### Estado não persiste
- Use `$this->state()` para acessar
- Certifique-se de salvar dados no step antes de avançar

## 📞 Suporte

Para entender melhor:
1. Leia `WIZARD_README.md` - documentação completa
2. Execute `php examples/exemplo_uso.php` - veja funcionando
3. Estude os arquivos em `examples/Steps/` - código comentado
4. Veja os diagramas em `docs/DIAGRAMA_FLUXO.md`

## 🎓 Recursos Adicionais

- [Spatie Wizard Original](https://github.com/spatie/laravel-livewire-wizard)
- [Livewire Docs](https://laravel-livewire.com/)
- [Laravel Docs](https://laravel.com/)

## ✨ Conclusão

Você tem agora:
- ✅ Sistema completo de wizard bifurcante
- ✅ Exemplo funcional testado
- ✅ Documentação detalhada
- ✅ Views prontas para usar
- ✅ Base para criar seus próprios wizards

**Boa sorte com sua implementação! 🚀**

---

*Criado com ❤️ por [L4ZARIN3](https://github.com/L4ZARIN3)*
