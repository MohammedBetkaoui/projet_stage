document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('login.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    const message = document.getElementById('message');
    message.textContent = result.message;
    message.style.color = result.status === 'success' ? 'green' : 'red';

    if (result.status === 'success') {
        setTimeout(() => {
            // Redirection selon le rôle
            if (result.role === 'admin') {
                window.location.href = '../../admin/admin_dashboard.php';
            } else if (result.role === 'company') {
                window.location.href = '../../company/company_dashboard.php';
            } else {
                window.location.href = '../../studant/student_dashboard.php';
            }
        }, 1000);
    }
});
