

function loadCard() {
    $.ajax({
        url: "/api/get-all-event", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const cardContainer = document.getElementById("eventCard");

            response.forEach((element) => {
                let link = document.createElement("a");
                link.setAttribute(
                    "href",
                    `/raffle/events/results?event=${element.event_id}`
                );
                link.classList.add("card-link");
                link.classList.add("col-md-6", "col-lg-3");
                
                let colDiv = document.createElement("div");
                colDiv.classList.add("col-md-6", "col-lg-3");

                let cardDiv = document.createElement("div");
                cardDiv.classList.add("card", "card-link-rotate");

                let ribbonDiv = document.createElement("div");
                if (element.event_status == "Active") {
                    ribbonDiv.classList.add("ribbon", "bg-success");
                    ribbonDiv.textContent = "Active";
                } else {
                    ribbonDiv.classList.add("ribbon", "bg-danger");
                    ribbonDiv.textContent = "Inactive";
                }

                let cardBodyDiv = document.createElement("div");
                cardBodyDiv.classList.add("card-body");

                let cardTitle = document.createElement("h3");
                cardTitle.classList.add("card-title");
                cardTitle.textContent = element.event_name;

                let cardParagraph = document.createElement("p");
                cardParagraph.classList.add("text-secondary");
                cardParagraph.textContent = element.event_description;

                cardBodyDiv.appendChild(cardTitle);
                cardBodyDiv.appendChild(cardParagraph);
                cardDiv.appendChild(ribbonDiv);
                cardDiv.appendChild(cardBodyDiv);
                // colDiv.appendChild(cardDiv);

                link.appendChild(cardDiv);

                cardContainer.appendChild(link);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

function submitdata(formID,route){
    if (!validateForm(formID)) {
        alertify.error("Please fill in all required fields.");
        return;
    }
    loading(true)
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
            if(response.success){
                alertify.success(response.message);
                document.getElementById(formID).reset();
                dynamicCall(response.reload);
            }else{
                alertify.alert("Warning",response.message, function () {
                });
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

function dynamicCall(functionName, ...args) {
    if (typeof window[functionName] === "function") {
        window[functionName](...args); // Call the function dynamically with any passed arguments
    } else {
        console.error("Function not found:", functionName);
    }
}

$(document).ready(function () {
    loadCard();
});
