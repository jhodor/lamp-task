// button to show nearest addresses
$(document).ready(function() {
    $('#processSelectedRow').on('click', function () {
        var selectedData = table.rows('.selected').data()[0];
        if (selectedData) {
            var apiEndpoint = '/api/search.php?id=' + encodeURIComponent(selectedData.id);

            fetch(apiEndpoint)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    distancesTable.clear();
                    distancesTable.rows.add(data).draw();

                    var markers = [];

                    if (markersGroup) {
                        map.removeLayer(markersGroup);
                    }

                    data.forEach(function (location, index) {
                        var latitude = location.latitude;
                        var longitude = location.longitude;
                        var title = location.title;

                        if (!isNaN(latitude) && !isNaN(longitude)) {
                            var mark = L.marker([latitude, longitude]).bindPopup(title);
                            markers.push(mark);
                        }
                    });

                    markersGroup = L.featureGroup(markers);
                    map.addLayer(markersGroup);
                    map.fitBounds(markersGroup.getBounds());
                })
                .catch(function (error) {
                    console.error('Error loading locations:', error);
                });
        } else {
            alert('Please select a row first.');
        }
    });
});
