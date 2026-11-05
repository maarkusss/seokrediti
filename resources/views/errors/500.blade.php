@extends('layouts.public')

@section('title', '500 — Sistēmas kļūda')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="500"
        heading="Īslaicīga sistēmas kļūda"
        message="Atvainojiet, apstrādājot pieprasījumu radās neparedzēta kļūda. Lūdzu, mēģiniet vēlāk." />
@endsection
