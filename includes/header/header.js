// Toggle mobile menu
const mobileMenu = document.getElementById('mobile-menu');
const nav = document.querySelector('.nav');

mobileMenu.addEventListener('click', () => {
    nav.classList.toggle('active');
});  
 