<div class="modal modal-blur fade" id="add-product" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Text</label>
                <input type="text" class="form-control" name="example-text-input" placeholder="Input placeholder">
              </div>
              <div class="mb-3">
                <label class="form-label">Text</label>
                <input type="text" class="form-control" name="example-text-input" placeholder="Input placeholder">
              </div>
              <div class="mb-3">
                <label class="form-label">Text</label>
                <input type="text" class="form-control" name="example-text-input" placeholder="Input placeholder">
              </div>
              <div class="mb-3">
                <div class="form-label">Custom File Input</div>
                <input type="file" class="form-control">
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Add Product</button>
        </div>
      </div>
    </div>
  </div>



  <div class="modal modal-blur fade" id="product-reports" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Info and Reports</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-primary">Update</button>
                <button class="btn btn-danger">Delete</button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-2 p-4 d-flex justify-content-center align-items-center">
                    <img src="/unioil_images/unioil.png" class="w-50" alt="Product Logo">
                </div>
                <div class="col-9">
                    <div class="row p-4">
                        <div class="col-12">
                        <p class="mb-0">Product Name</p>
                        <h2>Product</h2>
                    </div>
                    <div class="col-12">
                        <p class="mb-0">Type</p>
                        <h2>Product</h2>
                    </div>
                    <div class="col-12">
                        <p class="mb-0">Entry</p>
                        <h2>Product</h2>
                    </div>

                    </div>

                </div>
            </div>

            <div class="card-body">
                <form action="" id="searchEntry" method="post">
                    @csrf
                    <div class="card mb-2">
                        <div class="row p-2 Unioil-header">
                            <div class="col-6 mb-3">
                                <h4 class="mb-2 ms-2 text-white" for="">Raffle Events </h4>
                                <select class="form-select" name="event_id" id="event_id">
                                    <option value="" selected> All Raffle Event </option>
                                    @php
                                    $events = App\Models\Event::all();
                                    @endphp
                                    @foreach ($events as $event)
                                    <option value="{{$event->event_id}}"> {{$event->event_name}} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <h4 class="mb-2 ms-2 text-white" for=""> Regional Cluster </h4>
                                <select class="form-select" name="region" id="region" onchange=" GetAllEntry()">
                                    <option value="" selected> All Cluster </option>
                                    @php
                                    $regions = App\Models\RegionalCluster::all();
                                    @endphp
                                    @foreach ($regions as $region)
                                    <option value="{{$region->cluster_id}}"> {{$region->cluster_name}} </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </form>
                <div id="table-default" class="table-responsive">
                    <table class="table" id="entryTable">
                        <thead>
                            <tr>
                                <th>Regional Cluster</th>
                                <th>Store Name</th>
                                <th>Coupon</th>
                                <th>Product Purchased</th>
                                <th>Entry Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead>
                        <tbody class="table-tbody">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
        </div>
      </div>
    </div>
  </div>
