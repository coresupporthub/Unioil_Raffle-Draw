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
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-description">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 17h6" />
                                <path d="M9 13h6" />
                              </svg>
                           Import CSV Data
                          </button>

                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addClusterModal">
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
                                        <table class="table" id="ratailOutletTable">
                                            <thead>
                                                <tr>
                                                    <th>Cluster</th>
                                                    <th>Region</th>
                                                    <th>City</th>
                                                    <th>Outlet Name</th>
                                                    <th>Outlet Code</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


            @include('Admin.components.modal.retailoutletmodal')

            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')
    <script src="{{asset('js/retailoutlet_js/retailoutlet.js')}}"></script>

</body>

</html>
