@extends('layouts.app')

@section('title', 'Sections - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <!-- Info box -->
        @include('admincp.components.infobox')
        <!-- End info box -->
        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')

            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Sections</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Section order, parameters and sub-sections</p>
                    </div>
                    @can('add sections')
                        <a href="{{ route('admincp.sections.add.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add section</span>
                        </a>
                    @endcan
                </div>

                <!-- Table Card with order form -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <form method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="w-full table-fixed min-w-[760px] divide-y divide-slate-200 text-left">
                                <colgroup>
                                    <col class="w-24">
                                    <col>
                                    <col class="w-48">
                                    <col class="w-48">
                                    <col class="w-24">
                                </colgroup>
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="w-24 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Order</th>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Name</th>
                                        <th scope="col" class="w-48 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Last updated</th>
                                        <th scope="col" class="w-48 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Created at</th>
                                        <th scope="col" class="w-24 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Active</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($sections as $section)
                                        @php
                                            $subsections = \App\Models\Admincp\Sections\Sections::where('parent_section_id', $section->id)->get();
                                            $hasSubsections = $subsections->count() > 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="w-24 px-4 py-3 text-center">
                                                <input name="section[order][{{ $section->id }}]" type="number"
                                                    value="{{ $section->order }}"
                                                    class="w-16 px-2 py-1 text-center font-mono text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs inline-block">
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-medium truncate">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admincp.sections.edit.index', ['section_id' => $section->id]) }}"
                                                        class="text-slate-800 hover:text-blue-600 font-semibold no-underline hover:underline transition-colors truncate">
                                                        {{ $section->name }}
                                                    </a>
                                                    @if ($hasSubsections)
                                                        <button type="button"
                                                            onclick="openSubsections('subsections_{{ $section->id }}', this);"
                                                            class="p-1 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors cursor-pointer shrink-0"
                                                            title="Show sub sections">
                                                            <svg class="w-4 h-4 fill-current transform transition-transform duration-200" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="w-48 px-5 py-3.5 text-sm text-slate-500 whitespace-nowrap">
                                                {{ $section->updated_at ? date('d.m.Y H:i', strtotime($section->updated_at)) : '—' }}
                                            </td>
                                            <td class="w-48 px-5 py-3.5 text-sm text-slate-500 whitespace-nowrap">
                                                {{ $section->created_at ? date('d.m.Y H:i', strtotime($section->created_at)) : '—' }}
                                            </td>
                                            <td class="w-24 px-4 py-3 text-center">
                                                <input type="hidden" name="section[active][{{ $section->id }}]" value="0">
                                                <input name="section[active][{{ $section->id }}]" type="checkbox"
                                                    value="1" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer"
                                                    @if ($section->active) checked @endif>
                                            </td>
                                        </tr>

                                        @if ($hasSubsections)
                                            @foreach ($subsections as $subsection)
                                                <tr class="hidden text-sm bg-slate-50/70 border-b border-slate-100 hover:bg-slate-100/70 transition-colors"
                                                    id="subsections_{{ $section->id }}">
                                                    <td class="w-24 px-4 py-2.5 text-center">
                                                        <input name="subsection[order][{{ $subsection->id }}]"
                                                            type="number" value="{{ $subsection->order }}"
                                                            class="w-14 px-2 py-0.5 text-center font-mono text-xs text-slate-800 bg-white border border-slate-300 rounded focus:border-blue-600 inline-block">
                                                    </td>
                                                    <td class="px-5 py-2.5 text-sm pl-10 truncate">
                                                        <div class="flex items-center gap-1.5 text-slate-600">
                                                            <span class="text-slate-400">&rdsh;</span>
                                                            <a href="{{ route('admincp.sections.edit.index', ['section_id' => $subsection->id]) }}"
                                                                class="hover:text-blue-600 no-underline hover:underline truncate">
                                                                {{ $subsection->name }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="w-48 px-5 py-2.5 text-xs text-slate-500 whitespace-nowrap">
                                                        {{ $subsection->updated_at ? date('d.m.Y H:i', strtotime($subsection->updated_at)) : '—' }}
                                                    </td>
                                                    <td class="w-48 px-5 py-2.5 text-xs text-slate-500 whitespace-nowrap">
                                                        {{ $subsection->created_at ? date('d.m.Y H:i', strtotime($subsection->created_at)) : '—' }}
                                                    </td>
                                                    <td class="w-24 px-4 py-2.5 text-center">
                                                        <input type="hidden" name="subsection[active][{{ $subsection->id }}]" value="0">
                                                        <input name="subsection[active][{{ $subsection->id }}]"
                                                            value="1" type="checkbox"
                                                            @if ($subsection->active) checked @endif
                                                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500">
                                                No sections found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @can('edit sections')
                            @if(count($sections) > 0)
                                <div class="flex items-center justify-end px-5 py-3 bg-slate-50/50 border-t border-slate-100">
                                    <button class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer" type="submit">
                                        Save order and status
                                    </button>
                                </div>
                            @endif
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function openSubsections(id, btn) {
            const subSections = document.querySelectorAll("#" + id);
            for (let i = 0; i < subSections.length; i++) {
                subSections[i].classList.toggle("hidden");
            }
            if (btn) {
                const svg = btn.querySelector("svg");
                if (svg) {
                    svg.classList.toggle("rotate-180");
                }
            }
        }
    </script>
@endsection
