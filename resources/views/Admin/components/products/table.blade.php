<div class="card-body px-4">
    <form action="" id="searchEntry" method="post">
        @csrf
        <div class="card mb-2">
            <div class="row p-2 Unioil-header">
                <div class="col-4 mb-3">
                    <h4 class="mb-2 ms-2 text-white" for="">Raffle Events </h4>
                    <select class="form-select" name="event_id" id="event_id_table">
                        <option value="all" selected> All Raffle Event </option>
                        @php
                        $events = App\Models\Event::all();
                        @endphp
                        @foreach ($events as $event)
                        <option  value="{{$event->event_id}}"  {{ $event->event_status == 'Active' ? 'selected' : '' }}> {{$event->event_name}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4 mb-3">
                    <h4 class="mb-2 ms-2 text-white" for=""> Regional Cluster </h4>
                    <select class="form-select" name="region" id="region_table">
                        <option value="all" selected> All Cluster </option>
                        @php
                        $regions = App\Models\RegionalCluster::all();
                        @endphp
                        @foreach ($regions as $region)
                        <option value="{{$region->cluster_id}}"> {{$region->cluster_name}} </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-4 mb-3">
                    <h4 class="mb-2 ms-2 text-white" for=""> Products </h4>
                    <select class="form-select" name="region" id="products_table">
                        <option value="all" selected> All Products </option>
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
        <table class="table" id="productReportsTable">
            <thead>
                <tr>
                    <th>Area</th>
                    <th>Address</th>
                    <th>Distributor</th>
                    <th>Retail Store</th>
                    <th>Purchase Date</th>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Regional Cluster</th>
                </tr>
            </thead>
            <tbody class="table-tbody">

            </tbody>
        </table>

    </div>
