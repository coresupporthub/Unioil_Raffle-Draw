let chartInstance;
let barChart;

document.addEventListener("DOMContentLoaded", function () {
    if (window.ApexCharts) {
        // Initialize the pie chart
        chartInstance = new ApexCharts(document.getElementById('chart-demo-pie'), {
            chart: {
                type: "donut",
                fontFamily: "inherit",
                height: 354,
                sparkline: { enabled: true },
                animations: { enabled: false },
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "diagonal1",
                    shadeIntensity: 0.7,
                    gradientToColors: [tabler.getColor("primary"),"#137f13"],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 0.9,
                    stops: [0, 100],
                },
            },
            series: [0, 0], 
            labels: ["No Event Data", ""], 
            tooltip: { theme: "dark", enabled: false }, 
            grid: { strokeDashArray: 4 },
            colors: ["#B0B0B0", "#B0B0B0"], 
            legend: { show: true, position: "bottom", offsetY: 8 },
        });

        chartInstance.render();
        
        // Fetch the active event data
        fetchActiveEventData();

        // Handle dropdown change
        document.getElementById('event-dropdown').addEventListener('change', function () {
            const eventId = this.value;
            loadbarchart(eventId)
            fetchEventData(eventId); 
            fetchEntriesData(eventId); 
            fetchClusterData(eventId); 
            fetchEventDataarea(eventId);
            
        });

        // Initialize the bar chart for raffle entries issued by product type
        barChart = new ApexCharts(document.getElementById('chart-tasks-overview1'), {
            chart: {
                type: "bar",
                fontFamily: 'inherit',
                height: 302,
                toolbar: { show: false },
                animations: { enabled: false },
            },
            plotOptions: { bar: { columnWidth: '50%' } },
            dataLabels: { enabled: false },
            fill: { type: "solid" },
            series: [{ name: "Fully Synthetic", data: [] }, { name: "Semi Synthetic", data: [] }],
            tooltip: { theme: 'dark' },
            grid: { padding: { top: -20, right: 0, left: -4, bottom: -2 }, strokeDashArray: 4 },
            xaxis: { labels: { padding: 0 }, tooltip: { enabled: false }, axisBorder: { show: false }, categories: [] },
            yaxis: { labels: { padding: 4 } },
            colors: ['#137f13', tabler.getColor("primary")],
            legend: { show: true, position: 'top' },
        });

        barChart.render();
    }
});

// Fetch event data based on event ID
function fetchEventData(eventId) {
    if (!eventId) return;

    $.ajax({
        url: `/api/events/data/${eventId}`,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                updateCharts(data.semiSynthetic, data.fullySynthetic);
            } else {
                updateCharts(0, 0, true);
            }
        },
        error: function () {
            updateCharts(0, 0, true);
        }
    });
}

// Update the pie chart with new data
function updateCharts(semiSynthetic, fullySynthetic, noData = false) {
    if (chartInstance) {
        const chartData = noData ? [0, 0] : [semiSynthetic, fullySynthetic];
        const chartColors = noData ? ["#B0B0B0", "#B0B0B0"] : [ tabler.getColor("primary"), "#137f13"];

        chartInstance.updateSeries(chartData);
        chartInstance.updateOptions({
            labels: noData ? ["No Event Data", ""] : ["Semi Synthetic", "Fully Synthetic"],
            tooltip: { enabled: !noData },
            colors: chartColors,
            plotOptions: { pie: { donut: { size: "70%" } } }
        });
    }
}

// Fetch the data for raffle entries issued by product type
function fetchEntriesData(eventId) { 
    if (!eventId) return;

    $.ajax({
        url: `/api/entry/product-type/${eventId}`,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (Array.isArray(data)) {

                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                const months = data.map(item => {
                    const [year, month] = item.month.split('-');
                    return `${monthNames[parseInt(month) - 1]} ${year}`;
                });

                const fullySynthetic = data.map(item => item.fully_synthetic);
                const semiSynthetic = data.map(item => item.semi_synthetic);

                if (barChart) {
                    barChart.updateOptions({
                        xaxis: { categories: months },
                        series: [
                            { name: "Fully Synthetic", data: fullySynthetic },
                            { name: "Semi Synthetic", data: semiSynthetic },
                        ],
                    });
                }
            }
        },
        error: function () {
            console.error("Error fetching event data");
        }
    });
}


// Fetch active event data
function fetchActiveEventData() {
    $.ajax({
        url: '/api/events/datas/active', 
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.success && data.eventData) {
                const activeEventId = data.eventData.event_id; 
                const activeEventName = data.eventData.event_name;

                const dropdown = document.getElementById('event-dropdown');
                dropdown.value = activeEventId;

                const activeOption = Array.from(dropdown.options).find(
                    option => option.value == activeEventId
                );
                if (!activeOption) {
                    const newOption = new Option(activeEventName, activeEventId, true, true);
                    dropdown.add(newOption);
                }
                loadbarchart(activeEventId)
                fetchEventData(activeEventId); 
                fetchEntriesData(activeEventId); 
                fetchClusterData(activeEventId); 
                fetchEventDataarea(activeEventId);
                
            } else {
                console.warn('No active event found.');
                updateCharts(0, 0, true);
            }
        },
        error: function () {
            console.error('Failed to fetch active event data.');
            updateCharts(0, 0, true);
        }
    });
}


// Fetch regional cluster raffle data
function fetchClusterData(eventId) {
    if (!eventId) return;

    $.ajax({
        url: `/api/clusters/data/${eventId}`,
        method: 'GET',
        success: function(data) {
            const chartElement = document.getElementById('chart-tasks-overview');
            if (data.success && data.data.length > 0) {
                const clusterNames = data.data.map(item => item.cluster);
                const entries = data.data.map(item => item.entries);

                const chart = new ApexCharts(chartElement, {
                    chart: {
                        type: "bar",
                        fontFamily: 'inherit',
                        height: 314,
                        parentHeightOffset: 0,
                        toolbar: { show: true }, 
                        animations: { enabled: false },
                    },
                    plotOptions: { bar: { columnWidth: '70%' } },
                    dataLabels: { enabled: false },
                    fill: { type: "solid" },
                    series: [{ name: "Raffle Entries", data: entries }],
                    tooltip: { theme: 'dark' },
                    grid: { padding: { top: -20, right: 0, left: -4, bottom: -4 }, strokeDashArray: 4 },
                    xaxis: { labels: { padding: 0 }, tooltip: { enabled: false }, axisBorder: { show: false }, categories: clusterNames },
                    yaxis: { labels: { padding: 4 } },
                    colors: ['#137f13'],
                    legend: { show: false },
                });

                chart.render();

                // Add a download button for exporting the chart
                const downloadButton = document.createElement('button');
                downloadButton.textContent = 'Download Chart';
                downloadButton.style.marginTop = '10px';
                downloadButton.style.padding = '8px 12px';
                downloadButton.style.backgroundColor = '#137f13';
                downloadButton.style.color = '#fff';
                downloadButton.style.border = 'none';
                downloadButton.style.cursor = 'pointer';
                downloadButton.style.borderRadius = '4px';


            } else {
                chartElement.innerHTML = "<p>No event data found.</p>";
            }
        },
        error: function() {
            console.error('Error fetching cluster data');
            document.getElementById('chart-tasks-overview').innerHTML = "<p>Failed to fetch data. Please try again later.</p>";
        }
    });
}

// Fetch issuance data for the event
function fetchEventDataarea(eventId) {
    if (!eventId) return;

    $.ajax({
        url: `/api/entry/issuance/${eventId}`,
        method: 'GET',
        success: function (data) {
            const chartElement = document.getElementById('chart-completion-tasks-10');
            chartElement.innerHTML = "";
            
            if (data.success && data.eventData.length > 0) {
                // Sort the eventData array by date in ascending order
                data.eventData.sort((a, b) => new Date(a.date) - new Date(b.date));

                // Convert dates to a readable format
                const monthNames = [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ];

                const labels = data.eventData.map(entry => {
                    const [year, month, day] = entry.date.split("-");
                    return `${day} ${monthNames[parseInt(month) - 1]} ${year}`;
                });

                const counts = data.eventData.map(entry => entry.count);

                new ApexCharts(chartElement, {
                    chart: {
                        type: 'area',
                        height: 302,
                    },
                    series: [{
                        name: 'Raffle Entries',
                        data: counts
                    }],
                    xaxis: {
                        categories: labels,
                        title: {
                            style: {
                                color: '#333',
                                fontWeight: 'bold',
                            }
                        },
                        labels: {
                            rotate: -45,
                            style: {
                                colors: '#333',
                                fontSize: '12px',
                            }
                        },
                        axisBorder: { show: true },
                        axisTicks: { show: true },
                    },
                    yaxis: {
                        title: {
                            style: {
                                color: '#333',
                                fontWeight: 'bold',
                            }
                        }
                    },
                    stroke: { width: 2, curve: 'smooth' },
                    colors: [tabler.getColor("primary")],
                    dataLabels: { enabled: false },
                    tooltip: {
                        x: {
                            format: 'dd MMM yyyy' 
                        }
                    },
                    grid: {
                        borderColor: '#e7e7e7',
                        strokeDashArray: 4
                    }
                }).render();
            } else {
                chartElement.innerHTML = "<p>No data available for this event.</p>";
            }
        },
        error: function () {
            const chartElement = document.getElementById('chart-completion-tasks-10');
            chartElement.innerHTML = "<p>Error loading data.</p>";
        }
    });
}

var chart; // Declare chart variable outside to keep reference

function loadbarchart(id) {
    $.ajax({
        url: `/api/entries/productcluster/${id}`,
        method: 'GET',
        success: function (data) {
            console.log(data)
            if (data.success) {
                let seriesData = [];
                let categories = [];
                data.data.forEach((cluster, index) => {
                    categories.push(cluster.cluster);
                    cluster.product.forEach(product => {
                        for (let productType in product) {
                            let productIndex = seriesData.findIndex(item => item.name === productType);
                            if (productIndex === -1) {
                                seriesData.push({
                                    name: productType,
                                    data: Array(data.data.length).fill(0)
                                });
                                productIndex = seriesData.length - 1;
                            }

                            seriesData[productIndex].data[index] = product[productType];
                        }
                    });
                });
                // Destroy the existing chart instance if it exists
                if (chart !== undefined) {
                    chart.destroy();
                }
                // Create a new ApexCharts instance
                chart = new ApexCharts(document.getElementById('chart-combination'), {
                    chart: {
                        type: "bar",
                        fontFamily: 'inherit',
                        height: 240,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false,
                        },
                        animations: {
                            enabled: false
                        },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    fill: {
                        opacity: 1,
                    },
                    series: seriesData,
                    tooltip: {
                        theme: 'dark'
                    },
                    grid: {
                        padding: {
                            top: -20,
                            right: 0,
                            left: -4,
                            bottom: -4
                        },
                        strokeDashArray: 4,
                    },
                    xaxis: {
                        labels: {
                            padding: 0,
                        },
                        tooltip: {
                            enabled: false
                        },
                        axisBorder: {
                            show: false,
                        },
                        categories: categories,
                    },
                    yaxis: {
                        labels: {
                            padding: 4
                        },
                    },
                    colors: [tabler.getColor("success"), tabler.getColor("primary")],
                    legend: {
                        show: true,
                    },
                });
                chart.render();

            } else {
                console.error("Error fetching data: " + data.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX request failed: " + error);
        }
    });
}
