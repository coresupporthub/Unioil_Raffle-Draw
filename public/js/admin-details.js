window.onload = async () => {
    const response = await fetch('/api/get-admin-details');

    const result = await response.json();

    setText('administrator_name', result.info.name);

    setValue('adminEmail', result.info.email);
    setValue('adminName', result.info.name);

    enable('adminEmail', false);
    enable('adminName', false);
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
