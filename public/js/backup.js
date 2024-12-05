document.getElementById('enableAutoBackup').addEventListener('change', (e)=> {
    loading(true);

    const enableBackup = e.target.checked;
    const csrf = getCsrf();
    $.ajax({
        type: "POST",
        url: "/api/backup/automate",
        data: {"_token": csrf, "status": enableBackup},
        success: res=> {
            loading(false);
            dataParser(res);

            if(!res.success){
                e.target.checked = !enableBackup;
            }

        }, error: xhr=> console.log(xhr.responseText)
    })
});


document.getElementById('initiateBackup').addEventListener('click', ()=> {
 loading(true);

 $.ajax({
    type: "POST",
    url: "/api/backup/initiate",
    data: {"_token": getCsrf()},
    success: res=> {
        loading(false);
        dataParser(res);
        loadbackup();
    }, error: xhr=> console.log(xhr.responseText)
 })
});

function loadbackup(){
    $.ajax({
        type: "GET",
        url: "/api/backup/list",
        dataType: "json",
        success: res=> {
            const list = document.getElementById('backup-list');
            list.replaceChildren();

            const filterFiles = res.files.filter(x => x.path != '.gitignore');

            filterFiles.forEach(files => {
                const button = document.createElement('button');
                button.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'justify-content-between');
                button.textContent = files.path;

                const anchor = document.createElement('a');
                anchor.href = `${files.base64}`;
                anchor.download = files.path;
                anchor.textContent = 'Download';

                button.append(anchor);

                list.append(button);
            });
        }, error: xhr => console.loh(xhr.responseText)
    })
}
