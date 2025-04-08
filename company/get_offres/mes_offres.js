// Fonction pour confirmer la suppression d'une offre
document.querySelectorAll('.btn.delete').forEach(button => {
    button.addEventListener('click', (e) => {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) {
            e.preventDefault();
        }
    });
});

// Fonction pour afficher un message de succès après une action
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('success')) {
    alert(urlParams.get('success'));
}