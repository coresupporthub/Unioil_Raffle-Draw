window.onload = async () => {
    const response = await fetch('/api/get-admin-details');

    const result = await response.json();

    setText('administrator_name', result.info.name);
}

function adminLogout(){
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
