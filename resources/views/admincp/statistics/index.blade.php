@extends('layouts.app')

@section('title', 'Statistics - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Info box -->
        @include('admincp.components.infobox')

        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')

            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Header card with date range filter -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Statistics & Analytics</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Clicks, conversions, commissions, and EPC per lender</p>
                    </div>

                    <form class="flex items-center gap-2.5 w-full sm:w-auto" method="GET">
                        <div class="relative w-full sm:w-auto flex items-center">
                            <span class="absolute left-3.5 z-10 pointer-events-none text-slate-400 flex items-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input class="w-full sm:w-72 !pl-10 !pr-4 !py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors cursor-pointer"
                                name="datePicker"
                                type="text"
                                placeholder="Select date range..."
                                id="datepickr-element"
                                value="{{ request()->datePicker }}"
                                readonly>
                        </div>
                        <button class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 transition-colors whitespace-nowrap cursor-pointer"
                            type="submit">
                            Apply
                        </button>
                        @if (request()->datePicker)
                            <a href="{{ route('admincp.statistics.index') }}"
                                class="inline-flex items-center px-3.5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded transition-colors whitespace-nowrap"
                                title="Clear filter">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <!-- ApexCharts Card -->
                <div class="w-full p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Performance Over Time</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Commissions, clicks, and visitors breakdown</p>
                    </div>
                    <div id="chart" class="w-full"></div>
                </div>

                <!-- Lenders EPC Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden mb-6">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Lender Conversion Breakdown</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 text-left">Lender</th>
                                    <th scope="col" class="px-5 py-3.5 text-center">Confirmed</th>
                                    <th scope="col" class="px-5 py-3.5 text-center">Waiting</th>
                                    <th scope="col" class="px-5 py-3.5 text-center">Clicks</th>
                                    <th scope="col" class="px-5 py-3.5 text-center">EPC (€)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($lenders as $lender)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3.5 font-semibold text-slate-800">
                                            {{ $lender['name'] }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-mono {{ (float) $lender['confirmed'] > 0 ? 'font-bold text-emerald-600' : 'text-slate-400 font-normal' }}">
                                            €{{ number_format((float) $lender['confirmed'], 2) }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-slate-600">
                                            {{ $lender['waiting'] }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-mono text-slate-700">
                                            {{ $lender['clicks'] }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-bold" style="{{ $lender['style'] }}">
                                            €{{ sprintf('%.2f', $lender['epc']) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No lender data available for this date period.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        window.options = {
            chart: {
                type: "area",
                fontFamily: "inherit",
                toolbar: {
                    show: false
                },
                height: 360
            },
            colors: ['#10b981', '#2563eb', '#8b5cf6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                    name: "Commissions",
                    data: [{!! implode(', ', $chart_data['commissions']) !!}]
                },
                {
                    name: "Clicks",
                    data: [{!! implode(', ', $chart_data['clicks']) !!}]
                },
                {
                    name: "Visitors",
                    data: [{!! implode(', ', $chart_data['visitors']) !!}]
                },
            ],
            xaxis: {
                categories: ["{!! implode('", "', array_keys($chart_data['clicks'])) !!}"],
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '11px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            }
        };

        function setupStatisticsPage() {
            const chartEl = document.getElementById("chart");
            if (chartEl && typeof window.ApexCharts !== 'undefined' && !chartEl.hasChildNodes()) {
                const chart = new window.ApexCharts(chartEl, window.options);
                chart.render();
            }

            const element = document.getElementById("datepickr-element");
            if (element && typeof window.flatpickr !== 'undefined' && !element._flatpickr) {
                window.flatpickr(element, {
                    dateFormat: "Y-m-d",
                    mode: "range",
                    allowInput: false
                });
            }
        }

        setupStatisticsPage();
        document.addEventListener("DOMContentLoaded", setupStatisticsPage);
        setTimeout(setupStatisticsPage, 50);
        setTimeout(setupStatisticsPage, 200);
        setTimeout(setupStatisticsPage, 500);
    </script>
@endsection
