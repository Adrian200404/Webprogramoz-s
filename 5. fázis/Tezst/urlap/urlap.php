<?php
session_start();
require_once '../config.php';

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    header("Location: ../bejelentkezes/login.php");
    exit();
}

// Ellenőrizzük, hogy az űrlapot elküldték-e
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ellenőrizzük az űrlap adatokat
    if (!isset($_POST['message']) || empty($_POST['message'])) {
        echo 'Az üzenet mező kitöltése kötelező!';
        exit();
    }

    // Felhasználó neve
    $username = $_SESSION['username'];

    // Űrlap adatainak beolvasása
    $message = $_POST['message'];

    // Az űrlap adatainak tárolása az adatbázisban
    $stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $message);

    if ($stmt->execute()) {
        // Sikeres űrlapfeldolgozás esetén átirányítás a message.php oldalra
        header("Location: urlap.php");
        exit();
    } else {
        echo 'Hiba történt az üzenet beküldése során.';
    }

    $stmt->close();
} else {
    echo 'Hiba történt az űrlap feldolgozása során.';
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icon.jpg">
    <title>Űrlap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Űrlap</h1>
        <form action="submit_form.php" method="post">
            <label for="name">Név:</label>
            <input type="text" id="name" name="name" required>

            <label for="message">Üzenet:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit" name="submit">Küldés</button>
        </form>

        <p>Ugrás a Főoldalra <a href="../fooldal/index.php">Főoldal</a>.</p>
    </div>
</body>
</html>
