@extends('layouts.app')

@section('title', 'Overview - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')
            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">
                            Overview
                            @if (!empty(config('app.server_name_goodday')))
                                <span class="text-sm font-normal text-slate-500">
                                    (Splitted server: {{ config('app.server_name_goodday') }})
                                </span>
                            @endif
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">Summary of today's statistics, commissions, and traffic</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5 w-full">
                    <div class="flex flex-col gap-4 lg:col-span-1" id="overview_statistics">
                        @include('admincp.includes.dashboard-stat', [
                            'title' => 'Commissions today',
                            'value' => $data['commissions']['today'],
                            'detail' => $data['commissions']['percentage'] . '%',
                            'detail_color' => $data['commissions']['color'],
                        ])

                        @include('admincp.includes.dashboard-stat', [
                            'title' => 'Clicks today',
                            'value' => $data['clicks']['today'],
                            'detail' => $data['clicks']['percentage'] . '%',
                            'detail_color' => $data['clicks']['color'],
                        ])

                        @include('admincp.includes.dashboard-stat', [
                            'title' => 'Visitors today',
                            'value' => $data['visitors']['today'],
                            'detail' => $data['visitors']['percentage'] . '%',
                            'detail_color' => $data['visitors']['color'],
                        ])
                    </div>
                    <div class="flex flex-col w-full min-h-[300px] p-5 bg-white rounded border border-slate-200/80 shadow-xs lg:col-span-2">
                        <div id="chart" class="w-full min-h-[280px]">
                        </div>
                    </div>
                </div>
                <div class="grid w-full grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    @can('view lenders')
                        <div class="flex flex-col">
                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Active Lenders',
                                'value' => $data['lenders']['active'] ?? '-',
                                'value_color' => 'text-gray-600',
                                'details_array' => [
                                    'total' => $data['lenders']['total'] ?? '-',
                                ],
                                'link' => $data['lenders']['link'] ?? '',
                            ])
                        </div>

                        <div class="flex flex-col">
                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Active Campaigns',
                                'value' => $data['lendercampaigns']['active'] ?? '-',
                                'value_color' => 'text-gray-600',
                                'details_array' => [
                                    'total' => $data['lendercampaigns']['total'] ?? '-',
                                ],
                                'link' => $data['lendercampaigns']['link'] ?? '',
                            ])
                        </div>

                        @can('view go-rewrites')
                            <div class="flex flex-col">
                                @include('admincp.includes.dashboard-stat', [
                                    'title' => 'Active Go Rewrites',
                                    'value' => $data['go_rewrites']['active'] ?? '-',
                                    'value_color' => 'text-gray-600',
                                    'details_array' => [
                                        'total' => $data['go_rewrites']['total'] ?? '-',
                                    ],
                                    'link' => $data['go_rewrites']['link'] ?? '',
                                ])
                            </div>
                        @endcan

                        <div class="flex flex-col">
                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Active Lender Filters',
                                'value' => $data['lenderfilters']['active'] ?? '-',
                                'value_color' => 'text-gray-600',
                                'details_array' => [
                                    'total' => $data['lenderfilters']['total'] ?? '-',
                                ],
                                'link' => $data['lenderfilters']['link'] ?? '',
                            ])
                        </div>
                    @endcan

                    @can('view loantypes')
                        <div class="flex flex-col">
                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Active Loantypes',
                                'value' => $data['loantypes']['active'] ?? '-',
                                'value_color' => 'text-gray-600',
                                'details_array' => [
                                    'total' => $data['loantypes']['total'] ?? '-',
                                ],
                                'link' => $data['loantypes']['link'] ?? '',
                            ])
                        </div>
                    @endcan

                    @can('access postbacks')
                        <div class="flex flex-col">
                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Active Postbacks',
                                'value' => $data['postbacks']['active'] ?? '-',
                                'value_color' => 'text-gray-600',
                                'details_array' => [
                                    'enabled' => config('postbacks.enabled') ? 'yes' : 'no',
                                    'total' => $data['postbacks']['total'] ?? '-',
                                ],
                                'link' => $data['postbacks']['link'] ?? '',
                            ])
                        </div>
                    @endcan

                    {{-- @can('view consumers')
                        <div class="flex flex-col px-2">
                            <div class="flex items-center justify-between flex-1 p-4 mb-3 leading-none bg-white rounded">
                                <div class="relative flex flex-col">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="mb-3 w-7 h-7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>

                                    @if (config('landing_form.require_phone'))
                                        <span class="absolute top-0 text-gray-500 left-8 text-xxs">Collected</span>
                                    @else
                                        <span class="absolute top-0 text-orange-400 left-8 text-xxs">Not collected</span>
                                    @endif

                                    <span class="font-normal text-gray-500 text-normal">Consumer phones</span>
                                </div>
                                <div class="h-full text-right">
                                    <p class="mb-1">
                                        @if (config('app.verify_phone'))
                                            <span class="px-1 py-0 mr-0 badge badge-success">Verified in WEB</span>
                                        @else
                                            <span class="px-1 py-0 mr-0 badge badge-warning">Not verified in WEB</span>
                                        @endif
                                    </p>
                                    <p class="mb-1">
                                        @if (config('app.verify_phone_in_api'))
                                            <span class="px-1 py-0 mr-0 badge badge-success">Verified in API</span>
                                        @else
                                            <span class="px-1 py-0 mr-0 badge badge-warning">Not verified in API</span>
                                        @endif
                                    </p>

                                    <p class="mb-0">
                                        <span
                                            class="badge mr-0 px-1 {{ config('hoi_fm.export_phone_numbers') ? '' : 'badge-warning' }}">
                                            <a href="https://hoi.fm" target="_blank">
                                                <img src="/images/hoi.png" title="Hoi.fm" alt="hoi.fm"
                                                    style="display: inline; width: 10px; margin-bottom: 1px; filter: invert(.8)" />
                                            </a>
                                            Hoi export: {{ config('hoi_fm.export_phone_numbers') ?: 'none' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col px-2">
                            <div class="flex items-center justify-between flex-1 p-4 mb-3 leading-none bg-white rounded">
                                <div class="relative flex flex-col">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="mb-3 w-7 h-7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>

                                    @if (config('landing_form.require_email'))
                                        <span class="absolute top-0 text-gray-500 left-9 text-xxs">Collected</span>
                                    @else
                                        <span class="absolute top-0 text-orange-400 left-9 text-xxs">Not collected</span>
                                    @endif

                                    <span class="font-normal text-gray-500 text-normal">Consumer emails</span>
                                </div>
                                <div class="h-full text-right">
                                    <p class="mb-2">
                                        @if (config('app.verify_email'))
                                            <span class="px-1 py-0 mr-0 badge badge-success">Verified</span>
                                        @else
                                            <span class="px-1 py-0 mr-0 badge badge-warning">Not verified</span>
                                        @endif
                                    </p>

                                    <p class="mb-0">
                                        <span
                                            class="badge mr-0 px-1 {{ config('hoi_fm.export_emails') ? '' : 'badge-warning' }}">
                                            <a href="https://hoi.fm" target="_blank">
                                                <img src="/images/hoi.png" title="Hoi.fm" alt="hoi.fm"
                                                    style="display: inline; width: 10px; margin-bottom: 1px; filter: invert(.8)" />
                                            </a>
                                            Hoi export: {{ config('hoi_fm.export_emails') ?: 'none' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endcan --}}

                    <div class="flex flex-col">
                        @include('admincp.includes.dashboard-stat', [
                            'value' => 'Goodwall',
                            'title' => config('goodwall.enabled') ? ' enabled' : ' disabled',
                            'value_color' => 'text-white',
                            'bg_color' => config('goodwall.enabled')
                                ? ' bg-[#60223b] bg-gradient-to-r from-[#60223b] to-[#b11850]'
                                : ' bg-gray-400',
                            'title_color' => 'text-white',
                        ])
                    </div>

                    @if (!app()->isProduction())
                        <div class="flex flex-col">
                            @include('admincp.includes.dashboard-stat', [
                                'value' => 'Outline VPN',
                                'title' =>
                                    config('goodwall.enabled') && !app()->isProduction()
                                        ? ' restricted frontend'
                                        : ' disabled',
                                'value_color' => 'text-white',
                                'bg_color' =>
                                    config('goodwall.enabled') && !app()->isProduction()
                                        ? ' bg-[#1e493a] bg-gradient-to-r from-[#1e493a] to-[#778d84]'
                                        : ' bg-gray-400',
                                'title_color' => 'text-white',
                                'detail_container_class' => 'leading-none text-xs',
                                'detail_color' => 'text-white',
                            ])
                        </div>
                    @endif

                    {{-- @can('viewInternals')
                        <div class="flex flex-col px-2">
                            @include('admincp.includes.dashboard-stat', [
                                'value' => 'Hoi.fm',
                                'title' => config('services.hoi_fm.user_token') ? ' connected' : ' not connected',
                                'value_color' => 'text-white',
                                'bg_color' => config('services.hoi_fm.user_token')
                                    ? ' bg-[#6071e4] bg-gradient-to-r from-[#6071e4] to-[#815fe4]'
                                    : ' bg-gray-400',
                                'title_color' => 'text-white',
                                'detail_container_class' => 'leading-none font-mono',
                                'detail_color' => 'text-teal-900',
                                'details_array' => [
                                    'email' => config('services.hoi_fm.email_project_token') ? 'YES' : 'not',
                                    'push' => config('services.hoi_fm.push_project_token') ? 'YES' : 'not',
                                    'sms' => config('services.hoi_fm.sms_project_token') ? 'YES' : 'not',
                                ],
                            ])
                        </div>

                        <div class="flex flex-col px-2">
                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Environment',
                                'value' => ucfirst(
                                    strlen(config('app.env')) < 6
                                        ? config('app.env')
                                        : substr(config('app.env'), 0, 4)),
                                'value_color' => 'text-gray-600',
                                'detail_container_class' => 'leading-none',
                                'details_array' => [
                                    'sms gateway' => sms_gateway_name(),
                                    'sms alpha name' => config('app.sms_alpha_name'),
                                    'offer page link' => offer_page_link_strategy(),
                                    'currency' => currency_symbol(),
                                    'ver.' => app()->version() . ' | ' . PHP_VERSION,
                                ],
                            ])
                        </div>

                        <div class="flex flex-col px-2">
                            @php
                                $debug_links = [];
                                $debug_links[''] =
                                    '<a href="/en/log-viewer" target="_blank" class="px-1 mr-0 badge">Log Viewer</a>';
                                $debug_links[' '] =
                                    '<a href="/telescope" target="_blank" class="px-1 mr-0 badge">Telescope</a>';
                            @endphp

                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Debug tools',
                                'value' => 'Debug',
                                'value_color' => 'text-gray-600',
                                'details_array' => $debug_links,
                            ])
                        </div>

                        <div class="flex flex-col px-2">
                            @php
                                $offer_link_message = new \App\Services\Sms\Messages\OfferLinkMessage('123456');
                            @endphp

                            @include('admincp.includes.dashboard-stat', [
                                'title' => 'Offer Sms',
                                'value' => '',
                                'details_array' => [
                                    'sender' => $offer_link_message->sender,
                                    'message' => $offer_link_message->message,
                                ],
                            ])
                        </div>
                    @endcan --}}
                </div>
                {{-- <div class="w-full p-4 rounded bg-zinc-100">
                    <div class="grid grid-cols-3 pb-2 font-medium">
                        <div>
                            User
                        </div>
                        <div>
                            Log
                        </div>
                        <div class="text-right">
                            Created at
                        </div>
                    </div>
                    @foreach ($adminlogs as $adminlog)
                        <div class="grid grid-cols-3 py-1.5 border-t text-gray-500 text-sm">
                            <div>
                                @foreach ($users as $user)
                                    @if ($adminlog->user_id == $user->id)
                                        {{ $user->first_name . ' ' . $user->last_name }}
                                    @endif
                                @endforeach
                            </div>
                            <div>
                                {{ $adminlog->log }}
                            </div>
                            <div class="text-right">
                                {{ $adminlog->created_at }}
                            </div>
                        </div>
                    @endforeach

                </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        const activeSection = document.getElementById("sidebar-overview");
        if (activeSection) {
            activeSection.classList.add("text-stone-900", "bg-primary-normal/40", "font-semibold");
        }

        const statsEl = document.querySelector("#overview_statistics");
        let chartHeight = statsEl && statsEl.clientHeight > 100 ? statsEl.clientHeight : 300;

        window.options = {
            chart: {
                type: "area",
                fontFamily: "inherit",
                toolbar: {
                    show: false
                },
                height: chartHeight,
                maintainAspectRatio: false,
                responsive: true,
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
                    data: [{!! implode(', ', $chart_data['commissions'] ?? []) !!}]
                },
                {
                    name: "Clicks",
                    data: [{!! implode(', ', $chart_data['clicks'] ?? []) !!}]
                },
                {
                    name: "Visitors",
                    data: [{!! implode(', ', $chart_data['visitors'] ?? []) !!}]
                },
            ],
            xaxis: {
                categories: ["{!! implode('", "', array_keys($chart_data['clicks'] ?? [])) !!}"],
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

        function renderDashboardChart() {
            const chartEl = document.getElementById("chart");
            if (!chartEl || !window.options) {
                return;
            }

            // Prioritize window.ApexCharts if loaded
            if (typeof window.ApexCharts !== 'undefined') {
                if (!chartEl.hasChildNodes()) {
                    const chart = new window.ApexCharts(chartEl, window.options);
                    chart.render();
                }
            } else if (!document.getElementById("apexcharts-cdn-script")) {
                // Fallback to load ApexCharts directly if Vite bundle hasn't finished or failed
                const script = document.createElement("script");
                script.id = "apexcharts-cdn-script";
                script.src = "https://cdn.jsdelivr.net/npm/apexcharts";
                script.onload = () => {
                    if (chartEl && !chartEl.hasChildNodes()) {
                        const chart = new window.ApexCharts(chartEl, window.options);
                        chart.render();
                    }
                };
                document.head.appendChild(script);
            }
        }

        renderDashboardChart();
        document.addEventListener("DOMContentLoaded", renderDashboardChart);
        setTimeout(renderDashboardChart, 50);
        setTimeout(renderDashboardChart, 150);
        setTimeout(renderDashboardChart, 300);
        setTimeout(renderDashboardChart, 600);
        setTimeout(renderDashboardChart, 1000);
    </script>
@endsection
