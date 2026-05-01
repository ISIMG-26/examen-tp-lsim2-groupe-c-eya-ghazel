<?php
require_once "db.php";
require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inscription.php");
    exit;
}
verify_csrf_or_fail();

$nom = trim($_POST["nom"] ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm = $_POST["confirm_password"] ?? "";
$tel = trim($_POST["tel"] ?? "");

if ($password !== $confirm) {
    header("Location: inscription.php?error=password");
    exit;
}
if (strlen($password) < 6) {
    header("Location: inscription.php?error=password_len");
    exit;
}

$check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$check->bind_param("s", $email);
$check->execute();
$existing = $check->get_result();
if ($existing->num_rows > 0) {
    $check->close();
    header("Location: inscription.php?error=email");
    exit;
}
$check->close();

$hashed = password_hash($password, PASSWORD_DEFAULT);
$role = "client";

$stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, password, tel, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nom, $prenom, $email, $hashed, $tel, $role);
$stmt->execute();
$userId = $stmt->insert_id;
$stmt->close();

$_SESSION["user_id"] = (int)$userId;
$_SESSION["nom"] = $nom;
$_SESSION["prenom"] = $prenom;
$_SESSION["email"] = $email;
$_SESSION["role"] = "client";

header("Location: btq.php");
exit;
?>
