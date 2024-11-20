
function GetAllEntry() {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    const tableId = "#entryTable";

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

   const table = $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/get-all-entry',
            type: 'POST',
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataSrc: 'data',
            data: function (d) {
                d.region = $("#region").val();
                d.event_id = $("#event_id").val();
            },
        },
        columns: [
            { data: "cluster" },
            { data: "retail_name" },
            { data: "serial_number" },
            { data: "product_type" },
            { data: "customer_name" },
            { data: "customer_email" },
            { data: "customer_phone" },
        ],
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        paging: true,
        searching: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });

    $("#entryTable").on("click", "tbody tr", function () {
        const rowData = table.row(this).data(); // Get data for the clicked row

        if (rowData) {
            // Populate modal content only if rowData exists
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
        } else {
            console.warn("No data available for the clicked row.");
        }
    });
}



$(document).ready(function () {
    GetAllEntry();
});
