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
    $.ajax({
        url: "/api/get-all-store", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const data = response.data;
            $("#ratailOutletTable").DataTable({
                data: data,
                destroy: true,
                columns: [
                    { data: "cluster_name" },
                    { data: "region_name" },
                    { data: "city_name" },
                    { data: "store_name" },
                    { data: "store_code" },
                    {
                        // Define the Action button column
                        data: null,
                        render: function (data, type, row) {
                            return (
                                `<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-update-retail" onclick="updateRetail('${row.store_id}','${row.cluster_id}','${row.region_name}','${row.city_name}','${row.store_name}','${row.store_code}')">Update</button>` +
                                ` ` +
                                `<button class="btn btn-danger" onclick="ChangeStatus('${row.store_id}','/api/remove-retail')">Delete</button>`
                            );
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

function GetAllClusterSelect() {
    $.ajax({
        url: "/api/get-cluster", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const data = response.data;

            const selectElement = document.getElementById("cluster_id");

            selectElement.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select a cluster";
            defaultOption.value = "";
            selectElement.appendChild(defaultOption);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.cluster_id;
                newOption.text = element.cluster_name;
                selectElement.appendChild(newOption);
            });

            const selectElement2 = document.getElementById("cluster_id2");

            selectElement2.innerHTML = "";

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

function updateRetail(id, cluster, region, city, store, code) {
    document.getElementById("store_id").value = id;
    document.getElementById("cluster_id2").value = cluster;
    document.getElementById("store_name").value = store;
    document.getElementById("store_code").value = code;
    let region_id = "";

    dataGetter("https://psgc.cloud/api/regions").then((data) => {
        data.forEach((element) => {
            if (region === element.name) {
                region_id = element.code;
                
            }
        });
        const reg = document.getElementById("region_id2");
        reg.value = region_id;
        loadCity2(region_id);
        setTimeout(()=>{
        document.getElementById('city_id2').value=city;     
        },500);
    });
}


function dynamicCall(functionName, ...args) {
    if (typeof window[functionName] === "function") {
        window[functionName](...args); // Call the function dynamically with any passed arguments
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
                },
                error: function (xhr, status, error) {
                    console.error("Error posting data:", error);
                },
            });
        },
        function () {
            alertify.error("Cancel");
        }
    );
}

function loadRegion(){
   dataGetter("https://psgc.cloud/api/regions").then((data) => {

       const selectElement = document.getElementById("region_id");

       selectElement.innerHTML = "";

       const defaultOption = document.createElement("option");
       defaultOption.text = "Select a region";
       defaultOption.value = "";
       selectElement.appendChild(defaultOption);

       data.forEach((element) => {
           const newOption = document.createElement("option");
           newOption.value = element.code;
           newOption.text = element.name;
           selectElement.appendChild(newOption);
       });

       const selectElement2 = document.getElementById("region_id2");

       selectElement2.innerHTML = "";

       const defaultOption2 = document.createElement("option");
       defaultOption2.text = "Select a region";
       defaultOption2.value = "";
       selectElement2.appendChild(defaultOption2);

       data.forEach((element) => {
           const newOption = document.createElement("option");
           newOption.value = element.code;
           newOption.text = element.name;
           selectElement2.appendChild(newOption);
       });
   });
}

function loadCity(id){
    const code = id.value;
    dataGetter(`https://psgc.cloud/api/regions/${code}`).then(
        (data) => {
            document.getElementById('region_name').value=data.name;
        }
    );
    dataGetter(`https://psgc.cloud/api/regions/${code}/provinces`).then(
        (data) => {
            if(data.lenght>0){
                const selectElement = document.getElementById("city_id");

                selectElement.innerHTML = "";

                const defaultOption = document.createElement("option");
                defaultOption.text = "Select a city";
                defaultOption.value = "";
                selectElement.appendChild(defaultOption);

                data.forEach((element) => {
                    const newOption = document.createElement("option");
                    newOption.value = element.name;
                    newOption.text = element.name;
                    selectElement.appendChild(newOption);
                });
            }else{
                const selectElement = document.getElementById("city_id");

                selectElement.innerHTML = "";

                const defaultOption = document.createElement("option");
                defaultOption.text = "Select a city";
                defaultOption.value = "";
                selectElement.appendChild(defaultOption);
                 const newOption = document.createElement("option");
                 newOption.value = 'Manila';
                 newOption.text = "Manila";
                 selectElement.appendChild(newOption);
            }
            
        }
    );
}

function loadCity2(id) {
    const code = id.value;
    if (code) {
        dataGetter(`https://psgc.cloud/api/regions/${code}`).then((data) => {
            document.getElementById("region_name2").value = data.name;
        });
        dataGetter(`https://psgc.cloud/api/regions/${code}/provinces`).then(
            (data) => {
                 if (data.lenght>0) {
                     const selectElement = document.getElementById("city_id2");

                     selectElement.innerHTML = "";

                     const defaultOption = document.createElement("option");
                     defaultOption.text = "Select a city";
                     defaultOption.value = "";
                     selectElement.appendChild(defaultOption);

                     data.forEach((element) => {
                         const newOption = document.createElement("option");
                         newOption.value = element.name;
                         newOption.text = element.name;
                         selectElement.appendChild(newOption);
                     });
                 } else {
                     const selectElement = document.getElementById("city_id2");

                     selectElement.innerHTML = "";

                     const defaultOption = document.createElement("option");
                     defaultOption.text = "Select a city";
                     defaultOption.value = "";
                     selectElement.appendChild(defaultOption);
                     const newOption = document.createElement("option");
                     newOption.value = "Manila";
                     newOption.text = "Manila";
                     selectElement.appendChild(newOption);
                 }
            }
        ); 
    }else{
        dataGetter(`https://psgc.cloud/api/regions/${id}`).then((data) => {
            document.getElementById("region_name2").value = data.name;
        });
        dataGetter(`https://psgc.cloud/api/regions/${id}/provinces`).then(
            (data) => {
                const selectElement = document.getElementById("city_id2");

                selectElement.innerHTML = "";

                const defaultOption = document.createElement("option");
                defaultOption.text = "Select a city";
                defaultOption.value = "";
                selectElement.appendChild(defaultOption);

                data.forEach((element) => {
                    const newOption = document.createElement("option");
                    newOption.value = element.name;
                    newOption.text = element.name;
                    selectElement.appendChild(newOption);
                });
            }
        );
    }
        
}
function LoadAll(){
     GetAllCluster();
     GetAllClusterSelect();
     loadRegion();
     LoadAllRetailStore();
}
$(document).ready(function () {
   LoadAll();

});
