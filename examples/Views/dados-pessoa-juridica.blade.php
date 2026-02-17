<!-- resources/views/livewire/steps/dados-pessoa-juridica.blade.php -->
<div>
    <h2 class="step-title">Dados da Empresa</h2>
    <p class="step-description">Preencha os dados da sua empresa</p>

    <form class="form-grid">
        <div class="form-field full-width">
            <label for="razaoSocial">Razão Social *</label>
            <input type="text" id="razaoSocial" wire:model="razaoSocial" placeholder="Digite a razão social">
            @error('razaoSocial') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field full-width">
            <label for="nomeFantasia">Nome Fantasia</label>
            <input type="text" id="nomeFantasia" wire:model="nomeFantasia" placeholder="Digite o nome fantasia">
            @error('nomeFantasia') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="cnpj">CNPJ *</label>
            <input type="text" id="cnpj" wire:model="cnpj" placeholder="00.000.000/0000-00" maxlength="18">
            @error('cnpj') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="inscricaoEstadual">Inscrição Estadual</label>
            <input type="text" id="inscricaoEstadual" wire:model="inscricaoEstadual" placeholder="Digite a IE">
            @error('inscricaoEstadual') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="telefone">Telefone *</label>
            <input type="tel" id="telefone" wire:model="telefone" placeholder="(00) 0000-0000" maxlength="14">
            @error('telefone') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="responsavel">Nome do Responsável *</label>
            <input type="text" id="responsavel" wire:model="responsavel" placeholder="Nome completo">
            @error('responsavel') <span class="field-error">{{ $message }}</span> @enderror
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
