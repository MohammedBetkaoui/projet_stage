const mobileNav = document.querySelector(".hamburger");
const navbar = document.querySelector(".menubar");
const body = document.querySelector("body");

const toggleMenu = () => {
    navbar.classList.toggle("active");
    mobileNav.classList.toggle("hamburger-active");
    body.classList.toggle("no-scroll");
};

// Gestionnaire pour fermer le menu en cliquant à l'extérieur
document.addEventListener("click", (e) => {
    if (!navbar.contains(e.target) && !mobileNav.contains(e.target)) {
        navbar.classList.remove("active");
        mobileNav.classList.remove("hamburger-active");
        body.classList.remove("no-scroll");
    }
});

// Gestionnaire pour les liens du menu
document.querySelectorAll(".menubar a").forEach(link => {
    link.addEventListener("click", () => {
        navbar.classList.remove("active");
        mobileNav.classList.remove("hamburger-active");
        body.classList.remove("no-scroll");
    });
});