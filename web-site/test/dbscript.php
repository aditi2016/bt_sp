$db_handle = mysqli_connect("localhost","root","blueteam@11111p","compaining");
$db_handle2	 = mysqli_connect("localhost","root","blueteam@11111p","shatkonjobs");
$response = mysqli_query($db_handle,"select * from workers ;") ;
while($responseRow = mysqli_fetch_array($response)){
	$name = $responseRow["first_name"]." ".$responseRow["last_name"];
	$phone = $responseRow["phone"];
	$address = $responseRow["current_address"]." ".$responseRow["area"];
	$age = $responseRow["age"];
	$gender = $responseRow["gender"];
	$remarks = $responseRow["remarks"];
	$time = date("Y-m-d");
	if($phone != '2147483647') { 
		mysqli_query($db_handle2,"INSERT INTO workers (name, mobile, address, gender, cr_time, age, remarks, insert_id) VALUES ('$name', '$phone', '$address', '$gender', '$time', '$age', '$remarks', 1 );"); 
		echo "inserted";
	}
	else echo "aborting";
}