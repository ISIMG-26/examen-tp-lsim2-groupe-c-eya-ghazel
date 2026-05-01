<?php
require_once "auth.php";
require_login();
include "db.php";

$produits = $conn->query("SELECT id, nom, prix, image, stock FROM produits ORDER BY id DESC");
$boxList = $conn->query("SELECT id, nom, description, prix, image FROM box ORDER BY id DESC");

$produitsData = [];
if ($produits) {
    while ($r = $produits->fetch_assoc()) {
        $produitsData[] = $r;
    }
}

$boxData = [];
if ($boxList) {
    while ($r = $boxList->fetch_assoc()) {
        $boxData[] = $r;
    }
}

$boxChunks = array_chunk($boxData, 3);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloura | Boutique</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260501">
</head>
<body id="b">
<header class="navbar">
    <h1 class="logo">Veloura</h1>
    <nav>
        <a href="accueil.php">Accueil</a>
        <a href="btq.php" class="v">Boutique</a>
        <a href="box.php">Ma Box</a>
        <a href="logout.php" id="a">Deconnexion</a>
    </nav>
</header>

<section id="s1">
    <h1 class="h1">Tablettes Du Chocolat</h1>

    <div class="main">
        <div class="cards">
            <?php if (count($produitsData) > 0): ?>
                <?php foreach ($produitsData as $row): ?>
                    <div class="container">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars(!empty($row["image"]) ? $row["image"] : "img.jpg"); ?>" class="i" alt="<?php echo htmlspecialchars($row["nom"]); ?>">
                            <div class="d">
                                <h4><?php echo htmlspecialchars($row["nom"]); ?></h4>
                                <h6>Prix: <?php echo number_format((float)$row["prix"], 2); ?> DT</h6>
                                <p class="p1">Stock: <?php echo (int)$row["stock"]; ?></p>
                                <p class="p1">Quantite: <input class="qty-input" type="number" min="1" value="1"></p>
                                <button class="buy-btn" data-product="<?php echo htmlspecialchars($row["nom"]); ?>">Acheter</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun produit disponible.</p>
            <?php endif; ?>
        </div>
    </div>
    <h1 id="h1">Nos Boxs</h1>
    <?php if (count($boxChunks) > 0): ?>
        <?php foreach ($boxChunks as $chunk): ?>
            <div class="main">
                <div class="cards">
                    <?php foreach ($chunk as $row): ?>
                        <div class="container">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars(!empty($row["image"]) ? $row["image"] : "img.jpg"); ?>" class="i" alt="<?php echo htmlspecialchars($row["nom"]); ?>">
                                <div class="d">
                                    <h4><?php echo htmlspecialchars($row["nom"]); ?></h4>
                                    <p class="p1"><?php echo htmlspecialchars($row["description"] ?? ""); ?></p>
                                    <h6>Prix: <?php echo number_format((float)$row["prix"], 2); ?> DT</h6>
                                    <p class="p1">Quantite: <input class="qty-input" type="number" min="1" value="1"></p>
                                    <button class="buy-btn" data-product="<?php echo htmlspecialchars($row["nom"]); ?>">Acheter</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="main">
            <div class="cards">
                <p>Aucune box disponible.</p>
            </div>
        </div>
    <?php endif; ?>
</section>

<footer>
    <p>© 2026 Veloura. Tous droits réservés. Fait avec ❤️ et du chocolat.</p>
</footer>

<script>
window.addEventListener("scroll", function () {
    document.querySelector(".navbar").classList.toggle("scrolled", window.scrollY > 50);
});
</script>
<script src="shop.js"></script>
</body>
</html>
