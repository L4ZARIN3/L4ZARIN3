<!-- resources/views/livewire/steps/dados-pessoa-fisica.blade.php -->
<div>
    <h2 class="step-title">Dados Pessoais</h2>
    <p class="step-description">Preencha seus dados pessoais</p>

    <form class="form-grid">
        <div class="form-field full-width">
            <label for="nome">Nome Completo *</label>
            <input type="text" id="nome" wire:model="nome" placeholder="Digite seu nome completo">
            @error('nome') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="cpf">CPF *</label>
            <input type="text" id="cpf" wire:model="cpf" placeholder="000.000.000-00" maxlength="14">
            @error('cpf') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="dataNascimento">Data de Nascimento *</label>
            <input type="date" id="dataNascimento" wire:model="dataNascimento">
            @error('dataNascimento') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field full-width">
            <label for="telefone">Telefone *</label>
            <input type="tel" id="telefone" wire:model="telefone" placeholder="(00) 00000-0000" maxlength="15">
            @error('telefone') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </form>

    <div class="wizard-navigation">
        <button type="button" class="btn btn-secondary" wire:click="previousStep">
            ← Anterior
        </button>
        <button type="button" class="btn btn-primary" wire:click="nextStep">
            Próximo →
        </button>
    </div>
</div>

<style>
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.form-field {
    display: flex;
    flex-direction: column;
}

.form-field.full-width {
    grid-column: 1 / -1;
}

.form-field label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #333;
}

.form-field input {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-field input:focus {
    outline: none;
    border-color: #4CAF50;
}

.field-error {
    color: #f44336;
    font-size: 12px;
    margin-top: 4px;
}
</style>
