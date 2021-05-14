<?php

require_once "token.php";

$token = $_POST["token"];
$refresh_token = $_POST["refresh"];
$calendar_id = $_POST["calendarId"];
$reason = $_POST["reason"];
$CLIENT_ID = "561037342448-7698atk72j4ma0jb7gsbr1vdl698q61r.apps.googleusercontent.com";
$CLIENT_SECRET = "CsoDNyz1KpqU5v6hOgQ-BXnc";
$list_events = "https://www.googleapis.com/calendar/v3/calendars/$calendar_id/events";
$create_events = "https://www.googleapis.com/calendar/v3/calendars/$calendar_id/events";

function listEvents(string $url, string $tokenAccess)
{
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $tokenAccess"
	]);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($request);
	curl_close($request);
	$response = json_decode($response, true);
	$data = [
		"calendarTimeZone" => $response["timeZone"],
		"events" => $response["items"],
		"reminders" => $response["defaultReminders"]
	];
	echo formatResponse($data, $tokenAccess);
}

function createEvent(string $url, string $tokenAccess, array $values)
{
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $tokenAccess"
	]);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($request, CURLOPT_POST, true);
	curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($values));
	$response = curl_exec($request);
	$response = json_decode($response, true);
	echo formatResponse($response, $tokenAccess);
}

$token = testToken($token, $refresh_token, $list_events, $CLIENT_ID, $CLIENT_SECRET);

if ($reason == "list") {
	listEvents($list_events, $token);
}

if ($reason == "insert") {
	$attendees = explode(",", $_POST["attendees"]);
	$data = [
		"sendUpdates" => $_POST["notify"] == "true" ? "all" : "none",
		"start" => strtotime($_POST["start"]),
		"end" => strtotime($_POST["end"]),
		"description" => $_POST["desc"],
		"summary" => $_POST["title"],
		"attendees" => $attendees,
		// "attendees.email" => $attendees
	];
	createEvent($create_events, $token, $data);
}

?>
