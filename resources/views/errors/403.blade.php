@extends('layouts.public')

@section('title', '403 — Piekļuve liegta')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="403"
        heading="Piekļuve šai lapai ir liegta"
        message="Jums nav nepieciešamo tiesību šīs lapas apskatei." />
@endsection
