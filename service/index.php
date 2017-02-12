<?php

session_start();
include_once 'ajax/functions.php';
$url = explode("-",$_GET['load']);
$serviceName = $url[0];
$serviceId = $url[1];
$userId = 1;
$location = $_GET['l'];
$service = mysqli_query($dbHandle, "SELECT * FROM services WHERE id = '$serviceId' ;");
$serviceData = mysqli_fetch_array($service);
$objectId = 'bt-sp-'.$serviceId;
if($serviceData['pic_id']== 0) $pic = 1075;
else $pic = $serviceData['pic_id'] ;
if($serviceData['service_img']== 0) $img = 1075;
else $img = $serviceData['service_img'] ;
$profilePic = "http://api.file-dog.shatkonlabs.com/files/rahul/".$pic;
$serviceImg = "http://api.file-dog.shatkonlabs.com/files/rahul/".$img;
/*
 * SET @p = POINTFROMTEXT('POINT(28.4594965 77.0266383)');

SELECT  *
FROM service_providers where CalculateDistanceKm(X(@p), Y(@p), X(gps_location), Y(gps_location)) < 1 ;
 *
 * */
$photosArray = mysqli_query($dbHandle, "SELECT photo_id FROM photos WHERE service_provider_id IN
										(SELECT service_provider_id FROM service_provider_service_mapping
										 WHERE service_id = '$serviceId') ;");

$url = "http://api.sp.blueteam.in/service/".$serviceId."?location=".$_GET['l'];
$allServiceProviders = json_decode(httpGet($url))->service_providers;
$lookUpId = json_decode(httpGet($url))->lookup_id;
/*
$allServiceProviders = mysqli_query($dbHandle, "SELECT a.name, a.organization, a.id, a.profile_pic_id,
											b.price, b.negotiable, b.hourly FROM service_providers AS a
											JOIN service_provider_service_mapping AS b WHERE
											a.id = b.service_provider_id AND b.service_id = '$serviceId' ;");*/

$recommendedServices = mysqli_query($dbHandle, "SELECT a.price,a.negotiable,a.hourly,b.name,b.pic_id,
											b.description FROM service_provider_service_mapping AS a
                                            JOIN services AS b WHERE a.service_id = b.id
                                            AND b.status = 'active' ORDER BY RAND() LIMIT 6;");
$locationDetails = json_decode(httpGet("http://api.sp.blueteam.in/location/".$_GET['l']), true)[location_details];
$areaName = str_replace('-',', ',$locationDetails[area]['name']);
$cityName = str_replace('-',', ',$locationDetails[city]['name']);
$metaData = $serviceData['name'].", ".$serviceData['description'].", ".$areaName.", ".$cityName ;
$metaDescription = implode(',', array_keys(extractCommonWords($metaData)));

?>

<!DOCTYPE html>
<!--[if IE 9]> <html class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <!-- for Google -->
    <meta name="description" content="<?=$metaData; ?>" />
    <meta name="keywords" content="<?=$metaDescription; ?>" />
    <meta name="author" content="BlueTeam" />
    <meta name="copyright" content="true" />
    <meta name="application-name" content="website" />

    <!-- for Facebook -->
    <meta property="og:title" content="<?php echo $serviceName.", ".$areaName.", ".$cityName ;?>" />
    <meta name="og:author" content="BlueTeam" />
    <meta property="og:type" content="website"/>

    <meta name="p:domain_verify" content=""/>
    <meta property="og:image" content='<?= $serviceImg ; ?>' />
    <meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
    <meta property="og:image:type" content="image/jpeg" />

    <meta property="og:description" content="<?=$metaDescription; ?>" />

    <!-- for Twitter -->
    <!-- <meta name="twitter:card" content="n/a" /> -->
    <meta name="twitter:site" content="@hireblueteam">
    <meta name="twitter:creator" content="@hireblueteam">
    <meta name="twitter:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
    <meta name="twitter:title" content="<?php echo $serviceName.", ".$areaName.", ".$cityName ;?>" />
    <meta name="twitter:description" content="<?=$metaDescription; ?>" />
    <meta name="twitter:image" content="<?= $serviceImg ; ?>" />

    <link href="" type="image/png" rel="shortcut icon">
    <link href="" type="image/png" rel="apple-touch-icon">
    <title><?php echo $serviceName.", ".$areaName.", ".$cityName ;?></title>
    <link rel="icon" type="image/png"  href="../favicon.ico">
    <style type="text/css">
        #map{
            min-height: 500px;
        }
    </style>


    <!--[if IE]> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="//blueteam.in/static/css/fonts.css">
    <link rel="stylesheet" href="//blueteam.in/static/css/bootstrap.min.css">
    <!--  <link rel="stylesheet" href="//blueteam.in/static/css/font-awesome.min.css">
   -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="//blueteam.in/static/css/animate.css">
    <link rel="stylesheet" href="//blueteam.in/static/css/revslider2.css">
    <link rel="stylesheet" href="//blueteam.in/static/css/style.css">
    <link rel="stylesheet" href="//blueteam.in/static/css/responsive.css">
    <link rel="stylesheet" href="//blueteam.in/static/css/dedicated_page-afeb09052819dd920d48a269a058338d.css" type="text/css" media="screen">
    <link rel="stylesheet" href="http://blueteam.in/service_provider/index_files/bootstrap-datetimepicker.min.css" type="text/css">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="//blueteam.in/static/images/fevicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="//blueteam.in/static/images/fevicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="//blueteam.in/static/images/fevicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="//blueteam.in/static/images/fevicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="//blueteam.in/static/images/fevicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="//blueteam.in/static/images/fevicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="//blueteam.in/static/images/fevicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="//blueteam.in/static/images/fevicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="//blueteam.in/static/images/fevicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="//blueteam.in/static/images/fevicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="//blueteam.in/static/images/fevicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="//blueteam.in/static/images/fevicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="//blueteam.in/static/images/fevicon/favicon-16x16.png">
    <link rel="manifest" href="//blueteam.in/static/images/fevicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="//blueteam.in/static/images/fevicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!--- jQuery -->
    <script src="//blueteam.in/static/js/jquery.min.js"></script>

    <!-- Queryloader -->
    <script src="//blueteam.in/static/js/queryloader2.min.js"></script>

    <!-- Modernizr -->
    <script src="//blueteam.in/static/js/modernizr.js"></script>

    <style>
        body,
        html,
        #map {

        }

        #map .centerMarker {
            position: absolute;
            /*url of the marker*/
            background: url(http://maps.gstatic.com/mapfiles/markers2/marker.png) no-repeat;
            /*center the marker*/
            top: 50%;
            left: 50%;
            z-index: 1;
            /*fix offset when needed*/
            margin-left: -10px;
            margin-top: -34px;
            /*size of the image*/
            height: 34px;
            width: 20px;
            cursor: pointer;
        }

    </style>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */

        /* Optional: Makes the sample page fill the window. */

        .controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 300px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        .pac-container {
            font-family: Roboto;
        }

        #type-selector {
            color: #fff;
            background-color: #4d90fe;
            padding: 5px 11px 0px 11px;
        }

        #type-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }
        #target {
            width: 345px;
        }
        .side_bar_text {
            cursor: pointer;
            height: 160px;
            margin-top: 250px;
            position: fixed;
            width: 41px;
            z-index: 99;
            margin-left: 98%;
        }
        .left_side_popup {
            float: none;
            left: -119px;
            position: absolute;
            top: 0;
            width: 210px;
        }
        .left_side_popup > a {
            background: royalblue none repeat scroll 0 0;
            border-radius: 3px;
            color: #fff;
            display: block;
            font-weight: 600;
            margin-top: 0;
            padding: 10px;
            position: relative;
            text-decoration: none;
            transform: rotate(90deg);
        }
    </style>


    <!-- <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" id="st_insights_js"
            src="//w.sharethis.com/button/buttons.js?publisher=2b116127-b5f0-4211-8a7a-0870727e907d"></script>
    <script type="text/javascript" src="//s.sharethis.com/loader.js"></script>
    <script type="text/javascript">
        stLight.options({publisher: "2b116127-b5f0-4211-8a7a-0870727e907d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script> -->

</head>
<body data-spy="scroll" data-target="#main-menu">
<div class="geass-loader-overlay left"></div><!-- End .geass-loader-overlay left -->
<div class="geass-loader-overlay right"></div><!-- End .geass-loader-overlay right -->
<div id="wrapper">


    <!-- Header / Menu Section -->
    <header id="header" class="transparent">
        <nav class="navbar navbar-default navbar-transparent" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--                             <a class="navbar-brand navbar-brand-img" href="">
                                                    <img src="//blueteam.in/static/images/logo.png" class="img-responsive logo-white" alt="BlueTeam">
                                                    <img src="//blueteam.in/static/images/logo.png" class="img-responsive logo-fixed" alt="BlueTeam">
                                                    <span class="logo">
                                                        Blue Team
                                                    </span>
                                                </a> -->

                    <a href="http://blueteam.in/home" class="navbar-brand" style="padding-top: 0px;"><span class="logo" style="color: #ff2e8a06;">
                            <img src="//blueteam.in/static/images/logo.png" style="height: 68px; width: 68px;"></span></a>
                </div>



                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-right" id="main-menu">
                    <ul class="nav navbar-nav">
                        <li >
                            <div id="header-search-bar" class="form-group input-group" style="margin-top: 12px;" >
                                <input type="text" id="search_box1" style="vertical-align: middle;color: #000;margin: 2px;" class="form-control input-sm" >
                                        <span class="input-group-btn">
                                            <button id="search1" class="btn btn-lightblue btn-sm input-sm" onclick="search();">
                                                <i class="fa fa-search"></i>
                                            </button>

                                        </span>
                            </div>

                        </li>
                        <li ><a href="http://blueteam.in/aboutus">About Us</a></li>
                        <li ><a href="http://blueteam.in/terms&Conditions">T&Cs</a></li>
                        <li ><a href="http://blueteam.in/blueteamVerified">BlueTeam Verification</a></li>
                        <li ><a href="" >
                                <i class="fa fa-phone" ></i> or
                                <i class="fa fa-whatsapp" >
                                    95990 75355</i> </a></li>
                        <li ><a href="//goo.gl/EGxeu3" target="_blank">
                                <span class="fa fa-android"></span></a></li>
                        <li ><a href="//goo.gl/Ko19Gq" target="_blank"><span class="fa fa-apple"></span></a></li>
                        <li ><a href="//goo.gl/Ko19Gq" target="_blank"><span class="fa fa-windows"></span></a></li>

                    </ul>
                </div><!-- /.navbar-collapse -->
                <div class="row">
                    <div  class="col-md-3"></div>




                </div>

                <div class="row">
                    <div id="search-results" >

                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
    </header>
    <!-- <div style="margin-top: 300px;margin-left:95%;z-index: 99;background-color: skyblue;position: relative;width: auto;">
        <div style="position: absolute;">
            <p class="partner" >Partner Log In / Sign Up</p>
        </div>
    </div> -->
    <div class="side_bar_text">
        <div class="left_side_popup">
            <a href="http://bt-partner.blueteam.in"> Partner Log In / Sign Up </a>
        </div>
    </div>

    <!-- main Section -->
    <!-- <section id="home" class="section gfullscreen">
        <div id="revslider-container">
            <div id="revslider">
                <ul>
                    <li data-transition="fadefromtop" data-slotamount="8" data-masterspeed="400" data-thumb="<?/*= $this-> baseUrl */?>//blueteam.in/static/images/homeslider/mobilejob.jpg" data-saveperformance="on"  data-title="New Layouts">
                        <img src="<?/*= $this-> baseUrl */?>//blueteam.in/static/images/revslider/dummy.png"  alt="slidebg1" data-lazyload="https://thestartupgarage.com/wp-content/uploads/2011/10/the-startup-garage-entrepreneurship-networking-5-professionals.jpg" data-bgposition="center center" data-duration="4800" data-bgfit="cover">

                        <div class="tp-caption rev-subtitle bigger fancy customin customout"
                             data-x="center"
                             data-y="140"
                             data-customin="x:0;y:0;z:0;rotationX:-90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-speed="1100"
                             data-start="900"
                             data-easing="Power3.easeInOut"
                             data-endspeed="600"
                             style="z-index: 10">Welcome To <span class="blue-color">BlueTeam</span>
                        </div>

                        <div class="tp-caption rev-title bigger customin customout"
                             data-x="center"
                             data-y="220"
                             data-speed="1100"
                             data-customin="x:0;y:0;z:0;rotationX:-90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-start="1500"
                             data-easing="Power3.easeInOut"
                             data-endspeed="600"
                             style="z-index: 6">
                            <div class="form-group input-group" style="width: 246px;">
                                <input type="text" placeholder="Type service name your are looking for. Eg: 'yogo'" id="search_box" style="vertical-align: middle;color: #000;min-width: 400px;
	       		margin: 2px;" class="form-control input-lg" >
                                <span class="input-group-btn">
                                    <button id="search" class="btn btn-lightblue btn-lg input-lg" onclick="search();">
                                        <i class="fa fa-search"></i>
                                    </button>

                                </span>
                        </div>


                        <div class="tp-caption rev-text customin customout"
                             data-x="center"
                             data-y="355"
                             data-speed ="1200"
                             data-customin="x:0;y:0;z:0;rotationX:-90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-start="1900"
                             data-easing="Power3.easeInOut"
                             data-endspeed="600"
                             style="z-index: 12">Awesome Hiring Services, Hire maid, cook, baby sitter, electrician, plumber, security guard, driver, gardener at Affordable price
                        </div>

                        <div class="tp-caption customin customout"
                             data-x="center"
                             data-y="460"
                             data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-speed="1200"
                             data-start="2500"
                             data-easing="Power3.easeInOut"
                             data-endspeed="600"
                             style="z-index: 14">
                            <a href="#hire" class="btn btn-lightblue" title="Hire Now">
                                Hire Now</a>
                        </div>

                    </li>
                </ul>
            </div><!-- End revslider -->
</div><!-- End revslider-container -->
</section><!-- End #home -->
<?php /*
<section id="home" class="section gfullscreen"  >
    <div id="our-services" style="margin-top:100px"  >
        <div class="container"  >
            <div class="row" >
                <div class="col-lg-8 col-md-12 col-sm-12 col-lg-offset-2 col-md-offset-0">

                    <center>
                        <h2 class=" fancy">Finding Best for You</h2></center>
                    <div class="form-group input-group" >
                        <input type="text" placeholder="Type service name your are looking for. Eg: 'yoga'" id="search_box" style="color: #000;margin: 2px;" class="form-control input-lg" >
                        <span class="input-group-btn">
                            <button id="search" class="btn btn-lightblue btn-lg input-lg" onclick="showMap('search',0)">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div><br/>
                    <center>
                        <a href="" style="font-size: 42px;">
                            <i class="fa fa-phone" ></i> or
                            <i class="fa fa-whatsapp" > 95990 75355</i>
                        </a><br/><br/>
                    </center>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 ">


                    <center><h3 class=" fancy">One Touch Services</h3></center>

                    <div id="portfolio-wrapper " class="center-block">

                        <ul id="portfolio-item-container" class="clearfix" data-maxcolumn="8" data-animationclass="fadeInUpBig">
                            <li class="portfolio-item animate-item photography" data-animate-time="80">
                                <a onclick="showMap('Maid',0)">
                                    <div class="portfolio-item-wrapper shortcut" id="Maid">
                                        <img class="service-request-image" src="//blueteam.in/static/images/maid.jpeg" alt="Maid" style="height: 70px">
                                        <Br/>Maid
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>

                            <li class="portfolio-item animate-item design illustration video" data-animate-time="80">
                                <a onclick="showMap('Cook',0)">
                                    <div class="portfolio-item-wrapper  shortcut" id="Cook">
                                        <img  class="service-request-image" src="//blueteam.in/static/images/cook.jpeg" alt="Cook" style="height: 70px">
                                        <br/>Cook
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>
                            <li class="portfolio-item animate-item illustration design" data-animate-time="160">
                                <a onclick="showMap('Babysitter',0)">
                                    <div class="portfolio-item-wrapper shortcut" id="Baby Sitter">
                                        <img class="service-request-image" src="//blueteam.in/static/images/babysitter.jpeg" alt="Baby Sitter" style="height: 70px">
                                        <br/>Babysitter
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>
                            <li class="portfolio-item animate-item photography illustration" data-animate-time="160">
                                <a onclick="showMap('Drive',0)">
                                    <div class="portfolio-item-wrapper shortcut" id="Driver">
                                        <img  class="service-request-image" src="//blueteam.in/static/images/driver.png" alt="Driver" style="height: 70px">
                                        <br/>Driver
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>
                            <li class="portfolio-item animate-item photography" data-animate-time="400">
                                <a onclick="showMap('Maid Cum Babysitter',0)">
                                    <div class="portfolio-item-wrapper shortcut" id="Maid">
                                        <img class="service-request-image" src="//blueteam.in/static/images/babysitter.jpeg" alt="Baby Sitter" style="height: 70px">
                                        <Br/>Maid Cum Babysitter
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>

                            <li class="portfolio-item animate-item design illustration video" data-animate-time="560">
                                <a onclick="showMap('Nurse',0)">
                                    <div class="portfolio-item-wrapper  shortcut" id="Nurse">

                                        <img  class="service-request-image" src="//blueteam.in/static/images/patientcare.png" alt="Patient Care" style="height: 70px">
                                        <br/>Patient Care
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>
                            <li class="portfolio-item animate-item design illustration video" data-animate-time="400">
                                <a onclick="showMap('Maid Cum Cook',0)">
                                    <div class="portfolio-item-wrapper  shortcut" id="Cook">

                                        <img  class="service-request-image" src="//blueteam.in/static/images/cook.jpeg" alt="Maid Cum Cook" style="height: 70px">
                                        <br/>Maid Cum Cook
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>
                            <li class="portfolio-item animate-item design illustration video" data-animate-time="560">
                                <a onclick="showMap('Nurse',0)">
                                    <div class="portfolio-item-wrapper  shortcut" id="Nurse">

                                        <img  class="service-request-image" src="//blueteam.in/static/images/patientcare.png" alt="Elder Care" style="height: 70px">
                                        <br/>Elder Care
                                    </div><!-- End .portfolio-item-wrapper -->
                                </a>
                            </li>
                        </ul><!-- End #portfolio-item-container -->
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 cal-sm-12">
                    <ul class="list-inline">
                        <li style='width:32%;padding:10px;' ><a href="#hire0"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/978"/ width='100px'>
                                    Health and WellNess
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire1"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/979"/ width='100px'>
                                    Repair and Maintenance
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire2"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/980"/ width='100px'>
                                    Wedding and Events
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire3"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/981"/ width='100px'>
                                    Home Care and Design
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire4"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/982"/ width='100px'>
                                    Academic Tutors
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire5"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/983"/ width='100px'>
                                    Hobbies and Interest
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire6"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/984"/ width='100px'>
                                    Personal Services
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire7"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/985"/ width='100px'>
                                    Business Services
                                </p></a></li><li style='width:32%;padding:10px;' ><a href="#hire8"><p>
                                    <img src="http://api.file-dog.shatkonlabs.com/files/rahul/1205"/ width='100px'>
                                    Other Services
                                </p></a></li>                    </ul>
                </div>
                <div class="col-lg-12 col-md-12 cal-sm-12">
                    <center><h3 class="fancy">Why to Choose BlueTeam ?</h3></center>

                    <ul class="list-inline">
                        <li style='width:32%;padding:10px;' >
                            <p><center>
                                <i class="fa fa-check-square-o fa-2x"></i><br/>
                                <span style="font-size: 20px;font-weight: bold;">Verified Professionals</span><!-- <br/>
                            <span style="font-size: 14px;">Our Professionals are thoroughly verified and background checked</span> -->
                            </center></p>
                        </li>
                        <li style='width:32%;padding:10px;' >
                            <p><center><i class="fa fa-binoculars fa-2x"></i><br/>
                                <span style="font-size: 20px;font-weight: bold;">Choices of Service Partner</span><!-- <br/>
                            <span style="font-size: 14px;">Choose suitable for you</span> -->
                            </center></p>
                        </li>
                        <li style='width:32%;padding:10px;' >
                            <p><center><i class="fa fa-certificate fa-2x"></i><br/>
                                <span style="font-size: 20px;font-weight: bold;">Reliability and Quality Score</span><!-- <br/>
                            <span style="font-size: 14px;">Compare Reliability and Quality Score of different Professional</span> -->
                            </center></p>
                        </li>
                        <li style='width:32%;padding:10px;' >
                            <p><center><i class="fa fa-cog fa-2x"></i><br/>
                                <span style="font-size: 20px;font-weight: bold;">Skilled & Certified Professionals</span><!-- <br/>
                            <span style="font-size: 14px;">All our Professionals are well trained, skilled and experienced</span> -->
                            </center></p>
                        </li>
                        <li style='width:32%;padding:10px;' >
                            <p><center><i class="fa fa-clock-o fa-2x"></i><br/>
                                <span style="font-size: 20px;font-weight: bold;">On Time Services</span><!-- <br/>
                            <span style="font-size: 14px;">We assure timely services, delivery and completion to our customers.</span> -->
                            </center></p>
                        </li>
                        <li style='width:32%;padding:10px;' >
                            <p><center><i class="fa fa-hand-pointer-o fa-2x"></i><br/>
                                <span style="font-size: 20px;font-weight: bold;">Any Time Booking</span><!-- <br/>
                            <span style="font-size: 14px;">You can book services 24x7</span> -->
                            </center></p>
                        </li>
                    </ul>
                </div>

            </div>


        </div><!-- Ênd .container -->
        <!-- <div class="sm-margin"></div><! space -->

        <!--  <div class="container text-center">
             <a href="index14.html#" class="btn btn-lg btn-yellow wow tada">More Services</a>
         </div> -->
    </div><!-- End #our-services -->
</section>
 */ ?>
<div id="bookNow" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Book Now</h4>
            </div>
            <div class="modal-body">
                <label>Name</label>
                <input id="bookName" type="text" style="margin-left: 196px;" placeholder="Type your Name" ><br/><br/>
                <label>Mobile Number</label>
                <input id="bookMobile" type="text" style="margin-left: 120px;" placeholder="Type your mobile number" ><br/><br/>
                <label>Address</label>
                <input id="bookAddress" type="text" style="margin-left: 180px;" placeholder="Type your Address" ><br/><br/>
                <label>Remarks</label>
                <input id="remarks" type="text" style="margin-left: 175px;" placeholder="Remark" ><br/><br/>
                <input id="bookServiceProviderId" type="hidden" value="">
                <input id="bookServiceId" type="hidden" value="">
                <label>Starting Date & Time</label>
                <input id="startDate" style="margin-left: 80px;" placeholder="Enter Starting Date and time" > <br/><br/>
                <label>Number of Hours to Work</label>
                <select id= "totalHour" style="margin-left: 40px;" >
                    <option value='1' selected >1</option>
                    <option value=2 >2</option><option value=3 >3</option><option value=4 >4</option><option value=5 >5</option><option value=6 >6</option><option value=7 >7</option><option value=8 >8</option><option value=9 >9</option><option value=10 >10</option><option value=11 >11</option><option value=12 >12</option>                </select><br/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id="bookService" class="btn btn-info" onclick="bookNow();">Book</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Help us to locate you!</h4>
            </div>
            <div class="modal-body">
                <input id="pac-input" class="controls" type="text" placeholder="Search Box">

                <div id="map"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="loadServicePage()">Take Location</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function(){
        //$("#map").hide();
        $("#map-button").click(function(){
            $("#map").toggle(1000);
        });
    });
    // This example adds a search box to a map, using the Google Place Autocomplete
    // feature. People can enter geographical searches. The search box will return a
    // pick list containing a mix of places and predicted search terms.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initAutocomplete1() {
        if (navigator.geolocation) {
            console.log("got location");
            navigator.geolocation.getCurrentPosition(initAutocomplete1);
        } else {
            console.log("failed to get coordinate");
            initAutocomplete1(position);
            //x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function book(serviceProviderId, serviceId) {
        $("#bookServiceProviderId").val(serviceProviderId);
        $("#bookServiceId").val(serviceId);
        $("#bookNow").modal("show");
    }
    String.prototype.isValidDate = function() {
        var IsoDateRe = new RegExp("^([0-9]{4})-([0-9]{2})-([0-9]{2})$");
        var matches = IsoDateRe.exec(this);
        if (!matches) return false;
        else return true ;
    }
    function bookNow(){
        $("#bookService").attr('disabled','disabled');
        var bookName = replaceAll('\\s', '', $("#bookName").val());
        var bookMobile = replaceAll('\\s', '', $("#bookMobile").val());
        var bookAddress = replaceAll('\\s', '', $("#bookAddress").val());
        var remarks = replaceAll('\\s', '', $("#remarks").val());
        var serviceProviderId = $("#bookServiceProviderId").val();
        var serviceId = $("#bookServiceId").val();
        var startDate = replaceAll('\\s', '', $("#startDate").val());
        var timeData = startDate.split(" |:");
        var startTimeData = timeData[4];
        var startTime = parseInt(startTimeData);
        var totalHour = parseInt($("#totalHour").val());
        if(parseInt(startTime+totalHour) > 20) var endtime = '20:00:00';
        else var endtime = (startTime+totalHour)+':00:00';
        var serviceType = $("#serviceType").val();
        if(bookName.length < 3){
            alert('Please Enter Valid Name');
            $("#bookService").removeAttr('disabled');
            return false;
        }
        else if(!validatePhone(bookMobile)){
            alert("Please enter valid mobile number");
            $("#bookService").removeAttr('disabled');
            return false;
        }
        else if(bookAddress.length < 10){
            alert("Please enter valid Address");
            $("#bookService").removeAttr('disabled');
            return false;
        }
        /*else if(!(startDate.isValidDate())){
         alert('Enter valid date');
         $("#bookService").removeAttr('disabled');
         return false;
         }*/
        else {
            var startDatetime = startDate+":00";
            var startHour = startTime+":00:00";
            $.ajax({
                url: 'https://blueteam.in/api/service_request',
                type: 'post',
                dataType: 'json',
                data: '{"root": {"name":"'+bookName+'","mobile":"'+bookMobile+'","location":"",'+
                '"requirements":"'+serviceName+'","user_id": "1","user_type":"customer",'+
                '"start_datatime": "'+startDatetime+'","service_type": "monthly",'+
                '"remarks": "'+remarks+' by bt_sp web page","start_time":"'+startHour+'",'+
                '"end_time":"'+endtime+'","location":"'+lat+","+lng+'","address":"'+bookAddress+'","priority": "3",'+
                '"service_provider_id":"'+serviceProviderId+'"}}',
                success: function (feedback) {

                    alert("Your request has been send.\n We will connect with you soon.");
                    console.log(feedback);
                    $("#bookName").val("");
                    $("#bookMobile").val("");
                    $("#bookAddress").val("");
                    $("#remarks").val("");

                }
            });
        }
        $("#bookService").removeAttr('disabled');
        $("#bookNow").modal("hide");
    }



    function showMap(serviceN,serviceI) {
        serviceName = serviceN;
        serviceId = serviceI;

        $('#myModal').modal('toggle');
        setTimeout(initAutocomplete, 2000);

    }

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
            function(m,key,value) {
                vars[key] = value;
            });
        return vars;
    }

    function loadServicePage(){
        if(serviceName == 'search')
            search();
        else
        if(serviceId == 0){
            $('#myModal').modal('toggle');

            $('#bookNow').modal('toggle');
        }
        else
            var serviceData = getUrlVars()["load"];
        window.location.href = 'http://blueteam.in/service/index.php?load='+serviceData+'&l='+lat+','+lng;

    }


    var lat = 28.4595;
    var lng = 77.0266;
    var serviceName = null;
    var serviceId = null;

    function initAutocomplete() {

        var map = new google.maps.Map(document.getElementById('map'), {
            center:  {lat:lat,lng:lng},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        google.maps.event.addListener(map,'center_changed', function() {
            $.get( "http://api.sp.blueteam.in/location/"+map.getCenter().lat()+","+map.getCenter().lng(), function( data ) {
                //alert( "Data Loaded: " + data );
            });
            lat = map.getCenter().lat();
            lng = map.getCenter().lng();
        });



        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                /*markers.push(new google.maps.Marker({
                 map: map,
                 icon: icon,
                 title: place.name,
                 position: place.geometry.location
                 }));*/

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);

        });
        $('<div/>').addClass('centerMarker').appendTo(map.getDiv())
            //do something onclick
            .click(function() {
                var that = $(this);
                if (!that.data('win')) {
                    that.data('win', new google.maps.InfoWindow({
                        content: 'So, you are at this location!'
                    }));
                    that.data('win').bindTo('position', map, 'center');
                }
                that.data('win').open(map);
            });
    }

    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        //console.log(scroll);
        if(scroll < 20){
            $("#header-search-bar").hide();
        }else
            $("#header-search-bar").show();

    });


    function replaceAll(find, replace, str) {
        if(str == null) {
            str = "";
        }
        return str.replace(new RegExp(find, 'g'), replace);
    }
    function search() {
        $("#search").attr('disabled','disabled');
        $("#search1").attr('disabled','disabled');
        var keywords = replaceAll('\\s', '', $('#search_box').val());
        if(keywords.length <= 0)
            keywords = replaceAll('\\s', '', $('#search_box1').val());
        if(keywords.length < 3){
            alert ('Minimum words length is 3');
        }
        else {
            $.ajax({
                type: "POST",
                url: "http://blueteam.in/service/ajax/search.php?"+'l='+lat+','+lng,
                data: 'keywords='+ keywords,
                cache: false,
                success: function(result){
                    document.getElementById("search-results").innerHTML = result ;
                }
            });
            //$('#search_box').val("");
        }
        $("#search").removeAttr('disabled');
        $("#search1").removeAttr('disabled');
        return false;
    }
</script>
<!-- Portfolio Section -->
<!-- id="portfolio" changed to home and  style="padding-top: 115px;" is added for home-->



<section id="hire0" class="section padding-bottom" style="  overflow-y: hidden ! important;
                                                            overflow-x: hidden ! important;
                                                        background-image: url('<?= $serviceImg ?>');
                                                        background-size:     cover;                      /* <------ */
                                                        background-repeat: no-repeat;
                                                            background-position: top;">
    <header class="container text-center">
        <div class="fancy box">
        <h3 ><img src="<?=$profilePic;?>" style='max-width: 50px;max-height: 50px; '><?=$serviceData['name']; ?></h3>
        <p >
           <?=$serviceData['description']; ?>
        </p>
        </div>

    </header>



    <div class="container">
        <div class='row'>


            <center>
                <?php
                foreach ($allServiceProviders as $serviceProvider ) {
                    if($serviceProvider->hourly =='yes') $perHour = "/Hr";
                    else $perHour ="";
                    if($serviceProvider->price=="") $price = 0;
                    else $price = $serviceProvider->price;
                    if($serviceProvider->profile_pic_id== 0) $img2 = 1075;
                    else $img2 = $serviceProvider->profile_pic_id ;
                    echo "
<div class=\"col-md-2 col-sm-4\">
                    <a href='../service_provider/index.php?load=".$serviceProvider->name."-".$serviceProvider->id."-gurgaon&s= ".$serviceName."-".$serviceId."&l=".$location."'  ><center>
                            <div class=\"service box lightblue wow fadeInUp\" data-wow-delay=\"0.75s\">
                                <div class=\"service-header\">
                                <span class=\"service-icon\" style='background-color: #fff'>
                                    <img class=\"service-request-image\" src='http://api.file-dog.shatkonlabs.com/files/rahul/".$img2."' alt=\"Maid\" style=\"width: 50px;height: 50px\">
                                </span>

                                </div>
                                <p><span style=\"font-size: 15px;font-weight: bold;\">".$serviceProvider->name."</span><br/>
                                <span style=\"font-size: 15px;color: gray;\">Quality: 89%<br/>
                                Reliability: 89%</span><br/>
                                Price: ".$price."<i class='fa fa-rupee'></i>".$perHour."<br/>
                                <button class='btn btn-info'>View Details</button><br/></p><br/>
                            </div>
                    </a>
                </div>

";
                }
                if(count($allServiceProviders) <= 0){
                    echo "<div class='service box lightblue wow fadeInUp'><span style='font-size:12px;'>Sorry! No Service Provider in this Area<br/>
							We have taken your request for this area.<br/>
							We are committed to add 3 service providers in this area in next 48 hrs.<br/>
							Process of adding service provider<br/>
							1. Enqueuing 10 service providers in the area<br/>
							2. Interviewing every service provider<br/>
							3. Shot listing <br/>
							4. Document Verification<br/>
							5. Profile Creation<br/>
							Give us chance to reach you, after process compilation<br/>
							Thank You <br/><br/></span>
							<input id='requestName' type='text' placeholder='Name'><br/>
		  					<input id='requestMobile' type='text' placeholder='Mobile'><br/><br/>
		  					<button type='button' id='addService' class='btn btn-info' onclick='request(\"".$lookUpId."\");'>Submit</button>
		  				</div>";

                }
                ?>




            </center></div>

</section>

<section id="hire1" class="section padding-bottom">
    <header class="container text-center">
        <h3 class="fancy box">Recommended Services</h3>

    </header>



    <div class="container">
        <div class='row'>


            <center>
                <?php
                while ($allRecommendedServices = mysqli_fetch_array($recommendedServices)) {

                    if($allRecommendedServices['pic_id']== 0) $img = 1075;
                    else $img = $allRecommendedServices['pic_id'] ;
                    echo "
                    <div class=\"col-md-2 col-sm-4\">
                    <a href='../service/index.php?load=".$allRecommendedServices['name']."-gurgaon&l=".$location."'  >
                        <center>
                            <div class=\"service box lightblue wow fadeInUp\" data-wow-delay=\"0.75s\">
                                <div class=\"service-header\">
                                <span class=\"service-icon\" style='background-color: #fff'>
                                    <img class=\"service-request-image\" src=\"http://api.file-dog.shatkonlabs.com/files/rahul/".$img."\" alt=\"Maid\" style=\"width: 50px;height: 50px\">
                                </span>

                                </div>
                                <p>".$allRecommendedServices['name']."</p><br/>
                            </div>
                        </center>
                    </a>
                </div>
";
                }
                ?>



            </center></div>

</section>

<!-- <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Launch demo modal
</button> -->





<!-- Services Section -->
<!-- Count Parallax Section -->
<div class="countto-container background-new parallax" data-stellar-background-ratio="0.15">
    <div class="overlaybg overlay-pattern1"></div><!-- End .overlaybg -->
    <div class="parallax-content">
        <div class="container">
            <div class="row">

                <div class="col-md-3 col-sm-6 col-xs-6 count-container">
                    <span class="count" data-from="0" data-to="1170" data-speed="3000" data-refresh-interval="50">0</span>
                    <h3 class="fancy">Requests<!--  <span>Projects</span> --></h3>
                </div><!-- End .count-container -->

                <div class="col-md-3 col-sm-6 col-xs-6 count-container">
                    <span class="count" data-from="0" data-to="769" data-speed="3000" data-refresh-interval="50">0</span>
                    <h3 class="fancy">Users<!--  <span>Customers</span> --></h3>
                </div><!-- End .count-container -->

                <div class="xlg-margin visible-sm visible-xs hidden-xss clearfix"></div><!-- space -->

                <div class="col-md-3 col-sm-6 col-xs-6 count-container">
                    <span class="count" data-from="0" data-to="77" data-speed="3000" data-refresh-interval="50">0</span>
                    <h3 class="fancy">Locations<!--  <span>Awards</span> --></h3>
                </div><!-- End .count-container -->

                <div class="col-md-3 col-sm-6 col-xs-6 count-container">
                    <span class="count" data-from="0" data-to="787" data-speed="3000" data-refresh-interval="50">0</span>
                    <h3 class="fancy">Workers<!--  <span>Days</span> --></h3>
                </div><!-- End .count-container -->

            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .parallax-content -->
</div><!-- End .countto-container -->


<!-- About Us Section -->

<!-- Contact Us Section -->
<footer id="footer" class="parallax" data-stellar-background-ratio="0.15">
    <div class="overlaybg overlay-pattern1"></div><!-- End .section-overlay -->
    <div class="section-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="#home" class="footer-logo" title="BlueTeam | Hire now"><img src="//blueteam.in/static/images/logo.png" width="210" alt="BlueTeam"></a>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5" style="height: 600px;overflow-y: scroll;">
                    <a class="twitter-timeline" href="https://twitter.com/Blueteam_In">Tweets by Blueteam_In</a>
                    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
                <div class="col-md-5">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fblueteam.in&tabs=timeline&width=500&height=600px&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true" width="100%" height="600px" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </div><!-- End .col-md-12 -->
            </div><!-- End .row -->
        </div><br/><!-- End .container -->

        <div class="footer-social-icons transparent">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="social-icons-container">
                            <li><a href="https://www.facebook.com/blueteam.in" class="facebook add-tooltip" data-placement="top" data-toggle="tooltip" target="_blank" title="Follow us on Facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/blueteam_in" class="twitter add-tooltip" data-placement="top" data-toggle="tooltip" target="_blank" title="Follow us on Twitter"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://plus.google.com/110262724836533344452" class="googleplus add-tooltip" data-placement="top" data-toggle="tooltip" target="_blank" title="Follow us on Google +"><i class="fa fa-google-plus"></i></a></li>
                            <!-- <li><a href="index14.html#" class="dribbble add-tooltip" data-placement="top" data-toggle="tooltip" title="Find us at Dribbble"><i class="fa fa-dribbble"></i></a></li>
                            <li><a href="index14.html#" class="tumblr add-tooltip" data-placement="top" data-toggle="tooltip" title="Find us at Tumblr"><i class="fa fa-tumblr"></i></a></li>
                            <li><a href="index14.html#" class="flickr add-tooltip" data-placement="top" data-toggle="tooltip" title="Find us at Flickr"><i class="fa fa-flickr"></i></a></li> -->
                        </ul>
                        <center><span class='st_facebook_large' displayText='Facebook'></span>
                            <span class='st_twitter_large' displayText='Tweet'></span>
                            <span class='st_whatsapp_large' displayText='WhatsApp'></span>
                            <span class='st_linkedin_large' displayText='LinkedIn'></span>
                            <span class='st_pinterest_large' displayText='Pinterest'></span>
                            <span class='st_googleplus_large' displayText='Google +'></span>
                            <span class='st_newsvine_large' displayText='Newsvine'></span></center>
                    </div><!-- End .col-md-12 -->
                </div><!-- End .row -->
            </div><!-- End .container -->

        </div><!-- End .footer-social-icons -->
        <div class="footer-social-icons transparent">
            <div class='container'>
                <div class='row'>
                    <div class='col-md-12'>
                        <ul class='list-inline' style='margin:5px;'><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=1' style='text-decoration: none;color: #fff;padding:2px;'>Ghaziabad</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=2' style='text-decoration: none;color: #fff;padding:2px;'>Faridabad</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=3' style='text-decoration: none;color: #fff;padding:2px;'>Gurugram</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4' style='text-decoration: none;color: #fff;padding:2px;'>Rohtak</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=5' style='text-decoration: none;color: #fff;padding:2px;'>Sonipat</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6' style='text-decoration: none;color: #fff;padding:2px;'>Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=7' style='text-decoration: none;color: #fff;padding:2px;'>Noida</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=8' style='text-decoration: none;color: #fff;padding:2px;'>Greater Noida</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=9' style='text-decoration: none;color: #fff;padding:2px;'>Bangalore</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=10' style='text-decoration: none;color: #fff;padding:2px;'>Pune</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=11' style='text-decoration: none;color: #fff;padding:2px;'>Mumbai</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=12' style='text-decoration: none;color: #fff;padding:2px;'>Chennai</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=13' style='text-decoration: none;color: #fff;padding:2px;'>Kolkata</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=14' style='text-decoration: none;color: #fff;padding:2px;'>Hyderabad</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=15' style='text-decoration: none;color: #fff;padding:2px;'>Ahmedabad</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=16' style='text-decoration: none;color: #fff;padding:2px;'>Chandigarh</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=17' style='text-decoration: none;color: #fff;padding:2px;'>Gurgaon</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=76' style='text-decoration: none;color: #fff;padding:2px;'>Kings County</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=77' style='text-decoration: none;color: #fff;padding:2px;'>Monmouth County</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=79' style='text-decoration: none;color: #fff;padding:2px;'>Sonepat</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=80' style='text-decoration: none;color: #fff;padding:2px;'>Hyderabad</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=82' style='text-decoration: none;color: #fff;padding:2px;'>Ranga Reddy</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=116' style='text-decoration: none;color: #fff;padding:2px;'>Nalgonda</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=165' style='text-decoration: none;color: #fff;padding:2px;'>Mahabubnagar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=170' style='text-decoration: none;color: #fff;padding:2px;'>Jhajjar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=171' style='text-decoration: none;color: #fff;padding:2px;'>Bhiwani</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=283' style='text-decoration: none;color: #fff;padding:2px;'>South West Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=292' style='text-decoration: none;color: #fff;padding:2px;'>New Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=294' style='text-decoration: none;color: #fff;padding:2px;'>South Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=296' style='text-decoration: none;color: #fff;padding:2px;'>South East Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=301' style='text-decoration: none;color: #fff;padding:2px;'>Gautam Buddh Nagar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=302' style='text-decoration: none;color: #fff;padding:2px;'>West Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=303' style='text-decoration: none;color: #fff;padding:2px;'>Bangalore Urban</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=447' style='text-decoration: none;color: #fff;padding:2px;'>Tumkur</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=448' style='text-decoration: none;color: #fff;padding:2px;'>Chitradurga</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=449' style='text-decoration: none;color: #fff;padding:2px;'>Belagavi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=452' style='text-decoration: none;color: #fff;padding:2px;'>North West Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=453' style='text-decoration: none;color: #fff;padding:2px;'>Central Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=729' style='text-decoration: none;color: #fff;padding:2px;'>Pilibhit</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=840' style='text-decoration: none;color: #fff;padding:2px;'>Bulandshahar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=853' style='text-decoration: none;color: #fff;padding:2px;'>Jamnagar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=888' style='text-decoration: none;color: #fff;padding:2px;'>Sahibzada Ajit Singh Nagar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=893' style='text-decoration: none;color: #fff;padding:2px;'>District of Columbia</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=900' style='text-decoration: none;color: #fff;padding:2px;'>Gurgaon</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=932' style='text-decoration: none;color: #fff;padding:2px;'>Krishnagiri</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=935' style='text-decoration: none;color: #fff;padding:2px;'>Dharmapuri</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4338' style='text-decoration: none;color: #fff;padding:2px;'>Mewat</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4523' style='text-decoration: none;color: #fff;padding:2px;'>Mumbai Suburban</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4532' style='text-decoration: none;color: #fff;padding:2px;'>Quận 1</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4533' style='text-decoration: none;color: #fff;padding:2px;'>Quận 5</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4534' style='text-decoration: none;color: #fff;padding:2px;'>Quận 2</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4561' style='text-decoration: none;color: #fff;padding:2px;'>Faridabad</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=4946' style='text-decoration: none;color: #fff;padding:2px;'>Jaipur</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=5052' style='text-decoration: none;color: #fff;padding:2px;'>Mahendragarh</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=5556' style='text-decoration: none;color: #fff;padding:2px;'>South East Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6377' style='text-decoration: none;color: #fff;padding:2px;'>Churu</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6378' style='text-decoration: none;color: #fff;padding:2px;'>Hanumangarh</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6379' style='text-decoration: none;color: #fff;padding:2px;'>Agra</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6380' style='text-decoration: none;color: #fff;padding:2px;'>Rajgarh</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6382' style='text-decoration: none;color: #fff;padding:2px;'>Jhalawar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=6745' style='text-decoration: none;color: #fff;padding:2px;'>Karnal</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=12475' style='text-decoration: none;color: #fff;padding:2px;'>Khordha</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=12478' style='text-decoration: none;color: #fff;padding:2px;'>Cuttack</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=14743' style='text-decoration: none;color: #fff;padding:2px;'>Lucknow</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37134' style='text-decoration: none;color: #fff;padding:2px;'>Tapi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37337' style='text-decoration: none;color: #fff;padding:2px;'>Gautam Buddha Nagar</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37360' style='text-decoration: none;color: #fff;padding:2px;'>North 24 Parganas</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37741' style='text-decoration: none;color: #fff;padding:2px;'>Vadodara</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37742' style='text-decoration: none;color: #fff;padding:2px;'>Anand</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37748' style='text-decoration: none;color: #fff;padding:2px;'>Thiruvananthapuram</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37901' style='text-decoration: none;color: #fff;padding:2px;'>Janjgir-Champa</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37902' style='text-decoration: none;color: #fff;padding:2px;'>Thrissur</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=37903' style='text-decoration: none;color: #fff;padding:2px;'>Kottayam</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=38437' style='text-decoration: none;color: #fff;padding:2px;'>East Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=38448' style='text-decoration: none;color: #fff;padding:2px;'>North Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=38466' style='text-decoration: none;color: #fff;padding:2px;'>North East Delhi</a></li><li style='min-width: 180px;padding:5px;margin:2px;'><a href='http://blueteam.in/directory/index.php?city=38494' style='text-decoration: none;color: #fff;padding:2px;'>Shahdara</a></li></ul>
                        <center>  <p>All rights reserved &copy; <a href='http://shatkonlabs.com' class='yellow-color' title='Shatkon Labs' target='_blank'>Shatkon Labs&trade;</a></p>
                            <span class='footer-date highlight red'>2014</span></center>
                    </div>
                </div>
            </div>        </div>
    </div><!-- End .section-content -->
</footer>

</div><!-- End #wrapper -->

<!-- Scroll Top Button -->
<!--<a href="#" id="scroll-top" class="add-tooltip" data-placement="top" title="Go to top"><i class="fa fa-angle-double-up"></i></a>
<div class="modal fade modal-styled" id="iframe" style="width:350px;margin-left: 35em;">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src="//blueteam.in/web-app/" width="350" height="550" frameborder="5px"></iframe>
        </div>
    </div>
</div>
--><!-- Service Request Modal -->
<div class="modal fade modal-styled" id="service_request">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header" >
                <button data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Contact Details</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="modal_body_form">

                <div class="account-wrapper">

                    <form class="form-group" onSubmit="return (validateServiceRequest());">
                        <div class="form-group">
                            <span>Your Name</span>
                            <input name="name" id="name" type="text" placeholder="Your Name" required class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <span>Your Email</span>
                            <input name="email" id="email" type="text" placeholder="Your Email" required class="form-control input-sm">
                            <span id="email_status"></span>
                        </div>
                        <div class="form-group">
                            <span>Enter 10 digit moblie number</span>
                            <input  required class="form-control input-sm"  placeholder="Enter 10 digit mobile number" id="mobile" type="text">
                            <span id="mobile_status"></span>
                        </div>
                        <div class="form-group">
                            <span>Needed From</span>
                            <input name="needed" id="needed" type="date" placeholder="YYYY-MM-DD" required class="form-control input-sm">
                            <i class="small" >Like   2016-02-22</i>
                            <span id="needed_status"></span><br/><br/>
                        </div>
                        <div class="form-group">
                            <span>Please choose Service Type </span>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-5">
                                    <input type="radio" name="service_type" value="1" onclick="showclass();"> Monthly
                                </div>
                                <div class="col-md-5">
                                    <input type="radio" name="service_type" value="0" onclick="hideclass();"> On - Demand
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <span>Worker timings</span>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="time" id ="timing" class="form-control input-sm" required placeholder="Enter Time" />
                                    <i class="small">Like 8:00 </i>
                                </div>
                                <div class="col-md-1">To</div>
                                <div class="col-md-5">
                                    <input type="time" id ="timing2" class="form-control input-sm" required placeholder="Enter Time" />
                                    <i class="small" >Like 19:00</i>
                                </div>
                            </div>
                            <span id="timing_status" ></span>
                        </div><br/>

                        <div class="form-group salary_check">
                            <span >Expected Salary</span>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="number" id ="salary" class="form-control input-sm" required placeholder="Enter Salary In Rupees" />
                                    <i class="small">Like 2000 </i>
                                </div>
                                <div class="col-md-1">To</div>
                                <div class="col-md-5">
                                    <input type="number" id ="salary2" class="form-control input-sm" required placeholder="Enter SalaryIn Rupees" />
                                    <i class="small" >Like 5000</i>
                                </div>
                            </div>
                            <span id="salary_status" ></span>
                        </div><br/>
                        <div class="form-group">
                            <span>Full Specifications</span>
                            <textarea  required class="form-control input-lg" placeholder="Full Specifications" id="remarks" type="textarea"></textarea>
                            <!-- <span class="animated-label textarea-label">Full Address *</span> -->
                        </div>

                        <div class="form-group">
                            <span>Full Address</span>
                            <textarea  required class="form-control input-lg" placeholder="Full Address" id="address" type="textarea"></textarea>
                            <!-- <span class="animated-label textarea-label">Full Address *</span> -->
                        </div><br/>

                        <span id = "post_request_status"></span>

                        <!-- <div class="form-group">
                            <div class="checkbox">
                                <label class="string optional" for="terms">
                                    <input id="terms" style="" type="checkbox">
                                    <a href="#">I Agree with Term and Conditions</a>
                                </label>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <button type="submit" id="submit_request" class="btn btn-block btn-primary">Submit Request</button>
                        </div>
                    </form>


                </div> <!-- /.account-wrapper -->

            </div>

            <div class="modal-body" id="modal_result_show">
                    <span>
                        <div  style='margin-top: 10px; color: rgb(46, 19, 19); margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px'>
                            <p > <h4 align='center'> <b>Thank you <span id="client_name" style="color: #1ba7de"></span></b><br /> <br />
                                Our team will contact you in next 24 hours.<br>
                            </h4>
                            <h6  align='center'>If you have any query, you can contact 24x7 <br/>
                                <i class="fa fa-whatsapp"></i> or
                                <i class="fa fa-phone"></i>
                                <b style="font-size: 18px; color: #1ba7de">  95990 75355 </b>
                            </h6>
                            </p>
                        </div>
                </span>
            </div>

            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button"  id="close_modal">Close</button>
            </div>
        </div>
    </div>
</div>

<a href="#" data-target="#modal_get_in_touch_success" data-toggle="modal" title="Thanks for your interest"></a>

<div class="modal fade modal-styled" id="modal_get_in_touch_success">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body" id="modal_result_show">
            <span>
                <div  style='margin-top: 10px; color: rgb(46, 19, 19); margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px'>
                    <p> <h4 align='center'> <b>Thank you <span id="get_in_touch_contact_name" style="color: #1ba7de"></span></b><br /> <br />
                        Our team will contact you in next 24 hours.<br>
                    </h4>
                    <h6  align='center'>Your message has been recieved to us.<br/><br/>
                        <i class="fa fa-whatsapp"></i> or
                        <i class="fa fa-phone"></i>
                        <b style="font-size: 18px; color: #1ba7de">  95990 75355 </b>
                    </h6>
                    </p>
                </div>
        </span>
            </div>

            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button"  id="close_modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Plugins -->
<script src="//blueteam.in/static/js/bootstrap.min.js"></script>
<script src="//blueteam.in/static/js/plugins.js"></script>
<script src="//blueteam.in/static/js/twitter/jquery.tweet.min.js"></script>
<script src="//blueteam.in/static/js/jquery.themepunch.tools.min.js"></script>
<script src="//blueteam.in/static/js/jquery.themepunch.revolution.min.js"></script>
<script src="//blueteam.in/static/js/jquery.mb.YTPlayer.js"></script>

<script src="//blueteam.in/static/js/main.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVq_N_uJLaBm3pYRIZfz3gy-7A-iqFfTg&libraries=places"
        async defer></script>

<script>
    /*----------------------------------------------------*/
    //* Google javascript api v3  -- map */
    /*----------------------------------------------------*/
    (function () {
        "use strict";

        if (document.getElementById("mapC") && typeof google === "object") {
            var locations = [
                ['<div class="map-info-box"><ul class="contact-info-list"><li><span><i class="fa fa-home fa-fw"></i></span> Mimar Sinan Mh., Konak/İzmir, Türkiye</li><li><span><i class="fa fa-phone fa-fw"></i></span> +90 0 (232) 324 11 83</li></ul></div>', 38.396652, 27.090560, 9],
                ['<div class="map-info-box"><ul class="contact-info-list"><li><span><i class="fa fa-home fa-fw"></i></span> Kültür Mh., Konak/İzmir, Türkiye</li><li><span><i class="fa fa-phone fa-fw"></i></span> +90 0 (538) 324 11 84</li></ul></div>', 38.432742, 27.159140, 8]
            ];

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: new google.maps.LatLng(28.4646699, 77.08448010000006),
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [{"stylers":[{"hue":"#ff1a00"},{"invert_lightness":true},{"saturation":-100},{"lightness":33},{"gamma":0.5}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#2D333C"}]}]
            });

            var infowindow = new google.maps.InfoWindow();


            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon: 'images/pin.png',
                });

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }

    }());

    function iframe() {
        window.open('http://blueteam.in/web-app/#', 'height:300px', 'width:300px', '_blank');
    }

    $(function() {
        // Slider Revolution for Home Section
        jQuery('#revslider').revolution({
            delay:9000,
            startwidth: 1140,
            startheight: 600,
            onHoverStop:"true",
            hideThumbs:0,
            lazyLoad:"on",
            navigationType:"none",
            navigationHAlign:"center",
            navigationVAlign:"bottom",
            navigationHOffset:0,
            navigationVOffset:20,
            soloArrowLeftHalign:"left",
            soloArrowLeftValign:"center",
            soloArrowLeftHOffset:0,
            soloArrowLeftVOffset:0,
            soloArrowRightHalign:"right",
            soloArrowRightValign:"center",
            soloArrowRightHOffset:0,
            soloArrowRightVOffset:0,
            touchenabled:"on",
            stopAtSlide:-1,
            stopAfterLoops:-1,
            dottedOverlay:"twoxtwo",
            spinned:"spinner5",
            shadow:0,
            hideTimerBar: "on",
            fullWidth:"off",
            fullScreen:"on",
            navigationStyle:"preview3"
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {

        $("#modal_result_show").hide();
        $("#header-search-bar").hide();

    });
</script>
<script>
    $("ul.nav-tabs a").click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
</script>
<script type="text/javascript">

    function showclass() {
        $(".salary_check").show();
    }
    function hideclass() {
        $(".salary_check").hide();
    }

    function genericEmptyFieldValidator(fields){
        returnBool = true;
        $.each(fields, function( index, value ) {
            console.log(value);
            if($('#'+value).val() == "" || $('#'+value).val() == null){
                $('#'+value).keypress(function() {
                    genericEmptyFieldValidator([value]);
                });

                $('#'+value).css("border-color", "red");

                returnBool = false;
            }else{
                $('#'+value).css("border-color", "blue");
            }
        });

        return returnBool;
    }

    function postServiceRequest(fields, hire_type, value) {

        document.getElementById("submit_request").disabled = true;

        $('span[id^="post_request_status"]').empty();

        var dataString = "";
        $('#client_name').html( $('#'+fields[0]).val().capitalizeFirstLetter() );
        dataString = "name=" + $('#'+fields[0]).val() + "&email=" + $('#'+fields[1]).val() + "&mobile=" + $('#'+fields[2]).val() +
            "&needed=" + $('#'+fields[3]).val() + "&timing=" + $('#'+fields[4]).val() + "&timing2=" + $('#'+fields[5]).val() +
            "&salary=" + $('#'+fields[6]).val() + "&salary2=" + $('#'+fields[7]).val() + "&remarks=" + $('#'+fields[8]).val() +
            "&address=" + $('#'+fields[9]).val() + "&type=" + hire_type;
        if(value != 0){
            if(salary == 0 || salary2 == 0 || parseInt(salary2) < parseInt(salary)){
                $('#salary').css("border", "1px solid OrangeRed");
                $('#salary2').css("border", "1px solid OrangeRed");
                $('#salary_status').html("<font style= 'color: red;'>*Enter valid Salary. </font>");
                return false;
            }
        }
        else {}
        $.ajax({
            xhr: function(){
                var xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        //Do something with upload progress
                        console.log(percentComplete);
                    }
                }, false);
                //Download progress
                xhr.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        //Do something with download progress
                        console.log(percentComplete);
                    }
                }, false);
                return xhr;
            },
            type: "POST",
            url: "" + "home/serviceRequest",
            data: dataString,
            cache: false,
            success: function(result){
                document.getElementById("submit_request").disabled = false;
                $("#name").val("");
                $("#mobile").val("");
                $("#email").val("");
                $("#timing").val("");
                $("#needed").val("");
                $("#timing2").val("");
                $("#salary").val("");
                $("#salary2").val("");
                $("#address").val("");
                $("#remarks").val("");
                console.log("inside success");
                $("#modal_body_form").hide();
                $("#modal_result_show").show();
                setTimeout(function () {
                    $("#modal_body_form").show();
                    $("#modal_result_show").hide();
                    $("#close_modal").click();
                }, 10000);
            },
            error: function(result){
                console.log("inside error");
                console.log(result);
                $("#post_request_status").append(result);
                setTimeout(function () {
                    $('span[id^="post_request_status"]').empty();
                }, 10000);
            }
        });
        return false;
    }

    var hire_type = "";
    $('.shortcut').click(function(event) {
        hire_type = $(this).attr('id') ;
    });

    $('#close_modal').click(function(event) {
        $("#modal_body_form").show();
        $("#modal_result_show").hide();
    });

    function isValidDate(subject){
        if (subject.match(/^(?:(19|20)[0-9]{2})[\- \/.](0[1-9]|1[012])[\- \/.](0[1-9]|[12][0-9]|3[01])$/)){
            return true;
        }
        else{
            return false;
        }
    }

    function validateTime(time){
        var a=true;
        var time_arr=time.split(":");
        if(time_arr.length!=2)  a=false;
        else {
            if(isNaN(time_arr[0]) || isNaN(time_arr[1])){
                a=false;
            }
            if(time_arr[0]<24 && time_arr[1]<60) {}
            else a =false;
        }
        return a;
    }

    function validateServiceRequest(){

        $('span[id^="mobile_status"]').empty();
        var value = $("input[name=rate]:checked").val();
        if(value == 0) fields = ["name","email","mobile","needed","timing","timing2","remarks","address"];
        else fields = ["name","email","mobile","needed","timing","timing2","salary","salary2","remarks","address"];

        if (genericEmptyFieldValidator(fields)) {

            var phoneVal = $('#mobile').val();
            var emailVal = $('#email').val();
            var date = $('#needed').val();
            var timing = $('#timing').val();
            var timing2 = $('#timing2').val();
            var salary = $('#salary').val();
            var salary2 = $('#salary2').val();

            var stripped = phoneVal.replace(/[\(\)\.\-\ ]/g, '');
            if (isNaN(parseInt(stripped))) {
                //error("Contact No", "The mobile number contains illegal characters");
                $('#mobile').css("border", "1px solid OrangeRed");
                $('#mobile_status').html("<font style= 'color: red;'>*Enter valid mobile number. </font>");
                return false;
            }
            else if (phoneVal.length != 10) {
                //error("Contact No", "Make sure you included valid contact number");
                $('#mobile').css("border", "1px solid OrangeRed");
                $('#mobile_status').html("<font style= 'color: red;'>*Enter 10 digit  mobile number. </font>");
                return false;
            }
            else if(!(IsEmail(emailVal))) {
                $('#email').css("border", "1px solid OrangeRed");
                $('#email_status').html("<font style= 'color: red;'>*Enter valid Email-ID. </font>");
                return false;
            }
            else if(!(isValidDate(date))) {
                $('#needed').css("border", "1px solid OrangeRed");
                $('#needed_status').html("<font style= 'color: red;'>*Enter valid Date. </font>");
                return false;
            }
            else if(!(validateTime(timing))) {
                $('#timing').css("border", "1px solid OrangeRed");
                $('#timing_status').html("<font style= 'color: red;'>*Enter valid Time. </font>");
                return false;
            }
            else if(!(validateTime(timing2))) {
                $('#timing2').css("border", "1px solid OrangeRed");
                $('#timing_status').html("<font style= 'color: red;'>*Enter valid Time. </font>");
                return false;
            }
            else if(timing == 0 || timing2 == 0 || parseInt(timing2) < parseInt(timing)){
                $('#timing').css("border", "1px solid OrangeRed");
                $('#timing2').css("border", "1px solid OrangeRed");
                $('#timing_status').html("<font style= 'color: red;'>*Enter valid Time. </font>");
                return false;
            }
            postServiceRequest(fields, hire_type, value);

        }
        return false;
    }

    function postGetInTouch(fields) {

        var dataString = "";
        $('#get_in_touch_contact_name').html( $('#'+fields[0]).val().capitalizeFirstLetter() );
        dataString = "contactname=" + $('#'+fields[0]).val() + "&contactemail=" + $('#'+fields[1]).val() + "&contactsubject=" + $('#'+fields[2]).val() + "&contactmessage=" + $('#'+fields[3]).val();

        $.ajax({
            type: "POST",
            url: "" + "home/getInTouch",
            data: dataString,
            cache: false,
            success: function(result){
                $("#contactname").val("");
                $("#contactemail").val("");
                $("#contactmessage").val("");
                $("#contactsubject").val("");

                console.log("inside success");
                $("#reset_form").click();
                $("#modal_get_in_touch_success").modal('show');


            },
            error: function(result){

            }
        });
        return false;
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function validateGetInTouch(){

        fields = ["contactname", "contactemail", "contactsubject", "contactmessage"];

        if (genericEmptyFieldValidator(fields)) {

            if ( IsEmail($("#contactemail").val()) ) {
                postGetInTouch(fields);
            }
            return false;

        }
        return false;

    }

    String.prototype.capitalizeFirstLetter = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    function request(id) {
        $("#addService").attr('disabled','disabled');
        var mobile = $('#requestMobile').val();
        var name = replaceAll('\\s', '', $('#requestName').val());
        if(name.length < 3){
            alert('Please enter valid name');
        }
        else if(!validatePhone(mobile)){
            alert("Please enter valid mobile number");
        }
        else {
            setCookie('mobile', mobile, 10);
            setCookie('name', name, 10);
            $.ajax({
                type: "POST",
                url: "ajax/add_services.php",
                data: 'name='+ name+'&mobile='+mobile+'&lookup_id='+id,
                cache: false,
                success: function(result){
                    if(result=='Succesfully'){
                        alert("Thanks for your help. \n We will connect with you Shortly");
                    }
                }
            });
            $('#requestMobile').val("");
            $('#requestName').val("");
        }
        $("#addService").removeAttr('disabled');
        return false;
    }

    function validatePhone(fld) {
        var res = fld.split(",");
        var filter = /^([7-9][0-9]{9})+$/;
        var result = "" ;
        for(var i = 0; i < res.length; i++) {
            var stripped = res[i];
            if (stripped.value == "") {
                result = false;
            }
            else if (!(filter.test(stripped))) {
                result = false ;
            }
            else if (!(stripped.length == 10)) {
                result = false;
            }
            else result = true ;
        }
        return result;
    }



</script>
<!--
<script type="text/javascript">
var LHCChatOptions = {};
LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500,domain:'blueteam.in'};
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
po.src = '//livechat.blueteam.in/index.php/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true?r='+referrer+'&l='+location;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>-->
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=999514663402400";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-70488081-1', 'auto');
    ga('send', 'pageview');

</script>

<script>
    $( function() {
        $("#startDate").datetimepicker({ format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            todayBtn: true});
    } );
    $( function() {
        $("#startTime").datetimepicker({ format: "hh:ii",
            autoclose: true,
            todayBtn: true});
    } );
</script>
<script src="http://blueteam.in/service_provider/index_files/bootstrap-datetimepicker.min.js"></script>
<script src="http://blueteam.in/service_provider/index_files/business_ltd_1.0.js"></script>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>

<script>
    $(function() {
        FB.init({
            appId  : '235401549997398',
            status : true, // check login status
            cookie : true, // enable cookies to allow the server to access the session
            xfbml  : true  // parse XFBML
        });

        FB.getLoginStatus(function(response) {
            if (response.status == 'connected') {
                getCurrentUserInfo(response)
            } else {
                FB.login(function(response) {
                    if (response.authResponse){
                        getCurrentUserInfo(response)
                    } else {
                        console.log('Auth cancelled.')
                    }
                }, { scope: 'email' });
            }
        });

        function getCurrentUserInfo() {
            FB.api('/me', function(userInfo) {
                console.log(userInfo.name + ': ' + userInfo.email);
            });
        }
    });
    function getLocation() {
        $('#myModal').modal('toggle');
        setTimeout(initAutocomplete, 2000);
    }

    var locationDetails = getUrlVars()["l"];
    if(locationDetails == undefined || locationDetails == null || locationDetails == ""){
        setTimeout(getLocation,2000);
    }
</script>



<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/587e31c0a396482372767ed9/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->


<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>
<script type="text/javascript">stLight.options({publisher: "2b116127-b5f0-4211-8a7a-0870727e907d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script>
    var options={ "publisher": "2b116127-b5f0-4211-8a7a-0870727e907d", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}};
    var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script></body>
</html>
