CREATE DATABASE IF NOT EXISTS veloura CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE veloura;

CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS box (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL,
    prenom VARCHAR(80) NOT NULL,
    tel VARCHAR(30) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    email VARCHAR(120) NOT NULL,
    produit VARCHAR(150) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    statut ENUM('en_attente', 'validee', 'annulee') NOT NULL DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL,
    prenom VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tel VARCHAR(30) DEFAULT NULL,
    role ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (nom, prenom, email, password, tel, role)
SELECT 'Admin', 'Veloura', 'admin@veloura.tn', '$2y$12$e8frXQBEthwRLkjktg5InOBG/856FcbC84MKz/f5IkldF49.DlMcO', '+21600000000', 'admin'
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@veloura.tn'
);
