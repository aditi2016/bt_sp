<?php

session_start();
include_once 'ajax/functions.php';
$url = explode("-",$_GET['load']);
$serviceProviderName = $url[0];
$serviceProviderId = $url[1];
$cityName = $url[2];
$serviceUrl = explode("-",$_GET['s']);
$serviceNameUrl = $serviceUrl[1];

$service = mysqli_query($dbHandle, "SELECT * FROM services 
                                    WHERE id = '$serviceNameUrl' ;");
$serviceData = mysqli_fetch_array($service);
$icon = "http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceData['pic_id'];
$serviceName = $serviceData['name'];
$userId = 1;
$objectId = 'bt-sp-'.$serviceProviderId;
$serviceProvider = mysqli_query($dbHandle, "SELECT * FROM service_providers 
                                    WHERE id = '$serviceProviderId' ;");
$serviceProviderData = mysqli_fetch_array($serviceProvider);
$profilePic = "http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceProviderData['profile_pic_id'];
$photosArray = mysqli_query($dbHandle, "SELECT photo_id FROM photos WHERE 
                                        service_provider_id = '$serviceProviderId' ;");

$allServices = mysqli_query($dbHandle, "SELECT a.price, a.negotiable, b.name, b.pic_id, b.description
                                            FROM service_provider_service_mapping AS a
                                            JOIN services AS b
                                            WHERE a.service_provider_id = '$serviceProviderId'
                                            AND a.service_id = b.id AND b.status = 'active' ;");

$recommendedServices = mysqli_query($dbHandle, "SELECT a.price, a.negotiable, b.name, b.pic_id, b.description
                                            FROM service_provider_service_mapping AS a
                                            JOIN services AS b
                                            WHERE a.service_id = b.id AND b.status = 'active' 
                                            ORDER BY RAND() LIMIT 4;");

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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="index_files/custom.css" type="text/css" media="screen">	
	<link rel="stylesheet" href="index_files/bootstrap.css" type="text/css" media="screen">	
	<link href="" type="image/png" rel="shortcut icon">
	<link href="" type="image/png" rel="apple-touch-icon">
	<title>service provider</title>
	
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
		  	<label>Start Date</label>
		    <input id="startDate" type="text" placeholder="Enter Starting Date">
		    <label>Start Time</label>
		    <select id= "startTime">    
                <option value='7' selected >7</option>
                <?php
                for ($i=8; $i<20 ; $i++) {
                	echo "<option value=".$i." >".$i."</option>";                 	# code...
                } 
                ?>
            </select>
            <label>Hour</label>
            <select id= "totalHour">    
                <option value='1' selected >1</option>
                <?php
                for ($i=2; $i<13 ; $i++) {
                	echo "<option value=".$i." >".$i."</option>";                 	# code...
                } 
                ?>
            </select><br/><br/>
            <label>Select Service Type</label>
            <select id= "serviceType">    
                <option value='monthly' selected >Monthly</option>
                <option value="on-demand" >On Demand</option>
                <option value="AMC" >AMC</option>
                <option value="direct-service" >Direct Service</option>
            </select>
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
			  <div class="price-details">
				<span class="price-info" >
			  	  <span class="price-display-type">Reliability Score: <?php echo  $serviceProviderData['reliability_score']."/".(4*$serviceProviderData['reliability_count'])." (".$reliabilityScore." % )"; ?></span>
				</span>
				<div class="pp-container">
				  <span class="hide-embed rate">Quality Score: <?php echo $quality."/".$qualityTotal." ( ".$qualityScore." % )" ; ?></span>
				</div>
			  </div>
			  <div class="clearfix property-info">
			  	<span class="prifile-img">
			  		<img src="<?=$profilePic;?>" style='max-width: 150px;max-height: 150px;'>
			  	</span>
				<h1 class="main-text" itemprop="name"><?=$serviceProviderData['name']; ?></h1>
				<h2 class="builder-text">
				  <span itemprop="brand"><?=$serviceProviderData['organization']; ?></span>
				</h2>
				<div class="location-info"><?=$serviceProviderData['description']; ?></div>
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
		  <a class="flat-link " style="vertical-align: middle;margin-left: 200px;">
		  	
            <div class='flat-img'>
              <div class='img'  style='background-image:url(<?=$icon ; ?>)'></div>
			</div>
		  
            <a style="text-decoration: none;white-space: nowrap; position: absolute;padding: 20px;" onclick='book(<?php echo'"'.$serviceProviderId.'","'.$serviceName.'"';?>);'>
              <div class='price'>
			    <span class='value'><?=$serviceName ;?><br/>
            	Reliability Score: <?php echo  $serviceProviderData['reliability_score']."/".(4*$serviceProviderData['reliability_count'])." (".$reliabilityScore." % )"; ?><br/>
            	Quality Score: <?php echo $quality."/".$qualityTotal." ( ".$qualityScore." % )" ; ?></span><br/><br/>
				<span class='btn btn-info'>Book Now</span>
			  </div>
			</a> 
		  </a>
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
	                    	
	                    	if($allRecommendedServices['hourly']=='yes') $perHour = "per Hour";
	                    	else $perHour ="";
	                        echo "<a class='flat-link' onclick='book(\"".$serviceProviderId."\",\"".$allServicesOfVendor['name']."\");' style='text-decoration:none;'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$allServicesOfVendor['pic_id'].")'></div>
									  
								  	</div>
								  	<div class='name-info'>
									  	<div class='project-info'>".$allServicesOfVendor['name']."</div>
									</div>
	                                <div class='apt-info text'>".$allServicesOfVendor['description']."</div>
									<div class='loct-info text'></div>
									<div class='price'>
									  <span class='value'>".$allServicesOfVendor['price']." 
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
	                    	if($allRecommendedServices['hourly']=='yes') $perHour = "per Hour";
	                    	else $perHour ="";
	                        echo "<a class='flat-link' href='../service/index.php?load=".$allRecommendedServices['name']."-gurgaon'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$allRecommendedServices['pic_id'].")'></div>
								  	</div>
								  	<div class='name-info'>
									  	<div class='project-info'>".$allRecommendedServices['name']."</div>
									</div>
	                                <div class='apt-info text'>".$allRecommendedServices['description']."</div>
									<div class='loct-info text'></div>
									<div class='price'>
									  
									  <span class='value'>".$allRecommendedServices['price']." 
									    <i class='icon icon-rupee'></i> ".$perHour." <br/>Nagotiable : ".strtoupper($allRecommendedServices['negotiable'])."</span>
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
	  
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
			var startTime = parseInt($("#startTime").val());
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
			else if(!(startDate.isValidDate())){
				alert('Enter valid date');
				$("#bookService").removeAttr('disabled');
				return false;
			}
			else {
				var startDatetime = startDate+" "+startTime+":00:00";
				var startHour = startTime+":00:00";
				$.ajax({
		            url: 'https://blueteam.in/api/service_request',
		            type: 'post',
		            dataType: 'json',
		            data: '{"root": {"name":"'+bookName+'","mobile":"'+bookMobile+'","location":"",'+
				            	'"requirements":"'+serviceId+'","user_id": "1","user_type":"customer",'+
		                        '"start_datatime": "'+startDatetime+'","service_type": "'+serviceType+'",'+
		                        '"remarks": "'+remarks+' by bt_sp web page","start_time":"'+startHour+'",'+
		                        '"end_time":"'+endtime+'","address":"'+bookAddress+'","priority": "3",'+
		                        '"service_provider_id":"'+serviceProviderId+'"}}',
		            success: function (feedback) {
		                
						alert("Your request has been send.\n We will connect with you soon.");
						console.log(feedback);
						

		            }		            
		        });
		    }
		    $("#bookService").removeAttr('disabled');
		}
	</script>
	
	<script>
	  $( function() {
	    $( "#startDate" ).datepicker({dateFormat:"yy-mm-dd"});
	  } );
	</script>
	<script src="index_files/business_ltd_1.0.js"></script>
</body>
</html>