<?php

use App\Client;

require_once __DIR__ . './../vendor/autoload.php';

$googleClient = new Google_Client();

$client = new Client($googleClient);
$client->checkAccessTokenAndRefresh();

$webmaster = new \Google_Service_Webmasters($client->getGoogleClient());

$data = [];

$search = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest;

for ($i = 60; $i > 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $search->setStartDate(date('Y-m-d', strtotime("-{$i} days")));
    $search->setEndDate(date('Y-m-d', strtotime("-{$i} days")));
    $search->setDimensions(50);
    $search->setAggregationType('auto');
    $result = $webmaster->searchanalytics->query('https://moskva.kg', $search, [])->getRows()[0];
    $data[] = [
        'date'        => $date,
        'clicks'      => $result->clicks,
        'ctr'         => $result->ctr,
        'impressions' => $result->impressions,
    ];

    echo "$i: ({$date}) {$result->clicks}, {$result->ctr}, {$result->impressions}\n";
}

file_put_contents(__DIR__ . '/../data.json', json_encode($data, JSON_PRETTY_PRINT));
