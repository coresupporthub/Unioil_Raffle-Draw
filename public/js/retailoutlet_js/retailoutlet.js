function GetAllCluster() {
    $.ajax({
        url: "/api/get-cluster",
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
                            <button class="btn btn-success activeEdit" id="updateCluster${data.cluster_id}" onclick="UpdateCluster('${row.cluster_id}', this, '${data.cluster_name}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                            <path d="M13.5 6.5l4 4" />
                            </svg> Update</button>
                            <button onclick="CancelUpdate(this)" class="btn btn-warning d-none cancelEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                            </svg> Cancel </button>
                            <button class="btn btn-danger" onclick="ChangeStatus('${row.cluster_id}','/api/cluster-status')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg> Disable</button>
                            </div>` :  `<button class="btn btn-info" onclick="EnableCluster('${data.cluster_id}')">
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
}

let updateClusterId;

function UpdateCluster(id, btn, name) {
    hide('clusterForm');
    show('clusterFormUpdate');
    const allCancel = document.querySelectorAll('.cancelEdit');

    allCancel.forEach(d => {
        d.classList.add('d-none');
    });

    const allEdit = document.querySelectorAll('.activeEdit');

    allEdit.forEach(e => {
        e.classList.remove('d-none');
    });

    const cancelBtn = btn.nextElementSibling;
    btn.classList.add('d-none');
    cancelBtn.classList.remove('d-none');

    updateClusterId = `updateCluster${id}`;

    setValue('editRegionalCluster', name);
    setValue('updateClusterId', id);
}

function CancelUpdate(btn){
    show('clusterForm');
    hide('clusterFormUpdate');

    btn.classList.add('d-none');

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
                        `<div class="d-flex gap-1"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-update-retail" onclick="updateRetail('${row.store_id}','${row.cluster_id}','${row.address}','${row.area}','${row.distributor}','${row.retail_station}', '${row.rto_code}')">Update</button>` +
                        ` ` +
                        `<button class="btn btn-danger" onclick="ChangeStatus('${row.store_id}','/api/remove-retail')">Delete</button></div>`
                    );
                },
            },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });
}

function GetAllClusterSelect() {
    $.ajax({
        url: "/api/get-cluster",
        type: "GET",
        success: function (response) {
            const data = response.data;

            const selectElement2 = document.getElementById("cluster_id2");

            while (selectElement2.firstChild) {
                selectElement2.removeChild(selectElement2.firstChild);
            }

            const defaultOption2 = document.createElement("option");
            defaultOption2.text = "Select a cluster";
            defaultOption2.value = "";
            selectElement2.appendChild(defaultOption2);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.cluster_id;
                newOption.text = element.cluster_name;
                selectElement2.appendChild(newOption);
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
    setValue('address', address);
    setValue('area', area);
    setValue('distributor', distributor);
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

function validateForm(formID) {
    const form = document.getElementById(formID);
    let emptyField = false;

    const inputs = form.querySelectorAll("input, textarea, select");

    inputs.forEach(function (input) {
        if (input.value.trim() === "") {
            emptyField = true;
            input.classList.add("error");
        } else {
            input.classList.remove("error");
        }
    });

    return !emptyField;
}

function SubmitData(formID, route) {
    if (!validateForm(formID)) {
        alertify.error("Please fill in all required fields.");
        return;
    }
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
            exec('closeRetail');
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });
}

function ChangeStatus(id, route) {

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
                        `<div class="d-flex gap-1"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-update-retail" onclick="updateRetail('${row.store_id}','${row.cluster_id}','${row.address}','${row.area}','${row.distributor}','${row.retail_station}', '${row.rto_code}')">Update</button>` +
                        ` ` +
                        `<button class="btn btn-danger" onclick="ChangeStatus('${row.store_id}','/api/remove-retail')">Delete</button></div>`
                    );
                },
            },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
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
        ['addressAdd', 'addressAddE'],
        ['retailStationAdd', 'retailStationAddE'],
        ['distributorAdd', 'distributorAddE'],
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
                exec('closeAddRetailStation');
                LoadAllRetailStore();
            }, error: xhr => console.log(xhr.responseText)
        })
    }

});
