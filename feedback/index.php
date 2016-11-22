<?php

$fist = explode("?", $_SERVER['REQUEST_URI']);
$route = explode("/", $fist[0]);
var_dump($route);die();

$id = $route[1];

$dbHandle = mysqli_connect("localhost","root","redhat111111","blueteam_service_providers");

$fbRequest = mysqli_query($dbHandle, "SELECT *
                                              FROM `feedback_requests`
                                                WHERE id = '$id' ;");
$fbRequestData = mysqli_fetch_array($serviceProvider);

$customerName = $fbRequestData['customer_name'];



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
    <link href="./feedback.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>

<header>
    <h1>Hello, <?= $customerName ?> </h1>
</header>


<div id="form">


    <div class="fish" id="fish"></div>
    <div class="fish" id="fish2"></div>

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
            <input type="submit" value="Awesome" style="width: 130px;display: inline-block"/>
            <input type="submit" value="Good" style="width: 130px;display: inline-block" />
            <input type="submit" value="Ok" style="width: 130px;display: inline-block" />
            <input type="submit" value="Not Satisfied" style="width: 140px;display: inline-block"/>
                </div>
        </div>


    </form>

    <form id="onTime" method="post">


        <div class="formgroup" id="email-form" >
            <label for="email">Did I reached on time?</label>
            <div style="display:inline-block; vertical-align: middle;">
                <input type="submit" value="Yes" style="width: 130px;display: inline-block"/>
                <input type="submit" value="Nearly" style="width: 130px;display: inline-block" />
                <input type="submit" value="Too late" style="width: 130px;display: inline-block" />

            </div>
        </div>


    </form>
</div>

<script src="./feedback.js"></script>

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