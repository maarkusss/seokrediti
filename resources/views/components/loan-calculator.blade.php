@props([
    'initialAmount' => 1000,
    'minAmount' => 50,
    'maxAmount' => 15000,
    'initialMonths' => 12,
    'minMonths' => 1,
    'maxMonths' => 72,
])

<div x-data="creditCalculator({
        initialAmount: {{ (int) $initialAmount }},
        minAmount: {{ (int) $minAmount }},
        maxAmount: {{ (int) $maxAmount }},
        initialMonths: {{ (int) $initialMonths }},
        minMonths: {{ (int) $minMonths }},
        maxMonths: {{ (int) $maxMonths }}
    })"
    class="bg-paper-light border border-ink">

    <div class="px-4 py-2.5 border-b border-ink flex items-baseline justify-between">
        <h2 class="font-serif text-base font-semibold">Maksājuma aprēķins</h2>
        <span class="text-[10px] uppercase tracking-wider text-ink-muted">Orientējoši</span>
    </div>

    <div class="p-4 space-y-4">
        <div>
            <div class="flex items-baseline justify-between mb-1.5">
                <label for="calc-amount-range" class="label mb-0">Summa</label>
                <output class="font-serif text-xl font-semibold num"><span x-text="formatCurrency(amount)">{{ number_format($initialAmount, 0, ',', ' ') }}</span> €</output>
            </div>
            <input id="calc-amount-range" type="range" class="ink-range"
                   :min="minAmount" :max="maxAmount" :step="amountStep" x-model.number="amount">
            <div class="flex justify-between text-[11px] text-ink-faint mt-1 num">
                <span>{{ $minAmount }} €</span>
                <span>{{ number_format($maxAmount, 0, ',', ' ') }} €</span>
            </div>
        </div>

        <div>
            <div class="flex items-baseline justify-between mb-1.5">
                <label for="calc-months-range" class="label mb-0">Termiņš</label>
                <output class="font-serif text-xl font-semibold num"><span x-text="months">{{ $initialMonths }}</span> <span class="text-sm font-normal" x-text="months == 1 ? 'mēnesis' : 'mēneši'">mēneši</span></output>
            </div>
            <input id="calc-months-range" type="range" class="ink-range"
                   :min="minMonths" :max="maxMonths" step="1" x-model.number="months">
            <div class="flex justify-between text-[11px] text-ink-faint mt-1 num">
                <span>1 mēn.</span>
                <span>{{ $maxMonths }} mēn.</span>
            </div>
        </div>

        <fieldset class="pt-1">
            <legend class="label mb-1.5">Rādīt tikai</legend>
            <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-ink-soft">
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" x-model="zeroOnly" class="w-3.5 h-3.5 accent-[#151412]">
                    <span>0% pirmais</span>
                </label>
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" x-model="age18Only" class="w-3.5 h-3.5 accent-[#151412]">
                    <span>no 18 g.</span>
                </label>
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" x-model="fastPayout" class="w-3.5 h-3.5 accent-[#151412]">
                    <span>~15 min</span>
                </label>
            </div>
        </fieldset>

        <div class="border-t border-ink pt-3 flex items-baseline justify-between gap-3">
            <div>
                <div class="text-xs font-medium text-ink">Mēneša maksājums*</div>
                <div class="text-[11px] text-ink-muted num">Kopā: <span x-text="totalRepayment"></span> €</div>
            </div>
            <div class="font-serif text-2xl font-bold num text-ink"><span x-text="monthlyPayment">92,11</span> €</div>
        </div>

        <button type="button" @click="scrollToOffers()" class="btn-ink btn-sm w-full">
            Atlasīt piemērotos aizdevējus ↓
        </button>

        <p class="text-[10px] leading-snug text-ink-muted">
            * Orientējoša likme 18,9% gadā. Precīzu GPL nosaka aizdevējs.
        </p>
    </div>
</div>
