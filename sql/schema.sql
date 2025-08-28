-- sql/schema.sql
CREATE DATABASE IF NOT EXISTS nextintech
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;
USE nextintech;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
-- ALTER TABLE users ADD remember_token VARCHAR(255) NULL; Add at last by going on database
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo user (password: demo123)

-- INSERT INTO users (fullname, email, password, phone) VALUES (
--  'Demo User',
--  'demo@example.com',
--  '$2y$10$W8gA1QF3qj6fWb2o3yq3UuT4bV3xG7gQ7oR9bq3pYxYl3v/0oX5Ci',
--  '+1000000000'
-- ); 

