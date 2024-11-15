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

                            <div class="col-md-6 col-lg-3">
                                <div class="card card-link-rotate" onclick="window.location.href='/raffle/events/results'">
                                    <div class="ribbon bg-success">Ongoing</div>
                                    <div class="card-body">
                                        <h3 class="card-title">Raffle Name</h3>
                                        <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit. Architecto at consectetur culpa ducimus eum fuga fugiat, ipsa iusto,
                                            modi nostrum recusandae reiciendis saepe.</p>
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

</body>

</html>
