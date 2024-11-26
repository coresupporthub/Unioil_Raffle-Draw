window.onload = async () => {
    const response = await fetch('/api/get-admin-details');

    const result = await response.json();

    setText('administrator_name', result.info.name);

    setValue('adminEmail', result.info.email);
    setValue('adminName', result.info.name);

    enable('adminEmail', false);
    enable('adminName', false);

    loadAdmins();
}
function loadAdmins(){
    const tableId = "#admin-list";

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    $(tableId).DataTable({
        ajax: {
            url: '/api/list-admin',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: "name" },
            { data: "email" },

            {
                data: null,
                render: data => {
                    return `<div class="d-flex gap-1">
                    <button class="btn btn-primary updBtn" id="updBtn${data.id}" onclick="changeToUpdate('${data.id}', '${data.name}', '${data.email}')">Update Info</button>
                    <button class="btn btn-danger w-100 d-none cancelBtn" id="cancelBtn${data.id}" onclick="cancelUpdate('${data.id}')">Cancel</button>
                    <button class="btn btn-success changePassBtn" onclick="changePassAction('${data.id}')" id="changePassBtn${data.id}">Change Password</button>
                    <button class="btn btn-danger deleteAdminBtn" id="deleteAdminBtn${data.id}" onclick="deleteAdmin('${data.id}')">Delete</button>
                    </div>`;
                },
                width: '5%'
            }
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true
    });
}
function adminLogout(){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "Do you want to log out?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Log out!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
            loading(true);

            const csrf = getCsrf();

            $.ajax({
                type: "POST",
                url: "/api/logout",
                data: {"_token": csrf},
                success: res=> {
                    if(res.success){
                        window.location.href = "/admin/sign-in"
                    }
                }, error: xhr=> console.log(xhr.responseText)
            })
        }
      });


}
