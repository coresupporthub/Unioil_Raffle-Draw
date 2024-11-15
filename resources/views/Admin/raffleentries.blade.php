@include('Admin.components.head', ['title' => 'Raffle Entries'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'raffleentries'])

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
                        Raffle Entries
                      </h2>
                    </div>
                    <!-- Page title actions -->
                   
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
                                        <table class="table" id="entryTable">
                                            <thead>
                                                <tr>
                                                    <th>Regional Cluster</th>
                                                    <th>Store Name</th>
                                                    <th>Coupon</th>
                                                    <th>Product Type</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Number</th>
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

            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')
    <script src="{{ asset('js/raffleentry/raffleentry.js') }}"></script>
</body>
</html>
