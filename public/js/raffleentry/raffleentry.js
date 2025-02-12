document.getElementById('event_id').addEventListener('change', ()=> {
    GetAllEntry();
});

document.getElementById('region').addEventListener('change', ()=> {
    GetAllEntry();
});

let entryId;

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
                className: 'dt-button copy-btn btn-attri',
                text: '<i class="fas fa-clipboard"></i> Copy',


            },

            {
                extend: 'csv',
                className: 'dt-button csv-btn btn-attri2',
                text: '<i class="fas fa-file-csv"></i> Export All to CSV',
                action: function (e, dt, button, config) {
                    fetchAllData("/api/get-all-entry", "csv", button);
                },
            },

            {
                extend: 'print',
                className: 'dt-button print-btn btn-attri3',
                text: '<i class="fas fa-print"></i> Print',
                action: function (e, dt, button, config) {
                    fetchAllData("/api/get-all-entry", "print", button);
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
             $("#rto").text(rowData.retail_code || "N/A");
            $("#coupon").text(rowData.serial_number || "N/A");
            $("#product").text(rowData.product_type || "N/A");
            $("#name").text(rowData.customer_name || "N/A");
            $("#email").text(rowData.customer_email || "N/A");
            $("#phone").text(rowData.customer_phone || "N/A");
             $("#age").text(rowData.customer_age || "N/A");
             $("#created_at").text(formatDateTime(rowData.created_at));
             $("#qr_code").text(rowData.qr_code);
            entryId = rowData.entry_id;
            // Show the modal
            $("#viewModal").modal("show");
        } else {
            console.warn("No data available for the clicked row.");
        }
    });
}

const deleteEntryBtn = document.getElementById('deleteEntry');

if(deleteEntryBtn){
    deleteEntryBtn.addEventListener('click', ()=> {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
          }).then((result) => {
            if (result.isConfirmed) {
              loading(true);

              const csrf = getCsrf();
              $.ajax({
                type: "DELETE",
                url: "/api/remove/entry",
                data: {"_token": csrf, "id": entryId},
                success: res=> {
                    loading(false);
                    dataParser(res);
                    GetAllEntry();
                    exec('closeEntryModal');
                }, error: xhr=> console.log(xhr.responseText)
              })
            }
          });
    });
}

function fetchAllData(url, format, button) {
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

            $(button).removeClass('processing');
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
        alertify.set('notifier','position', 'top-right');
        alertify.warning("No Data to export! Please try again later");
    }
}

function printData(data) {
    alertify.set('notifier','position', 'top-right');
    if(data.length == 0){
        alertify.warning("No Data to Print! Please try again later");
        return;
    }
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
        <table border="1" class="tableSize">
            <thead>
                <tr>${tableHeader}</tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    const headerContent = `
        <div class="header">
            <img src="/unioil_images/unioil_logo.png" alt="Unioil Logo" class="imageSize">
            <div class="gray font-12"> <span class="font-12"> Printed on: </span> ${new Date().toLocaleString()}</div>
        </div>
    `;

    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
            <head>
                <title>Raffle Entries Report</title>

                <style nonce="${nonceValue}">
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    .content {
                        padding: 20px;
                        margin-top: 100px;
                    }
                    table {
                        table-layout: auto;
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        word-wrap: break-word;
                        max-width: 100px;
                        white-space: normal;
                    }
                    th {
                        background-color: #fcbc9e;
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                            zoom: 80%;
                        }
                        .header {
                            position: fixed;
                            top: 0;
                            width: 100%;
                            text-align: center;
                            padding: 10px;
                            background-color: #ffffff;
                            border-bottom: 1px solid #ccc;
                            z-index: 10;
                        }
                        .content {
                            margin-top: 120px; /* Make sure the content is below the fixed header */
                        }
                        table {
                            page-break-before: always;
                        }
                        .page-break {
                            page-break-before: always;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">${headerContent}</div>
                <div class="content">${tableContent}</div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

$(document).ready(function () {
    GetAllEntry();
});
