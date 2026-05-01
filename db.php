<?php
$conn = new mysqli("localhost", "root", "", "veloura");

if ($conn->connect_error) {
    die("Erreur connexion: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . "uploads")) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . "uploads", 0777, true);
}

// Initialisation simple pour TP: cree users si absente.
$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(80) NOT NULL,
        prenom VARCHAR(80) NOT NULL,
        email VARCHAR(120) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        tel VARCHAR(30) DEFAULT NULL,
        role ENUM('admin', 'client') NOT NULL DEFAULT 'client',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS produits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(120) NOT NULL,
        prix DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        stock INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS box (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(120) NOT NULL,
        description VARCHAR(255) DEFAULT NULL,
        prix DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$hasBoxImage = $conn->query("SHOW COLUMNS FROM box LIKE 'image'");
if ($hasBoxImage && $hasBoxImage->num_rows === 0) {
    $conn->query("ALTER TABLE box ADD COLUMN image VARCHAR(255) DEFAULT NULL");
}

$prodCountResult = $conn->query("SELECT COUNT(*) AS c FROM produits");
$prodCount = $prodCountResult ? (int)($prodCountResult->fetch_assoc()["c"] ?? 0) : 0;
if ($prodCount === 0) {
    $conn->query("
        INSERT INTO produits (nom, prix, image, stock) VALUES
        ('Tablette Chocolat Blanc Framboise', 30.00, 'fram.jpeg', 100),
        ('Tablette Chocolat Dubai (Lait)', 35.00, 'dubaiLait.jpeg', 100),
        ('Tablette Chocolat Dubai (Blanc)', 35.00, 'dubaiBlanc.jpeg', 100)
    ");
}

$boxCountResult = $conn->query("SELECT COUNT(*) AS c FROM box");
$boxCount = $boxCountResult ? (int)($boxCountResult->fetch_assoc()["c"] ?? 0) : 0;
if ($boxCount === 0) {
    $conn->query("
        INSERT INTO box (nom, description, prix, image) VALUES
        ('Box Veloura', '12 pieces a 70 DT', 70.00, 'vel.jpeg'),
        ('Box Premium', '12 pieces a 50 DT', 50.00, 'BG.jpeg'),
        ('Box Velvet', '12 pieces a 40 DT', 40.00, 'CHOCO.jpeg'),
        ('Box Amour', '12 pieces a 55 DT', 55.00, 'amour.jpeg'),
        ('Box Nouvelle-nee', '30 pieces a 65 DT', 65.00, 'girl.jpeg'),
        ('Box Nouveau-ne', '30 pieces a 65 DT', 65.00, 'boy.jpeg')
    ");
}

$adminEmail = "admin@veloura.tn";
$adminHash = '$2y$12$e8frXQBEthwRLkjktg5InOBG/856FcbC84MKz/f5IkldF49.DlMcO';
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$exists) {
    $stmt = $conn->prepare("
        INSERT INTO users (nom, prenom, email, password, tel, role)
        VALUES ('Admin', 'Veloura', ?, ?, '+21600000000', 'admin')
    ");
    $stmt->bind_param("ss", $adminEmail, $adminHash);
    $stmt->execute();
    $stmt->close();
}
?>
