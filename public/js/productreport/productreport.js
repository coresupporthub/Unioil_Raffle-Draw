function GetAllEntry() {
    const form = document.getElementById("searchEntry");
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData(form);
    formData.append("_token", csrfToken);

    // Initialize DataTable
    $("#product-table").DataTable({
        ajax: {
            url: "/api/product-report", // Your API endpoint
            type: "POST",
            data: function (d) {
                formData.forEach((value, key) => {
                    d[key] = value; // Append additional form data (like search parameters)
                });
            },
            dataSrc: function (response) {
                return response.data; // Return paginated data
            },
        },
        serverSide: true, // Enable server-side processing
        processing: true, // Show processing indicator
        destroy: true, // Destroy any previous DataTable instances to avoid duplication
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
        dom: "Bfrtip", // Enable buttons in the top section
        buttons: [
            {
                extend: "copy",
                exportOptions: {
                    modifier: {
                        page: "all", // Export data from all pages
                    },
                },
            },
            {
                extend: "csv",
                exportOptions: {
                    modifier: {
                        page: "all", // Export data from all pages
                    },
                },
            },
            {
                extend: "excel",
                exportOptions: {
                    modifier: {
                        page: "all", // Export data from all pages
                    },
                },
            },
            {
                extend: "pdf",
                exportOptions: {
                    modifier: {
                        page: "all", // Export data from all pages
                    },
                },
            },
            {
                extend: "print",
                exportOptions: {
                    modifier: {
                        page: "all", // Export data from all pages
                    },
                },
            },
        ],
        paging: true, // Enable pagination
        pageLength: 10, // Default page size
        searching: true,
        info: true, // Show table info
        lengthMenu: [10, 25, 50, 100], // Options for page length
        footerCallback: function (row, data, start, end, display) {
            var totalRows = this.api().data().length;
            var totalRecords = this.api().page.info().recordsTotal;
            $("#total-rows").html("Total Products: " + totalRecords);
        },
    });
}

$(document).ready(function () {
    GetAllEntry();
});
