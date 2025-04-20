document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    const roleSelect = document.getElementById('role');
    const nextBtnStep2 = document.getElementById('next-btn-step2');
    const submitBtnStep2 = document.getElementById('submit-btn-step2');

    // Navigation entre les étapes
    document.querySelectorAll('.next-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}`).style.display = 'none';
                currentStep++;
                document.getElementById(`step${currentStep}`).style.display = 'block';
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById(`step${currentStep}`).style.display = 'none';
            currentStep--;
            document.getElementById(`step${currentStep}`).style.display = 'block';
        });
    });

    // Gestion du changement de rôle
    roleSelect.addEventListener('change', function() {
        if (this.value === 'company') {
            nextBtnStep2.style.display = 'none';
            submitBtnStep2.style.display = 'inline-block';
        } else {
            nextBtnStep2.style.display = 'inline-block';
            submitBtnStep2.style.display = 'none';
        }
    });

    // Validation des étapes
    function validateStep(step) {
        let isValid = true;
        const currentStepElement = document.getElementById(`step${step}`);
        const inputs = currentStepElement.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });

        // Validation spécifique pour l'email
        if (step === 1) {
            const emailInput = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                isValid = false;
                emailInput.classList.add('error');
            }
        }

        return isValid;
    }
});