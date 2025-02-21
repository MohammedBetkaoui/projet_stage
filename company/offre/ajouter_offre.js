document.getElementById('offerForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const sector = document.getElementById('sector').value.trim();
    const location = document.getElementById('location').value.trim();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const errorMessages = [];

    // Validation des champs requis
    if (!title) errorMessages.push('Le titre est requis');
    if (!description) errorMessages.push('La description est requise');
    if (!sector) errorMessages.push('Le secteur est requis');
    if (!location) errorMessages.push('Le lieu est requis');
    if (!startDate) errorMessages.push('La date de début est requise');
    if (!endDate) errorMessages.push('La date de fin est requise');
    
    // Validation des dates
    if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
        errorMessages.push('La date de fin doit être postérieure à la date de début');
    }

    if (errorMessages.length > 0) {
        e.preventDefault();
        alert('Erreurs:\n' + errorMessages.join('\n'));
    }
});