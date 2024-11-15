@include('Admin.components.head', ['title' => 'Raffle Events'])

<style>
    .card-link-rotate:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

</style>

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
                    </div>


                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">
                            @php
                                $raffleEvents = \App\Models\Event::all();
                            @endphp
                            @foreach ($raffleEvents as $event)
                            <div class="col-md-6 col-lg-3">
                                <div class="card card-link-rotate" onclick="window.location.href='/raffle/events/results?event={{$event->event_id}}'">
                                    @if ($event->event_status =='Active')
                                        <div class="ribbon bg-success">Ongoing</div>
                                    @else
                                        <div class="ribbon bg-danger">Ended</div>
                                    @endif
                                    <div class="card-body">
                                        <h3 class="card-title">{{$event->event_name}}</h3>
                                        <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit. Architecto at consectetur culpa ducimus eum fuga fugiat, ipsa iusto,
                                            modi nostrum recusandae reiciendis saepe.</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach

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
