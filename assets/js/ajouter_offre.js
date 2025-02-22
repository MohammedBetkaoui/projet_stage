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

// Fonction pour afficher les erreurs
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
    const errorMessages = [];
    const step = formSteps[stepIndex];

    if (stepIndex === 0) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        if (!title) errorMessages.push('Le titre est requis');
        if (!description) errorMessages.push('La description est requise');
    } else if (stepIndex === 1) {
        const sector = document.getElementById('sector').value.trim();
        const location = document.getElementById('location').value.trim();
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const deadline = document.getElementById('deadline').value;
        const today = new Date().toISOString().split('T')[0]; // Date d'aujourd'hui

        if (!sector) errorMessages.push('Le secteur est requis');
        if (!location) errorMessages.push('Le lieu est requis');
        if (!startDate) errorMessages.push('La date de début est requise');
        if (!endDate) errorMessages.push('La date de fin est requise');
        if (!deadline) errorMessages.push('La date limite de candidature est requise');

        if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
            errorMessages.push('La date de fin doit être postérieure à la date de début');
        }
        if (deadline && new Date(deadline) <= new Date(today)) {
            errorMessages.push('La date limite de candidature doit être postérieure à aujourd\'hui');
        }
    }

    if (errorMessages.length > 0) {
        showErrors(errorMessages); // Afficher les erreurs
        return false;
    } else {
        clearErrors(); // Effacer les erreurs si la validation réussit
        return true;
    }
}

// Ajouter des écouteurs d'événements pour effacer les erreurs en temps réel
document.querySelectorAll('#offerForm input, #offerForm textarea').forEach(input => {
    input.addEventListener('input', () => {
        clearErrors(); // Effacer les erreurs lorsque l'utilisateur commence à taper
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

// Afficher la première étape au chargement
showStep(currentStep);