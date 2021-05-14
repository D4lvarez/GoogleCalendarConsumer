<?php

require_once "token.php";

$token = $_POST["token"];
$refresh_token = $_POST["refresh"];
$CLIENT_ID = "561037342448-7698atk72j4ma0jb7gsbr1vdl698q61r.apps.googleusercontent.com";
$CLIENT_SECRET = "CsoDNyz1KpqU5v6hOgQ-BXnc";
$calendar_list= "https://www.googleapis.com/calendar/v3/users/me/calendarList";


function listCalendars (string $url, string $tokenAccess) {
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $tokenAccess"
	]);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($request);
	curl_close($request);
	$response = json_decode($response, true);
	$data = [
		"calendars" => $response["items"]
	];
	echo formatResponse($data, $tokenAccess);
}

$token = testToken($token, $refresh_token, $calendar_list, $CLIENT_ID, $CLIENT_SECRET);

listCalendars($calendar_list, $token);

?>

