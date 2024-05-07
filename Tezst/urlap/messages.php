<?php
session_start();

require_once '../config.php';

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    // Ha a felhasználó nincs bejelentkezve, átirányítjuk a bejelentkezési oldalra
    header("Location: ../bejelentkezes/login.php");
    exit();
}

// Felhasználó neve
$username = $_SESSION['username'];

// Ellenőrizzük, hogy van-e üzenet az URL-ben
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Töröljük az üzenetet az adatbázisból
    $stmt_delete = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt_delete->bind_param("i", $delete_id);
    if ($stmt_delete->execute()) {
        // Sikeres törlés után irányítás az üzenet oldalra
        header("Location: messages.php");
        exit();
    } else {
        echo "Hiba történt az üzenet törlése során.";
    }
}

// Lekérdezzük az előző üzeneteket az adatbázisból rendezve dátum szerint csökkenő sorrendben
$stmt = $conn->prepare("SELECT * FROM messages WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icon.jpg">
    <link rel="stylesheet" href="style.css">
    <title>Üzenetek</title>
</head>
<body>
</div>
    <div class="container">
        <h1>Üzenetek</h1>
        <p>Itt láthatóak az üzeneteid:</p>
        <ul>
            <?php
            // Ellenőrizzük, hogy vannak-e üzenetek
            if ($result->num_rows > 0) {
                // Megjelenítjük az előző üzeneteket
                while ($row = $result->fetch_assoc()) {
                    echo "<p><strong>Üzenet:</strong> " . $row['message'] . "</p>";
                    echo "<p><strong>Dátum:</strong> " . $row['created_at'] . "</p>";
                    // Törlés gomb
                    echo "<form action='messages.php' method='get'>";
                    echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
                    echo "<input type='submit' name='delete' value='Törlés'>";
                    echo "</form>";
                    echo "<hr>";
                }
            } else {
                echo '<div style="text-align: center;">';
                echo "Nincsenek korábbi üzeneteid.";
                echo "</div>";
            }

            $stmt->close();
            ?>
        </ul>
        <p>Ugrás a Főoldalra <a href="../fooldal/index.php">Főoldal</a>.</p>
    </div>
</body>
</html>