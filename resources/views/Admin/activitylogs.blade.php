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

    <div class="modal modal-blur fade" id="logDetails" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Activity Log Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2>Log Info</h2>
                <p class="mb-1"><strong>User Name: </strong><span id="logName">Loading...</span></p>
                <p class="mb-1"><strong>Action: </strong><span id="logAction">Loading...</span></p>
                <p class="mb-1"><strong>Result: </strong><span id="logResult">Loading...</span></p>
                <p class="mb-1"><strong>Device: </strong><span id="logDevice">Loading...</span></p>
                <p class="mb-1"><strong>Timestamp: </strong><span id="logTimestamp">Loading...</span></p>
                <hr class="my-2">
                <h2>Request Info</h2>
                <div>
                  <pre>Api Calls&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="logApiCall">Loading...</span><br>Page Route&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="logPageRoute">Loading...</span><br>Request Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="logRequestType">Loading...</span><br>Session ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="logSessionID">Loading...</span><br>Sent Data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="logSentData">Loading...</span><br>Response Data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="logResponseData">Loading...</span></pre>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>

            </div>
          </div>
        </div>
      </div>

    @include('Admin.components.scripts')
    <script src="/js/activitylogs.js"></script>

</body>

</html>
