@extends('layouts.app')

@section('title', 'Edit a lender - ' . env('APP_NAME'))

@section('head')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">
                            Edit lender: <span class="text-blue-600">{{ $lender->name }}</span>
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">Edit lender data, terms and description</p>
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
                        <button type="submit" name="action" value="save" class="hidden" aria-hidden="true"></button>
                        <div id="editor"
                            data-image-browse-url="{{ env('APP_URL') . '/' . explode('-', app()->getlocale())[0] . '/admincp/images/' }}">
                        </div>

                        <!-- 1. Main information -->
                        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-semibold text-slate-800">Main information</h2>
                            <div class="h-12 w-28 p-1 bg-slate-50 border border-slate-200 rounded flex items-center justify-center" id="lender-image-container">
                                <img class="object-contain max-h-full max-w-full" src="{{ $lender->image }}" alt="{{ $lender->name }}" id="lender_image">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Name</label>
                                <input id="name" name="name" type="text" value="{{ $lender->name }}" placeholder="Lender's name"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="fileInput" class="block text-sm font-medium text-slate-700 mb-1.5">Change logo image</label>
                                <input id="fileInput" name="image" type="file" accept="image/*" onchange="displayImage(this);"
                                    class="w-full text-sm text-slate-700 border border-slate-300 rounded p-2 bg-white">
                            </div>
                            <div>
                                <label for="image_alt_text" class="block text-sm font-medium text-slate-700 mb-1.5">Image ALT text</label>
                                <input id="image_alt_text" name="image_alt_text" type="text"
                                    value="{{ $lender_data ? $lender_data->image_alt_text : null }}" placeholder="Logo ALT text"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="anchor_title" class="block text-sm font-medium text-slate-700 mb-1.5">Anchor title</label>
                                <input id="anchor_title" name="anchor_title" type="text"
                                    value="{{ $lender_data ? $lender_data->anchor_title : null }}" placeholder="Anchor title"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="affLink" class="block text-sm font-medium text-slate-700 mb-1.5">Affiliate link</label>
                                <input id="affLink" name="affLink" type="text" value="{{ $lender->affiliate_link }}" placeholder="https://..."
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="route_name" class="block text-sm font-medium text-slate-700 mb-1.5">Route name</label>
                                <input id="route_name" name="route_name" type="text" value="{{ $lender->route_name }}" placeholder="route-name"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono">
                            </div>
                        </div>

                        <!-- 2. Display parameters -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Offer terms and parameters
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                            <div class="sm:col-span-2">
                                <label for="slogan" class="block text-sm font-medium text-slate-700 mb-1.5">Slogan</label>
                                <input id="slogan" name="slogan" type="text" value="{{ $lender_data ? $lender_data->slogan : null }}" placeholder="Slogan"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="firstLoan" class="block text-sm font-medium text-slate-700 mb-1.5">First loan (€)</label>
                                <input id="firstLoan" name="firstLoan" type="text" value="{{ $lender->first_loan }}" placeholder="First loan"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="receivingTime" class="block text-sm font-medium text-slate-700 mb-1.5">Receiving time (min)</label>
                                <input id="receivingTime" name="receivingTime" type="text" value="{{ $lender->receiving_time }}" placeholder="15"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="minAmount" class="block text-sm font-medium text-slate-700 mb-1.5">Min amount (€)</label>
                                <input id="minAmount" name="minAmount" type="text" value="{{ $lender->min_amount }}" placeholder="50"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxAmount" class="block text-sm font-medium text-slate-700 mb-1.5">Max amount (€)</label>
                                <input id="maxAmount" name="maxAmount" type="text" value="{{ $lender->max_amount }}" placeholder="5000"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="minTerm" class="block text-sm font-medium text-slate-700 mb-1.5">Min term (days)</label>
                                <input id="minTerm" name="minTerm" type="text" value="{{ $lender->min_term }}" placeholder="30"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxTerm" class="block text-sm font-medium text-slate-700 mb-1.5">Max term (days)</label>
                                <input id="maxTerm" name="maxTerm" type="text" value="{{ $lender->max_term }}" placeholder="360"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="minYears" class="block text-sm font-medium text-slate-700 mb-1.5">Min age</label>
                                <input id="minYears" name="minYears" type="text" value="{{ $lender->min_years }}" placeholder="18"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxYears" class="block text-sm font-medium text-slate-700 mb-1.5">Max age</label>
                                <input id="maxYears" name="maxYears" type="text" value="{{ $lender->max_years }}" placeholder="75"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="maxApr" class="block text-sm font-medium text-slate-700 mb-1.5">Max APR (%)</label>
                                <input id="maxApr" name="maxApr" type="text" value="{{ $lender->max_apr }}" placeholder="54.2"
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
                                    value="{{ $lender_data ? $lender_data->work_time_m_f : null }}"
                                    placeholder="e.g. 8:00 - 20:00"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="work_time_sa" class="block text-sm font-medium text-slate-700 mb-1.5">Saturday (Sat)</label>
                                <input id="work_time_sa" name="work_time_sa" type="text"
                                    value="{{ $lender_data ? $lender_data->work_time_sa : null }}"
                                    placeholder="e.g. 9:00 - 18:00 or -"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="work_time_su" class="block text-sm font-medium text-slate-700 mb-1.5">Sunday (Sun)</label>
                                <input id="work_time_su" name="work_time_su" type="text"
                                    value="{{ $lender_data ? $lender_data->work_time_su : null }}"
                                    placeholder="e.g. 10:00 - 16:00 or -"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                        </div>

                        <!-- 3. Description (CKEditor) -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Lender description
                        </h2>
                        <div class="mb-6">
                            <textarea class="w-full border border-slate-300 rounded" name="description" id="description">{!! $lender_data ? $lender_data->description : null !!}</textarea>
                        </div>

                        <!-- 4. SEO settings -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            SEO settings
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="googleDescription" class="block text-sm font-medium text-slate-700 mb-1.5">Google description (Meta description)</label>
                                <input id="googleDescription" name="googleDescription" type="text"
                                    value="{{ $lender_data ? $lender_data->google_description : null }}" placeholder="Meta description"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                            <div>
                                <label for="googleKeywords" class="block text-sm font-medium text-slate-700 mb-1.5">Google keywords (Meta keywords)</label>
                                <input id="googleKeywords" name="googleKeywords" type="text"
                                    value="{{ $lender_data ? $lender_data->google_keywords : null }}" placeholder="keyword 1, keyword 2"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all">
                            </div>
                        </div>

                        <!-- 5. Loan Types / Categories -->
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
                                                       type="checkbox"
                                                       @if ($lender->categories->where('loan_type_id', $category->id)->first()) checked @endif>
                                                <span>{{ $category->name }}</span>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 italic">No categories in this language</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- 6. Settings and status -->
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Additional settings
                        </h2>
                        <div class="flex flex-wrap items-center gap-6 mb-6">
                            <input type="hidden" name="zero_percent" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                       name="zero_percent" value="1" type="checkbox"
                                       @if ($lender->zero_percent) checked @endif>
                                <span>0% first loan (Zero percent)</span>
                            </label>

                            <input type="hidden" name="top_lender" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                       name="top_lender" value="1" type="checkbox"
                                       @if ($lender->top_lender) checked @endif>
                                <span>Top Lender (TOP 5)</span>
                            </label>

                            <input type="hidden" name="active" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                       name="active" value="1" type="checkbox"
                                       @if ($lender->active) checked @endif>
                                <span>Active</span>
                            </label>
                        </div>

                        <!-- Bottom actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                <button class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded transition-colors cursor-pointer"
                                        type="submit" name="action" value="delete"
                                        onclick="return confirm('Are you sure you want to delete this lender?');">
                                    Delete lender
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admincp.lenders.index') }}"
                                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                    Cancel
                                </a>
                                <button class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                        type="submit" name="action" value="save">
                                    Save changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace('description');
    </script>
@endsection

@section('javascript')
    <script>
        function displayImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let lenderImage = document.getElementById("lender_image");
                    lenderImage.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
