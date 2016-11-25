<?php
//input
// url: http://blueteam.in/service/index.php?load=cleaning-1-gurgaon
// url: http://blueteam.in/service_provider/index.php?load=anil%20kumar-1-gurgaon

// Name of service/ service provider
// Id of service
session_start();
$dbHandle = mysqli_connect("localhost","root","redhat111111","blueteam_service_providers");
$url = explode("-",$_GET['load']);
$serviceName = $url[0];
$cityName = $url[2];
$userId = 1;

$service = mysqli_query($dbHandle, "SELECT * FROM service_providers 
                                    WHERE name = '$serviceName' ;");
$serviceData = mysqli_fetch_array($service);
$serviceId = $serviceData['id'];
$objectId = 'bt-sp-'.$serviceId;
$profilePic = "http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceData['pic_id'];
$photosArray = mysqli_query($dbHandle, "SELECT photo_id FROM photos WHERE 
                                        service_provider_id IN (SELECT service_provider_id FROM
                                        service_provider_service_mapping WHERE service_id = '$serviceId') ;");

$allServiceProviders = mysqli_query($dbHandle, "SELECT a.name, a.organization, a.id, a.profile_pic_id, 
											b.price, b.nagotiable, b.hourly FROM service_providers AS a
											JOIN service_provider_service_mapping AS b WHERE 
											a.id = b.service_provider_id AND b.service_id = '$serviceId' ;");

$recommendedServices = mysqli_query($dbHandle, "SELECT a.price, a.nagotiable, b.name, b.pic_id, b.description
                                            FROM service_provider_service_mapping AS a
                                            JOIN services AS b
                                            WHERE a.service_id = b.id AND b.status = 'active' 
                                            ORDER BY RAND() LIMIT 4;");

function httpGet($url){
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false);

    $output=curl_exec($ch);

    curl_close($ch);
    return $output;
}
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

$quality = $marvelous+$appreciation+$suggestion+$complain ;
$qualityTotal = ($marvelous*4)+($appreciation*3)+($suggestion*2)+($complain) ;
$qualityScore = ($quality/$qualityTotal)*100 ;
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
	  <header class="" data-reactroot="" data-reactid="1" data-react-checksum="-85391792">
	  	<div class="city-select" data-reactid="2">
		  <div class="header" data-reactid="3"></div>
		  <i class="icon-close close-city-selection" data-reactid="4"></i>
		  <!-- <div data-reactid="5">
		    <div class="search-city-container" data-reactid="6">
			  <div class="Select is-searchable" data-reactid="7">
			    <div class="Select-control" data-reactid="8">
				  <div class="Select-placeholder" data-reactid="9">Type Your City</div>
				  <div class="Select-input" style="display:inline-block;" data-reactid="10">
				    <input style="width: 2px; box-sizing: content-box;" data-reactid="11">
				    <div style="position: absolute; visibility: hidden; height: 0px; width: 0px; overflow: scroll; white-space: nowrap; font-size: 12px; font-family: sans-serif; font-weight: 400; font-style: normal; letter-spacing: normal;" data-reactid="12">
				    </div>
				  </div>
				  
			    </div>
			  </div>
		    </div>
		    
		  </div> -->
	    </div>
	    <div class="header-group header-main" data-reactid="21">
	      <div class="header-item header-logo" data-reactid="22">
	        <a class="housing-logo" title="blueteam" href="http://blueteam.in/app/" data-reactid="23"></a>
	      </div>
	    </div>
	    
	    <div class="header-search" data-reactid="49">
	      <div class="tag-container header-search-input" data-reactid="50"></div>
	      <i class="icon-search" data-reactid="51"></i>
	    </div>
	  </header>
    </div>
		
	<div class="loading-animation">
		<div class="background-container">
			<div class="apex-loading"></div>
		</div>
		<div class="housing-logo"></div>
		<span class="content"></span>
	</div>
	<div id="notification-container"></div>
	<div id="main-content"><!-- / not of primary and secondary as it will be true if all its keys would be having a false value. -->
	  <div id="dedicated-buy-np-container" data-source="non_quickview" data-uuid="5e353c8965c64df3b546" itemscope="" itemtype="">
		<div class="banner-section mw">
		  
		  <div class="image-info mw">
			<div class="image-info-inner">
			  <div class="price-details">
				<span class="price-info" data-currency="inr" data-value="15400000" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
			  	  <meta itemprop="priceCurrency" content="INR">
				  
				  		  
				</span>
				<div class="pp-container">
				  <span class="hide-embed rate">Quality Score: <?php echo $quality."/".$qualityTotal." ( ".$qualityScore." % )" ; ?></span>
				</div>
			  </div>
			  <div class="clearfix property-info">
			  	<span class="prifile-img"><img src="<?=$profilePic;?>" style='max-width: 150px;max-height: 150px;'></span>
				<h1 class="main-text" itemprop="name"><?=$serviceData['name']; ?></h1>
				
				<div class="location-info"><?=$serviceData['description']; ?></div>
				<!-- <div class="offer-bar-container">
				  <div class="offer-bar">
					<div class="left-cont">
					  <span class="btn offer-btn small tertiary">OFFER</span>
				    </div>
				    <div class="right-cont">
					  <span class="offer-text">Base Rate now@ Rs 11500 per sq ft. Possession Date For ABC-Dec-2016 &amp; D-March-2017</span>
					</div>
				  </div>
				</div> -->
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
		  
		  <div class="project-info-container">
		    <div class="info-col"><i class="glyphicon glyphicon-star"></i>
			  <div class="info-value">Awesome</div>
			  <div class="info-description"><?=$marvelous; ?></div>
			</div>
			<div class="info-col"><i class="glyphicon glyphicon-heart"></i>
		  	  <div class="info-value">Good</div>
		  	  <div class="info-description"><?=$appreciation; ?></div>
			</div>
			<div class="info-col"><i class="glyphicon glyphicon-ok-circle"></i>
		  	  <div class="info-value"><span>Average</span></div>
		  	  <div class="info-description"><?=$suggestion; ?></div>
			</div>
			<div class="info-col"><i class="glyphicon glyphicon-thumbs-down"></i>
		  	  <div class="info-value">Not Good</div>
		  	  <div class="info-description"><?=$complain; ?></div>
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
					  	
                        <textarea id="comment" type="text" class="form-control inpul-lg" placeholder="Type your message to send..." ></textarea><br/>
                        <button type="submit" id="marvelous" onclick="postComment('marvelous');" class='btn btn-info'><i class="glyphicon glyphicon-star"></i></button>
                        <button type="submit" id="appreciation" onclick="postComment('appreciation');" class='btn btn-info'><i class="glyphicon glyphicon-heart"></i></button>
                        <button type="submit" id="suggestion" onclick="postComment('suggestion');" class='btn btn-info'><i class="glyphicon glyphicon-ok-circle"></i></button>
                        <button type="submit" id="complain" onclick="postComment('complain');"  class='btn btn-info'><i class="glyphicon glyphicon-thumbs-down"></i></button>
	                    
					  <hr/>
					  	
                    <?php
                    foreach ($comments as $key => $value) {
                       
	                    echo "<div class='left clearfix'>
	                            <span class='chat-img pull-left'>
	                                <img src='index_files/icon.png'  class='img-circle'/>
	                            </span>
	                            <div class='chat-body'>                                        
	                                <strong >Jack Sparrow</strong>
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
					<h2 class="header-cont">Service Providers</h2>
					<div class="body-cont">
					  <div class="flat-container">
					  <?php
	                    while ($serviceProviders = mysqli_fetch_array($allServiceProviders)) {
	                        echo "<a class='flat-link' href='http://blueteam.in/app/'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$serviceProviders['profile_pic_id'].")'></div>
									  <div class='name-info'>
									  	<div class='project-info'>".$serviceProviders['name']."</div>
									  </div>
								  	</div>
	                                <div class='apt-info text'>".$serviceProviders['organization']."</div>
									<div class='loct-info text'>Natwar Nagar, Jogeshwari East</div>
									<div class='price'>
									  <span class='value'>".$serviceProviders['price']." 
									    <i class='icon icon-rupee'></i> per Hour <br/>Nagotiable : ".strtoupper($serviceProviders['nagotiable'])."</span>
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
	                        echo "<a class='flat-link' href='http://blueteam.in/app/'>
	                                <div class='flat-img'>
	                                  <div class='img'  style='background-image:url(http://api.file-dog.shatkonlabs.com/files/rahul/".$allRecommendedServices['pic_id'].")'></div>
									  <div class='name-info'>
									  	<div class='project-info'>".$allRecommendedServices['name']."</div>
									  </div>
								  	</div>
	                                <div class='apt-info text'>".$allRecommendedServices['description']."</div>
									<div class='loct-info text'>Natwar Nagar, Jogeshwari East</div>
									<div class='price'>
									  
									  <span class='value'>".$allRecommendedServices['price']." 
									    <i class='icon icon-rupee'></i> per Hour <br/>Nagotiable : ".strtoupper($allRecommendedServices['nagotiable'])."</span>
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
						<div class="pull-left seller-count">Sellers (1)</div>
						<div class="navbar pull-right" data-length="0">
					 	  <div class="disabled icon-arrow-left left-nav nav" data-action="prev"></div>
						  <div class="icon-arrow-right nav right-nav" data-action="next"></div>
						</div>
					  </div>
					  <div class="all-person-holder">
						<div class="all-person-translator">
						  <div class="person-container selected" data-id="16301c17-a405-4178-b4a1-0135c2bb7708" data-type="Developer">
							<img class="img normal" src="index_files/icon.png" alt="Spenta Group logo">
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
	  <script type="text/javascript">
	  	function getInTouch() {
	  		$("#getInTouch").attr('disabled','disabled');
	  		var mobile = $('#inputContact').val();
	  		alert(mobile);
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
	        var feedback = {
	            "digieye_user_id":"1",
	            "feedback":$("#comment").val(),
	            "type": type
	        }

	        var comment = replaceAll('\\s', '', $("#comment").val());
		    if(comment.length < 5){
				alert ('Minimum words length is 10');
			}
			else {

		        $.ajax({
		            url: 'http://api.wazir.shatkonlabs.com/feedbacks/1/<?= $objectId ?>',
		            type: 'post',
		            dataType: 'json',
		            success: function (data) {
		                $('#comment').val("");
						alert("Thanks for valuable feedback ");
		            },
		            data: feedback
		        });
		    }
		    return false;
		    $("#complain").removeAttr('disabled');
			$("#suggestion").removeAttr('disabled');
			$("#appreciation").removeAttr('disabled');
			$("#marvelous").removeAttr('disabled');
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
	  </script>
	<script src="index_files/jquery-1.10.2.js"></script>
	  <!-- BOOTSTRAP SCRIPTS -->
	<script src="index_files/bootstrap.min.js"></script>
	<script src="index_files/business_ltd_1.0.js"></script>
</body>
</html>