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
