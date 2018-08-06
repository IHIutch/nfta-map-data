<?php

function dbConnection(){
    // Opens a connection to a MySQL server
    include "db.php";
    return $connection;
    
    //Close connection to db
    mysqli_close($connection);
};

function getStopIdFromURL(){
    $urlStopId = $_GET["stop_id"];
    return $urlStopId;
};

function rand_color() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
};

function getTimes(){
        
    // Opens a connection to a MySQL server
    $connection = dbConnection();
    $stopId = getStopIdFromURL();
    
    
    // Select data from database
    $query = "SELECT stop_times.stop_id, stop_times.trip_id, trips.route_id, stop_times.arrival_time, trips.service_id, trips.direction_id, routes.route_long_name, stops.stop_name, routes.route_color
    FROM stop_times 
    INNER JOIN trips ON stop_times.trip_id = trips.trip_id 
    INNER JOIN routes ON trips.route_id = routes.route_id
    INNER JOIN stops ON stop_times.stop_id = stops.stop_id
    WHERE stop_times.stop_id = ". $stopId ." AND trips.service_id = 7
    ORDER BY stop_times.arrival_time";
    
    //Return error if connection fails
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }

    // Puts Stop Data into an array
    while ($row = mysqli_fetch_assoc($result)){
                
        $stop['stop_name'] = $row['stop_name'];
        $stop['route_info'][] = array(
            'route_name' => $row['route_long_name'],
            'route_color' => $row['route_color'],
            'time' => date('g:i A', strtotime($row['arrival_time']))
        );
    };
    
    return $stop;

};

function getStops(){
    // Opens a connection to a MySQL server
    $connection = dbConnection();
    
    $query = "SELECT
    stops.stop_name,
    stops.stop_id,
    stop_times.stop_sequence
FROM
    stops
INNER JOIN(
    SELECT
        stop_times.stop_id,
        stop_times.trip_id,
        stop_times.stop_sequence
    FROM
        stop_times
    WHERE
        stop_times.trip_id IN(
        SELECT
            trips.trip_id
        FROM
            trips
        WHERE
            trips.trip_id = 6410 AND trips.route_id = 20 AND trips.service_id = 7
    )
) stop_times
ON
    stops.stop_id = stop_times.stop_id
ORDER BY
    stop_times.trip_id,
    stop_times.stop_sequence";
    
        //Return error if connection fails
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }
    
    while ($row = mysqli_fetch_assoc($result)){
        
        $theStops[] = array(
            'id' => $row['stop_id'],
            'stop_name' => $row['stop_name']
        );
    
    };
    
    return $theStops;
    
}

function renderTheStops(){
    // Opens a connection to a MySQL server
    $connection = dbConnection();

    // Select all the rows in the markers table
    $query = "SELECT
    stops.stop_name,
    stops.stop_id,
    stop_times.stop_sequence,
    stops.stop_lat,
    stops.stop_lon
FROM
    stops
INNER JOIN(
    SELECT
        stop_times.stop_id,
        stop_times.trip_id,
        stop_times.stop_sequence
    FROM
        stop_times
    WHERE
        stop_times.trip_id IN(
        SELECT
            trips.trip_id
        FROM
            trips
        WHERE
            trips.trip_id = 6410 AND trips.route_id = 20 AND trips.service_id = 7
    )
) stop_times
ON
    stops.stop_id = stop_times.stop_id
ORDER BY
    stop_times.trip_id,
    stop_times.stop_sequence";
    
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }

    $previous = 0;
    $allMarkers = array();

    $coords = array();
    while ($row = mysqli_fetch_assoc($result)){

        $current = $row['stop_sequence'];

        // We've switched to a new route, output the set of coords
        if ($current > $previous){
            $marker = array(
                'type' => 'Feature',
                'properties' => array(
//                    'stop_id' => $row['stop_id'],
                    'name' => $row['stop_name'],
//                    'address' => $row['address'],
//                    'type' => $row['type'],
//                    'specific_type' => $row['specific_type']
                ),
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array($row['stop_lon'], $row['stop_lat'])
                )
            );
            
            
            $paint = array(
                'circle-radius' => 4,
                'circle-color' => "#FF0000",
                'circle-opacity' => 0.6
                );
                
            array_push($allMarkers, $marker);
        } 
        
        $previous = $current;
    };

    
    // Did we have a set of coords left over from the last row?
    $allPoints = array(
        'id' => 'points',
        'type' => 'circle',
        'source' => array(
            'type' => 'geojson',
            'data' => array(
                'type' => 'FeatureCollection',
                'features' => $allMarkers
            )
        ),
        'paint' => $paint
    );
    
    echo json_encode($allPoints);
    
};

function renderRoute(){
    
    // Opens a connection to a MySQL server
    $connection = dbConnection();

    // Select all the rows in the markers table
    $query = "SELECT trips.trip_id, shapes.shape_pt_lat, shapes.shape_pt_lon, shapes.shape_id, shapes.shape_pt_sequence FROM trips INNER JOIN shapes ON trips.shape_id = shapes.shape_id WHERE trip_id = 6410";
    
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }

    $previous = 0;
    $allMarkers = array();


    $coords = array();
    
    
    while ($row = mysqli_fetch_assoc($result)){
        $points[] = array($row['shape_pt_lon'], $row['shape_pt_lat']);
    };
    
    $route_shape = array(
        'id' => 'route',
        'type' => 'line',
        'source' => array(
            'type' => 'geojson',
            'data' => array(
                'type' => 'Feature',
                'properties' => array(),
                'geometry' => array(
                        'type' => 'LineString',
                        'coordinates' => $points
                )
                    
            )
        ),
        'layout' => array(
            'line-join' => 'round',
            'line-cap' => 'round'
        ), 
        'paint' => array(
            'line-color' => '#FF0000',
            'line-width' => 3,
            'line-opacity' => 1
        )
    );
    
    echo json_encode($route_shape);  
};

function getCoords(){
    $connection = dbConnection();
    
    $query = "SELECT
        trips.trip_id,
        shapes.shape_pt_lat,
        shapes.shape_pt_lon,
        shapes.shape_id,
        shapes.shape_pt_sequence,
        routes.route_color,
        routes.route_long_name,
        routes.route_id
    FROM
        trips
    INNER JOIN shapes ON trips.shape_id = shapes.shape_id
    INNER JOIN routes ON trips.route_id = routes.route_id
    WHERE
        trip_id = 6410";
    
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }
    
    while ($row = mysqli_fetch_assoc($result)){
        
        $route_info = array(
            'shape' => array(
                'type' => 'Feature',
                'properties' => array(
                    'name' => $row['route_long_name'],
                    'popupContent' => 'Route ' . $row['route_id'] . '<br>' . $row['route_long_name']
                ),
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $points
                )
            ),
            'style' => array(
                'color' => $row['route_color'],
                'weight' => 5,
                'opacity' => 1
            )
        );
        $points[] = array($row['shape_pt_lon'], $row['shape_pt_lat']);
    };
    
    return $route_info;
};


function test(){
    header('Content-Type: application/json');
    $a = getTimes();
    
    echo json_encode($a);
};

//test();
?>
