document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const roleSelect = document.getElementById('role');
    const nextBtnStep2 = document.getElementById('next-btn-step2');
    const submitBtnStep2 = document.getElementById('submit-btn-step2');

    // Initialize first step
    document.getElementById('step1').classList.remove('hidden');

    // Navigation between steps
    document.querySelectorAll('.next-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                navigateToStep(currentStep + 1);
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(button => {
        button.addEventListener('click', function() {
            navigateToStep(currentStep - 1);
        });
    });

    // Role selection handling
    roleSelect.addEventListener('change', function() {
        if (this.value === 'company') {
            nextBtnStep2.classList.add('hidden');
            submitBtnStep2.classList.remove('hidden');
        } else {
            nextBtnStep2.classList.remove('hidden');
            submitBtnStep2.classList.add('hidden');
        }
    });

    // Step navigation function
    function navigateToStep(step) {
        if (step < 1 || step > 4) return;
    
        // Validation avant de passer à l'étape suivante
        if (step > currentStep && !validateStep(currentStep)) {
            return;
        }
    
        // Animation de transition
        const currentStepEl = document.getElementById(`step${currentStep}`);
        currentStepEl.classList.add('animate__animated', 'animate__fadeOut');
        
        setTimeout(() => {
            currentStepEl.classList.add('hidden');
            currentStepEl.classList.remove('animate__fadeOut');
            
            currentStep = step;
            const newStepEl = document.getElementById(`step${currentStep}`);
            newStepEl.classList.remove('hidden');
            newStepEl.classList.add('animate__animated', 'animate__fadeIn');
            
            setTimeout(() => {
                newStepEl.classList.remove('animate__fadeIn');
                
                // Scroll vers le haut de l'étape
                newStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        }, 300);
    }

    // Step validation
    function validateStep(step) {
        let isValid = true;
        const currentStepElement = document.getElementById(`step${step}`);
        const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
    
        // Reset errors
        currentStepElement.querySelectorAll('.error-field').forEach(el => {
            el.classList.remove('error-field', 'border-red-500');
            const errorMsg = el.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });
    
        // Gestion spécifique du bouton suivant de l'étape 2
nextBtnStep2.addEventListener('click', function() {
    if (validateStep(currentStep)) {
        navigateToStep(currentStep + 1);
    }
});

// Gestion spécifique du bouton submit de l'étape 2
submitBtnStep2.addEventListener('click', function() {
    if (validateStep(currentStep)) {
        document.getElementById('registerForm').submit();
    }
});

// Gestion spécifique du bouton "Suivant" après vérification (étape 3)
document.getElementById('next-after-verification')?.addEventListener('click', function() {
    if (validateStep(currentStep)) {
        navigateToStep(currentStep + 1);
    }
});
        // Validate required fields
        inputs.forEach(input => {
            if (!input.value.trim()) {
                markAsInvalid(input, 'Ce champ est obligatoire');
                isValid = false;
            }
            
            // Email validation for step 1
            if (step === 1 && input.id === 'email' && input.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    markAsInvalid(input, 'Veuillez entrer une adresse email valide');
                    isValid = false;
                }
            }
        });
    
        // Special validation for step 2
        if (step === 2) {
            const role = document.getElementById('role').value;
            if (!role) {
                markAsInvalid(document.getElementById('role'), 'Veuillez sélectionner un rôle');
                isValid = false;
            }
    
            if (!document.getElementById('full_name').value.trim()) {
                markAsInvalid(document.getElementById('full_name'), 'Le nom complet est obligatoire');
                isValid = false;
            }
        }
    
        return isValid;
    }
    // Mark field as invalid
    function markAsInvalid(input, message) {
        input.classList.add('error-field');
        
        // Add error message
        const errorMsg = document.createElement('div');
        errorMsg.className = 'error-message';
        errorMsg.textContent = message;
        input.parentNode.insertBefore(errorMsg, input.nextSibling);
        
        // Scroll to first error
        if (input === document.querySelector('.error-field')) {
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Real-time validation for fields
    document.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('input', function() {
            if (this.classList.contains('error-field')) {
                this.classList.remove('error-field');
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.remove();
                }
            }
        });
    });
});