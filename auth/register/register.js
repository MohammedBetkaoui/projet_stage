document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.step');
    const nextButtons = document.querySelectorAll('.next-btn');
    const prevButtons = document.querySelectorAll('.prev-btn');
    const roleSelect = document.getElementById('role');
    const nextButton = document.getElementById('nextButton');
    const submitButton = document.getElementById('submitButton');

    let currentStep = 0;

    // Fonction pour afficher une étape spécifique
    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.style.display = index === stepIndex ? 'block' : 'none';
        });

        // Gérer l'affichage des boutons en fonction du rôle
        if (stepIndex === 1) {
            if (roleSelect.value === 'student') {
                nextButton.style.display = 'inline-block';
                submitButton.style.display = 'none';
            } else if (roleSelect.value === 'company') {
                nextButton.style.display = 'none';
                submitButton.style.display = 'inline-block';
            }
        }

        // Masquer l'étape 3 si le rôle est "company"
        if (stepIndex === 2 && roleSelect.value === 'company') {
            steps[2].style.display = 'none';
            submitButton.style.display = 'block';
        }
    }

    // Fonction pour valider l'étape actuelle
    function validateStep(stepIndex) {
        const inputs = steps[stepIndex].querySelectorAll('input, select');
        let isValid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                isValid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });
        return isValid;
    }

    // Écouteurs d'événements pour les boutons "Suivant"
    nextButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    // Écouteurs d'événements pour les boutons "Précédent"
    prevButtons.forEach(button => {
        button.addEventListener('click', function () {
            currentStep--;
            showStep(currentStep);
        });
    });

    // Écouteur d'événement pour le changement de rôle
    roleSelect.addEventListener('change', function () {
        if (currentStep === 1) {
            if (roleSelect.value === 'student') {
                nextButton.style.display = 'inline-block';
                submitButton.style.display = 'none';
            } else if (roleSelect.value === 'company') {
                nextButton.style.display = 'none';
                submitButton.style.display = 'inline-block';
            }
        }
    });

    // Afficher la première étape au chargement
    showStep(currentStep);
});