{{-- <div class="flex justify-end items-center mb-3 gap-3">
    @if(auth()->user()->role_id == 1)
        <div>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button id="orgDropdownBtn"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                        <div>All Organizations</div>
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="org-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    data-id="0"  data-name="All Organizations">
                        All Organizations
                    </div>
                    @foreach($org_list as $org)
                        <div class="org-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="{{ $org->id }}"
                            data-name="{{ $org->name }}">
                            {{ $org->name }}
                        </div>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </div>
    @endif
    @if(auth()->user()->role_id > 1)
        <div>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button id="locDropdownBtn"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                        <div>All Locations</div>
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="loc-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="0"
                        data-name="All Locations">
                        All Locations
                    </div>
                    @foreach($locations_list as $location)
                        <div class="loc-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            data-id="{{ $location->id }}" data-name="{{ $location->name }}">
                            {{ $location->name }}
                        </div>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.org-option').on('click', function () {
            var selectedOrgId = $(this).data('id');
            var selectedOrgName = $(this).data('name');
            $('#orgDropdownBtn div').text(selectedOrgName);
            updateDashboard(selectedOrgId, null);
        });

        $('.loc-option').on('click', function () {
            var selectedLocId = $(this).data('id');
            var selectedLocName = $(this).data('name');
            $('#locDropdownBtn div').text(selectedLocName);
            updateDashboard(null, selectedLocId);
        });

        function updateDashboard(orgId, locId) {
            let org = orgId !== null ? orgId : 0;
            let loc = locId !== null ? locId : 0;
            initBarGraph();
            updateBarGraph(org, loc);
            $.get(`/update_dashboard/${org}/${loc}`, function (data) {
                //console.log("Dashboard updated:", data);
                updateTopHeaders(data.stock_onhand, data.value_onhand, data.stock_to_receive, data.pending_value);
                updatePurchaseOrderStat(data.ordered_status_count, data.partial_status_count, data.in_cart_count);
                updateSupplierStats(data.supplier_list);
                updateRecentPurchaseOrders(data.recent_purchase_orders_list);
                updateLowProductList(data.low_stock_products_list);
            });
        }

        function updateTopHeaders(stock_onhand, value_onhand, stock_to_receive, pendingValue) {
            $('#stock_onhand').text(stock_onhand);
            $('#value_onhand').text('$' + formatCurrency(value_onhand));
            $('#stock_to_receive').text(stock_to_receive);
            $('#pendingValue').text('$' + formatCurrency(pendingValue));
        }

        function formatCurrency(value) {
            return value ? parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00';
        }

    });

</script> --}}