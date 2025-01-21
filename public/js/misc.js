document.addEventListener("DOMContentLoaded", function() {
    const list = new List('table-default', {
        sortClass: 'table-sort',
        listClass: 'table-tbody',
        valueNames: ['sort-name', 'sort-type', 'sort-city', 'sort-score', {
            attr: 'data-date',
            name: 'sort-date'
        }, {
            attr: 'data-progress',
            name: 'sort-progress'
        }, 'sort-quantity']
    });
})
