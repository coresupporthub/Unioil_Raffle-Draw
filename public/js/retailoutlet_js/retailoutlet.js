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
                            return `<button class="btn btn-danger" onclick="ChangeStatus('${row.cluster_id}','/api/cluster-status')">Change Status</button>`;
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

function GetAllRegion() {
    $.ajax({
        url: "/api/get-region", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const data = response.data;
            console.log(data)
            // $("#clusterTable").DataTable({
            //     data: data,
            //     destroy: true,
            //     columns: [
            //         { data: "cluster_name" },
            //         { data: "cluster_status" },
            //         {
            //             // Define the Action button column
            //             data: null,
            //             render: function (data, type, row) {
            //                 return `<button class="btn btn-danger" onclick="ChangeStatus('${row.cluster_id}','/api/cluster-status')">Change Status</button>`;
            //             },
            //         },
            //     ],
            // });
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

            const cluster2 = document.getElementById("cluster_id_city");

            cluster2.innerHTML = "";

            const defaultOption2 = document.createElement("option");
            defaultOption2.text = "Select a cluster";
            defaultOption2.value = "";
            cluster2.appendChild(defaultOption2);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.cluster_id;
                newOption.text = element.cluster_name;
                cluster2.appendChild(newOption);
            });

            const cluster3 = document.getElementById("cluster_id_store");

            cluster3.innerHTML = "";

            const defaultOption3 = document.createElement("option");
            defaultOption3.text = "Select a cluster";
            defaultOption3.value = "";
            cluster3.appendChild(defaultOption3);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.cluster_id;
                newOption.text = element.cluster_name;
                cluster3.appendChild(newOption);
            });

        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function GetRegionByCluster(id) {

    const selectedValue = id.value;
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("id", selectedValue);
    formData.append("_token", csrfToken);

    $.ajax({
        url: "/api/get-region-by-cluster",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response.data;
            const selectElement = document.getElementById("region_id");

            selectElement.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select a resion";
            defaultOption.value = "";
            selectElement.appendChild(defaultOption);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.region_id;
                newOption.text = element.region_name;
                selectElement.appendChild(newOption);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function GetCityByRegion(id) {
    const selectedValue = id.value;
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("id", selectedValue);
    formData.append("_token", csrfToken);

    $.ajax({
        url: "/api/get-city-by-region",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response.data;
            const selectElement = document.getElementById("city_id");

            selectElement.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select a city";
            defaultOption.value = "";
            selectElement.appendChild(defaultOption);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.city_id;
                newOption.text = element.city_name;
                selectElement.appendChild(newOption);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function GetRegionByCluster2(id) {
    const selectedValue = id.value;
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("id", selectedValue);
    formData.append("_token", csrfToken);

    $.ajax({
        url: "/api/get-region-by-cluster",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response.data;
            const selectElement = document.getElementById("region_id2");

            selectElement.innerHTML = "";

            const defaultOption = document.createElement("option");
            defaultOption.text = "Select a resion";
            defaultOption.value = "";
            selectElement.appendChild(defaultOption);

            data.forEach((element) => {
                const newOption = document.createElement("option");
                newOption.value = element.region_id;
                newOption.text = element.region_name;
                selectElement.appendChild(newOption);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
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
}
function LoadAll(){
     GetAllCluster();
     GetAllClusterSelect();
     GetAllRegion();
}
$(document).ready(function () {
   LoadAll();

   dataGetter("https://psgc.cloud/api/regions").then((data) => {
       console.log(data);
   });
});
