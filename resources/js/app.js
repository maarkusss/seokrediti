// Safe window.ga stub to prevent ReferenceError when adblocker blocks analytics or ga is called inline
window.ga = window.ga || function () {
    (window.ga.q = window.ga.q || []).push(arguments);
};

import "./public/scrollToTopButton";
import "./public/header";
import "./public/lazyload";
import "./public/sidebarToggle";

import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();
