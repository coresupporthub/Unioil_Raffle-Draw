@include('Admin.components.head', ['title' => 'Raffle Draw'])
<link rel="stylesheet" href="{{ asset('car_animation/car.css') }}">
<link rel="stylesheet" href="/css/winner.css">
<style>
    .border-right {
        border-right: 1px solid #ccc;
    }

    .card-body:fullscreen {
    width: 100vw;
    height: 100vh;
    background: inherit;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Prevent scrolling in fullscreen */
    padding: 0;
    margin: 0;
}

.card-body {
    transition: all 0.3s ease-in-out;
}



    body {
        background-color: #f8f9fa;
    }
</style>

<body>

    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'raffledraw'])
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
                                Grand Raffle Draw
                            </h2>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">
                            <div class="card">
                                <div class="card-body" id="cardbg"
                                    style="background: linear-gradient(to right, #FF7F00, #FFFFFF);">
                                    @include('Admin.components.winnermodal')
                                    <div id="colorOverlay" class="position-fixed w-100 h-100 d-none left-0 top-0" style="opacity: 0.6; left: 0"></div>

                                    <div class="row g-0 p-5 w-100">

                                        <div class="col-12 col-lg-7 col-xl-9 border-end">

                                            <div class="ms-3">
                                                <img src="{{ asset('unioil_images/unioil.png') }}" alt="Raffle Logo"
                                                    class="img-fluid rounded-circle" style="max-width: 100px;">
                                            </div>
                                            <div class="card-body p-0 d-flex justify-content-center align-items-center"
                                                style="height: 100%; max-height: 35rem;">
                                                <div class="text-center m-4">
                                                    <h1>Grand Raffle</h1>
                                                    <h1 class="display-1"></h1>

                                                    <div class="form-group mb-4">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center"
                                                            id="raffleInput" readonly
                                                            style="font-size: 1.5rem; padding: 1rem;" />
                                                    </div>

                                                    <button class="btn btn-primary btn-lg w-100" id="drawButton"
                                                        style="font-size: 1.5rem; padding: 1rem;">Start Raffle</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-5 col-xl-3 d-flex flex-column">
                                            <div id="fullscreenButton"
                                                class=" text-center position-absolute top-0 end-0 me-2 mt-2 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-maximize">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M16 4l4 0l0 4" />
                                                    <path d="M14 10l6 -6" />
                                                    <path d="M8 20l-4 0l0 -4" />
                                                    <path d="M4 20l6 -6" />
                                                    <path d="M16 20l4 0l0 -4" />
                                                    <path d="M14 14l6 6" />
                                                    <path d="M8 4l-4 0l0 4" />
                                                    <path d="M4 4l6 6" />
                                                </svg>
                                            </div>

                                            <div class="ms-4 me-4 mb-4 mt-6">
                                                <label for="selectCluster" class="form-label"> <strong> Regional Clusters </strong></label>
                                                <select name="selectCluster" id="selectCluster"
                                                    class="form-select form-select-sm rounded shadow"
                                                    onchange="SelectEntry(this)"></select>

                                            </div>

                                            <div class="card-header text-center position-relative">
                                                <h2>Winners List</h2>
                                            </div>

                                            <div class="card-body scrollable" style="height: 35rem; overflow-y: auto;">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Cluster</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="winnerList">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- car animation --}}
                                    <div class="container">
                                        <div class="car-wrapper">
                                            <div class="car-wrapper_inner">

                                                <div class="car_outter">
                                                    <div class="car">
                                                        <div class="body">
                                                            <div></div>
                                                        </div>
                                                        <div class="decos">
                                                            <div class="line-bot" style="font-size: 3px; padding-left: 5px; display: flex; justify-content:space-between"><span>coresupporthub</span><span>JPRheyanTishaHazel</span></div>
                                                            <div class="door">
                                                                <div class="handle"></div>
                                                                <div class="bottom"></div>
                                                            </div>
                                                            <div class="window"></div>
                                                            <div class="light"></div>
                                                            <div class="light-front"></div>
                                                            <div class="antenna"></div>
                                                            <div class="ice-cream">
                                                                <img class=""
                                                                    src="{{ asset('unioil_images/unioil.png') }}"
                                                                    alt="Ice cream cone">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="wheel"></div>
                                                            <div class="wheel"></div>
                                                        </div>
                                                        <div class="wind">
                                                            <div class="p p1"></div>
                                                            <div class="p p2"></div>
                                                            <div class="p p3"></div>
                                                            <div class="p p4"></div>
                                                            <div class="p p5"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="background-stuff">
                                            <div class="bg"></div>
                                            <div class="bg bg-2"></div>
                                            <div class="bg bg-3"></div>
                                            <div class="ground"></div>
                                        </div>

                                    </div>
                                    {{-- car end --}}

                                     {{-- Modals --}}
                                    <div class="modal modal-blur fade" id="RedrawPrompt" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="modal-status bg-danger"></div>
                                                <div class="modal-body text-center py-4">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                                                        <path d="M12 9v4" />
                                                        <path d="M12 17h.01" />
                                                    </svg>
                                                    <h3>Redraw Not Permitted</h3>
                                                    <div class="text-secondary">A winner has already been declared for this regional cluster.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{-- Modals --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="/js/misc/raffledraw.js"> </script>


@include('Admin.components.scripts', ['loc'=> 'admin'])
    <script src="{{ asset('js/raffledraw/raffledraw.js') }}"></script>
    <script src="/js/confetti.js"></script>

</body>

</html>
