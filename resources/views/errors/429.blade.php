@extends('layouts.public')

@section('title', '429 — Pārāk daudz pieprasījumu')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="429"
        heading="Pārāk daudz pieprasījumu"
        message="Lūdzu, uzgaidiet dažas sekundes un mēģiniet atvērt lapu atkārtoti." />
@endsection
