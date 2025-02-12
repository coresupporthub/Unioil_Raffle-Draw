function GetAllCluster() {
    $.ajax({
        url: "/api/get-cluster/all",
        type: "GET",
        success: function (response) {
            const data = response.data;

            $("#clusterTable").DataTable({
                data: data,
                destroy: true,
                autoWidth: true,
                columns: [
                    { data: "cluster_name" },
                    { data: "cluster_status" },
                    {
                        // Define the Action button column
                        data: null,
                        render: function (data, type, row) {
                            return data.cluster_status == 'Enable' ? `<div class="d-flex gap-1">
                            <button class="btn btn-success activeEdit" id="updateCluster${data.cluster_id}" data-cluster-name="${data.cluster_name}" data-cluster-id="${row.cluster_id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                            <path d="M13.5 6.5l4 4" />
                            </svg> Update</button>
                            <button class="btn btn-warning d-none cancelEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                            </svg> Cancel </button>
                            <button class="btn btn-danger disableCluster" data-cluster-id="${row.cluster_id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg> Disable</button>
                            </div>` :  `<button class="btn btn-info enableCluster" data-cluster-id="${data.cluster_id}" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 12l5 5l10 -10" />
                            <path d="M2 12l5 5m5 -5l5 -5" />
                            </svg> Enable</button>`;
                        },
                        width: '10%'
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });

    $("#clusterTable").on('click', '.activeEdit', function() {
        const clusterId = $(this).data('cluster-id');
        const clusterName = $(this).data('cluster-name');
        UpdateCluster(clusterId, $(this), clusterName);
    });

    $("#clusterTable").on('click', '.cancelEdit', function() {
        CancelUpdate($(this));
    });

    $("#clusterTable").on('click', '.disableCluster', function() {
        const clusterId = $(this).data('cluster-id');
        DisableCluster(clusterId, '/api/cluster-status');
    });

    $("#clusterTable").on('click', '.enableCluster', function() {
        const clusterId = $(this).data('cluster-id');
        EnableCluster(clusterId);
    });
}

let updateClusterId;

function UpdateCluster(id, btn, name) {
    hide('clusterForm');
    show('clusterFormUpdate');

    $('.cancelEdit').addClass('d-none');

    $('.activeEdit').removeClass('d-none');


    const cancelBtn = btn.next();

    btn.addClass('d-none');
    cancelBtn.removeClass('d-none');

    updateClusterId = `updateCluster${id}`;
    setValue('editRegionalCluster', name);
    setValue('updateClusterId', id);
}


function CancelUpdate(btn){
    show('clusterForm');
    hide('clusterFormUpdate');

    btn.addClass('d-none');

    const updateBtn = document.getElementById(updateClusterId);

    updateBtn.classList.remove('d-none');
}

function EnableCluster(id){
    Swal.fire({
        title: "Are you sure to enable this cluster back?",
        text: "This will able you to add more retail store to this cluster",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Enable it"
      }).then((result) => {
        if (result.isConfirmed) {
          loading(true);

          const csrfToken = $('meta[name="csrf-token"]').attr("content");
          const data = {
            "_token" : csrfToken,
            "id": id
          };

          $.ajax({
            type: "POST",
            url: "/api/enable-cluster",
            data: data,
            success: res=> {
                loading(false);
                dataParser(res);
                GetAllCluster();
                GetAllClusterSelect();
            }, error: xhr=> console.log(xhr.responseText)
          });

        }
      });
}


document.getElementById('clusterFormUpdate').addEventListener('submit', e => {
    e.preventDefault();

    const inputs = [
        ['editRegionalCluster', 'editRegionalClusterE']
    ];

    if(checkValidity(inputs)){
        loading(true);

        $.ajax({
            type: "POST",
            url: "/api/update-cluster",
            data: $('#clusterFormUpdate').serialize(),
            success: res=> {
                loading(false);
                dataParser(res);
                GetAllCluster();
                show('clusterForm');
                hide('clusterFormUpdate');

            }, error: xhr=> console.log(xhr.response)
        })
    }
});


function LoadAllRetailStore() {
    const tableId = "#ratailOutletTable";

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/get-all-store',
            dataSrc: 'data'
        },
        destroy: true,
        columns: [
            { data: "cluster_name" },
            { data: "address" },
            { data: "distributor" },
            { data: "retail_station" },
            { data: "rto_code" },
            {
                data: null,
                render: function (data, type, row) {
                    return (
                        `<div class="d-flex gap-1"><button class="btn btn-success updateRetail" data-bs-toggle="modal" data-bs-target="#modal-update-retail"
                        data-store-id="${row.store_id}" data-area="${row.area}" data-cluster-id="${row.cluster_id}" data-address="${row.address}" data-distributor="${row.distributor}" data-retail-station="${row.retail_station}" data-rto-code="${row.rto_code}">Update</button>` +
                        ` ` +
                        `<button class="btn btn-danger deleteRetail" data-store-id="${row.store_id}">Delete</button></div>`
                    );
                },
            },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });

    $(tableId).on('click', '.updateRetail', function() {
        const storeId = $(this).data('store-id');
        const clusterId = $(this).data('cluster-id');
        const address = $(this).data('address') ?? '';
        const area = $(this).data('area') ?? '';
        const distributor = $(this).data('distributor') ?? '';
        const retailStation = $(this).data('retail-station') ?? '';
        const rtocode = $(this).data('rto-code') ?? '';
        updateRetail(storeId, clusterId, address, area, distributor, retailStation, rtocode);
    });

    $(tableId).on('click', '.deleteRetail', function() {
        const storeId = $(this).data('store-id');
        DeleteRetail(storeId);
    });
}



function GetAllClusterSelect() {
    $.ajax({
        url: "/api/get-cluster/draw",
        type: "GET",
        success: function (response) {
            const data = response.data;

            const selectElement2 = document.getElementById("cluster_id2");
            const selectFilter = document.getElementById("clusterFilter");

            while (selectElement2.firstChild) {
                selectElement2.removeChild(selectElement2.firstChild);
            }

            while (selectFilter.firstChild) {
                selectFilter.removeChild(selectFilter.firstChild);
            }

            const allFilter = document.createElement("option");
            allFilter.text = "All";
            allFilter.value = "all";
            selectFilter.appendChild(allFilter);

            const defaultOption2 = document.createElement("option");
            defaultOption2.text = "Select a cluster";
            defaultOption2.value = "";
            selectElement2.appendChild(defaultOption2);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.cluster_id;
                newOption.text = element.cluster_name;

                const newOption2 = document.createElement("option");
                newOption2.value = element.cluster_id;
                newOption2.text = element.cluster_name;
                selectElement2.appendChild(newOption2);
                selectFilter.appendChild(newOption);

            });

        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function updateRetail(id, cluster_id, address, area, distributor, retail_station, rto_code) {
    document.getElementById("store_id").value = id;

    setValue('cluster_id2', cluster_id);
    setValue('address', address == 'null' ? '' : address);
    setValue('area', area == 'null' ? '' : area);
    setValue('distributor', distributor == 'null' ? '' : distributor);
    setValue('retail_store', retail_station);
    setValue('rto_code', rto_code);

}


function dynamicCall(functionName, ...args) {
    if (typeof window[functionName] === "function") {
        window[functionName](...args);
    } else {
        console.error("Function not found:", functionName);
    }
}

document.getElementById('addCluster').addEventListener('click', ()=> {
    SubmitData('clusterForm', '/api/add-retail-store');
});

document.getElementById('updateCluster').addEventListener('click', ()=> {
    SubmitData('updateregionForm', '/api/update-store');
})

function SubmitData(formId, route) {

    const inputs = [
        ['cluster_id2', 'cluster_id2E'],
        ['retail_store', 'retail_storeE'],
        ['rto_code', 'rto_codeE'],
    ];
    const clusterinputs = [["cluster_name", "cluster_nameE"]];

    if(checkValidity(inputs)){
        loading(true);

        const form = document.getElementById(formId);
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        const formData = new FormData(form);
        formData.append("_token", csrfToken);

        $.ajax({
            url: route,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                loading(false);
                dataParser(response);
                if(response.success){
                    document.getElementById(formId).reset();
                    dynamicCall(response.reload);
                    exec('closeRetail');
                    setValue('clusterFilter', 'all');
                }

            },
            error: function (xhr, status, error) {
                console.error("Error posting data:", error);
            },
        });
    }else if (checkValidity(clusterinputs)) {
        loading(true);

        const form = document.getElementById(formID);
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        const formData = new FormData(form);
        formData.append("_token", csrfToken);

        $.ajax({
            url: route,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alertify.success(response.message);
                document.getElementById(formID).reset();
                dynamicCall(response.reload);
                loading(false);
                exec("closeRetail");
                setValue("clusterFilter", "all");
            },
            error: function (xhr, status, error) {
                console.error("Error posting data:", error);
            },
        });
    }

}

function DisableCluster(id, route) {

    Swal.fire({
        title: "Are you sure to disable this?",
        text: "The data attach to this cluster will not be remove but you cannot add any retail store to this cluster until you enable it back",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, disable it!"
      }).then((result) => {
        if (result.isConfirmed) {
            loading(true);
            const csrfToken = $('meta[name="csrf-token"]').attr("content");
            const formData = new FormData();
            formData.append("_token", csrfToken);
            formData.append("id", id);

            $.ajax({
                url: route,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alertify.success(response.message);
                    dynamicCall(response.reload);
                    loading(false);
                    LoadAllRetailStore();
                    GetAllClusterSelect();
                },
                error: function (xhr, status, error) {
                    console.error("Error posting data:", error);
                },
            });
        }
      });


}

function LoadAll() {
    GetAllCluster();
    GetAllClusterSelect();
    LoadAllRetailStore();
}
$(document).ready(function () {
    LoadAll();

});


document.getElementById('uploadBtn').addEventListener('click', () => {
    document.getElementById('uploadCsvForm').requestSubmit();
});

document.getElementById('uploadCsvForm').addEventListener('submit', e => {
    e.preventDefault();
    const form = document.getElementById('uploadCsvForm');
    const formData = new FormData(form);

    loading(true);
    $.ajax({
        type: 'POST',
        url: "/api/upload-retail-store",
        data: formData,
        contentType: false,
        processData: false,
        success: res => {
            loading(false);
            LoadAllRetailStore();
            clearForm('uploadCsvForm');
            exec('closeUploadModal');
            dataParser(res);
        }, error: xhr => console.log(xhr.responseText)
    })
});

function FilterRetailStore(filter) {
    const tableId = "#ratailOutletTable";


    if ($.fn.DataTable.isDataTable(tableId)) {

        $(tableId).DataTable().destroy();
    }

    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/api/filter-cluster?filter=${filter}`,
            dataSrc: 'data'
        },
        destroy: true,
        columns: [
            { data: "cluster_name" },
            { data: "address" },
            { data: "distributor" },
            { data: "retail_station" },
            { data: "rto_code" },
            {
                data: null,
                render: function (data, type, row) {
                    return (
                        `<div class="d-flex gap-1"><button class="btn btn-success updateRetail" data-bs-toggle="modal" data-bs-target="#modal-update-retail"
                         data-store-id="${row.store_id}" data-area="${row.area}" data-cluster-id="${row.cluster_id}" data-address="${row.address}" data-distributor="${row.distributor}" data-retail-station="${row.retail_station}" data-rto-code="${row.rto_code}">Update</button>` +
                        ` ` +
                        `<button class="btn btn-danger deleteRetail" data-store-id="${row.store_id}">Delete</button></div>`
                    );
                },
            },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });

    $(tableId).on('click', '.updateRetail', function() {
        const storeId = $(this).data('store-id');
        const clusterId = $(this).data('cluster-id');
        const address = $(this).data('address') ?? '';
        const area = $(this).data('area') ?? '';
        const distributor = $(this).data('distributor') ?? '';
        const retailStation = $(this).data('retail-station') ?? '';
        const rtocode = $(this).data('rto-code') ?? '';
        updateRetail(storeId, clusterId, address, area, distributor, retailStation, rtocode);
    });

    $(tableId).on('click', '.deleteRetail', function() {
        const storeId = $(this).data('store-id');
        DeleteRetail(storeId);
    });
}

function DeleteRetail(id){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
         loading(true);
         const csrfToken = $('meta[name="csrf-token"]').attr("content");

         const data = {
            "_token": csrfToken,
            "id": id
         }

         $.ajax({
            type: "POST",
            url: "/api/remove-retail",
            data: data,
            success: res=> {
                loading(false);
                dataParser(res);
                LoadAllRetailStore();
                setValue('clusterFilter', 'all');
            }, error: xhr=> console.log(xhr.responseText)
         });
        }
      });
}

document.getElementById('clusterFilter').addEventListener('change', (e) => {
    const filter = e.target.value;

    if (filter == 'all') {
        LoadAllRetailStore();
    } else {
        FilterRetailStore(filter);
    }
});


document.getElementById('addRetailStationForm').addEventListener('submit', e => {
    e.preventDefault();

    const inputs = [
        ['clusterAddStore', 'clusterAddStoreE'],
        ['retailStationAdd', 'retailStationAddE'],
        ['rtoCodeAdd', 'rtoCodeAddE']
    ];

    if (checkValidity(inputs)) {
        loading(true);

        $.ajax({
            type: "POST",
            url: "/api/add-single-retail-store",
            data: $('#addRetailStationForm').serialize(),
            success: res => {
                loading(false);
                dataParser(res);
                if(res.success){
                    exec('closeAddRetailStation');
                    LoadAllRetailStore();
                    clearForm('addRetailStationForm');
                }

            }, error: xhr => console.log(xhr.responseText)
        })
    }

});
