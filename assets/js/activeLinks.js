function toggleMobileMenu() {
    const navList = document.querySelector(".nav-list");
    const toggle = document.querySelector(".nav-toggle");

    navList.classList.toggle("active");
    toggle.classList.toggle("active");
}
