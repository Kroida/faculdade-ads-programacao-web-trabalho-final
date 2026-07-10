/*
===============================================================================
  UniFood - Banco de dados corrigido para UTF-8/utf8mb4
===============================================================================
  Objetivo:
  - Evitar textos quebrados como "pÃ£o", "opÃ§Ã£o", "combinaÃ§Ã£o".
  - Forçar banco, conexão e tabelas para utf8mb4.
  - Atualizar os registros da vitrine caso eles já existam com texto quebrado.
===============================================================================
*/

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET CHARACTER SET utf8mb4;
SET collation_connection = 'utf8mb4_unicode_ci';

/*
As partes comentadas abaixo são para caso você esteja usando XAMPP/MySQL local
com usuário próprio. No Docker, normalmente o docker-compose já cria banco,
usuário e permissões.
*/

CREATE DATABASE IF NOT EXISTS desgraca
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- CREATE USER IF NOT EXISTS 'goku'@'localhost' IDENTIFIED BY '4321';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON desgraca.* TO 'goku'@'localhost';
-- FLUSH PRIVILEGES;

USE desgraca;

ALTER DATABASE desgraca
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(191) NOT NULL,
    email VARCHAR(191) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) NOT NULL DEFAULT 'uploads/profiles/default.png',
    status ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    INDEX idx_users_status (status),
    INDEX idx_users_deleted_at (deleted_at)
) ENGINE = InnoDB
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

ALTER TABLE users
    CONVERT TO CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

INSERT INTO users (
    id,
    name,
    email,
    password_hash,
    profile_image,
    status
) VALUES (
    1,
    'Goku',
    'goku@gmail.com',
    '$2y$10$EnkwPqrtpHnKPwfoU4pegOWP0s4fbQGPi0RFuQSzzFBDB8H9tHs.K',
    'uploads/default/goku.jpg',
    'active'
)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    email = VALUES(email),
    password_hash = VALUES(password_hash),
    profile_image = VALUES(profile_image),
    status = VALUES(status),
    deleted_at = NULL;

CREATE TABLE IF NOT EXISTS featured_sections (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    html_id VARCHAR(80) NOT NULL,
    product_key VARCHAR(80) NOT NULL,
    badge_text VARCHAR(191) NOT NULL,
    title VARCHAR(191) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    combo_price DECIMAL(10,2) NULL,
    image_path VARCHAR(255) NOT NULL,
    image_alt VARCHAR(191) NOT NULL,
    is_light TINYINT(1) NOT NULL DEFAULT 0,
    is_reverse TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_featured_sections_html_id (html_id),
    UNIQUE KEY uq_featured_sections_product_key (product_key),
    INDEX idx_featured_sections_order (display_order),
    INDEX idx_featured_sections_active (is_active)
) ENGINE = InnoDB
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

ALTER TABLE featured_sections
    CONVERT TO CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

INSERT INTO featured_sections (
    html_id,
    product_key,
    badge_text,
    title,
    description,
    price,
    combo_price,
    image_path,
    image_alt,
    is_light,
    is_reverse,
    is_active,
    display_order
) VALUES
(
    'destaque1',
    'biguni',
    'O Mais Pedido com 5 estrelas',
    'BigUni',
    '2 hambúrgueres com alface, cebola, picles e pão com gergelim, e aquele molho especial da marca do palhaço.',
    31.90,
    37.90,
    'src/img/bigmac.jpg',
    'BigUni',
    0,
    0,
    1,
    1
),
(
    'destaque-2',
    'BoxCasalUni',
    'Melhor Custo-Benefício',
    'BoxCasalUni',
    'A combinação perfeita para dividir: 2 hambúrgueres Salada de forma caprichada, com porção de batata.',
    54.90,
    NULL,
    'src/img/casal.webp',
    'BoxCasalUni',
    1,
    1,
    1,
    2
),
(
    'destaque-3',
    'kids',
    'Melhor opção para os nossos pequenos',
    'KidsUni',
    'A opção perfeita para nossos pequenos, pão, carne e queijo com toque da nossa maionese especial.',
    21.90,
    28.90,
    'src/img/Kids.jpg',
    'KidsUni',
    0,
    0,
    1,
    3
),
(
    'destaque-4',
    'MegaUltraUni',
    'Melhor opção para o seu desjejum',
    'MegaUltraUni',
    'A opção perfeita para quebrar o seu jejum, com 4 carnes e muito queijo cheddar.',
    39.90,
    43.90,
    'src/img/mega.jpg',
    'MegaUltraUni',
    1,
    1,
    1,
    4
),
(
    'destaque-5',
    'BrownieUni',
    'Melhor opção de sobremesa',
    'BrownieUni',
    'A opção perfeita para uma sobremesa, pedacinhos de brownie com nossa calda de chocolate à parte.',
    14.90,
    NULL,
    'src/img/brownie.jpg',
    'BrownieUni',
    0,
    0,
    1,
    5
)
ON DUPLICATE KEY UPDATE
    html_id = VALUES(html_id),
    product_key = VALUES(product_key),
    badge_text = VALUES(badge_text),
    title = VALUES(title),
    description = VALUES(description),
    price = VALUES(price),
    combo_price = VALUES(combo_price),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    is_light = VALUES(is_light),
    is_reverse = VALUES(is_reverse),
    is_active = VALUES(is_active),
    display_order = VALUES(display_order);
