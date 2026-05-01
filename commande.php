<?php
require_once "auth.php";
require_login();
include "db.php";

$produit = trim($_GET["produit"] ?? "Produit libre");
$quantite = (int)($_GET["qte"] ?? 1);
if ($quantite < 1) {
    $quantite = 1;
}

$livraison = 7.0;
$prixUnitaire = 0.0;
$prixGet = $_GET["prix"] ?? null;
if ($prixGet !== null && is_numeric($prixGet)) {
    $prixUnitaire = (float)$prixGet;
} else {
    $stmt = $conn->prepare("
        SELECT prix FROM produits WHERE nom = ?
        UNION ALL
        SELECT prix FROM box WHERE nom = ?
        LIMIT 1
    ");
    if ($stmt) {
        $stmt->bind_param("ss", $produit, $produit);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row) {
            $prixUnitaire = (float)$row["prix"];
        }
    }
}

$sousTotal = $prixUnitaire * $quantite;
$totalFinal = $sousTotal + $livraison;

$nomSession = $_SESSION["nom"] ?? "";
$prenomSession = $_SESSION["prenom"] ?? "";
$emailSession = $_SESSION["email"] ?? "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloura | Passer Commande</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260501">
</head>
<body>
    <header class="navbar">
        <h1 class="logo">Veloura</h1>
        <nav>
            <a href="accueil.php">Accueil</a>
            <a href="logout.php">Deconnexion</a>
        </nav>
    </header>
    <section id="cmd">
        <div class="login">
            <div class="box">
                <h2 class="tt">Passer votre commande</h2>
                <?php if (isset($_GET["ok"])): ?>
                    <p style="color:#1f5e1f;font-weight:700;">Commande enregistree avec succes.</p>
                <?php endif; ?>
                <?php if (isset($_GET["error"])): ?>
                    <p style="color:#8b1f1f;font-weight:700;">Veuillez verifier les informations du formulaire.</p>
                <?php endif; ?>
                <form class="box-form" action="cmdT.php" method="POST" id="commandeForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <input type="hidden" name="prix_unitaire" id="prix_unitaire" value="<?php echo htmlspecialchars((string)$prixUnitaire); ?>">
                    <input type="hidden" name="livraison" id="livraison" value="<?php echo htmlspecialchars((string)$livraison); ?>">
                    <input type="hidden" name="total_final" id="total_final" value="<?php echo htmlspecialchars((string)$totalFinal); ?>">

                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" placeholder="Votre nom" id="nom" value="<?php echo htmlspecialchars($nomSession); ?>" required>
                    <label for="prenom">Prenom :</label>
                    <input type="text" name="prenom" placeholder="Votre prenom" id="prenom" value="<?php echo htmlspecialchars($prenomSession); ?>" required>
                    <label for="tel">Telephone :</label>
                    <input type="tel" name="tel" placeholder="+216XXXXXXXX" id="tel" required>
                    <label for="adresse">Adresse :</label>
                    <input type="text" name="adresse" placeholder="Votre adresse" id="adresse" required>
                    <label for="email">E-mail :</label>
                    <input type="email" name="email" placeholder="nom@gmail.com" id="email" value="<?php echo htmlspecialchars($emailSession); ?>" required>
                    <label for="produit">Produit :</label>
                    <input type="text" name="produit" id="produit" value="<?php echo htmlspecialchars($produit); ?>" required>
                    <label for="quantite">Quantite :</label>
                    <input type="number" name="quantite" id="quantite" min="1" value="<?php echo (int)$quantite; ?>" required>

                    <label for="prix_affiche">Prix unitaire :</label>
                    <input type="text" id="prix_affiche" value="<?php echo number_format($prixUnitaire, 2); ?> DT" readonly>
                    <label for="livraison_affiche">Livraison :</label>
                    <input type="text" id="livraison_affiche" value="<?php echo number_format($livraison, 2); ?> DT" readonly>
                    <label for="total_affiche">Prix final :</label>
                    <input type="text" id="total_affiche" value="<?php echo number_format($totalFinal, 2); ?> DT" readonly>

                    <div class="checkbox-group">
                        <input type="checkbox" name="pay" id="pay" required>
                        <label for="pay">Paiement a la livraison</label>
                    </div>
                    <button type="submit">Valider</button>
                    <button type="reset">Effacer</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <p>© 2026 Veloura. Tous droits reserves. Fait avec amour et du chocolat.</p>
    </footer>

    <script src="commande.js?v=20260501"></script>
</body>
</html>
