@include('Admin.components.head', ['title' => 'Product Reports'])

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<style  nonce="{{ csp_nonce() }}">
  .avatar{
    background-image: url('/unioil_images/unioil.png');
  }
  </style>
<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'productreports'])
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
                        Product Reports
                      </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                      <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add-product">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                          Add Product
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Product List</h3>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item">
                          <div class="row align-items-center">

                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" ></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Paweł Kuna</a>
                              <div class="d-block text-muted text-truncate mt-n1">Change deprecated html tags to text decoration classes (#29604)</div>
                            </div>
                            <div class="col-auto">
                              <button href="#" class="list-group-item-actions btn btn-info"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    View Reports
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">

                              <div class="col-auto">
                                <a href="#">
                                  <span class="avatar" style="background-image: url(./static/avatars/000m.jpg)"></span>
                                </a>
                              </div>
                              <div class="col text-truncate">
                                <a href="#" class="text-reset d-block">Paweł Kuna</a>
                                <div class="d-block text-muted text-truncate mt-n1">Change deprecated html tags to text decoration classes (#29604)</div>
                              </div>
                              <div class="col-auto">
                                <button data-bs-toggle="modal" data-bs-target="#product-reports" class="list-group-item-actions btn btn-info"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    View Reports
                              </button>
                              </div>
                            </div>
                          </div>
                          <div class="list-group-item">
                            <div class="row align-items-center">

                              <div class="col-auto">
                                <a href="#">
                                  <span class="avatar" style="background-image: url(./static/avatars/000m.jpg)"></span>
                                </a>
                              </div>
                              <div class="col text-truncate">
                                <a href="#" class="text-reset d-block">Paweł Kuna</a>
                                <div class="d-block text-muted text-truncate mt-n1">Change deprecated html tags to text decoration classes (#29604)</div>
                              </div>
                              <div class="col-auto">
                                <button data-bs-toggle="modal" data-bs-target="#product-reports" class="list-group-item-actions btn btn-info"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    View Reports
                              </button>
                              </div>
                            </div>
                          </div>
                          <div class="list-group-item">
                            <div class="row align-items-center">

                              <div class="col-auto">
                                <a href="#">
                                  <span class="avatar" style="background-image: url(./static/avatars/000m.jpg)"></span>
                                </a>
                              </div>
                              <div class="col text-truncate">
                                <a href="#" class="text-reset d-block">Paweł Kuna</a>
                                <div class="d-block text-muted text-truncate mt-n1">Change deprecated html tags to text decoration classes (#29604)</div>
                              </div>
                              <div class="col-auto">
                                <button data-bs-toggle="modal" data-bs-target="#product-reports" class="list-group-item-actions btn btn-info"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    View Reports
                              </button>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
                </div>
            </div>
            @include('Admin.components.modal.productmodal')
            @include('Admin.components.footer')

        </div>
    </div>
    @include('Admin.components.scripts', ['loc'=> 'admin'])
    <script src="{{asset('js/productreport/productreport.js')}}"></script>
</body>
</html>
