<?php

require_once "../api/includes/error.php";


function getInvoiceHtml($serviceName, $servicePrice, $totalAmount, $serviceTax,$partnerName, $partnerId, $partnerAddress, $partnerMobile,$customerName, $customerEmail, $customerMobile, $logoImg, $invoiceId, $creation){

    return "<!doctype html>
<html>
<head>
    <meta charset=\"utf-8\">
    <title>Invoice for $serviceName of Rs $totalAmount, by $partnerName. Power by blueteam.in</title>

    <style>
        .invoice-box{
            max-width:800px;
            margin:auto;
            padding:30px;
            border:1px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            font-size:16px;
            line-height:24px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color:#555;
        }

        .invoice-box table{
            width:100%;
            line-height:inherit;
            text-align:left;
        }

        .invoice-box table td{
            padding:5px;
            vertical-align:top;
        }

        .invoice-box table tr td:nth-child(2){
            text-align:right;
        }

        .invoice-box table tr.top table td{
            padding-bottom:20px;
        }

        .invoice-box table tr.top table td.title{
            font-size:45px;
            line-height:45px;
            color:#333;
        }

        .invoice-box table tr.information table td{
            padding-bottom:40px;
        }

        .invoice-box table tr.heading td{
            background:#eee;
            border-bottom:1px solid #ddd;
            font-weight:bold;
        }

        .invoice-box table tr.details td{
            padding-bottom:20px;
        }

        .invoice-box table tr.item td{
            border-bottom:1px solid #eee;
        }

        .invoice-box table tr.item.last td{
            border-bottom:none;
        }

        .invoice-box table tr.total td:nth-child(2){
            border-top:2px solid #eee;
            font-weight:bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td{
                width:100%;
                display:block;
                text-align:center;
            }

            .invoice-box table tr.information table td{
                width:100%;
                display:block;
                text-align:center;
            }
        }
    </style>
</head>

<body>
<div class=\"invoice-box\">
    <table cellpadding=\"0\" cellspacing=\"0\">
        <tr class=\"top\">
            <td colspan=\"2\">
                <table>
                    <tr>
                        <td class=\"title\">
                            <img src=\"$logoImg\" style=\"width:100%; max-width:300px;\">
                        </td>

                        <td>
                            Invoice #: $invoiceId<br>
                            Created: $creation<br>
                            Partner Name: $partnerName<br>
                            Partner Mobile: $partnerMobile<br>
                            BlueTeam Partner ID: $partnerId
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class=\"information\">
            <td colspan=\"2\">
                <table>
                    <tr>
                        <td>
                            $partnerAddress
                        </td>

                        <td>
                            $customerName<br>
                            $customerMobile<br>
                            $customerEmail
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class=\"heading\">
            <td>
                Payment Method
            </td>

            <td>
                Cash #
            </td>
        </tr>

        <tr class=\"details\">
            <td>
                Cash
            </td>

            <td>
                in hand
            </td>
        </tr>

        <tr class=\"heading\">
            <td>
                Service
            </td>

            <td>
                Price
            </td>
        </tr>

        <tr class=\"item\">
            <td>
                $serviceName
            </td>

            <td>
                $servicePrice
            </td>
        </tr>

        <tr class=\"item last\">
            <td>
                service tax
            </td>

            <td>
                $serviceTax
            </td>
        </tr>



        <tr class=\"total\">
            <td></td>

            <td>
                Total: $totalAmount
            </td>
        </tr>

        <tr class=\"heading\">
            <td>
                Technology Partner
            </td>

            <td>
                <a href=\"http://blueteam.in\"> www.blueteam.in</a><br/>
                For more services <a href=\"http://blueteam.in/app/\">Download App</a><br/>
                Shatkon Labs Pvt. Ltd
            </td>
        </tr>
    </table>
</div>
</body>
</html>";
}

$fist = explode("?", $_SERVER['REQUEST_URI']);
$route = explode("/", $fist[0]);
//var_dump($route);die();

$id = $route[1];

$dbHandle = mysqli_connect("localhost","root","redhat@11111p","blueteam_service_providers");

$fbRequest = mysqli_query($dbHandle, "SELECT a.service_provider_id, a.creation, a.customer_email, a.customer_name, a.customer_mobile, a.amount,
c.name as service_name, a.service_tax, b.name as service_provider_name, b.address, b.mobile_no as partner_mobile
                        FROM `invoice` as a INNER JOIN service_providers as b INNER JOIN  services as c
                        WHERE a.id = '$id'  and a.service_provider_id = b.id and a.service_id = c.id;");
$count = mysqli_num_rows($fbRequest);
if($count == 0) {
    header('Location: http://blueteam.in/app/');
    die();
}
$fbRequestData = mysqli_fetch_array($fbRequest);

$customerName = $fbRequestData['customer_name'];

if(isset($_POST['reliability'])){
    $email = $_POST['email'];

    $logoImg = "http://api.file-dog.shatkonlabs.com/files/rahul/".($photos['photo_id']== 0)?1075:$photos['photo_id'];

    $servicePrice = ($fbRequestData['service_tax'] == "yes")?$fbRequestData['amount']*0.845:$fbRequestData['amount'];
    $serviceTax = ($fbRequestData['service_tax'] == "yes")?$fbRequestData['amount']*0.155:0;

    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`invoice` SET `customer_email` = '$email' WHERE `invoice`.`id` =$id;");


    $reliablityEnum = array('Yes' => 4, 'Nearly' => 3, 'Too late' => 1);

    $score = $reliablityEnum[$_POST['reliability']];
    mysqli_query($dbHandle, "UPDATE `blueteam_service_providers`.`service_providers`
                SET `reliability_score` = `reliability_score` + $score, `reliability_count` = `reliability_count` + 1, `sms_credit` = `sms_credit` + 5, `email_credit` = `email_credit` + 10
                          WHERE `service_providers`.`id` =".$fbRequestData['service_provider_id'].";");

    $message = "Thanks for using service\nYou have paid $invoice->amount including tax\nget bill on email at http://b.blueteam.in/".$invoice->id;

    $message = getInvoiceHtml($fbRequestData['service_name'], $servicePrice, $fbRequestData['amount'], $serviceTax,
        $fbRequestData['service_provider_name'],
        $fbRequestData['service_provider_id'],
        $fbRequestData['address'], $fbRequestData['partner_mobile'],$customerName, $email, $fbRequestData['customer_mobile'],
        $logoImg, $invoice->id, $fbRequestData['creation']);
    sendMail($email, "Invoice for ".$fbRequestData['service_name']." of Rs "
        .$fbRequestData['amount'].", by ".$fbRequestData['service_provider_name'].". Power by blueteam.in", $message);

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