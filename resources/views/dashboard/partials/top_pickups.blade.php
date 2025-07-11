{{-- <div class="w-full p-3 bg-white rounded-lg text-sm">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <h3 class="font-semibold text-gray-800 text-sm">Top Pickups</h3>
        </div>
        <span class="text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded">
            Top {{ count($top_picks) }} items
        </span>
    </div>

    <!-- Table Header -->
    <div class="grid grid-cols-6 gap-2 px-2 py-1.5 bg-gray-50 rounded-md text-xs font-medium text-gray-600 border-b border-gray-200">
        <div class="col-span-2">Product</div>
        <div>Supplier</div>
        <div>Location</div>
        <div>Unit</div>
        <div class="text-right">Picked Qty</div>
    </div>

    <!-- Products Table -->
    <div class="top-pickups-list space-y-0.5 mt-2">
        @foreach($top_picks as $index => $pickup)
            <div class="grid grid-cols-6 gap-2 p-2 py-3 border border-transparent hover:border-green-100 hover:bg-green-50/30 rounded-md transition cursor-pointer">
                
                <!-- Product Info (spans 2 columns) -->
                <div class="col-span-2" onclick="openProductModal({{ $pickup->product->id }})">
                    <div class="flex items-center gap-2">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-gray-900 font-medium leading-tight truncate hover:text-green-700 text-xs">
                                {{ \Illuminate\Support\Str::limit($pickup->product->product_name, 40) }}
                            </h4>
                            <p class="text-xs text-gray-500">{{ $pickup->product->product_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Supplier -->
                <div class="flex flex-1 items-center">
                    <span class="text-xs text-gray-700 truncate" title="{{ $pickup->product->supplier->supplier_name ?? 'N/A' }}">
                        {{ $pickup->product->supplier->supplier_name ?? 'N/A'}}
                    </span>
                </div>

                <!-- Location -->
                <div class="flex items-center">
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs truncate">
                        <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="truncate">{{$pickup->location_name ?? 'N/A' }}</span>
                    </span>
                </div>

                <!-- Unit -->
                <div class="flex items-center">
                    <span class="text-xs text-gray-600 px-1.5 py-0.5 bg-gray-100 rounded">
                        {{ 'N/A' }}
                    </span>
                </div>

                <!-- Picked Quantity -->
                <div class="flex items-center justify-end">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-green-700">
                            {{ number_format($pickup->total_picked_qty) }}
                        </div>
                        <div class="text-xs text-gray-500">picked</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if(count($top_picks) === 0)
        <div class="text-center py-6 text-sm text-gray-600">
            <div class="w-10 h-10 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            No pickup data available
        </div>
    @endif
</div>

<script>
    function openProductModal(id) {
        //console.log('openProductModal called with ID:', id);

        // Check if Livewire is available
        if (typeof Livewire !== 'undefined') {
            console.log('Livewire is available, dispatching event...');
            Livewire.dispatch('openProductDetailBrowser', { id: id });
            console.log('Event dispatched');
        } else {
            console.error('Livewire is not available!');
        }
    }

    function updateTopPickupsList(pickups) {
        console.log(pickups);
        const container = document.querySelector('.top-pickups-list');
        container.innerHTML = '';

        if (pickups.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 text-sm text-gray-600">
                    <div class="w-10 h-10 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    No pickup data available
                </div>`;
            return;
        }

        pickups.forEach((pickup, index) => {
            const rankClass = index === 0 ? 'bg-yellow-100 text-yellow-800' : 
                             (index === 1 ? 'bg-gray-100 text-gray-600' : 
                             (index === 2 ? 'bg-orange-100 text-orange-600' : 
                             'bg-green-100 text-green-600'));
            
            const pickupHTML = `
                <div class="grid grid-cols-6 gap-2 p-2 border border-transparent hover:border-green-100 hover:bg-green-50/30 rounded-md transition cursor-pointer">
                    
                    <!-- Product Info (spans 2 columns) -->
                    <div class="col-span-2" onclick="openProductModal('${pickup.product_id}')">
                        <div class="flex items-center gap-2">
                            <!-- Rank Badge -->
                            <div class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold ${rankClass}">
                                ${index + 1}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-gray-900 font-medium leading-tight truncate hover:text-green-700 text-xs">
                                    ${pickup.product.product_name.length > 30 ? pickup.product.product_name.slice(0, 30) + '...' : pickup.product.product_name}
                                </h4>
                                <p class="text-xs text-gray-500">${pickup.product.product_code}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="flex items-center">
                        <span class="text-xs text-gray-700 truncate" title="${pickup.product.supplier?.supplier_name ?? 'N/A'}">
                            ${(pickup.product.supplier?.supplier_name ?? 'N/A').length > 12 ? (pickup.product.supplier?.supplier_name ?? 'N/A').slice(0, 12) + '...' : (pickup.product.supplier?.supplier_name ?? 'N/A')}
                        </span>
                    </div>

                    <!-- Location -->
                    <div class="flex items-center">
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs truncate">
                            <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="truncate">${(pickup.location_name ?? 'N/A').length > 8 ? (pickup.location_name ?? 'N/A').slice(0, 8) + '...' : (pickup.location_name ?? 'N/A')}</span>
                        </span>
                    </div>

                    <!-- Unit -->
                    <div class="flex items-center">
                        <span class="text-xs text-gray-600 px-1.5 py-0.5 bg-gray-100 rounded">
                            ${ 'N/A'}
                        </span>
                    </div>

                    <!-- Picked Quantity -->
                    <div class="flex items-center justify-end">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-green-700">
                                ${pickup.total_picked_qty.toLocaleString()}
                            </div>
                            <div class="text-xs text-gray-500">picked</div>
                        </div>
                    </div>
                </div>
            `;
            const wrapper = document.createElement('div');
            wrapper.innerHTML = pickupHTML;
            container.appendChild(wrapper.firstElementChild);
        });
    }
</script> --}}