<?php include "inc/header.php";?>
<?php include "maps_xml.php"; ?>

<div id="map"></div>
<div class='map-overlay'>
    <div id='route'></div>
    <div id='points'></div>
</div>

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiamJodXRjaCIsImEiOiJjamRqZGU1eTYxMTZlMzNvMjV2dGxzdG8wIn0.IAAk5wKeLXOUaQ4QYF3sEA';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v10',
        center: [-78.87, 42.91],
        zoom: 10
    });

    
    var route = document.getElementById('route');
    var stops = document.getElementById('stops');
    
    map.on('load', function() {

        map.addLayer(
            <?php shapes();?>
        );
        
        map.addLayer(
            <?php stops();?>
        );

        var popup = new mapboxgl.Popup({
            closeButton: false,
            closeOnClick: false
        });
        

        map.on('mouseenter', 'route', function(e) {
            // Change the cursor style as a UI indicator.
            map.getCanvas().style.cursor = 'pointer';
            map.setPaintProperty('route', 'line-width', 8);
            
            // Render found features in an overlay.
            route.innerHTML = '';
            
            var id = document.createElement('div');
            id.textContent = 'Route ID: ' + e.features[0].properties.route_id;
            route.appendChild(id);
            route.style.display = 'block';
        });
        
        map.on('mouseenter', 'points', function(e) {
            points.innerHTML = '';
            
            var name = document.createElement('div');
            name.textContent = 'Stop Name: ' + e.features[0].properties.name;
            points.appendChild(name);
            points.style.display = 'block';
        });

        map.on('mouseleave', 'route', function() {
            map.getCanvas().style.cursor = '';
            map.setPaintProperty('route', 'line-width', 4);
            route.style.display = 'none';
        });
        
        map.on('mouseleave', 'points', function() {
            points.style.display = 'none';
        });
    });

</script>

<?php include "inc/footer.php"?>
