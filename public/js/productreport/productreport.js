

//-----------------------------------------------------------------------------------------------

function createListItem(name, description, containerId, prod_id, type = 'product', image, purchased) {
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

    const avatarSpan = document.createElement("img");
    avatarSpan.src = image != null ? image : "/unioil_images/unioil.png";
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

    nameLink.innerHTML = `${name} <strong>(Total Purchased: ${purchased})</strong>`;
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
    enable('updateLogo', true);
    document.getElementById('updateLogo').value = '';
    productId = id;
    $.ajax({
        url: `/api/products/details/${id}`,
        type: "GET",
        dataType: "json",
        success: res=> {
            loadProductDetails(false);
            const product = res.product;
            productDetails = product;
            enable('updateLogo', false);
            setText('info_prod_name', product.product_name);
            setText('info_prod_type', product.product_type);
            setText('info_prod_entry', product.entries == 1 ? 'Single Entry' : 'Dual Entry');
            setImage('prod_logo_info', product.imagebase64 ?? "/unioil_images/unioil.png");
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
                createListItem(data.product_name, description, 'prod_list', data.product_id, 'product', data.imagebase64, data.purchased);
            });

            setText('totalPurchased', res.total_products);
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
                createListItem(data.product_name, description, 'prod_list', data.product_id, 'product', data.imagebase64, data.purchased);
            });
            setText('totalPurchased', res.total_products);
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
                createListItem(data.product_name, description, 'archived-list', data.product_id, 'archive', data.imagebase64, data.purchased);
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
                createListItem(data.product_name, description, 'archived-list', data.product_id, 'archive', data.imagebase64, data.purchased);
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
            { data: "customer_name" },
            { data: "purchase_date" },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true,
    });
}


document.getElementById('updateLogo').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const image = document.getElementById('updateImage');
            image.src = e.target.result;

            exec('closeProductInfo');
            setText('updateLogoHeader', "Upload Logo (" + productDetails.product_name + ")");
            const cropperModal = new bootstrap.Modal(document.getElementById('uploadLogo'));
            cropperModal.show();

            document.getElementById('uploadLogo').addEventListener('shown.bs.modal', function () {
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

document.getElementById('upload-logo').addEventListener('click', ()=> {


    if (cropper) {
        const canvas = cropper.getCroppedCanvas();
        if (canvas) {
            canvas.toBlob(blob => {
                const formData = new FormData();
                formData.append('id', productId);
                formData.append('image', blob, 'cropped-image.png');
                loading(true);

                $.ajax({
                    url: "/api/products/uploadlogo",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': getCsrf()
                    },
                    success: res=> {
                        loading(false);
                        dataParser(res);
                        if(res.success){
                             displayProductList();
                             document.getElementById('updateLogo').value = "";
                             exec('closeLogoUploader');
                        }
                    }, error: xhr=> console.log(xhr.responseText)
                })

            }, 'image/png');
        }
    }
});

document.getElementById('event_id').addEventListener('change', ()=> {
    loadReports(productId, getValue('region'), getValue('event_id'));
});

document.getElementById('region').addEventListener('change', () => {
    loadReports(productId, getValue('region'), getValue('event_id'));
});

document.getElementById('chart-filter').addEventListener('change', e => {
    switch(e.target.value){
        case "pie":
            show('pie-div');
            hide('bar-div');
            hide('line-div');
            break;
        case 'bar':
            hide('pie-div');
            show('bar-div');
            hide('line-div');
            break;
        case 'line':
            hide('pie-div');
            hide('bar-div');
            show('line-div');
            break;
    }
});


async function loadCharts(){
    const data = await $.ajax({
        url: "/api/products/chartreports",
        type: "GET",
        dataType: "json",
    });

    const barChart = data.barchart;
    const pieChart = data.piechart;
    const lineChart = data.linechart;
    //Bar Chart
    window.ApexCharts && (new ApexCharts(document.getElementById('bar-charts'), {
        chart: {
            type: "bar",
            fontFamily: 'inherit',
            height: 240,
            parentHeightOffset: 0,
            toolbar: {
                show: false,
            },
            animations: {
                enabled: false
            },
            nonce: cspNonce
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
            }
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            opacity: 1,
        },
        series: [{
            name: "Tasks completion",
            data: barChart.data
        }],
        tooltip: {
            theme: 'dark'
        },
        grid: {
            padding: {
                top: -20,
                right: 0,
                left: -4,
                bottom: -4
            },
            strokeDashArray: 4,
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false
            },
            axisBorder: {
                show: false,
            },
        },
        yaxis: {
            labels: {
                padding: 4
            },
        },
        labels: barChart.products,
        colors: [tabler.getColor("primary")],
        legend: {
            show: false,
        },
    })).render();


    window.ApexCharts && (new ApexCharts(document.getElementById('pie-charts'), {
        chart: {
            type: "donut",
            fontFamily: 'inherit',
            height: 240,
            sparkline: {
                enabled: true
            },
            animations: {
                enabled: false
            },
            nonce: cspNonce
        },
        fill: {
            opacity: 1,
        },
        series: pieChart.data,
        labels: pieChart.products,
        tooltip: {
            theme: 'dark'
        },
        grid: {
            strokeDashArray: 4,
        },
        colors: [tabler.getColor("primary"), tabler.getColor("primary", 0.8), tabler.getColor("primary", 0.6), tabler.getColor("gray-300")],
        legend: {
            show: true,
            position: 'bottom',
            offsetY: 12,
            markers: {
                width: 10,
                height: 10,
                radius: 100,
            },
            itemMargin: {
                horizontal: 8,
                vertical: 8
            },
        },
        tooltip: {
            fillSeriesColor: false
        },
    })).render();


    window.ApexCharts && (new ApexCharts(document.getElementById('line-charts'), {
        chart: {
            type: "line",
            fontFamily: 'inherit',
            height: 288,
            parentHeightOffset: 0,
            toolbar: {
                show: false,
            },
            animations: {
                enabled: false
            },
            nonce: cspNonce
        },
        fill: {
            opacity: 1,
        },
        stroke: {
            width: 2,
            lineCap: "round",
            curve: "smooth",
        },
        series: lineChart.data,
        tooltip: {
            theme: 'dark'
        },
        grid: {
            padding: {
                top: -20,
                right: 0,
                left: -4,
                bottom: -4
            },
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true
                }
            },
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false
            },
            type: 'datetime',
        },
        yaxis: {
            labels: {
                padding: 4
            },
        },
        labels: lineChart.dates,
        colors: [tabler.getColor("facebook"), tabler.getColor("twitter"), tabler.getColor("dribbble")],
        legend: {
            show: true,
            position: 'bottom',
            offsetY: 12,
            markers: {
                width: 10,
                height: 10,
                radius: 100,
            },
            itemMargin: {
                horizontal: 8,
                vertical: 8
            },
        },
    })).render();

}


function loadProductReports(event = 'all', cluster = 'all', product = 'all'){
    const tableId = "#productReportsTable";

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/api/products/productreports?event=${event}&cluster=${cluster}&product=${product}`,
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: "area" },
            { data: "address" },
            { data: "distributor" },
            { data: "retail_station" },
            { data: null,
                render: data => {
                    return formatDateTime(data.created_at)
                }
             },
            { data: "full_name"},
            { data: "product_name" },
            { data: "cluster_name" },
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });

}

document.getElementById('event_id_table').addEventListener('change', ()=> {
    loadProductReports(getValue('events_id_table'), getValue('region_table'), getValue('products_table'));
});

document.getElementById('region_table').addEventListener('change', ()=> {
    loadProductReports(getValue('events_id_table'), getValue('region_table'), getValue('products_table'));
});

document.getElementById('products_table').addEventListener('change', ()=> {
    loadProductReports(getValue('events_id_table'), getValue('region_table'), getValue('products_table'));
});


$(document).ready(async function () {
    displayProductList();
    loadCharts();
    loadProductReports(getValue('events_id_table'), getValue('region_table'), getValue('products_table'));
});
