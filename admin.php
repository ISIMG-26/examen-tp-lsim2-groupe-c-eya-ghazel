<?php
require_once "auth.php";
require_admin();
require_once "db.php";

$totalProduits = (int)$conn->query("SELECT COUNT(*) AS c FROM produits")->fetch_assoc()["c"];
$totalBox = (int)$conn->query("SELECT COUNT(*) AS c FROM box")->fetch_assoc()["c"];
$totalCommandes = (int)$conn->query("SELECT COUNT(*) AS c FROM commandes")->fetch_assoc()["c"];
$commandesEnAttente = (int)$conn->query("SELECT COUNT(*) AS c FROM commandes WHERE statut = 'en_attente'")->fetch_assoc()["c"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloura | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header class="navbar">
        <h1 class="logo">Veloura Admin</h1>
    </header>

    <main class="wrap">
        <div class="top-links">
            <a class="btn-link btn-muted" href="accueil.php">Voir le site</a>
            <a class="btn-link btn-danger" href="logout.php">Deconnexion</a>
        </div>

        <section class="stats-grid">
            <article class="card stat-card">
                <p class="stat-label">Produits</p>
                <p class="stat-value"><?php echo $totalProduits; ?></p>
            </article>
            <article class="card stat-card">
                <p class="stat-label">Box</p>
                <p class="stat-value"><?php echo $totalBox; ?></p>
            </article>
            <article class="card stat-card">
                <p class="stat-label">Commandes</p>
                <p class="stat-value"><?php echo $totalCommandes; ?></p>
            </article>
            <article class="card stat-card">
                <p class="stat-label">En attente</p>
                <p class="stat-value"><?php echo $commandesEnAttente; ?></p>
            </article>
        </section>

        <section class="card">
            <h2>Gestion rapide</h2>
            <div class="admin-actions">
                <a class="btn-link btn-primary" href="admin_produits.php">Gerer Produits</a>
                <a class="btn-link btn-primary" href="admin_box.php">Gerer Box</a>
                <a class="btn-link btn-primary" href="admin_commandes.php">Gerer Commandes</a>
            </div>
        </section>
    </main>
</body>
</html>
