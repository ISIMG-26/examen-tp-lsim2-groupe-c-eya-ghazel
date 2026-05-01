<?php
require_once "auth.php";
require_admin();
include "db.php";

function upload_image_file(string $field): string
{
    if (!isset($_FILES[$field]) || ($_FILES[$field]["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return trim($_POST["image"] ?? "");
    }

    if (($_FILES[$field]["error"] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return trim($_POST["image"] ?? "");
    }

    $tmpPath = $_FILES[$field]["tmp_name"] ?? "";
    $mime = mime_content_type($tmpPath);
    $allowed = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp",
        "image/gif" => "gif"
    ];
    if (!$tmpPath || !isset($allowed[$mime])) {
        return trim($_POST["image"] ?? "");
    }

    $filename = "prod_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $allowed[$mime];
    $targetRelative = "uploads/" . $filename;
    $targetAbsolute = __DIR__ . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $filename;

    if (move_uploaded_file($tmpPath, $targetAbsolute)) {
        return $targetRelative;
    }

    return trim($_POST["image"] ?? "");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    verify_csrf_or_fail();
    $nom = trim($_POST["nom"] ?? "");
    $prix = (float)($_POST["prix"] ?? 0);
    $image = upload_image_file("photo");
    $stock = (int)($_POST["stock"] ?? 0);

    if ($nom !== "" && $prix > 0) {
        $stmt = $conn->prepare("INSERT INTO produits (nom, prix, image, stock) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi", $nom, $prix, $image, $stock);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: admin_produits.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    verify_csrf_or_fail();
    $id = (int)$_POST["delete_id"];
    $stmt = $conn->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_produits.php");
    exit;
}

$result = $conn->query("SELECT * FROM produits ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Produits</title>
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
            <a class="btn-link btn-primary" href="admin_box.php">Gerer Box</a>
            <a class="btn-link btn-primary" href="admin_commandes.php">Gerer Commandes</a>
        </div>

        <section class="card">
            <h2>Ajouter Produit</h2>
            <form method="POST" class="grid" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <input type="text" name="nom" placeholder="Nom du produit" required>
                <input type="number" step="0.01" min="0.01" name="prix" placeholder="Prix (DT)" required data-number>
                <input type="file" name="photo" accept="image/*">
                <input type="number" min="0" name="stock" placeholder="Stock" value="0" data-number>
                <div class="full actions">
                    <button class="btn-primary" type="submit" name="add">Ajouter</button>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Liste Produits</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["nom"]); ?></td>
                            <td><?php echo number_format((float)$row["prix"], 2); ?> DT</td>
            
                            <td><?php echo (int)$row["stock"]; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo (int)$row["id"]; ?>">
                                    <button
                                        class="btn-link btn-danger"
                                        type="submit"
                                        data-delete="le produit <?php echo htmlspecialchars($row["nom"]); ?>"
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
