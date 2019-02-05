<?php

use App\Client;

require_once __DIR__ . './../vendor/autoload.php';

$googleClient = new Google_Client();

$client = new Client($googleClient);
$client->checkAccessTokenAndRefresh();

$webmaster = new \Google_Service_Webmasters($client->getGoogleClient());

$data = [];

$search = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest;

for ($i = 10; $i > 0; $i--){
    $search->setStartDate(date('Y-m-d', strtotime("-{$i} days")));
    $search->setEndDate(date('Y-m-d', strtotime("-{$i} days")));
    $search->setDimensions(50);
    $search->setAggregationType('auto');
    $impressions = $webmaster->searchanalytics->query('https://moskva.kg', $search, [])->getRows()[0]->impressions;
    $data[] = $impressions;
}

var_export($data);
