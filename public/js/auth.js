document.getElementById('authForm').addEventListener('submit', (e)=> {
    e.preventDefault();

    loading(true);

    $.ajax({
        type: "POST",
        url: "/api/admin/auth",
        data: $('#authForm').serialize(),
        success: res=> {
            loading(false);
            dataParser(res);

            if(res.success){
                window.location.href = "/admin/verification-code";
            }

            if(res.redirect){
                window.location.href = "/dashboard";
            }

        }, error: xhr=> console.log(xhr.responseText)
    })
});

document.getElementById('showPass').addEventListener('click', ()=> {
    showPass('password');
});
