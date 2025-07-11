{{-- <div>
    <div  id="col-bar-chart"></div>
</div>


<!-- Load ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
        var chart;
        
        $(document).ready(function () {
            initBarGraph();
            updateBarGraph(0,0);
        });
        
        function initBarGraph() {
            chart = new ApexCharts(document.querySelector("#col-bar-chart"), {
                series: [{
                    name: 'Purchase',
                    data: []
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Inter, sans-serif'
                },
                legend: {
                    show: false
                },
                colors: ['#10b981'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded',
                        borderRadius: 4
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: [],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '12px',
                            fontWeight: 500
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return "$" + value.toLocaleString();
                        },
                        style: {
                            colors: '#6b7280',
                            fontSize: '12px'
                        }
                    }
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                fill: {
                    opacity: 0.9,
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.25,
                        gradientToColors: ['#34d399'],
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 0.85,
                        stops: [0, 100]
                    }
                },
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return "$ " + val.toLocaleString();
                        }
                    }
                }
            });
            
            chart.render();
        }
        
        function updateBarGraph(organization_id, location_id) {
            $.get('/apex-bar-chart/' + organization_id + '/' + location_id, function (data) {
                var months = data.map(item => item.month).reverse();
                var totalPurchaseCosts = data.map(item => {
                    var cost = Number(item.total_purchase_cost) || 0;
                    return cost % 1 === 0 ? cost.toFixed(0) : cost.toFixed(2);
                }).reverse();
                
                if (chart) {
                    chart.updateOptions({
                        xaxis: {
                            categories: months
                        }
                    });
                    chart.updateSeries([{
                        name: 'Purchase',
                        data: totalPurchaseCosts
                    }]);
                } else {
                    console.error("Chart is not initialized.");
                }
            }).fail(function () {
                console.error("Failed to load chart data.");
            });
        }
    </script> --}}