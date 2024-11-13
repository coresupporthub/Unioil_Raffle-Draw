@include('Admin.components.head', ['title' => 'UniOil Raffle Draw'])

<body>
    <script src="{{asset('./dist/js/demo-theme.min.js?1692870487')}}"></script>

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
                        Dashboard
                      </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                      {{-- <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                          Create button
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        </a>
                      </div> --}}
                    </div>
                  </div>
                </div>
              </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">



                </div>
            </div>
        </div>
      
        @include('Admin.components.footer')
    </div>
    </div>

    @include('Admin.components.scripts')
</body>

</html>
