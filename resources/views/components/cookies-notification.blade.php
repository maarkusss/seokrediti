@if (!request()->cookie('cookies_accepted'))
    <div x-data="{
            visible: false,
            init() {
                if (!document.cookie.split(';').some(c => c.trim().startsWith('cookies_accepted='))) {
                    this.visible = true;
                }
            },
            accept() {
                const exp = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toUTCString();
                document.cookie = 'cookies_accepted=1; expires=' + exp + '; path=/; SameSite=Lax';
                this.visible = false;
            }
        }"
        x-cloak
        x-show="visible"
        data-nosnippet
        class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-md z-50 bg-paper-light text-ink border-2 border-ink p-5">
        <div class="space-y-3">
            <div class="kicker">Sīkdatņu paziņojums</div>
            <p class="text-xs text-ink-soft leading-relaxed">
                @lang('By using this website, in accordance with the rules, you agree to the placement of cookies on your device. Cookies collect data about visiting the website, the data is anonymous and helps to offer you suitable content. You can change your consent at any time.')
            </p>
            <div class="flex items-center justify-between gap-4 pt-1">
                <a href="{{ url('/sikfaili') }}" class="text-xs link">
                    Sīkdatņu politika
                </a>
                <button type="button"
                        @click="accept()"
                        class="btn-ink btn-sm">
                    @lang('I agree')
                </button>
            </div>
        </div>
    </div>
@endif
