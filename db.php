<?php
$connection = mysqli_connect('localhost', 'root', 'root', 'nfta_info');
    
    if(!$connection){
        die("Database connection failed.");
    }
?>