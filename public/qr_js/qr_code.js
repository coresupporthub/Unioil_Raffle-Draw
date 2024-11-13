function GenerateQrCode() {
    const form = document.getElementById("generateform");
    const formData = new FormData(form);

    formData.forEach(function (value, key) {
        console.log(key + ": " + value);
    });

    $.ajax({
        url: "/api/generate-qr-code", // Replace with your endpoint URL
        type: "POST",
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Let FormData handle the content type (especially for file uploads)
        success: function (response) {
            // Handle success
            console.log("Data posted successfully:", response);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error posting data:", error);
        },
    });
}
