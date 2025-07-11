<style>
    /* CSS Code */
    .swiper-wrapper {
        width: 100%;
        height: max-content !important;
        padding-bottom: 64px !important;
        -webkit-transition-timing-function: linear !important;
        transition-timing-function: linear !important;
        position: relative;
    }

    .swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
        background: #4F46E5 !important;
    }
</style>
<style>
    .swiper-wrapper {
        width: 100%;
        height: max-content !important;
        padding-bottom: 40px !important;
        transition-timing-function: ease-in-out !important;
    }

    .swiper-pagination-progressbar {
        background: #e5e7eb !important;
        height: 3px !important;
        border-radius: 0 !important;
    }

    .dark .swiper-pagination-progressbar {
        background: #374151 !important;
    }

    .swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
        background: #2563eb !important;
        border-radius: 0 !important;
    }
</style>

<!--HTML CODE-->



<div class="w-full relative py-12 px-6 dark:from-gray-900 bg-gradient-to-tl dark:via-gray-900 dark:to-gray-800">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Medical Inventory Solutions
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Streamline your healthcare operations with comprehensive inventory management
        </p>
    </div>

    <!-- Carousel -->
    <div class="swiper progress-slide-carousel max-w-3xl mx-auto">
        <div class="swiper-wrapper">

            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 h-full shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-6 flex items-center justify-center">
                        <img class="object-cover h-[350px]" src="{{ url('savedollars.jpg') }}" alt="" draggable="false">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white p-3">
                        Streamline Vendor Data Management
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed p-3">
                        Our inventory tracking software assists in seamless vendor information management, simplifying
                        the reordering and replenishment process for medical supply organizations.
                    </p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 h-full shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-6 flex items-center justify-center">
                        <img class="object-cover h-[350px]" src="{{ url('newbarcode.png') }}" alt="" draggable="false">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white  p-3">
                        Barcode Scanning for Clinic Supplies
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed  p-3">
                        Revolutionize your medical supply management with our barcode scanning solution tailored for
                        healthcare. Seamlessly integrate cutting-edge barcode technology.
                    </p>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="swiper-slide">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 h-full shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-6 flex items-center justify-center">
                        <img class="object-cover h-[350px]"  src="{{ url('wearing-protective-clothing.jpg') }}" alt="" draggable="false">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white  p-3">
                        Streamlined Storage Allocation
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed  p-3">
                        Enhance the efficiency of storage allocation within the warehouse. Get insights into item
                        dimensions, storage capacity, and demand patterns.
                    </p>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="swiper-slide">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 h-full shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-6 flex items-center justify-center">
                        <img class="object-cover h-[350px]"  src="{{ url('paperwork.jpg') }}" alt="" draggable="false">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white  p-3">
                        Customized Reporting
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed  p-3">
                        Generate customized reports on inventory usage, costs, and trends. These reports provide
                        valuable insights for strategic decision-making.
                    </p>
                </div>
            </div>

            <!-- Slide 5 -->
            <div class="swiper-slide">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 h-full shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-6 flex items-center justify-center">
                        <img class="object-cover h-[350px]"  class="object-cover" src="{{ url('two-pharmacists.jpg') }}" alt="" draggable="false">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white  p-3">
                        Optimized Storage Space
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed  p-3">
                        Our medical inventory management software enhances storage allocation efficiency, guaranteeing
                        convenient access and meticulous organization.
                    </p>
                </div>
            </div>

            <!-- Slide 6 -->
            <div class="swiper-slide">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 h-full shadow-sm hover:shadow-md transition-shadow">
                    <div
                        class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-6 flex items-center justify-center">
                        <img class="object-cover h-[350px]"  src="{{ url('stock.jpeg') }}" alt="" draggable="false">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white  p-3">
                        Real-Time Inventory View
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed  p-3">
                        In the fast-paced healthcare landscape, having real-time insights into medical inventory is
                        crucial for staying proactive and responsive.
                    </p>
                </div>
            </div>

        </div>

        <!-- Progress Bar -->
        <div class="swiper-pagination !bottom-2 !top-auto !w-80 right-0 mx-auto bg-gray-100"></div>
    </div>
</div>
</div>
</div>

<script>
    var swiper = new Swiper(".progress-slide-carousel", {
        loop: true,
        fraction: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".progress-slide-carousel .swiper-pagination",
            type: "progressbar",
        },
    });
</script>