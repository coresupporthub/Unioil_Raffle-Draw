
  document.addEventListener("DOMContentLoaded", function() {
      // Donut Chart
      window.ApexCharts && (new ApexCharts(document.getElementById('chart-demo-pie'), {
          chart: {
              type: "donut",
              fontFamily: 'inherit',
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
                  shade: 'dark',
                  type: "diagonal1",
                  shadeIntensity: 0.7,
                  gradientToColors: ['#137f13', '#fd7e14'],
                  inverseColors: false,
                  opacityFrom: 1,
                  opacityTo: 0.9,
                  stops: [0, 100],
              },
          },
          series: [44, 55], 
          labels: ["Semi Synthetic",
          "Fully Synthetic"], 
          tooltip: {
              theme: 'dark'
          },
          grid: {
              strokeDashArray: 4,
          },
          colors: ['#137f13', '#fd7e14'], 
          legend: {
              show: true,
              position: 'bottom',
              offsetY: 12,
              markers: {
                  width: 15,
                  height: 15,
                  radius: 100,
              },
              itemMargin: {
                  horizontal: 8,
                  vertical: 8
              },
          },
          tooltip: {
              fillSeriesColor: false
          },
      })).render();
  });


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
              type: "gradient",
              gradient: {
                  shade: 'dark',
                  type: "vertical",
                  shadeIntensity: 0.7,
                  gradientToColors: ['#fd7e14'],
                  inverseColors: true,
                  opacityFrom: 1,
                  opacityTo: 0.8,
                  stops: [0, 100],
              },
          },
          series: [{
              name: "Cluster Data",
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
      // Area Chart
      window.ApexCharts && (new ApexCharts(document.getElementById('chart-completion-tasks-10'), {
          chart: {
              type: "area",
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
          dataLabels: {
              enabled: false,
          },
          fill: {
              type: "gradient",
              gradient: {
                  shade: 'dark',
                  type: "vertical",
                  shadeIntensity: 0.7,
                  gradientToColors: ['#137f13', '#fd7e14'],
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
              name: "Series 1",
              data: [155, 65, 465, 265, 225, 325, 80]
          }, {
              name: "Series 2",
              data: [113, 42, 65, 54, 76, 65, 35]
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
              type: 'datetime',
          },
          yaxis: {
              labels: {
                  padding: 4
              },
          },
          labels: [
              '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24',
              '2020-06-25', '2020-06-26'
          ],
          colors: ['#137f13', '#fd7e14'],
          legend: {
              show: false,
          },
      })).render();
  });
