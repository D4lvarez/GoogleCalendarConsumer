<?php

class Database {
	protected $user_db;
	protected $password_db;
	protected $database;
	protected $dns;

	public function __construct($host, $db_name, $user_db, $password_db)
	{
		$this->user_db = $user_db;
		$this->password_db = $password_db;
		$this->dns = "mysql:dbname=". $db_name . ";host=" . $host;
		$this->create_connection();
		$this->create_tables();
	}

	private function create_connection()
	{
		try {
			$this->database = new PDO($this->dns, $this->user_db, $this->password_db);
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}

	private function create_tables()
	{
		$statement = "CREATE TABLE IF NOT EXISTS events ( " .
			"title VARCHAR(20) NOT NULL, description VARCHAR(255)," .
			"api_response JSON NOT NULL,created_at DATETIME NOT NULL);";

		try {
			$this->database->query($statement);
		} catch (PDOException $e) {
			echo "An error ocurred: " . $e->getMessage();
		}
	}

	public function insert_data($values)
	{
		$query = "INSERT INTO events (title, description, api_response, created_at) VALUES (?, ?, ?, ?);";
		$statement = $this->database->prepare($query);
		try {
			[$title, $description, $api_response] = $values;
			$created_at = date("Y-m-d H:i:s");
			$statement->execute(array($title, $description, $api_response, $created_at));
		} catch (PDOException $e) {
			echo "An error ocurred: " . $e->getMessage();
		}

	}

	public function select_where($event_id)
	{
		$query = "SELECT * FROM events WHERE JSON_EXTRACT(api_response, '$.id') = ?;";
		$statement = $this->database->prepare($query);
		try {
			$statement->execute(array($event_id));
		}catch (PDOException $e) {
			echo "An error ocurred: " . $e->getMessage();
		}
	}

	public function select_all()
	{}	
}

?>
