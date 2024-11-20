        @php
            $cluster = App\Models\RegionalCluster::where('cluster_status', 'Enable')->get();
        @endphp
        <div class="modal modal-blur fade" id="addClusterModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Cluster</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="col-md-12">
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

                    <form action="" method="post" id="clusterFormUpdate" class="d-none">
                        @csrf
                        <input type="hidden" name="cluster_id" id="updateClusterId">
                    <div class="row">
                        <div class="col-12 col-md-8 mb-3">
                            <label for="editRegionalCluster">Regional Cluster (Update)</label>
                            <input type="text" name="cluster_name" id="editRegionalCluster" class="form-control">
                            <small class="d-none text-danger" id="editRegionalClusterE">This is a required field</small>
                        </div>
                        <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                            <button class="btn btn-info w-100" type="submit">Update</button>
                        </div>

                    </div>
                    </form>


                    <div id="table-default" class="table-responsive">
                                    <table class="table" id="clusterTable">
                                        <thead>
                                            <tr>
                                                <th>Cluster</th>
                                                <th>Status</th>
                                                <th style="width:10%">Action</th>
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
                                <select name="cluster_id" id="cluster_id2" class="form-control">

                                </select>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="area">Area</label>
                                <input type="text" class="form-control" id="area" name="area">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="distributor">Distributor</label>
                                <input type="text" class="form-control" name="distributor" id="distributor">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="retail_store">Retail Store</label>
                                <input type="text" id="retail_store" name="retail_store" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="rto_code">RTO Code</label>
                                <input type="text" name="rto_code" id="rto_code" class="form-control">
                            </div>

                        </div>
                        </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeRetail" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary " type="button" onclick="SubmitData('updateregionForm','/api/update-store')">Update</button>
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
                        @csrf
                        <div class="mb-3">
                            <label for="clusterCSV">Select a Cluster</label>
                            <select name="cluster" required id="clusterCSV" class="form-select">
                                <option value="" disabled selected>-----Select a cluster----</option>
                                @foreach ($cluster as $clust)
                                    <option value="{{ $clust->cluster_id }}">{{ $clust->cluster_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-label">Upload CSV File</div>
                            <input required type="file" name="csv_file" id="csv_file" class="form-control">
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

                <div class="modal modal-blur fade" id="addRetailStore" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Retail Outlet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="col-md-12">
                            <form  id="addRetailStationForm">
                                @csrf
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <label for="clusterAddStore">Select a Cluster</label>
                                    <select name="cluster" id="clusterAddStore" class="form-select">
                                        <option value="" disabled selected>-----Select a cluster----</option>
                                        @foreach ($cluster as $clust)
                                            <option value="{{ $clust->cluster_id }}">{{ $clust->cluster_name }}</option>
                                        @endforeach
                                    </select>
                                    <small id="clusterAddStoreE" class="text-danger d-none">Please choose a cluster to proceed</small>
                                </div>
                                <div class="col-6 mb-4">
                                    <label for="areaAdd">Area</label>
                                    <input type="text" name="area" id="areaAdd" placeholder="Add area" class="form-control">
                                    <small id="areaAddE" class="text-danger d-none">Please add an area to proceed</small>
                                </div>
                                <div class="col-6 mb-4">
                                    <label for="addressAdd">Address</label>
                                    <input type="text" name="address" id="addressAdd" placeholder="Add Address" class="form-control">
                                    <small id="addressAddE" class="text-danger d-none">Please add an address to proceed</small>
                                </div>

                                <div class="col-6 mb-4">
                                    <label for="distributorAdd">Distributor</label>
                                    <input type="text" name="distributor" id="distributorAdd" placeholder="Add Distributor" class="form-control">
                                    <small id="distributorAddE" class="text-danger d-none">Please add a distributor to proceed</small>
                                </div>

                                <div class="col-6 mb-4">
                                    <label for="retailStationAdd">Retail Station</label>
                                    <input type="text" name="retail_station" id="retailStationAdd" placeholder="Add Retail Station" class="form-control">
                                    <small id="retailStationAddE" class="text-danger d-none">Please add a Retail Station to proceed</small>
                                </div>

                                <div class="col-6 mb-4">
                                    <label for="rtoCodeAdd">RTO Code</label>
                                    <input type="text" name="rto_code" id="rtoCodeAdd" placeholder="Add RTO Code" class="form-control">
                                    <small id="rtoCodeAddE" class="text-danger d-none">RTO Code is required to proceed</small>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit" id="saveRetailStation">Save</button>
                                </div>
                            </div>
                            </form>
                      </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="closeAddRetailStation" class="btn me-auto" data-bs-dismiss="modal">Close</button>

                        </div>
                        </div>
                    </div>
                    </div>


