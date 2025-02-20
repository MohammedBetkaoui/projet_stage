<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Inscription - Plateforme de Stages</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --error: #ef4444;
            --background: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            margin: 0;
            padding: 1rem;
        }

        .container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 520px;
        }

        h1 {
            text-align: center;
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 2rem;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .password-strength {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .password-strength::before {
            content: '';
            position: absolute;
            left: 0;
            height: 100%;
            width: var(--strength, 0%);
            background: var(--strength-color, transparent);
            transition: all 0.4s ease;
        }

        button[type="submit"] {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .error-message {
            color: var(--error);
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: none;
        }

        .input-group.invalid input,
        .input-group.invalid select {
            border-color: var(--error);
        }

        .input-group.invalid .error-message {
            display: block;
        }

        #global-error {
            display: none;
            padding: 1rem;
            background: #fee2e2;
            color: var(--error);
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        @media (max-width: 640px) {
            .container {
                padding: 1.5rem;
                border-radius: 1rem;
            }
            
            h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="global-error"></div>
        <h1>Créer un compte</h1>
        <form id="registerForm" method="POST" novalidate>
            <div class="input-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" required autocomplete="username">
                <div class="error-message"></div>
            </div>

            <div class="input-group">
                <label>Adresse email</label>
                <input type="email" name="email" required autocomplete="email">
                <div class="error-message"></div>
            </div>

            <div class="input-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required autocomplete="new-password">
                <div class="password-strength"></div>
                <div class="error-message"></div>
            </div>

            <div class="input-group">
                <label>Vous êtes</label>
                <select name="role" required>
                    <option value="">Sélectionnez un rôle</option>
                    <option value="student">Étudiant</option>
                    <option value="company">Entreprise</option>
                </select>
                <div class="error-message"></div>
            </div>

            <div class="input-group">
                <label>Nom complet <small>(optionnel)</small></label>
                <input type="text" name="full_name">
                <div class="error-message"></div>
            </div>

            <div class="input-group">
                <label>Téléphone <small>(optionnel)</small></label>
                <input type="tel" name="phone" pattern="[+0-9\s]{10,15}">
                <div class="error-message"></div>
            </div>

            <div class="input-group">
                <label>Adresse <small>(optionnel)</small></label>
                <input type="text" name="address">
                <div class="error-message"></div>
            </div>

            <button type="submit">S'inscrire gratuitement</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('registerForm');
            const globalError = document.getElementById('global-error');
            const passwordInput = form.querySelector('input[name="password"]');
            const passwordStrength = form.querySelector('.password-strength');

            form.addEventListener('input', ({ target }) => {
                if (target.name === 'password') updatePasswordStrength(target.value);
                validateField(target);
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                const errors = validateForm();
                if (errors.length > 0) {
                    showGlobalError('Veuillez corriger les erreurs ci-dessous');
                    submitBtn.disabled = false;
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
                        handleServerErrors(result.errors || []);
                        showGlobalError(result.message || 'Erreur lors de l\'inscription');
                    }
                } catch (error) {
                    showGlobalError('Erreur de connexion au serveur');
                } finally {
                    submitBtn.disabled = false;
                }
            });

            function validateForm() {
                let errors = [];
                form.querySelectorAll('input, select').forEach(field => {
                    const error = validateField(field);
                    if (error) errors.push(error);
                });
                return errors;
            }

            function validateField(field) {
                const value = field.value.trim();
                const group = field.closest('.input-group');
                let errorEl = group.querySelector('.error-message');

                if (!errorEl) {
                    errorEl = document.createElement('div');
                    errorEl.className = 'error-message';
                    group.appendChild(errorEl);
                }

                group.classList.remove('invalid');
                errorEl.textContent = '';

                let error = '';
                switch(field.name) {
                    case 'username':
                        if (!value) error = 'Ce champ est requis';
                        else if (!/^[a-zA-Z0-9_]{3,20}$/.test(value)) 
                            error = '3 à 20 caractères alphanumériques';
                        break;
                    case 'email':
                        if (!value) error = 'Ce champ est requis';
                        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
                            error = 'Email invalide';
                        break;
                    case 'password':
                        if (!value) error = 'Ce champ est requis';
                        else if (value.length < 8) error = '8 caractères minimum';
                        else if (!/[A-Z]/.test(value) || !/[0-9]/.test(value))
                            error = 'Majuscule et chiffre requis';
                        break;
                    case 'role':
                        if (!value) error = 'Sélectionnez un rôle';
                        break;
                    case 'full_name':
                    case 'phone':
                    case 'address':
                        break;
                }

                if (error) {
                    group.classList.add('invalid');
                    errorEl.textContent = error;
                    return error;
                }
                return null;
            }

            function updatePasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                passwordStrength.style.setProperty('--strength', `${(strength/4)*100}%`);
                passwordStrength.style.setProperty('--strength-color', 
                    strength < 2 ? '#ef4444' :
                    strength < 4 ? '#f59e0b' : 
                    '#10b981');
            }

            function handleServerErrors(errors) {
                errors.forEach(({ field, message }) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        const group = input.closest('.input-group');
                        group.classList.add('invalid');
                        const errorEl = group.querySelector('.error-message') || createErrorElement(group);
                        errorEl.textContent = message;
                    } else {
                        showGlobalError(message);
                    }
                });
            }

            function showGlobalError(message) {
                globalError.textContent = message;
                globalError.style.display = 'block';
                setTimeout(() => {
                    globalError.style.display = 'none';
                }, 5000);
            }

            function createErrorElement(group) {
                const errorEl = document.createElement('div');
                errorEl.className = 'error-message';
                group.appendChild(errorEl);
                return errorEl;
            }
        });
    </script>
</body>
</html>