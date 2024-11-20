function GetAllEntry() {
    const form = document.getElementById("searchEntry");
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData(form);
    formData.append("_token", csrfToken);

    $.ajax({
        url: "/api/product-report", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response;

            // Initialize or refresh DataTable
            $("#product-table").DataTable({
                data: data,
                destroy: true, // Destroy previous table instance to avoid duplication
                columns: [
                    { data: "cluster" },
                    { data: "area" },
                    { data: "address" },
                    { data: "distributor" },
                    { data: "retail_name" },
                    {
                        data: "purchase_date",
                        render: function (data, type, row) {
                            const date = new Date(data);
                            const options = {
                                month: "2-digit",
                                day: "2-digit",
                                year: "numeric",
                                hour: "2-digit",
                                minute: "2-digit",
                                hour12: true,
                            };
                            return date.toLocaleString("en-US", options);
                        },
                    },
                    { data: "product" },
                ],
                dom: "Bfrtip", // Enables buttons layout
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                scrollY: "400px", // Set vertical scroll height (adjust as needed)
                scrollCollapse: true, // Allow table height to shrink if fewer rows
                searching: true,
                info: true, // Show table info
                footerCallback: function (row, data, start, end, display) {
                    const totalRows = data.length;
                    $("#total-rows").html("Total Products: " + totalRows);
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
