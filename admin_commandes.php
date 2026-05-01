<?php
require_once "auth.php";
require_admin();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    verify_csrf_or_fail();
    $id = (int)$_POST["delete_id"];
    $stmt = $conn->prepare("DELETE FROM commandes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_commandes.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["status_id"])) {
    verify_csrf_or_fail();
    $id = (int)$_POST["status_id"];
    $statut = $_POST["statut"] ?? "en_attente";
    $allowed = ["en_attente", "validee", "annulee"];
    if (!in_array($statut, $allowed, true)) {
        $statut = "en_attente";
    }

    $stmt = $conn->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
    $stmt->bind_param("si", $statut, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_commandes.php");
    exit;
}

$result = $conn->query("SELECT * FROM commandes ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Commandes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header class="navbar">
        <h1 class="logo">Veloura Admin</h1>
    </header>

    <main class="wrap">
        <div class="top-links">
            <a class="btn-link btn-muted" href="admin.php">Dashboard</a>
            <a class="btn-link btn-primary" href="admin_produits.php">Gerer Produits</a>
            <a class="btn-link btn-primary" href="admin_box.php">Gerer Box</a>
        </div>

        <section class="card">
            <h2>Liste Commandes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Produit</th>
                        <th>Qte</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row["id"]; ?></td>
                            <td>
                                <?php echo htmlspecialchars($row["nom"] . " " . $row["prenom"]); ?><br>
                                <?php echo htmlspecialchars($row["adresse"]); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row["tel"]); ?><br>
                                <?php echo htmlspecialchars($row["email"]); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["produit"]); ?></td>
                            <td><?php echo (int)$row["quantite"]; ?></td>
                            <td>
                                <span class="status <?php echo htmlspecialchars($row["statut"]); ?>">
                                    <?php echo htmlspecialchars($row["statut"]); ?>
                                </span>
                                <form method="POST" class="actions" style="margin-top:6px;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                                    <input type="hidden" name="status_id" value="<?php echo (int)$row["id"]; ?>">
                                    <select name="statut">
                                        <option value="en_attente" <?php echo $row["statut"] === "en_attente" ? "selected" : ""; ?>>en_attente</option>
                                        <option value="validee" <?php echo $row["statut"] === "validee" ? "selected" : ""; ?>>validee</option>
                                        <option value="annulee" <?php echo $row["statut"] === "annulee" ? "selected" : ""; ?>>annulee</option>
                                    </select>
                                    <button class="btn-primary" type="submit">MAJ</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo (int)$row["id"]; ?>">
                                    <button
                                        class="btn-link btn-danger"
                                        type="submit"
                                        data-delete="la commande #<?php echo (int)$row["id"]; ?>"
                                    >
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="admin.js"></script>
</body>
</html>
