window.onload = () => {

    const tableId = "#activityLogsTable";


    if ($.fn.DataTable.isDataTable(tableId)) {

        $(tableId).DataTable().destroy();
    }


    $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/api/activitylogs/list`,
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: "name" },
            { data: "action" },
            { data: "result" },
            { data: null,
                render: data=> {
                    return formatDateTime(data.created_at);
                }
             },
            { data: "device" },
            {
                data: null,
                render: data => {
                    return `<button class="btn unioil-info" data-bs-toggle="modal" data-bs-target="#logDetails" data-act-id="${data.act_id}" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M12 9v4" />
                    <path d="M12 16v.01" />
                    </svg> View More Info
                    </button>`
                },
            }
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        destroy: true,
    });

    $(tableId).on('click', '.unioil-info', function() {
        const actId = $(this).data('act-id');
        viewLogDetails(actId);
    });

    adminDetails();
}


async function viewLogDetails(id){
    const response = await fetch(`/api/activitylogs/details/${id}`);
    const result = await response.json();

    setText('logName', result.logs.name);
    setText('logAction', result.logs.action);
    setText('logResult', result.logs.result);
    setText('logDevice', result.logs.device);
    setText('logTimestamp', formatDateTime(result.logs.created_at));

    setText('logApiCall', result.logs.api_calls);
    setText('logPageRoute', result.logs.page_route);
    setText('logRequestType', result.logs.request_type);
    setText('logSessionID', result.logs.session_id);
    setText('logSentData', JSON.stringify(result.logs.sent_data));
    setText('logResponseData', JSON.stringify(result.logs.response_data));
}

async function adminDetails (){
    const response = await fetch('/api/get-admin-details');

    const result = await response.json();

    setText('administrator_name', result.info.name);

    setValue('adminEmail', result.info.email);
    setValue('adminName', result.info.name);

    enable('adminEmail', false);
    enable('adminName', false);
}
