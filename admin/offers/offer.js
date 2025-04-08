document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const offerId = event.target.getAttribute('data-id');
            const confirmDelete = confirm('Voulez-vous vraiment supprimer cette offre ?');
            if (confirmDelete) {
                window.location.href = `suppoffer.php?delete_id=${offerId}`;
            }
        });
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const offerId = event.target.getAttribute('data-id');
            window.location.href = `editoffer.php?edit_id=${offerId}`;
        });
    });
});
// pour modife offer 
 // JavaScript to toggle between steps
 function showStep1() {
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
}

function showStep2() {
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}