const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    corePlugins: {
        container: false,
    },
    content: [
        "./resources/**/*.blade.php",
        "./resources/js/**/*.js",
        "./app/View/**/*.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                gray: colors.neutral,
                "gray-slate": colors.slate,

                // Editorial / newspaper palette (public site)
                paper: {
                    DEFAULT: "#F6F3EC",
                    light: "#FBF9F4",
                    dark: "#ECE7DC",
                },
                ink: {
                    DEFAULT: "#151412",
                    soft: "#3A3833",
                    muted: "#6B675F",
                    faint: "#9A958A",
                },
                rule: {
                    DEFAULT: "#D6D0C3",
                    strong: "#151412",
                },
                claret: {
                    DEFAULT: "#9E1B1B",
                    dark: "#7A1414",
                    tint: "#F3E4DF",
                },

                // Legacy keys kept for AdminCP compatibility
                forest: {
                    50: "#f0fdf8",
                    100: "#ccfbeb",
                    500: "#10b981",
                    600: "#059669",
                    700: "#047857",
                    800: "#0b2e28",
                    900: "#0b221e",
                    950: "#071917",
                },
                primary: {
                    light: "#3b82f6",
                    normal: "#2563eb",
                    text: "#1d4ed8",
                    dark: "#1e40af",
                },
                secondary: {
                    normal: "#e89980",
                    dark: "#f9e5df",
                },
                background: {
                    normal: "#f8fafc",
                    dark: "#0b221e",
                },
            },
            screens: {
                xs: "480px",
                ...defaultTheme.screens,
            },
            fontFamily: {
                serif: ["\"Source Serif 4\"", "Georgia", "Cambria", "\"Times New Roman\"", "serif"],
                sans: ["\"IBM Plex Sans\"", "\"Helvetica Neue\"", "Arial", ...defaultTheme.fontFamily.sans],
                mono: ["\"IBM Plex Mono\"", ...defaultTheme.fontFamily.mono],
                display: ["\"Source Serif 4\"", "Georgia", "serif"],
                helvetica: ["\"Helvetica Neue\"", "Helvetica", ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                xs: "0 1px 2px 0 rgba(15, 23, 42, 0.04)",
                card: "0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 1px 3px 0 rgba(15, 23, 42, 0.04)",
                "card-hover": "0 14px 30px -6px rgba(11, 34, 30, 0.10), 0 4px 10px -2px rgba(11, 34, 30, 0.05)",
            },
            keyframes: {
                slide: {
                    "0%": { transform: "translateX(0px)" },
                    "100%": { transform: "translateX(17.188rem)" },
                },
                slideBar: {
                    "0%": { transform: "-translateX(17.188rem)" },
                    "100%": { transform: "translateX(0px)" },
                },
                slideOut: {
                    "0%": { transform: "translateX(17.188rem)" },
                    "100%": { transform: "translateX(0px)" },
                },
                slideBarOut: {
                    "0%": { transform: "translateX(0px)" },
                    "100%": { transform: "-translateX(17.188rem)" },
                },
            },
            animation: {
                slide: "slide 0.5s ease-in",
                slideBar: "slideBar 0.5s ease-in",
                slideOut: "slideOut 0.5s ease-out",
                slideBarOut: "slideBarOut 0.5s ease-out",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms")({
            strategy: "class",
        }),
        require("@tailwindcss/typography"),
    ],
};
