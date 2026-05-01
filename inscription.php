<?php
require_once "auth.php";
if (is_logged_in()) {
    header("Location: accueil.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloura | Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260501">
</head>
<body>
<header class="navbar">
    <h1 class="logo">Veloura</h1>
</header>

<section class="boxform">
    <div class="login">
        <div class="box">
            <h2>Creer un compte</h2>
            <p class="mini">Rejoignez l'univers Veloura</p>

            <?php if (isset($_GET["error"]) && $_GET["error"] === "email"): ?>
                <p style="color:#8b1f1f;font-weight:700;">Cet email existe deja.</p>
            <?php endif; ?>
            <?php if (isset($_GET["error"]) && $_GET["error"] === "password"): ?>
                <p style="color:#8b1f1f;font-weight:700;">Les mots de passe ne correspondent pas.</p>
            <?php endif; ?>
            <?php if (isset($_GET["error"]) && $_GET["error"] === "password_len"): ?>
                <p style="color:#8b1f1f;font-weight:700;">Le mot de passe doit contenir au moins 6 caracteres.</p>
            <?php endif; ?>

            <form action="signup.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <label>Nom</label>
                <input type="text" name="nom" placeholder="Votre nom" required>

                <label>Prenom</label>
                <input type="text" name="prenom" placeholder="Votre prenom" required>

                <label>Email</label>
                <input type="email" name="email" placeholder="exemple@mail.com" required>

                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="******" required minlength="6">

                <label>Confirmer mot de passe</label>
                <input type="password" name="confirm_password" placeholder="******" required minlength="6">

                <label>Numero de telephone</label>
                <input type="tel" name="tel" placeholder="+216...." required>

                <button type="submit">S'inscrire</button>
            </form>
            <hr>

            <p class="login-link">
                Vous avez deja un compte ? <a href="veloura.php">Se connecter</a>
            </p>
        </div>
    </div>
</section>

<footer>
    <p>© 2026 Veloura. Tous droits réservés. Fait avec ❤️ et du chocolat.</p>
</footer>
</body>
</html>
