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
