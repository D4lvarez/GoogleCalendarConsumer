<?php

$access_token = $_POST['token'];
$refresh_token = $_POST['refresh'];

class ClientCalendar {
	protected $access_token;
	protected $refresh_token;
	protected $header;
	private $client_id = "561037342448-7698atk72j4ma0jb7gsbr1vdl698q61r.apps.googleusercontent.com";
	private $client_secret = "CsoDNyz1KpqU5v6hOgQ-BXnc";

	public function __construct($access_token, $refresh_token)
	{
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
		$this->setHeader();
		$this->renewToken();
	}

	private function renewToken()
	{
		$request = curl_init("https://www.googleapis.com/calendar/v3/users/me/calendarList");
		curl_setopt($request, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($request);
		curl_close($request);
		$response = json_decode($response, true);

		if (isset($response['error'])) {
			if ($response['error']['code'] == 401) {
				$fieldsPost = [
					"client_id" => $this->client_id,
					"client_secret" => $this->client_secret,
					"refresh_token" => $this->refresh_token,
					"grant_type" => "refresh_token"
				];
				$data = http_build_query($fieldsPost);
				$requestRefresh = curl_init("https://oauth2.googleapis.com/token");
				curl_setopt($requestRefresh, CURLOPT_POST, true);
				curl_setopt($requestRefresh, CURLOPT_POSTFIELDS, $data);
				$result = curl_exec($requestRefresh);
				$result = json_decode($result);
				if (isset($result['access_token'])) {
						$this->access_token = $result['access_token'];
				}
			}
		}
	}

	private function setHeader()
	{
		$this->header = ["Authorization: Bearer $this->access_token"];
	}
}

$client = new ClientCalendar($access_token, $refresh_token);
