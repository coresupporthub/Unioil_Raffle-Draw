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
