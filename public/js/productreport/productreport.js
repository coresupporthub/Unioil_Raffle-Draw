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
            console.log(response);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}
$(document).ready(function () {
    GetAllEntry();
});
