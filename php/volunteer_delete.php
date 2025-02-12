<?php

require 'config.php';

$currentVolunteerId = $_GET['id'];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM benevoles WHERE id = ?");

    if ($stmt->execute([$currentVolunteerId])) {
        header("Location: volunteer_list.php?success=1");
        exit();
    } else {
        echo "Erreur lors de la suppression";
    }

} else {
    echo "ID invalide";
}
?>

