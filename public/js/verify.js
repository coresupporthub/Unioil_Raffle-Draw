window.onload = () => {
    dataGetter('/api/get-auth').then(data=>{
        const auth = data.auth;
        setText('userEmail', auth.email);
    });
}

document.querySelectorAll('[data-code-input]').forEach((input, index, inputs) => {
    input.addEventListener('input', (e) => {
        if (e.inputType === 'insertText' && e.target.value.length === 1) {
            // Move to the next input if it exists
            if (inputs[index + 1]) {
                inputs[index + 1].focus();
            }
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value === '') {
            // Move to the previous input if it exists
            if (inputs[index - 1]) {
                inputs[index - 1].focus();
            }
        }
    });
});


document.getElementById('verifyForm').addEventListener('submit', e => {
    e.preventDefault();

    btnLoading('verifySubmit', true);

    $.ajax({
        type: "POST",
        url: "/api/verify-user",
        data: $('#verifyForm').serialize(),
        success: res=> {
            btnLoading('verifySubmit', false);
            dataParser(res);
            if(res.success){
                window.location.href = '/dashboard';
            }
        }, error: xhr => console.log(xhr.responseText)
    })
});

document.getElementById('cancelVerify').addEventListener('click', ()=> {
    btnLoading('cancelVerify', true);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        type: "POST",
        url: "/api/logout",
        data: {"_token": csrfToken},
        success: res=> {
            if(res.success){
                window.location.href = '/admin/sign-in';
            }
        }, error: xhr=> console.log(xhr.responseText)
    });

});

document.getElementById('resendCode').addEventListener('click', ()=> {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    loading(true);

    $.ajax({
        type: "POST",
        url: "/api/resend-code",
        data: {"_token": csrfToken},
        success: res=> {
            loading(false);
            dataParser(res);
        }, error: xhr=> console.log(xhr.responseText)
    });

});
