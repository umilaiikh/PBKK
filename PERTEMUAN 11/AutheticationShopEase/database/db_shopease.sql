CREATE DATABASE IF NOT EXISTS db_shopease CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_shopease;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS shipments, payments, order_items, orders, cart_items, carts, addresses, products, categories, users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(120) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('customer','admin','operator') NOT NULL DEFAULT 'customer',
 phone VARCHAR(30) NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at DATETIME NULL,
 updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 slug VARCHAR(120) NOT NULL UNIQUE,
 description TEXT NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at DATETIME NULL,
 updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 category_id INT NOT NULL,
 name VARCHAR(150) NOT NULL,
 slug VARCHAR(170) NOT NULL UNIQUE,
 description TEXT NULL,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 stock INT NOT NULL DEFAULT 0,
 image VARCHAR(255) NULL,
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at DATETIME NULL,
 updated_at DATETIME NULL,
 CONSTRAINT fk_products_category FOREIGN KEY(category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

CREATE TABLE carts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 created_at DATETIME NULL,
 updated_at DATETIME NULL,
 CONSTRAINT fk_carts_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cart_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 cart_id INT NOT NULL,
 product_id INT NOT NULL,
 quantity INT NOT NULL DEFAULT 1,
 created_at DATETIME NULL,
 updated_at DATETIME NULL,
 UNIQUE KEY uq_cart_product(cart_id,product_id),
 CONSTRAINT fk_ci_cart FOREIGN KEY(cart_id) REFERENCES carts(id) ON DELETE CASCADE,
 CONSTRAINT fk_ci_product FOREIGN KEY(product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE addresses (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 recipient_name VARCHAR(100) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 address TEXT NOT NULL,
 city VARCHAR(100) NOT NULL,
 province VARCHAR(100) NOT NULL,
 postal_code VARCHAR(15) NOT NULL,
 is_default TINYINT(1) NOT NULL DEFAULT 0,
 CONSTRAINT fk_addresses_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 address_id INT NULL,
 order_number VARCHAR(40) NOT NULL UNIQUE,
 order_date DATETIME NOT NULL,
 subtotal DECIMAL(12,2) NOT NULL,
 shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0,
 total DECIMAL(12,2) NOT NULL,
 order_status ENUM('pending_payment','waiting_verification','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending_payment',
 notes TEXT NULL,
 CONSTRAINT fk_orders_user FOREIGN KEY(user_id) REFERENCES users(id),
 CONSTRAINT fk_orders_address FOREIGN KEY(address_id) REFERENCES addresses(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL,
 product_id INT NULL,
 product_name VARCHAR(150) NOT NULL,
 price DECIMAL(12,2) NOT NULL,
 quantity INT NOT NULL,
 subtotal DECIMAL(12,2) NOT NULL,
 CONSTRAINT fk_oi_order FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
 CONSTRAINT fk_oi_product FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE payments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL,
 method VARCHAR(50) NOT NULL DEFAULT 'Transfer Bank',
 amount DECIMAL(12,2) NOT NULL,
 proof_file VARCHAR(255) NULL,
 payment_status ENUM('unpaid','submitted','verified','rejected') NOT NULL DEFAULT 'unpaid',
 verified_by INT NULL,
 verified_at DATETIME NULL,
 CONSTRAINT fk_pay_order FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
 CONSTRAINT fk_pay_user FOREIGN KEY(verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE shipments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL UNIQUE,
 courier VARCHAR(80) NULL,
 tracking_number VARCHAR(100) NULL,
 shipment_status VARCHAR(40) NOT NULL DEFAULT 'waiting',
 shipped_at DATETIME NULL,
 delivered_at DATETIME NULL,
 CONSTRAINT fk_ship_order FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users(name,email,password_hash,role,phone,created_at) VALUES
('Administrator','admin@shopease.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llZ3S7S5x5s0G5KQ3Jz7e','admin','080000000001',NOW()),
('Operator Toko','operator@shopease.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llZ3S7S5x5s0G5KQ3Jz7e','operator','080000000002',NOW()),
('Customer Demo','customer@shopease.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llZ3S7S5x5s0G5KQ3Jz7e','customer','080000000003',NOW());

INSERT INTO categories(name,slug,description,created_at) VALUES
('Elektronik','elektronik','Perangkat elektronik pilihan',NOW()),
('Fashion','fashion','Kebutuhan fashion sehari-hari',NOW()),
('Aksesoris','aksesoris','Aksesoris untuk aktivitasmu',NOW());

INSERT INTO products(category_id,name,slug,description,price,stock,status,created_at) VALUES
(1,'Wireless Headphone','wireless-headphone','Headphone nyaman untuk belajar dan bekerja.',249000,15,'active',NOW()),
(1,'Smart Watch','smart-watch','Jam pintar dengan desain minimalis.',399000,10,'active',NOW()),
(2,'Basic T-Shirt','basic-t-shirt','Kaos basic yang nyaman digunakan.',89000,30,'active',NOW()),
(3,'Canvas Tote Bag','canvas-tote-bag','Tas simpel untuk kegiatan sehari-hari.',59000,25,'active',NOW());
