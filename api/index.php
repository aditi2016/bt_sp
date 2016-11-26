<?php

require_once "header.php";

include 'db.php';
require 'Slim/Slim.php';

//sms lib
require_once "includes/sms.php";

//candidates resource

require_once "resources/auth/postUserAuth.php";

//service provide resource
require_once "resources/service_provider/getServiceProviderByType.php";
require_once"resources/service_provider/insertServiceProvider.php";
require_once"resources/service_provider/updateServiceProvider.php";
require_once"resources/service_provider/getServiceProvider.php";



require_once"resources/services/getAllServices.php";
require_once"resources/services/getHotServices.php";
require_once"resources/services/getAllServicesByCategory.php";
require_once"resources/service_provider/getAllServiceProviders.php";

//search resource

require_once "resources/search/search.php";

// service provider invoice
require_once "resources/service_provider/invoice/insertServiceProviderInvoice.php";
require_once "resources/service_provider/invoice/getServiceProviderInvoice.php";

// service provider feedback request apia
require_once "resources/service_provider/feedback_request/insertServiceProviderFeedbackRequest.php";

//app
require_once "app.php";




?>