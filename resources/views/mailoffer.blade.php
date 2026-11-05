<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="robots"
          content="noindex, nofollow">
    <style>
        .body {
            background: #f7fafc;
            margin: 0;
            padding: 15px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
        }

        .logo {
            text-align: center;
            margin: 0 0 15px 0;
        }

        .title {
            text-align: center;
            font-size: 1rem;
            color: #2d3748;
            font-weight: 600;
        }

        .lender-box-outside {
            width: 25%;
            float: left;
            overflow: hidden;
            position: relative;
        }

        .lender-box-inside {
            margin: 0 15px 15px 0;
            text-align: center;
            padding: 10px;
            background: #FFF;
            border-radius: .5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
            border: 1px solid #e2e8f0;
        }

        .lender-box-inside-primary {
            border: 1px solid #48bb78;
            ;
        }

        .lender-slogan {
            position: absolute;
            left: 0;
            top: 0;
            font-size: .875rem;
            color: #2d3748;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
            padding: .25rem;
            line-height: 1;
            font-weight: 600;
            border-radius: .25rem;
            background-color: #e2e8f0;
            text-align: center;
        }

        .lender-slogan-primary {
            background-color: #48bb78;
            color: #FFF;
        }

        .lender-grid {
            overflow: hidden;
        }

        .lender-left {
            float: left;
            width: 50%;
        }

        .lender-right {
            float: left;
            width: 50%;
        }

        .lender-left .lender-title,
        .lender-right .lender-title {
            font-size: .875rem;
            color: #718096;
            line-height: 1;
            font-weight: 400;
            text-align: left;
        }

        .lender-left .lender-text,
        .lender-right .lender-text {
            font-size: 1.125rem;
            color: #2d3748;
            line-height: 1;
            font-weight: 600;
            text-align: left;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .lender-text-primary {
            color: #f4d20f;
        }

        .lender-logo-container {
            margin-bottom: 1rem;
            height: 3rem;
            margin-top: 15px;
        }

        .lender-logo-img {
            max-width: 100%;
            max-height: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            text-align: center;
        }

        .lender-button {
            border-radius: .5rem;
            font-size: 1rem;
            color: #2d3748 !important;
            text-align: center;
            padding: 15px 0;
            display: block;
            line-height: 1;
            font-weight: 600;
            background-color: #f4d20f;
            text-decoration: none;
        }

        .clear {
            clear: both;
        }

        .h4 {
            font-size: 1.125rem;
            color: #2d3748;
            margin-top: 1rem;
            line-height: 1;
            font-weight: 600;
            margin-bottom: 0;
        }

        .p {
            font-size: 1rem;
            color: #718096;
            margin-top: .5rem;
            line-height: 1.25;
            font-weight: 400;
        }

        .p a {
            color: #718096 !important;
            text-decoration: underline !important;
        }

        .unsubscribe {
            padding-top: 10px;
            border-top: 1px solid #dcdcdc;
            text-align: center;
        }

        @media screen and (max-device-width: 1100px),
        screen and (max-width: 1100px) {
            .lender-box-outside {
                width: 33%;
            }
        }

        @media screen and (max-device-width: 720px),
        screen and (max-width: 720px) {
            .lender-box-outside {
                width: 50%;
            }
        }

        @media screen and (max-device-width: 500px),
        screen and (max-width: 500px) {
            .lender-box-outside {
                width: 100%;
            }

            .lender-box-inside {
                margin-right: 0;
            }
        }

    </style>
</head>

<body class="body">

    <div class="logo">
        <a href="https://www.kreditstar.com.ua"
           target="_blank"><img src="https://www.kreditstar.com.ua/logo.png"
                 width="129"
                 height="24"
                 alt=""></a>
    </div>

    <p class="title">Заполните простую заявку, подождите решение и получите деньги. Рекомендуем подавать заявки сразу в несколько компаний!</p>

    @php
        $i = 0;
    @endphp
    @foreach ($lenders as $lender)
        @php
            $i++;
            $lenders_data = \App\Models\Admincp\Lenders\LendersData::where('lender_id', $lender->id)
                ->where('lang', app()->getLocale())
                ->first();
            $slogan_medal_arr = ['🥇', '🥈', '🥉'];
        @endphp
        <div class="lender-box-outside">
            <div class="lender-box-inside{{ $i <= 3 ? ' lender-box-inside-primary' : '' }}">
                <div class="lender-slogan{{ $i <= 3 ? ' lender-slogan-primary' : '' }}">
                    {{ $i <= 3 ? $slogan_medal_arr[$i - 1] : '' }}
                    {{ $lenders_data ? $lenders_data->slogan : '' }}
                </div>
                <div class="lender-logo-container">
                    <a href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $i]) }}?utm_source={{ $user->utm_source }}&utm_medium={{ $user->utm_medium }}&utm_campaign={{ $user->utm_campaign }}&utm_content={{ $user->utm_content }}&gclid={{ $user->gclid }}"
                       rel="nofollow"
                       target="_blank"><img class="lender-logo-img"
                             src="https://www.kreditstar.com.ua{{ $lender->image }}"
                             alt="Kreditstar"></a>
                </div>
                <div class="lender-grid">
                    <div class="lender-left">
                        <div class="lender-title">Кредит</div>
                        <div class="lender-text">{!! $lender->zero_percent ? '<span class="lender-text-primary">0,01%</span> ' : '' !!}{{ __('main.līdz') }} {{ $lender->first_loan }}₴</div>
                    </div>
                    <div class="lender-right">
                        <div class="lender-title">Срок</div>
                        <div class="lender-text">{{ __('main.līdz') }} {{ $lender->max_term }} дней</div>
                    </div>
                    <div class="clear"></div>
                    <div class="lender-left">
                        <div class="lender-title">Возраст</div>
                        <div class="lender-text">{{ $lender->min_years }} {{ __('main.līdz') }} {{ $lender->max_years }}</div>
                    </div>
                </div>
                <a class="lender-button"
                   href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $i]) }}?utm_source={{ $user->utm_source }}&utm_medium={{ $user->utm_medium }}&utm_campaign={{ $user->utm_campaign }}&utm_content={{ $user->utm_content }}&gclid={{ $user->gclid }}"
                   rel="nofollow"
                   target="_blank">{{ __('main.Receive a loan') }}</a>
            </div>
        </div>
    @endforeach
    <div class="clear"></div>

    <h4 class="h4">Кто может получить кредит?</h4>
    <p class="p">Мы найдем подходящий вариант для всех, кто имеет украинское гражданство, а также для лиц старше 18 лет. Место работы, трудовой стаж, уровень дохода, кредитная история, место жительства и регистрации не имеет значения и главное подходить основным базовым требованиям кредитодателя.</p>

    <h4 class="h4">Что Вам нужно для получения денег?</h4>
    <p class="p">При подаче заявки на кредит через Kreditstar вам понадобится лишь паспорт, номер мобильного телефона и адрес электронной почты. Введите необходимые данные в анкете, получите СМС от кредитора, индивидуальные рекомендации с лучшими предложениями.</p>

    <h4 class="h4">Сколько и на какой срок я могу взять?</h4>
    <p class="p">Параметры кредита зависят от индивидуальных особенностей клиента, его платежеспособности, рейтинга, потребностей и возможностей. Кредитстар предлагает кредит от своих партнеров от 100 до 15 000 гривен, срок возврата может составлять от 1 дня до 12 месяцев. Но занимая в первый раз не более 15 000 гривен на 1-2 месяца.</p>

    <h4 class="h4">Каковы последствия просрочки по кредиту?</h4>
    <p class="p">В случае, если сумма кредита не будет выплачена в указанный срок, кредитор может взимать штраф от общей суммы просроченного платежа за каждый день просрочки. Большинство кредиторов идут на уступки и дают дополнительные 3-7 рабочих дней продления. Они предоставляются только в том случае, если банковский перевод требует больше времени чем обычно. Однако, если вы не отреагируете на запрос кредитора и задержите выплату по кредиту, будет начислен штраф в среднем 0,10% от общей суммы кредита. Также кредитор имеет право в одностороннем порядке передать информацию о Вас реестру должников, а взыскание кредита коллекторскому агентству.</p>
    <p class="p">О сроках платежа кредитор своевременно информирует, отправляя СМС а также в электронном виде по почте. Мы рекомендуем делать платежи в день получения этих уведомлений. Так, Вы сохраните репутацию добросовестного заемщика и при последующем обращении сможете получать скидки на повторные займы.</p>

    <h4 class="h4">Как долго рассматривается заявка на кредит?</h4>
    <p class="p">Решение принимается мгновенно, но не позже, чем через 5 минут.</p>

    <h4 class="h4">Могу ли я получить одновременно несколько кредитов?</h4>
    <p class="p">Да, вы можете получить несколько кредитов одновременно.</p>

    <h4 class="h4">В течение какого времени я получу свой кредит?</h4>
    <p class="p">Деньги на вашу карту поступают после утверждения заявки и подписания договора двумя сторонами.</p>

    <p class="p unsubscribe">Если в дальнейшем не желаешь получать новости и эл. почту от Kreditstar, нажми
        <a href="https://www.kreditstar.com.ua/ru/unsubscribe"
           target="_blank">здесь</a>.
    </p>

</body>

</html>
