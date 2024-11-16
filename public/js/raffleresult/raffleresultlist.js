function addWinnerRow() {

     $.ajax({
         url: "/api/get-all-winner", // Replace with your endpoint URL
         type: "GET",
         success: function (response) {
              const tableBody = document.getElementById("winnerListTable");
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
                   tdPrize.textContent = element.event_price;
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
                  deleteButton.classList.add("btn", "btn-warning");
                  deleteButton.addEventListener("click", function () {


                    alertify.confirm("Warning",
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
        window[functionName](...args); // Call the function dynamically with any passed arguments
    } else {
        console.error("Function not found:", functionName);
    }
}

function getevent(){
     $.ajax({
         url: "/api/get-all-winner", // Replace with your endpoint URL
         type: "GET",
         success: function (response) {
            console.log(response);
         },
         error: function (xhr, status, error) {
             console.error("Error posting data:", error);
         },
     });
}

$(document).ready(function () {
    addWinnerRow();
});