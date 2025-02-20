document.getElementById('registerForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('register.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    const message = document.getElementById('message');
    message.textContent = result.message;
    message.style.color = result.status === 'success' ? 'green' : 'red';

    if (result.status === 'success') {
        this.reset();
    }
});