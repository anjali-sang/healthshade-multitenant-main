{{-- <!-- Chart Section -->
<div class="p-6 pb-4">
    <div class="relative">
        <canvas id="purchase_order_stat" class="w-full min-h-52"></canvas>
    </div>
</div>
@php
    $total = max(1, $ordered_status_count + $partial_status_count + $in_cart_count);
    $ordered_status_count_percent = ($ordered_status_count / $total) * 100;
    $partial_status_count_percent = ($partial_status_count / $total) * 100;
    $in_cart_count_percent = ($in_cart_count / $total) * 100;
@endphp
<!-- Stats List -->
<div>
    <div class="space-y-3">
        <!-- Ordered -->
        <div class="flex items-center justify-between py-2 px-4 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <span class="text-sm font-medium text-gray-700">Ordered</span>
            </div>
            <div class="flex items-center space-x-3">
                <span id="ordered_status_count" class="text-sm font-semibold text-gray-900 min-w-12 text-right">
                    {{ $ordered_status_count }}
                </span>
                <span id="ordered_status_count_percent"
                    class="text-xs font-medium text-white bg-blue-500 px-2 py-1 rounded-full">
                    {{ number_format((float) $ordered_status_count_percent, 1, '.', '') }}%
                </span>
            </div>
        </div>

        <!-- Partial Orders -->
        <div class="flex items-center justify-between py-2 px-4 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <span class="text-sm font-medium text-gray-700">Partial Orders</span>
            </div>
            <div class="flex items-center space-x-3">
                <span id="partial_status_count" class="text-sm font-semibold text-gray-900 min-w-12 text-right">
                    {{ $partial_status_count }}
                </span>
                <span id="partial_status_count_percent"
                    class="text-xs font-medium text-white bg-yellow-500 px-2 py-1 rounded-full">
                    {{ number_format((float) $partial_status_count_percent, 1, '.', '') }}%
                </span>
            </div>
        </div>

        <!-- In Cart -->
        <div class="flex items-center justify-between py-2 px-4 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <span class="text-sm font-medium text-gray-700">In Cart</span>
            </div>
            <div class="flex items-center space-x-3">
                <span id="in_cart_count" class="text-sm font-semibold text-gray-900 min-w-12 text-right">
                    {{ $in_cart_count }}
                </span>
                <span id="in_cart_count_percent"
                    class="text-xs font-medium text-white bg-green-500 px-2 py-1 rounded-full">
                    {{ number_format((float) $in_cart_count_percent, 1, '.', '') }}%
                </span>
            </div>
        </div>
    </div>
</div>



<!-- Include jQuery and Chart.js -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let purchaseOrderStatPieChart;

    $(document).ready(function () {
        const purchaseOrderStat = document.getElementById('purchase_order_stat').getContext('2d');

        let ordered_status_count = parseInt($('#ordered_status_count').text());
        let partial_status_count = parseInt($('#partial_status_count').text());
        let in_cart_count = parseInt($('#in_cart_count').text());

        const labels = ["Ordered", "Partial Orders", "In Cart"];

        purchaseOrderStatPieChart = new Chart(purchaseOrderStat, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Purchase Orders',
                    data: [ordered_status_count, partial_status_count, in_cart_count],
                    borderWidth: 0,
                    backgroundColor: [
                        '#3b82f6',
                        '#eab308',
                        '#22c55e'
                    ],
                    hoverBackgroundColor: [
                        '#2563eb',
                        '#ca8a04',
                        '#16a34a'
                    ]
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1000
                }
            }
        });
    });

    function updatePurchaseOrderStat(ordered_status_count, partial_status_count, in_cart_count) {
        $('#ordered_status_count').text(ordered_status_count);
        $('#partial_status_count').text(partial_status_count);
        $('#in_cart_count').text(in_cart_count);

        let total = Math.max(1, ordered_status_count + partial_status_count + in_cart_count);

        let ordered_status_count_percent = ((ordered_status_count / total) * 100).toFixed(1);
        let partial_status_count_percent = ((partial_status_count / total) * 100).toFixed(1);
        let in_cart_count_percent = ((in_cart_count / total) * 100).toFixed(1);

        $('#ordered_status_count_percent').text(ordered_status_count_percent + "%");
        $('#partial_status_count_percent').text(partial_status_count_percent + "%");
        $('#in_cart_count_percent').text(in_cart_count_percent + "%");

        if (typeof purchaseOrderStatPieChart !== 'undefined' && purchaseOrderStatPieChart) {
            purchaseOrderStatPieChart.data.datasets[0].data = [
                ordered_status_count,
                partial_status_count,
                in_cart_count
            ];
            purchaseOrderStatPieChart.update();
        }
    }
</script> --}}