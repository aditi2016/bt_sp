<?php

session_start();
include_once 'ajax/functions.php';
$url = explode("-",$_GET['load']);
$serviceProviderName = $url[0];
$serviceProviderId = $url[1];
$serviceUrl = explode("-",$_GET['s']);
$serviceNameUrl = $serviceUrl[1];
$location = $_GET['l'];
$service = mysqli_query($dbHandle, "SELECT a.price,b.service_img, a.negotiable,a.hourly,b.name, 
									b.pic_id, b.description FROM service_provider_service_mapping AS a
									JOIN services AS b WHERE a.service_provider_id = 
									'$serviceProviderId' AND a.service_id = b.id and 
									a.service_id = '$serviceNameUrl' ;");
$serviceData = mysqli_fetch_array($service);
if($serviceData['pic_id']== 0) $img = 1075;
else $img = $serviceData['pic_id'] ;
$icon = "http://api.file-dog.shatkonlabs.com/files/rahul/".$img;
$serviceName = $serviceData['name'];
if($serviceData['hourly']=='yes') $servicePerHour = "/ Hour";
else $servicePerHour ="";
if($serviceData['price']=="") $servicePrice = 0;
else $servicePrice = $serviceData['price'] ;
$userId = 1;
$objectId = 'bt-sp-'.$serviceProviderId;
$serviceProvider = mysqli_query($dbHandle, "SELECT * FROM service_providers 
                                    WHERE id = '$serviceProviderId' ;");
$serviceProviderData = mysqli_fetch_array($serviceProvider);
if($serviceProviderData['profile_pic_id']== 0) $pic = 1075;
else $pic = $serviceProviderData['profile_pic_id'] ;
$profilePic = "http://api.file-dog.shatkonlabs.com/files/rahul/".$pic;
$photosArray = mysqli_query($dbHandle, "SELECT photo_id FROM photos WHERE 
                                        service_provider_id = '$serviceProviderId' ;");

$allServices = mysqli_query($dbHandle, "SELECT a.price, a.negotiable, a.hourly,b.name,b.pic_id, 
										b.description FROM service_provider_service_mapping AS a JOIN
										services AS b WHERE a.service_provider_id ='$serviceProviderId'
                                        AND a.service_id = b.id AND b.status = 'active' ;");

$serviceImg = "http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceData['service_img'];

$recommendedServices = mysqli_query($dbHandle, "SELECT a.price,a.negotiable,a.hourly,b.name,b.pic_id,
											b.description FROM service_provider_service_mapping AS a
                                            JOIN services AS b WHERE a.service_id = b.id 
                                            AND b.status = 'active' ORDER BY RAND() LIMIT 4;");

$commentsCountUrl = "http://api.wazir.shatkonlabs.com/feedbacks/".$userId."/".$objectId."/count";
$commentsCounts = json_decode(httpGet($commentsCountUrl), true)['counts'];

foreach ($commentsCounts as $key => $value) {
    if($value['type'] == 'marvelous') $marvelous = $value['count'];
    elseif($value['type'] == 'appreciation') $appreciation = $value['count'];
    elseif($value['type'] == 'suggestion') $suggestion = $value['count'];
    else $complain = $value['count'];
}
$commentsUrl = "http://api.wazir.shatkonlabs.com/feedbacks/".$userId."/".$objectId;
$comments = json_decode(httpGet($commentsUrl), true)['feedbacks'];
$reliabilityScore = round((($serviceProviderData['reliability_score']/(4*$serviceProviderData['reliability_count']))*100),2);

$qualityTotal = 4*($marvelous+$appreciation+$suggestion+$complain) ;
$quality = ($marvelous*4)+($appreciation*3)+($suggestion*2)+$complain ;
$qualityScore = round((($quality/$qualityTotal)*100),2) ;

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
	<link rel="stylesheet" href="index_files/bootstrap-datetimepicker.min.css" type="text/css">	
	<link rel="stylesheet" href="index_files/bootstrap.css" type="text/css" media="screen">	
	<link href="" type="image/png" rel="shortcut icon">
	<link href="" type="image/png" rel="apple-touch-icon">
	<title>service provider</title>
	<link rel="icon" type="image/png"  href="../favicon.ico">
	
</head>

<body style="font-family:Gotham SSm A,Gotham SSm B,Halvetica,sans-serif;letter-spacing:normal" class="buy-service projects dedicated-page" data-device-type="">
	<div id="bookNow" class="modal fade" role="dialog">
	  <div class="modal-dialog">

    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Book Now</h4>
	      </div>
	      <div class="modal-body">
	        <input id="bookName" type="text" placeholder="Type your Name">
		  	<input id="bookMobile" type="text" placeholder="Type your mobile number"> <br/><br/>
		  	<input id="bookAddress" type="text" placeholder="Type your Address">
		  	<textarea id="remarks" type="text" placeholder="Remark" ></textarea><br/><br/>
		  	<input id="bookServiceProviderId" type="hidden" value="">
		  	<input id="bookServiceId" type="hidden" value="">
		  	<input id="userLocation" type="hidden" value="">
		  	<label>Starting Date & Time</label>
		    <input id="startDate"  placeholder="Enter Starting Date and time"> <br/><br/>
		    <label>Hour</label>
            <select id= "totalHour">    
                <option value='1' selected >1</option>
                <?php
                for ($i=2; $i<13 ; $i++) {
                	echo "<option value=".$i." >".$i."</option>";                 	# code...
                } 
                ?>
            </select><br/>
            
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	        <button type="button" id="bookService" class="btn btn-info" onclick="bookNow();">Book</button>
	      </div>
	    </div>

	  </div>
	</div> 
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
	      <button id="search" class="btn btn-info"onclick="search();"><i class="icon-search"></i></button>
	    </div>

	  </header>
    </div>
		
	<div id="search-results"></div>
	<div id="notification-container"></div>
	<div id="main-content" style="overflow-y: hidden ! important;overflow-x: hidden ! important;
            background-image: url('<?= $serviceImg ?>');background-size:contain;background-repeat: no-repeat;
			background-position: top;"><!-- / not of primary and secondary as it will be true if all its keys would be having a false value. -->
	  <div id="dedicated-buy-np-container" >
		<div class="banner-section mw">
		  <div class="row">
	      <div class="col-lg-9 col-md-9 col-sm-12">
		  <div class="image-info mw">
			<div class="image-info-inner">
			  <div class="price-details">
				<span class="price-info" >
			  	  <span class="price-display-type">Reliability Score: <?php echo  $serviceProviderData['reliability_score']."/".(4*$serviceProviderData['reliability_count'])." (".$reliabilityScore." % )"; ?></span>
				</span>
				<div class="pp-container">
				  <span class="">Quality Score: <?php echo $quality."/".$qualityTotal." ( ".$qualityScore." % )" ; ?></span>
				</div>
			  </div>
			  <div class="clearfix property-info">
			  	<span class="prifile-img">
			  		<img src="<?=$profilePic;?>" style='max-width: 150px;max-height: 150px;'>
			  	</span>
				<p class="main-text" itemprop="name" style="font-size: 16px;"><?=$serviceProviderData['name']; ?><br/><?=$serviceProviderData['organization']; ?><br/><?=$serviceProviderData['description']; ?>
				</p>
			  </div>
			</div>
		  </div>
		  
	      <section id="carouselSection" style="text-align:center">
            <div id="myCarousel" class="carousel slide">
              <div class="carousel-inner">
            <?php
            $flag = true;
            while ( $photos = mysqli_fetch_array($photosArray)) {
            	if($photos['photo_id']== 0) $image = 1075;
				else $image = $photos['photo_id'] ;
                if($flag){
                 echo "<div  style='text-align:center'  class='item active'>
                        <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$image."' alt='business webebsite template'>
                       </div>";
                }
                else {
                 echo "<div  style='text-align:center'  class='item'>
                        <img src='http://api.file-dog.shatkonlabs.com/files/rahul/".$image."' alt='business themes'>
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
          <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 30px;background-color: #fff;">
          <?php if(isset($_GET['s'])) { 
          	echo "<a class='flat-link' onclick='book(\"".$serviceProviderId."\",\"".$serviceName."\",\"".$location."\");' style='text-decoration:none;'>
                    <div class='flat-img'>
                      <div class='img'  style='background-image:url(\"".$icon."\")'></div>
					</div>
					<div class='name-info'>
					  	<div class='project-info'>".$serviceName."</div>
					</div>
					<div class='loct-info text'></div>
					
					  <span class='value' style='color:#000'>".$servicePrice." 
					    <i class='icon icon-rupee'></i> ".$servicePerHour." <br/>
					    Nagotiable : ".strtoupper($serviceData['negotiable'])."</span>
		            	<br/><br/>
						<span class='btn btn-info'>Book Now</span>
					
				  </a>"; 
                }
               ?>
                <i class="glyphicon glyphicon-star"></i>
                <span style="margin:4px 0 0 10px;position: absolute;">
                 Awesome : <?php echo (isset($marvelous)) ? $marvelous : "0";?></span><hr/>
				<i class="glyphicon glyphicon-heart"></i>
				<span style="margin:4px 0 0 10px;position: absolute;">
				 Good : <?php echo(isset($appreciation)) ? $appreciation : "0";?></span><hr/>
				<i class="glyphicon glyphicon-ok-circle"></i>
				<span style="margin:4px 0 0 10px;position: absolute;">
				 Average : <?php echo (isset($suggestion)) ? $suggestion : "0";?></span><hr/>
				<i class="glyphicon glyphicon-thumbs-down"></i >
				<span style="margin:4px 0 0 10px;position: absolute;">
				 Not Good : <?php echo (isset($complain)) ? $complain : "0";  ?></span>
		  	</div>
		  </div>
          <?php if(isset($_GET['s'])) { ?>
		  <a class="flat-link " style="vertical-align: middle;margin-left: 220px;">
		  	
            <div class='flat-img'>
              <div class='img'  style='background-image:url(<?=$icon ; ?>)'></div>
			</div>
		  
            <a style="text-decoration: none; background:#fff;color:#000;white-space: nowrap; position: absolute;padding: 25px;" onclick='book(<?php echo'"'.$serviceProviderId.'","'.$serviceName.'","'.$location.'"';?>);'>
              <div class='price'>
			    <span class='value'><b><?=$serviceName ;?></b><br/>
			    <?=$servicePrice ;?><i class='icon icon-rupee'></i> <?=$servicePerHour ;?><br/>
			    Nagotiable : <?php echo strtoupper($serviceData['negotiable']);?> <br/>
            	Reliability Score: <?php echo  $serviceProviderData['reliability_score']."/".(4*$serviceProviderData['reliability_count'])." (".$reliabilityScore." % )"; ?><br/>
            	Quality Score: <?php echo $quality."/".$qualityTotal." ( ".$qualityScore." % )" ; ?></span><br/><br/>
				<span class='btn btn-info'>Book Now</span>
			  </div>
			</a> 
		  </a>
		  <?php } ?>
		  <div class="project-info-container">
		    <div class="info-col"><i class="glyphicon glyphicon-star"></i>
			  <div class="info-value">Awesome</div>
			  <div class="info-description"><?php echo (isset($marvelous)) ? $marvelous : "0";?></div>
			</div>
			<div class="info-col"><i class="glyphicon glyphicon-heart"></i>
		  	  <div class="info-value">Good</div>
		  	  <div class="info-description"><?php echo (isset($appreciation)) ? $appreciation : "0";?></div>
			</div>
			<div class="info-col"><i class="glyphicon glyphicon-ok-circle"></i>
		  	  <div class="info-value"><span>Average</span></div>
		  	  <div class="info-description"><?php echo (isset($suggestion)) ? $suggestion : "0";?></div>
			</div>
			<div class="info-col"><i class="glyphicon glyphicon-thumbs-down"></i>
		  	  <div class="info-value">Not Good</div>
		  	  <div class="info-description"><?php echo (isset($complain)) ? $complain : "0";  ?></div>
			</div>
	  	  </div>
		</div>
		
		<div class="clearfix dummy sticky-container" style="height: 60px;"></div>
		  <div class="body-elem">
			<div class="inner-body mw">
			  <div class="main-layout">
				<div class="bordered-card card-cont hide" id="discussions-card"></div>
				<div class="hide-embed" id="similar-card">
				  <div class="bordered-card card-cont mw similar-flat-card">
					<h2 class="header-cont">Comments</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  	<input id="userName" type="text" placeholder="Type your Name">
					  	<input id="userMobile" type="text" placeholder="Type your mobile number"> <br/><br/>
					    <textarea id="comment" type="text" class="form-control inpul-lg" placeholder="Type your message to send..." ></textarea><br/>
                        <button type="submit" id="marvelous" onclick="postComment('marvelous');" class='btn btn-info'><i class="glyphicon glyphicon-star"></i>&nbsp;&nbsp; Awesome</button>
                        <button type="submit" id="appreciation" onclick="postComment('appreciation');" class='btn btn-info'><i class="glyphicon glyphicon-heart"></i>&nbsp;&nbsp; Good</button>
                        <button type="submit" id="suggestion" onclick="postComment('suggestion');" class='btn btn-info'><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;&nbsp;Suggestion</button>
                        <button type="submit" id="complain" onclick="postComment('complain');"  class='btn btn-info'><i class="glyphicon glyphicon-thumbs-down"></i>&nbsp;&nbsp;Complain</button>
	                    <hr/>
					  	
                    <?php
	                    foreach ($comments as $key => $value) {
	                       
		                    echo "<div class='left clearfix'>
		                            
		                            <div class='chat-body'>                                        
		                                <strong >".strtoupper($value['name'])."</strong>
		                                <small class='pull-right text-muted' style='margin-top:-25px;'>
		                                    <i class='glyphicon glyphicon-time'></i> ".$value['creation']."
		                                </small>                                      
		                                <p style='margin-top:10px;'>
		                                    ".$value['feedback']."
		                                </p>
		                            </div>
		                        </div><hr/>" ; 
	                    }
                    ?>
                       
					  </div>
					</div>
				  </div>
				</div>
				<div class="hide-embed" id="similar-card">
				  <div class="bordered-card card-cont mw similar-flat-card">
					<h2 class="header-cont">Other Services through same Service Provider</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  <?php
	                    while ($allServicesOfVendor = mysqli_fetch_array($allServices)) {
	                    	
	                    	if($allRecommendedServices['hourly']=='yes') $perHour = "/ Hour";
	                    	else $perHour ="";
	                    	if($allServicesOfVendor['price']=="") $price = 0;
	                    	else $price = $allServicesOfVendor['price'] ;
	                    	if($allServicesOfVendor['pic_id']== 0) $pic = 1075;
	                    	else $pic = $allServicesOfVendor['pic_id'] ;
	                        echo "<a class='flat-link' onclick='book(\"".$serviceProviderId."\",\"".$allServicesOfVendor['name']."\",\"".$location."\");' style='text-decoration:none;'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$pic.")'></div>
									  
								  	</div>
								  	<div class='name-info'>
									  	<div class='project-info'>".$allServicesOfVendor['name']."</div>
									</div>
	                                <div class='apt-info text'>".$allServicesOfVendor['description']."</div>
									<div class='loct-info text'></div>
									<div class='price'>
									  <span class='value'>".$price." 
									    <i class='icon icon-rupee'></i> ".$perHour." <br/>Nagotiable : ".strtoupper($allServicesOfVendor['negotiable'])."<br/><br/>
									    <span class='btn btn-info'>Book Now</span>
									  </span>
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
	                    	
	                    	if($allRecommendedServices['pic_id']== 0) $pic = 1075;
	                    	else $pic = $allRecommendedServices['pic_id'] ;
	                        echo "<a class='flat-link' href='../service/index.php?load=".$allRecommendedServices['name']."-gurgaon&l=".$location."' style='text-decoration:none;'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$pic.")'></div>
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
	<script src="index_files/bootstrap-datetimepicker.min.js"></script>
	
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
	   	function postComment(type) {
       		
       		$("#complain").attr('disabled','disabled');
		    $("#suggestion").attr('disabled','disabled');
		    $("#appreciation").attr('disabled','disabled');
		    $("#marvelous").attr('disabled','disabled');
	        
	        var comment = replaceAll('\\s', '', $("#comment").val());
	        var name = replaceAll('\\s', '', $('#userName').val());
	        var mobile = $('#userMobile').val();
		    if(comment.length < 5){
		    	alert('Minimum words length is 10');
		    }
			else if(!validatePhone(mobile)){	
				alert("Please enter valid mobile number");
			}
			else if(name.length < 3){ 
				alert("Please enter valid Name");
			}
			else {
				$.ajax({
					type: "POST",
					url: "ajax/insert.php",
					data: "name="+name+"&mobile="+mobile,
					cache: false,
					success: function(result){
						$.ajax({
				            url: 'http://api.wazir.shatkonlabs.com/feedbacks/1/<?= $objectId ?>',
				            type: 'post',
				            dataType: 'json',
				            data: '{"digieye_user_id":"1","feedback":"'+comment+
				            			'","type": "'+type+'","user_id":"'+result+'"}',
				            success: function (feedback) {
				                $('#comment').val("");
								alert("Thanks for valuable feedback ");
								location.reload();
				            }		            
				        });
					}
				});
		    }

		    $("#complain").removeAttr('disabled');
			$("#suggestion").removeAttr('disabled');
			$("#appreciation").removeAttr('disabled');
			$("#marvelous").removeAttr('disabled');
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
		function book(serviceProviderId, serviceId,location) {
			$("#bookServiceProviderId").val(serviceProviderId);
			$("#bookServiceId").val(serviceId);
			$("#userLocation").val(location);
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
			var location = $("#userLocation").val();
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
		            data: '{"root": {"name":"'+bookName+'","mobile":"'+bookMobile+'","requirements":"'
		            		+serviceId+'","user_id": "27","user_type":"customer",'+'"start_datatime":"'
		            		+startDatetime+'","service_type": "direct-service",'+'"remarks": "'+remarks
		            		+' by bt_sp web page","start_time":"'+startHour+'",'+'"end_time":"'+endtime
		            		+'","location":"'+location+'","address":"'+bookAddress+'","priority": "3",'
		            		+'"service_provider_id":"'+serviceProviderId+'"}}',
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
	<script src="index_files/business_ltd_1.0.js"></script>
</body>
</html>