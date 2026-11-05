@if (!request()->cookie('cookies_accepted'))
    <div id="cookies_notification" data-nosnippet style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9999;background:rgba(23, 23, 23, 0.94);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);color:#fff;border-top:1px solid rgba(255,255,255,0.08);padding:14px 0;box-shadow:0 -4px 16px rgba(0,0,0,0.25);font-size:13px;line-height:1.5;">
        <div class="container">
            <div class="cookies-box">
                <div class="cookies-text">
                    <p style="margin:0;color:#e5e5e5;font-size:13px;line-height:1.5;">
                        @lang('By using this website, in accordance with the rules, you agree to the placement of cookies on your device. Cookies collect data about visiting the website, the data is anonymous and helps to offer you suitable content. You can change your consent at any time.')
                    </p>
                </div>
                <div class="cookies-btn-wrap">
                    <button id="cookies_notification_button" type="button" class="btn btn-success cookies-btn" title="@lang('I agree')" aria-label="@lang('I agree')">
                        @lang('I agree')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cookies-box {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }
        .cookies-text {
            width: 100%;
        }
        .cookies-btn-wrap {
            width: 100%;
        }
        .cookies-btn {
            display: block !important;
            width: 100% !important;
            font-weight: 700;
            font-size: 14px;
            padding: 9px 24px;
            border-radius: 4px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        @media (min-width: 768px) {
            .cookies-box {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 24px;
            }
            .cookies-text {
                flex: 1;
            }
            .cookies-btn-wrap {
                width: auto;
                flex-shrink: 0;
            }
            .cookies-btn {
                display: inline-block !important;
                width: auto !important;
                font-size: 13px;
                padding: 8px 24px;
            }
        }
    </style>
@endif

<script>
    (function() {
        var banner = document.getElementById('cookies_notification');
        var btn = document.getElementById('cookies_notification_button');
        if (!banner || !btn) return;

        if (!document.cookie.split(';').some(function(item) { return item.trim().indexOf('cookies_accepted=') === 0; })) {
            banner.style.display = 'block';
        }

        btn.addEventListener('click', function() {
            var expiry = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toUTCString();
            document.cookie = "cookies_accepted=1; expires=" + expiry + "; path=/; SameSite=Lax";
            banner.style.display = 'none';
        });
    })();
</script>
