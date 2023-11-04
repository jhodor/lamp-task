var distancesTable;

$(document).ready(function() {
    distancesTable = $('#distancesTable').DataTable({
    "columns": [
        { "data": "id", "className": "text-center" },
        { "data": "address", "className": "text-left" },
        { "data": "city", "className": "text-center" },
        { "data": "state", "className": "text-center" },
        { "data": "distance" }
    ],
    "pageLength": 5,
    "lengthChange": false,
    "paging": false,
    "searching": false,
    "ordering": true,
    "info": false,
    "order": [],
    });
});
