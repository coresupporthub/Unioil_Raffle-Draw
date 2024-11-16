        @php
            $cluster = App\Models\RegionalCluster::all();
        @endphp
        <div class="modal modal-blur fade" id="addClusterModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Retail Outlet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                      <li class="nav-item">
                        <a href="#tabs-Cluster" class="nav-link active" data-bs-toggle="tab">Cluster</a>
                      </li>
                      <li class="nav-item">
                        <a href="#tabs-Region" class="nav-link" data-bs-toggle="tab">Retail Store Details</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane active show" id="tabs-Cluster">

                        <form action="" method="post" id="clusterForm">
                            @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="regionalCluster">Regional Cluster</label>
                                <input type="text" name="cluster_name" id="regionalCluster" class="form-control">
                            </div>
                            <div class="col-12 col-md-6 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="button" onclick="SubmitData('clusterForm','/api/add-retail-store')">Submit</button>
                            </div>
                        </div>
                        </form>

                        <div id="table-default" class="table-responsive">
                                        <table class="table" id="clusterTable">
                                            <thead>
                                                <tr>
                                                    <th>Cluster</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-tbody">

                                            </tbody>
                                        </table>

                        </div>

                      </div>
                      <div class="tab-pane" id="tabs-Region">

                        <form action="" method="post" id="regionForm">
                            @csrf
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Regional Cluster</label>
                                <select name="cluster_id" id="cluster_id" class="form-control"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Region</label>
                                <select name="region_id" id="region_id" class="form-control" onchange="loadCity(this)"></select>
                                <input type="text" id="region_name" name="region_name" hidden>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">City</label>
                                <select name="city_name" id="city_id" class="form-control"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Retail Store</label>
                                <input type="text" name="store_name" id="" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Store Code</label>
                                <input type="text" name="store_code" id="" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="button" onclick="SubmitData('regionForm','/api/add-store')">Submit</button>
                            </div>
                        </div>
                        </form>
                     </div>

                    </div>
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


            <div class="modal modal-blur fade" id="modal-update-retail" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Retail Outlet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="updateregionForm">
                            @csrf
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <input type="text" name="store_id" id="store_id" hidden>
                                <label for="regionalCluster">Regional Cluster</label>
                                <select name="cluster_id" id="cluster_id2" class="form-control"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Region</label>
                                <select name="region_id" id="region_id2" class="form-control" oninput="loadCity2(this)"></select>
                                <input type="text" id="region_name2" name="region_name" hidden>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">City</label>
                                <select name="city_name" id="city_id2" class="form-control"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Retail Store</label>
                                <input type="text" name="store_name" id="store_name" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Store Code</label>
                                <input type="text" name="store_code" id="store_code" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="button" onclick="SubmitData('updateregionForm','/api/update-store')">Update</button>
                            </div>
                        </div>
                        </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>

                </div>
                </div>
            </div>
            </div>



            <div class="modal modal-blur fade" id="importCsvModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Retail Outlets</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="uploadCsvForm" class="modal-body">
                        <div class="mb-3">
                            <label for="clusterCSV">Select a Cluster</label>
                            <select name="cluster" id="clusterCSV" class="form-select">
                                <option value="" disabled selected>-----Select a cluster----</option>
                                @foreach ($cluster as $clust)
                                    <option value="{{ $clust->cluster_id }}">{{ $clust->cluster_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-label">Upload CSV File</div>
                            <input type="file" id="csv_file" class="form-control">
                        </div>

                    </form>
                    <div class="modal-footer">
                        <button type="button" id="closeUploadModal" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="uploadBtn" class="btn btn-primary" >Upload</button>
                    </div>
                    </div>
                </div>
                </div>


                <div class="modal modal-blur fade" id="modal-update-retail" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Retail Outlet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="updateregionForm">
                                @csrf
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <input type="text" name="store_id" id="store_id" hidden>
                                    <label for="regionalCluster">Regional Cluster</label>
                                    <select name="cluster_id" id="cluster_id2" class="form-control"></select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="regionalCluster">Region</label>
                                    <select name="region_id" id="region_id2" class="form-control" oninput="loadCity2(this)"></select>
                                    <input type="text" id="region_name2" name="region_name" hidden>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="regionalCluster">City</label>
                                    <select name="city_name" id="city_id2" class="form-control"></select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="regionalCluster">Retail Store</label>
                                    <input type="text" name="store_name" id="store_name" class="form-control">
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="regionalCluster">Store Code</label>
                                    <input type="text" name="store_code" id="store_code" class="form-control">
                                </div>
                                <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" type="button" onclick="SubmitData('updateregionForm','/api/update-store')">Update</button>
                                </div>
                            </div>
                            </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>

                    </div>
                    </div>
                </div>
                </div>



