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
                    <button class="btn btn-primary updBtn" id="updBtn${data.id}"
                    data-id="${data.id}" data-name="${data.name}" data-email="${data.email}">Update Info</button>
                    <button class="btn btn-danger w-100 d-none cancelBtn" id="cancelBtn${data.id}" data-id="${data.id}" >Cancel</button>
                    <button class="btn btn-success changePassBtn" data-id="${data.id}" id="changePassBtn${data.id}">Change Password</button>
                    <button class="btn btn-danger deleteAdminBtn" data-id="${data.id}" id="deleteAdminBtn${data.id}">Delete</button>
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

    $(tableId).on('click', '.updBtn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        changeToUpdate(id, name, email);
    });

    $(tableId).on('click', '.cancelBtn', function() {
        const id = $(this).data('id');
        cancelUpdate(id);
    });

    $(tableId).on('click', '.changePassBtn', function() {
        const id = $(this).data('id');
        changePassAction(id);
    });

    $(tableId).on('click', '.deleteAdminBtn', function() {
        const id = $(this).data('id');
        deleteAdmin(id);
    });
}
document.getElementById('logoutBtn').addEventListener('click', ()=> {
    adminLogout()
});

const adminLogoutBtn = document.getElementById('adminLogoutBtn');

if(adminLogoutBtn){
    adminLogoutBtn.addEventListener('click', ()=>{
        adminLogout()
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
