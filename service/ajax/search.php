<?php
session_start();
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
include_once 'functions.php';

if (isset($_POST['keywords'])) {
	
	$keywords = mysqli_real_escape_string($dbHandle,$_POST['keywords']);
   
    $url = "http://api.sp.blueteam.in/search/".$keywords;
    
    $result = json_decode(httpGet($url), true)['allServices'];
	$data = "<div class='hide-embed' id='similar-card'>
                  <div class='bordered-card card-cont mw similar-flat-card'>
                    <h2 class='header-cont'>Search Results</h2>
                    <div class='body-cont'>
                      <div class='flat-container'>";
    foreach ($result as $key => $value) {
        $data .= "<a class='flat-link' href='../service/index.php?load=".$value['name']."'>
                  <div class='flat-img'>
                    <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$value['pic_id'].")'></div>
                    <div class='name-info'>
                      <div class='project-info'>".$value['name']."</div>
                    </div>
                  </div>
                  <div class='apt-info text'>".$value['description']."</div>
                </a>" ;
    }
    $data = $data."</div>
                 </div>
                </div>
              </div>";
    echo $data ;
		
}

mysqli_close($dbHandle);
?>