CREATE DATABASE p13_users;

USE p13_users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NULL,
    password VARCHAR(255) NULL
);

INSERT INTO users (username, password) VALUES
('admin', 'admin'),
('user1', 'user1'),

ALTER TABLE users ADD hak_akses enum('Admin', 'User') NULL AFTER `password`;

INSERT INTO users (username, password, hak_akses) VALUES ('rafli', 'password', 'Admin')