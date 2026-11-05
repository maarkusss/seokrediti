@if (
    $loanType->section->heading &&
        $loanType->section->first_step &&
        $loanType->section->second_step &&
        $loanType->section->third_step &&
        $loanType->section->fourth_step)
    <section class="mt-3.5 bg-gradient-to-b from-[#C2B3B0] to-[#5F555E] text-white -translate-y-[12rem]">
        <div class="max-w-screen-xl px-4 py-16 mx-auto md:px-4">
            <div class="flex items-center justify-center">
                <h2 class="pb-12 text-xl font-bold md:text-2xl">{{ $loanType->section->heading }}</h2>
            </div>
            <div class="flex items-center justify-center">
                <div class="grid grid-cols-1 gap-8 md:gap-4 md:grid-cols-4 sm:grid-cols-2">
                    <div
                        class="flex flex-col items-center justify-start prose prose-img:!w-24 prose-img:!h-24 prose-p:my-0 prose-p:leading-6 prose-strong:text-white prose-p:text-white prose-img:my-0 prose-h3:!my-0">
                        {!! $loanType->section->first_step !!}
                    </div>
                    <div
                        class="flex flex-col items-center justify-start prose prose-img:!w-24 prose-img:!h-24 prose-p:my-0 prose-p:leading-6 prose-strong:text-white prose-p:text-white prose-img:my-0 prose-h3:!my-0">
                        {!! $loanType->section->second_step !!}
                    </div>
                    <div
                        class="flex flex-col items-center justify-start prose prose-img:!w-24 prose-img:!h-24 prose-p:my-0 prose-p:leading-6 prose-strong:text-white prose-p:text-white prose-img:my-0 prose-h3:!my-0">
                        {!! $loanType->section->third_step !!}
                    </div>
                    <div
                        class="flex flex-col items-center justify-start prose prose-img:!w-24 prose-img:!h-24 prose-p:my-0 prose-p:leading-6 prose-strong:text-white prose-p:text-white prose-img:my-0 prose-h3:!my-0">
                        {!! $loanType->section->fourth_step !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
