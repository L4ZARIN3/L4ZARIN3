<!-- resources/views/livewire/steps/confirmacao.blade.php -->
<div>
    <h2 class="step-title">Confirmação dos Dados</h2>
    <p class="step-description">Revise os dados informados antes de finalizar</p>

    <div class="confirmation-box">
        @if($state()->get('choose-type', 'tipo') === 'pessoa_fisica')
            <div class="data-section">
                <h3>📋 Dados Pessoais</h3>
                <dl class="data-list">
                    <dt>Nome:</dt>
                    <dd>{{ $state()->get('dados-pessoa-fisica', 'nome') }}</dd>
                    
                    <dt>CPF:</dt>
                    <dd>{{ $state()->get('dados-pessoa-fisica', 'cpf') }}</dd>
                    
                    <dt>Data de Nascimento:</dt>
                    <dd>{{ $state()->get('dados-pessoa-fisica', 'data_nascimento') }}</dd>
                    
                    <dt>Telefone:</dt>
                    <dd>{{ $state()->get('dados-pessoa-fisica', 'telefone') }}</dd>
                </dl>
            </div>
        @else
            <div class="data-section">
                <h3>🏢 Dados da Empresa</h3>
                <dl class="data-list">
                    <dt>Razão Social:</dt>
                    <dd>{{ $state()->get('dados-pessoa-juridica', 'razao_social') }}</dd>
                    
                    <dt>Nome Fantasia:</dt>
                    <dd>{{ $state()->get('dados-pessoa-juridica', 'nome_fantasia') ?: 'Não informado' }}</dd>
                    
                    <dt>CNPJ:</dt>
                    <dd>{{ $state()->get('dados-pessoa-juridica', 'cnpj') }}</dd>
                    
                    <dt>Inscrição Estadual:</dt>
                    <dd>{{ $state()->get('dados-pessoa-juridica', 'inscricao_estadual') ?: 'Não informado' }}</dd>
                    
                    <dt>Telefone:</dt>
                    <dd>{{ $state()->get('dados-pessoa-juridica', 'telefone') }}</dd>
                    
                    <dt>Responsável:</dt>
                    <dd>{{ $state()->get('dados-pessoa-juridica', 'responsavel') }}</dd>
                </dl>
            </div>
        @endif
    </div>

    <div class="alert alert-info">
        <strong>ℹ️ Atenção:</strong> Ao confirmar, seus dados serão enviados para análise e você receberá um email de confirmação.
    </div>

    <div class="wizard-navigation">
        <button type="button" class="btn btn-secondary" wire:click="previousStep">
            ← Anterior
        </button>
        <button type="button" class="btn btn-success" wire:click="submit">
            ✓ Confirmar Cadastro
        </button>
    </div>
</div>

<style>
.confirmation-box {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.data-section h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #333;
}

.data-list {
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 12px 20px;
}

.data-list dt {
    font-weight: 500;
    color: #666;
}

.data-list dd {
    margin: 0;
    color: #333;
}

.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-info {
    background: #e3f2fd;
    border: 1px solid #2196F3;
    color: #1976D2;
}

.btn-success {
    background: #4CAF50;
    color: white;
    font-weight: bold;
}

.btn-success:hover {
    background: #45a049;
}
</style>
