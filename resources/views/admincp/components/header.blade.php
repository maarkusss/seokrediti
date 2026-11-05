<div class="fixed top-0 left-0 z-50 flex flex-row items-center justify-between w-full h-16 px-4 sm:px-6 bg-white border-b border-slate-200 shadow-xs">
    <div class="flex flex-row items-center gap-3">
        <button class="p-2 -ml-2 text-slate-600 rounded lg:hidden hover:bg-slate-100" id="sidebar-toggle">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <a href="{{ route('admincp.dashboard') }}" class="flex items-center gap-2 hover:opacity-90 transition-opacity">
            <img src="/images/logo-coin.png" alt="eirokrediti.lv" class="w-6 h-6 object-contain">
            <span class="text-lg font-bold text-slate-900 tracking-tight">eirokrediti.lv</span>
        </a>
    </div>
    <div class="flex flex-row items-center gap-3 sm:gap-4">
        <a href="{{ route('admincp.adminlogs.index') }}"
            class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">
            <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
        </a>
        <a class="flex flex-row items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-slate-600 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 rounded transition-colors cursor-pointer"
            href="{{ route('logout', ['lang' => app()->getLocale()]) }}"
            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
            <span class="hidden sm:inline">Log Out</span>
            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
            </svg>
        </a>
    </div>
    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</div>
