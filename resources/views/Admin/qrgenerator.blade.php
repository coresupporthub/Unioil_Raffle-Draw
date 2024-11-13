@include('Admin.components.head', ['title' => 'QR Generator'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header')

        <div class="page-wrapper">
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
                                                <button class="btn btn-secondary" onclick="()">
                                                    Generate QR</button>
                                                <button class="btn btn-primary" onclick="()">Download
                                                    PDF</button>
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
                                                <button class="btn btn-secondary" onclick="()">Generate QR</button>
                                                <button class="btn btn-primary" onclick="()">Download PDF</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')


</body>

</html>
