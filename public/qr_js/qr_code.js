function GenerateQrCode() {
    const form = document.getElementById("generateform");
    const formData = new FormData(form);

    const numberofqr = document.getElementById("numberofqr").value;
    
    $.ajax({
        url: "/api/generate-qr-code", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Let FormData handle the content type (especially for file uploads)
        success: function (response) {
                alertify.success(`${numberofqr} QR Code(s) generation is now in progress.`);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error posting data:", error);
        },
    });
}

function GetGeneratedQr(){
    $.ajax({
        url: "/api/get-qr-code-generated", // Replace with your endpoint URL
        type: "GET",
        success: function (response) {
            console.log(response)
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error fetching data:", error);
        },
    });
}
$(document).ready(function () {

    GetGeneratedQr();

});