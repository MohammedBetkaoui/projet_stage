// Toggle mobile menu
const mobileNav = document.querySelector(".hamburger");
const navbar = document.querySelector(".menubar");
const overlay = document.querySelector(".overlay");
const body = document.querySelector("body");

const toggleMenu = () => {
    navbar.classList.toggle("active");
    mobileNav.classList.toggle("hamburger-active");
    overlay.classList.toggle("active");
    body.classList.toggle("no-scroll");
};

// Fermer le menu en cliquant à l'extérieur
document.addEventListener("click", (e) => {
    if (!navbar.contains(e.target) && !mobileNav.contains(e.target)) {
        navbar.classList.remove("active");
        mobileNav.classList.remove("hamburger-active");
        overlay.classList.remove("active");
        body.classList.remove("no-scroll");
    }
});

// Fermer le menu en cliquant sur un lien
document.querySelectorAll(".menubar a").forEach(link => {
    link.addEventListener("click", () => {
        navbar.classList.remove("active");
        mobileNav.classList.remove("hamburger-active");
        overlay.classList.remove("active");
        body.classList.remove("no-scroll");
    });
});

// Ajouter une classe "scrolled" lors du défilement
window.addEventListener("scroll", () => {
    const nav = document.querySelector("nav");
    if (window.scrollY > 50) {
        nav.classList.add("scrolled");
    } else {
        nav.classList.remove("scrolled");
    }
});