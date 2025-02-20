const mobileNav = document.querySelector(".hamburger");
const navbar = document.querySelector(".menubar");

const toggleNav = () => {
  navbar.classList.toggle("active");
  mobileNav.classList.toggle("hamburger-active");
};
mobileNav.addEventListener("click", () => toggleNav());
function toggleMenu() {
  const menu = document.getElementById('mobileMenu');
  menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

