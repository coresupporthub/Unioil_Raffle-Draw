function GetAllEntry() {
    $.ajax({
        url: "/api/get-all-entry", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const data = response;
            $("#entryTable").DataTable({
                data: data,
                destroy: true,
                columns: [
                    { data: "cluster" },
                    { data: "retail_name" },
                    { data: "serial_number" },
                    { data: "product_type" },
                    { data: "customer_name" },
                    { data: "customer_email" },
                    { data: "customer_phone" },
                ],
                layout: {
                    topStart: {
                        buttons: ["copy", "csv", "excel", "pdf", "print"],
                    },
                },
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

$(document).ready(function () {
    GetAllEntry();
});