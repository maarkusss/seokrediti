const menuButton = document.querySelector("#header_menu_button");
const menu = document.querySelector("#header_menu");

if (menuButton && menu) {
    menuButton.addEventListener("click", () => {
        menu.classList.toggle("hidden");
    });
}

const langButton = document.querySelector("#language_dropdown_button");
const langMenu = document.querySelector("#language_dropdown_menu");

if (langButton && langMenu) {
    langButton.addEventListener("click", () => {
        langMenu.classList.toggle("hidden");
    });
}
