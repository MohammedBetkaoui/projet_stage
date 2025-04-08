// JavaScript for "See More" and "Back" buttons
document.getElementById('seeMoreBtn').addEventListener('click', function() {
    const hiddenRows = document.querySelectorAll('.more-branch');
    hiddenRows.forEach(row => row.style.display = 'table-row');
    document.getElementById('seeMoreBtn').style.display = 'none';
    document.getElementById('backBtn').style.display = 'inline-block';
});

document.getElementById('backBtn').addEventListener('click', function() {
    const hiddenRows = document.querySelectorAll('.more-branch');
    hiddenRows.forEach(row => row.style.display = 'none');
    document.getElementById('seeMoreBtn').style.display = 'inline-block';
    document.getElementById('backBtn').style.display = 'none';
});

// Automatically hide the success/error messages after 5 seconds
setTimeout(() => {
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        successMessage.style.display = 'none';
    }
    const errorMessage = document.getElementById('errorMessage');
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
}, 5000);