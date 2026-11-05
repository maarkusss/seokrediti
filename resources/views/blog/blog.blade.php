@php
    $postTitle = clean_legacy_brand($blogPost->title ?: $blogPost->name);
    $postDescription = clean_legacy_brand(
        $blogPost->description ?: strip_tags($blogPost->source ?: \Illuminate\Support\Str::limit(strip_tags($blogPost->text), 160))
    );
    $readMinutes = max(2, (int) ceil(str_word_count(strip_tags((string) $blogPost->text)) / 180));

    $rawBody = clean_legacy_brand((string) $blogPost->text);
    while (preg_match('/^\s*<div\s+class=[\"\'\s]*panel\s+panel-default[\"\'\s]*>\s*<div\s+class=[\"\'\s]*panel-body[\"\'\s]*>/i', $rawBody)) {
        $rawBody = preg_replace('/^\s*<div\s+class=[\"\'\s]*panel\s+panel-default[\"\'\s]*>\s*<div\s+class=[\"\'\s]*panel-body[\"\'\s]*>/i', '', $rawBody);
        $rawBody = preg_replace('/<\/div>\s*<\/div>\s*$/i', '', $rawBody);
        $rawBody = trim((string) $rawBody);
    }
@endphp

@extends('layouts.public')

@section('title', $postTitle)
@section('description', $postDescription)
@section('og_type', 'article')
@if (!empty($blogPost->image))
    @section('og_image', url($blogPost->image))
@endif

@section('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => clean_legacy_brand($blogPost->name),
            'description' => $postDescription,
            'image' => $blogPost->image ? url($blogPost->image) : asset('images/brand/og-cover.svg'),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url()->current(),
            ],
            'url' => url()->current(),
            'datePublished' => \Carbon\Carbon::parse($blogPost->created_at)->toIso8601String(),
            'dateModified' => \Carbon\Carbon::parse($blogPost->updated_at ?: $blogPost->created_at)->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => brand_name() . ' finanšu redakcija',
                'url' => url('/'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => brand_name(),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => brand_logo_url() ? url(brand_logo_url()) : asset('favicon.svg'),
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2 text-xs text-ink-muted mb-3">
                    <a href="{{ route('section', ['name' => $blogPostParent->route_name]) }}" class="kicker">
                        {{ clean_legacy_brand($blogPostParent->name) }}
                    </a>
                    <span>·</span>
                    <span>Lasīšanas laiks: {{ $readMinutes }} min</span>
                    <span>·</span>
                    <time class="num">{{ \Carbon\Carbon::parse($blogPost->updated_at ?: $blogPost->created_at)->format('d.m.Y') }}</time>
                </div>

                <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink leading-tight">
                    {{ clean_legacy_brand($blogPost->h1 ?: $blogPost->name) }}
                </h1>

                @if (!empty($blogPost->h1_description))
                    <p class="standfirst mt-3">
                        {{ clean_legacy_brand($blogPost->h1_description) }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8 space-y-12">
                <article>
                    @if (!empty($blogPost->image))
                        <figure class="mb-8 border border-rule bg-paper-dark">
                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                 data-lazy-src="{{ $blogPost->image }}"
                                 alt="{{ clean_legacy_brand($blogPost->name) }}"
                                 loading="lazy" decoding="async"
                                 class="w-full max-h-[420px] object-cover">
                        </figure>
                    @endif

                    <div class="border-y border-rule py-3 mb-6 text-xs text-ink-muted flex flex-wrap items-center justify-between gap-2">
                        <span>Autors: <strong class="text-ink">{{ brand_name() }} finanšu redakcija</strong></span>
                        <span>Avoti: PTAC vadlīnijas un licencēto aizdevēju publiskie cenrāži</span>
                    </div>

                    @if (!empty($blogPost->source))
                        <div class="standfirst mb-6 pb-6 border-b border-rule">
                            {!! clean_legacy_brand($blogPost->source) !!}
                        </div>
                    @endif

                    <div class="seo-prose">
                        {!! $rawBody !!}
                    </div>

                    <x-share-buttons :title="$blogPost->name" />
                </article>

                @if (isset($relatedPosts) && $relatedPosts->isNotEmpty())
                    <section aria-labelledby="related-guides-heading">
                        <div class="section-head">
                            <div>
                                <span class="kicker">Lasiet arī</span>
                                <h2 id="related-guides-heading" class="section-title mt-1">Citi finanšu ceļveži</h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 border-y border-rule divide-y sm:divide-y-0 sm:divide-x divide-rule">
                            @foreach ($relatedPosts as $rel)
                                @php
                                    $relUrl = route('section.deep', ['name' => $blogPostParent->route_name, 'deep_name' => $rel->route_name]);
                                @endphp
                                <a href="{{ $relUrl }}" class="p-4 sm:p-5 flex flex-col justify-between hover:bg-paper-light transition-colors group">
                                    <h3 class="font-serif text-lg font-semibold text-ink group-hover:text-claret leading-snug">
                                        {{ clean_legacy_brand($rel->name) }}
                                    </h3>
                                    <span class="link-more text-xs mt-4 inline-block">Lasīt →</span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
