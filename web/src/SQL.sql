# Created Session Table
CREATE TABLE sessions
(
	user VARCHAR(32) NOT NULL
		PRIMARY KEY,
	created DATETIME NOT NULL,
	expires DATETIME NOT NULL,
	session_key BINARY(20) NOT NULL,
	logout_key BINARY(20) NOT NULL
);

