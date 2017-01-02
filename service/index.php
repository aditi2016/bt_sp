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
$serviceId = $serviceData['id'];
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
                                            AND b.status = 'active' ORDER BY RAND() LIMIT 4;");
$metaData = $serviceData['name']." ".$serviceData['description'] ;
$metaDescription = implode(',', array_keys(extractCommonWords($metaData)));

?>
<!DOCTYPE html>
<html data-placeholder-focus="false" lang="en"><head>
	<link type="text/css" rel="stylesheet" href="index_files/fonts.css">
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
    <meta property="og:title" content="<?=$serviceData['name'] ;?>" />
    <meta name="og:author" content="BlueTeam" />
    <meta property="og:type" content="website"/>

    <meta name="p:domain_verify" content=""/>
    <meta property="og:image" content='<?= $serviceImg ; ?>' />
    <meta property="og:url" content="<?php echo 'www.blueteam.in' ; ?>" />
    <meta property="og:image:type" content="image/jpeg" />

    <meta property="og:description" content="<?=$metaDescription; ?>" />

    <!-- for Twitter -->
    <!-- <meta name="twitter:card" content="n/a" /> -->
    <meta name="twitter:site" content="@hireblueteam">
    <meta name="twitter:creator" content="@hireblueteam">
    <meta name="twitter:url" content="<?php echo 'www.blueteam.in' ; ?>" />
    <meta name="twitter:title" content="<?=$serviceData['name'] ;?>" />
    <meta name="twitter:description" content="<?=$metaDescription; ?>" />
    <meta name="twitter:image" content="<?= $serviceImg ; ?>" />

	<link rel="stylesheet" href="index_files/dedicated_page-afeb09052819dd920d48a269a058338d.css" type="text/css" media="screen">
	<link rel="stylesheet" href="index_files/custom.css" type="text/css" media="screen">	
	<link rel="stylesheet" href="index_files/bootstrap.css" type="text/css" media="screen">	
	<link href="" type="image/png" rel="shortcut icon">
	<link href="" type="image/png" rel="apple-touch-icon">
	<title>service provider</title>
	<link rel="icon" type="image/png"  href="../favicon.ico">
	
</head>

<body style="font-family:Gotham SSm A,Gotham SSm B,Halvetica,sans-serif;letter-spacing:normal" class="buy-service projects dedicated-page" data-device-type="">
	<div id="dummy-react"><!-- react-empty: 1 --></div>
	<div id="header">
	  <header style=""  >
	  	<div class="city-select" >
	    </div>
	    <div class="header-group header-main" >
	      <div class="header-item header-logo" d>
	        <a class="housing-logo" title="blueteam" href="http://blueteam.in" ></a>
	      </div>
	    </div>
	    
	    <div >
	      <input type="text" id="search_box" style="vertical-align: middle;color: #000;min-width: 400px;
	       		margin: 2px;" class="" >
	      <button id="search" class="btn btn-info"onclick="search();"><i class="icon-search"></i></button> 
	    </div>
	  </header>
    </div>
		
	<div id="search-results"></div>
	<div id="notification-container"></div>
	<div id="main-content" style="overflow-y: hidden ! important;overflow-x: hidden ! important;
            background-image: url('<?= $serviceImg ?>');background-size:cover;background-repeat: no-repeat;
			background-position: top;">
	  <div id="dedicated-buy-np-container" >
		<div class="banner-section mw">
		  <div class="row">
	      <div class="col-lg-9 col-md-9 col-sm-12">
			  <div class="image-info mw">
				<div class="image-info-inner" style="background-color: #fff;margin-bottom: 10px;padding-top: 10px;">
				  <div class="price-details"></div>
				  <div class="clearfix property-info" >
				  	<span class="prifile-img">
				  		<img src="<?=$profilePic;?>" style='max-width: 150px;max-height: 150px;padding-bottom:20px; '>
				  	</span>
					<p class="main-text" itemprop="name" style="font-size: 14px;word-break: normal;width:70%">
						<?=$serviceData['name']; ?><br/><?=$serviceData['description']; ?>
					</p>
				  </div>
				</div>
			  </div>
			  
		      <section id="carouselSection" style="text-align:center">
	            <div id="myCarousel" class="carousel slide">
	              <div class="carousel-inner">
	            <?php
				echo "<div  style='text-align:center'  class='item active'>
	                        <img src='".$serviceImg."' alt='business webebsite template'>
	                       </div>";

	            while ( $photos = mysqli_fetch_array($photosArray)) {
	            	if($photos['photo_id']== 0) $image = 1075;
					else $image = $photos['photo_id'] ;
	                echo "<div  style='text-align:center'  class='item'>
	                        <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$image."' alt='business themes'>
	                       </div>";  

	             } 
	            ?>
	              </div>
	              <a class="left carousel-control"  href="#myCarousel" data-slide="prev">&lsaquo;</a>
	              <a class="right carousel-control"  href="#myCarousel" data-slide="next">&rsaquo;</a>
	            </div>
	          </section>
		  </div>
		  <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 30px;background-color: #fff;">
        	<p style="white-space: nowrap; padding-top: 10px;"><?= count($allServiceProviders) ?> Service Providers Found</p>
			<hr/>
			  <?php
                foreach ($allServiceProviders as $serviceProvider ) {
                	if($serviceProvider->hourly =='yes') $perHour = "/ Hour";
                	else $perHour ="";
                	if($serviceProvider->price=="") $price = 0;
                	else $price = $serviceProvider->price;
                	if($serviceProvider->profile_pic_id== 0) $img2 = 1075;
					else $img2 = $serviceProvider->profile_pic_id ;
                    echo "<div><a style='text-decoration:none;' href='../service_provider/index.php?load=".$serviceProvider->name."-".$serviceProvider->id."-gurgaon&s= ".$serviceName."-".$serviceId."&l=".$location."' style='text-decoration:none;'>
                            <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$img2."' height='70px' width='70px'>
                            <p style='font-size:12px;margin:-70px 0 0 80px;color:#000;'>".$serviceProvider->name."<br/>".$price."<i class='icon icon-rupee'></i> ".$perHour." <br/><br/>
                            <button class='btn btn-info'>View Details</button><br/>
                            </p>
						  </a>
						  </div><hr/>"; 
                }
			  	if(count($allServiceProviders) <= 0){
					echo "<div class='flat-container'><span style='font-size:12px;'>Sorry! No Service Provider in this Area<br/>
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
							<input id='requestName' type='text' placeholder='Plese enter your Name'>
		  					<input id='requestMobile' type='text' placeholder='Enter your mobile number'><br/><br/>
		  					<button type='button' id='addService' class='btn btn-info' onclick='request(\"".$lookUpId."\");'>Submit</button>
		  				</div>";
		  			
				}
              ?>
              	
			
		  </div>
     	</div>
		  
		</div>
		
		<div class="clearfix dummy sticky-container" style="height: 60px;"></div>
		  <div class="body-elem">
			<div class="inner-body mw">
			  <div class="main-layout">
				<div class="bordered-card card-cont hide" id="discussions-card"></div>
				
				<!-- <div class="hide-embed" id="similar-card">
				  <div class="bordered-card card-cont mw similar-flat-card">
					<h2 class="header-cont"><?= count($allServiceProviders) ?> Service Providers Found</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  <?php /*
	                    foreach ($allServiceProviders as $serviceProvider ) {
	                    	if($serviceProvider->hourly =='yes') $perHour = "/ Hour";
	                    	else $perHour ="";
	                    	if($serviceProviders['price']=="") $price = 0;
	                    	else $price = $serviceProviders['price'] ;
	                    	if($serviceProviders['profile_pic_id']== 0) $img = 1075;
							else $img = $serviceProviders['profile_pic_id'] ;
	                        echo "<a class='flat-link' href='../service_provider/index.php?load=".$serviceProviders['name']."-".$serviceProviders['id']."-gurgaon&s= ".$serviceName."-".$serviceId."&l=".$location."' style='text-decoration:none;'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$img.")'></div>
									</div>
									<div class='name-info'>
									  	<div class='project-info'>".$serviceProvider->name."</div>
									</div>
									<div class='loct-info text'></div>
									<div class='price'>
									  <span class='value'>".$price." 
									    <i class='icon icon-rupee'></i> ".$perHour." <br/>Nagotiable : ".strtoupper($serviceProviders->negotiable)."</span>
									</div>
								  </a>"; 
	                    }
					  	if(count($allServiceProviders) <= 0){
							echo "Sorry! No Service Provider in this Area<br/>
									We have taken your request for this area.<br/>
									We are committed to add 3 service providers in this area in next 48 hrs.<br/>
									Process of adding service provider<br/>
									1. Enqueuing 10 service providers in the area<br/>
									2. Interviewing every service provider<br/>
									3. Shot listing <br/>
									4. Document Verification<br/>
									5. Profile Creation<br/>
									Give us chance to reach you, after process compilation<br/>
									Name: ; Mobile ; <br/>
									Thank You";
						} */
	                  ?>
					   
					  </div>
					</div>
				  </div>
				</div> -->
				<div class="hide-embed" id="similar-card">
				  <div class="bordered-card card-cont mw similar-flat-card">
					<h2 class="header-cont">Recommended Services</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  <?php
	                    while ($allRecommendedServices = mysqli_fetch_array($recommendedServices)) {
	                    
	                    	if($allRecommendedServices['pic_id']== 0) $img = 1075;
							else $img = $allRecommendedServices['pic_id'] ;
	                        echo "<a class='flat-link' href='../service/index.php?load=".$allRecommendedServices['name']."-gurgaon&l=".$location."' style='text-decoration:none;'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$img.")'></div>
									  
								  	</div>
								  	<div class='name-info'>
									  	<div class='project-info'>".$allRecommendedServices['name']."</div>
									</div>
	                                <div class='apt-info text'>".$allRecommendedServices['description']."</div>
								  </a>"; 
	                    }
	                  ?>
					   
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="right-elem sticky" >
				<div id="booking-container-card"></div>
				<div class="bordered-card" id="card-contact">
				  <div class="container">
					<div class="top-container">
					  <div class="clearfix person-controls">
						<div class="pull-left seller-count"></div>
						<div class="navbar pull-right" data-length="0">
					 	  <div class="disabled icon-arrow-left left-nav nav" data-action="prev"></div>
						  <div class="icon-arrow-right nav right-nav" data-action="next"></div>
						</div>
					  </div>
					  <div class="all-person-holder">
						<div class="all-person-translator">
						  <div class="person-container selected" >
							<img class="img normal" src="index_files/icon.png" alt="BlueTeam logo">
							<div class="info">
							  <a class="name" href=""  data-bypass="">BlueTeam.in</a>
							  <div class="type">Shatkon Labs Pvt Ltd</div>
							</div>
							<div class="select-contact">
							  <i class="icon icon-checkbox"></i>
							  <i class="icon icon-checkbox-filled"></i>
							</div>
						  </div>
						</div>
					  </div>
					</div>
					<div class="input-container">
					  <div class="not-sent">
						<div class="dd-bhk-container tags">
						  <div class="prev-text tl">I would like to know more about</div>
						  <div class="generic-tag-container">
						    <div class="dummy-form-elem form-element">
						      <div class="input-helper">
							    <div class="up-arrow"></div>
							    <div class="helper-text">Please select a preference</div>
							  </div>
							</div>
						  </div>
						</div>
						  
						<div class="form-element with-country-code invalid filled">
						  <div class="placeholder">Phone</div>
						  <input class="input country-code"  value="+91" name="country_code" readonly="readonly" data-text="true" data-length="3" data-url_name="in" type="select">
						  <input class="input phone" id="inputContact" required="" name="phone" country-code="true" pattern="^[0-9]{10}$" type="tel">
						</div>  
							  
						<div class="form-field sent-button-container">
						  <button id="getInTouch" class="btn btn-info"onclick="getInTouch();">Get In Touch</button>
						</div>
						<div class="hide on-error-container"></div>
					  </div>
						
				    </div>
					<div class="otp-container"></div>
				  </div>
					
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	<script src="index_files/jquery-1.10.2.js"></script>
	  <!-- BOOTSTRAP SCRIPTS -->
	<script src="index_files/bootstrap.min.js"></script>
	<script type="text/javascript">
	  	function getInTouch() {
	  		$("#getInTouch").attr('disabled','disabled');
	  		var mobile = $('#inputContact').val();
	  		if(validatePhone(mobile)){
				$.ajax({
					type: "POST",
					url: "ajax/get_in_touch.php",
					data: 'mobile='+ mobile,
					cache: false,
					success: function(result){
						if(result=='Succesfully'){
							alert("Thanks for contacting us. \n We will connect with you Shortly");
						}
					}
				});
				$('#inputContact').val("");
			}
			else {
				alert("Please enter valid mobile number");
			}
			$("#getInTouch").removeAttr('disabled');
			return false;
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
		function replaceAll(find, replace, str) {
			if(str == null) {
				str = "";
			}
			return str.replace(new RegExp(find, 'g'), replace);
		}
		$(document).ready(function() {
		    $('#search_box').keydown(function(event) {
		        if (event.keyCode == 13) {
		            var keywords = replaceAll('\\s', '', $('#search_box').val());
					if(keywords.length < 3){
						return false;
					}
					else {
						search();
		            	return false;
		            }
		         }
		    });
		});
		function search() {
			$("#search").attr('disabled','disabled');
			var keywords = replaceAll('\\s', '', $('#search_box').val());
			if(keywords.length < 3){
				alert ('Minimum words length is 3');
			}
			else {
				$.ajax({
					type: "POST",
					url: "ajax/search.php",
					data: 'keywords='+ keywords,
					cache: false,
					success: function(result){
						document.getElementById("search-results").innerHTML = result ;
					}
				});
				$('#search_box').val("");
			}
			$("#search").removeAttr('disabled');
			return false;
		}
	</script>
	<script src="index_files/bootstrap-modal.js"></script>
	<script src="index_files/business_ltd_1.0.js"></script>
</body>
</html>