@include('Admin.components.head', ['title' => 'Retail Outlet Management'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'retailoutlets'])

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                  <div class="row g-2 align-items-center">
                    <div class="col">
                      <!-- Page pre-title -->
                      <div class="page-pretitle">
                        Overview
                      </div>
                      <h2 class="page-title">
                        Retail Outlets
                      </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                      <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-simple">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                          Add Outlet
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">

                            <div class="card">
                                <div class="card-body">
                                    <div id="table-default" class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th><button class="table-sort" data-sort="sort-name">Outlet Name</button></th>
                                                    <th><button class="table-sort" data-sort="sort-city">City</button></th>
                                                    <th><button class="table-sort" data-sort="sort-type">Store Type</button></th>
                                                    <th><button class="table-sort" data-sort="sort-score">Total Entries</button></th>
                                                    <th><button class="table-sort" data-sort="sort-date">Last Activity</button></th>
                                                    <th><button class="table-sort" data-sort="sort-quantity">QRs Issued</button></th>
                                                    <th><button class="table-sort" data-sort="sort-progress">QR Usage %</button></th>
                                               
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">
                                                <tr>
                                                    <td class="sort-name">Fuel Mart</td>
                                                    <td class="sort-city">Manila</td>
                                                    <td class="sort-type">Gas Station</td>
                                                    <td class="sort-score">152</td>
                                                    <td class="sort-date" data-date="1697355600">October 15, 2023</td>
                                                    <td class="sort-quantity">200</td>
                                                    <td class="sort-progress" data-progress="76">
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-lg-auto">76%</div>
                                                            <div class="col">
                                                                <div class="progress" style="width: 5rem">
                                                                    <div class="progress-bar" style="width: 76%" role="progressbar" aria-valuenow="76"
                                                                         aria-valuemin="0" aria-valuemax="100" aria-label="76% Complete">
                                                                        <span class="visually-hidden">76% Complete</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="sort-name">Quick Stop</td>
                                                    <td class="sort-city">Quezon City</td>
                                                    <td class="sort-type">Convenience Store</td>
                                                    <td class="sort-score">89</td>
                                                    <td class="sort-date" data-date="1695973200">September 29, 2023</td>
                                                    <td class="sort-quantity">150</td>
                                                    <td class="sort-progress" data-progress="59">
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-lg-auto">59%</div>
                                                            <div class="col">
                                                                <div class="progress" style="width: 5rem">
                                                                    <div class="progress-bar" style="width: 59%" role="progressbar" aria-valuenow="59"
                                                                         aria-valuemin="0" aria-valuemax="100" aria-label="59% Complete">
                                                                        <span class="visually-hidden">59% Complete</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="sort-name">Motor Pro</td>
                                                    <td class="sort-city">Cebu City</td>
                                                    <td class="sort-type">Motorcycle Shop</td>
                                                    <td class="sort-score">120</td>
                                                    <td class="sort-date" data-date="1698534000">October 28, 2023</td>
                                                    <td class="sort-quantity">160</td>
                                                    <td class="sort-progress" data-progress="75">
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-lg-auto">75%</div>
                                                            <div class="col">
                                                                <div class="progress" style="width: 5rem">
                                                                    <div class="progress-bar" style="width: 75%" role="progressbar" aria-valuenow="75"
                                                                         aria-valuemin="0" aria-valuemax="100" aria-label="75% Complete">
                                                                        <span class="visually-hidden">75% Complete</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const list = new List('table-default', {
                sortClass: 'table-sort',
                listClass: 'table-tbody',
                valueNames: ['sort-name', 'sort-type', 'sort-city', 'sort-score',
                    {
                        attr: 'data-date',
                        name: 'sort-date'
                    },
                    {
                        attr: 'data-progress',
                        name: 'sort-progress'
                    },
                    'sort-quantity'
                ]
            });
        })
    </script>

    @include('Admin.components.scripts')

</body>

</html>
