@extends('layouts.public')

@section('title', '419 — Sesija beigusies')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="419"
        heading="Lapas sesija ir beigusies"
        message="Drošības nolūkos lūdzu atsvaidziniet lapu un mēģiniet iesniegt formu vēlreiz." />
@endsection
