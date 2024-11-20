function GetAllCluster() {
    $.ajax({
        url: "/api/get-cluster", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const data = response.data;

            $("#clusterTable").DataTable({
                data: data,
                destroy: true,
                columns: [
                    { data: "cluster_name" },
                    { data: "cluster_status" },
                    {
                        // Define the Action button column
                        data: null,
                        render: function (data, type, row) {
                            return `<button class="btn btn-danger" onclick="ChangeStatus('${row.cluster_id}','/api/cluster-status')">Delete</button>`;
                        },
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

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
        url: "/api/get-cluster", // Replace with your endpoint URL
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
    alertify.confirm(
        "Warning","Are you sure you want to remove this data?",
        function () {
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
        },
        function () {
            return;
        }
    );
}

function LoadAll(){
     GetAllCluster();
     GetAllClusterSelect();
     LoadAllRetailStore();
}
$(document).ready(function () {
   LoadAll();

});


document.getElementById('uploadBtn').addEventListener('click', ()=> {
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
        success: res=> {
            loading(false);
            LoadAllRetailStore();
            clearForm('uploadCsvForm');
            exec('closeUploadModal');
            dataParser(res);
        }, error: xhr=> console.log(xhr.responseText)
    })
});

function FilterRetailStore(filter){
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

document.getElementById('clusterFilter').addEventListener('change', (e)=> {
    const filter = e.target.value;

    if(filter == 'all'){
        LoadAllRetailStore();
    }else{
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

    if(checkValidity(inputs)){
        loading(true);

        $.ajax({
            type: "POST",
            url: "/api/add-single-retail-store",
            data: $('#addRetailStationForm').serialize(),
            success: res=> {
                loading(false);
                dataParser(res);
                exec('closeAddRetailStation');
                LoadAllRetailStore();
            }, error: xhr=> console.log(xhr.responseText)
        })
    }

});
