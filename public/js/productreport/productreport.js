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
                dom: "Bfrtip",
                buttons: [
                    {
                        extend: "copy",
                        className: "dt-button copy-btn",
                        text: '<i class="fas fa-clipboard"></i> Copy',
                        attr: {
                            style: "background-color: #34bfa3; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;",
                        },
                    },
                    {
                        extend: "csv",
                        className: "dt-button csv-btn",
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        attr: {
                            style: "background-color: #ffc107; color: black; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;",
                        },
                        exportOptions: {
                            modifier: {
                                page: 'all',
                            },
                            format: {
                                body: function (data, row, column, node) {
                                    return data; // No formatting needed for CSV data
                                }
                            }
                        },
                        footerCallback: function (row, data, start, end, display) {
                            const totalRows = data.length;
                            // Add the total rows footer line after the table content in CSV
                            const footer = ["", "", "", "", "", "", "Total Products: " + totalRows];
                            data.push(footer); // Append the footer to the data
                        }
                    },
                    {
                        extend: "excel",
                        className: "dt-button excel-btn",
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        attr: {
                            style: "background-color: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;",
                        },
                        footerCallback: function (row, data, start, end, display) {
                            const totalRows = data.length;
                            // Set up the footer row
                            const footer = ["", "", "", "", "", "", "Total Products: " + totalRows];
                            data.push(footer); // Append footer at the end
                        }
                    },
                    {
                        extend: "print",
                        className: "dt-button print-btn",
                        text: '<i class="fas fa-print"></i> Print',
                        attr: {
                            style: "background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;",
                        },
                        customize: function (win) {
                            // Custom header for print view
                            $(win.document.body).css(
                                "font-family",
                                "Arial, sans-serif"
                            );

                            // Add header image and text
                            $(win.document.body).prepend(`
                                <div style="text-align: center; padding: 20px;">
                                    <img src="/unioil_images/unioil_logo.png" alt="Unioil Logo" style="max-height: 50px; width: auto; margin-bottom: 10px;">
                                    <div style="font-size: 14px; color: gray;">Printed on: ${new Date().toLocaleString()}</div>
                                </div>
                            `);

                            // Customize table styles in the print version
                            $(win.document.body).find("table").css({
                                "border-collapse": "collapse",
                                width: "100%",
                            });

                            $(win.document.body).find("th, td").css({
                                border: "1px solid #dddddd",
                                "text-align": "left",
                                padding: "8px",
                            });

                            $(win.document.body).find("th").css({
                                "background-color": "#f2f2f2",
                            });

                            // Adjust the content position to avoid overlap with the header
                            $(win.document.body).find("table").css({
                                "margin-top": "100px",
                            });
                        },
                    },
                    {
                        text: "PDF",
                        className: "dt-button pdf-btn",
                        action: function () {
                            const totalRows = data.length; // Get total rows
                            generatePDF(data, totalRows); // Pass data and totalRows
                        },
                        attr: {
                            style: "background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s ease, transform 0.3s ease;",
                        },
                    },
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

function generatePDF(data, totalRows) {
    const { jsPDF } = window.jspdf;

    const headers = Object.keys(data[0]);
    const doc = new jsPDF();

    // Set the page size to a smaller value to simulate zooming out
    const scale = 0.8; // 80% scale

    // Header with Image
    doc.setFontSize(16);
    doc.addImage(
        "/unioil_images/unioil_logo.png",
        "PNG",
        10,
        10,
        50 * scale,
        15 * scale
    ); // Adjust image size based on scale

    // Adjust the "Printed on" text size and position
    doc.setFontSize(8); // Smaller font size for "Printed on"
    doc.text("Printed on: " + new Date().toLocaleString(), 10, 30 * scale + 2); // Adjust Y position (2 units lower)

    // Adding Table Content
    const tableStartY = 50 * scale + 2; // Start Y position for the table
    doc.autoTable({
        head: [headers],
        body: data.map((row) => Object.values(row)),
        startY: tableStartY, // Set startY to a position after the header and "Printed on" text
        theme: "striped",
        styles: {
            fontSize: 10 * scale, // Scale down the font size for table content
        },
        headStyles: {
            fillColor: [169, 169, 169], // Light gray color for the header
            textColor: 255, // White text color
        },
        bodyStyles: {
            fillColor: [245, 245, 245], // Change row background color if needed
        },
    });

    // Footer Design: Add a background, border, and total rows text
    const footerY = doc.internal.pageSize.height - 20; // Set footer 20 units from the bottom of the page
    const footerHeight = 10; // Height of the footer box

    // Draw a rectangle for the footer background
    doc.setFillColor(169, 169, 169); // Set footer background color (blue)
    doc.rect(
        0,
        footerY - footerHeight,
        doc.internal.pageSize.width,
        footerHeight,
        "F"
    );

    // Footer text
    doc.setFontSize(8); // Font size for footer text
    doc.setTextColor(255, 255, 255); // Set text color to white
    doc.text(`Total Products: ${totalRows}`, 10, footerY - 3); // Display total rows in the footer

    // Optional: Add a border line at the top of the footer
    doc.setLineWidth(0.5);
    doc.setDrawColor(255, 255, 255); // White color for border
    doc.line(
        0,
        footerY - footerHeight,
        doc.internal.pageSize.width,
        footerY - footerHeight
    ); // Draw the border

    // Optional: Add page number in the footer
    const pageCount = doc.internal.pages.length;
    doc.setFontSize(8);
    doc.text(
        `Page ${doc.internal.getNumberOfPages()}/${pageCount}`,
        doc.internal.pageSize.width - 30,
        footerY - 3
    );

    // Save the generated PDF
    doc.save("data-report.pdf");
}


$(document).ready(function () {
    GetAllEntry();
});
