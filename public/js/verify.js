window.onload = () => {
    dataGetter('/api/get-auth').then(data=>{
        const auth = data.auth;
        setText('userEmail', auth.email);
    });
}
