CREATE TABLE IF NOT EXISTS events (
	title VARCHAR(20) NOT NULL,
	description VARCHAR(255),
	api_response JSON NOT NULL,
	-- Timestamps
	created_at DATETIME NOT NULL
);

INSERT INTO events (title, description, api_response, created_at) VALUES (?, ?, ?, ?);

SELECT * FROM events WHERE JSON_EXTRACT(api_response, '$.id') = ?;
