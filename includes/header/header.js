// Scroll animation for header
window.addEventListener('scroll', () => {
    const header = document.querySelector('.gradient-bg');
    if (header) {
        if (window.scrollY > 50) {
            header.classList.add('shadow-lg');
        } else {
            header.classList.remove('shadow-lg');
        }
    }
}); 



