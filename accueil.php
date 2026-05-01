<?php
require_once "auth.php";
require_login();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloura | Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260501">
</head>
<body id="b">
    <header class="navbar">
        <h1 class="logo">Veloura</h1>
        <nav>
            <a href="accueil.php" class="v">Accueil</a>
            <a href="btq.php">Boutique</a>
            <a href="box.php"> Ma Box</a>
            <a href="logout.php" id="a">Deconnexion</a>
        </nav>
    </header>

    <section class="SS">
        <div class="div1">
            <p class="mini">ARTISANAL · PREMIUM · PERSONNALISE</p>
            <h1>La ou le <br><span>Chocolat</span><br> rencontre <br><span>l'elegance.</span></h1>
            <p class="desc">Creez votre box sur mesure avec les meilleurs chocolats du monde.</p>
            <div>
                <button onclick="window.location.href='btq.php'">Explorer la Boutique</button>
                <button onclick="window.location.href='box.php'">Creer Ma Box</button>
            </div>
        </div>

        <div class="side">
            <h2 class="best">Best-Sellers des Box</h2>
            <div class="cards">
                <div class="container">
                    <div class="card">
                        <img src="vel.jpeg" class="i">
                        <div class="d">
                            <h4>Box Veloura</h4>
                            <p class="p1">12 pieces a 70 DT</p>
                            <button class="buy-btn" data-product="Box Veloura">Acheter</button>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="card">
                        <img src="BG.jpeg" class="i">
                        <div class="d">
                            <h4>Box Premium</h4>
                            <p class="p1">12 pieces a 50 DT</p>
                            <button class="buy-btn" data-product="Box Premium">Acheter</button>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="card">
                        <img src="CHOCO.jpeg" class="i">
                        <div class="d">
                            <h4>Box Velvet</h4>
                            <p class="p1">9 pieces a 40 DT</p>
                            <button class="buy-btn" data-product="Box Velvet">Acheter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
