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

    if(inputElement.value == '' &&
        (inputElement.type == 'text'
            || inputElement.type == 'password'
            || inputElement.type == 'number'
            || inputElement.tagName.toLowerCase() == 'textarea'
            || inputElement.tagName.toLowerCase() == 'select'
        )){
        text.classList.remove('d-none');
        inputElement.classList.add('border', 'border-danger');

        return 0;
    }

    if(inputElement.type == 'file' && !inputElement.files.length){
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


function formatDateTime(isoString) {
    const date = new Date(isoString);
    const options = {
        month: 'long',
        day: '2-digit',
        year: 'numeric',
    };

    const formattedDate = date.toLocaleDateString('en-US', options);


    let hours = date.getHours();
    const minutes = date.getMinutes();
    const period = hours >= 12 ? 'PM' : 'AM';


    hours = hours % 12 || 12;

    const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')} ${period}`;


    return `${formattedDate} - ${formattedTime}`;
}


function hide(id){
    const element = document.getElementById(id);

    if(element){
        element.classList.add('d-none');
    }
}

function show(id, type = 'block'){
    const element = document.getElementById(id);

    if(element){
        element.classList.remove('d-none');
        type == 'block' ? null : element.classList.add('d-flex');
    }
}


function getFile(code){
    switch(code){
        case '0100000000':
            return 'region1.json';
        case '0200000000':
            return 'region2.json';
        case '0300000000':
            return 'region3.json';
        case '0400000000':
            return 'region4a.json';
        case '1700000000':
            return 'mimaropa.json';
        case '0500000000':
            return 'region5.json';
        case '0600000000':
            return 'region6.json';
        case '0700000000':
            return 'region7.json';
        case '0800000000':
            return 'region8.json';
        case '0900000000':
            return 'region9.json';
        case '1000000000':
            return 'region10.json';
        case '1100000000':
            return 'region11.json';
        case '1200000000':
            return 'region12.json';
        case '1300000000':
            return 'ncr.json';
        case '1400000000':
            return 'car.json';
        case '1600000000':
            return 'caraga.json';
        case '1900000000':
            return 'barmm.json';
    }
}

function clearVal(id){
    const element = document.getElementById(id);
    if(element){
        element.value = '';
    }
}
