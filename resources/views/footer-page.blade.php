@extends('layouts.public')

@section('title', $section->title ?: ($section->name ?: brand_name()))
@section('description', $section->description ?: (clean_legacy_brand($section->name) . ' — ' . brand_name()))

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="max-w-3xl">
                <span class="kicker mb-2">Oficiālā informācija</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink">
                    {{ clean_legacy_brand($section->h1 ?: $section->name) }}
                </h1>
                @if (!empty($section->h1_description))
                    <p class="standfirst mt-3">
                        {{ clean_legacy_brand($section->h1_description) }}
                    </p>
                @endif
                @if ($section->updated_at)
                    <p class="mt-3 meta">
                        Pēdējo reizi atjaunots: <time datetime="{{ $section->updated_at->toDateString() }}" class="num">{{ $section->updated_at->format('d.m.Y.') }}</time>
                    </p>
                @endif
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <article class="lg:col-span-8">
                <div class="seo-prose no-dropcap">
                    @if (!empty($section->text))
                        {!! clean_legacy_brand($section->text) !!}
                    @else
                        <p>Informācija drīzumā tiks papildināta. Jautājumu gadījumā rakstiet uz <a href="mailto:{{ brand_email() }}">{{ brand_email() }}</a>.</p>
                    @endif
                </div>
            </article>

            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
