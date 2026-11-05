import Alpine from "alpinejs";

// Safe analytics stub so custom head scripts never throw ReferenceError
window.ga =
    window.ga ||
    function () {
        (window.ga.q = window.ga.q || []).push(arguments);
    };

document.addEventListener("alpine:init", () => {
    Alpine.data("creditCalculator", (config = {}) => ({
        amount: Number(config.initialAmount || 1000),
        minAmount: Number(config.minAmount || 50),
        maxAmount: Number(config.maxAmount || 15000),
        months: Number(config.initialMonths || 12),
        minMonths: Number(config.minMonths || 1),
        maxMonths: Number(config.maxMonths || 72),
        annualRate: Number(config.annualRate || 18.9),
        zeroOnly: Boolean(config.zeroOnly || false),
        age18Only: Boolean(config.age18Only || false),
        fastPayout: Boolean(config.fastPayout || false),

        init() {
            this.$watch("amount", () => this.broadcast());
            this.$watch("months", () => this.broadcast());
            this.$watch("zeroOnly", () => this.broadcast());
            this.$watch("age18Only", () => this.broadcast());
            this.$watch("fastPayout", () => this.broadcast());
        },

        get amountStep() {
            if (this.amount < 500) return 25;
            if (this.amount < 2000) return 50;
            if (this.amount < 5000) return 100;
            return 250;
        },

        get monthlyPayment() {
            const principal = Math.max(50, Number(this.amount) || 500);
            const term = Math.max(1, Number(this.months) || 1);

            if (this.zeroOnly && term <= 1) {
                return principal.toFixed(2);
            }

            const monthlyRate = this.annualRate / 100 / 12;
            if (monthlyRate <= 0) {
                return (principal / term).toFixed(2);
            }

            const factor = Math.pow(1 + monthlyRate, term);
            const payment = (principal * (monthlyRate * factor)) / (factor - 1);
            return payment.toFixed(2);
        },

        get totalRepayment() {
            return (Number(this.monthlyPayment) * Math.max(1, Number(this.months))).toFixed(2);
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("lv-LV", {
                maximumFractionDigits: 0,
            }).format(Number(value) || 0);
        },

        setPreset(sum, term, zero = false) {
            this.amount = sum;
            this.months = term;
            this.zeroOnly = zero;
            this.broadcast();
        },

        broadcast() {
            window.dispatchEvent(
                new CustomEvent("credit-filter-change", {
                    detail: {
                        amount: Number(this.amount),
                        months: Number(this.months),
                        zeroOnly: Boolean(this.zeroOnly),
                        age18Only: Boolean(this.age18Only),
                        fastPayout: Boolean(this.fastPayout),
                    },
                })
            );
        },

        scrollToOffers() {
            this.broadcast();
            const target = document.getElementById("piedavajumi");
            if (target) {
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        },
    }));

    Alpine.data("lenderFilterList", (config = {}) => ({
        amount: Number(config.amount || 0),
        months: Number(config.months || 0),
        zeroOnly: false,
        age18Only: false,
        fastPayout: false,
        activeFilter: false,
        visibleCount: Number(config.totalCount || 0),
        totalCount: Number(config.totalCount || 0),

        init() {
            this.$nextTick(() => this.recount());
        },

        applyExternalFilter(detail) {
            if (!detail) return;
            this.amount = Number(detail.amount || 0);
            this.months = Number(detail.months || 0);
            this.zeroOnly = Boolean(detail.zeroOnly);
            this.age18Only = Boolean(detail.age18Only);
            this.fastPayout = Boolean(detail.fastPayout);
            this.activeFilter = true;
            this.$nextTick(() => this.recount());
        },

        toggleChip(chip) {
            this[chip] = !this[chip];
            this.activeFilter =
                this.zeroOnly || this.age18Only || this.fastPayout || this.amount > 0;
            this.$nextTick(() => this.recount());
        },

        resetFilters() {
            this.amount = 0;
            this.months = 0;
            this.zeroOnly = false;
            this.age18Only = false;
            this.fastPayout = false;
            this.activeFilter = false;
            this.$nextTick(() => this.recount());
        },

        matchesCard(meta) {
            if (!this.activeFilter) return true;

            if (this.amount > 0) {
                if (meta.maxAmount > 0 && this.amount > meta.maxAmount) return false;
                if (meta.minAmount > 0 && this.amount < meta.minAmount) return false;
            }

            if (this.months > 0 && meta.maxDays > 0) {
                const requestedDays = this.months * 30;
                if (requestedDays > meta.maxDays + 5) return false;
            }

            if (this.zeroOnly && !meta.zeroPercent) {
                return false;
            }

            if (this.age18Only && meta.minYears > 18) {
                return false;
            }

            if (this.fastPayout && meta.receivingMinutes > 20) {
                return false;
            }

            return true;
        },

        recount() {
            if (!this.$root) return;
            const cards = this.$root.querySelectorAll("[data-lender-card]");
            let count = 0;
            cards.forEach((el) => {
                if (el.offsetParent !== null || el.style.display !== "none") {
                    count++;
                }
            });
            this.visibleCount = count;
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();

// Subtle IntersectionObserver scroll reveal for below-the-fold sections
if (typeof window !== "undefined" && "IntersectionObserver" in window) {
    const initReveal = () => {
        const items = document.querySelectorAll("[data-reveal]");
        if (!items.length) return;

        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.08, rootMargin: "0px 0px -30px 0px" }
        );

        items.forEach((el) => observer.observe(el));
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initReveal, { once: true });
    } else {
        initReveal();
    }
}

// Viewport-driven asynchronous image loader:
// Images with [data-lazy-src] are fetched only when they scroll into view.
if (typeof window !== "undefined") {
    const loadImage = (img) => {
        const realSrc = img.getAttribute("data-lazy-src");
        if (!realSrc) return;

        const markReady = () => img.classList.add("is-loaded");
        img.addEventListener("load", markReady, { once: true });
        img.addEventListener("error", markReady, { once: true });
        img.src = realSrc;

        if (img.complete) {
            markReady();
        }
    };

    const initLazyImages = () => {
        const lazyImages = document.querySelectorAll("img[data-lazy-src]");
        if (!lazyImages.length) return;

        if (!("IntersectionObserver" in window)) {
            lazyImages.forEach(loadImage);
            return;
        }

        const imgObserver = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        loadImage(entry.target);
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.01, rootMargin: "80px 0px" }
        );

        lazyImages.forEach((img) => imgObserver.observe(img));
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initLazyImages, { once: true });
    } else {
        initLazyImages();
    }
}
