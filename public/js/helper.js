function loading(status){
    const loader = document.getElementById('systemLoader');

    loader.classList.add(status ?'d-flex' : 'd-none')

    loader.classList.remove(status ? 'd-none' : 'd-flex');
}
