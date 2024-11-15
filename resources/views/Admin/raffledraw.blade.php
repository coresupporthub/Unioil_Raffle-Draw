@include('Admin.components.head', ['title' => 'Raffle Draw'])
<link rel="stylesheet" href="{{asset('car_animation/car.css')}}">
<style>
    .border-right {
        border-right: 1px solid #ccc;
    }

    #cardbg:fullscreen {
        background-color: #fff;
    }


    body {
        background-color: #f8f9fa;
    }
</style>

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'raffledraw'])

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
                                <div class="card-body" id="cardbg" style="background: linear-gradient(to right, #FF7F00, #FFFFFF);
">

                                    <div class="row g-0 p-5">

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
                                                        <input type="text" class="form-control form-control-lg"
                                                            id="nameInput" style="font-size: 1.5rem; padding: 1rem;" />
                                                    </div>

                                                    <button class="btn btn-primary btn-lg w-100" id="startRaffleButton"
                                                        style="font-size: 1.5rem; padding: 1rem;">Start Raffle</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-5 col-xl-3 d-flex flex-column">
                                            <div class="card-header text-center position-relative">
                                                <h2>Winner List</h2>
                                                <div id="fullscreenButton"
                                                    class=" text-center position-absolute top-0 end-0 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
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
                                            </div>

                                            <div class="card-body scrollable" style="height: 35rem; overflow-y: auto;">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Coupon</th>
                                                            <th>Name</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1st Place</td>
                                                            <td>John Doe</td>

                                                        </tr>
                                                        <tr>
                                                            <td>2nd Place</td>
                                                            <td>Jane Smith</td>
                                                        </tr>
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
                                                <div class="line-bot"></div>
                                                <div class="door">
                                                <div class="handle"></div>
                                                <div class="bottom"></div>
                                                </div>
                                                <div class="window"></div> 
                                                <div class="light"></div>
                                                <div class="light-front"></div>
                                                <div class="antenna"></div>
                                                <div class="ice-cream" >
                                                <img class="" src="{{asset('unioil_images/unioil.png')}}" alt="Ice cream cone">
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

    <script>
        document.getElementById('fullscreenButton').addEventListener('click', function() {
            let targetDiv = document.querySelector('#cardbg'); // Select the div by id

            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                if (targetDiv.requestFullscreen) {
                    targetDiv.requestFullscreen();
                } else if (targetDiv.mozRequestFullScreen) { // Firefox
                    targetDiv.mozRequestFullScreen();
                } else if (targetDiv.webkitRequestFullscreen) { // Chrome, Safari, Opera
                    targetDiv.webkitRequestFullscreen();
                } else if (targetDiv.msRequestFullscreen) { // IE/Edge
                    targetDiv.msRequestFullscreen();
                }
            }
        });
    </script>



    @include('Admin.components.scripts')

</body>

</html>
