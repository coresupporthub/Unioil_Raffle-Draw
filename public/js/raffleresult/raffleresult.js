const dateRanges = [];

function loadCard() {
    $.ajax({
        url: "/api/get-all-event", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
             response.forEach((element) => {
                 dateRanges.push({
                     from: element.event_start,
                     to: element.event_end,
                 });
             });
            const cardContainer = document.getElementById("eventCard");
            while (cardContainer.firstChild) {
                cardContainer.removeChild(cardContainer.firstChild);
            }
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

                 let eventDate = document.createElement("p");
                 eventDate.classList.add("text-primary");
                 eventDate.textContent = element.event_start + " - " + element.event_end;

                cardBodyDiv.appendChild(cardTitle);
                cardBodyDiv.appendChild(cardParagraph);
                cardBodyDiv.appendChild(eventDate);
                cardDiv.appendChild(ribbonDiv);
                cardDiv.appendChild(cardBodyDiv);
                // colDiv.appendChild(cardDiv);

                link.appendChild(cardDiv);

                cardContainer.appendChild(link);
            });

            flatpickr("#event_start", {
                dateFormat: "Y-m-d", // Format as YYYY-MM-DD
                minDate: "today", // Disable past dates
                disable: dateRanges, // Use the array of date ranges
            });
            flatpickr("#event_end", {
                dateFormat: "Y-m-d", // Format as YYYY-MM-DD
                minDate: "today", // Disable past dates
                disable: dateRanges, // Use the array of date ranges
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

document.getElementById('addEvent').addEventListener('click', ()=> {
    submitdata('add-event-form',`/api/add-event`);
});

function submitdata(formID, route) {

    const inputs = [
        ['event_name', 'event_name_e'],
        ['event_prize', 'event_prize_e'],
        ['event_start', 'event_start_e'],
        ['event_end', 'event_end_e'],
        ['image', 'image_e'],
        ['banner', 'banner_e'],
        ['event_description', 'event_description_e'],
    ];

    if(checkValidity(inputs)){
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
                    alertify.success(response.message);
                    document.getElementById(formID).reset();
                    dynamicCall(response.reload);
                    exec('closeAddEventModal');
                } else {
                    alertify.alert("Warning", response.message, function () {});
                }
            },
            error: function (xhr, status, error) {
                console.error("Error posting data:", error);
            },
        });
    }

}

function dynamicCall(functionName, ...args) {
    if (typeof window[functionName] === "function") {
        window[functionName](...args);
    } else {
        console.error("Function not found:", functionName);
    }
}

$(document).ready(function () {
    loadCard();
});
