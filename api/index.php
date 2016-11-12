<?php

require_once "header.php";

include 'db.php';
require 'Slim/Slim.php';


//candidates resource

require_once "resources/auth/postUserAuth.php";

require_once "resources/service_provider/getServiceProviderByType.php";




//app
require_once "app.php";




?>