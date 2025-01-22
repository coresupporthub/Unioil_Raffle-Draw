function addWinnerRow() {

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
         url: "/api/event-winner", // Replace with your endpoint URL
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

                 // Add a click event to display the modal
                 newRow.addEventListener("click", function () {
                     showModal(element); // Pass the row data to the modal function
                 });

                 // Create table cells for each column and add content
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

                 let tdAction = document.createElement("td");
                 let deleteButton = document.createElement("button");
                 deleteButton.textContent = "Redraw";
                 deleteButton.className = "hide-me";
                 deleteButton.classList.add("btn", "btn-warning");
                 deleteButton.addEventListener("click", function (event) {
                     event.stopPropagation(); // Prevent the row click event from triggering
                     alertify.confirm(
                         "Warning",
                         "Are you sure you want to redraw for this regional cluster?",
                         function () {
                             redraw(element.serial_number);
                         },
                         function () {
                             alertify.error("Cancel");
                         }
                     );
                 });
                 tdAction.appendChild(deleteButton);
                 newRow.appendChild(tdAction);

                 tableBody.appendChild(newRow);
             });

         },
         error: function (xhr, status, error) {
             console.error("Error fetching data:", error);
         },
     });
}

function showModal(data) {
     $("#regiondisplay").text(data.cluster || "N/A");
     $("#area").text(data.retail_area || "N/A");
     $("#address").text(data.address || "N/A");
     $("#distributor").text(data.retail_distributor || "N/A");
     $("#store").text(data.retail_name || "N/A");
     $("#coupon").text(data.serial_number || "N/A");
     $("#name").text(data.customer_name || "N/A");
     $("#email").text(data.customer_email || "N/A");
     $("#phone").text(data.customer_number || "N/A");
     $("#age").text(data.customer_age || "N/A");

    const myModal = new bootstrap.Modal(document.getElementById("viewModal"));
    myModal.show();
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
            addUnclaimrRow();
            alertify.alert("Message", response.message, function () {});
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
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

function getevent(){
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
        url: "/api/event-selected", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if(response.event_status=='Inactive'){
                document.querySelectorAll(".hide-me").forEach((element) => {
                    element.style.display = "none";
                });
            }
            document.getElementById("title_event_name").textContent =
                response.event_name;
            document.getElementById("title_start").textContent =
                response.event_start;
            document.getElementById("title_end").textContent =
                response.event_end;
            document.getElementById("prize_label").textContent =
                response.event_prize;
            document.getElementById("event_id").value = response.event_id;
            document.getElementById("event_id2").value = response.event_id;
             document.getElementById("event_id3").value = response.event_id;
            setValue('event_idInactive', response.event_id);
            document.getElementById("event_name").value = response.event_name;
            document.getElementById("event_price").value = response.event_prize;
            document.getElementById("event_start").value = response.event_start;
            document.getElementById("event_end").value = response.event_end;
            document.getElementById("event_description").value =
                response.event_description;

            document.getElementById("event-image").src = "data:image/png;base64," + response.event_prize_image;
            document.getElementById("event-banner").src = "data:image/png;base64," + response.event_banner;
            document.getElementById("event-banner-modal").src ="data:image/png;base64," + response.event_banner;
             document.getElementById("event-prize-modal").src = "data:image/png;base64," + response.event_prize_image;
                if(response.event_status == 'Inactive'){
                    hide('inactiveBtn');
                }
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });
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

document.getElementById('updateEventBanner').addEventListener('click', ()=> {
    SubmitData('update-event-banner-form', '/api/update-event-banner')
});

document.getElementById('updateEventRaffleImage').addEventListener('click', ()=> {
    SubmitData('update-event-image-form', '/api/update-event-images')
});

document.getElementById('updateEvent').addEventListener('click', ()=> {
    SubmitData('update-event-form', '/api/update-event');
});

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
                exec('openUpdateModal');
            } else {
                alertify.alert("Warning", response.message, function () {});
            }
        },
        error: function (xhr, status, error) {
            console.error("Error posting data:", error);
        },
    });
}

$(document).ready(function () {

    addWinnerRow();
    getevent();
    addUnclaimrRow();

});


document.getElementById('confirmInactiveBtn').addEventListener('click', ()=> {

   const inputs = [
    ['adminPassword', 'adminPasswordE']
   ];

   if(checkValidity(inputs)){
    loading(true);

    $.ajax({
        type: "POST",
        url: "/api/inactive-event",
        data: $('#confirmInactiveForm').serialize(),
        success: res=> {
            loading(false);
            addWinnerRow();
            dataParser(res);
            getevent();
            exec('closeAdminPasswordModal');
        }, error: xhr=> console.log(xhr.responseText)
    });
   }
});
