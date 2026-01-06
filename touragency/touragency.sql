-- База: touragency
CREATE DATABASE IF NOT EXISTS touragency DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE touragency;

-- Таблица users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица destinations
CREATE TABLE IF NOT EXISTS destinations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица reservations
CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  destination_id INT NOT NULL,
  date_from DATE NOT NULL,
  date_to DATE NOT NULL,
  guests INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Тестови данни: админ и потребител
INSERT INTO users (full_name, email, password, role) VALUES
('Admin Example','admin@example.com', '$2y$10$...............................................', 'admin'),
('Ivan Petrov','ivan@example.com', '$2y$10$...............................................', 'user');

-- ЗАБЕЛЕЖКА: горните password hash стойности заменете с резултат от password_hash('парола', PASSWORD_DEFAULT).
-- Примерни дестинации
INSERT INTO destinations (title, description, price, image) VALUES
('Атина, Гърция', '3 дни в древния град - обиколка на Акропола и свободно време.', 249.00, 'assets/images/athens.jpg'),
('София - преживяване', 'Уикенд в София с културна програма.', 99.00, 'assets/images/sofia.jpg'),
('Плажове на Родос', '7 дни на плажа, хотел 4*, с трансфери и закуски.', 599.00, 'assets/images/rodos.jpg');

-- Примерна резервация
INSERT INTO reservations (user_id, destination_id, date_from, date_to, guests) VALUES
(2, 1, '2025-12-15', '2025-12-18', 2);

UPDATE users
SET password = '$2y$10$u.UMCUphl.2QbtX54QYSNONOp/uquU7WpSd3E3yhhFVFZqGD4LrlW'
WHERE email = 'admin@example.com';