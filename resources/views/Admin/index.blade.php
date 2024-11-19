@include('Admin.components.head', ['title' => 'UniOil Raffle Draw'])

<style>
    .form-select:focus {
        border-color: #ff2600;
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
        background-color: #e7f1ff;
    }
</style>

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>

    <div class="page">

        @include('Admin.components.header', ['active' => 'dashboard'])

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
                        <div class="col-auto ms-auto d-print-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">

                    <div class="row m-2">

                        {{-- DONUT CHART --}}
                        <div class="col-lg-4 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        use App\Models\Event;
                                        $events = Event::all();
                                    @endphp

                                    <div class="form-group" style="width: 140px">
                                        <select class="form-select form-select-md border-primary" style="color: #ff3300"
                                            aria-label="Select Event" id="event-dropdown"
                                            onchange="fetchEventData(this.value)">
                                            <option selected disabled value="">Select Event</option>
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

                                    <h3 class="text-center"> Product Type Breakdown </h3>
                                    <div id="chart-demo-pie"></div>
                                </div>
                            </div>
                        </div>

                        {{-- BAR GRAPH --}}
                        <div class="col-lg-8 col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        $events = Event::all();
                                    @endphp

                                    <div class="form-group" style="width: auto">
                                        <select class="form-select form-select-md border-primary" style="color: #ff3300"
                                            aria-label="Select Event" id="event-dropdown"
                                            onchange="fetchEventData(this.value)">
                                            <option selected disabled value="">Select Event</option>
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

                                    <h3 class="text-center"> Raffle Entries Issued by Product Type </h3>
                                    <div id="chart-tasks-overview1"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-2">
                        {{-- BAR GRAPH --}}
                        <div class="col-lg-7 col-xl-7">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        $events = Event::all();
                                    @endphp

                                    <div class="form-group" style="width: 140px">
                                        <select class="form-select form-select-md border-primary" style="color: #ff3300"
                                            aria-label="Select Event">

                                            <option selected disabled value="">Select Event</option>
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

                                    <h3 class="text-center"> Raffle Entry Issuance Over Time </h3>
                                    <div id="chart-completion-tasks-10"></div>
                                </div>
                            </div>
                        </div>

                        {{-- BAR GRAPH --}}
                        <div class="col-lg-5 col-xl-5">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        $events = Event::all();
                                    @endphp

                                    <div class="form-group" style="width: 140px">
                                        <select class="form-select form-select-md border-primary" style="color: #ff3300"
                                            aria-label="Select Event">

                                            <option selected disabled value="">Select Event</option>
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

                                    <h3 class="text-center"> Regional Cluster Raffle Participation</h3>
                                    <div id="chart-tasks-overview"></div>
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

    @include('Admin.components.scripts')


    <script src="/js/analytics.js"></script>


</body>

</html>
