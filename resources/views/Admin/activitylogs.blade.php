@include('Admin.components.head', ['title' => 'Activity Logs'])

<body>
    <script src="{{asset('./dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page">
        @include('Admin.components.header', ['active' => ''])
        @include('Admin.components.loader')

        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <div class="page-pretitle">
                            Overview
                          </div>
                          <h2 class="page-title">
                           Activity Logs
                          </h2>
                    </div>

                    <div id="table-default" class="table-responsive">
                        <table class="table" id="activityLogsTable">
                            <thead>
                                <tr>
                                    <th><button class="table-sort" data-sort="sort-status">User</button></th>
                                    <th><button class="table-sort" data-sort="sort-action">Action</button></th>
                                    <th><button class="table-sort" data-sort="sort-result">Result</button></th>
                                    <th><button class="table-sort" data-sort="sort-timestamp">Timestamp</button></th>
                                    <th><button class="table-sort" data-sort="sort-devide">Device</button></th>
                                    <th>View More Info</th>
                                </tr>
                            </thead>
                            <tbody class="table-tbody">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')


</body>

</html>
