<div class="overflow-hidden transition-all duration-200 transform bg-white rounded-lg"
    href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $position]) }}" target="_blank" rel="nofollow"
    title="{{ $lender->name }}">
    <div class="flex flex-col items-center justify-between w-full px-2 pb-4 bg-white md:pb-2 lg:flex-row">
        <div
            class="flex flex-col items-center justify-center max-w-full px-1 mt-1.5 w-36 h-[72px] sm:w-40 sm:h-20 lg:w-40 lg:h-20">
            <a class="flex items-center justify-center w-full h-full max-w-full max-h-full profit"
                href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $position]) }}"
                aria-label="{{ __('Take this offer') }} — {{ $lender->name }}"
                @if (isset($_GET['fbpixel'])) onclick="javascript:profit();" @endif target="_blank" rel="nofollow">
                <img class="object-contain w-full h-full max-w-full max-h-full" src="{{ $lender->image }}"
                    alt="{{ $lender->name . ' ' . __('logo') }}" title="{{ $lender->name . ' logo' }}" width="160"
                    height="80"
                    @if ($loop->first) loading="eager"
                        fetchpriority="high"
                        decoding="sync"
                    @else
                        loading="lazy"
                        fetchpriority="low"
                        decoding="async" @endif>
            </a>
        </div>
        <div class="flex-1 my-4 md:mx-4 lg:my-0">
            <ul class="grid grid-cols-1 gap-2 lg:grid-cols-3">
                <li class="flex flex-col font-normal text-center text-gray-500 text-md lg:text-xl">
                    @if ($loop->iteration <= 5)
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                        </div>
                    @else
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M239.2,97.4A16.4,16.4,0,0,0,224.6,86l-59.4-4.1-22-55.5A16.4,16.4,0,0,0,128,16h0a16.4,16.4,0,0,0-15.2,10.4L90.4,82.2,31.4,86A16.5,16.5,0,0,0,16.8,97.4,16.8,16.8,0,0,0,22,115.5l45.4,38.4L53.9,207a18.5,18.5,0,0,0,7,19.6,18,18,0,0,0,20.1.6l46.9-29.7h.2l50.5,31.9a16.1,16.1,0,0,0,8.7,2.6,16.5,16.5,0,0,0,15.8-20.8l-14.3-58.1L234,115.5A16.8,16.8,0,0,0,239.2,97.4Z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="#666666" viewBox="0 0 256 256"
                                aria-hidden="true" role="presentation">
                                <path
                                    d="M132.4,190.7l50.4,32c6.5,4.1,14.5-2,12.6-9.5l-14.6-57.4a8.7,8.7,0,0,1,2.9-8.8l45.2-37.7c5.9-4.9,2.9-14.8-4.8-15.3l-59-3.8a8.3,8.3,0,0,1-7.3-5.4l-22-55.4a8.3,8.3,0,0,0-15.6,0l-22,55.4a8.3,8.3,0,0,1-7.3,5.4L31.9,94c-7.7.5-10.7,10.4-4.8,15.3L72.3,147a8.7,8.7,0,0,1,2.9,8.8L61.7,209c-2.3,9,7.3,16.3,15,11.4l46.9-29.7A8.2,8.2,0,0,1,132.4,190.7Z"
                                    fill="none" stroke="#666666" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="16"></path>
                            </svg>
                        </div>
                    @endif
                </li>
                <li class="flex flex-col text-sm font-normal text-center text-gray-500 xl:text-base 2xl:text-lg">
                    {{-- <span class="text-gray-500 md:hidden">@lang('Sum')</span> --}}
                    <span class="font-medium ">
                        {{ number_format($lender->min_amount, 0, ' ', ' ') }}
                        -
                        <span
                            class="whitespace-nowrap text-primary">{{ number_format($lender->max_amount, 0, ' ', ' ') }}
                            @lang('€')</span>
                    </span>
                    {{-- <span class="hidden text-gray-500 md:block">@lang('Sum')</span> --}}
                </li>
                <li class="flex flex-col text-sm font-normal text-center text-gray-500 2xl:text-lg xl:text-base">
                    {{-- <span class="text-gray-500 md:hidden">@lang('Term')</span> --}}
                    <span class="font-medium">
                        <div>
                            @lang('from')
                            @if ($lender->min_term <= 61)
                                {{ $lender->min_term }} @lang('days2')
                            @elseif($lender->min_term < 360)
                                {{ round($lender->min_term / 30, 0) }} @lang('months')
                            @elseif($lender->min_term == 360)
                                {{ round($lender->min_term / 360, 0) }} @lang('year2')
                            @else
                                {{ round($lender->min_term / 360, 0) }} @lang('years')
                            @endif
                        </div>
                        <div>
                            @lang('up to')
                            @if ($lender->max_term == 360)
                                1 @lang('year')
                            @else
                                {{ round($lender->max_term / 360, 0) }} @lang('years')
                            @endif
                        </div>
                    </span>
                    {{-- <span class="hidden text-gray-500 md:block">@lang('Term')</span> --}}
                </li>
            </ul>
        </div>
        <div class="w-auto text-center xl:pr-4">
            <a class="block w-full px-5 py-2 text-sm font-medium text-center text-white transition-all duration-200 rounded-md xl:text-base 2xl:text-lg lg:px-10 lg:py-4 profit bg-primary-text hover:bg-opacity-80 hover:text-secondary-normal md:w-auto focus:shadow-outline"
                href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $position]) }}"
                aria-label="{{ __('Take this offer') }} — {{ $lender->name }}" target="_blank" rel="nofollow">
                @lang('Take this offer')
            </a>
        </div>
    </div>
    @if ($lender->data)
        <div class="px-2 pb-4 mt-2 text-sm text-center border-b-2 xl:mt-0 lg:mt-4 lg:text-left">
            {{ $lender->data->slogan . ', ' . __('from') . ' ' . $lender->min_years . ' ' . __('up to') . ' ' . $lender->max_years . ' ' . __('years') }}
        </div>
    @endif
</div>
