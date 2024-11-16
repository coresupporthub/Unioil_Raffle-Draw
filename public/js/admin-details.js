window.onload = async () => {
    const response = await fetch('/api/get-admin-details');

    const result = await response.json();

    setText('administrator_name', result.info.name);
}
