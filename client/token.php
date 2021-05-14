<?php
function testToken(string $tokenAccess, string $refreshToken, string $url, string $clientId, string $clientSecret)
{
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $tokenAccess"
	]);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($request);
	curl_close($request);
	$response = json_decode($response, true);

	if (isset($response['error'])) {
		if ($response['error']['code'] == 401) {
			return getNewToken($refreshToken, $clientId, $clientSecret);
		}
	}

	return $tokenAccess;
}

function getNewToken(string $refreshToken, string $clientId, string $clientSecret) {
	$fieldsPost = [
		"client_id" => $clientId,
		"client_secret" => $clientSecret,
		"refresh_token" => $refreshToken,
		"grant_type" => "refresh_token"
	];
	$data = http_build_query($fieldsPost);
	$request = curl_init("https://oauth2.googleapis.com/token");
	curl_setopt($request, CURLOPT_POST, true);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($request, CURLOPT_POSTFIELDS, $data);
	$response = curl_exec($request);
	curl_close($request);
	$response = json_decode($response, true);

	if (isset($response['access_token'])) {
		return $response['access_token'];
	}

}

function formatResponse(array $response, string $newToken)
{
	return json_encode([
		"data" => $response,
		"token" => $newToken,
	]);
}
?>
