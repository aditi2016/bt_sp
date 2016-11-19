<?php

require_once "header.php";

include 'db.php';
require 'Slim/Slim.php';


//candidates resource

require_once "resources/auth/postUserAuth.php";

require_once "resources/service_provider/getServiceProviderByType.php";
require_once"resources/service_provider/insertServiceProvider.php";
require_once"resources/service_provider/updateServiceProvider.php";
require_once"resources/services/getAllServices.php";
require_once"resources/services/getAllServicesByCategory.php";
require_once"resources/service_provider/getAllServiceProviders.php";


//app
require_once "app.php";




?>