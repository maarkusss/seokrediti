const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebar-toggle");
const sidebarOverlay = document.getElementById("sidebar-overlay");

if (sidebarToggle && sidebar && sidebarOverlay) {
    sidebarToggle.onclick = () => {
        toggle();
        if (sidebarOverlay.classList.contains("hidden")) {
            slideIn();
        } else {
            slideOut();
        }
    };

    sidebarOverlay.onclick = () => {
        toggle();
        if (sidebarOverlay.classList.contains("hidden")) {
            slideIn();
        } else {
            slideOut();
        }
    };
}

function slideIn() {
    if (!sidebar || !sidebar.parentNode) return;
    sidebar.parentNode.classList.add("animate-slideBar");
    setTimeout(function () {
        sidebar.parentNode.classList.toggle("-translate-x-[17.188rem]");
    }, 500);

    const header = document.querySelector("header");
    if (header && header.children[0]) {
        header.children[0].classList.add("animate-slide");
    }

    const main = document.querySelector("main");
    if (main) main.classList.add("animate-slide");
    const footer = document.querySelector("footer");
    if (footer) footer.classList.add("animate-slide");

    setTimeout(function () {
        sidebar.parentNode.classList.remove("animate-slideBar");
        if (header && header.children[0]) {
            header.children[0].classList.remove("animate-slide");
        }
        if (main) main.classList.remove("animate-slide");
        if (footer) footer.classList.remove("animate-slide");
        sidebarOverlay.classList.toggle("hidden");
    }, 600);
}

function slideOut() {
    if (!sidebar || !sidebar.parentNode) return;
    sidebar.parentNode.classList.add("animate-slideBarOut");
    sidebar.parentNode.classList.toggle("-translate-x-[17.188rem]");

    const header = document.querySelector("header");
    if (header && header.children[0]) {
        header.children[0].classList.add("animate-slideOut");
    }
    const main = document.querySelector("main");
    if (main) main.classList.add("animate-slideOut");
    const footer = document.querySelector("footer");
    if (footer) footer.classList.add("animate-slideOut");

    setTimeout(function () {
        sidebar.parentNode.classList.remove("animate-slideBarOut");
        if (header && header.children[0]) {
            header.children[0].classList.remove("animate-slideOut");
        }
        if (main) main.classList.remove("animate-slideOut");
        if (footer) footer.classList.remove("animate-slideOut");
        sidebarOverlay.classList.toggle("hidden");
    }, 600);
}

function toggle() {
    const header = document.querySelector("header");
    if (header && header.children[0]) {
        header.children[0].classList.toggle("translate-x-[17.188rem]");
    }
    const main = document.querySelector("main");
    if (main) main.classList.toggle("translate-x-[17.188rem]");
    const footer = document.querySelector("footer");
    if (footer) footer.classList.toggle("translate-x-[17.188rem]");
}
