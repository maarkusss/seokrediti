@extends('layouts.public')

@section('title', '404 — Lapa nav atrasta')
@section('robots', 'noindex, nofollow')

@section('content')
    <x-error-page
        code="404"
        heading="Meklētā lapa nav atrasta"
        message="Atvainojiet, pieprasītā lapa neeksistē vai ir tikusi pārvietota uz jaunu adresi." />
@endsection
