@extends('layouts.public')

@section('title', clean_legacy_brand($section->title) ?: 'Finanšu ceļveži un raksti par kredītiem')
@section('description', clean_legacy_brand($section->description) ?: 'Praktiski padomi par patēriņa kredītiem, GPL aprēķināšanu, kredītu apvienošanu, auto līzingu un atbildīgu budžeta plānošanu.')

@section('schema')
    @if ($blogPosts->isNotEmpty())
        @php
            $blogSchemaPosts = $blogPosts->map(fn ($post) => [
                '@type' => 'BlogPosting',
                'headline' => clean_legacy_brand($post->name),
                'image' => $post->image ? url($post->image) : null,
                'datePublished' => \Carbon\Carbon::parse($post->created_at)->toIso8601String(),
                'dateModified' => \Carbon\Carbon::parse($post->updated_at)->toIso8601String(),
                'url' => route('section.deep', ['name' => $section->route_name, 'deep_name' => $post->route_name]),
            ])->values()->all();
        @endphp
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Blog',
                'name' => clean_legacy_brand($section->h1 ?: $section->name),
                'description' => clean_legacy_brand($section->description),
                'url' => url()->current(),
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => brand_name(),
                ],
                'blogPost' => $blogSchemaPosts,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endsection

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="max-w-3xl">
                <span class="kicker mb-2">Finanšu pratība un analītika</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink">
                    {{ clean_legacy_brand($section->h1 ?: 'Finanšu ceļveži un kredītu jaunumi') }}
                </h1>
                <p class="standfirst mt-3">
                    {{ clean_legacy_brand($section->description) ?: 'Skaidri un praktiski ceļveži par procentu likmēm (GPL), aizdevumu apvienošanu, auto finansēšanu un atbildīgu aizņemšanos.' }}
                </p>
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8">
                @if ($blogPosts->isNotEmpty())
                    <div class="divide-y divide-rule">
                        @foreach ($blogPosts as $idx => $post)
                            @php
                                $postUrl = route('section.deep', ['name' => $section->route_name, 'deep_name' => $post->route_name]);
                                $excerpt = clean_legacy_brand(
                                    $post->description ?: strip_tags($post->source ?: \Illuminate\Support\Str::limit(strip_tags($post->text), 200))
                                );
                                $readMinutes = max(2, (int) ceil(str_word_count(strip_tags((string) $post->text)) / 180));
                            @endphp
                            <article class="{{ $idx === 0 ? 'pb-8' : 'py-8' }}">
                                @if ($idx === 0)
                                    {{-- Lead Story --}}
                                    @if (!empty($post->image))
                                        <a href="{{ $postUrl }}" class="block aspect-[16/9] overflow-hidden border border-rule mb-5 bg-paper-dark">
                                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                 data-lazy-src="{{ $post->image }}"
                                                 alt="{{ clean_legacy_brand($post->name) }}"
                                                 loading="lazy" decoding="async"
                                                 class="w-full h-full object-cover">
                                        </a>
                                    @endif
                                    <div class="meta uppercase tracking-wider mb-2">
                                        Galvenais raksts · {{ $readMinutes }} min lasījums · {{ \Carbon\Carbon::parse($post->updated_at ?: $post->created_at)->format('d.m.Y') }}
                                    </div>
                                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-ink leading-tight">
                                        <a href="{{ $postUrl }}" class="hover:text-claret">{{ clean_legacy_brand($post->name) }}</a>
                                    </h2>
                                    <p class="font-serif text-lg text-ink-soft mt-3 leading-relaxed">
                                        {{ $excerpt }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ $postUrl }}" class="link-more text-xs">Lasīt pilnu rakstu →</a>
                                    </div>
                                @else
                                    {{-- Standard Editorial Row --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-start">
                                        @if (!empty($post->image))
                                            <a href="{{ $postUrl }}" class="sm:col-span-4 aspect-[16/10] overflow-hidden border border-rule bg-paper-dark block">
                                                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                                     data-lazy-src="{{ $post->image }}"
                                                     alt="{{ clean_legacy_brand($post->name) }}"
                                                     loading="lazy" decoding="async" fetchpriority="low"
                                                     class="w-full h-full object-cover">
                                            </a>
                                        @endif
                                        <div class="{{ !empty($post->image) ? 'sm:col-span-8' : 'sm:col-span-12' }}">
                                            <div class="meta uppercase tracking-wider mb-1.5">
                                                Ceļvedis · {{ $readMinutes }} min · {{ \Carbon\Carbon::parse($post->updated_at ?: $post->created_at)->format('d.m.Y') }}
                                            </div>
                                            <h2 class="font-serif text-xl sm:text-2xl font-semibold text-ink leading-snug">
                                                <a href="{{ $postUrl }}" class="hover:text-claret">{{ clean_legacy_brand($post->name) }}</a>
                                            </h2>
                                            <p class="text-sm text-ink-soft mt-2 leading-relaxed">
                                                {{ $excerpt }}
                                            </p>
                                            <div class="mt-3">
                                                <a href="{{ $postUrl }}" class="link-more text-xs">Lasīt rakstu →</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="boxed p-8 text-center text-ink-muted">
                        Šobrīd jauni finanšu ceļveži tiek sagatavoti.
                    </div>
                @endif
            </div>

            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
