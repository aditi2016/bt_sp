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
        'with','und','the','www', 'also', 'you','your','yours','true','which','only','have','would','everything');

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

$companies = mysqli_query($dbHandle, "SELECT * FROM `auth_keys` WHERE social_network_id = 1 and status = 0 and company_id =3");

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

    echo "tags: " . $tags . "\npage_id: ". $page_id . "\ncompanyId: " . $companyId."\n";

    $posts = mysqli_query($dbHandle, "
                select *
                from shatkon_sherlock.urls
                WHERE keyword_id = 1 and id not in (SELECT `shatkon_sherlock_id` FROM ragnar_social.`shatkon_sherlock_urls` WHERE 1) ORDER BY `last_updated` DESC
              LIMIT 0 , 15");

    $post = mysqli_fetch_array($posts);

    var_dump($post);
    if(isset($post)){
     /*   $keywords = extractCommonWords($post['description']);
//var_dump($keywords);
        foreach($keywords as $word => $t){
            //echo $word."\n";
            $post['description'] = str_ireplace($word,"#".$word,$post['description']);
        }
        $post['description'] = "#".str_replace(' ', '',ucwords($post['title'])) . ": " .$post['description'];*/

//var_dump($post);
        if($post['url']){
            //&#39;
            $post['description'] = str_replace('&#39;', "//'",$post['description']);
            $data['picture'] = $post['img'];
            $data['link'] = $post['url'];
            $data['message'] = "Get Upto date with all exam news and and Learn & Earn at https://www.examhans.com";
            $data['place'] = $page_id;

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
                $sql = "INSERT INTO `shatkon_sherlock_urls`(`shatkon_sherlock_id`) VALUES (".$post['id'].")";
                mysqli_query($dbHandle, $sql);
            }
        }
        //sleep(rand(3,5));
    }
}
mysqli_close($dbHandle);
