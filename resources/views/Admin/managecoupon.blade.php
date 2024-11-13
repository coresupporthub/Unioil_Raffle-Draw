@include('Admin.components.head', ['title' => 'Coupon Management'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'managecoupon'])

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
                        Coupon Management
                      </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                      <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-simple">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-report"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" /><path d="M18 14v4h4" /><path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" /><path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M8 11h4" /><path d="M8 15h3" /></svg>                          Generate Report
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
                                                    <th><button class="table-sort" data-sort="sort-coupon">Coupon
                                                            No.</button></th>
                                                    <th><button class="table-sort" data-sort="sort-customer">Customer
                                                            Name</button></th>
                                                    <th><button class="table-sort" data-sort="sort-product">Product
                                                            Type</button></th>
                                                    <th><button class="table-sort"
                                                            data-sort="sort-status">Status</button></th>
                                                    <th><button class="table-sort" data-sort="sort-issued">Issued
                                                            Date</button></th>
                                                    <th><button class="table-sort" data-sort="sort-expiry">Expiry
                                                            Date</button></th>
                                                    <th><button class="table-sort"
                                                            data-sort="sort-redemptions">Redemptions</button></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">
                                                <tr>
                                                    <td class="sort-coupon">C123456</td>
                                                    <td class="sort-customer">Juan Dela Cruz</td>
                                                    <td class="sort-product">Fully Synthetic Oil</td>
                                                    <td class="sort-status">Active</td>
                                                    <td class="sort-issued" data-date="1698534000">October 28, 2023</td>
                                                    <td class="sort-expiry" data-date="1714582800">October 28, 2024</td>
                                                    <td class="sort-redemptions">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="sort-coupon">C654321</td>
                                                    <td class="sort-customer">Maria Santos</td>
                                                    <td class="sort-product">Semi-Synthetic Oil</td>
                                                    <td class="sort-status">Redeemed</td>
                                                    <td class="sort-issued" data-date="1697355600">October 15, 2023</td>
                                                    <td class="sort-expiry" data-date="1713404400">October 15, 2024</td>
                                                    <td class="sort-redemptions">1</td>
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
