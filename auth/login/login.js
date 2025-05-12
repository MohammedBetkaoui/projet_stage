document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const loginError = document.getElementById('login-error');
    const passwordError = document.getElementById('password-error');
    const submitBtn = document.getElementById('submit-btn');
    const message = document.getElementById('message');

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
        }
    };

    // Validate a single input
    function validateInput(input) {
        let isValid = true;
        let errorMessage = '';

        // Required validation
        if (input.hasAttribute('required')) {
            errorMessage = validators.required(input.value);
            if (errorMessage) {
                isValid = false;
            }
        }

        // Min length validation
        if (isValid && input.hasAttribute('data-min-length')) {
            const minLength = parseInt(input.dataset.minLength);
            errorMessage = validators.minLength(input.value, minLength);
            if (errorMessage) {
                isValid = false;
            }
        }

        // Email or phone validation for login field
        if (isValid && input.id === 'login' && input.value.trim() !== '') {
            // Try to determine if it's an email or phone
            if (input.value.includes('@')) {
                errorMessage = validators.email(input.value);
            } else {
                errorMessage = validators.phone(input.value);
            }
            if (errorMessage) {
                isValid = false;
            }
        }

        // Update UI
        const errorElement = document.getElementById(`${input.id}-error`);
        if (errorElement) {
            errorElement.textContent = errorMessage;
        }

        if (isValid) {
            input.classList.remove('error');
            input.classList.add('valid');
        } else {
            input.classList.remove('valid');
            input.classList.add('error');
        }

        return isValid;
    }

    // Validate all form inputs
    function validateForm() {
        const inputs = loginForm.querySelectorAll('input[required]');
        let isFormValid = true;

        inputs.forEach(input => {
            const isInputValid = validateInput(input);
            if (!isInputValid) {
                isFormValid = false;
            }
        });

        return isFormValid;
    }

    // Add input event listeners for real-time validation
    loginForm.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            validateInput(this);
        });

        input.addEventListener('blur', function() {
            validateInput(this);
        });
    });

    // Form submission
    loginForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        // Validate form before submission
        if (!validateForm()) {
            message.textContent = 'Veuillez corriger les erreurs dans le formulaire.';
            message.style.color = 'red';
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Connexion en cours...';

        try {
            const formData = new FormData(this);

            const response = await fetch('login.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            // Vérifier si la réponse est du JSON valide
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Réponse non-JSON reçue:', await response.text());
                throw new Error('La réponse du serveur n\'est pas au format JSON');
            }

            let result;
            try {
                result = await response.json();
            } catch (jsonError) {
                console.error('Erreur lors du parsing JSON:', jsonError);
                console.error('Contenu de la réponse:', await response.text());
                throw jsonError;
            }
            message.textContent = result.message;
            message.style.color = result.status === 'success' ? 'green' : 'red';

            if (result.status === 'success') {
                // Reset form and show success state
                loginForm.reset();
                loginInput.classList.remove('valid', 'error');
                passwordInput.classList.remove('valid', 'error');

                setTimeout(() => {
                    // Redirection selon le rôle
                    if (result.role === 'admin') {
                        window.location.href = '../../admin/admin_dashboard.php';
                    } else if (result.role === 'company') {
                        window.location.href = '../../company/company_dashboard.php';
                    } else {
                        window.location.href = '../../studant/home_dashboard/student_dashboard.php';
                    }
                }, 1000);
            } else {
                // Reset submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Se connecter';
            }
        } catch (error) {
            console.error('Error:', error);

            // Message d'erreur plus spécifique selon le type d'erreur
            let errorMessage = 'Une erreur est survenue. Veuillez réessayer.';

            if (error.name === 'SyntaxError') {
                console.error('Erreur de syntaxe JSON. La réponse du serveur n\'est pas un JSON valide.');
                errorMessage = 'Erreur de communication avec le serveur. Veuillez réessayer.';
            } else if (error.name === 'TypeError') {
                console.error('Erreur de type. Vérifiez la connexion réseau.');
                errorMessage = 'Erreur de connexion au serveur. Vérifiez votre connexion internet.';
            } else if (error.message && error.message.includes('JSON')) {
                errorMessage = 'Erreur de format de données. Veuillez contacter l\'administrateur.';
            }

            message.textContent = errorMessage;
            message.style.color = 'red';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Se connecter';

            // Ajouter une classe d'animation pour attirer l'attention sur l'erreur
            message.classList.add('error-shake');
            setTimeout(() => {
                message.classList.remove('error-shake');
            }, 500);
        }
    });
});
