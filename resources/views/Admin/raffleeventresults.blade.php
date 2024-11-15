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
                                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#modal-simple">
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
                                 @php
                                    $event = App\Models\Event::where('event_id',$_GET['event'])->first();
                                @endphp
                                <h1 class="fw-bold display-6 position-relative"
                                    style="color: #f75a04; padding-left: 20px; font-family: 'Poppins', sans-serif;">
                                    <span
                                        style=" margin-right: 10px; padding-left: 10px;">
                                        {{ $event->event_name }}
                                    </span>
                                </h1>
                                <p class="text-secondary fs-5">Start Date: <strong>{{$event->event_start}}</strong> - End Date: <strong>{{$event->event_end}}</strong></p>
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
                                                    <th>#</th>
                                                    <th>Cluster</th>
                                                    <th>Winner Name</th>
                                                    <th>Prize</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Region I</td>
                                                    <td>John Doe</td>
                                                    <td>$500 Gift Card</td>
                                                    <td>john.doe@example.com</td>
                                                    <td><button class="btn btn-indigo"> Redraw </button></td>

                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Region II</td>
                                                    <td>Jane Smith</td>
                                                    <td>Bluetooth Headphones</td>
                                                    <td>jane.smith@example.com</td>
                                                    <td><button class="btn btn-indigo"> Redraw </button></td>
                                                </tr>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Back to Raffles Button -->
                            <div class="text-center mt-5">
                                <a href="/raffle/events" class="btn btn-primary">← Back to Raffles</a>
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

</body>

</html>
