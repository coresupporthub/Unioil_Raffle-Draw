@include('Admin.components.head', ['title' => 'QR Generator'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'qrgenerator'])
        @include('Admin.components.loader')
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
                                QR Management
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none gap-2 d-flex">
                            <button id="resetTable" class="btn btn-vk btn-icon" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="Refresh Tables"
                                data-bs-original-title="Refresh Tables" aria-label="Refresh Tables">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                            </button>
                            <div class="btn-list">

                                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#generateqr">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Generate QR
                                </button>
                                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#export">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                        <path d="M7 11l5 5l5 -5" />
                                        <path d="M12 4l0 12" />
                                    </svg>
                                    Export
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <!-- Card Title aligned to the left -->
                                <h5 class="card-title mb-0">Queue List & Exported QR Code</h5>
                            </div>
                            <div id="table-default" class="table-responsive">
                                <table id="queue-progress" class="table">
                                    <thead>
                                        <tr>
                                            <th><button class="table-sort" data-sort="sort-queue_number">Queue
                                                    Number</button></th>
                                            <th><button class="table-sort" data-sort="sort-entry_type">Entry
                                                    Type</button></th>
                                            <th><button class="table-sort" data-sort="sort-status">Status</button></th>
                                            <th><button class="table-sort" data-sort="sort-progress">Progress</button>
                                            </th>

                                            <th><button class="table-sort" data-sort="sort-status">Files</button></th>
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

            <div class="page-body">
                <div class="container-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <!-- Card Title aligned to the left -->
                                <h5 class="card-title mb-0">Generated QR Codes</h5>

                                <!-- Dropdown aligned to the right -->
                                <div class="d-flex align-items-center gap-2">
                                    <label for="filterQR" class="mb-0">Filter QR Entry Type</label>
                                    <select id="filterQR" class="form-select w-50">
                                        <option value="all">All</option>
                                        <option value="Single Entry QR Code">Single Entry QR Code</option>
                                        <option value="Dual Entry QR Code">Dual Entry QR Code</option>
                                    </select>
                                </div>
                            </div>

                            <div id="table-default" class="table-responsive">
                                <table class="table" id="generatedQrTable">
                                    <thead>
                                        <tr>
                                            <th><button class="table-sort" data-sort="sort-code">Code</button></th>
                                            <th><button class="table-sort" data-sort="sort-entry_type">Entry
                                                    Type</button></th>
                                            <th><button class="table-sort" data-sort="sort-status">Status</button></th>
                                            <th><button class="table-sort" data-sort="sort-export_status">Export
                                                    Status</button></th>
                                            <th><button class="table-sort" data-sort="sort-export_status">Export
                                                    Action</button></th>
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

            <div class="modal modal-blur fade" id="generateqr" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Generate QR</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="generateform">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="numberofqr">Number of QR</label>
                                            <input type="number" class="form-control" name="numberofqr"
                                                id="numberofqr" min="1" max="15000" value="1"
                                                oninput="enforceLimit(this)">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="qrtype">QR Type</label>
                                            <select name="qrtype" id="qrtype" class="form-control">
                                                <option value="Single Entry QR Code">Single Entry QR Code</option>
                                                <option value="Dual Entry QR Code">Dual Entry QR Code</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" id="closeQrCodeGenerator" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary"
                                onclick="GenerateQrCode()" data-bs-dismiss="modal">Generate</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal modal-blur fade" id="viewQR" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View QR Code Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <img src="" id="qrCodeImage" alt="QR Code">
                                </div>

                                <div class="col-6">
                                    <h3 class="mb-2">QR Code Details</h3>
                                    <hr class="my-2">
                                    <p class="mb-1"><strong>Code: </strong> <span id="viewCode">Loading...</span></p>
                                    <p class="mb-1"><strong>Unique Identifier:</strong> <span id="viewUUID">Loading...</span></p>
                                    <p class="mb-1"><strong>Entry Type:</strong>  <span id="viewEntryType">Loading...</span></p>
                                    <p class="mb-1"><strong>Status:</strong>  <span id="viewStatus">Loading...</span></p>
                                    <hr class="my-2">
                                    <div id="entry_available" class="d-none">
                                    <p class="mb-1"><strong>Customer Name: </strong> <span id="viewCustomerName">Loading...</span></p>
                                    <p class="mb-1"><strong>Address: </strong> <span id="viewAddress">Loading...</span></p>
                                    <p class="mb-1"><strong>Email: </strong> <span id="viewEmail">Loading...</span></p>
                                    <p class="mb-1"><strong>Contact: </strong> <span id="viewContact">Loading...</span></p>
                                    <p class="mb-1"><strong>Product Purchased: </strong> <span id="viewProductPurchased">Loading...</span></p>
                                    <p class="mb-1"><strong>Serial Number 1: </strong> <span id="viewSerialNumber1">Loading...</span></p>
                                    <p class="mb-1"><strong>Serial Number 2: </strong> <span id="viewSerialNumber2">Loading...</span></p>
                                    <p class="mb-1"><strong>Registration Date:</strong> <span id="viewRegistrationDate">Loading...</span></p>
                                    <p class="mb-1"><strong>Retail Station:</strong> <span id="viewRetailStation">Loading...</span></p>
                                    <p class="mb-1"><strong>Distributor:</strong> <span id="viewDistributor">Loading...</span></p>
                                    </div>

                                    <div id="entry_unavalable" class="d-none">
                                        <h3 class="text-muted mb-1">No Entry Found</h3>
                                        <p class="mb-1 text-muted">QR is not in used</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>


            <div class="modal modal-blur fade" id="export" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Export to PDF</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form class="modal-body" id="exportQrForm">
                            <p>PDF will contain 36 qr codes per pages</p>
                            @csrf
                            <div class="mb-3">

                                <label class="form-label">How many pages?</label>
                                <input type="number" class="form-control" required name="page_number"
                                    value="1" placeholder="# of pages">
                            </div>
                            <div class="mb-3">

                                <label class="form-label">Entry Type</label>
                                <select name="qrtype" class="form-control">
                                    <option value="Single Entry QR Code">Single Entry QR Code</option>
                                    <option value="Dual Entry QR Code">Dual Entry QR Code</option>
                                </select>
                            </div>
                        </form>

                        <div class="modal-footer">
                            <button type="button" id="closeExportModal" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="exportQrBtn" class="btn btn-primary">Export</button>
                        </div>
                    </div>
                </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>
    {{-- @vite('resources/js/app.js')
    <script>
        setTimeout(() => {
            window.Echo.channel('queueingstatus').listen('QueueingStatus', (e) => {
                console.log(e);
            });
        }, 200);
    </script> --}}

    @include('Admin.components.scripts')
    <script src="{{ asset('/js/qr_code.js') }}"></script>
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
