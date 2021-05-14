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

function getTimeZone(string $tokenAcces)
{
	$request = curl_init("https://www.googleapis.com/calendar/v3/users/me/settings/timezone");
	curl_setopt($request, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $tokenAcces"
	]);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($request);
	curl_close($request);
	$response = json_decode($response, true);
	return $response["value"];

}

function createEvent(string $url, string $tokenAccess, array $values)
{
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $tokenAccess"
	]);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($request, CURLOPT_POST, true);
	curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($values));
	$response = curl_exec($request);
	$response = json_decode($response, true);
	echo formatResponse($response, $tokenAccess);
}


if ($reason == "list") {
	$token = testToken($token, $refresh_token, $list_events, $CLIENT_ID, $CLIENT_SECRET);
	listEvents($list_events, $token);
}

if ($reason == "insert") {
	$token = testToken($token, $refresh_token, $list_events, $CLIENT_ID, $CLIENT_SECRET);
	$attendees = explode(",", $_POST["attendees"]);
	$userTimezone = getTimeZone($token);

	$data = [
		"sendUpdates" => $_POST["notify"],
		"start" => [
			"dateTime" => $_POST["start"] . ":00",
			"timeZone" => $userTimezone
		],
		"end" => [
			"dateTime" => $_POST["end"] . ":00",
			"timeZone" => $userTimezone
		],
		"description" => $_POST["desc"],
		"summary" => $_POST["title"],
	];
	$members = array();
	foreach($attendees as $user) {
		$members[$user] = ["email" => $user];
		array_merge($members, $members[$user]);
	}
	$data["attendees"] = $members;
	var_dump($data);
	createEvent($create_events, $token, $data);
}

?>
