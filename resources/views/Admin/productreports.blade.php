@include('Admin.components.head', ['title' => 'Product Reports'])

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<style nonce="{{ csp_nonce() }}">
    .avatar {
       width:100%;
    }

    .min-height {
        height: 65vh;
        overflow-y: auto;
    }

    .min-height-40{
        height: 40vh;
    }

    .charts{
        height: 40vh;
    }
    .productLoader {
        width: 50px;
        aspect-ratio: 1;
        display: grid;
        border: 4px solid #0000;
        border-radius: 50%;
        border-right-color: #f76707;
        animation: l15 1s infinite linear;
    }

    .productLoader::before,
    .productLoader::after {
        content: "";
        grid-area: 1/1;
        margin: 2px;
        border: inherit;
        border-radius: 50%;
        animation: l15 2s infinite;
    }

    .cropper-container {
        width: 100% !important;
        height: 100% !important;
        display: flex;
        justify-content: center;
        align-items: center;
}


#image {
    display: block;
    max-width: 100%;
    max-height: 100%;
    width: 100% !important; /* Force full width */
    height: 100% !important; /* Force full height */
    object-fit: cover; /* Ensures full coverage */
}
}

    .productLoader::after {
        margin: 8px;
        animation-duration: 3s;
    }

    .skeleton {
      background-color: #eee;
      position: relative;
      overflow: hidden;
    }
    /* The moving gradient overlay */
    .skeleton::after {
      content: "";
      position: absolute;
      top: 0;
      left: -150px;
      width: 150px;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      animation: loading 1s infinite;
    }
    @keyframes loading {
      0% {
        left: -150px;
      }
      100% {
        left: 100%;
      }
    }
    .skeleton-text {
      height: 25px;
      margin-bottom: 8px;
      border-radius: 4px;
    }


    @keyframes l15 {
        100% {
            transform: rotate(1turn)
        }
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
                                <button class="btn btn-info d-none d-sm-inline-block" id="show-archive" data-bs-toggle="modal"
                                    data-bs-target="#archive">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-folders"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" /><path d="M17 16v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2" /></svg>
                                    Archived Products
                                </button>

                                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#add-product">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
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
                          <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                              <a href="#tab-product-list" class="nav-link active" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-list"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l11 0" /><path d="M9 12l11 0" /><path d="M9 18l11 0" /><path d="M5 6l0 .01" /><path d="M5 12l0 .01" /><path d="M5 18l0 .01" /></svg>
                                All Products</a>
                            </li>
                            <li class="nav-item" role="presentation">
                              <a href="#tab-chart" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar-popular"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20h14" /></svg>
                                Chart Reports</a>
                            </li>
                            <li class="nav-item" role="presentation">
                              <a href="#tab-purchased-list" class="nav-link " data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/activity -->
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-border-all"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M4 12l16 0" /><path d="M12 4l0 16" /></svg>
                                Purchase List</a>
                            </li>
                          </ul>
                        </div>
                        <div class="card-body">
                          <div class="tab-content">
                            <div class="tab-pane active" id="tab-product-list" role="tabpanel">
                                <div class="card">
                                    <div class="card-header justify-content-between">
                                        <h3 class="card-title">Product List (Total Purchased: <strong id="totalPurchased"></strong>)</h3>
                                        <div class="w-25">
                                            <input id="searchProduct" type="text" class="form-control"
                                                placeholder="Search for products">
                                        </div>
                                    </div>
                                    <div class="list-group list-group-flush list-group-hoverable min-height" id="prod_list">
                                        <!--All Products-->
                                        <div id="productLoader"
                                            class="w-100 h-100 d-flex flex-column gap-2 justify-content-center align-items-center">
                                            <p>Loading Products</p>
                                            <div class="productLoader"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="tab-chart" role="tabpanel">
                                @include('Admin.components.products.chart')
                            </div>
                            <div class="tab-pane show" id="tab-purchased-list" role="tabpanel">
                                @include('Admin.components.products.table')
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
    @include('Admin.components.scripts', ['loc' => 'admin'])
     <script nonce="{{ csp_nonce() }}">
        let cspNonce = "{{ csp_nonce() }}";
    </script>
    <script src="{{ asset('js/productreport/productreport.js') }}"></script>
</body>

</html>
