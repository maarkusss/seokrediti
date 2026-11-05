@extends('layouts.app')

@section('title', 'Add FAQ - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Add new FAQ</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Create a new question and answer</p>
                    </div>
                    <a href="{{ route('admincp.faqs.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded transition-colors no-underline">
                        &larr; Back
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                    <form action="{{ route('admincp.faqs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="editor"
                            data-image-browse-url="{{ env('APP_URL') . '/' . explode('-', app()->getlocale())[0] . '/admincp/images/' }}">
                        </div>

                        <!-- Question -->
                        <div class="mb-5">
                            <label for="question" class="block text-sm font-medium text-slate-700 mb-1.5">Question *</label>
                            <textarea
                                class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all resize-y"
                                id="question" name="question" rows="2" placeholder="Enter question..." maxlength="1000" required></textarea>
                        </div>

                        <!-- Answer -->
                        <div class="mb-5">
                            <label for="answer" class="block text-sm font-medium text-slate-700 mb-1.5">Answer *</label>
                            <textarea class="w-full p-3 border border-slate-300 rounded" name="answer" placeholder="Enter answer..."
                                maxlength="1000" id="answer" required></textarea>
                        </div>

                        <!-- Topic -->
                        <div class="mb-6">
                            <label for="topic" class="block text-sm font-medium text-slate-700 mb-1.5">Topic / Additional information</label>
                            <textarea class="w-full p-3 border border-slate-300 rounded" name="topic" id="topic"></textarea>
                        </div>

                        <!-- Associations -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 mb-6 bg-slate-50/70 border border-slate-200/80 rounded">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 mb-3">Sections</h3>
                                <div class="max-h-56 overflow-y-auto space-y-2 pr-2">
                                    @forelse($sections as $section)
                                        <label class="flex items-center gap-3 text-sm text-slate-700 hover:text-slate-900 cursor-pointer select-none">
                                            <input
                                                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                                name="section[{{ $section->id }}]" value="1" type="checkbox">
                                            <span>{{ $section->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">No sections in this language</p>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 mb-3">Loan types</h3>
                                <div class="max-h-56 overflow-y-auto space-y-2 pr-2">
                                    @forelse($loanTypes as $loanType)
                                        <label class="flex items-center gap-3 text-sm text-slate-700 hover:text-slate-900 cursor-pointer select-none">
                                            <input
                                                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                                name="loanType[{{ $loanType->id }}]" value="1" type="checkbox">
                                            <span>{{ $loanType->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">No loan types in this language</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Active Toggle -->
                        <div class="mb-6">
                            <input type="hidden" name="active" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input
                                    class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                    name="active" type="checkbox" value="1" checked>
                                <span>Active</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('admincp.faqs.index') }}"
                                class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                Cancel
                            </a>
                            <button
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                type="submit">
                                Add FAQ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace('answer');
        CKEDITOR.replace('topic');
    </script>
@endsection
