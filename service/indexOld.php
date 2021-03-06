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
                                            AND b.status = 'active' ORDER BY RAND() LIMIT 4;");
$locationDetails = json_decode(httpGet("http://api.sp.blueteam.in/location/".$_GET['l']), true)[location_details];
$areaName = str_replace('-',', ',$locationDetails[area]['name']);
$cityName = str_replace('-',', ',$locationDetails[city]['name']);
$metaData = $serviceData['name'].", ".$serviceData['description'].", ".$areaName.", ".$cityName ;
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

	<link rel="stylesheet" href="index_files/dedicated_page-afeb09052819dd920d48a269a058338d.css" type="text/css" media="screen">
	<link rel="stylesheet" href="index_files/custom.css" type="text/css" media="screen">	
	<link rel="stylesheet" href="index_files/bootstrap.css" type="text/css" media="screen">	
	<link href="" type="image/png" rel="shortcut icon">
	<link href="" type="image/png" rel="apple-touch-icon">
	<title><?php echo $serviceName.", ".$areaName.", ".$cityName ;?></title>
	<link rel="icon" type="image/png"  href="../favicon.ico">
	<style type="text/css">
		#map{
			min-height: 500px;
		}
	</style>

	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" id="st_insights_js"
			src="//w.sharethis.com/button/buttons.js?publisher=2b116127-b5f0-4211-8a7a-0870727e907d"></script>
	<script type="text/javascript" src="//s.sharethis.com/loader.js"></script>
	<script type="text/javascript">
		stLight.options({publisher: "2b116127-b5f0-4211-8a7a-0870727e907d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
	
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
	      <button id="search" class="btn btn-info" onclick="search();"><i class="icon-search"></i></button> 
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
						  <button id="getInTouch" class="btn btn-info" onclick="getInTouch();">Get In Touch</button>
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
	  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="myModalLabel">Please select your location</h4>
	            </div>
	            <div class="modal-body">
	                <input id="pac-input" class="controls" type="text" placeholder="Search Box">
	                <div id="map" ></div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                <button type="button" class="btn btn-primary" onclick="loadServicePage()">Take Loction</button>
	            </div>
	        </div>
	    </div>
	</div>
	<script src="index_files/jquery-1.10.2.js"></script>
	  <!-- BOOTSTRAP SCRIPTS -->
	<script src="index_files/bootstrap.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVq_N_uJLaBm3pYRIZfz3gy-7A-iqFfTg&libraries=places"
        async defer></script>
	<script type="text/javascript">
		var lat = 28.4595;
    	var lng = 77.0266;
	  	function getInTouch() {
	  		$("#getInTouch").attr('disabled','disabled');
	  		var mobile = $('#inputContact').val();
	  		setCookie('mobile', mobile, 10);
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
		function replaceAll(find, replace, str) {
			if(str == null) {
				str = "";
			}
			return str.replace(new RegExp(find, 'g'), replace);
		}
		$(document).ready(function() {
			$("#map-button").click(function(){
	            $("#map").toggle(1000);
	        });
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
		   	var userMobile = getCookie('mobile');
		   	var userName = getCookie('name');
		   	$("#inputContact").val(userMobile); 
		   	$("#requestMobile").val(userMobile); 
		   	$("#requestName").val(userName); 
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
		function initAutocomplete() {

	        var map = new google.maps.Map(document.getElementById('map'), {
	            center:  {lat:lat,lng:lng},
	            zoom: 13,
	            mapTypeId: google.maps.MapTypeId.ROADMAP
	        });
	        var marker = new google.maps.Marker({
			    position: {lat:lat,lng:lng},
			    draggable: true
			});
			marker.setMap(map);
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
	            marker.setMap(null);	            
	            marker = new google.maps.Marker({
	                position: {lat:lat,lng:lng},
	                draggable: true
                });
                marker.setMap(map);
	        });
	        var markers = [];
	        // Listen for the event fired when the user selects a prediction and retrieve
	        // more details for that place.
	        searchBox.addListener('places_changed', function() {
	            var places = searchBox.getPlaces();

	            if (places.length == 0) {
	                return;
	            }
	            
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

	                /*marker = new google.maps.Marker({
		                 map: map,
		                 icon: icon,
		                 title: place.name,
		                 position: place.geometry.location
	                });
	                marker.setMap(map);*/

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
		function getUrlVars() {
		    var vars = {};
		    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
		    function(m,key,value) {
		      vars[key] = value;
		    });
		    return vars;
		}
		function loadServicePage(){
			var serviceData = getUrlVars()["load"];
	        window.location.href = 'http://blueteam.in/service/index.php?load='+serviceData+'&l='+lat+','+lng;
	    }
	    function getLocation() {
	    	$('#myModal').modal('toggle');
	    	setTimeout(initAutocomplete, 2000);
	    }
		var locationDetails = getUrlVars()["l"];
		if(locationDetails == undefined || locationDetails == null || locationDetails == ""){
			setTimeout(getLocation,2000);       	
		}
		function setCookie(cname, cvalue, exdays) {
		    var d = new Date();
		    d.setTime(d.getTime() + (exdays*24*60*60*1000));
		    var expires = "expires="+ d.toUTCString();
		    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}
		function getCookie(cname) {
		    var name = cname + "=";
		    var ca = document.cookie.split(';');
		    for(var i = 0; i < ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) == ' ') {
		            c = c.substring(1);
		        }
		        if (c.indexOf(name) == 0) {
		            return c.substring(name.length, c.length);
		        }
		    }
		    return "";
		}
	</script>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70488081-1', 'auto');
  ga('send', 'pageview');

</script>
	<script src="index_files/bootstrap-modal.js"></script>
	<script src="index_files/business_ltd_1.0.js"></script>

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

	<script type="text/javascript">stLight.options({publisher: "2b116127-b5f0-4211-8a7a-0870727e907d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
	<script>
		var options={ "publisher": "2b116127-b5f0-4211-8a7a-0870727e907d", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}};
		var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
	</script>
	
</body>
</html>