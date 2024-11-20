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

function initializeQRTable(data){
    if ($.fn.dataTable.isDataTable("#generatedQrTable")) {
        const table = $("#generatedQrTable").DataTable();
        table.clear();
        table.rows.add(data);
        table.draw();
    } else {

        $("#generatedQrTable").DataTable({
            data: data,
            columns: [
                { data: "code" },
                { data: "entry_type" },
                { data: "status" },
                { data: "export_status" },
                { data: null,
                    render: data => {
                        return `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewQR" onclick="viewQR('${data.qr_id}')">View</button>`
                    },
                }
            ],
        });
    }

}

function GetGeneratedQr() {
    $.ajax({
        url: "/api/get-qr-code-generated", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            const qrCodesData = response.qrcodes;
            exec('closeQrCodeGenerator');
            initializeQRTable(qrCodesData);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
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

async function QueueStatus() {
    const response = await fetch('/api/get-queue-status');

    if (!response.ok) {
        alert("Failed to fetch queue status");
        return;
    }

    const result = await response.json();

    $("#queue-progress").DataTable({
        data: result.queue,
        destroy: true,
        columns: [
            {
                data: null,
                render: data => {
                    return `${data.type} ${data.queue_number}`;
                }
            },
            {
                data: "entry_type"
            },
            {
                data: "status"
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `${data.items}/${data.total_items}`;
                },
            },
            {
                data: null,
                render: data => {
                    if (data.export && data.export.base64File) {
                        let base64File = data.export.base64File;
                        if (base64File.startsWith('data:')) {
                            return `<a download href="${base64File}">${data.export.file_name}</a>`;
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
        xhrFields: {
            responseType: 'blob'
        },
        success: res => {
            loading(false);
            document.getElementById("exportQrForm").reset();
           const blob = new Blob([res], { type: 'application/pdf' });
           const url = URL.createObjectURL(blob);

           window.open(url, '_blank');

        }, error: xhr=> {
            console.log(xhr.status);
            loading(false);
            if(xhr.status == 403){
                dataParser({'success': false, 'message':'The QR Codes is not enough for the pages'})
            }else{
                dataParser({'success': false, 'message': 'No Unexported qr code images are available for export! Please add atleast 3'});
            }

            exec('closeExportModal');
        }
    });
});

function GetGenerateQRFilter(filter){
    $.ajax({
        type: "GET",
        url: `/api/filter-qrcodes?filter=${filter}`,
        dataType: "json",
        success: res=> {
            const data = res.data;

            initializeQRTable(data);
        }, error: xhr=> console.log(xhr.responseText)
    })
}

document.getElementById('filterQR').addEventListener('click', (e)=>{
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

    setValue('export_pages', result.page);
});

document.getElementById('selectExportQRType').addEventListener('change', async (e)=> {
    const filter = e.target.value;

    const response = await fetch(`/api/get-export-page-num?filter=${filter}`);

    const result = response.json();

    setValue('export_pages', result.page);
});
