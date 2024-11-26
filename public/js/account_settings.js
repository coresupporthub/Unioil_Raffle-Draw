let userid;
function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function() {
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        imagePreview.src = reader.result;
        imagePreviewContainer.style.display = 'block';
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

function hasRequiredCharacters(str) {
    const hasUppercase = /[A-Z]/.test(str); // At least one uppercase letter
    const hasLowercase = /[a-z]/.test(str); // At least one lowercase letter
    const hasNumber = /\d/.test(str);       // At least one digit

    return hasUppercase && hasLowercase && hasNumber;
}

function checkPass(input, opposite) {
    const oppositeInput = document.getElementById(opposite).value;
    const btn = document.getElementById('changePasswordBtn');
    const newError = document.getElementById('newPasswordError');
    const confirmError = document.getElementById('confirmPasswordError');

    const showError = (message) => {
        newError.textContent = message;
        confirmError.textContent = message;
        newError.style.display = '';
        confirmError.style.display = '';
        btn.disabled = true;
    };

    const hideError = () => {
        newError.style.display = 'none';
        confirmError.style.display = 'none';
        btn.disabled = false;
    };


    if (input.value.length < 8) {
        showError('Password must be at least 8 characters long.');
        return;
    }

    if (!hasRequiredCharacters(input.value)) {
        showError('Password must have at least 1 uppercase letter, 1 lowercase letter, and 1 number.');
        return;
    }

    if (input.value !== oppositeInput) {
        showError('Passwords do not match.');
        return;
    }


    hideError();
}


document.getElementById('changePasswordBtn').addEventListener('click', ()=> {
    document.getElementById('changePasswordForm').requestSubmit();
});

document.getElementById('changePasswordForm').addEventListener('submit', (e)=> {
    e.preventDefault();

    loading(true);

    $.ajax({
        type: "POST",
        url: "/api/admin-changepassword",
        data: $('#changePasswordForm').serialize(),
        success: res => {
            loading(false);
            dataParser(res);
            if(res.success){
                exec('closeChangePassword');
                clearForm('changePasswordForm')
            }
        }, error: xhr=> console.log(xhr.responseText)
    })
});


document.getElementById('adminDetailsBtn').addEventListener('click', ()=> {
    document.getElementById('adminDetailsForm').requestSubmit();
});

document.getElementById('adminDetailsForm').addEventListener('submit', e => {
    e.preventDefault();
    loading(true)
    $.ajax({
        type: "POST",
        url: "/api/update-admin-details",
        data: $('#adminDetailsForm').serialize(),
        success: res=> {
            loading(false);
            dataParser(res);
        }, error: xhr=> console.log(xhr.responseText)
    })
});


//Adminstrator Management

const addAdminBtn = document.getElementById('addAdmin');
if(addAdminBtn){
    addAdminBtn.addEventListener('click', ()=> {
        const inputs =  [
            ['m_name', 'm_name_e'],
            ['m_email', 'm_email_e']
        ];

        if(checkValidity(inputs)){
            loading(true);
            const csrf = getCsrf();
            $.ajax({
                type: "POST",
                url: "/api/add-admin",
                data: {"_token": csrf, "name": getValue('m_name'), "email": getValue('m_email')},
                success: res=> {
                    loading(false);
                    dataParser(res);
                    loadAdmins();
                },error: xhr=> console.log(xhr.responseText)
            })
        }
    });
}



function deleteAdmin(id){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
            loading(true);

            const csrf = getCsrf();

            $.ajax({
                type: "POST",
                url: "/api/delete-admin",
                data: {"_token": csrf, "id": id},
                success: res=> {
                    loading(false);
                    dataParser(res);
                    loadAdmins();
                    cancelUpdate(userid);
                }, error: xhr=> console.log(xhr.responseText)
            })
        }
      });
}

function changeToUpdate(id, name, email){
    userid = id;

    restartBtns('.updBtn', 'remove');
    restartBtns('.cancelBtn', 'add');
    restartBtns('.changePassBtn', 'remove');
    restartBtns('.deleteAdminBtn', 'remove');

    hide('saveAdminDiv');
    show('updateAdminDiv');
    hide('changePassAdminDiv');
    hide(`updBtn${id}`);
    show(`cancelBtn${id}`);
    hide(`changePassBtn${id}`);
    hide(`deleteAdminBtn${id}`);

    show('adminNameDiv');
    show('adminEmailDiv');
    hide('newPassDiv');

    setValue('m_name', name);
    setValue('m_email', email);
}


function cancelUpdate(id){
    show('saveAdminDiv');
    hide('updateAdminDiv');
    hide('changePassAdminDiv');
    show(`updBtn${id}`);
    hide(`cancelBtn${id}`);
    show(`changePassBtn${id}`);
    show(`deleteAdminBtn${id}`);

    show('adminNameDiv');
    show('adminEmailDiv');
    hide('newPassDiv');

    clearVal('m_name');
    clearVal('m_email');
    clearVal('m_newpass');
}

function restartBtns(id, type){
    const btns = document.querySelectorAll(id);

    btns.forEach(btn => {
        if(type == 'add'){
            btn.classList.add('d-none');
        }else{
            btn.classList.remove('d-none');
        }
    });
}

const updateAdminBtn = document.getElementById('updateAdminBtn');
if(updateAdminBtn){
    updateAdminBtn.addEventListener('click', ()=> {
        const inputs =  [
            ['m_name', 'm_name_e'],
            ['m_email', 'm_email_e']
        ];

        if(checkValidity(inputs)){
            loading(true);
            const csrf = getCsrf();

            $.ajax({
                type: "POST",
                url: "/api/update-admin",
                data: {"_token": csrf, "id": userid, "name": getValue('m_name'), "email": getValue('m_email')},
                success: res=> {
                    loading(false);
                    dataParser(res);
                    loadAdmins();
                    clearVal('m_name');
                    clearVal('m_email');
                    cancelUpdate(userid);
                }, error: xhr=> console.log(xhr.responseText)
            })
        }
    });

}

function changePassAction(id){
    userid = id;
    restartBtns('.updBtn', 'remove');
    restartBtns('.cancelBtn', 'add');
    restartBtns('.changePassBtn', 'remove');
    restartBtns('.deleteAdminBtn', 'remove');

    hide('saveAdminDiv');
    hide('updateAdminDiv');
    show('changePassAdminDiv');
    hide(`updBtn${id}`);
    show(`cancelBtn${id}`);
    hide(`changePassBtn${id}`);
    hide(`deleteAdminBtn${id}`);

    hide('adminNameDiv');
    hide('adminEmailDiv');
    show('newPassDiv');
}

const changePassAdminBtn = document.getElementById('changePassAdminBtn');
if(changePassAdminBtn){
    changePassAdminBtn.addEventListener('click', ()=> {
        const inputs =  [
            ['m_newpass', 'm_newpass_e'],
        ];

        if(checkValidity(inputs)){
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update this admin's password",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change pass it"
              }).then((result) => {
                if (result.isConfirmed) {
                  loading(true);

                  const csrf = getCsrf();
                  $.ajax({
                    type: "POST",
                    url: "/api/changepass-admin",
                    data: {"_token": csrf, "id": userid, "password": getValue('m_newpass')},
                    success: res=> {
                        loading(false);
                        dataParser(res);
                        loadAdmins();
                        clearVal('m_newpass');
                    }, error: xhr=> console.log(xhr.responseText)
                  })
                }
              });
        }
    });

}
