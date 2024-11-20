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
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'dt-button copy-btn',
                        text: '<i class="fas fa-clipboard"></i> Copy',
                        attr: {
                            style: 'background-color: #34bfa3; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                        }

                    },
                    {
                        extend: 'csv',
                        className: 'dt-button csv-btn',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        attr: {
                            style: 'background-color: #ffc107; color: black; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'dt-button excel-btn',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        attr: {
                            style: 'background-color: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'dt-button pdf-btn',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        attr: {
                            style: 'background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                        }
                    },
                    {
                        extend: 'print',
                        className: 'dt-button print-btn',
                        text: '<i class="fas fa-print"></i> Print',
                        attr: {
                            style: 'background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;',
                        }
                    }
                ],          
                paging: false, 
                scrollY: "400px", 
                scrollCollapse: true, 
                searching: true,
                info: true, 
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
