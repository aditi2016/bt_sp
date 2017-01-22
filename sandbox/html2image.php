<?php
require_once "getHtmlGenTemp.php";

$apikey = "2ae4363709e08c29"; //please input your RESTful api key here.
$api_url = "http://api.page2images.com/html2image";

$bgImg = $_GET['base_img_url'];
$logo = $_GET['logo_img_url'];
$focus = $_GET['focus']."<br>Share If You Agree";
$target = $_GET['target'];
$link = $_GET['link'];

//var_dump(file_get_contents(call_p2i($bgImg,$logo,$focus,$target."<br/><br/>".$link)));die();
unlink("Tmpfile.png");
file_put_contents("Tmpfile.png", fopen(call_p2i($bgImg,$logo,$focus,$target."<br/><br/>".$link),'r'));

echo upload_file("Tmpfile.png");

function upload_file($uri)
{
    $target_url = 'http://api.file-dog.shatkonlabs.com/files/rahul';
//This needs to be the full path to the file you want to send.
    $file_name_with_full_path = realpath('/home/spider-ninja/acc.txt');

    $cfile = curl_file_create($uri, 'image/png', end(explode('/', $uri)));
    /* curl will accept an array here too.
     * Many examples I found showed a url-encoded string instead.
     * Take note that the 'key' in the array will be the key that shows up in the
     * $_FILES array of the accept script. and the at sign '@' is required before the
     * file name.
     */
    $post = array('fileToUpload' => '123456', 'fileToUpload' => $cfile);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result  = curl_exec($ch);
    //var_dump($result);
    curl_close($ch);
    /*$fileIdObj = json_decode($result);
    $fileId = $fileIdObj->file->id;*/
    return $result;

}

function call_p2i($bgImg,$logo,$focus,$target)
{
    global $apikey, $api_url;
    // URL can be those formats: http://www.google.com https://google.com google.com and www.google.com
    $device = 6; // 0 - iPhone4, 1 - iPhone5, 2 - Android, 3 - WinPhone, 4 - iPad, 5 - Android Pad, 6 - Desktop
    $loop_flag = TRUE;
    $timeout = 120; // timeout after 120 seconds
    set_time_limit($timeout+10);
    $start_time = time();
    $timeout_flag = false;
    $html = getHtmlGenTemp(array('bgImg' => $bgImg,'logo'=>$logo,'focus'=>$focus,'target'=>$target));
    var_dump($html);die();
    //Note: free rate plan user cannot use SSL url.
    $url = ""; //This is the URL of the page. We will use it to generate relative path to get remote resources: css, js or images.

    while ($loop_flag) {
        // We need call the API until we get the screenshot or error message
        try {
            $para = array(
            		"p2i_html" => $html,
                "p2i_url" => $url,
                "p2i_key" => $apikey,
                "p2i_device" => $device
            );
            // connect page2images server
            $response = connect($api_url, $para);

            if (empty($response)) {
                $loop_flag = FALSE;
                // something error
                echo "something error";
                break;
            } else {
                $json_data = json_decode($response);
                if (empty($json_data->status)) {
                    $loop_flag = FALSE;
                    // api error
                    break;
                }
            }
            switch ($json_data->status) {
                case "error":
                    // do something to handle error
                    $loop_flag = FALSE;
                    echo $json_data->errno . " " . $json_data->msg;
                    break;
                case "finished":
                    // do something with finished. For example, show this image
                    //echo "<img src='$json_data->image_url'>";
                    return $json_data->image_url;
                    // Or you can download the image from our server
                    $loop_flag = FALSE;
                    break;
                case "processing":
                default:
                    if ((time() - $start_time) > $timeout) {
                        $loop_flag = false;
                        $timeout_flag = true; // set the timeout flag. You can handle it later.
                    } else {
                        sleep(3); // This only work on windows.
                    }
                    break;
            }
        } catch (Exception $e) {
            // Do whatever you think is right to handle the exception.
            $loop_flag = FALSE;
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    if ($timeout_flag) {
        // handle the timeout event here
        echo "Error: Timeout after $timeout seconds.";
    }
}
// curl to connect server
function connect($url, $para)
{
    if (empty($para)) {
        return false;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($para));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function call_p2i_with_callback()
{
    global $apikey, $api_url;
    // URL can be those formats: http://www.google.com https://google.com google.com and www.google.com
    $url = "http://www.google.com";
    // 0 - iPhone4, 1 - iPhone5, 2 - Android, 3 - WinPhone, 4 - iPad, 5 - Android Pad, 6 - Desktop
    $device = 0;

    // you can pass us any parameters you like. We will pass it back.
    // Please make sure http://your_server_domain/api_callback can handle our call
    $callback_url = "http://your_server_domain/api_callback?image_id=your_unique_image_id_here";
    $para = array(
                "p2i_url" => $url,
                "p2i_key" => $apikey,
                "p2i_device" => $device
            );
    $response = connect($api_url, $para);

    if (empty($response)) {
        // Do whatever you think is right to handle the exception.
    } else {
        $json_data = json_decode($response);
        if (empty($json_data->status)) {
            // api error do something
            echo "api error";
        }else
        {
            //do anything
            echo $json_data->status;
        }
    }

}

// This function demo how to handle the callback request
function api_callback()
{
    if (! empty($_REQUEST["image_id"])) {
        // do anything you want about the unique image id. We suggest to use it to identify which url you send to us since you can send 1,000 at one time.
    }

    if (! empty($_POST["result"])) {
        $post_data = $_POST["result"];
        $json_data = json_decode($post_data);
        switch ($json_data->status) {
            case "error":
                // do something with error
                echo $json_data->errno . " " . $json_data->msg;
                break;
            case "finished":
                // do something with finished
                echo $json_data->image_url;
                // Or you can download the image from our server
                break;
            default:
                break;
        }
    } else {
        // Do whatever you think is right to handle the exception.
        echo "Error: Empty";
    }
}
