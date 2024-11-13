@include('Admin.components.head', ['title' => 'QR Generator'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'qrgenerator'])

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
                        QR Generator
                      </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                      <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-simple">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                          generate QR
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


            <div class="page-body">
                <div class="container-xl">
                    <div class="card">
                        <div class="card-body">
                            <div id="table-default" class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><button class="table-sort" data-sort="sort-qr_id">QR ID</button></th>
                                            <th><button class="table-sort" data-sort="sort-code">Code</button></th>
                                            <th><button class="table-sort" data-sort="sort-entry_type">Entry Type</button></th>
                                            <th><button class="table-sort" data-sort="sort-status">Status</button></th>
                                            <th><button class="table-sort" data-sort="sort-progress">Progress</button>
                                            </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-tbody">
                                        <tr>
                                            <td class="sort-qr_id">001</td>
                                            <td class="sort-code">AB1234</td>
                                            <td class="sort-entry_type">product1</td>
                                            <td class="sort-status">Active</td>
                                            <td class="sort-progress" data-progress="70">
                                                <div class="row align-items-center">
                                                    <div class="col-12 col-lg-auto">70%</div>
                                                    <div class="col">
                                                        <div class="progress" style="width: 5rem;">
                                                            <div class="progress-bar" style="width: 70%;"
                                                                role="progressbar" aria-valuenow="70" aria-valuemin="0"
                                                                aria-valuemax="100" aria-label="70% Complete">
                                                                <span class="visually-hidden">70% Complete</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="sort-qr_id">002</td>
                                            <td class="sort-code">CD5678</td>
                                            <td class="sort-entry_type">product2</td>
                                            <td class="sort-status">Inactive</td>
                                            <td class="sort-progress" data-progress="50">
                                                <div class="row align-items-center">
                                                    <div class="col-12 col-lg-auto">50%</div>
                                                    <div class="col">
                                                        <div class="progress" style="width: 5rem;">
                                                            <div class="progress-bar" style="width: 50%;"
                                                                role="progressbar" aria-valuenow="50" aria-valuemin="0"
                                                                aria-valuemax="100" aria-label="50% Complete">
                                                                <span class="visually-hidden">50% Complete</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="generateform">
                        @csrf
                    <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="numberofqr">Number of QR</label>
                            <input type="number" class="form-control" name="numberofqr" id="numberofqr" min="1" max="15000" value="1" oninput="enforceLimit(this)">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="qrtype">QR Type</label>
                            <select name="qrtype" id="qrtype" class="form-control">
                                <option value="1">Single Entry QR Code</option>
                                <option value="2">Dual Entry QR Code</option>
                            </select>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="GenerateQrCode()">Generate</button>
                </div>
                </div>
            </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')
    <script src="{{asset('qr_js/qr_code.js')}}"></script>
    <script>
    function enforceLimit(input) {
        if (input.value > 15000) {
            input.value = 15000;
        } else if (input.value < 1) {
            input.value = 1;
        }
    }
    </script>

</body>

</html>
