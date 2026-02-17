<!-- resources/views/livewire/steps/choose-type.blade.php -->
<div>
    <h2 class="step-title">Escolha o Tipo de Cadastro</h2>
    <p class="step-description">Selecione se você é Pessoa Física ou Pessoa Jurídica</p>

    <div class="type-selection">
        <label class="type-option {{ $tipo === 'pessoa_fisica' ? 'selected' : '' }}">
            <input type="radio" wire:model.live="tipo" value="pessoa_fisica">
            <div class="option-card">
                <div class="option-icon">👤</div>
                <div class="option-content">
                    <h3>Pessoa Física</h3>
                    <p>Cadastro com CPF para pessoas individuais</p>
                </div>
            </div>
        </label>

        <label class="type-option {{ $tipo === 'pessoa_juridica' ? 'selected' : '' }}">
            <input type="radio" wire:model.live="tipo" value="pessoa_juridica">
            <div class="option-card">
                <div class="option-icon">🏢</div>
                <div class="option-content">
                    <h3>Pessoa Jurídica</h3>
                    <p>Cadastro com CNPJ para empresas e organizações</p>
                </div>
            </div>
        </label>
    </div>

    @error('tipo')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <div class="wizard-navigation">
        <button type="button" class="btn btn-secondary" disabled>
            ← Anterior
        </button>
        <button type="button" class="btn btn-primary" wire:click="nextStep" {{ empty($tipo) ? 'disabled' : '' }}>
            Próximo →
        </button>
    </div>
</div>

<style>
.step-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.step-description {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
}

.type-selection {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.type-option {
    cursor: pointer;
}

.type-option input[type="radio"] {
    display: none;
}

.option-card {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 15px;
}

.type-option:hover .option-card {
    border-color: #4CAF50;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.type-option.selected .option-card {
    border-color: #4CAF50;
    background: #f1f8f4;
}

.option-icon {
    font-size: 48px;
}

.option-content h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #333;
}

.option-content p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.wizard-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #4CAF50;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #45a049;
}

.btn-primary:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

.btn-secondary {
    background: #f0f0f0;
    color: #666;
}

.error-message {
    color: #f44336;
    font-size: 14px;
    margin-top: 10px;
}
</style>
