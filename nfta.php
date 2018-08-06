<?php include "inc/header.php"?>
<?php include "nfta_functions.php"?>
<?php $stop = getTimes();?>

<div class="container-fluid w-100 h-100">
    <div class="row">
        <div class="col-3">
            <div class="p-3">
                <div class="card p-3 shadow">
                    <h1 class="h3">
                        <?php echo $stop['stop_name']?>
                    </h1>
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="inbound-tab" data-toggle="pill" href="#inbound" role="tab" aria-controls="pills-home" aria-selected="true">Inbound</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="outbound-tab" data-toggle="pill" href="#outbound" role="tab" aria-controls="pills-profile" aria-selected="false">Outbound</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="inbound" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="border-top">
                                <?php foreach($stop['route_info'] as $v){ ?>
                                <div class="border-bottom p-1  d-flex justify-content-between align-items-center">
                                    <span class="badge" style="background-color: <?php echo $v['route_color']?>">
                                            <?php echo $v['route_name']?>
                                        </span>
                                    <?php echo $v['time'];?>
                                </div>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="outbound" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="border-top">
                                <div class="border-bottom p-2 text-right">9:00 AM</div>
                                <div class="border-bottom p-2 text-right">10:00 AM</div>
                                <div class="border-bottom p-2 text-right">11:00 AM</div>
                                <div class="border-bottom p-2 text-right">12:00 PM</div>
                                <div class="border-bottom p-2 text-right">1:00 PM</div>
                                <div class="border-bottom p-2 text-right">2:00 PM</div>
                                <div class="border-bottom p-2 text-right">3:00 PM</div>
                                <div class="border-bottom p-2 text-right">4:00 PM</div>
                                <div class="border-bottom p-2 text-right">5:00 PM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <?php $theStops = getStops(); 
                    foreach($theStops as $s){ ?>
            <div>
                <a class="small" href="nfta.php?stop_id=<?php echo $s['id']?>">
                    <?php echo $s['stop_name']?>
                </a>
            </div>
            <?php } ?>
        </div>
        <div class="col-6">
            <div id="map" style="height:100vh;"></div>

        </div>
    </div>
</div>

<script>
    //    mapboxgl.accessToken = 'pk.eyJ1IjoiamJodXRjaCIsImEiOiJjamRqZGU1eTYxMTZlMzNvMjV2dGxzdG8wIn0.IAAk5wKeLXOUaQ4QYF3sEA';
    //    var map = new mapboxgl.Map({
    //        container: 'map',
    //        style: 'mapbox://styles/mapbox/streets-v10',
    //        center: [-78.87, 42.91],
    //        zoom: 12
    //    });
    //
    //
    //    var markers = document.getElementById('markers');
    //
    //    map.on('load', function() {
    //        
    //        map.addLayer(
    //            <?php //renderRoute();?>
    //        );
    //        
    //        map.addLayer(
    //            <?php //renderTheStops();?>
    //        );
    //        
    //        
    //        // Create a popup, but don't add it to the map yet.
    //        var popup = new mapboxgl.Popup({
    //            closeButton: false,
    //            closeOnClick: false
    //        });
    //
    //        map.on('mouseenter', 'points', function(e) {
    //            // Change the cursor style as a UI indicator.
    //            map.getCanvas().style.cursor = 'pointer';
    //
    //            var coordinates = e.features[0].geometry.coordinates.slice();
    //            var name = e.features[0].properties.name;
    //
    //            // Ensure that if the map is zoomed out such that multiple
    //            // copies of the feature are visible, the popup appears
    //            // over the copy being pointed to.
    //            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
    //                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
    //            }
    //
    //            // Populate the popup and set its coordinates
    //            // based on the feature found.
    //            popup.setLngLat(coordinates)
    //                .setHTML(name)
    //                .addTo(map);
    //        });
    //
    //        map.on('mouseleave', 'points', function() {
    //            map.getCanvas().style.cursor = '';
    //            popup.remove();
    //        });
    //    });

    var map = L.map('map').setView([42.8864, -78.8784], 10);
    var marker = L.marker([42.8864, -78.8784]).addTo(map);


    L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/256/{z}/{x}/{y}@2x?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoiamJodXRjaCIsImEiOiJjamRqZGU1eTYxMTZlMzNvMjV2dGxzdG8wIn0.IAAk5wKeLXOUaQ4QYF3sEA'
    }).addTo(map);

    
    function onEachFeature(feature, layer) {
        // does this feature have a property named popupContent?
        if (feature.properties && feature.properties.popupContent) {
            layer.bindPopup(feature.properties.popupContent);
        }
    }
    
    <?php $route = getCoords();?>
    var geojsonFeature = <?php echo json_encode($route['shape']); ?>;
    var style = <?php echo json_encode($route['style']); ?>;

    L.geoJSON(geojsonFeature, {
        style : style,
        onEachFeature: onEachFeature
    }).addTo(map);
    
</script>


<?php include "footer.php" ?>
