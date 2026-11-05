@props([
    'faqs' => collect(),
    'faqPage' => true,
    'heading' => null,
])

@php
    $resolvedFaqs = collect();
    foreach ($faqs as $item) {
        if (isset($item->question)) {
            $resolvedFaqs->push($item);
        } elseif (isset($item->faq_id)) {
            $found = \App\Models\Admincp\Faqs\Faqs::where('id', $item->faq_id)->where('active', true)->first();
            if ($found) {
                $resolvedFaqs->push($found);
            }
        }
    }
@endphp

@if ($resolvedFaqs->isNotEmpty())
    <section aria-labelledby="faq-section-heading">
        <div class="section-head">
            <div>
                <span class="kicker">Jautājumi un atbildes</span>
                <h2 id="faq-section-heading" class="section-title mt-1">
                    {{ $heading ?: __('Frequently Asked Questions') }}
                </h2>
            </div>
        </div>

        <div class="divide-y divide-rule border-y border-rule">
            @foreach ($resolvedFaqs as $idx => $faqItem)
                <div x-data="{ open: {{ $idx === 0 ? 'true' : 'false' }} }">
                    <button type="button"
                            @click="open = !open"
                            :aria-expanded="open.toString()"
                            class="w-full flex items-baseline justify-between gap-6 py-4 text-left hover:text-claret transition-colors cursor-pointer">
                        <h3 class="font-serif text-lg font-semibold text-ink leading-snug">
                            <span class="font-sans text-xs font-semibold text-ink-faint num mr-2">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}.</span>
                            {{ clean_legacy_brand($faqItem->question) }}
                        </h3>
                        <span class="font-mono text-base text-ink-muted shrink-0 select-none" x-text="open ? '−' : '+'">+</span>
                    </button>

                    <div x-cloak
                         x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="pb-5 pl-7 pr-4">
                        <div class="seo-prose no-dropcap text-base">
                            {!! clean_legacy_brand($faqItem->answer) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @push('schema')
        @php
            $faqEntities = $resolvedFaqs->map(fn ($f) => [
                '@type' => 'Question',
                'name' => strip_tags(clean_legacy_brand($f->question)),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => trim(strip_tags(clean_legacy_brand($f->answer))),
                ],
            ])->values()->all();
        @endphp
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqEntities,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endpush
@endif
