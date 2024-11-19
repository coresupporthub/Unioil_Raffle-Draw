
function GetAllEntry() {
    const form = document.getElementById("searchEntry");
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData(form);
    formData.append("_token", csrfToken);

    $.ajax({
        url: "/api/get-all-entry", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response;

            console.log(data);
            // Initialize DataTable and save instance
            const table = $("#entryTable").DataTable({
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
                dom: "Bfrtip", // Enables buttons layout
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                paging: true,
                searching: true, // Default search bar
                info: true, // Show table info
            });

            // Add click event for rows
            $("#entryTable tbody").on("click", "tr", function () {
                const rowData = table.row(this).data(); // Get data for the clicked row

                // Populate modal content
                $("#regiondisplay").text(rowData.cluster || "N/A");
                $("#area").text(rowData.area || "N/A");
                $("#address").text(rowData.address || "N/A");
                $("#distributor").text(rowData.distributor || "N/A");
                $("#store").text(rowData.retail_name || "N/A");
                $("#coupon").text(rowData.serial_number || "N/A");
                $("#product").text(rowData.product_type || "N/A");
                $("#name").text(rowData.customer_name || "N/A");
                $("#email").text(rowData.customer_email || "N/A");
                $("#phone").text(rowData.customer_phone || "N/A");

                // Show the modal
                $("#viewModal").modal("show");
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
