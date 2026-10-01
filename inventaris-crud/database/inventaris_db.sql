CREATE DATABASE IF NOT EXISTS inventaris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS suppliers;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE categories (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE suppliers (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 phone VARCHAR(30),
 address VARCHAR(255),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 category_id INT UNSIGNED NOT NULL,
 supplier_id INT UNSIGNED NOT NULL,
 name VARCHAR(150) NOT NULL,
 sku VARCHAR(50) NOT NULL UNIQUE,
 quantity INT UNSIGNED NOT NULL DEFAULT 0,
 price DECIMAL(15,2) NOT NULL DEFAULT 0,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_products_category FOREIGN KEY(category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT fk_products_supplier FOREIGN KEY(supplier_id) REFERENCES suppliers(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO categories(name) VALUES
("Elektronik"),("Peralatan Kantor"),("ATK"),("Jaringan"),("Aksesoris Komputer");

INSERT INTO suppliers(name,phone,address) VALUES
("PT Teknologi Nusantara","081234567801","Medan, Sumatera Utara"),
("CV Sumber Komputer","081234567802","Jakarta Pusat"),
("UD Maju Bersama","081234567803","Bandung, Jawa Barat"),
("PT Jaringan Indonesia","081234567804","Surabaya, Jawa Timur"),
("CV Digital Mandiri","081234567805","Yogyakarta, DI Yogyakarta");

INSERT INTO products(category_id,supplier_id,name,sku,quantity,price,description) VALUES
(1,1,"Laptop Lenovo IdeaPad","INV-LAP-001",8,8500000,"Laptop untuk pemrograman dan perkuliahan."),
(1,2,"Monitor 24 Inch","INV-MON-002",12,1850000,"Monitor Full HD 24 inch."),
(3,3,"Keyboard Mechanical","INV-KEY-003",20,650000,"Keyboard mechanical USB."),
(4,4,"Router WiFi 6","INV-ROU-004",10,1250000,"Router jaringan dengan WiFi 6."),
(5,5,"Mouse Wireless","INV-MOU-005",25,275000,"Mouse wireless."),
(3,1,"Flashdisk 64GB","INV-FLA-006",30,95000,"Media penyimpanan USB 64GB."),
(2,2,"Printer Laser","INV-PRI-007",5,3200000,"Printer laser untuk kantor.");
