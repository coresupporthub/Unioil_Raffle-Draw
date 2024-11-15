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
    $.ajax({
        url: "/api/get-qr-code-generated", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {

            // Assuming the response contains the 'qrcodes' array
            const qrCodesData = response.qrcodes;

            // Initialize DataTable
            $("#generatedQrTable").DataTable({
                data: qrCodesData,
                destroy:true,
                columns: [
                    { data: "code" },
                    { data: "entry_type" },
                    { data: "status" },
                    { data: "export_status"},
                    {
                        // Define the Action button column
                        data: null,
                        render: function (data, type, row) {
                            return `<button class="btn btn-danger" onclick="DeleteQrCode('${row.qr_id}')">Delete</button>`;
                        },
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}
function DeleteQrCode(qrId) {

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("qr_id", qrId);
    loading(true);
    $.ajax({
        url: "/api/delete-generate-qr-code", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Let FormData handle the content type (especially for file uploads)
        success: function (response) {
            GetGeneratedQr();
            loading(false);
            alertify.success(`Qr Code has been deleted successfully`);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error posting data:", error);
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

async function QueueStatus(){
    const response = await fetch('/api/get-queue-status');

    const result = await response.json();

    $("#queue-progress").DataTable({
        data: result.queue,
        destroy: true,
        columns: [
            { data: null,
                render: data=> {
                    return `${data.type} ${data.queue_number}`
                }
             },
            { data: "entry_type" },
            { data: "status" },
            {

                data: null,
                render: function (data, type, row) {
                    return `${data.items}/${data.total_items}`;
                },
            },
            {
                data: null,
                render: data=> {
                    return data.export ? `<a download href="/pdf_files/${data.export.file_name}">${data.export.file_name}</a>` : 'N/A';
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
            const blob = new Blob([res], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);
            loading(false);
            window.open(url, '_blank');
        }, error: xhr=> console.log(xhr.responseText)
    });
});
