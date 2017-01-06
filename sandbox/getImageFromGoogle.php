<?php
include "simple_html/simple_html_dom.php";
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
$search_query = $_GET['keywords'];
$search_query = urlencode( $search_query );
$html = file_get_html( "https://www.pexels.com/search/$search_query/"  );
//$painhtml = file_get_contents( "https://www.pexels.com/search/life/" );
//$image_container = $html->find('div#rcnt', 0);
//preg_match('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/]+!', $painhtml, $text);

//var_dump($painhtml);die();/*

$images = $html->find('img');
$image_count = 10; //Enter the amount of images to be shown
$i = 0;
//var_dump($images);
foreach($images as $image){

    // DO with the image whatever you want here (the image element is '$image'):
    if(get_string_between($image, "data-pin-media=\"", "\" "))
    echo '<img src="'.explode('?',get_string_between($image, "data-pin-media=\"", "\" "))[0]."?h=350&auto=compress\" style='max-width:200px;'>\n";
}

//http://api.pexels.com/v1/search?query=example+query&per_page=15&page=1

/*$url = "http://api.pexels.com/v1/search?query=example+query&per_page=15&page=1";
// sendRequest
// note how referer is set manually
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'http://blueteam.in');
$body = curl_exec($ch);
curl_close($ch);

// now, process the JSON string
$json = json_decode($body);
var_dump($json);*/