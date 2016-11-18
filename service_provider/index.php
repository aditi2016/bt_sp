<?php
//input
// url: http://blueteam.in/service/index.php?load=cleaning-1-gurgaon
// url: http://blueteam.in/service_provider/index.php?load=anil%20kumar-1-gurgaon

// Name of service/ service provider
// Id of service
session_start();
$dbHandle = mysqli_connect("localhost","root","redhat111111","blueteam_service_providers");
$url = explode("-",$_GET['load']);
$serviceProviderName = $url[0];
$serviceProviderId = $url[1];
$cityName = $url[2];
$serviceProvider = mysqli_query($dbHandle, "SELECT * FROM service_providers 
                                    WHERE id = '$serviceProviderId' ;");
$serviceProviderData = mysqli_fetch_array($serviceProvider);
$profilePic = "http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceProviderData['profile_pic_id'];
$photosArray = mysqli_query($dbHandle, "SELECT photo_id FROM photos WHERE 
                                        service_provider_id = '$serviceProviderId' ;");

$allServices = mysqli_query($dbHandle, "SELECT a.price, a.nagotiable, b.name, b.pic_id, b.description
                                            FROM service_provider_service_mapping AS a
                                            JOIN services AS b
                                            WHERE a.service_provider_id = '$serviceProviderId'
                                            AND a.service_id = b.id AND b.status = 'active' ;");


// vendor other service
//$otherServices
//service {pic, title, description, price}

//$sameServiceByOtherVenders
//$recommendedService

/*$nagotiable
$comments
$likeCount
$okCount
$averageCount
$donotLikeCount*/


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BlueTeam</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header" style="float: right;">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" ><?=$serviceProviderData['name']; ?></a> 
            </div>
            <div style="color: white;padding: 15px 50px 5px 50px;float: left;font-size: 16px;"> 
                Last access : 30 May 2014 &nbsp; 
                <a href="#" class="btn btn-danger square-btn-adjust">Logout</a> 
            </div>
        </nav>   
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side pull-right" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                <li class="text-center">
                    <img src="<?=$profilePic; ?>" class="user-image img-responsive"/>
                    </li>
                    <li>
                        <a class="active-menu"  ><i class="fa fa-flag fa-2x"></i> 
                        <?=$serviceProviderData['organization']; ?></a>
                    </li>
                     <li>
                        <a  ><i class="fa fa-bars fa-2x"></i>
                        <?=$serviceProviderData['description']; ?></a>
                    </li>
                    <li>
                        <a  ><i class="fa fa-home fa-2x"></i>
                        <?=$serviceProviderData['address']; ?></a>
                    </li>
                        
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <section id="carouselSection" style="text-align:center">
                            <div id="myCarousel" class="carousel slide">
                                <div class="carousel-inner">
                                <?php
                                $flag = true;
                                while ( $photos = mysqli_fetch_array($photosArray)) {
                                    if($flag){
                                     echo "<div  style='text-align:center'  class='item active'>
                                            <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$photos['photo_id']."' alt='business webebsite template'>
                                           </div>";
                                    }
                                    else {
                                     echo "<div  style='text-align:center'  class='item'>
                                            <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$photos['photo_id']."' alt='business themes'>
                                           </div>";  
                                    }
                                    $flag = false;
                                 } 
                                ?>
                                </div>
                                <a class="left carousel-control"  href="#myCarousel" data-slide="prev">&lsaquo;</a>
                                <a class="right carousel-control"  href="#myCarousel" data-slide="next">&rsaquo;</a>
                            </div>
                        </section>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue set-icon">
                                <i class="fa fa-rocket"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">120</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-green set-icon">
                                <i class="fa fa-heart"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">30</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-brown set-icon">
                                <i class="fa fa-check"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">240</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-red set-icon">
                                <i class="fa fa-thumbs-o-down"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">3 </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
                <div class="row">
                <?php
                    while ($allServicesOfVendor = mysqli_fetch_array($allServices)) {
                        echo "<div class='col-md-3 col-sm-6 col-xs-12'>
                                <div class='panel panel-primary text-center no-boder bg-color-green'>
                                    <div class='panel-body'>
                                        <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$allServicesOfVendor['pic_id']."' class='service-image img-responsive'/>
                                        <span class='service-name'>".$allServicesOfVendor['price']." Rs per Hour <br/>Nagotiable : ".strtoupper($allServicesOfVendor['nagotiable'])." </span>
                                    </div>
                                    <div class='panel-footer back-footer-green'>
                                       ".$allServicesOfVendor['name']." <br/>
                                       ".$allServicesOfVendor['description']."
                                    </div>
                                </div>
                            </div>";
                    }
                ?>
                    
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="chat-panel panel panel-default chat-boder chat-panel-head" >
                            <div class="panel-heading">
                                
                                <textarea id="btn-input" type="text" class="form-control inpul-lg" placeholder="Type your message to send..." ></textarea>
                            

                            </div>

                            <div class="panel-body">
                                
                                <ul class="chat-box">
                                    <li class="left clearfix">
                                        <span class="chat-img pull-left">
                                            <img src="assets/img/1.png" alt="User" class="img-circle" />
                                        </span>
                                        <div class="chat-body">                                        
                                                <strong >Jack Sparrow</strong>
                                                <small class="pull-right text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i>12 mins ago
                                                </small>                                      
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                            </p>
                                        </div>
                                    </li>
                                    <li class="right clearfix">
                                        <span class="chat-img pull-right">

                                            <img src="assets/img/2.png" alt="User" class="img-circle" />
                                        </span>
                                        <div class="chat-body clearfix">
                                            
                                                <small class=" text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i>13 mins ago</small>
                                                <strong class="pull-right">Jhonson Deed</strong>
                                          
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                            </p>
                                        </div>
                                    </li>
                                    <li class="left clearfix">
                                        <span class="chat-img pull-left">
                                             <img src="assets/img/3.png" alt="User" class="img-circle" />
                                        </span>
                                        <div class="chat-body clearfix">
                                            
                                                <strong >Jack Sparrow</strong>
                                                <small class="pull-right text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i>14 mins ago</small>
                                            
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                            </p>
                                        </div>
                                    </li>
                                    <li class="right clearfix">
                                        <span class="chat-img pull-right">
                                             <img src="assets/img/4.png" alt="User" class="img-circle" />
                                        </span>
                                        <div class="chat-body clearfix">
                                          
                                                <small class=" text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i>15 mins ago</small>
                                                <strong class="pull-right">Jhonson Deed</strong>
                                           
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                            </p>
                                        </div>
                                    </li>
                                        <li class="left clearfix">
                                        <span class="chat-img pull-left">
                                            <img src="assets/img/1.png" alt="User" class="img-circle" />
                                        </span>
                                        <div class="chat-body">                                        
                                                <strong >Jack Sparrow</strong>
                                                <small class="pull-right text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i>12 mins ago
                                                </small>                                      
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                            </p>
                                        </div>
                                    </li>
                                    <li class="right clearfix">
                                        <span class="chat-img pull-right">
                                           <img src="assets/img/2.png" alt="User" class="img-circle" />
                                        </span>
                                        <div class="chat-body clearfix">
                                            
                                                <small class=" text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i>13 mins ago</small>
                                                <strong class="pull-right">Jhonson Deed</strong>
                                          
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        
                    </div>
                    
                </div>     
                <!-- /. ROW  -->           
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/business_ltd_1.0.js"></script>
    <!-- METISMENU SCRIPTS -->
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
