@extends('layouts.app')

@section('title', 'Add a lender - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <!-- Info box -->
        @include('admincp.components.infobox')
        <!-- End info box -->
        <div class="flex flex-row flex-auto h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')
            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Add new lender</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Create a new lender profile and offer parameters</p>
                    </div>
                    <a href="{{ route('admincp.lenders.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded transition-colors no-underline">
                        &larr; Back
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center justify-start my-4" id="lender-image-container">
                            <img class="object-contain max-w-full max-h-32 rounded border border-slate-200 p-2 hidden"
                                 alt="Lender image" id="lender-image">
                        </div>

                        <!-- 1. Main information -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Main information
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                            <div>
                                <label for="lender_name" class="block text-sm font-medium text-slate-700 mb-1.5">Name *</label>
                                <input id="lender_name" name="name" type="text" placeholder="Lender name" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="fileInput" class="block text-sm font-medium text-slate-700 mb-1.5">Logo image *</label>
                                <input id="fileInput" name="image" type="file" accept="image/*" onchange="displayImage(this);" required
                                    class="w-full text-sm text-slate-700 border border-slate-300 rounded p-2 bg-white">
                            </div>
                            <div>
                                <label for="affLink" class="block text-sm font-medium text-slate-700 mb-1.5">Affiliate link *</label>
                                <input id="affLink" name="affLink" type="text" placeholder="https://..." required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono">
                            </div>
                            <div>
                                <label for="route_name" class="block text-sm font-medium text-slate-700 mb-1.5">Route name *</label>
                                <input id="route_name" name="route_name" type="text" placeholder="lender-name" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono">
                            </div>
                        </div>

                        <!-- 2. Display parameters -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Offer terms and parameters
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                            <div class="sm:col-span-2">
                                <label for="slogan" class="block text-sm font-medium text-slate-700 mb-1.5">Slogan *</label>
                                <input id="slogan" name="slogan" type="text" placeholder="First loan without fee" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="firstLoan" class="block text-sm font-medium text-slate-700 mb-1.5">First loan (€)</label>
                                <input id="firstLoan" name="firstLoan" type="text" placeholder="500"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="receivingTime" class="block text-sm font-medium text-slate-700 mb-1.5">Receiving time (min) *</label>
                                <input id="receivingTime" name="receivingTime" type="text" placeholder="15" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="minAmount" class="block text-sm font-medium text-slate-700 mb-1.5">Min amount (€) *</label>
                                <input id="minAmount" name="minAmount" type="text" placeholder="50" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxAmount" class="block text-sm font-medium text-slate-700 mb-1.5">Max amount (€) *</label>
                                <input id="maxAmount" name="maxAmount" type="text" placeholder="5000" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="minTerm" class="block text-sm font-medium text-slate-700 mb-1.5">Min term (days) *</label>
                                <input id="minTerm" name="minTerm" type="text" placeholder="30" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxTerm" class="block text-sm font-medium text-slate-700 mb-1.5">Max term (days) *</label>
                                <input id="maxTerm" name="maxTerm" type="text" placeholder="360" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="minYears" class="block text-sm font-medium text-slate-700 mb-1.5">Min age *</label>
                                <input id="minYears" name="minYears" type="text" placeholder="18" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxYears" class="block text-sm font-medium text-slate-700 mb-1.5">Max age *</label>
                                <input id="maxYears" name="maxYears" type="text" placeholder="75" required
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                        </div>

                        <!-- Work hours -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Working hours
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
                            <div>
                                <label for="work_time_m_f" class="block text-sm font-medium text-slate-700 mb-1.5">Working days (Mon - Fri)</label>
                                <input id="work_time_m_f" name="work_time_m_f" type="text"
                                    placeholder="e.g. 8:00 - 20:00"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="work_time_sa" class="block text-sm font-medium text-slate-700 mb-1.5">Saturday (Sat)</label>
                                <input id="work_time_sa" name="work_time_sa" type="text"
                                    placeholder="e.g. 9:00 - 18:00 or -"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="work_time_su" class="block text-sm font-medium text-slate-700 mb-1.5">Sunday (Sun)</label>
                                <input id="work_time_su" name="work_time_su" type="text"
                                    placeholder="e.g. 10:00 - 16:00 or -"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                        </div>

                        <!-- 3. Loan Types / Categories -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Categories and loan types
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                            @foreach ($languages as $language)
                                <div class="p-4 bg-slate-50/70 border border-slate-200/80 rounded">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-3">
                                        Language: {{ strtoupper($language->value) }}
                                    </span>
                                    <div class="space-y-2">
                                        @forelse($categories->where('lang', $language->value) as $category)
                                            <label class="flex items-center text-sm text-slate-700 hover:text-slate-900 cursor-pointer">
                                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 mr-2 cursor-pointer"
                                                       name="category[{{ $category->id }}]"
                                                       value="1"
                                                       type="checkbox">
                                                <span>{{ $category->name }}</span>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 italic">No categories in this language</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- 4. Settings and status -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Additional settings
                        </h2>
                        <div class="flex flex-wrap items-center gap-6 mb-6">
                            <input type="hidden" name="zero_percent" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                       name="zero_percent" value="1" type="checkbox">
                                <span>0% first loan (Zero percent)</span>
                            </label>

                            <input type="hidden" name="top_lender" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                       name="top_lender" value="1" type="checkbox">
                                <span>Top Lender (TOP 5)</span>
                            </label>

                            <input type="hidden" name="active" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                       name="active" value="1" type="checkbox" checked>
                                <span>Active</span>
                            </label>
                        </div>

                        <!-- Bottom actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('admincp.lenders.index') }}"
                                class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer">
                                Add lender
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function displayImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let lenderImage = document.getElementById("lender-image");
                    lenderImage.src = e.target.result;
                    lenderImage.classList.remove("hidden");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
