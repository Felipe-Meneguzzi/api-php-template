CREATE TABLE application_db.users (
    id INT UNSIGNED auto_increment NOT NULL,
    name varchar(100) NOT NULL,
    login varchar(100) NOT NULL,
    password varchar(255) NOT NULL,
    email varchar(100) NOT NULL,
    phone varchar(50) NULL,
    CONSTRAINT users_pk PRIMARY KEY (id),
    CONSTRAINT users_unique UNIQUE KEY (login),
    CONSTRAINT users_unique_1 UNIQUE KEY (email)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;