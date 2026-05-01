<?php
require_once "auth.php";
if (is_logged_in()) {
    if (($_SESSION["role"] ?? "client") === "admin") {
        header("Location: admin.php");
    } else {
        header("Location: accueil.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloura</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260501">
</head>
<body class="b">
    <header class="navbar">
        <h1 class="logo">Veloura</h1>
    </header>
    <section class="S">
        <div class="divv">
            <p class="mini">ARTISANAL Â· PREMIUM Â· PERSONNALISE</p>
            <h1>
                La ou le <br>
                <span>Chocolat</span><br>
                rencontre <br><span>l'elegance.</span>
            </h1>
            <p class="desc">
                Decouvrez Veloura, l'art du chocolat reinvente ou chaque creation
                devient une experience d'elegance et de plaisir.
            </p>
        </div>
        <div class="login">
            <div class="box">
                <h2>Se connecter</h2>

                <?php if (isset($_GET["ok"]) && $_GET["ok"] === "signup"): ?>
                    <p style="color:#1f5e1f;font-weight:700;">Inscription reussie. Connectez-vous.</p>
                <?php endif; ?>
                <?php if (isset($_GET["error"]) && $_GET["error"] === "invalid"): ?>
                    <p style="color:#8b1f1f;font-weight:700;">Email ou mot de passe invalide.</p>
                <?php endif; ?>
                <?php if (isset($_GET["error"]) && $_GET["error"] === "login_required"): ?>
                    <p style="color:#8b1f1f;font-weight:700;">Connectez-vous d'abord.</p>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@mail.com" required>
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Se connecter</button>
                    <p class="pp">Vous n'avez pas un compte? <br><a href="inscription.php">Inscrivez-vous</a></p>
                </form>
                <hr>
                <p class="demo">Acces rapide admin</p>
                <div class="demo-box">
                    <div>admin@veloura.tn / admin123</div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p >© 2026 Veloura. Tous droits réservés. Fait avec ❤️ et du chocolat.</p>
    </footer>

    <script>
      window.addEventListener("scroll", function () {
        document.querySelector(".navbar").classList.toggle("scrolled", window.scrollY > 50);
      });
    </script>
</body>
</html>
