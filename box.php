<?php
require_once "auth.php";
require_login();

$boxError = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_or_fail();

    $nb = trim($_POST["nb"] ?? "");
    $choco = trim($_POST["choco"] ?? "");
    $fruit = trim($_POST["fruit"] ?? "");
    $gouts = $_POST["gout"] ?? [];

    if (!is_array($gouts)) {
        $gouts = [$gouts];
    }

    $gouts = array_values(array_filter(array_map("trim", $gouts), static function ($v) {
        return $v !== "";
    }));

    if ($nb === "" || $choco === "" || $fruit === "") {
        $boxError = "Veuillez remplir tous les choix de la box.";
    } elseif (count($gouts) < 1 || count($gouts) > 3) {
        $boxError = "Selectionnez entre 1 et 3 gouts.";
    } else {
        $prixMap = ["6" => 40, "9" => 50, "12" => 65];
        $prixUnitaire = $prixMap[$nb] ?? 65;
        $produit = sprintf(
            "Box personnalisee - %s pieces - Chocolat: %s - Fruits secs: %s - Gouts: %s",
            $nb,
            $choco,
            $fruit,
            implode(", ", $gouts)
        );

        header("Location: commande.php?produit=" . urlencode($produit) . "&qte=1&prix=" . urlencode((string)$prixUnitaire));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Veloura | Box</title>
  <link rel="stylesheet" href="style.css?v=20260501">
</head>
<body class="box-static-page">
<header class="navbar">
  <h1 class="logo">Veloura</h1>
  <nav>
    <a href="accueil.php">Accueil</a>
    <a href="btq.php">Boutique</a>
    <a href="box.php" class="v">Ma Box</a>
    <a href="logout.php" id="a">Deconnexion</a>
  </nav>
</header>

<section class="S box-static-wrap">
  <div class="main" id="boxform">
    <div class="box">
      <h2>Creez votre Box</h2>
      <p class="subtitle">Personnalisez votre chocolat selon vos envies</p>

      <?php if ($boxError !== ""): ?>
        <p style="color:#8b1f1f;font-weight:700;"><?php echo htmlspecialchars($boxError); ?></p>
      <?php endif; ?>

      <form action="" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

        <label class="tt">Nombre de pieces</label>
        <div class="radio-group">
          <label><input type="radio" name="nb" value="6" required> 6pcs  (40DT)</label>
          <label><input type="radio" name="nb" value="9"> 9pcs  (50DT)</label>
          <label><input type="radio" name="nb" value="12"> 12pcs  (65DT)</label>
        </div>

        <label class="tt">Type de chocolat</label>
        <div class="radio-group">
          <label><input type="radio" name="choco" value="noir" required> Chocolat noir</label>
          <label><input type="radio" name="choco" value="lait"> Chocolat au lait</label>
        </div>

        <label class="tt">Fruits secs</label>
        <div class="radio-group">
          <label><input type="radio" name="fruit" value="oui" required> Oui</label>
          <label><input type="radio" name="fruit" value="non"> Non</label>
        </div>

        <label class="tt">Gout de la farce</label>
        <p class="p">*Selectionnez au maximum 3 gouts</p>
        <div class="radio-group">
          <label><input type="checkbox" name="gout[]" value="fraise"> Fraise</label>
          <label><input type="checkbox" name="gout[]" value="pistache"> Pistache</label>
          <label><input type="checkbox" name="gout[]" value="caramel"> Caramel</label>
          <label><input type="checkbox" name="gout[]" value="framboise"> Framboise</label>
          <label><input type="checkbox" name="gout[]" value="noisette"> Noisette</label>
        </div>

        <button type="submit">Valider ma Box</button>
      </form>
    </div>
  </div>
</section>

<footer class="box-static-footer">
<p>© 2026 Veloura. Tous droits réservés. Fait avec ❤️ et du chocolat.</p>
</footer>

<script>
  window.addEventListener("scroll", function () {
    document.querySelector(".navbar").classList.toggle("scrolled", window.scrollY > 50);
  });
</script>
</body>
</html>
