@include('Admin.components.head', ['title' => 'Product Reports'])
<style>
    #product-table tbody tr:hover {
        cursor: pointer;
        background-color: #fcbc9e;
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
                    {{-- <div class="col-auto ms-auto d-print-none">
                      <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-simple">
                          <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-report"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" /><path d="M18 14v4h4" /><path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" /><path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M8 11h4" /><path d="M8 15h3" /></svg>                          Generate Report
                        </button>
                      </div>
                    </div> --}}
                  </div>
                </div>
              </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">

                            <div class="card">
                                <div class="card-body">
                                  <form action="" id="searchEntry" method="post">
                                    @csrf
                            <div class="card mb-2">
                                <div class="row p-2 Unioil-header">
                                    <div class="col-3 mb-3">
                                    <h4 class="mb-2 ms-2 text-white" for="">Raffle Events </h4>
                                    <select class="form-select" name="event_id" id="event_id" onchange="GetAllEntry()">
                                        {{-- <option value="" selected> Select Event </option> --}}
                                        @php
                                            $events = App\Models\Event::all();
                                        @endphp
                                        @foreach ($events as $event)
                                            <option value="{{ $event->event_id }}" 
                                                @if($event->event_status === 'Active') selected @endif>
                                                {{ $event->event_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>

                                <div class="col-3 mb-3">
                                    <h4 class="mb-2 ms-2 text-white" for=""> Regional Cluster </h4>
                                    <select class="form-select" name="region" id="region" onchange=" GetAllEntry()">
                                         <option value="" selected> All Clusters </option>
                                         @php
                                            $regions = App\Models\RegionalCluster::all();
                                        @endphp
                                         @foreach ($regions as $region)
                                             <option value="{{$region->cluster_id}}"> {{$region->cluster_name}} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3 mb-3">
                                    <h4 class="mb-2 ms-2 text-white" for=""> Product Type </h4>
                                    <select class="form-select" name="ptype" id="ptype" onchange=" GetAllEntry()">
                                       <option value="">All Type</option>
                                       <option value="1">Fully Synthetic</option>
                                       <option value="2">Semi Synthetic</option>
                                    </select>
                                </div>

                                <div class="col-3 mb-3">
                                    <h4 class="mb-2 ms-2 text-white" for=""> Products </h4>
                                    <select class="form-select" name="producttype" id="producttype" onchange=" GetAllEntry()">
                                         <option value="" selected> All Products </option>
                                         @php
                                            $products = App\Models\ProductList::all();
                                        @endphp
                                         @foreach ($products as $product)
                                             <option value="{{$product->product_id}}"> {{$product->product_name}} </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                         </form>
                                    <div id="table-default" class="table-responsive">
                                        <table class="table table-hover" id="product-table">
                                            <thead>
                                                <tr>
                                                    <th>Regional Cluster</th>
                                                    <th>Area</th>
                                                    <th>Address</th>
                                                    <th>Distributor</th>
                                                    <th>Retail Store</th>
                                                    <th>Purchase Date</th>
                                                    <th>Product Purchased</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">
                                                <!-- Table rows will be dynamically inserted here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6"></th> <!-- Leave empty for first 6 columns -->
                                                    <th id="total-rows"></th> <!-- Footer for Total Rows in the 7th column -->
                                                </tr>
                                            </tfoot>
                                        </table>
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
    <script src="{{asset('js/productreport/productreport.js')}}"></script>
</body>
</html>
