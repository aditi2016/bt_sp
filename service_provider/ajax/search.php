<?php
session_start();
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