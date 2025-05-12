// Fonctions de validation
const validators = {
    required: (value) => value.trim() !== '' ? '' : 'Ce champ est obligatoire',
    minLength: (value, length) => value.length >= length ? '' : `Doit contenir au moins ${length} caractères`,
    maxLength: (value, length) => value.length <= length ? '' : `Doit contenir au maximum ${length} caractères`,
    date: (value) => {
        if (!value) return '';
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        return dateRegex.test(value) ? '' : 'Format de date invalide (AAAA-MM-JJ)';
    },
    futureDate: (value) => {
        if (!value) return '';
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const inputDate = new Date(value);
        return inputDate >= today ? '' : 'La date doit être dans le futur';
    },
    dateAfter: (value, startDateId) => {
        if (!value) return '';
        const startDate = document.getElementById(startDateId).value;
        if (!startDate) return '';
        return new Date(value) > new Date(startDate) ? '' : 'La date doit être postérieure à la date de début';
    },
    number: (value) => {
        if (value === '') return '';
        return !isNaN(value) && Number(value) >= 0 ? '' : 'Doit être un nombre positif';
    }
};

// Fonction pour valider un champ spécifique
function validateField(field) {
    // Réinitialiser l'état du champ
    field.classList.remove('valid', 'invalid');

    // Supprimer les messages d'erreur existants
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }

    // Vérifier si le champ est vide et requis
    if (field.hasAttribute('required') && !field.value.trim()) {
        showFieldError(field, 'Ce champ est obligatoire');
        return false;
    }

    // Validations spécifiques selon le type de champ
    let isValid = true;
    let errorMessage = '';

    switch(field.id) {
        case 'title':
            if (field.value.trim().length < 5) {
                errorMessage = 'Le titre doit contenir au moins 5 caractères';
                isValid = false;
            } else if (field.value.trim().length > 100) {
                errorMessage = 'Le titre doit contenir au maximum 100 caractères';
                isValid = false;
            }
            break;

        case 'description':
            if (field.value.trim().length < 20) {
                errorMessage = 'La description doit contenir au moins 20 caractères';
                isValid = false;
            }
            break;

        case 'sector':
        case 'location':
            if (field.value.trim().length < 3) {
                errorMessage = 'Ce champ doit contenir au moins 3 caractères';
                isValid = false;
            }
            break;

        case 'start_date':
            if (field.value) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const startDate = new Date(field.value);
                if (startDate < today) {
                    errorMessage = 'La date de début doit être dans le futur';
                    isValid = false;
                }
            }
            break;

        case 'end_date':
            if (field.value) {
                const startDateField = document.getElementById('start_date');
                if (startDateField.value) {
                    const startDate = new Date(startDateField.value);
                    const endDate = new Date(field.value);
                    if (endDate <= startDate) {
                        errorMessage = 'La date de fin doit être postérieure à la date de début';
                        isValid = false;
                    }
                }
            }
            break;

        case 'deadline':
            if (field.value) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const deadlineDate = new Date(field.value);
                if (deadlineDate <= today) {
                    errorMessage = 'La date limite doit être dans le futur';
                    isValid = false;
                }
            }
            break;

        case 'compensation':
            if (field.value && (isNaN(field.value) || Number(field.value) < 0)) {
                errorMessage = 'La gratification doit être un nombre positif';
                isValid = false;
            }
            break;

        case 'branch':
            if (field.hasAttribute('required') && (!field.value || field.value === "")) {
                errorMessage = 'Veuillez sélectionner une branche';
                isValid = false;
            }
            break;
    }

    if (!isValid) {
        showFieldError(field, errorMessage);
        return false;
    }

    // Si tout est valide, marquer le champ comme valide
    field.classList.add('valid');
    return true;
}

// Fonction pour afficher une erreur sous un champ
function showFieldError(field, message) {
    field.classList.add('invalid');

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.innerHTML = `<i class='bx bx-error-circle'></i> ${message}`;

    field.parentNode.appendChild(errorDiv);
}

// Fonction pour effacer les messages d'erreur
function clearErrors() {
    const errorDiv = document.getElementById('error-messages');
    errorDiv.style.opacity = '0';
    setTimeout(() => {
        errorDiv.innerHTML = '';
        errorDiv.style.display = 'none';
        errorDiv.style.opacity = '1';
    }, 300);
}

// Fonction pour afficher les erreurs globales
function showErrors(messages) {
    const errorDiv = document.getElementById('error-messages');
    errorDiv.innerHTML = '';

    messages.forEach(message => {
        const errorItem = document.createElement('div');
        errorItem.className = 'error-item';

        errorItem.innerHTML = `
            <i class='bx bx-error-circle'></i>
            <span>${message}</span>
            <button class="close-btn">&times;</button>
        `;

        // Ajouter l'événement pour fermer l'erreur
        errorItem.querySelector('.close-btn').addEventListener('click', () => {
            errorItem.style.opacity = '0';
            setTimeout(() => errorItem.remove(), 300);
            if (errorDiv.children.length === 1) errorDiv.style.display = 'none';
        });

        errorDiv.appendChild(errorItem);
    });

    errorDiv.style.display = messages.length > 0 ? 'block' : 'none';
}

// Fonction pour valider chaque étape
function validateStep(stepIndex) {
    const step = formSteps[stepIndex];
    const fields = step.querySelectorAll('input, textarea, select');

    let isStepValid = true;

    fields.forEach(field => {
        if (!validateField(field)) {
            isStepValid = false;
        }
    });

    // Validations spécifiques à l'étape
    if (stepIndex === 1) {
        // Vérification supplémentaire pour les dates
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const deadline = document.getElementById('deadline');

        if (startDate.value && endDate.value && deadline.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const deadlineDate = new Date(deadline.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (end <= start) {
                showFieldError(endDate, 'La date de fin doit être postérieure à la date de début');
                isStepValid = false;
            }

            if (deadlineDate <= today) {
                showFieldError(deadline, 'La date limite doit être dans le futur');
                isStepValid = false;
            }
        }
    }

    return isStepValid;
}

// Ajouter des écouteurs d'événements pour la validation en temps réel
document.querySelectorAll('#offerForm input, #offerForm textarea, #offerForm select').forEach(field => {
    field.addEventListener('input', function() {
        validateField(this);
    });

    field.addEventListener('blur', function() {
        validateField(this);
    });
});

// Gestion des étapes du formulaire
const formSteps = document.querySelectorAll('.form-step');
const nextButtons = document.querySelectorAll('.next-btn');
const prevButtons = document.querySelectorAll('.prev-btn');
let currentStep = 0;

// Afficher l'étape actuelle
function showStep(stepIndex) {
    formSteps.forEach((step, index) => {
        step.classList.toggle('active', index === stepIndex);
    });

    // Scroll vers le haut du formulaire
    document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Bouton Suivant
nextButtons.forEach(button => {
    button.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < formSteps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        }
    });
});

// Bouton Précédent
prevButtons.forEach(button => {
    button.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
});

// Validation du formulaire avant soumission
document.getElementById('offerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Valider toutes les étapes
    let isFormValid = true;
    for (let i = 0; i < formSteps.length; i++) {
        if (!validateStep(i)) {
            isFormValid = false;
            currentStep = i;
            showStep(i);
            break;
        }
    }

    if (isFormValid) {
        this.submit();
    }
});

// Fermer les alertes
document.querySelectorAll('.alert .close-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.parentElement.remove();
    });
});

// Afficher la première étape au chargement
showStep(currentStep);