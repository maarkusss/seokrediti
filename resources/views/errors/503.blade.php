@extends('layouts.public')

@section('title', '503 — Tehniskā apkope')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="503"
        heading="Portālā notiek tehniskā apkope"
        message="Šobrīd atjauninām datus un sistēmu. Būsim atpakaļ jau pavisam drīz!" />
@endsection
