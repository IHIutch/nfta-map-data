<?php include "inc/header.php"?>
<?php 
function geocoder(){
    $address = $_POST['address'];
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
    echo "(a)" . $lng . " " . "(a)" . $lat;
    
    return array($lng, $lat);
}
?>
<div class="container">
       <div class="col-xs-6">
            <form action="coordinates.php" method="post">
                <div class="form-group">
                   <label>Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="submit" value="SUBMIT">
                </div>
            </form>
    </div>
    <?php 
    $array = geocoder();
    $lng = $array[0];
    $lat = $array[1];
    echo "(b)" . $lng . " " . "(b)" . $lat;
    ?>
    
</div>
<? include "inc/footer.php"?>