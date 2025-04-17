document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const steps = Array.from(document.querySelectorAll('.step'));
    let currentStep = 0;

    // Navigation entre les étapes
    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.style.display = index === stepIndex ? 'block' : 'none';
        });
    }

    // Validation de l'étape 1
    function validateStep1() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const email = document.getElementById('email').value.trim();
        
        if (!username || !password || !email) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
        
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Veuillez entrer une adresse email valide.');
            return false;
        }
        
        return true;
    }

    // Validation de l'étape 2
    function validateStep2() {
        const role = document.getElementById('role').value;
        const fullName = document.getElementById('full_name').value.trim();
        
        if (!role || !fullName) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
        
        return true;
    }

    // Gestion des boutons Suivant
    document.querySelectorAll('.next-btn').forEach(button => {
        button.addEventListener('click', function() {
            const isValid = currentStep === 0 ? validateStep1() : validateStep2();
            
            if (isValid) {
                currentStep++;
                showStep(currentStep);
                
                // Si on arrive à l'étape 3 et que le rôle n'est pas étudiant, soumettre le formulaire
                if (currentStep === 2 && document.getElementById('role').value !== 'student') {
                    form.submit();
                }
            }
        });
    });

    // Gestion des boutons Précédent
    document.querySelectorAll('.prev-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentStep--;
            showStep(currentStep);
        });
    });

    // Initialisation
    showStep(0);
});