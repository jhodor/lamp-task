var map;
var marker;
var table;

$(document).ready(function() {

    map = L.map('map').setView([40.53558710541011, -105.03952262680973], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    table = $('#myTable').DataTable({
        "ajax": {
            "url": "api/adresses.php",
            "dataSrc": ""
        },
        "columns": [
            { "data": "id", "className": "text-center" },
            { "data": "address", "className": "text-left" },
            { "data": "city", "className": "text-center" },
            { "data": "state", "className": "text-center" },
            { "data": "latitude" },
            { "data": "longitude" }
        ],
        "pageLength": 10,
        "paging": true,
        "searching": true,
        "ordering": true,
        "lengthChange": false,
        "order": [],
    });

    // Add a hover effect to the rows
    $('#myTable tbody').on('mouseenter', 'tr', function () {
        $(this).addClass('table-hover-row');
    }).on('mouseleave', 'tr', function () {
        $(this).removeClass('table-hover-row');
    });


    // Enable row selection
    $('#myTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var selectedData = table.rows('.selected').data()[0];
        if (selectedData) {
            var id = selectedData.id;
            var longitude = selectedData.longitude;
            var latitude = selectedData.latitude;
            var address = selectedData.address;
            map.setView([latitude, longitude], 12);
            if (marker) {
                marker.remove();
            }
            marker = L.marker([latitude, longitude]).addTo(map);
            marker.bindPopup(address).openPopup();
        }
    });
});
