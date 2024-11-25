@include('Admin.components.head', ['title' => 'Raffle Event Results'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'raffleevents'])
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
                                <div class=" hide-me">
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
                            <div class="card shadow mb-4">
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
                                                    <th class="hide-me">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="winnerListTable">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow">
                                <div class="card-header text-white d-flex justify-content-center align-items-center"
                                    style="background-color: #f70404;">
                                    <h2 class="mb-0">Redrawn Winners (Unconfirmed Prizes)</h2>
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
                                                </tr>
                                            </thead>
                                            <tbody id="unclaim-table">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Back to Raffles Button -->
                            <div class="text-center mt-5">
                                <a href="/raffle/draw" class="btn btn-primary hide-me">← Back to Raffle Page                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    <div class="modal modal-blur fade" id="update-event-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header Unioil-header">
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
            <button type="button" class="btn btn-outline-dark me-auto" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" id="inactiveBtn" data-bs-target="#confirmInactive">Set Inactive</button>
            <button type="button" class="btn btn-primary" onclick=" SubmitData('update-event-form', '/api/update-event')">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal modal-blur fade" id="confirmInactive" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Authentication Needed</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="mb-1">Please provide the the current administrators login password to verify this action!</p>
              <small class="text-danger">Note! This action is irreversible </small>

              <form class="form-group mt-4" id="confirmInactiveForm">
                @csrf
                <input type="hidden" id="event_idInactive" name="event_id">
                <label for="adminPassword">Admin Password</label>
                <input type="password" id="adminPassword" name="password" required class="form-control" placeholder="Enter your admin password here">
                <small class="text-danger d-none" id="adminPasswordE">This is a required field</small>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn me-auto" id="closeAdminPasswordModal" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="confirmInactiveBtn">Proceed</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header Unioil-header">
                    <h5 class="modal-title text-white" id="staticBackdropLabel">Winner Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Regional Cluster -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="region">Regional Cluster</label>
                                <p id="regiondisplay"></p>
                            </div>
                        </div>
                        <!-- Area -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="area">Area</label>
                                <p id="area"></p>
                            </div>
                        </div>
                        <!-- Address -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="address">Address</label>
                                <p id="address"></p>
                            </div>
                        </div>
                        <!-- Distributor -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="distributor">Distributor</label>
                                <p id="distributor"></p>
                            </div>
                        </div>
                        <!-- Retail Store -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="store">Retail Store</label>
                                <p id="store"></p>
                            </div>
                        </div>
                        <!-- Store Code -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="rto">Rto Code</label>
                                <p id="rto"></p>
                            </div>
                        </div>

                        <hr>

                        <!-- Full Name -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="name">Full Name</label>
                                <p id="name"></p>
                            </div>
                        </div>
                        <!-- Age -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="age">Age</label>
                                <p id="age"></p>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="email">Email</label>
                                <p id="email"></p>
                            </div>
                        </div>
                        <!-- Phone Number -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="phone">Phone Number</label>
                                <p id="phone"></p>
                            </div>
                        </div>

                        <!-- Coupon -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="coupon">Coupon</label>
                                <p class="text-warning" id="coupon"></p>
                            </div>
                        </div>
                        <!-- Product -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold" for="product">Product</label>
                                <p id="product"></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>

        @include('Admin.components.footer')
        @include('Admin.components.loader')
    </div>
    </div>
    @include('Admin.components.scripts', ['loc'=> 'admin'])
    <script src="{{asset('js/raffleresult/raffleresultlist.js')}}"></script>
</body>

</html>
