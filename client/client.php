<?php

$access_token = $_POST['access_token'];

$request = curl_init('https://www.googleapis.com/calendar/v3/users/me/calendarList');
$header = ['Authorization: Bearer ' . json_decode($access_token)];

curl_setopt($request, CURLOPT_HTTPHEADER,  $header);
$response = curl_exec($request);
curl_close($request);
echo json_encode($response);
