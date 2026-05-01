<?php
require_once "auth.php";
require_login();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: commande.php");
    exit;
}
verify_csrf_or_fail();

$nom = trim($_POST["nom"] ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$tel = trim($_POST["tel"] ?? "");
$adresse = trim($_POST["adresse"] ?? "");
$email = trim($_POST["email"] ?? "");
$produit = trim($_POST["produit"] ?? "Produit libre");
$quantite = (int)($_POST["quantite"] ?? 1);
$prixUnitaire = (float)($_POST["prix_unitaire"] ?? 0);
$livraison = (float)($_POST["livraison"] ?? 7);
$totalFinal = (float)($_POST["total_final"] ?? 0);
$statut = "en_attente";

if ($quantite < 1) {
    $quantite = 1;
}

if ($nom === "" || $prenom === "" || $tel === "" || $adresse === "" || $email === "") {
    header("Location: commande.php?error=1");
    exit;
}

if ($prixUnitaire > 0) {
    $sousTotal = $prixUnitaire * $quantite;
    if ($totalFinal <= 0) {
        $totalFinal = $sousTotal + $livraison;
    }
    $produit .= sprintf(
        " | PU: %.2f DT | Livraison: %.2f DT | Total: %.2f DT",
        $prixUnitaire,
        $livraison,
        $totalFinal
    );
}

$stmt = $conn->prepare("
    INSERT INTO commandes (nom, prenom, tel, adresse, email, produit, quantite, statut)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssssis", $nom, $prenom, $tel, $adresse, $email, $produit, $quantite, $statut);
$stmt->execute();
$stmt->close();

header("Location: commande.php?ok=1");
exit;
?>
