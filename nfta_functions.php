<?php

function dbConnection(){
    // Opens a connection to a MySQL server
    include "db.php";
    return $connection;
    
    //Close connection to db
    mysql_close($connection);
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
    $query = "SELECT stop_times.stop_id, stop_times.trip_id, trips.route_id, stop_times.arrival_time, trips.service_id, trips.direction_id, routes.route_long_name, stops.stop_name
    FROM stop_times 
    INNER JOIN trips ON stop_times.trip_id = trips.trip_id 
    INNER JOIN routes ON trips.route_id = routes.route_id
    INNER JOIN stops ON stop_times.stop_id = stops.stop_id
    WHERE stop_times.stop_id = " .$stopId. " AND trips.service_id = 7";
    
    //Return error if connection fails
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }

    // Puts Stop Data into an array
    while ($row = mysqli_fetch_assoc($result)){
    
        $stop['times'][] = date('g:i A', strtotime($row['arrival_time']));
        $stop['stop_name'] = $row['stop_name'];
        $stop['route_name'] = $row['route_long_name'];
    };
    
//    header('Content-Type: application/json'); 
//    echo json_encode($time)['time'];
    return $stop;

};

function getStops(){
    // Opens a connection to a MySQL server
    $connection = dbConnection();
    
    $query = "SELECT stops.stop_id, stops.stop_name 
    FROM stops 
    LIMIT 10";
    
        //Return error if connection fails
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die('Invalid query: ' . mysqli_error());
    }
    
    while ($row = mysqli_fetch_assoc($result)){
//        $theStops[] = array();
        
        $temp['id'] = $row['stop_id'];
        $temp['stop_name'] = $row['stop_name'];
        
        $theStops[] = $temp;
    
    };
    
//    header('Content-Type: application/json');    
//    echo json_encode($theStops, JSON_PRETTY_PRINT);
//    echo $theStops;
    
    return $theStops;
    
}



//getStops();
getTimes();
?>
