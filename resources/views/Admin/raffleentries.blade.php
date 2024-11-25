@include('Admin.components.head', ['title' => 'Raffle Entries'])
<style>
    #entryTable tbody tr:hover {
        cursor: pointer;
        /* Change the cursor to a pointer */
        background-color: #fcbc9e;
        /* Highlight the row with a light gray color */
    }

</style>
<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'raffleentries'])
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
                                    <form action="" id="searchEntry" method="post">
                                        @csrf
                                        <div class="card mb-2">
                                            <div class="row p-2 Unioil-header">
                                                <div class="col-6 mb-3">
                                                    <h4 class="mb-2 ms-2 text-white" for="">Raffle Events </h4>
                                                    <select class="form-select" name="event_id" id="event_id" onchange=" GetAllEntry()">
                                                        <option value="" selected> All Raffle Event </option>
                                                        @php
                                                        $events = App\Models\Event::all();
                                                        @endphp
                                                        @foreach ($events as $event)
                                                        <option value="{{$event->event_id}}"> {{$event->event_name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-6 mb-3">
                                                    <h4 class="mb-2 ms-2 text-white" for=""> Regional Cluster </h4>
                                                    <select class="form-select" name="region" id="region" onchange=" GetAllEntry()">
                                                        <option value="" selected> All Cluster </option>
                                                        @php
                                                        $regions = App\Models\RegionalCluster::all();
                                                        @endphp
                                                        @foreach ($regions as $region)
                                                        <option value="{{$region->cluster_id}}"> {{$region->cluster_name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                    <div id="table-default" class="table-responsive">
                                        <table class="table" id="entryTable">
                                            <thead>
                                                <tr>
                                                    <th>Regional Cluster</th>
                                                    <th>Store Name</th>
                                                    <th>Coupon</th>
                                                    <th>Product Purchased</th>
                                                    <th>Entry Name</th>
                                                    <th>Email</th>
                                                    <th>Phone Number</th>
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

            <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header Unioil-header">
                            <h5 class="modal-title text-white" id="staticBackdropLabel">Entry Details</h5>
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

        </div>
    </div>
    @include('Admin.components.scripts', ['loc'=> 'admin'])
    <script src="{{ asset('js/raffleentry/raffleentry.js') }}"></script>
</body>
</html>
