<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/24/16
 * Time: 8:13 PM
 */

$dbHandle = mysqli_connect("localhost","root","redhat@11111p","ragnar_social");

$posts = mysqli_query($dbHandle, "
                SELECT
                  a.`id`, a.`company_id`, a.`title`, a.`description`, a.`link`, a.`raw_img_id`, b.logo_id
                FROM `posts` as a
                  inner join companies as b
                  WHERE a.gen_img_id = 0 and a.`status` = 'not-approved' and a.company_id = b.id");

while ( $post = mysqli_fetch_array($posts)) {
    var_dump($post);
    $genId = getGenPost($post['raw_img_id'],$post['logo_id'],$post['description'],"Get ".$post['title']." Services<br>Get More Info <br>" ,"www.StandupIndians.com");
    if(isset($genId) && $genId != 0){
        $sql="UPDATE `ragnar_social`.`posts` SET `gen_img_id` = '".$genId."' WHERE `posts`.`id` =".$post['id'].";";
        echo $sql."\n";
        //break;
        mysqli_query($dbHandle, $sql);
    }else
    break;

}

function getGenPost($bgImg,$logo,$focus,$target,$link){
    $bgImg = urlencode($bgImg);
    $logo = urlencode($logo);
    $focus = urlencode($focus);
    $target = urlencode($target);
    $link = urlencode($link);
    //$address = urlencode($address);
    $url = "http://blueteam.in/sandbox/html2image.php?".
            "base_img_url=http://api.file-dog.shatkonlabs.com/files/rahul/$bgImg".
            "&logo_img_url=http://api.file-dog.shatkonlabs.com/files/rahul/$logo&".
            "focus=$focus&".
            "target=$target&link=$link";
    echo $url ."\n";
    $response = file_get_contents($url);
    $json = json_decode($response,true);

    var_dump($json);

    return $json['file']['id'];
}

