<?php include "db.php"; ?>
<?php

function showAllData(){
    global $connection;
    $query = "SELECT * FROM map_locations";
    
    $result = mysqli_query($connection, $query);
    
    if(!$result){
        die('Query failed.' . mysqli_error($connection));
    }

    while($row = mysqli_fetch_assoc($result)){
        $id = $row['id'];
      echo "<option value='$id'>$id</option>";

    }   
}

function geocoder(){
    global $connection;
    $address = $_POST['address'];
    $address = mysqli_real_escape_string($connection, $address);
    $encodeAdd = urlencode($address);

    $apikey = "AIzaSyCoaxEFz926RAn5JG8NvrRK0KQ9xI8g_e4"; 
    $geourl = "https://maps.googleapis.com/maps/api/geocode/xml?address=$encodeAdd,+Buffalo,+NY&key=$apikey"; 

    /* Create cUrl object to grab XML content using $geourl */ 
    $c = curl_init(); 
    curl_setopt($c, CURLOPT_URL, $geourl); 
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
    $xmlContent = trim(curl_exec($c)); 
    curl_close($c); 
    /* Create SimpleXML object from XML Content*/
    $xmlObject = simplexml_load_string($xmlContent); 
    /* Print out all of the XML Object*/ 
    $localObject = $xmlObject->result->geometry->location; 

    $lng = ($localObject->lng);
    $lat = ($localObject->lat);
    return array($address, $lng, $lat);
}
?>