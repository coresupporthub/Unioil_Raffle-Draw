
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
        buttons: [
            {
                extend: 'copy',
                className: 'dt-button copy-btn',
                text: '<i class="fas fa-clipboard"></i> Copy',
                attr: {
                    style: 'background-color: #34bfa3; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                }

            },            // {
            //     extend: "csv",
            //     text: "Export All to CSV",
            //     action: function (e, dt, button, config) {
            //         fetchAllData("/api/get-all-entry", "csv");
            //     },
            // },
            {
                extend: 'csv',
                className: 'dt-button csv-btn',
                text: '<i class="fas fa-file-csv"></i> Export All to CSV',
                attr: {
                    style: 'background-color: #ffc107; color: black; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                },
                action: function (e, dt, button, config) {
                    fetchAllData("/api/get-all-entry", "csv");
                },
            },
            // {
            //     extend: "print",
            //     text: "Print All",
            //     action: function (e, dt, button, config) {
            //         fetchAllData("/api/get-all-entry", "print");
            //     },
            // },
            {
                extend: 'print',
                className: 'dt-button print-btn',
                text: '<i class="fas fa-print"></i> Print',
                attr: {
                    style: 'background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                },
                action: function (e, dt, button, config) {
                    fetchAllData("/api/get-all-entry", "print");
                },
            }
        ],
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

function fetchAllData(url, format) {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: {
            region: $("#region").val(),
            event_id: $("#event_id").val(),
            allData: true,
        },
        success: function (response) {
            const data = response.data;
            if (format === "csv") {
                exportToCSV(data, "export_all_data.csv");
            } else if (format === "print") {
                printData(data);
            }
        },
        error: function () {
            alert("Failed to fetch data for export.");
        },
    });
}
function exportToCSV(data, filename) {

    if (Array.isArray(data) && data.length > 0) {

        const headers = Object.keys(data[0]);


        const escapeCsvValue = (value) => {
            if (typeof value === 'string') {

                value = value.replace(/\r/g, '');
                value = value.replace(/\n/g, ' ');
                value = value.replace(/#/g, '%23');


                if (value.includes(',') || value.includes('"')) {
                    return `"${value.replace(/"/g, '""')}"`;
                }
            }
            return value;
        };

        const csvContent = "data:text/csv;charset=utf-8,"
            + headers.map(header => escapeCsvValue(header)).join(",") + "\n"
            + data.map(row => headers.map(header => escapeCsvValue(row[header])).join(",")).join("\n");

        console.log("Generated CSV content:", csvContent);

        const encodedUri = encodeURI(csvContent);


        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);


        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        console.warn("No data available for export.");
    }
}


function printData(data) {

    const headers = Object.keys(data[0]);
    const tableHeader = headers.map(header => `<th>${header}</th>`).join("");

    const tableRows = data
        .map(row => {
            const rowData = Object.values(row)
                .map(value => `<td>${value || "N/A"}</td>`)
                .join("");
            return `<tr>${rowData}</tr>`;
        })
        .join("");

    const tableContent = `
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>${tableHeader}</tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Data</title>
                <style>
                    table {
                        font-family: Arial, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                ${tableContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}


$(document).ready(function () {
    GetAllEntry();
});
