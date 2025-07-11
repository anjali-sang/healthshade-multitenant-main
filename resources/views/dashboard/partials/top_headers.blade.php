{{-- <div class="max-w-10xl mx-auto sm:px-6 lg:px-8 grid grid-cols-4 gap-6">

    <!-- Card 1 -->
    <div
        class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1" id="stock_onhand">
                {{ $stock_onhand ?? 0 }}
            </div>
            <div class="text-emerald-100 text-sm font-medium uppercase tracking-wide">
                Stock On Hand
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div
        class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1" id="value_onhand">
                ${{ $value_onhand ? number_format($value_onhand, 2) : 0 }}
            </div>
            <div class="text-blue-100 text-sm font-medium uppercase tracking-wide">
                Value On Hand
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div
        class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1" id="stock_to_receive">
                {{ $stock_to_receive ?? 0 }}
            </div>
            <div class="text-amber-100 text-sm font-medium uppercase tracking-wide">
                Stock To Be Received
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div
        class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold mb-1" id="pendingValue">
                ${{ $pending_value ? number_format($pending_value, 2) : 0 }}
            </div>
            <div class="text-purple-100 text-sm font-medium uppercase tracking-wide">
                Value To Be Received
            </div>
        </div>
    </div>
</div> --}}