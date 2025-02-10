<?php
require 'config.php';

// 2. Afficher la base de données avec les utilisateurs :
$sql = "SELECT * FROM benevoles"; // Ici on va chercher tous les utilisateurs de notre table users dans notre base de données.
// C'est une requête SQL qui est stockée

// On met en place la requête
$req = $pdo->query($sql);

// Tant que tu as une réponse on va fetch => on boucle sur toute les lignes
// On peut aussi faire un fetchAll()
while ($rep = $req->fetch())
{
    echo $rep['nom'].'<br>';
}
// Ça fonctionne pour afficher les bénévoles

// TODO : créer l'ajout d'un user avec exec

// Test insertion avec des données
// $pdo->exec("INSERT INTO benevoles VALUES (0, 'Gwenaëlle Bussac', 'test@test.fr', '123456', 'participant')"); // Ça fonctionne
//$pdo->exec("INSERT INTO benevoles VALUES ('Majda Fougou', 'fougou@test.fr', '123456', 'participant')"); // Ça fonctionne pas sans le 0 pour l'auto-incrémentation

// Avec post récupérer les données des inputs
if (isset($_POST['ajouter']))
{
    $name = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // On prépare notre requête
    // TODO : renommer ma varaible sql
    $sqlInsert = $pdo->prepare("INSERT INTO benevoles VALUES (0, :name, :email, :password, :role)");
    // la méthode permet de vérifier avant d'insérer dans la BDD
    $sqlInsert->execute(
        [
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "role" => $role
        ]
    );
    // => Ça fonctionne !
}

// Test pour voir si je récupère bien la valeur dans l'input
//echo $name. $email . $password . $role;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bénévole</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <!-- Barre de navigation -->
    <div class="bg-cyan-200 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
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
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Ajouter un Bénévole</h1>

        <!-- Formulaire d'ajout -->
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            <form action="user_add.php" method="POST"> <!-- Ici on retrouve ce qu'il faut pour le POST et action qui renvoit le fichier qui lance le code pour ajouter un bénévole -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="nom"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nom du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Email du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Mot de passe</label>
                    <input type="password" name="mot_de_passe"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Mot de passe" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Rôle</label>
                    <select name="role"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="participant">Participant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit" name="ajouter"
                            class="w-full bg-cyan-200 hover:bg-cyan-600 text-white py-3 rounded-lg shadow-md font-semibold">
                        Ajouter le bénévole
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

