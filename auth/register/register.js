document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordStrength = document.createElement('div');
    passwordStrength.className = 'password-strength';
    passwordInput.parentNode.insertBefore(passwordStrength, passwordInput.nextSibling);

    // Configuration de la validation
    const constraints = {
        username: {
            presence: true,
            format: {
                pattern: /^[a-zA-Z0-9_]{3,20}$/,
                message: "doit contenir 3-20 caractères alphanumériques"
            }
        },
        email: {
            presence: true,
            email: true
        },
        password: {
            presence: true,
            length: {
                minimum: 8,
                message: "doit contenir au moins 8 caractères"
            },
            strength: {
                requirement: {
                    matches: [/[a-z]/, /[A-Z]/, /[0-9]/, /[^a-zA-Z0-9]/],
                    message: "doit contenir majuscules, minuscules, chiffres et caractères spéciaux"
                }
            }
        },
        role: {
            presence: true
        }
    };

    // Validation en temps réel
    form.addEventListener('input', (e) => {
        const field = e.target.name;
        if (field === 'password') updatePasswordStrength(e.target.value);
        validateField(e.target);
    });

    // Soumission du formulaire
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        
        // Validation finale
        const errors = validateForm();
        if (Object.keys(errors).length > 0) {
            showMessage('Veuillez corriger les erreurs', 'error');
            submitButton.disabled = false;
            return;
        }

        try {
            const response = await fetch('register.php', {
                method: 'POST',
                body: new FormData(form)
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                window.location.href = result.redirect;
            } else {
                showMessage(result.message, 'error');
                handleServerErrors(result.errors);
            }
        } catch (error) {
            showMessage('Erreur de connexion au serveur', 'error');
        } finally {
            submitButton.disabled = false;
        }
    });

    function validateForm() {
        const formData = new FormData(form);
        const values = Object.fromEntries(formData.entries());
        const errors = validate(values, constraints);
        
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) showFieldError(input, errors[field][0]);
        });
        
        return errors;
    }

    function validateField(input) {
        const errors = validate.single(input.value, constraints[input.name]);
        if (errors) showFieldError(input, errors[0]);
        else clearFieldError(input);
    }

    function showFieldError(input, message) {
        const group = input.closest('.input-group');
        group.classList.add('invalid');
        const errorElement = group.querySelector('.error-message') || createErrorElement(group);
        errorElement.textContent = message;
    }

    function createErrorElement(group) {
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        group.appendChild(errorElement);
        return errorElement;
    }

    function clearFieldError(input) {
        const group = input.closest('.input-group');
        group.classList.remove('invalid');
        const errorElement = group.querySelector('.error-message');
        if (errorElement) errorElement.textContent = '';
    }

    function updatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        const width = (strength / 4) * 100;
        passwordStrength.style.setProperty('--strength', `${width}%`);
        passwordStrength.style.setProperty('--strength-color', 
            strength < 2 ? '#ef4444' : 
            strength < 4 ? '#f59e0b' : 
            '#10b981');
    }

    function showMessage(message, type) {
        const messageElement = document.getElementById('message');
        messageElement.className = `${type}-message`;
        messageElement.textContent = message;
        messageElement.style.display = 'block';
    }

    function handleServerErrors(errors) {
        errors?.forEach(error => {
            const input = form.querySelector(`[name="${error.field}"]`);
            if (input) showFieldError(input, error.message);
        });
    }
});