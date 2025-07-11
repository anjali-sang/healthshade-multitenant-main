{{-- <!-- Enhanced Supplier Stats Component -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header Section -->
    <div class="border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- View Toggle -->
                <div class="flex bg-white rounded-lg p-1 shadow-sm border">
                    <button id="chart-view"
                        class="px-3 py-1 text-xs font-medium rounded-md bg-blue-500 text-white transition-all">
                        Chart View
                    </button>
                    <button id="table-view"
                        class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:text-gray-900 transition-all">
                        Table View
                    </button>
                </div>

                <!-- Sort Options -->
                <select id="sort-option"
                    class="text-xs border border-gray-200 rounded-lg px-4 py-1.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="cost-desc">Highest Cost</option>
                    <option value="cost-asc">Lowest Cost</option>
                    <option value="name-asc">Name A-Z</option>
                    <option value="name-desc">Name Z-A</option>
                </select>

            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600" id="total-cost">$0</div>
                    <div class="text-xs text-gray-500">Total Cost</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Section -->
    <div class="p-6">
        <!-- Loading State -->
        <div id="loading-state" class="hidden">
            <div class="flex items-center justify-center h-64">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-3"></div>
                    <p class="text-gray-500 text-sm">Loading supplier data...</p>
                </div>
            </div>
        </div>

        <!-- Chart View -->
        <div id="chart-container" class="transition-all duration-300">
            <div id="supplier-bar-chart" class="h-96 w-full"></div>
        </div>

        <!-- Table View -->
        <div id="table-container" class="hidden transition-all duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-2 font-medium text-gray-700">#</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-700">Supplier Name</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-700">Total Cost</th>
                            <th class="text-right py-3 px-2 font-medium text-gray-700">Percentage</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="supplier-table-body">
                        <!-- Table rows will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden">
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Supplier Data</h3>
                <p class="text-gray-500 mb-4">There are no supplier costs to display at the moment.</p>
                <button
                    class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                    Add Supplier
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let supplierChart;
    let supplierData = [];
    let currentView = 'chart';

    $(document).ready(function () {
        loadSupplierChart();
        initializeEventListeners();
    });

    function initializeEventListeners() {
        // View toggle
        $('#chart-view').click(function () {
            switchView('chart');
        });

        $('#table-view').click(function () {
            switchView('table');
        });

        // Sort change
        $('#sort-option').change(function () {
            sortAndRenderData();
        });

        // Refresh button
        $('#refresh-btn').click(function () {
            loadSupplierChart();
        });
    }

    function switchView(view) {
        currentView = view;

        if (view === 'chart') {
            $('#chart-view').addClass('bg-blue-500 text-white').removeClass('text-gray-600');
            $('#table-view').removeClass('bg-blue-500 text-white').addClass('text-gray-600');
            $('#chart-container').removeClass('hidden');
            $('#table-container').addClass('hidden');
        } else {
            $('#table-view').addClass('bg-blue-500 text-white').removeClass('text-gray-600');
            $('#chart-view').removeClass('bg-blue-500 text-white').addClass('text-gray-600');
            $('#chart-container').addClass('hidden');
            $('#table-container').removeClass('hidden');
            renderTable();
        }
    }

    function showLoading() {
        $('#loading-state').removeClass('hidden');
        $('#chart-container, #table-container, #empty-state').addClass('hidden');
    }

    function hideLoading() {
        $('#loading-state').addClass('hidden');
    }

    function showEmptyState() {
        $('#empty-state').removeClass('hidden');
        $('#chart-container, #table-container').addClass('hidden');
    }

    function loadSupplierChart() {
        showLoading();

        $.get('/supplier-costs', function (data) {
            console.log('Raw API Response:', data); // Debug log
            processSupplierData(data);
        }).fail(function (xhr, status, error) {
            hideLoading();
            console.error("Failed to fetch supplier stats:", {
                status: status,
                error: error,
                response: xhr.responseText
            });
            showEmptyState();
        });
    }

    function processSupplierData(data) {
        console.log('Processing data:', data); // Debug log
        hideLoading();

        if (!data || data.length === 0) {
            console.log('No data or empty array'); // Debug log
            showEmptyState();
            return;
        }

        // Process and clean data
        supplierData = data.map(item => {
            console.log('Processing item:', item); // Debug log
            return {
                name: item.supplier_name || 'Unknown Supplier',
                cost: parseFloat(item.total_cost) || 0
            };
        });

        console.log('Processed supplier data:', supplierData); // Debug log

        if (supplierData.length === 0) {
            console.log('No valid supplier data after processing'); // Debug log
            showEmptyState();
            return;
        }

        // Update summary stats
        updateSummaryStats();

        // Sort and render data
        sortAndRenderData();
    }

    function updateSummaryStats() {
        const totalCost = supplierData.reduce((sum, item) => sum + item.cost, 0);
        const totalSuppliers = supplierData.length;

        $('#total-suppliers').text(totalSuppliers);
        $('#total-cost').text(formatCurrency(totalCost));
    }

    function sortAndRenderData() {
        const sortOption = $('#sort-option').val();
        let sortedData = [...supplierData];

        switch (sortOption) {
            case 'cost-desc':
                sortedData.sort((a, b) => b.cost - a.cost);
                break;
            case 'cost-asc':
                sortedData.sort((a, b) => a.cost - b.cost);
                break;
            case 'name-asc':
                sortedData.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'name-desc':
                sortedData.sort((a, b) => b.name.localeCompare(a.name));
                break;
        }

        supplierData = sortedData;

        if (currentView === 'chart') {
            renderChart();
        } else {
            renderTable();
        }
    }

    function renderChart() {
        $('#chart-container').removeClass('hidden');

        if (supplierChart) {
            supplierChart.destroy();
        }

        const supplierNames = supplierData.map(item => item.name);
        const totalCosts = supplierData.map(item => item.cost);

        const options = {
            series: [{
                data: totalCosts
            }],
            chart: {
                type: 'bar',
                height: 384,
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: '60%',
                    distributed: true
                }
            },
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#84cc16', '#f97316'],
            xaxis: {
                categories: supplierNames,
                min: 0,
                labels: {
                    formatter: function (val) {
                        return formatCurrency(val);
                    },
                    style: {
                        fontSize: '11px',
                        colors: '#6b7280'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px',
                        colors: '#374151',
                        fontWeight: 500
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return formatCurrency(val);
                    }
                },
                style: {
                    fontSize: '12px'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return formatCurrency(val);
                },
                style: {
                    fontSize: '10px',
                    colors: ['#ffffff']
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 2,
                yaxis: {
                    lines: {
                        show: false
                    }
                },
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            },
            legend: {
                show: false
            }
        };

        supplierChart = new ApexCharts(document.querySelector("#supplier-bar-chart"), options);
        supplierChart.render();
    }

    function renderTable() {
        const totalCost = supplierData.reduce((sum, item) => sum + item.cost, 0);
        const tbody = $('#supplier-table-body');
        tbody.empty();

        supplierData.forEach((item, index) => {
            const percentage = ((item.cost / totalCost) * 100).toFixed(1);
            const statusClass = item.cost > totalCost * 0.2 ? 'bg-red-100 text-red-800' :
                item.cost > totalCost * 0.1 ? 'bg-yellow-100 text-yellow-800' :
                    'bg-green-100 text-green-800';
            const statusText = item.cost > totalCost * 0.2 ? 'High' :
                item.cost > totalCost * 0.1 ? 'Medium' : 'Low';

            const row = `
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-2 text-gray-600">${index + 1}</td>
                        <td class="py-3 px-2">
                            <div class="font-medium text-gray-900">${item.name}</div>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <div class="font-semibold text-gray-900">${formatCurrency(item.cost)}</div>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <div class="text-gray-600">${percentage}%</div>
                        </td>
                        <td class="py-3 px-2 text-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                    </tr>
                `;
            tbody.append(row);
        });
    }

    function formatCurrency(value) {
        const num = Number(value);
        if (num >= 1_000_000) return `$${(num / 1_000_000).toFixed(1)}M`;
        if (num >= 1000) return `$${(num / 1000).toFixed(1)}K`;
        return `$${num.toFixed(2)}`;
    }
</script> --}}