{{-- <div class="recent-purchase-orders-list">
    @foreach($recent_purchase_orders_list as $orders)
        @php
            $statusClasses = match ($orders->status) {
                'pending' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400 border-yellow-200',
                'ordered' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400 border-blue-200',
                'partial' => 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400 border-orange-200',
                'completed' => 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400 border-green-200',
                'cancel' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400 border-red-200',
                default => 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-400 border-gray-200',
            };
        @endphp

        <div
            class="flex items-center justify-between gap-3 p-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 recent-purchase-orders">
            <!-- PO Number & Supplier -->
            <div class="flex min-w-0 flex-col">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $orders->purchase_oder_number  }}</p>
                <div class="flex items-center gap-1 mt-0.5">
                    <p class="text-xs text-gray-600 truncate">
                        {{ $orders->purchaseSupplier->supplier_name ?? 'Supplier Name' }}
                    </p>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="flex-shrink-0">
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses }}">
                    <div class="w-1.5 h-1.5 rounded-full mr-1.5 {{ 
                                                            match ($orders->status) {
                'pending' => 'bg-yellow-500',
                'ordered' => 'bg-blue-500',
                'partial' => 'bg-orange-500',
                'completed' => 'bg-green-500',
                'cancel' => 'bg-red-500',
                default => 'bg-gray-500',
            }
                                                        }}"></div>
                    {{ ucfirst($orders->status ?? 'pending') }}
                </span>
            </div>

            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium">
                    {{ ucfirst($orders->purchaseLocation->name) }}
                </span>
            </div>

            <!-- Amount -->
            <div class="flex-shrink-0 text-right min-w-0">
                <p class="text-sm font-semibold text-gray-900">${{ number_format($orders->total ?? 0.00, 2) }}</p>
                <p class="text-xs text-gray-500">{{ $orders->created_at ? $orders->created_at->format('M d, Y') : 'N/A' }}
                </p>
            </div>

            <!-- Reorder Button -->
            <div class="flex-shrink-0">
                <button onclick="openReorderModal({{ $orders->id }})"
                    class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors duration-200"
                    title="Reorder this purchase order">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reorder
                </button>
            </div>
        </div>
    @endforeach
</div>
<!-- Confirmation Modal -->
<div id="reorder-confirm-modal"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Confirm Reorder</h2>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Are you sure you want to reorder this purchase order?
        </p>
        <div class="flex justify-end space-x-2">
            <button onclick="closeReorderModal()"
                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                Cancel
            </button>
            <button id="confirmReorderBtn"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                Yes, Reorder
            </button>
        </div>
    </div>
</div>

<script>
    function updateRecentPurchaseOrders(orders) {
        //console.log(orders);
        const container = document.querySelector('.recent-purchase-orders-list');

        container.innerHTML = '';
        if (Array.isArray(orders) && orders.length > 0) {

            orders.forEach(order => {
                const statusClasses = {
                    'pending': 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400 border-yellow-200',
                    'ordered': 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400 border-blue-200',
                    'partial': 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400 border-orange-200',
                    'completed': 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400 border-green-200',
                    'cancel': 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400 border-red-200',
                }[order.status] || 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-400 border-gray-200';

                const dotColor = {
                    'pending': 'bg-yellow-500',
                    'ordered': 'bg-blue-500',
                    'partial': 'bg-orange-500',
                    'completed': 'bg-green-500',
                    'cancel': 'bg-red-500',
                }[order.status] || 'bg-gray-500';

                const locationName = order.purchase_location ? order.purchase_location.name : 'Unknown Location';
                const supplierName = order.purchase_supplier ? order.purchase_supplier.supplier_name : 'Supplier Name';
                const amount = parseFloat(order.total ?? 0).toFixed(2);
                const createdAt = order.created_at ? new Date(order.created_at).toLocaleString('default', { month: 'short', day: 'numeric' }) : 'Dec 15';

                const orderElement = document.createElement('div');
                orderElement.className = `flex items-center justify-between gap-3 p-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200`;
                orderElement.innerHTML = `
                <div class="flex min-w-0 flex-col">
                    <p class="text-sm font-semibold text-gray-900 truncate">${order.purchase_oder_number}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <p class="text-xs text-gray-600 truncate">${supplierName}</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border ${statusClasses}">
                        <div class="w-1.5 h-1.5 rounded-full mr-1.5 ${dotColor}"></div>
                        ${order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : 'Pending'}
                    </span>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium">
                        ${locationName}
                    </span>
                </div>
                <div class="flex-shrink-0 text-right min-w-0">
                    <p class="text-sm font-semibold text-gray-900">$${amount}</p>
                    <p class="text-xs text-gray-500">${createdAt}</p>
                </div>
                <div class="flex-shrink-0">
                    <button
                        onclick="openReorderModal(${order.id})"
                        class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors duration-200"
                        title="Reorder this purchase order">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reorder
                    </button>
                </div>
            `;

                container.appendChild(orderElement);
            });
        } else {
            container.innerHTML = `
            <div class="p-4 text-center text-gray-500 text-sm">
                No recent purchase orders found.
            </div>
        `;
        }
    }
</script>
<script>
    let reorderId = null;

    function openReorderModal(id) {
        reorderId = id;
        document.getElementById('reorder-confirm-modal').classList.remove('hidden');
    }

    function closeReorderModal() {
        reorderId = null;
        document.getElementById('reorder-confirm-modal').classList.add('hidden');
    }

    document.getElementById('confirmReorderBtn').addEventListener('click', function () {
        if (!reorderId) return;

        fetch(`/purchase-orders/${reorderId}/reorder`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
            .then(res => res.json())
            .then(data => {
                closeReorderModal();
                if (data.success) {

                    alert('Order placed successfully !');

                } else {
                    alert(data.message || 'Failed to reorder. Please try again.');
                }
            })
            .catch(err => {
                closeReorderModal();
                console.error(err);
                alert('An error occurred while reordering.');
            });
    });
</script> --}}