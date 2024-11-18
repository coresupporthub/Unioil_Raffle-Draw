function loading(status){
    const loader = document.getElementById('systemLoader');

    loader.classList.add(status ?'d-flex' : 'd-none')

    loader.classList.remove(status ? 'd-none' : 'd-flex');
}


function dataParser(data){
    alertify.set('notifier','position', 'top-center');
    if(data.success){
        alertify.success(data.message);
    }else{
        alertify.error(data.message);
    }
}

function showPass(id){
    const inp = document.getElementById(id);
    if(inp.type === 'password'){
        inp.type = 'text';
    }else{
        inp.type = 'password';
    }
}


async function dataGetter(api){
    const response = await fetch(api);
    const result = await response.json();

    return result;
}

function setText(id, value){
    const element =  document.getElementById(id);

    if(element){
        element.textContent = value;
    }
}

function btnLoading(id, status){
    const element = document.getElementById(id);

    if(element){
        if(status){
            element.innerHTML = `<div class="btnLoader"></div>` ;
        }else{
            element.textContent = 'Verify';
        }
    }
}

function createOption(name, value, data = true,){
    if(data){
        const option = document.createElement('option');
        option.value = value;
        option.textContent = name;

        return option;
    }else{
        const option = document.createElement('option');
        option.value = "";
        option.textContent = name;
        option.disabled = true;
        option.selected = true;

        return option;
    }
}

function clearOption(element, pl){

    while(element.firstChild){
        element.removeChild(element.firstChild);
    }

    const placeholder = createOption(pl, '', false);

    element.append(placeholder);
}


function setValue(id, value){
    const element = document.getElementById(id);

    if(element){
        element.value = value;
    }
}

function getCsrf(){
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    return csrfToken;
}

function exec(id){
    const element = document.getElementById(id);

    if(element){
        element.click();
    }
}

function clearForm(formId) {
    const form = document.getElementById(formId); // Get the form by its ID
    if (!form) {
        console.error(`Form with ID "${formId}" not found.`);
        return;
    }

    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {

        if(input.name != '_token'){

            input.value = '';
        }

        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        }
    });


    const textareas = form.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.value = '';
    });

    const selects = form.querySelectorAll('select');
    selects.forEach(select => {
        select.selectedIndex = 0;
    });
}


function enable(id, status){
    const element = document.getElementById(id);

    if(element){
        element.disabled = status;
    }
}

function getValue(id){
    const element = document.getElementById(id);
    if(element){
        return element.value;
    }
}


function checkInp(inp, textErr){
    const inputElement = document.getElementById(inp);
    const text = document.getElementById(textErr);

    if(inputElement.value == ''){
        text.classList.remove('d-none');
        inputElement.classList.add('border', 'border-danger');

        return 0;
    }

    text.classList.add('d-none');
    inputElement.classList.remove('border', 'border-danger');

    return 1;
}

function checkValidity(data){
    let validity = 0;

    data.forEach(element => {
        validity += checkInp(element[0], element[1]);
    });

    if(validity == data.length){
        return true;
    }

    return false;
}

function setImage(id, src){
    const img = document.getElementById(id);

    if(img){
        img.src = src;
    }
}
