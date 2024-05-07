<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['username'])) {
   header("Location: ../bejelentkezes/login.php")
    exit();
}

if (isset($_GET['id'])) {
    $film_id = $_GET['id'];

    // Először törölni kell az összes értékelést, ami ehhez a filmhez tartozik
    $delete_ertekeles_query = "DELETE FROM film_ertekeles WHERE film_id = $film_id";
    if ($conn->query($delete_ertekeles_query) === TRUE) {
        // Ezután törölhetjük magát a filmet
        $delete_film_query = "DELETE FROM filmek WHERE id = $film_id";
        if ($conn->query($delete_film_query) === TRUE) {
            header("Location: film_lista.php");
            exit();
        } else {
            echo "Hiba történt az adatbázis film törlése során: " . $conn->error;
        }
    } else {
        echo "Hiba történt az adatbázis értékelések törlése során: " . $conn->error;
    }
} else {
    echo "Nem megfelelő hívás.";
}
?>

