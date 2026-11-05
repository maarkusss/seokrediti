@if (
    $loanType->section->second_heading &&
        $loanType->section->second_first_step &&
        $loanType->section->second_second_step &&
        $loanType->section->second_third_step &&
        $loanType->section->second_fourth_step &&
        $loanType->section->second_first_image &&
        $loanType->section->second_second_image &&
        $loanType->section->second_third_image &&
        $loanType->section->second_fourth_image)
    <section class="text-gray-800 bg-gradient-to-b -translate-y-[12rem] bg-white">
        <div class="max-w-screen-xl px-4 py-16 mx-auto md:px-4">
            <div class="flex items-center justify-center">
                <h2 class="pb-12 text-xl font-bold md:text-2xl">{{ $loanType->section->second_heading }}</h2>
            </div>
            <div class="flex items-center justify-center">
                <div class="grid grid-cols-1 md:gap-4 md:grid-cols-2">
                    <div class="flex flex-col items-start justify-start order-2 md:order-1">
                        <div class="flex w-full rounded-lg md:bg-neutral-100 text" data-target="image1">
                            <div class="w-1 rounded-l-lg bg-white md:bg-[#5F555E] md:pr-1.5"></div>
                            <div class="flex flex-col w-full p-4 max-md:text-center">
                                {!! $loanType->section->second_first_step !!}
                            </div>
                        </div>
                        <div class="flex w-full rounded-lg cursor-pointer max-md:hidden text" data-target="image2">
                            <div class="w-1 rounded-l-lg bg-white md:pr-1.5"></div>
                            <div class="flex flex-col w-full p-4 max-md:text-center">
                                {!! $loanType->section->second_second_step !!}
                            </div>
                        </div>
                        <div class="flex w-full rounded-lg max-md:hidden text" data-target="image3">
                            <div class="w-1 rounded-l-lg bg-white md:pr-1.5"></div>
                            <div class="flex flex-col w-full p-4 max-md:text-center">
                                {!! $loanType->section->second_third_step !!}
                            </div>
                        </div>
                        <div class="flex w-full rounded-lg max-md:hidden text" data-target="image4">
                            <div class="w-1 rounded-l-lg bg-white md:pr-1.5"></div>
                            <div class="flex flex-col w-full p-4 max-md:text-center">
                                {!! $loanType->section->second_fourth_step !!}
                            </div>
                        </div>
                        <div class="flex justify-center w-full md:hidden">
                            <div class="w-4 h-4 rounded-full bg-primary-normal cursor-pointer m-[10px] transition-colors"
                                data-id="image1"></div>
                            <div class="w-4 h-4 rounded-full bg-primary-normal opacity-40 cursor-pointer m-[10px] transition-colors"
                                data-id="image2"></div>
                            <div class="w-4 h-4 rounded-full bg-primary-normal opacity-40 cursor-pointer m-[10px] transition-colors"
                                data-id="image3"></div>
                            <div class="w-4 h-4 rounded-full bg-primary-normal opacity-40 cursor-pointer m-[10px] transition-colors"
                                data-id="image4"></div>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="flex flex-col items-center justify-start prose prose-img:!w-[350px] prose-img:!h-full prose-img:my-0"
                            id="image1">
                            {!! $loanType->section->second_first_image !!}
                        </div>
                        <div class="flex flex-col items-center justify-start prose prose-img:!w-[350px] prose-img:!h-full prose-img:my-0 hidden"
                            id="image2">
                            {!! $loanType->section->second_second_image !!}
                        </div>
                        <div class="flex flex-col items-center justify-start prose prose-img:!w-[350px] prose-img:!h-full prose-img:my-0 hidden"
                            id="image3">
                            {!! $loanType->section->second_third_image !!}
                        </div>
                        <div class="flex flex-col items-center justify-start prose prose-img:!w-[350px] prose-img:!h-full prose-img:my-0 hidden"
                            id="image4">
                            {!! $loanType->section->second_fourth_image !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const textElements = document.querySelectorAll('.text');
        const circles = document.querySelectorAll('[data-id^="image"]');
        const imageElements = document.querySelectorAll('[id^="image"]');
        let touchStartX = 0;

        textElements.forEach((textElement, index) => {
            textElement.addEventListener('touchstart', (event) => handleTouchStart(event));
            textElement.addEventListener('touchmove', (event) => handleTouchMove(event, textElement, index));
            addClickListener(textElement, "text", "data-target");
        });

        imageElements.forEach((imageElement, index) => {
            imageElement.addEventListener('touchstart', (event) => handleTouchStart(event));
            imageElement.addEventListener('touchmove', (event) => handleTouchMove(event, imageElement, index));
        });

        circles.forEach((circle) => addClickListener(circle, "circle", "data-id"));

        function handleTouchStart(event) {
            touchStartX = event.touches[0].clientX;
        }

        function handleTouchMove(event, element, index) {
            const touchEndX = event.changedTouches[0].clientX;
            const swipeThreshold = 50;

            const deltaX = touchEndX - touchStartX;

            if (deltaX > swipeThreshold) {
                navigateToElement(index - 1, element);
            } else if (deltaX < -swipeThreshold) {
                navigateToElement(index + 1, element);
            }
        }

        function navigateToElement(targetIndex, element) {
            const elements = element.tagName === 'IMG' ? imageElements : textElements;
            const numElements = elements.length;
            const newIndex = (targetIndex + numElements) % numElements;
            const targetElement = imageElements[newIndex];
            const circle = circles[newIndex];
            const text = textElements[newIndex];
            updateElementsStyles(circle, text, targetElement);
        }

        function addClickListener(element, elementType, idElement) {
            element.addEventListener('click', function() {
                const targetImageId = element.getAttribute(idElement);
                const targetImage = document.getElementById(targetImageId);
                const circle = elementType === "text" ? document.querySelector('[data-id="' + targetImageId +
                    '"]') : element;
                const text = elementType === "text" ? element : document.querySelector('[data-target="' +
                    targetImageId + '"]');

                updateElementsStyles(circle, text, targetImage);
            });
        }

        function updateElementsStyles(circle, text, targetImage) {
            hideAllImages();
            removeAllActiveClasses();

            circle.classList.remove('opacity-40');
            text.classList.add('md:bg-neutral-100');
            text.classList.remove('max-md:hidden', 'cursor-pointer');
            text.firstElementChild.classList.add('md:bg-[#5F555E]');

            if (targetImage) {
                targetImage.classList.remove('hidden');
            }
        }

        function hideAllImages() {
            imageElements.forEach((imageElement) => imageElement.classList.add('hidden'));
        }

        function removeAllActiveClasses() {
            textElements.forEach((textElement) => {
                textElement.classList.remove('md:bg-neutral-100');
                textElement.firstElementChild.classList.remove('md:bg-[#5F555E]');
                textElement.classList.add('max-md:hidden', 'cursor-pointer');
            });

            circles.forEach((circle) => circle.classList.add('opacity-40'));
        }
    </script>
@endif
