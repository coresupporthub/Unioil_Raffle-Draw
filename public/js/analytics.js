let chartInstance;
let barChart;

document.addEventListener("DOMContentLoaded", function () {
    if (window.ApexCharts) {
        // Initialize the pie chart
        chartInstance = new ApexCharts(document.getElementById('chart-demo-pie'), {
            chart: {
                type: "donut",
                fontFamily: "inherit",
                height: 340,
                sparkline: { enabled: true },
                animations: { enabled: false },
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "diagonal1",
                    shadeIntensity: 0.7,
                    gradientToColors: ["#137f13", "#fd7e14"],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 0.9,
                    stops: [0, 100],
                },
            },
            series: [0, 0], // Default values for the chart
            labels: ["No Event Data", ""], 
            tooltip: { theme: "dark", enabled: false }, 
            grid: { strokeDashArray: 4 },
            colors: ["#B0B0B0", "#B0B0B0"], 
            legend: { show: true, position: "bottom", offsetY: 12 },
        });

        chartInstance.render();
        
        // Fetch the active event data
        fetchActiveEventData();

        // Handle dropdown change
        document.getElementById('event-dropdown').addEventListener('change', function () {
            const eventId = this.value;
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
            grid: { padding: { top: -20, right: 0, left: -4, bottom: -4 }, strokeDashArray: 4 },
            xaxis: { labels: { padding: 0 }, tooltip: { enabled: false }, axisBorder: { show: false }, categories: [] },
            yaxis: { labels: { padding: 4 } },
            colors: ['#137f13', '#fd7e14'],
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
                updateChart(data.semiSynthetic, data.fullySynthetic);
            } else {
                updateChart(0, 0, true);
            }
        },
        error: function () {
            updateChart(0, 0, true);
        }
    });
}

// Update the pie chart with new data
function updateChart(semiSynthetic, fullySynthetic, noData = false) {
    if (chartInstance) {
        const chartData = noData ? [0, 0] : [semiSynthetic, fullySynthetic];
        const chartColors = noData ? ["#B0B0B0", "#B0B0B0"] : ["#137f13", "#fd7e14"];

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
                const months = data.map(item => item.month);
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
                fetchEventData(activeEventId);
            } else {
                updateChart(0, 0, true);
            }
        },
        error: function () {
            updateChart(0, 0, true);
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
                        height: 302,
                        parentHeightOffset: 0,
                        toolbar: { show: false },
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
            if (data.success && data.eventData.length > 0) {
                const labels = data.eventData.map(entry => entry.date);
                const counts = data.eventData.map(entry => entry.count);

                new ApexCharts(chartElement, {
                    chart: {
                        type: 'line',
                        height: 250,
                        sparkline: { enabled: true }
                    },
                    series: [{
                        data: counts
                    }],
                    xaxis: {
                        categories: labels,
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    stroke: { width: 2, curve: 'smooth' },
                    colors: ['#fd7e14']
                }).render();
            }
        },
        error: function () {
            chartElement.innerHTML = "<p>Error loading data.</p>";
        }
    });
}
