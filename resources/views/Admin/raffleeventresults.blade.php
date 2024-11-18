@include('Admin.components.head', ['title' => 'Raffle Event Results'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'raffleevents'])

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
                                Raffle Events & Results
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a class="btn btn-primary d-none d-sm-inline-block" href="/raffle/events/results/print?event={{ $_GET['event'] }}" target="_blank">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-report">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                        <path d="M18 14v4h4" />
                                        <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                                        <path
                                            d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M8 11h4" />
                                        <path d="M8 15h3" />
                                    </svg> Generate Report
                                </a>

                                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#update-event-modal">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-report">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                        <path d="M18 14v4h4" />
                                        <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                                        <path
                                            d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M8 11h4" />
                                        <path d="M8 15h3" />
                                    </svg> Update Raffle Event
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

                            <!-- Raffle Draw Details -->
                            <div class="text-center mb-5">
                                 
                                <h1 class="fw-bold display-6 position-relative"
                                    style="color: #f75a04; padding-left: 20px; font-family: 'Poppins', sans-serif;">
                                    <span
                                        style=" margin-right: 10px; padding-left: 10px;" id="title_event_name">
                                        
                                    </span>
                                </h1>
                                <p class="text-secondary fs-5">Start Date: <strong id="title_start"> </strong> - End Date: <strong id="title_end"> </strong></p>
                            </div>

                            <!-- Winners Table -->
                            <div class="card shadow">
                                <div class="card-header text-white d-flex justify-content-center align-items-center"
                                    style="background-color: #f75a04;">
                                    <h2 class="mb-0">🎉 Winners List 🎉</h2>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Coupon</th>
                                                    <th>Cluster</th>
                                                    <th>Prize</th>
                                                    <th>Winner Name</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="winnerListTable">
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Back to Raffles Button -->
                            <div class="text-center mt-5">
                                <a href="/raffle/draw" class="btn btn-primary">← Back to Raffles</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    <div class="modal modal-blur fade" id="update-event-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Event Raffle</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
                <form action="" method="post" id="update-event-form" class="row g-3">
                <!-- Event Name -->
                <div class="col-md-6 col-12">
                    <input type="text" name="event_id" id="event_id" class="form-control" placeholder="Event ID" readonly hidden>
                    <label for="event_name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" name="event_name" id="event_name" placeholder="Enter event name">
                </div>

                <!-- Event Price -->
                <div class="col-md-6 col-12">
                    <label for="event_price" class="form-label">Event Prize</label>
                    <input type="text" class="form-control" name="event_price" id="event_price" placeholder="Enter event price">
                </div>

                <!-- Event Start -->
                <div class="col-md-6 col-12">
                    <label for="event_start" class="form-label">Event Start</label>
                    <input type="date" class="form-control" name="event_start" id="event_start">
                </div>

                <!-- Event End -->
                <div class="col-md-6 col-12">
                    <label for="event_end" class="form-label">Event End</label>
                    <input type="date" class="form-control" name="event_end" id="event_end">
                </div>

                <!-- Event Description -->
                <div class="col-12">
                    <label for="event_description" class="form-label">Event Description</label>
                    <textarea class="form-control" name="event_description" id="event_description" rows="4" placeholder="Enter event description"></textarea>
                </div>

            </form>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="confirmation()">Set Incative</button>
            <button type="button" class="btn btn-primary" onclick=" SubmitData('update-event-form', '/api/update-event')">Save changes</button>
          </div>
        </div>
      </div>
    </div>


        @include('Admin.components.footer')
        @include('Admin.components.loader')
    </div>
    </div>

    @include('Admin.components.scripts')
    <script src="{{asset('js/raffleresult/raffleresultlist.js')}}"></script>
</body>

</html>
