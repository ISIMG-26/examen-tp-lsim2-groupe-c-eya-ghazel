<?php
require_once "db.php";
require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: veloura.php");
    exit;
}
verify_csrf_or_fail();

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

$stmt = $conn->prepare("SELECT id, nom, prenom, email, password, role FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user["password"])) {
    header("Location: veloura.php?error=invalid");
    exit;
}

session_regenerate_id(true);
$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["nom"] = $user["nom"];
$_SESSION["prenom"] = $user["prenom"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

if ($user["role"] === "admin") {
    header("Location: admin.php");
} else {
    header("Location: accueil.php");
}
exit;
?>
