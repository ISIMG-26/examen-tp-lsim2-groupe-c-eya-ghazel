<?php
require_once "auth.php";
require_admin();
include "db.php";

function upload_box_image_file(string $field): string
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

    $filename = "box_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $allowed[$mime];
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
    $description = trim($_POST["description"] ?? "");
    $prix = (float)($_POST["prix"] ?? 0);
    $image = upload_box_image_file("photo");

    if ($nom !== "" && $prix > 0) {
        $stmt = $conn->prepare("INSERT INTO box (nom, description, prix, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $nom, $description, $prix, $image);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: admin_box.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    verify_csrf_or_fail();
    $id = (int)$_POST["delete_id"];
    $stmt = $conn->prepare("DELETE FROM box WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_box.php");
    exit;
}

$result = $conn->query("SELECT * FROM box ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Box</title>
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
            <a class="btn-link btn-primary" href="admin_commandes.php">Gerer Commandes</a>
        </div>

        <section class="card box-create-card">
            <h2>Ajouter Box</h2>
            <form method="POST" class="grid" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <input type="text" name="nom" placeholder="Nom de la box" required>
                <input type="number" step="0.01" min="0.01" name="prix" placeholder="Prix (DT)" required data-number>
                <input class="full" type="text" name="description" placeholder="Description">
                <input type="file" name="photo" accept="image/*">
                <div class="full actions">
                    <button class="btn-primary" type="submit" name="add">Ajouter</button>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Liste Box</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["nom"]); ?></td>
                            <td><?php echo htmlspecialchars($row["description"]); ?></td>
                            <td><?php echo number_format((float)$row["prix"], 2); ?> DT</td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo (int)$row["id"]; ?>">
                                    <button
                                        class="btn-link btn-danger"
                                        type="submit"
                                        data-delete="la box <?php echo htmlspecialchars($row["nom"]); ?>"
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
