@include('Admin.components.head', ['title' => 'UniOil Raffle Draw'])

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
                  {{-- graph chart --}}
                    <div class="row m-2">

                        <div class="col-lg-12 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="chart-completion-tasks-10"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-2">
                        {{-- DONUT CHART --}}
                        <div class="col-lg-4 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div id="chart-demo-pie"></div>
                                </div>
                            </div>
                        </div>

                        {{-- BAR CHART --}}
                        <div class="col-lg-8 col-xl-8">
                            <div class="card">
                                <div class="card-body">
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
