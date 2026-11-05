@extends('layouts.public')

@section('title', __('Autorizācija nepieciešama') . ' - ' . config('app.name'))

@section('content')
    <div class="container" itemscope itemtype="https://schema.org/FinancialService">
        <div class="row">
            <div class="col-md-12">
                <h1 class="h2" itemprop="name">401 - Autorizācija nepieciešama</h1>
                <ol class="breadcrumb">
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="{{ route('homepage') }}"><span itemprop="title">eirokrediti.lv</span></a>
                    </li>
                    <li class="active">401</li>
                </ol>
            </div>
            <div class="clearfix"></div>

            {{-- Main Column (840px desktop) --}}
            <div class="col-md-8 container-large">
                <div class="panel panel-default">
                    <div class="panel-body text-center" style="padding: 50px 20px;">
                        <p style="font-size: 72px; font-weight: 700; color: #f0ad4e; margin: 0 0 10px 0; line-height: 1;">401</p>
                        <h2 style="font-size: 24px; font-weight: 600; color: #333; margin: 0 0 15px 0;">Nepieciešama autorizācija</h2>
                        <p style="font-size: 15px; color: #666; max-width: 520px; margin: 0 auto 25px auto; line-height: 1.6;">
                            Lai piekļūtu šai lapai, lūdzu, vispirms autorizējieties sistēmā.
                        </p>
                        <p>
                            <a href="{{ route('homepage') }}" class="btn btn-primary" style="padding: 10px 24px; font-size: 15px; font-weight: 600;">
                                <span class="glyphicon glyphicon-home" style="margin-right: 6px;"></span> Atgriezties uz sākumlapu
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar (330px desktop) --}}
            @include('components.right-sidebar')
        </div>
    </div>
@endsection
