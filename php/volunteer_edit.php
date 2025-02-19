<?php
require 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: volunteer_list.php");
    exit;
} 

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT id, nom, email, role FROM benevoles WHERE id = ?");
$stmt->execute([$id]);
$benevole = $stmt->fetch();

if (!$benevole) {
    header("Location: volunteer_list.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $stmt = $pdo->prepare("UPDATE benevoles SET nom = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$nom, $email, $role, $id]);

    header("Location: volunteer_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un bénévole</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation -->
    <div class="bg-cyan-800 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        <ul>
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li><a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-user-plus mr-3"></i>Ajouter un bénévole</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
        </ul>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Modifier un bénévole</h1>

        <!-- Formulaire d'ajout -->
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($benevole['nom']) ?>" required
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nom du bénévole">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($benevole['email']) ?>" required
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Email du bénévole">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Rôle</label>
                    <select name="role"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="participant" <?php if($benevole["role"] === "participant"){echo 'selected';} else{echo '';}?>>Participant</option>
                        <option value="admin" <?php if($benevole["role"] === "admin"){echo 'selected';} else{echo '';}?>>Admin</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-3 rounded-lg shadow-md font-semibold">
                        Modifier un bénévole
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>


