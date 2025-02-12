<?php
require 'config.php';

// Vérifier si un ID est fourni dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: volunteer_list.php");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations du bénévole
$stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = ?");
$stmt->execute([$id]);
$benevole = $stmt->fetch();

if (!$benevole) {
    header("Location: volunteer_list.php");
    exit;
}

// Mettre à jour le bénévole
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier si toutes les données sont présentes
    if (isset($_POST["id"], $_POST["nom"], $_POST["email"], $_POST["role"])) {
        $id = $_POST["id"];
        $nom = trim($_POST["nom"]);
        $email = trim($_POST["email"]);
        $role = trim($_POST["role"]);

        // Vérifier que l'ID est un entier valide
        if (!is_numeric($id)) {
            die("ID invalide.");
        }
    }

        // Exécuter la mise à jour
        $stmt = $pdo->prepare("UPDATE benevoles SET nom = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $role, $id]);

    
        // Redirection après la mise à jour
        header("Location: volunteer_list.php");
        exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Bénévole</title>
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
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Modifier un Bénévole</h1>

        <!-- Formulaire de modification -->
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            <form action="volunteer_edit.php?id=<?= $id ?>" method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">
                

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($benevole['nom']) ?>"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nom du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($benevole['email']) ?>"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Email du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Rôle</label>
                    <select name="role"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="participant" <?= ($benevole['role'] === 'participant') ? 'selected' : '' ?>>Participant</option>
                        <option value="admin" <?= ($benevole['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
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
