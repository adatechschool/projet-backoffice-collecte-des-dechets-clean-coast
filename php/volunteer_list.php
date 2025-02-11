<?php
// Connexion à la BDD :
require 'config.php';

// 1. Afficher les bénévoles (nom, email et role)
// J'affiche tous les bénévoles
$sql_all_benevoles = "SELECT nom, email, role FROM benevoles";

$req = $pdo->query($sql_all_benevoles); // Tous les bénévoles

// TODO: 2. Pouvoir modifier un élément dans la table
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bénévoles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation -->
    <div class="bg-cyan-800 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li>
                <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole
                </a>
            </li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-cogs mr-3"></i> Mon compte</a></li>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Liste des Bénévoles</h1>

        <!-- Tableau des bénévoles -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nom</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rôle</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                <tr class="hover:bg-gray-100 transition duration-200">
                    <?php
                    if ($req) {
                        while($benevole = $req->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr class='hover:bg-gray-100 transition duration-200'>";
                            // On affiche les données en les protégeant avec htmlspecialchars
                            echo "<td class='py-3 px-4'>" . htmlspecialchars($benevole['nom']) . "</td>";
                            echo "<td class='py-3 px-4'>" . htmlspecialchars($benevole['email']) . "</td>";
                            echo "<td class='py-3 px-4'>" . htmlspecialchars($benevole['role']) . "</td>";
                            echo "<td class='py-3 px-4 flex space-x-2'>
                                <a href='volunteer_edit.php' 
                                   class='bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200'>
                                    ✏️ Modifier
                                </a>
                                <a href='#' 
                                   class='bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200'>
                                    🗑️ Supprimer
                                </a>
                            </td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>