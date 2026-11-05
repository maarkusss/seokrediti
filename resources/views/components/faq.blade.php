@props(['faqs' => $faqs, 'faqPage' => $faqPage])

<section>
    <div class="max-w-6xl py-6 mx-auto">

        @if ($faqPage == false)
            <h2 class="mb-8 text-2xl font-semibold text-center text-gray-800">
                @lang('Frequently Asked Questions')
            </h2>

            @foreach ($faqs as $faq)
                @php
                    $faq_details = App\Models\Admincp\Faqs\Faqs::find($faq->faq_id);
                @endphp

                <div x-data="{ open: false }" class="mb-3 overflow-hidden border border-gray-200 rounded-xl">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-5 py-4 text-left transition-colors duration-200 bg-gray-100 hover:bg-gray-200">
                        <span class="pr-4 text-base font-medium leading-snug text-gray-900">
                            {{ $faq_details->question }}
                        </span>
                        <svg :class="open ? 'rotate-180' : ''"
                            class="flex-shrink-0 w-4 h-4 text-gray-500 transition-transform duration-200"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                        class="px-5 py-4 text-sm leading-relaxed text-gray-600 border-t border-gray-200 bg-gray-50">
                        {!! $faq_details->answer !!}
                    </div>
                </div>
            @endforeach

        @else
            <div itemscope itemtype="https://schema.org/FAQPage">
                @foreach ($faqs as $faq)
                    <div x-data="{ open: false }" itemscope itemprop="mainEntity"
                        itemtype="https://schema.org/Question"
                        class="mb-3 overflow-hidden border border-gray-200 rounded-xl">

                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-5 py-4 text-left transition-colors duration-200 bg-gray-100 hover:bg-gray-200">
                            <h3 class="pr-4 text-base font-medium leading-snug text-gray-900" itemprop="name">
                                {{ $faq->question }}
                            </h3>
                            <svg :class="open ? 'rotate-180' : ''"
                                class="flex-shrink-0 w-4 h-4 text-gray-500 transition-transform duration-200"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition itemscope itemprop="acceptedAnswer"
                            itemtype="https://schema.org/Answer"
                            class="px-5 py-4 text-sm leading-relaxed text-gray-600 border-t border-gray-200 bg-gray-50">
                            <div itemprop="text" class="prose max-w-none">
                                {!! $faq->answer !!}
                            </div>
                            @if ($faq->topic)
                                <p class="mt-3 text-sm text-gray-400">
                                    {!! $faq->topic !!}
                                </p>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        @endif

    </div>
</section>