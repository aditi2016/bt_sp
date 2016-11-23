<?php

require_once "../includes/error.php";

$fist = explode("?", $_SERVER['REQUEST_URI']);
$route = explode("/", $fist[0]);
//var_dump($route);die();

$id = $route[1];

$dbHandle = mysqli_connect("localhost","root","redhat@11111p","blueteam_service_providers");

$fbRequest = mysqli_query($dbHandle, "SELECT * FROM `invoice` WHERE id = '$id' ;");
$count = mysqli_num_rows($fbRequest);
if($count == 0) {
    header('Location: http://blueteam.in/app/');
    die();
}
$fbRequestData = mysqli_fetch_array($fbRequest);

$customerName = $fbRequestData['customer_name'];

if(isset($_POST['reliability'])){
    $email = $_POST['email'];

    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`invoice` SET `customer_email` = '$email' WHERE `invoice`.`id` =$id;");


    $reliablityEnum = array('Yes' => 4, 'Nearly' => 3, 'Too late' => 1);

    $score = $reliablityEnum[$_POST['reliability']];
    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`service_providers`
                SET `reliability_score` = `reliability_score` + $score, `reliability_count` = `reliability_count` + 1
                          WHERE `service_providers`.`id` =".$fbRequestData['service_provider_id'].";");

    $message = "Thanks for using service\nYou have paid $invoice->amount including tax\nget bill on email at http://b.blueteam.in/".$invoice->id;

    sendMail($email, "Bill for customer service by BlueTeam", $message);

    header('Location: http://blueteam.in/app/');
    die();


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
             ){ ?>
    <?php if(!isset($route[2])){ ?>
    <form id="waterform" method="post">


        <div class="formgroup" id="email-form">
            <label for="email">Your e-mail*</label>
            <input type="email" id="email" name="email" />
        </div>


        <div class="formgroup" id="email-form" >
            <label for="email">Did I reached on time?</label>
            <div style="display:inline-block; vertical-align: middle;">
                <input type="submit" name="reliability" value="Yes" style="width: 130px;display: inline-block"/>
                <input type="submit" name="reliability" value="Nearly" style="width: 130px;display: inline-block" />
                <input type="submit" name="reliability" value="Too late" style="width: 130px;display: inline-block" />

            </div>
        </div>


    </form>

    <?php } ?>


    <?php  } else{ ?>

    <form id="waterform" method="post">

        <!--<div class="formgroup" id="name-form">
            <label for="name">Your name*</label>
            <input type="text" id="name" name="name" />
        </div>-->

        <div class="formgroup" id="email-form">
            <label for="email">Your bill has been send to your email id. </label>
            <label for="email">If you have not received it, Please give your Mobile no.</label>
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


</body>
</html>