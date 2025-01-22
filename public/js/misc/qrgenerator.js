document.getElementById('export_pages').addEventListener('input',(event) => {
    if (event.target.value > 25000) {
        event.target.value = 25000;
    }
});

document.getElementById('numberofqr').addEventListener('input',(event) => {
    if (event.target.value > 25000) {
        event.target.value = 25000;
    }
});
