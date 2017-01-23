<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/24/16
 * Time: 10:18 PM
 */

function extractCommonWords($string){
    $stopWords = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from',
        'how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will',
        'with','und','the','www', 'also', 'you','your','yours','true');

    $string = preg_replace('/\s\s+/i', '', $string); // replace whitespace
    $string = trim($string); // trim the string
    $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too…
    $string = strtolower($string); // make it lowercase

    preg_match_all('/\b.*?\b/i', $string, $matchWords);
    $matchWords = $matchWords[0];

    foreach ( $matchWords as $key=>$item ) {
        if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {
            unset($matchWords[$key]);
        }
    }
    $wordCountArr = array();
    if ( is_array($matchWords) ) {
        foreach ( $matchWords as $key => $val ) {
            $val = strtolower($val);
            if ( isset($wordCountArr[$val]) ) {
                $wordCountArr[$val]++;
            } else {
                $wordCountArr[$val] = 1;
            }
        }
    }
    arsort($wordCountArr);
    $wordCountArr = array_slice($wordCountArr, 0, 10);
    return $wordCountArr;
}

//$page_access_token = 'EAADIQGZBO8vQBAN86wOjzGZBFSFw2W8BqQZCBjrS9XwQGKnZCmaeWg17X8ZBXh2R9Arwgb9UkDOPXXUq0dVR8rLJpb3ojKUvOTG5ZBdR52etFT0qpn5rmLZBLuW5ZA5w5ESuce5vIpLxZAsSjoRaBVdov6R1Y5L8fxK4ZD';



$dbHandle = mysqli_connect("localhost","root","redhat@11111p","ragnar_social");

//SELECT * FROM `auth_keys` WHERE 1

$companies = mysqli_query($dbHandle, "SELECT * FROM `auth_keys` WHERE status = 0");

while ($company = mysqli_fetch_array($companies)){
    $page_id = $company['page_id'];
    $companyId = $company['company_id'];
    $page_access_token = $company['access_token'];

    $tagsArr = mysqli_query($dbHandle, "SELECT * FROM `tags` WHERE company_id = ".$companyId);

    $tags = "";
    while ($tag = mysqli_fetch_array($tagsArr)){
        $tags = $tags. ",".$tag['tag'];
    }
    $tags = ltrim($tags,',');

    echo "tags: " . $tags . "\npage_id: ". $page_id . "\ncompanyId: " . $companyId."\n access token: ".$page_access_token."\n";

    $posts = mysqli_query($dbHandle, "
                SELECT
                  a.`id`, a.`company_id`, a.user_id, a.`title`, a.`description`, a.`link`, a.`raw_img_id`,
                  a.gen_img_id, b.logo_id
                FROM `posts` as a
                  inner join companies as b
                  WHERE a.gen_img_id != 0
                      and a.company_id = ".$companyId."
                      and a.`status` = 'approved'
                      and a.id NOT IN (SELECT post_id FROM post_tracks WHERE social_network_id = 1)
                      and a.company_id = b.id and a.gen_img_id != 0 ORDER BY RAND() limit 0,1");

    $post = mysqli_fetch_array($posts);

    var_dump($post);
    if(isset($post)){
        $keywords = extractCommonWords($post['description']);
//var_dump($keywords);
        foreach($keywords as $word => $t){
            //echo $word."\n";
            $post['description'] = str_ireplace($word,"#".$word,$post['description']);
        }
        $post['description'] = "#".str_replace(' ', '',ucwords($post['title'])) . ": " .$post['description'];

//var_dump($post);
        if($post['link']){
            $data['picture'] = "http://api.file-dog.shatkonlabs.com/files/rahul/".$post['gen_img_id'];
            $data['link'] = "http://ragnarsocial.com/l/?p=".$post['company_id'].'-'.$post['user_id'].'-'.$post['id'].'-f';
            $data['message'] = $post['description']." http://ragnarsocial.com/l/?p=".$post['company_id'].'-'.$post['user_id'].'-'.$post['id'].'-f';
            $data['place'] = "596434263827904";

            if($tags != "")
                $data['tags'] = $tags;
            $data['caption'] = "Get ". $post['title']." services";
            $data['description'] = $post['description'];

            $data['access_token'] = $page_access_token;

//var_dump($data);

            $post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $post_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($ch);
            $fbReturn = json_decode($return);
            var_dump($fbReturn);
            curl_close($ch);

            if($fbReturn->id){
                $sql = "INSERT INTO
                `post_tracks`(`post_id`, `social_network_id`, `publish_id`, `publish_datatime`, `creation`)
              VALUES (".$post['id'].",1,'".$fbReturn->id."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
                mysqli_query($dbHandle, $sql);
            }
        }
        sleep(rand(3,5));
    }
}
mysqli_close($dbHandle);
