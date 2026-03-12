<!-- resources/views/livewire/wizard.blade.php -->
<div class="wizard-wrapper">
    <!-- Header do Wizard -->
    <div class="wizard-progress">
        <div class="wizard-steps">
            @foreach($this->stepNames() as $index => $stepName)
                <div class="wizard-step {{ $stepName === $currentStepName ? 'active' : '' }} {{ $this->isStepCompleted($stepName) ? 'completed' : '' }}">
                    <div class="step-number">{{ $index + 1 }}</div>
                    <div class="step-name">{{ $this->getStepTitle($stepName) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Conteúdo do Step Atual -->
    <div class="wizard-body">
        @livewire($currentStepName, $this->getCurrentStepState())
    </div>
</div>

<style>
.wizard-wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.wizard-progress {
    margin-bottom: 40px;
}

.wizard-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.wizard-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
}

.wizard-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
    transition: all 0.3s;
}

.wizard-step.active .step-number {
    background: #4CAF50;
    color: white;
    transform: scale(1.1);
}

.wizard-step.completed .step-number {
    background: #2196F3;
    color: white;
}

.step-name {
    font-size: 12px;
    color: #666;
}

.wizard-step.active .step-name {
    color: #4CAF50;
    font-weight: bold;
}

.wizard-body {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
