<?php
function rand_color() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
};


function shapes(){
    // Opens a connection to a MySQL server
    include "db.php";

    // Select all the rows in the markers table
    $query = "SELECT * FROM route_path";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }

    $previous = 1;
    $allRoutes = array();
    $coords = array();
    
    while ($row = mysqli_fetch_assoc($result)){

        $current = $row['shape_id'];

        // We've switched to a new route, output the set of coords
        if ($current > $previous){
            $color = rand_color();
            $route = array(
                'type' => 'Feature',
                'properties' => array(
                    'color' => $color,
                    'line-width' => 4,
                    'shape_id' => $row['shape_id']
                ),
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $coords
                )
            );
            array_push($allRoutes, $route);
            array_push($coords, array($row['lng'], $row['lat']));
            $coords = array();
        } else{
            array_push($coords, array($row['lng'], $row['lat']));
        } 
        
        $previous = $current;
    };

    
    // Did we have a set of coords left over from the last row?

    $mapPath = array(
        'id' => 'route',
        'type' => 'line',
        'source' => array(
            'type' => 'geojson',
            'data' => array(
                'type' => 'FeatureCollection',
                'features' => $allRoutes
            )
        ),
        'layout' => array(
            'line-join' => 'round',
            'line-cap' => 'round'
        ),
        'paint' => array(
            'line-color' => array('get','color'),
            'line-width' => array('get', 'line-width')
        )
    );
    
    echo json_encode($mapPath, JSON_PRETTY_PRINT);
    
};

function stops(){
    // Opens a connection to a MySQL server
    include "db.php";

    // Select all the rows in the markers table
    $query = "SELECT * FROM nfta_stops";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }

    $previous = 0;
    $allRoutes = array();


    $coords = array();
    while ($row = mysqli_fetch_assoc($result)){

        $current = $row['id'];

        // We've switched to a new route, output the set of coords
        if ($current > $previous){
            $route = array(
                'type' => 'Feature',
                'properties' => array(
                    'stop_id' => $row['stop_id'],
                    'name' => $row['name']
                ),
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array($row['lng'], $row['lat'])
                )
            );
            array_push($allRoutes, $route);
            $coords = array();
        } 
        
        $previous = $current;
    };

    
    // Did we have a set of coords left over from the last row?

    $allStops = array(
        'id' => 'points',
        'type' => 'circle',
        'source' => array(
            'type' => 'geojson',
            'data' => array(
                'type' => 'FeatureCollection',
                'features' => $allRoutes
            )
        ),
        'paint' => array(
            'circle-radius' => 2,
            'circle-color' => '#ff0000'
        )
    );
    
    echo json_encode($allStops, JSON_PRETTY_PRINT);
    
};

function test(){
    header("Content-type: application/json");
    shapes();
};

test();
?>
