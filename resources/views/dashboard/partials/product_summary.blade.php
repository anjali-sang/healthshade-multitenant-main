    {{-- <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
        
        <!-- Low Stock Count -->
        <div class=" bg-white rounded-xl shadow-sm border border-red-100 hover:shadow-md transition-all duration-300 group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 rounded-xl group-hover:bg-red-100 transition-colors duration-300">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $low_on_stock }}
                </div>
                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                    Low on Stock
                </div>
            </div>
            <div class="h-1 bg-red-500"></div>
        </div>

        <!-- Products Available -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-all duration-300 group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 rounded-xl group-hover:bg-green-100 transition-colors duration-300">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $product_avialable }}
                </div>
                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                    Stock Available
                </div>
            </div>
            <div class="h-1 bg-green-500"></div>
        </div>

        <!-- Product Not Available -->
        <div class=" bg-white rounded-xl shadow-sm border border-orange-100 hover:shadow-md transition-all duration-300 group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition-colors duration-300">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $product_not_avialable }}
                </div>
                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                    Not Available
                </div>
            </div>
            <div class="h-1 bg-orange-500"></div>
        </div>

        <!-- Total Products -->
        <div class=" bg-white rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-all duration-300 group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors duration-300">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $total_products }}
                </div>
                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                    Total Products
                </div>
            </div>
            <div class="h-1 bg-blue-500"></div>
        </div>

    </div>

    <!-- Active Products Progress -->
    <div class="max-w-7xl mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Active Products</h3>
                <span class="text-2xl font-bold text-emerald-600">{{ number_format((float) $active_products, 2, '.', '') }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-emerald-500 to-green-500 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $active_products }}%"></div>
            </div>
        </div>
    </div>

 <!-- #region   -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var productsChart;
    function getProductData() {
        const activeProducts = parseFloat(document.getElementById('product_available').textContent);
        const notAvailable = parseFloat(document.getElementById('product_not_available').textContent);
        return [activeProducts, notAvailable];
    }
    function initChart() {
        const ctx = document.getElementById('products_chart').getContext('2d');
        if (productsChart) {
            productsChart.destroy();
        }
        productsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Active Products', 'Products not Available'],
                datasets: [{
                    label: 'Quantity',
                    data: getProductData(),
                    borderWidth: 1,
                    backgroundColor: [
                        'rgb(56, 199, 56)',
                        '#C8C8C8'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    document.addEventListener("DOMContentLoaded", initChart);
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === 'visible') {
            initChart();
        }
    });
</script> --}}