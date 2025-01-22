document.getElementById('event_id').addEventListener('change', ()=> {
    GetAllEntry();
});

document.getElementById('region').addEventListener('change', ()=> {
    GetAllEntry();
});
document.getElementById('ptype').addEventListener('change', ()=> {
    GetAllEntry();
});

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
                        className: "dt-button copy-btn btnCopy",
                        text: '<i class="fas fa-clipboard"></i> Copy',
                    },
                    {
                        extend: "csv",
                        className: "dt-button csv-btn btnCsv",
                        text: '<i class="fas fa-file-csv"></i> CSV',
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
                        className: "dt-button excel-btn btnExcel",
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        footerCallback: function (row, data, start, end, display) {
                            const totalRows = data.length;
                            // Set up the footer row
                            const footer = ["", "", "", "", "", "", "Total Products: " + totalRows];
                            data.push(footer); // Append footer at the end
                        }
                    },
                    {
                        text: "PDF",
                        className: "dt-button pdf-btn btnPdf",
                        action: function () {
                            const totalRows = data.length; // Get total rows
                            generatePDF(data, totalRows); // Pass data and totalRows
                        },

                    },
                    {
                        extend: "print",
                        className: "dt-button print-btn btnPrint",
                        text: '<i class="fas fa-print"></i> Print',
                        customize: function (win) {
                            // Custom header for print view
                            $(win.document.body).css(
                                "font-family",
                                "Arial, sans-serif"
                            );

                            $(win.document.body).find('h1').remove();

                            // Add header image and text
                            $(win.document.body).prepend(`
                                <div class="headerImage">
                                    <img src="/unioil_images/unioil_logo.png" alt="Unioil Logo" class="imageClass">
                                    <div class="text-color">Printed on: ${new Date().toLocaleString()}</div>
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
                                "font-size": "12px",
                            });

                            $(win.document.body).find("th").css({
                                "background-color": "#fcbc9e",
                                "color": "black"
                            });

                            // Adjust the content position to avoid overlap with the header
                            $(win.document.body).find("table").css({
                                "margin-top": "30px",
                            });
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

    alertify.set('notifier','position', 'top-right');
    if(data.length == 0){
        alertify.warning("No Data to Print! Please try again later");
        return;
    }

    const { jsPDF } = window.jspdf;

    const headers = Object.keys(data[0]);
    const doc = new jsPDF();

    // Set the page size to a smaller value to simulate zooming out
    const scale = 0.8; // 80% scale

    // Calculate center position for logo (based on page width)
    const pageWidth = doc.internal.pageSize.width;
    const logoWidth = 50 * scale; // Width of the logo
    const logoX = (pageWidth - logoWidth) / 2;

    // Add the logo centered on the page
    doc.setFontSize(16);
    doc.addImage(
        "/unioil_images/unioil_logo.png",
        "PNG",
        logoX,
        10,
        logoWidth,
        15 * scale
    ); // Adjust image size based on scale

    // Adjust the "Printed on" text size and position (centered)
    doc.setFontSize(8); // Smaller font size for "Printed on"
    const printedText = "Printed on: " + new Date().toLocaleString();
    const textWidth = doc.getStringUnitWidth(printedText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
    const textX = (pageWidth - textWidth) / 2;
    doc.text(printedText, textX, 30 * scale + 2); // Adjust Y position (2 units lower)


    // Adding Table Content
    const tableStartY = 40 * scale + 2; // Start Y position for the table
    const headerBgColor = [252, 188, 158];
    doc.autoTable({
        head: [headers],
        body: data.map((row) => Object.values(row)),
        startY: tableStartY, // Set startY to a position after the header and "Printed on" text
        theme: "grid",
        styles: {
            fontSize: 10 * scale, // Scale down the font size for table content
        },
        headStyles: {
            fillColor: headerBgColor, // Set header background color
            textColor: 0, // Black text color
        },
        bodyStyles: {
            textColor: 0, // Black text color
        },

    });

    // Footer Design: Add a background, border, and total rows text
    const footerY = doc.internal.pageSize.height - 20; // Set footer 20 units from the bottom of the page
    const footerHeight = 10; // Height of the footer box

    // Draw a rectangle for the footer background
    doc.setFillColor(252, 188, 158); // Set footer background color (blue)
    doc.rect(
        0,
        footerY - footerHeight,
        doc.internal.pageSize.width,
        footerHeight,
        "F"
    );

    // Footer text
    doc.setFontSize(8); // Font size for footer text
    doc.setTextColor(0); // Set text color to white
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
    doc.save("product-report.pdf");
}


$(document).ready(function () {
    GetAllEntry();
});
