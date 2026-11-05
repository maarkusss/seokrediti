const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    corePlugins: {
        container: false,
    },
    content: ["./resources/**/*.blade.php"],
    darkMode: "media",
    theme: {
        extend: {
            backgroundImage: {
                banner: "url('/images/banner.jpg')",
                overlay: "url('/images/overlay.png')",
            },
            colors: {
                gray: colors.neutral,
                "gray-slate": colors.slate,
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
                    normal: "#f0f0f0",
                    dark: "#2b2b2b",
                },
            },
            screens: {
                xs: "480px",
                ...defaultTheme.screens,
            },
            fontFamily: {
                helvetica: [
                    "Helvetica Neue",
                    "Helvetica",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        color: "#333",
                        a: {
                            color: theme("colors.primary.normal"),
                            "text-decoration-line": "none",
                            "&:hover": {
                                color: theme("colors.primary.dark"),
                                "text-decoration-line": "underline",
                            },
                        },
                        h1: {
                            "font-size": "1.5rem !important",
                            "line-height": "2rem !important",
                            "font-weight": "500 !important",
                        },
                        h2: {
                            "font-size": "1.5rem !important",
                            "line-height": "2rem !important",
                            "font-weight": "500 !important",
                            margin: "20px 0 10px !important",
                        },
                        h3: {
                            "font-size": "1.25rem !important",
                            "line-height": "1.75rem !important",
                            "font-weight": "500 !important",
                            margin: "20px 0 10px !important",
                        },
                        h4: {
                            "font-size": "1.125rem !important",
                            "line-height": "1.75rem !important",
                            "font-weight": "500 !important",
                            margin: "10px 0 !important",
                        },
                    },
                },
            }),
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
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
