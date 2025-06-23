CREATE TABLE application_db.request_logs (
    id INT UNSIGNED auto_increment NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    uri varchar(255) NOT NULL,
    method varchar(20) NOT NULL,
    headers varchar(255) NOT NULL,
    body LONGTEXT NULL,
    cookies LONGTEXT NULL,
    agent varchar(100) NOT NULL,
    time DATETIME NOT NULL,
    ip varchar(40) NOT NULL,
    CONSTRAINT request_logs_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;