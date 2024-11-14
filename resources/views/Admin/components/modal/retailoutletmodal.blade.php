        <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
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
                        <a href="#tabs-Region" class="nav-link" data-bs-toggle="tab">Region</a>
                      </li>
                      <li class="nav-item">
                        <a href="#tabs-City" class="nav-link" data-bs-toggle="tab">City</a>
                      </li>
                      <li class="nav-item">
                        <a href="#tabs-Street" class="nav-link" data-bs-toggle="tab">Retail Store</a>
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
                                <input type="text" name="region_name" id="region_name" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="button" onclick="SubmitData('regionForm','/api/add-region')">Submit</button>
                            </div>
                        </div>
                        </form>
                     </div>
                      <div class="tab-pane" id="tabs-City">

                        <form action="" method="post" id="cityForm">
                            @csrf
                        <div class="row">
                            <div class="col-12 col-md-3 mb-3">
                                <label for="regionalCluster">Regional Cluster</label>
                                <select name="cluster_id" id="cluster_id_city" class="form-control" onchange=" GetRegionByCluster(this)"></select>
                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                <label for="regionalCluster">Region</label>
                                <select name="region_id" id="region_id" class="form-control"></select>
                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                <label for="regionalCluster">City</label>
                                <input type="text" name="city_name" id="" class="form-control">
                            </div>
                            <div class="col-12 col-md-3 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="button" onclick="SubmitData('cityForm','/api/add-city')">Submit</button>
                            </div>
                        </div>
                        </form>
                        
                      </div>
                      <div class="tab-pane" id="tabs-Street">
                        <form action="" method="post" id="storeform">
                            @csrf
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Regional Cluster</label>
                                <select name="cluster_id" id="cluster_id_store" class="form-control" onchange=" GetRegionByCluster2(this)"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Region</label>
                                <select name="region_id" id="region_id2" class="form-control" onclick="GetCityByRegion(this)"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">City</label>
                                <select name="city_id" id="city_id" class="form-control"></select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Retail Store</label>
                                <input type="text" name="store_name" id="" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="regionalCluster">Retail Store Code</label>
                                <input type="text" name="store_code" id="" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="button" onclick="SubmitData('storeform','/api/add-store')">Submit</button>
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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                </div>
                </div>
            </div>
            </div>