 <!-- Enhanced App Demo Video Section -->
    <div
        class="relative py-20 px-4 bg-gradient-to-br from-amber-50 via-white to-amber-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
        <!-- Background Decorative Elements -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-10 left-10 w-32 h-32 bg-amber-300/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-sky-300/20 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-amber-500/10 rounded-full blur-3xl">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- App Demo Content - Left Side -->
                <div class="space-y-8">
                    <!-- Section Header -->
                    <div class="space-y-4">
                        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                            See It In <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">Action</span>
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 font-medium">
                            Procurement and managing supplies made simple
                        </p>
                    </div>

                    <!-- App Demo Content -->
                    <div class="space-y-6">
                        <p class="text-md text-gray-600 dark:text-gray-300 leading-relaxed">
                            Watch how our intuitive software transforms the way you procure, track, manage and optimize your
                            supplies. obtain real-time updates to automated alerts, see how easy it is to stay in control.
                        </p>
                        <p class="text-md text-gray-600 dark:text-gray-300 leading-relaxed">
                            Discover powerful features designed to connect to different suppliers, eliminate reorder hassle,
                            track and streamline your entire inventory workflow. Every click is purposeful, every feature is
                            essential.
                        </p>
                    </div>

                    <!-- Call to Action -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ url('contact') }}"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-amber-300 to-amber-500 hover:from-amber-300 hover:to-amber-300 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                            <span>Let's Solve Your Inventory Challenges</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Video Container - Right Side -->
                <div class="relative group">
                    <!-- Gradient Border Effect -->
                    <div
                        class="absolute -inset-2 bg-gradient-to-r from-amber-300 via-sky-300 to-amber-500 rounded-3xl blur opacity-30 group-hover:opacity-50 transition-all duration-700">
                    </div>

                    <!-- Main Video Wrapper -->
                    <div class="relative bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-2xl">
                        <!-- Video Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-gradient-to-r from-amber-300 to-amber-500 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Clinic automation is our
                                        priority</h3>
                                    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Step-by-step guide</p> --}}
                                </div>
                            </div>
                            {{-- <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Live Demo</span>
                            </div> --}}
                        </div>

                        <!-- Video Player -->
                        <div class="relative rounded-2xl overflow-hidden bg-black shadow-inner">
                            <!-- Video Element -->
                            <video
                                class="w-full h-auto rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300"
                                controls poster="{{ asset('images/app-demo-poster.jpg') }}" preload="metadata">
                                <source src="{{ asset('videos/promo.mp4') }}" type="video/mp4">
                                <div
                                    class="flex items-center justify-center h-64 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="font-medium">Your browser does not support the video tag.</p>
                                        <p class="text-sm mt-1">Please upgrade to a modern browser to view this demo.</p>
                                    </div>
                                </div>
                            </video>

                            <!-- Custom Play Button Overlay -->
                            <div
                                class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 bg-black/20 rounded-2xl pointer-events-none">
                                <div class="bg-white/20 backdrop-blur-sm p-4 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Video Footer -->
                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Duration: 1:25</span>
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span>Full Tutorial</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-amber-600 dark:text-amber-400 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z">
                                    </path>
                                </svg>
                                <span>Quick Setup</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>