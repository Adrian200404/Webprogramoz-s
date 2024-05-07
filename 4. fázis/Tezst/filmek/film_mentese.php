<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../bejelentkezes/login.php");
    exit();
}

if (isset($_POST['szerkesztes_submit'])) {
    $film_id = $_POST['film_id'];
    $cim = $_POST['cim'];
    $rendezo = $_POST['rendezo'];
    // A többi adatmező feldolgozása

    $sql = "UPDATE filmek SET cim = '$cim', rendezo = '$rendezo' WHERE id = $film_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: film_lista.php");
        exit();
    } else {
        echo "Hiba történt az adatbázis frissítése során: " . $conn->error;
    }
} else {
    echo "Nem megfelelő hívás.";
}
?>
