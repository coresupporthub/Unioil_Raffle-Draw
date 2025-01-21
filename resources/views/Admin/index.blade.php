@include('Admin.components.head', ['title' => 'UniOil Raffle Draw'])

<style>
    .form-select:focus {
        border-color: #ffffff;
        box-shadow: 0 0 0 0.25rem rgba(255, 136, 38, 0.25);
    }

    .form-select option {
        color: #fd7e14;
    }

    .form-select option:checked {
        background-color: #fd7e14;
        color: white;
    }

    .form-select option:hover {
        background-color: #ffffff;
    }
</style>

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>

    <div class="page">
        
        @include('Admin.components.header', ['active' => 'dashboard'])
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
                                Analytics Dashboard
                            </h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none me-3">
                            <div class="card-body text-center">
                                <h4 class="fw-bold mb-1">Select Event to View Insights</h4>
                                @php
                                    use App\Models\Event;
                                    $events = Event::all();
                                @endphp
                                <div class="form-group mx-auto" style="max-width: 400px;">
                                    <select class="form-select fw-semibold" style="border-color:#fd7e14; #fd7e14;"
                                        id="event-dropdown">
                                        <option selected disabled value="">Choose an Event</option>
                                        @if ($events->isEmpty())
                                            <option value="#" disabled>No events available</option>
                                        @else
                                            @foreach ($events as $event)
                                                <option value="{{ $event->event_id }}">{{ $event->event_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">

                    <div class="row m-2">

                        <div class="col-lg-12 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center">Entries by Product Type Across Regional Clusters</h3>

                                    <div id="chart-combination"></div>
                                    <div id="total-entries"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-2">
                        {{-- DONUT CHART --}}
                        <div class="col-lg-4 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center">Product Type Breakdown</h3>
                                    <div id="chart-demo-pie"></div>
                                </div>
                            </div>
                        </div>

                        {{-- BAR GRAPH --}}
                        <div class="col-lg-8 col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center">Regional Cluster Raffle Participation</h3>
                                    <div id="chart-tasks-overview"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-2">


                        {{-- REGIONAL CLUSTER PARTICIPATION --}}
                        <div class="col-lg-5 col-xl-5">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center">Raffle Entries Issued by Product Type</h3>
                                    <div id="chart-tasks-overview1"></div>
                                </div>
                            </div>
                        </div>

                        {{-- AREA CHART --}}
                        <div class="col-lg-7 col-xl-7">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center">Raffle Entry Issuance Over Time</h3>
                                    <div id="chart-completion-tasks-10"></div>
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
    @include('Admin.components.scripts', ['loc'=> 'admin'])

    <script src="/js/analytics.js"></script>

</body>

</html>
