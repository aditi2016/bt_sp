<?php
	
require __DIR__ . '/vendor/autoload.php';

function getHtmlGenTemp($replaceArray){
	$dbHandle = mysqli_connect("localhost","root","redhat@11111p","ragnar_social");
	$templetes = mysqli_query($dbHandle, "SELECT * FROM templates WHERE status = 'active' ORDER BY RAND() LIMIT 1 ;");
	$templeteData = mysqli_fetch_array($templetes);
	$templete = htmlspecialchars_decode($templeteData['temp'], ENT_QUOTES);

	$m = new Mustache_Engine;
	$temp = $m->render($templete, array('bgImg' => $replaceArray['bgImg'],'logo'=>$replaceArray['logo'],'focus'=>$replaceArray['focus'],'target'=>$replaceArray['target']));
	return $temp; 	
}
?>