<?php
session_start();
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
require_once"resources/service_provider/checkReqisteredByMobile.php";

//campaigning request
require_once"resources/service_provider/campaign_request/createCampaignRequest.php";



require_once"resources/services/getAllServices.php";
require_once"resources/services/getServicesById.php";
require_once"resources/services/insertNewServices.php";
require_once"resources/services/getServicesGeo.php";
require_once"resources/services/getHotServices.php";
require_once"resources/services/servicesNeedLookUp.php";
require_once"resources/services/getAllServicesByCategory.php";
require_once"resources/service_provider/getAllServiceProviders.php";

//search resource

require_once "resources/search/search.php";
require_once "resources/search/getSearchResults.php";
require_once "resources/search/getInteresredServices.php";
require_once "resources/category/getCategories.php";

// service provider invoice
require_once "resources/service_provider/invoice/insertServiceProviderInvoice.php";
require_once "resources/service_provider/invoice/getServiceProviderInvoice.php";

// service provider feedback request apia
require_once "resources/service_provider/feedback_request/insertServiceProviderFeedbackRequest.php";

require_once "resources/service_provider/services/insertServices.php";

// cities api
require_once "resources/cities/getAllCities.php";
require_once "resources/cities/areas/getAllCityAreas.php";

//location api
require_once "resources/location/getLocationDetails.php";

//Expanse api

require_once "resources/service_provider/expanses/getServiceProviderExpanses.php";
require_once "resources/service_provider/expanses/insertServiceProviderExpanse.php";
require_once "resources/service_provider/expanses/getExpansesTypes.php";

require_once "resources/mobac/insertCallLogs.php";
require_once "resources/mobac/getCallLogs.php";
require_once "resources/mobac/updateCallLogs.php";
require_once "resources/mobac/getRecentCall.php";


//app
require_once "app.php";




?>