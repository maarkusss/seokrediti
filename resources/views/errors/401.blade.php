@extends('layouts.public')

@section('title', '401 — Nepieciešama autorizācija')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="401"
        heading="Nepieciešama autorizācija"
        message="Lai piekļūtu šai sadaļai, lūdzu, pieslēdzieties savam profilam." />
@endsection
