<?php
session_start();
include_once 'functions.php';
if (isset($_POST['name'])) {
	
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
	$lookup_id = $_POST['lookup_id'];
	$date = date("Y-m-d H:i:s");
   
    mysqli_query($dbHandle, "INSERT INTO add_services(name, mobile, lookup_id, creation_time) 
                                VALUES ('$name', '$mobile', '$lookup_id', '$date');");
    	
    echo "Succesfully" ;
		
}

mysqli_close($dbHandle);
?>