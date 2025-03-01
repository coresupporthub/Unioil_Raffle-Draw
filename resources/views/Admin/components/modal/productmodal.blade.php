<div class="modal modal-blur fade" id="add-product" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Product</h5>
          <button type="button" class="btn-close" id="closeAddProduct" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="product_form" class="modal-body">
            @csrf
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="name" placeholder="Enter Product Name">
                <small class="d-none text-danger" id="product_name_e">Name is required</small>
              </div>
              <div class="mb-3">
                <label class="form-label">Product Type</label>
                <input type="text" class="form-control" id="product_type" name="type" placeholder="Enter Product Type">
                <small class="d-none text-danger" id="product_type_e">Type is required</small>
              </div>
              <div class="mb-3">
                <label class="form-label">Entries</label>
                <select class="form-select" name="entry" id="product_entry">
                    <option selected disabled value="">----Select Entry Type----</option>
                    <option value="1">Single Entry</option>
                    <option value="2">Dual Entry</option>
                </select>
                <small class="d-none text-danger" id="product_entry_e">Entry is required</small>
              </div>
              <div class="mb-3">
                <div class="form-label">Upload Product Image (Optional)</div>
                <input type="file" name="image" id="inputImage" class="form-control">
              </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn me-auto"  data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="add_product_btn">Add Product</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal modal-blur fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crop Logo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="w-100 min-height-40 border border-danger">
                <img id="image" class="w-100 h-100" >
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" id="closeCropper" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="save-crop">Crop Logo</button>
        </div>
      </div>
    </div>
  </div>



  <div class="modal modal-blur fade" id="uploadLogo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateLogoHeader">Update Logo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="w-100 min-height-40 border border-danger">
                <img id="updateImage" class="w-100 h-100" >
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" id="closeLogoUploader" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="upload-logo">Update Logo</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal modal-blur fade" id="product-reports" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Info and Reports</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-2 p-4 d-flex justify-content-center align-items-center flex-column gap-2">
                    <img src="/unioil_images/unioil.png" id="prod_logo_info" class="w-50 rounded-full" alt="Product Logo">
                    <div class="mb-3">
                        <input type="file" class="form-control" id="updateLogo" />
                      </div>
                </div>
                <div class="col-9">
                    <div class="row p-4">
                        <div class="col-12">
                        <p class="mb-0">Product Name</p>
                        <div class="skeleton skeleton-text w-full"></div>
                        <h2 id="info_prod_name" class="product-info"></h2>
                        <input type="text" id="input_prod_name" class="form-control edit-form d-none" placeholder="Enter Product Name">
                        <small class="d-none text-danger" id="info_product_name_e">Name is required</small>
                    </div>
                    <div class="col-12">
                        <p class="mb-0">Type</p>
                        <div class="skeleton skeleton-text w-full"></div>
                        <h2 id="info_prod_type" class="product-info"></h2>
                        <input type="text" id="input_prod_type"  class="form-control edit-form d-none" placeholder="Enter Product Type">
                        <small class="d-none text-danger" id="info_product_type_e">Name is required</small>
                    </div>
                    <div class="col-12">
                        <p class="mb-0">Entry</p>
                        <div class="skeleton skeleton-text w-full"></div>
                        <h2 id="info_prod_entry" class="product-info"></h2>
                        <select id="input_prod_entry"  class="form-select edit-form d-none" name="entry" id="product_entry">
                            <option selected disabled value="">----Select Entry Type----</option>
                            <option value="1">Single Entry</option>
                            <option value="2">Dual Entry</option>
                        </select>
                    </div>

                    </div>

                </div>
            </div>

            <div class="card-body px-4">
                <form action="" id="searchEntry" method="post">
                    @csrf
                    <div class="card mb-2">
                        <div class="row p-2 Unioil-header">
                            <div class="col-6 mb-3">
                                <h4 class="mb-2 ms-2 text-white" for="">Raffle Events </h4>
                                <select class="form-select" name="event_id" id="event_id">
                                    <option value="all" selected> All Raffle Event </option>
                                    @php
                                    $events = App\Models\Event::all();
                                    @endphp
                                    @foreach ($events as $event)
                                    <option  value="{{$event->event_id}}"  {{ $event->event_status == 'Active' ? 'selected' : '' }}> {{$event->event_name}} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <h4 class="mb-2 ms-2 text-white" for=""> Regional Cluster </h4>
                                <select class="form-select" name="region" id="region">
                                    <option value="all" selected> All Cluster </option>
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
                    <table class="table" id="productReports">
                        <thead>
                            <tr>
                                <th>Regional Cluster</th>
                                <th>Area</th>
                                <th>Address</th>
                                <th>Distributor</th>
                                <th>Retail Store</th>
                                <th>Customer Name</th>
                                <th>Purchase Date</th>
                            </tr>
                        </thead>
                        <tbody class="table-tbody">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeProductInfo" class="btn me-auto" data-bs-dismiss="modal">Close</button>
          <div class="gap-2 d-none" id="edit-options">
            <button type="button" id="cancelEdit" class="btn btn-secondary "> <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                Cancel Edit</button>
            <button type="button" id="saveEdit" class="btn btn-info"> <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                Save Edit</button>
          </div>
          <div class="d-flex gap-2" id="product-options">
            <button id="enable-product-edit" class="btn btn-primary"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                Edit</button>
            <button id="remove-product" class="btn btn-danger"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal modal-blur fade" id="archive" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Archive List</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="card">
                <div class="card-header justify-content-between">
                  <h3 class="card-title">Archived Products</h3>
                  <div class="w-50">
                    <input id="searchProductArchived" type="text" class="form-control"
                        placeholder="Search for products">
                </div>
                </div>
                <div class="list-group list-group-flush list-group-hoverable min-height" id="archived-list">
                    <div id="archiveLoader"
                    class="w-100 h-100 d-flex flex-column gap-2 justify-content-center align-items-center">
                    <p>Loading Product Archives</p>
                    <div class="productLoader"></div>
                </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
