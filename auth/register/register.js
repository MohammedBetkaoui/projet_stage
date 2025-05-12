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
            // Pour les entreprises, on affiche le bouton de soumission directe
            nextBtnStep2.classList.add('hidden');
            submitBtnStep2.classList.remove('hidden');
        } else if (this.value === 'student') {
            // Pour les étudiants, on affiche le bouton suivant pour aller à l'étape de vérification
            nextBtnStep2.classList.remove('hidden');
            submitBtnStep2.classList.add('hidden');
        } else {
            // Par défaut, masquer les deux boutons jusqu'à ce qu'un rôle soit sélectionné
            nextBtnStep2.classList.add('hidden');
            submitBtnStep2.classList.add('hidden');
        }
    });

    // Vérifier l'état initial des boutons en fonction du rôle sélectionné
    if (roleSelect.value === 'company') {
        nextBtnStep2.classList.add('hidden');
        submitBtnStep2.classList.remove('hidden');
    } else if (roleSelect.value === 'student') {
        nextBtnStep2.classList.remove('hidden');
        submitBtnStep2.classList.add('hidden');
    } else {
        nextBtnStep2.classList.add('hidden');
        submitBtnStep2.classList.add('hidden');
    }

    // Step navigation function
    function navigateToStep(step) {
        console.log("Navigation vers l'étape", step, "depuis l'étape", currentStep);

        // Vérifier que l'étape est valide
        if (step < 1 || step > 4) {
            console.error("Étape invalide:", step);
            return;
        }

        // Validation avant de passer à l'étape suivante
        if (step > currentStep && !validateStep(currentStep)) {
            console.error("Validation échouée pour l'étape", currentStep);
            return;
        }

        // Vérifier si l'étape existe
        const targetStepEl = document.getElementById(`step${step}`);
        if (!targetStepEl) {
            console.error(`L'élément step${step} n'existe pas`);
            return;
        }

        // Animation de transition
        const currentStepEl = document.getElementById(`step${currentStep}`);
        if (!currentStepEl) {
            console.error(`L'élément step${currentStep} n'existe pas`);
            return;
        }

        // Ajouter l'animation de sortie
        currentStepEl.classList.add('animate__animated', 'animate__fadeOut');

        // Après l'animation de sortie
        setTimeout(() => {
            // Cacher l'étape actuelle
            currentStepEl.classList.add('hidden');
            currentStepEl.classList.remove('animate__animated', 'animate__fadeOut');

            // Mettre à jour l'étape actuelle
            currentStep = step;
            console.log("Étape actuelle mise à jour:", currentStep);

            // Afficher la nouvelle étape avec animation
            targetStepEl.classList.remove('hidden');
            targetStepEl.classList.add('animate__animated', 'animate__fadeIn');

            // Après l'animation d'entrée
            setTimeout(() => {
                targetStepEl.classList.remove('animate__animated', 'animate__fadeIn');

                // Scroll vers le haut de l'étape
                targetStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Gérer l'affichage des boutons en fonction du rôle à l'étape 2
                if (currentStep === 2) {
                    const role = roleSelect.value;
                    if (role === 'company') {
                        nextBtnStep2.classList.add('hidden');
                        submitBtnStep2.classList.remove('hidden');
                    } else if (role === 'student') {
                        nextBtnStep2.classList.remove('hidden');
                        submitBtnStep2.classList.add('hidden');
                    } else {
                        nextBtnStep2.classList.add('hidden');
                        submitBtnStep2.classList.add('hidden');
                    }
                }
            }, 300);
        }, 300);
    }

    // Validation functions
    const validators = {
        required: (value) => value.trim() !== '' ? '' : 'Ce champ est obligatoire',
        email: (value) => {
            if (value.trim() === '') return '';
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(value) ? '' : 'Format d\'email invalide';
        },
        phone: (value) => {
            if (value.trim() === '') return '';
            const phoneRegex = /^[0-9+\s()-]{8,15}$/;
            return phoneRegex.test(value) ? '' : 'Format de téléphone invalide';
        },
        minLength: (value, length) => {
            return value.length >= length ? '' : `Doit contenir au moins ${length} caractères`;
        },
        password: (value) => {
            if (value.trim() === '') return '';
            if (value.length < 8) return 'Le mot de passe doit contenir au moins 8 caractères';

            let strength = 0;
            if (/[A-Z]/.test(value)) strength++; // Uppercase
            if (/[a-z]/.test(value)) strength++; // Lowercase
            if (/[0-9]/.test(value)) strength++; // Numbers
            if (/[^A-Za-z0-9]/.test(value)) strength++; // Special chars

            if (strength < 3) return 'Le mot de passe doit contenir au moins 3 types de caractères (majuscules, minuscules, chiffres, caractères spéciaux)';
            return '';
        },
        username: (value) => {
            if (value.trim() === '') return '';
            if (value.length < 4) return 'Le nom d\'utilisateur doit contenir au moins 4 caractères';
            if (!/^[a-zA-Z0-9_.-]+$/.test(value)) return 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, points, tirets et underscores';
            return '';
        }
    };

    // Gestion spécifique des boutons
    if (nextBtnStep2) {
        nextBtnStep2.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher le comportement par défaut
            console.log("Bouton suivant étape 2 cliqué");

            if (validateStep(currentStep)) {
                // Si le rôle est étudiant, aller à l'étape 3 (vérification)
                if (roleSelect.value === 'student') {
                    console.log("Navigation vers l'étape 3 (vérification)");
                    navigateToStep(3);
                } else {
                    // Sinon, aller à l'étape suivante
                    console.log("Navigation vers l'étape suivante");
                    navigateToStep(currentStep + 1);
                }
            } else {
                console.log("Validation échouée pour l'étape", currentStep);
            }
        });
    }

    if (submitBtnStep2) {
        submitBtnStep2.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher le comportement par défaut
            console.log("Bouton soumettre étape 2 cliqué");

            if (validateStep(currentStep)) {
                console.log("Soumission du formulaire");
                document.getElementById('registerForm').submit();
            } else {
                console.log("Validation échouée pour l'étape", currentStep);
            }
        });
    }

    const nextAfterVerification = document.getElementById('next-after-verification');
    if (nextAfterVerification) {
        nextAfterVerification.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher le comportement par défaut
            console.log("Bouton suivant après vérification cliqué");

            if (validateStep(currentStep)) {
                console.log("Navigation vers l'étape 4 (branche)");
                navigateToStep(4);
            } else {
                console.log("Validation échouée pour l'étape", currentStep);
            }
        });
    }

    // Step validation
    function validateStep(step) {
        let isValid = true;
        const currentStepElement = document.getElementById(`step${step}`);
        const inputs = currentStepElement.querySelectorAll('input, select');

        // Reset errors
        currentStepElement.querySelectorAll('.error-field').forEach(el => {
            el.classList.remove('error-field', 'border-red-500');
            const errorMsg = el.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });

        // Validate all inputs in the current step
        inputs.forEach(input => {
            // Skip non-required empty fields
            if (!input.hasAttribute('required') && !input.value.trim()) {
                return;
            }

            // Required validation
            if (input.hasAttribute('required') && !input.value.trim()) {
                markAsInvalid(input, 'Ce champ est obligatoire');
                isValid = false;
                return;
            }

            // Specific validations based on input type or id
            if (input.value.trim()) {
                let errorMessage = '';

                switch(input.id) {
                    case 'email':
                        errorMessage = validators.email(input.value);
                        break;
                    case 'password':
                        errorMessage = validators.password(input.value);
                        break;
                    case 'username':
                        errorMessage = validators.username(input.value);
                        break;
                    case 'phone':
                        errorMessage = validators.phone(input.value);
                        break;
                }

                if (errorMessage) {
                    markAsInvalid(input, errorMessage);
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
        }

        // Branch validation for students (only at step 4)
        if (step === 4) {
            const role = document.getElementById('role').value;
            if (role === 'student') {
                const branch = document.getElementById('branch');
                if (branch && !branch.value) {
                    markAsInvalid(branch, 'Veuillez sélectionner une branche');
                    isValid = false;
                }
            }
        }

        return isValid;
    }
    // Mark field as invalid
    function markAsInvalid(input, message) {
        input.classList.add('error-field', 'border-red-500');

        // Add error message
        const errorMsg = document.createElement('div');
        errorMsg.className = 'error-message text-red-500 text-sm mt-1 font-medium';

        // Add icon to error message
        errorMsg.innerHTML = `
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>${message}</span>
            </div>
        `;

        // Check if there's already an error message
        const existingError = input.nextElementSibling;
        if (existingError && existingError.classList.contains('error-message')) {
            existingError.remove();
        }

        input.parentNode.insertBefore(errorMsg, input.nextSibling);

        // Scroll to first error
        if (input === document.querySelector('.error-field')) {
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Real-time validation for fields
    document.querySelectorAll('input, select').forEach(el => {
        // Validate on input
        el.addEventListener('input', function() {
            if (this.classList.contains('error-field')) {
                this.classList.remove('error-field', 'border-red-500');
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.remove();
                }
            }

            // For password field, show strength indicator
            if (this.id === 'password' && this.value.trim()) {
                let strength = 0;
                if (this.value.length >= 8) strength++;
                if (/[A-Z]/.test(this.value)) strength++;
                if (/[a-z]/.test(this.value)) strength++;
                if (/[0-9]/.test(this.value)) strength++;
                if (/[^A-Za-z0-9]/.test(this.value)) strength++;

                // Remove any existing strength indicator
                const existingIndicator = document.getElementById('password-strength');
                if (existingIndicator) {
                    existingIndicator.remove();
                }

                // Create strength indicator
                const strengthIndicator = document.createElement('div');
                strengthIndicator.id = 'password-strength';
                strengthIndicator.className = 'mt-1 text-sm';

                let strengthText = '';
                let strengthColor = '';

                if (strength <= 2) {
                    strengthText = 'Faible';
                    strengthColor = 'text-red-500';
                } else if (strength <= 3) {
                    strengthText = 'Moyen';
                    strengthColor = 'text-yellow-500';
                } else {
                    strengthText = 'Fort';
                    strengthColor = 'text-green-500';
                }

                strengthIndicator.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full ${strengthColor === 'text-red-500' ? 'bg-red-500' : strengthColor === 'text-yellow-500' ? 'bg-yellow-500' : 'bg-green-500'}" style="width: ${strength * 20}%"></div>
                        </div>
                        <span class="ml-2 ${strengthColor}">${strengthText}</span>
                    </div>
                `;

                this.parentNode.insertBefore(strengthIndicator, this.nextSibling);
            }
        });

        // Validate on blur
        el.addEventListener('blur', function() {
            // Skip validation for non-required empty fields
            if (!this.hasAttribute('required') && !this.value.trim()) {
                return;
            }

            // Required validation
            if (this.hasAttribute('required') && !this.value.trim()) {
                markAsInvalid(this, 'Ce champ est obligatoire');
                return;
            }

            // Specific validations based on input type or id
            if (this.value.trim()) {
                let errorMessage = '';

                switch(this.id) {
                    case 'email':
                        errorMessage = validators.email(this.value);
                        break;
                    case 'password':
                        errorMessage = validators.password(this.value);
                        break;
                    case 'username':
                        errorMessage = validators.username(this.value);
                        break;
                    case 'phone':
                        errorMessage = validators.phone(this.value);
                        break;
                }

                if (errorMessage) {
                    markAsInvalid(this, errorMessage);
                }
            }
        });
    });
});