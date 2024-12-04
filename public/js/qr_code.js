function GenerateQrCode() {
    const form = document.getElementById("generateform");
    const formData = new FormData(form);
    loading(true);
    const numberofqr = document.getElementById("numberofqr").value;

    $.ajax({
        url: "/api/generate-qr-code", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Let FormData handle the content type (especially for file uploads)
        success: function (response) {
                alertify.success(`${numberofqr} QR Code(s) generation is now in progress.`);
                form.reset();
                GetGeneratedQr();
                setTimeout(function () {
                    GetGeneratedQr();
                }, 2000);
                loading(false);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error posting data:", error);
        },
    });
}


function GetGeneratedQr() {
    const tableId = "#generatedQrTable";

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/get-qr-code-generated',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: "code" },
            { data: "entry_type" },
            { data: "status" },
            { data: "export_status" },
            {
                data: null,
                render: data => {
                    return `<button class="btn unioil-info" data-bs-toggle="modal" data-bs-target="#viewQR" onclick="viewQR('${data.qr_id}')">View</button>`;
                }
            }
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });
}

$(document).ready(function () {

    GetGeneratedQr();
    QueueStatus();
});

document.getElementById('resetTable').addEventListener('click', ()=> {
    GetGeneratedQr();
    QueueStatus();
});
function QueueStatus() {
    $("#queue-progress").DataTable({
        ajax: {
            url: '/api/get-queue-status',
            dataSrc: 'queue'
        },
        destroy: true,
        columns: [
            {
                data: null,
                render: data => {
                    return `${data.queue.type} ${data.queue.queue_number}`;
                }
            },
            {
                data: null,
                render: data => {
                    return data.queue.entry_type
                }
            },
            {
                data: null,
                render: data => {
                    return  data.queue.status
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `${data.queue.items}/${data.queue.total_items}`;
                },
            },
            {
                data: null,
                render: data => {
                    if (data.export && data.export.base64File) {
                        let base64File = data.export.base64File;
                        if (base64File.startsWith('data:')) {
                            return `<a download="${data.export.file_name}" href="${base64File}">${data.export.file_name}</a>`;
                        } else {
                            return 'Invalid file data';
                        }
                    } else {
                        return 'N/A';
                    }
                }
            }
        ],
    });
}


document.getElementById('exportQrBtn').addEventListener('click', ()=> {
    document.getElementById('exportQrForm').requestSubmit();
});

document.getElementById('exportQrForm').addEventListener('submit', (e)=> {
    e.preventDefault();
    loading(true);
    $.ajax({
        type: "POST",
        url: "/api/export-qr",
        data: $('#exportQrForm').serialize(),
        success: res => {
            loading(false);
            dataParser(res);
            exec('closeExportModal');

        }, error: xhr=> {
            console.log(xhr.status);
            loading(false);
            exec('closeExportModal');
        }
    });
});

function GetGenerateQRFilter(filter) {
    const tableId = "#generatedQrTable";


    if ($.fn.DataTable.isDataTable(tableId)) {

        $(tableId).DataTable().destroy();
    }


    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/api/filter-qrcodes?filter=${filter}`,
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: "code" },
            { data: "entry_type" },
            { data: "status" },
            { data: "export_status" },
            {
                data: null,
                render: data => {
                    return `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewQR" onclick="viewQR('${data.qr_id}')">View</button>`;
                }
            }
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true,
    });
}

document.getElementById('filterQR').addEventListener('change', (e)=>{
    const filter = e.target.value;

    if(filter == 'all'){
        GetGeneratedQr();
    }else{
        GetGenerateQRFilter(filter);
    }
});

function viewQR(id){
    $.ajax({
        type: "GET",
        url: `/api/view-qrcodes?id=${id}`,
        dataType: "json",
        success: res=> {
            setImage('qrCodeImage', res.qr.image_base64);
            setText('viewCode', res.qr.code);
            setText('viewUUID', res.qr.qr_id);
            setText('viewEntryType', res.qr.entry_type);
            setText('viewStatus', res.qr.status);

            const available = document.getElementById('entry_available');
            const unavailable = document.getElementById('entry_unavalable');
            if(res.success){
                available.classList.remove('d-none');
                unavailable.classList.add('d-none');

                setText('viewCustomerName', res.customer.full_name);
                setText('viewAddress', res.customer.address);
                setText('viewEmail', res.customer.email);
                setText('viewContact', res.customer.contact);
                setText('viewProductPurchased', res.product.product_name);

                if(res.entry_type == 'Single Entry'){
                    setText('viewSerialNumber2', 'Not Available');
                    setText('viewSerialNumber1', res.entries.serial_number);
                }else{
                    let ent = 1;
                    res.entries.forEach(e => {
                        setText(`viewSerialNumber${ent}`, e.serial_number);
                        ent++;
                    });
                }

                setText('viewRegistrationDate', formatDateTime(res.customer.created_at));
                setText('viewRetailStation', res.entries.retail_station);
                setText('viewDistributor', res.entries.distributor);
            }else{
                available.classList.add('d-none');
                unavailable.classList.remove('d-none');
            }
        }, error: xhr=> console.log(xhr.responseText)
    })
}

document.getElementById('openExportBtn').addEventListener('click', async ()=> {
    const response = await fetch('/api/get-export-page-num?filter=Single Entry QR Code');

    const result = await response.json();

    suggestExport(result.page);
});

document.getElementById('selectExportQRType').addEventListener('change', async (e)=> {
    const filter = e.target.value;

    const response = await fetch(`/api/get-export-page-num?filter=${filter}`);

    const result = await response.json();

    suggestExport(result.page);
});

function suggestExport(data){

    if(data == 0){
        enable('exportQrBtn', true);
        enable('export_pages', true);
    }else{
        enable('exportQrBtn', false);
        enable('export_pages', false);
    }

    setValue('export_pages', data);
}
