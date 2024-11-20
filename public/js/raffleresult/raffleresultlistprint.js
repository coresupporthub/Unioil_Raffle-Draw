function addWinnerRow() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const eventId = urlParams.get("event");

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("event_id", eventId);

    $.ajax({
        url: "/api/event-winner",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const tableBody = document.getElementById("winnerListTable");
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }
            response.forEach((element) => {
                let newRow = document.createElement("tr");

                let tdIndex = document.createElement("td");
                tdIndex.textContent = element.serial_number;
                newRow.appendChild(tdIndex);

                let tdCluster = document.createElement("td");
                tdCluster.textContent = element.cluster;
                newRow.appendChild(tdCluster);

                let tdPrize = document.createElement("td");
                tdPrize.textContent = element.event_prize;
                newRow.appendChild(tdPrize);

                let tdWinnerName = document.createElement("td");
                tdWinnerName.textContent = element.customer_name;
                newRow.appendChild(tdWinnerName);

                let tdEmail = document.createElement("td");
                tdEmail.textContent = element.customer_email;
                newRow.appendChild(tdEmail);

                tableBody.appendChild(newRow);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function addUnclaimrRow() {
    const queryString = window.location.search;

    // Create a URLSearchParams object
    const urlParams = new URLSearchParams(queryString);

    // Get a specific query parameter
    const eventId = urlParams.get("event"); // Replace 'event' with your parameter name

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("event_id", eventId);

    $.ajax({
        url: "/api/event-unclaim", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const tableBody = document.getElementById("unclaim-table");
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }
            response.forEach((element) => {
                let newRow = document.createElement("tr");

                // Create table cells for each column and add content
                let tdIndex = document.createElement("td");
                tdIndex.textContent = element.serial_number; // Example: index or serial number
                newRow.appendChild(tdIndex);

                let tdCluster = document.createElement("td");
                tdCluster.textContent = element.cluster;
                newRow.appendChild(tdCluster);

                let tdPrize = document.createElement("td");
                tdPrize.textContent = element.event_prize;
                newRow.appendChild(tdPrize);

                let tdWinnerName = document.createElement("td");
                tdWinnerName.textContent = element.customer_name;
                newRow.appendChild(tdWinnerName);

                let tdEmail = document.createElement("td");
                tdEmail.textContent = element.customer_email;
                newRow.appendChild(tdEmail);

                tableBody.appendChild(newRow);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function redraw(serial) {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("serial", serial);

    $.ajax({
        url: "/api/raffle-redraw",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            dynamicCall(response.reload);
            alertify.alert("Message", response.message, function () {});
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });
}

function dynamicCall(functionName, ...args) {
    if (typeof window[functionName] === "function") {
        window[functionName](...args);
    } else {
        console.error("Function not found:", functionName);
    }
}

function getevent() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const eventId = urlParams.get("event");

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("event_id", eventId);

    $.ajax({
        url: "/api/event-selected",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            document.getElementById("title_event_name").textContent =
                response.event_name;
            document.getElementById("title_start").textContent =
                response.event_start;
            document.getElementById("title_end").textContent =
                response.event_end;
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });
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
            loading(false);
            if (response.success) {
                dynamicCall(response.reload);
                addWinnerRow();
                alertify.success(response.message);
            } else {
                alertify.alert("Warning", response.message, function () {});
            }
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });
}

function confirmation() {
    alertify.confirm(
        "Warning",
        "Are you sure you want to set this raffle event to inactive?",
        function () {
            SubmitData("update-event-form", "/api/inactive-event");
        },
        function () {
            alertify.error("Cancel");
        }
    );
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

function triggerPrint() {
    window.print();
}

$(document).ready(function () {
    addWinnerRow();
    getevent();
    addUnclaimrRow();
});
