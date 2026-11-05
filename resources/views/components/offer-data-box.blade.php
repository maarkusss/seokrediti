<div class="p-5 bg-white rounded-[5px]">
    <div class="p-[5px] odd:bg-gray-50 border-t">
        {{-- <a class="text-sm font-bold text-center"
            href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $position]) }}" target="_blank"
            rel="nofollow" title="{{ $lender->name }}">
            {{ $lender->data->company_name ? $lender->data->company_name : '-' }}
        </a> --}}
    </div>
    <div class="p-[5px] odd:bg-gray-50 border-t">
        <p class="text-sm text-center">
            @lang('Address'): {{ $lender->data->address ? $lender->data->address : '-' }}
        </p>
    </div>
    <div class="p-[5px] odd:bg-gray-50 border-t">
        <p class="text-sm text-center">
            @lang('Phone number'): {{ $lender->data->phone ? $lender->data->phone : '-' }}
        </p>
    </div>
    <div class="p-[5px] odd:bg-gray-50 border-t">
        <p class="text-sm text-center">
            @lang('E-mail'): {{ $lender->data->email ? $lender->data->email : '-' }}
        </p>
    </div>
    <div class="p-[5px] odd:bg-gray-50 border-t">
        <p class="text-sm text-center">
            @lang('Max GPL'): {{ $lender->data->max_apr ? $lender->data->max_apr : '-' }}
        </p>
    </div>
    <div class="p-[5px] odd:bg-gray-50 border-t">
        <p class="text-sm text-center">
            @lang('Term'):
            @if ($lender->min_term == $lender->max_term)
                @if (is_numeric($lender->max_term))
                    {{ round($lender->max_term / 30, 0) }} @lang('months')
                @else
                    {{ $lender->max_term }}
                @endif
            @else
                @lang('from')
                @if (is_numeric($lender->min_term))
                    @if ($lender->min_term <= 62)
                        62 @lang('days')
                    @elseif($lender->min_term < 360)
                        {{ round($lender->min_term / 30, 0) }} @lang('months')
                    @elseif($lender->min_term == 360)
                        {{ round($lender->min_term / 360, 0) }} @lang('year')
                    @else
                        {{ round($lender->min_term / 360, 0) }} @lang('years')
                    @endif
                @else
                    {{ $lender->min_term }}
                @endif
                @lang('up to')
                @if (is_numeric($lender->max_term))
                    @if ($lender->max_term <= 360)
                        1 @lang('year')
                    @else
                        {{ round($lender->max_term / 360, 0) }} @lang('years')
                    @endif
                @else
                    {{ $lender->max_term }}
                @endif
            @endif
            {{-- 62 días - 12 año --}}
        </p>
    </div>
    @if ($lender->data->apr_example)
        <div class="p-[5px] odd:bg-gray-50 border-t">
            <p class="text-sm text-center">
                {{ $lender->data->apr_example }}
            </p>
        </div>
    @endif
</div>
