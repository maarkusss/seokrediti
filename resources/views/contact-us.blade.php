@extends('layouts.public')

@section('title', $section->title ?: 'Par mums & reklāma - eirokrediti.lv')
@section('description', $section->description)
@section('keywords', $section->keywords)

@section('content')
    <div class="container" itemscope itemtype="https://schema.org/FinancialService">
        <div class="row">
            <div class="col-md-12">
                <h1 class="h2" itemprop="name">{{ $section->h1 ?: 'Par mums' }}</h1>
                <ol class="breadcrumb">
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="{{ route('homepage') }}"><span itemprop="title">eirokrediti.lv</span></a>
                    </li>
                </ol>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-8 container-large">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="margin:0;padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @php
                            $contentText = $section->text ?? '';
                            if (str_contains($contentText, 'navbar-header') || str_contains($contentText, 'container-fluid')) {
                                if (preg_match('/<div class="panel-body">\s*(.*?)\s*<h2>Uzdod jautājumu/is', $contentText, $matches)) {
                                    $contentText = $matches[1];
                                }
                            }
                        @endphp
                        @if ($contentText)
                            {!! $contentText !!}
                        @else
                            <p>Esam neatkarīgi <a href="/atrie-krediti">ātro kredītu</a>, <a href="/paterina-krediti">patēriņa kredīta</a> un <a href="/auto-lizings">auto līzinga</a> eksperti. Palīdzam cilvēkiem pieņemt pareizāko risinājumu, piedāvājot kredīta salīdzināšanas tabulas, kurās viegli var salīdzināt kredītus.</p>
                            <p>Lai palīdzētu pieņemt labāko risinājumu, starp vienādiem kredītu aizdevējiem, iesakam izmantot arī <a href="/atsauksmes">kredītu atsauksmes</a>.</p>
                            <p>Jautājuma gadījumos, kā arī reklāmu jautājumos, lūdzams, izmantot zemāk redzamo formu.</p>
                        @endif

                        <h2>Uzdod jautājumu</h2>
                        <div style="padding:10px 0;">
                            <form name="send_feedback" method="post" action="{{ route('section.post', ['name' => $section->route_name]) }}" id="form">
                                @csrf
                                <div style="padding-bottom:10px;">
                                    <label for="send_feedback_name" style="padding:0 0 2px 5px;font-weight:normal;display:block;margin:0;">Vārds <span style="color:#D50000;">*</span></label>
                                    <input id="send_feedback_name" class="form-control" type="text" name="name" value="{{ old('name') }}" required />
                                </div>
                                <div style="padding-bottom:10px;">
                                    <label for="send_feedback_email" style="padding:0 0 2px 5px;font-weight:normal;display:block;margin:0;">E-pasts <span style="color:#D50000;">*</span></label>
                                    <input id="send_feedback_email" class="form-control" type="email" name="email" value="{{ old('email') }}" required />
                                </div>
                                <div style="padding-bottom:10px;">
                                    <label for="send_feedback_question" style="padding:0 0 2px 5px;font-weight:normal;display:block;margin:0;">Jautājums <span style="color:#D50000;">*</span></label>
                                    <textarea id="send_feedback_question" class="form-control" name="question" cols="20" rows="5" style="overflow:auto" required>{{ old('question') }}</textarea>
                                </div>
                                <div style="padding-bottom:10px;position:relative;width:200px;height:80px;">
                                    <div style="position:absolute;top:0;right:0;">
                                        <a href="javascript:;" onclick="document.getElementById('captcha').src='/captcha?'+Math.random();document.getElementById('captcha-form').focus();">
                                            <span class="glyphicon glyphicon-refresh" style="font-size:20px;color:#337ab7;padding:4px;" title="Atsvaidzināt"></span>
                                        </a>
                                    </div>
                                    <img src="{{ route('captcha') }}" id="captcha" alt="captcha" style="border:1px solid #ddd;border-radius:4px;width:200px;height:70px;"/>
                                </div>
                                <div style="padding-bottom:10px;">
                                    <input type="text" class="form-control" name="captcha" id="captcha-form" maxlength="16" autocomplete="off" placeholder="Ievadiet drošības kodu" />
                                </div>
                                <div>
                                    <input class="form-control btn btn-sm btn-primary" name="send_feedback" value="Nosūtīt" type="submit" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 container-small">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">TOP 5 ātrie kredīti {{ __(date('m').'date') }}</h2>
                    </div>
                    <div class="panel-body loan-body">
                        <div class="row small-loans">
                            <div class="col-md-4 col-xs-12">
                                <a href="/atsauksmes/sosocredit"><img class="img-responsive" alt="Sosocredit logo" src="/images/other/20242407112054_5143.jpg"></a>
                            </div>
                            <div class="col-md-4 col-xs-12 text-center amount">līdz <span>25000 &euro;</span></div>
                            <div class="col-md-4 col-xs-12"><a rel="nofollow" target="_blank" class="get btn btn-sm btn-success" href="/go/sosocredit?p=1">Saņemt</a></div>
                        </div>
                        <div class="row small-loans">
                            <div class="col-md-4 col-xs-12">
                                <a href="/atsauksmes/kreditstar"><img class="img-responsive" alt="Kreditstar logo" src="/images/other/kreditstar.jpg"></a>
                            </div>
                            <div class="col-md-4 col-xs-12 text-center amount">līdz <span>25000 &euro;</span></div>
                            <div class="col-md-4 col-xs-12"><a rel="nofollow" target="_blank" class="get btn btn-sm btn-success" href="/go/kreditstar?p=2">Saņemt</a></div>
                        </div>
                        <div class="row small-loans">
                            <div class="col-md-4 col-xs-12">
                                <a href="/atsauksmes/viasms"><img class="img-responsive" alt="Viasms logo" src="/images/other/viasms-lv-kv.jpg"></a>
                            </div>
                            <div class="col-md-4 col-xs-12 text-center amount">līdz <span>5000 &euro;</span></div>
                            <div class="col-md-4 col-xs-12"><a rel="nofollow" target="_blank" class="get btn btn-sm btn-success" href="/go/viasms?p=3">Saņemt</a></div>
                        </div>
                        <div class="row small-loans">
                            <div class="col-md-4 col-xs-12">
                                <a href="/atsauksmes/soscredit"><img class="img-responsive" alt="Soscredit logo" src="/images/other/sosocredit.jpg"></a>
                            </div>
                            <div class="col-md-4 col-xs-12 text-center amount">līdz <span>25000 &euro;</span></div>
                            <div class="col-md-4 col-xs-12"><a rel="nofollow" target="_blank" class="get btn btn-sm btn-success" href="/go/soscredit?p=4">Saņemt</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
