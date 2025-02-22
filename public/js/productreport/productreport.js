// document.getElementById('event_id').addEventListener('change', ()=> {
//     GetAllEntry();
// });

// document.getElementById('region').addEventListener('change', ()=> {
//     GetAllEntry();
// });
// document.getElementById('ptype').addEventListener('change', ()=> {
//     GetAllEntry();
// });

// document.getElementById('producttype').addEventListener('change', ()=> {
//     GetAllEntry();
// });

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


// $(document).ready(function () {
//     GetAllEntry();
// });


//-----------------------------------------------------------------------------------------------

function createListItem(name, description, containerId, prod_id, type='product') {
    const listItem = document.createElement("div");
    listItem.className = `list-group-item ${type === 'product' ? 'product-list-items' : 'archive-list-items'}`;

    const rowDiv = document.createElement("div");
    rowDiv.className = "row align-items-center";

    const colAuto1 = document.createElement("div");
    colAuto1.className = "col-auto";

    const avatarWrapper = document.createElement("div");
    avatarWrapper.className = "cursor-pointer";

    if(type === 'product'){
        avatarWrapper.setAttribute("data-bs-toggle", "modal");
        avatarWrapper.setAttribute("data-bs-target", "#product-reports");
    }

    avatarWrapper.addEventListener("click", () => {
        if(type == 'product'){
            fetchProductDetails(prod_id);
        }else{
            restoreArchive(prod_id)
        }
    });

    const avatarSpan = document.createElement("span");
    avatarSpan.className = "avatar";

    avatarWrapper.appendChild(avatarSpan);
    colAuto1.appendChild(avatarWrapper);


    const colText = document.createElement("div");
    colText.className = "col text-truncate";

    const nameLink = document.createElement("a");
    nameLink.href = "#";
    nameLink.className = "text-reset d-block";

    if(type === 'product'){
        nameLink.setAttribute("data-bs-toggle", "modal");
        nameLink.setAttribute("data-bs-target", "#product-reports");
    }

    nameLink.textContent = name;
    nameLink.addEventListener("click", () => {
        if(type == 'product'){
            fetchProductDetails(prod_id);
        }else{
            restoreArchive(prod_id)
        }
    });

    const descriptionDiv = document.createElement("div");
    descriptionDiv.className = "d-block text-muted text-truncate mt-n1";
    descriptionDiv.textContent = description;

    colText.appendChild(nameLink);
    colText.appendChild(descriptionDiv);


    const colAuto2 = document.createElement("div");
    colAuto2.className = "col-auto";

    const button = document.createElement("button");
    button.className = "list-group-item-actions btn btn-info";

    if(type === 'product'){
        button.setAttribute("data-bs-toggle", "modal");
        button.setAttribute("data-bs-target", "#product-reports");
    }

    button.textContent = type === "product" ? "View Reports" : "Restore";
    button.addEventListener("click", () => {
        if(type == 'product'){
            fetchProductDetails(prod_id);
        }else{
            restoreArchive(prod_id)
        }
    });

    colAuto2.appendChild(button);


    rowDiv.appendChild(colAuto1);
    rowDiv.appendChild(colText);
    rowDiv.appendChild(colAuto2);
    listItem.appendChild(rowDiv);

    document.getElementById(containerId).appendChild(listItem);
}

let productId;
let productDetails;

function loadProductDetails(status){
    const skeletons = document.querySelectorAll('.skeleton-text');
    const product_infos = document.querySelectorAll('.product-info');
    if(status){
        product_infos.forEach(infos => {
            infos.textContent = '';
            infos.classList.add('d-none');
        });
        skeletons.forEach(skeleton => {
            skeleton.classList.remove('d-none');
        });
    }else{
        skeletons.forEach(skeleton => {
            skeleton.classList.add('d-none');
        });

        product_infos.forEach(infos => {
          infos.textContent = '';
          infos.classList.remove('d-none');
        });
    }

}

function fetchProductDetails(id){
    loadProductDetails(true);
    optionsEditStatus(false);
    loadReports(id, getValue('region'), getValue('event_id'));
    productId = id;
    $.ajax({
        url: `/api/products/details/${id}`,
        type: "GET",
        dataType: "json",
        success: res=> {
            loadProductDetails(false);
            const product = res.product;
            productDetails = product;
            setText('info_prod_name', product.product_name);
            setText('info_prod_type', product.product_type);
            setText('info_prod_entry', product.entries == 1 ? 'Single Entry' : 'Dual Entry');

        }, error: xhr=> console.log(xhr.responseText)
    });
}


function loadingProduct(status, type = 'product'){
    const productLoader = document.getElementById('productLoader');
    const archiveLoader = document.getElementById('archiveLoader');
    if(status){
        if(type === 'product'){
            productLoader.classList.remove('d-none');
            productLoader.classList.add('d-flex');
        }else{
            archiveLoader.classList.remove('d-none');
            archiveLoader.classList.add('d-flex');
        }

    }else{
        if(type === 'product'){
            productLoader.classList.remove('d-flex');
            productLoader.classList.add('d-none');
        }else{
            archiveLoader.classList.remove('d-flex');
            archiveLoader.classList.add('d-none');
        }
    }

}

function clearList(type = 'product'){
   if(type === 'product'){
        document.querySelectorAll('.product-list-items').forEach(el => el.remove());
   }else{
        document.querySelectorAll('.archive-list-items').forEach(el => el.remove());
   }
}

function searchProduct(search){
    clearList();
    loadingProduct(true);
    $.ajax({
        url: `/api/products/search?search=${search}`,
        type: "GET",
        dataType: "json",
        success: res=> {
            loadingProduct(false);
            res.products.forEach(data => {
                const description = `${data.product_type} ${data.entries === 1 ? '(Single Entry)' : '(Dual Entry)'}`;
                createListItem(data.product_name, description, 'prod_list', data.product_id);
            });
        }, error: xhr=> console.log(xhr.responseText)
    });
}

function displayProductList(){
    clearList();
    loadingProduct(true);
    $.ajax({
        url: "/api/products/list",
        type: "GET",
        dataType: "json",
        success: res=> {
            loadingProduct(false);
            res.products.forEach(data => {
                const description = `${data.product_type} ${data.entries === 1 ? '(Single Entry)' : '(Dual Entry)'}`;
                createListItem(data.product_name, description, 'prod_list', data.product_id);
            });
            document.getElementById('searchProduct').value = '';
        }, error: xhr=> console.log(xhr.responseText)
    });
}

let searchDebounce;
document.getElementById('searchProduct').addEventListener('input', e => {
    clearTimeout(searchDebounce);

    searchDebounce = setTimeout(()=> {
        if(e.target.value == ''){
            displayProductList();
        }else{
            searchProduct(e.target.value);
        }

    }, 750);
});

let formDataNew = false;

document.getElementById('add_product_btn').addEventListener('click', ()=> {
    const inputs = [
       ['product_name', 'product_name_e'],
       ['product_type', 'product_type_e'],
       ['product_entry', 'product_entry_e']
    ];

    if(checkValidity(inputs)){
        loading(true);

        const formData = new FormData();
        formData.append('_token', getCsrf());
        formData.append('name', getValue('product_name'));
        formData.append('type', getValue('product_type'));
        formData.append('entry', getValue('product_entry'));
        $.ajax({
            url: "/api/products/add",
            type: "POST",
            data: !formDataNew ? formData : formDataNew,
            contentType: false,
            processData: false,
            success: res=> {
                loading(false);
                dataParser(res);
                if(res.success){
                    displayProductList();
                    exec('closeAddProduct');
                    document.getElementById('product_form').reset();
                }

            },error: xhr=> console.log(xhr.responseText)
        })
    }
});

let cropper;
document.getElementById('inputImage').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const image = document.getElementById('image');
            image.src = e.target.result;


            const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
            cropperModal.show();


            document.getElementById('cropperModal').addEventListener('shown.bs.modal', function () {

                if (cropper) cropper.destroy();

                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 2,
                    autoCropArea: 1,
                    responsive: true,
                    scalable: true,
                    zoomable: true,
                    movable: true,
                    minCanvasWidth: image.parentElement.clientWidth,
                    minCanvasHeight: image.parentElement.clientHeight,
                    minContainerWidth: image.parentElement.clientWidth,
                    minContainerHeight: image.parentElement.clientHeight,
                    background: true
                });
            }, { once: true });
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('save-crop').addEventListener('click', function () {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas();
        if (canvas) {
            canvas.toBlob(blob => {
                formDataNew = new FormData();
                formDataNew.append('_token', getCsrf());
                formDataNew.append('name', getValue('product_name'));
                formDataNew.append('type', getValue('product_type'));
                formDataNew.append('entry', getValue('product_entry'));
                formDataNew.append('image', blob, 'cropped-image.png');
                exec('closeCropper');
            }, 'image/png');
        }
    }
});

document.getElementById('remove-product').addEventListener('click', ()=> {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
            DeleteProduct();
        }
      });
});

function DeleteProduct(){
    loading(true);
    $.ajax({
        url: "/api/products/delete",
        type: "DELETE",
        data: {"_token": getCsrf(), 'product_id': productId},
        success: res=> {
            loading(false);
            exec('closeProductInfo');
            dataParser(res);
            displayProductList();
        }, error: xhr=> console.log(xhr.responseText)
    })
}

function optionsEditStatus(status){
    const productOptions = document.getElementById('product-options');
    const editOptions = document.getElementById('edit-options');

    const editForm = document.querySelectorAll('.edit-form');
    const productInfo = document.querySelectorAll('.product-info');

    if(status){
        editOptions.classList.remove('d-none');
        editOptions.classList.add('d-flex');
        productOptions.classList.remove('d-flex');
        productOptions.classList.add('d-none');
        editForm.forEach(forms => {
            forms.classList.remove('d-none');
        });
        productInfo.forEach(info=> {
            info.classList.add('d-none');
        });
    }else{
        editOptions.classList.add('d-none');
        editOptions.classList.remove('d-flex');
        productOptions.classList.add('d-flex');
        productOptions.classList.remove('d-none');
        editForm.forEach(forms => {
            forms.classList.add('d-none');
        });

        productInfo.forEach(info=> {
            info.classList.remove('d-none');
        });
    }
}

document.getElementById('enable-product-edit').addEventListener('click', ()=> {
    optionsEditStatus(true);
    setValue('input_prod_name', productDetails.product_name);
    setValue('input_prod_type', productDetails.product_type);
    setValue('input_prod_entry', productDetails.entries);
});

document.getElementById('cancelEdit').addEventListener('click', ()=> {
    optionsEditStatus(false);
});

document.getElementById('saveEdit').addEventListener('click', ()=> {
    const input = [
        ['input_prod_name', 'info_product_name_e'],
        ['input_prod_type', 'info_product_type_e'],
    ];

    if(checkValidity(input)){
        loading(true);

        const data = {
            "_token" : getCsrf(),
            "id": productId,
            "name":getValue('input_prod_name'),
            "type": getValue('input_prod_type'),
            "entry": getValue('input_prod_entry')
        }
        $.ajax({
            url: "/api/products/update",
            type: "PATCH",
            data: data,
            success: res=> {
                loading(false);
                dataParser(res);
                if(res.success){
                    displayProductList();
                    fetchProductDetails(productId);
                }
            }, error: xhr=> console.log(xhr.responseText)
        })
    }
});

function loadArchive(){
    clearList('archive');
    loadingProduct(true, 'archive');
    $.ajax({
        url: "/api/products/archivelist",
        type: "GET",
        dataType: "json",
        success: res=> {
            loadingProduct(false, 'archive');
            res.product.forEach(data => {
                const description = `${data.product_type} ${data.entries === 1 ? '(Single Entry)' : '(Dual Entry)'}`;
                createListItem(data.product_name, description, 'archived-list', data.product_id, 'archive');
            });
            document.getElementById('searchProductArchived').value = '';
        }, error: xhr => console.log(xhr.responseText)
    })
}

document.getElementById('show-archive').addEventListener('click', ()=> {
    loadArchive();
});

function searchArchive(search){
    clearList('archive');
    loadingProduct(true, 'archive');
    $.ajax({
        url: `/api/products/searcharchived?search=${search}`,
        type: "GET",
        dataType: "json",
        success: res=> {
            loadingProduct(false, 'archive');
            res.products.forEach(data => {
                const description = `${data.product_type} ${data.entries === 1 ? '(Single Entry)' : '(Dual Entry)'}`;
                createListItem(data.product_name, description, 'archived-list', data.product_id, 'archive');
            });
        }, error: xhr=> console.log(xhr.responseText)
    });
}

function restoreArchive(id){
    loading(true);

    $.ajax({
        url: "/api/products/restore",
        type: "PATCH",
        data: {"_token": getCsrf(), "id": id},
        success: res=> {
            loading(false);
            dataParser(res);
            if(res.success){
                displayProductList();
                loadArchive();
            }
        }
    })
}

let searchArchivedDebounce;

document.getElementById('searchProductArchived').addEventListener('input', e => {
    clearTimeout(searchArchivedDebounce);

    searchArchivedDebounce = setTimeout(()=> {
        if(e.target.value == ''){
            loadArchive();
        }else{
            searchArchive(e.target.value);
        }

    }, 750);
});


function loadReports(productId, region, event){
    const tableId = "#productReports";


    if ($.fn.DataTable.isDataTable(tableId)) {

        $(tableId).DataTable().destroy();
    }


    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/api/products/report?cluster=${region}&event=${event}&id=${productId}`,
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: "cluster" },
            { data: "area" },
            { data: "address" },
            { data: "distributor" },
            { data: "retail_name" },
            { data: "purchase_date" },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true,
    });
}


document.getElementById('event_id').addEventListener('change', ()=> {
    loadReports(productId, getValue('region'), getValue('event_id'));
});

document.getElementById('region').addEventListener('change', () => {
    loadReports(productId, getValue('region'), getValue('event_id'));
});

$(document).ready(function () {
    displayProductList();
});
