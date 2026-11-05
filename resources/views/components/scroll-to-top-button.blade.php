<div x-data="{ showTop: false }"
     @scroll.window="showTop = window.scrollY > 500"
     x-cloak
     x-show="showTop"
     x-transition.opacity
     class="fixed bottom-5 right-5 z-40">
    <button type="button"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="Ritināt uz augšu"
            class="px-3 py-2 bg-paper-light border border-ink text-xs font-semibold uppercase tracking-wider text-ink hover:bg-ink hover:text-paper-light transition-colors cursor-pointer">
        ↑ Uz augšu
    </button>
</div>
