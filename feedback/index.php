<?php

$fist = explode("?", $_SERVER['REQUEST_URI']);
$route = explode("/", $fist[0]);
//var_dump($route);die();

$id = $route[1];

$dbHandle = mysqli_connect("localhost","root","redhat@11111p","blueteam_service_providers");

$fbRequest = mysqli_query($dbHandle, "SELECT * FROM `feedback_requests` WHERE id = '$id' ;");
$count = mysqli_num_rows($fbRequest);
if($count == 0) {
    header('Location: http://blueteam.in/app/');
    die();
}
$fbRequestData = mysqli_fetch_array($fbRequest);

$customerName = $fbRequestData['customer_name'];

if(isset($_POST['feedback'])){
    $feedback =$_POST['feedback'];
    $email = $_POST['email'];
    $type = $_POST['feedback_s'];
    //'complain','suggestion','appreciation','marvelous'
    $typeEnum = array('Awesome' => 'complain', 'Good' =>'suggestion', 'Ok' => 'appreciation','Not Satisfied' => 'marvelous');
    $type = $typeEnum[$type];

    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`feedback_requests` SET `customer_email` = '$email' WHERE `feedback_requests`.`id` =$id;");
    $sql = "INSERT INTO `wazir`.`feedbacks` (

                    `object_id` ,
                    `user_id` ,
                    `feedback`,
                    `type`,
                    digieye_user_id
                    )
                    VALUES (
                    'bt-sp-$id', 1, '$feedback','$type',1
                    );";
    mysqli_query($dbHandle, $sql);

    header('Location: '.$id.'/2');
}

if(isset($_POST['reliability'])){

    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`feedback_requests` SET `reliability_score` = 1 WHERE `feedback_requests`.`id` =$id;");


    $reliablityEnum = array('Yes' => 4, 'Nearly' => 3, 'Too late' => 1);
    //UPDATE `blueteam_service_providers`.`service_providers` SET `reliability_score` = '1' WHERE `service_providers`.`id` =1;

    $score = $reliablityEnum[$_POST['reliability']];
    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`service_providers`
                SET `reliability_score` = `reliability_score` + $score, `reliability_count` = `reliability_count` + 1
                          WHERE `service_providers`.`id` =".$fbRequestData['service_provider_id'].";");

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
    <link href="http://f.blueteam.in/feedback.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>

<header>
    <h1>Hello, <?= $customerName ?> </h1>
</header>


<div id="form">


    <div class="fish" id="fish"></div>
    <div class="fish" id="fish2"></div>

    <?php if(($fbRequestData['customer_email'] == "" || $fbRequestData['customer_email'] == null)
            || ($fbRequestData['reliability_score'] == 0 && isset($route[2]) && $route[2] == "2" ) ){ ?>
    <?php if(!isset($route[2])){ ?>
    <form id="waterform" method="post">

        <!--<div class="formgroup" id="name-form">
            <label for="name">Your name*</label>
            <input type="text" id="name" name="name" />
        </div>-->

        <div class="formgroup" id="email-form">
            <label for="email">Your e-mail*</label>
            <input type="email" id="email" name="email" />
        </div>

        <div class="formgroup" id="message-form">
            <label for="message">What do you say about my service?</label>
            <textarea id="feedback" name="feedback"></textarea>
        </div>

        <div class="formgroup" id="email-form" >
            <label for="email">Did you liked my work?</label>
            <div style="display:inline-block; vertical-align: middle;">
            <input type="submit" name="feedback_s" value="Awesome" style="width: 130px;display: inline-block"/>
            <input type="submit" name="feedback_s" value="Good" style="width: 130px;display: inline-block" />
            <input type="submit" name="feedback_s" value="Ok" style="width: 130px;display: inline-block" />
            <input type="submit" name="feedback_s" value="Not Satisfied" style="width: 140px;display: inline-block"/>
                </div>
        </div>


    </form>

    <?php } ?>

    <?php if(isset($route[2])){ ?>
    <form id="onTime" method="post">


        <div class="formgroup" id="email-form" >
            <label for="email">Did I reached on time?</label>
            <div style="display:inline-block; vertical-align: middle;">
                <input type="submit" name="reliability" value="Yes" style="width: 130px;display: inline-block"/>
                <input type="submit" name="reliability" value="Nearly" style="width: 130px;display: inline-block" />
                <input type="submit" name="reliability" value="Too late" style="width: 130px;display: inline-block" />

            </div>
        </div>


    </form>
    <?php } } else{ ?>

    <form id="waterform" method="post">

        <!--<div class="formgroup" id="name-form">
            <label for="name">Your name*</label>
            <input type="text" id="name" name="name" />
        </div>-->

        <div class="formgroup" id="email-form">
            <label for="email">This feedback have already give, if you have not given it. </label>
            <label for="email">Please give your Mobile no.</label>
            <input type="number" id="email" name="email" />
        </div>



        <div class="formgroup" id="email-form" >

            <div style="display:inline-block; vertical-align: middle;">
                <input type="submit" name="feedback_issue" value="Submit" style="width: 130px;display: inline-block"/>
                </div>
        </div>


    </form>
    <?php } ?>
</div>

<script src="http://f.blueteam.in/feedback.js"></script>

<script type="text/javascript">
    //send user email
    //send feedback

    //send reliability score

    //give thank you

    //'complain','suggestion','appreciation','marvelous'
    function sendFeedback() {
        /*var person = {
            name: $("#id-name").val(),
            address:$("#id-address").val(),
            phone:$("#id-phone").val()
        }*/

        var feedback = {
            "digieye_user_id":"1",
            "feedback":$("#feedback").val(),
            "type": $("#type").val()
        }

        $('#target').html('sending..');

        $.ajax({
            url: 'http://api.wazir.shatkonlabs.com/feedbacks/1/bt-sp-<?= $id ?>',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                $('#target').html(data.msg);
            },
            data: feedback
        });
    }
</script>

</body>
</html>