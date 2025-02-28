<div class="w-100">
    <div class="w-100 d-flex justify-content-end">
        <div class="d-flex gap-3 mb-4">

            <select id="chart-filter" class="form-select">
                <option value="bar">Product Purchase Breakdown (Bar Graph)</option>
                <option value="pie">Top-Selling Products Share (Pie Graph)</option>
                <option value="line">Purchase Trends Over Time (Line Graph)</option>
            </select>
        </div>
    </div>

    <div id="bar-div" class="charts">
        <div class="card">
            <div class="card-body">
              <div id="bar-charts"></div>
            </div>
          </div>
    </div>
    <div id="pie-div" class="d-none charts">
        <div class="card h-100">
            <div class="card-body">
              <div id="pie-charts"></div>
            </div>
          </div>
    </div>
    <div id="line-div" class="d-none charts">

        <div class="card">
            <div class="card-body">
              <div class="d-flex">
                <h3 class="card-title">Purchase Trend Overtime</h3>
                <div class="ms-auto">
                  <div class="dropdown">
                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item active" href="#">Last 7 days</a>
                      <a class="dropdown-item" href="#">Last 30 days</a>
                      <a class="dropdown-item" href="#">Last 3 months</a>
                    </div>
                  </div>
                </div>
              </div>
              <div id="line-charts"></div>
            </div>
          </div>

    </div>
</div>
