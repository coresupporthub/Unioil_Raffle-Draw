let chartInstance; 

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
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(`Error fetching event data: ${textStatus}`, errorThrown);

            updateChart(0, 0, true); 
        }
    });
}

function updateChart(semiSynthetic, fullySynthetic, noData = false) {
    if (chartInstance) {
        const chartData = noData ? [0, 0] : [semiSynthetic, fullySynthetic];
        const chartColors = noData ? ["#B0B0B0", "#B0B0B0"] : ["#137f13", "#fd7e14"]; 

        chartInstance.updateSeries(chartData);
        chartInstance.updateOptions({
            labels: noData ? ["No Event Data", ""] : ["Semi Synthetic", "Fully Synthetic"],
            tooltip: {
                enabled: !noData, 
            },
            colors: chartColors, 
            plotOptions: {
                pie: {
                    donut: {
                        size: "70%", 
                    }
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.ApexCharts) {

        chartInstance = new ApexCharts(document.getElementById('chart-demo-pie'), {
            chart: {
                type: "donut",
                fontFamily: "inherit",
                height: 340,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: false
                },
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
            series: [0, 0], 
            labels: ["No Event Data", ""], 
            tooltip: {
                theme: "dark",
                enabled: false, 
            },
            grid: {
                strokeDashArray: 4,
            },
            colors: ["#B0B0B0", "#B0B0B0"], 
            legend: {
                show: true,
                position: "bottom",
                offsetY: 12,
                markers: {
                    width: 15,
                    height: 15,
                    radius: 100,
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 8,
                },
            },
        });

        chartInstance.render();

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

        document.getElementById('event-dropdown').addEventListener('change', function () {
            const eventId = this.value;
            fetchEventData(eventId); 
        });
    }
});


//   Raffle Entries issued by Product Type
document.addEventListener("DOMContentLoaded", function () {
    // Bar Chart for Fully Synthetic and Semi Synthetic Raffle Entries Over Time
    window.ApexCharts && (new ApexCharts(document.getElementById('chart-tasks-overview1'), {
        chart: {
            type: "bar",
            fontFamily: 'inherit',
            height: 302,
            parentHeightOffset: 0,
            toolbar: {
                show: false,
            },
            animations: {
                enabled: false,
            },
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
                dataLabels: {
                    position: 'top', 
                },
            },
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            type: "solid",
        },
        series: [
            {
                name: "Fully Synthetic",
                data: [120, 150, 170, 140, 180, 190, 220, 200, 210, 230, 240, 260], 
            },
            {
                name: "Semi Synthetic",
                data: [80, 100, 90, 120, 110, 130, 140, 150, 160, 170, 180, 200], 
            },
        ],
        tooltip: {
            theme: 'dark',
        },
        grid: {
            padding: {
                top: -20,
                right: 0,
                left: -4,
                bottom: -4,
            },
            strokeDashArray: 4,
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false,
            },
            axisBorder: {
                show: false,
            },
            categories: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December',
            ],
        },
        yaxis: {
            labels: {
                padding: 4,
            },
        },
        colors: ['#137f13', '#fd7e14'],
        legend: {
            show: true,
            position: 'top',
        },
    })).render();
});



//   Regional Cluster Raffle Participation
  document.addEventListener("DOMContentLoaded", function() {
      // Bar Chart
      window.ApexCharts && (new ApexCharts(document.getElementById('chart-tasks-overview'), {
          chart: {
              type: "bar",
              fontFamily: 'inherit',
              height: 302,
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
                  columnWidth: '70%',
              }
          },
          dataLabels: {
              enabled: false,
          },
          fill: {
              type: "solid",
          },
          series: [{
              name: "Raffle Entries",
              data: [44, 32, 48, 72, 60]
          }],
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
              categories: ['Cluster 1', 'Cluster 2', 'Cluster 3', 'Cluster 4', 'Cluster 5'],
          },
          yaxis: {
              labels: {
                  padding: 4
              },
          },
          colors: ['#137f13'],
          legend: {
              show: false,
          },
      })).render();
  });

  document.addEventListener("DOMContentLoaded", function() {
    const eventSelect = document.querySelector("select"); 
    const chartElement = document.getElementById('chart-completion-tasks-10');
    
    function fetchEventDataarea(eventId) {

        const url = `/api/entry/issuance/${eventId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const eventData = data.eventData;
                    
                    const labels = eventData.map(entry => entry.date); 
                    const counts = eventData.map(entry => entry.count); 

                    new ApexCharts(chartElement, {
                        chart: {
                            type: "area",
                            fontFamily: 'inherit',
                            height: 302,
                            parentHeightOffset: 0,
                            toolbar: {
                                show: false,
                            },
                            animations: {
                                enabled: false
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                shade: 'dark',
                                type: "vertical",
                                shadeIntensity: 0.7,
                                gradientToColors: ['#fd7e14', '#fd7e14'],
                                inverseColors: false,
                                opacityFrom: 0.8,
                                opacityTo: 0.3,
                                stops: [0, 100],
                            },
                        },
                        stroke: {
                            width: 2,
                            lineCap: "round",
                            curve: "smooth",
                        },
                        series: [{
                            name: "Raffle Entries",
                            data: counts 
                        }],
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
                            categories: labels, 
                        },
                        yaxis: {
                            labels: {
                                padding: 4
                            },
                        },
                        colors: ['#fd7e14'],
                        legend: {
                            show: false,
                        },
                    }).render();
                } else {
                    alert('No data available for the selected event.');
                }
            })
            .catch(error => {
                console.error('Error fetching event data:', error);
            });
    }

    eventSelect.addEventListener('change', function() {
        const eventId = eventSelect.value;
        if (eventId) {
            fetchEventDataarea(eventId);  
        }
    });

    fetchEventDataarea('active'); 
});
