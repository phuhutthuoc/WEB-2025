-- SQL schema for UNIKOON simple news system
CREATE DATABASE IF NOT EXISTS unikoon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE unikoon;

CREATE TABLE IF NOT EXISTS news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  excerpt TEXT,
  content TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create default admin user: username 'admin' password 'admin123' (you should change it)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$e0NR8yYgI6h6rC1p2JQY6O6u7n8YB1Vd7yq8bK1yEwJqv3wq9dF9G');
-- The above hash is password_hash('admin123', PASSWORD_DEFAULT)
