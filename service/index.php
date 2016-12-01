<?php

session_start();
include_once 'ajax/functions.php';
$url = explode("-",$_GET['load']);
$serviceName = $url[0];
$cityName = $url[1];
$userId = 1;

$service = mysqli_query($dbHandle, "SELECT * FROM services 
                                    WHERE name = '$serviceName' ;");
$serviceData = mysqli_fetch_array($service);
$serviceId = $serviceData['id'];
$objectId = 'bt-sp-'.$serviceId;
$profilePic = "http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceData['pic_id'];
$photosArray = mysqli_query($dbHandle, "SELECT photo_id FROM photos WHERE 
                                        service_provider_id IN (SELECT service_provider_id FROM
                                        service_provider_service_mapping WHERE service_id = '$serviceId') ;");

$allServiceProviders = mysqli_query($dbHandle, "SELECT a.name, a.organization, a.id, a.profile_pic_id, 
											b.price, b.negotiable, b.hourly FROM service_providers AS a
											JOIN service_provider_service_mapping AS b WHERE 
											a.id = b.service_provider_id AND b.service_id = '$serviceId' ;");

$recommendedServices = mysqli_query($dbHandle, "SELECT a.price, a.negotiable, b.name, b.pic_id, b.description
                                            FROM service_provider_service_mapping AS a
                                            JOIN services AS b
                                            WHERE a.service_id = b.id AND b.status = 'active' 
                                            ORDER BY RAND() LIMIT 4;");


?>
<!DOCTYPE html>
<html data-placeholder-focus="false" lang="en"><head>
	<link type="text/css" rel="stylesheet" href="index_files/fonts.css">
	<meta charset="utf-8">
	<meta content="IE=Edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimum-scale=1.0">
	<link rel="stylesheet" href="index_files/dedicated_page-afeb09052819dd920d48a269a058338d.css" type="text/css" media="screen">
	<link rel="stylesheet" href="index_files/custom.css" type="text/css" media="screen">	
	<link rel="stylesheet" href="index_files/bootstrap.css" type="text/css" media="screen">	
	<link href="" type="image/png" rel="shortcut icon">
	<link href="" type="image/png" rel="apple-touch-icon">
	<title>service provider</title>
	
</head>

<body style="font-family:Gotham SSm A,Gotham SSm B,Halvetica,sans-serif;letter-spacing:normal" class="buy-service projects dedicated-page" data-device-type="">
	<div id="dummy-react"><!-- react-empty: 1 --></div>
	<div id="header">
	  <header style=""  >
	  	<div class="city-select" >
	    </div>
	    <div class="header-group header-main" >
	      <div class="header-item header-logo" d>
	        <a class="housing-logo" title="blueteam" href="http://blueteam.in/app/" ></a>
	      </div>
	    </div>
	    
	    <div >
	      <input type="text" id="search_box" style="vertical-align: middle;color: #000;min-width: 400px;
	       		margin: 2px;" class="" >
	      <button id="search" class="btn primary"onclick="search();"><i class="icon-search"></i></button> 
	    </div>
	  </header>
    </div>
		
	<div id="search-results"></div>
	<div id="notification-container"></div>
	<div id="main-content"><!-- / not of primary and secondary as it will be true if all its keys would be having a false value. -->
	  <div id="dedicated-buy-np-container" >
		<div class="banner-section mw">
		  
		  <div class="image-info mw">
			<div class="image-info-inner">
			  
			  <div class="clearfix property-info">
			  	<span class="prifile-img">
			  		<img src="<?=$profilePic;?>" style='max-width: 150px;max-height: 150px;'>
			  	</span>
				<h1 class="main-text" itemprop="name"><?=$serviceData['name']; ?></h1>
				<h2 class="builder-text">
				  <span itemprop="brand"><?=$serviceData['description']; ?></span>
				</h2>
			  </div>
			</div>
		  </div>
		  
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
		
		<div class="clearfix dummy sticky-container" style="height: 60px;"></div>
		  <div class="body-elem">
			<div class="inner-body mw">
			  <div class="main-layout">
				<div class="bordered-card card-cont hide" id="discussions-card"></div>
				
				<div class="hide-embed" id="similar-card">
				  <div class="bordered-card card-cont mw similar-flat-card">
					<h2 class="header-cont">Service Providers of this Service</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  <?php
	                    while ($serviceProviders = mysqli_fetch_array($allServiceProviders)) {
	                        echo "<a class='flat-link' href='../service_provider/index.php?load=".$serviceProviders['name']."-".$serviceProviders['id']."-gurgaon'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceProviders['profile_pic_id'].")'></div>
									  <div class='name-info'>
									  	<div class='project-info'>".$serviceProviders['name']."</div>
									  </div>
								  	</div>
	                                <div class='apt-info text'>".$serviceProviders['organization']."</div>
									<div class='loct-info text'></div>
									<div class='price'>
									  <span class='value'>".$serviceProviders['price']." 
									    <i class='icon icon-rupee'></i> per Hour <br/>Nagotiable : ".strtoupper($serviceProviders['negotiable'])."</span>
									</div>
								  </a>"; 
	                    }
	                  ?>
					   
					  </div>
					</div>
				  </div>
				</div>
				<div class="hide-embed" id="similar-card">
				  <div class="bordered-card card-cont mw similar-flat-card">
					<h2 class="header-cont">Recommended Services</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  <?php
	                    while ($allRecommendedServices = mysqli_fetch_array($recommendedServices)) {
	                        echo "<a class='flat-link' href='../service/index.php?load=".$allRecommendedServices['name']."-gurgaon'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$allRecommendedServices['pic_id'].")'></div>
									  
								  	</div>
								  	<div class='name-info'>
									  	<div class='project-info'>".$allRecommendedServices['name']."</div>
									</div>
	                                <div class='apt-info text'>".$allRecommendedServices['description']."</div>
									<div class='loct-info text'>Natwar Nagar, Jogeshwari East</div>
									<div class='price'>
									  
									  <span class='value'>".$allRecommendedServices['price']." 
									    <i class='icon icon-rupee'></i> per Hour <br/>Nagotiable : ".strtoupper($allRecommendedServices['negotiable'])."</span>
									</div>
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
						  <button id="getInTouch" class="btn primary"onclick="getInTouch();">Get In Touch</button>
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