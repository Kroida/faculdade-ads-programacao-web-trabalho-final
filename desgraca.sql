CREATE DATABASE IF NOT EXISTS desgraca
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'goku'@'localhost' IDENTIFIED BY '4321';

GRANT SELECT, INSERT, UPDATE, DELETE ON desgraca.* TO 'goku'@'localhost';

FLUSH PRIVILEGES;

USE desgraca;

CREATE TABLE users (
  id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name           VARCHAR(191)    NOT NULL,
  email          VARCHAR(191)    NOT NULL,
  password_hash  VARCHAR(255)    NOT NULL,
  status         ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
  created_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at     TIMESTAMP       NULL DEFAULT NULL,

  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email),
  INDEX idx_users_status (status),
  INDEX idx_users_deleted_at (deleted_at)
) ENGINE=InnoDB;

-- USE desgraca;
-- SHOW TABLES;
-- DESCRIBE users;