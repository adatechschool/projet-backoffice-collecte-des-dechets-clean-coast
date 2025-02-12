<?php

require "config.php";

// 1. On vérifie que dans l'URL on a l'id du bénévole
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: volunteer_list.php");
    exit;
}

$currentVolunteerId = $_GET['id'];
//echo $currentVolunteerId;

// 2. On récupère le bénévole sélectionné
$stmt = $pdo->prepare("SELECT id, nom, email, role FROM benevoles WHERE id = ?");
$stmt->execute([$currentVolunteerId]);
$currentVolunteer = $stmt->fetch(); // Ici pas bien compris pourquoi ne met pas un "fetchAll()"

// Récupérer l'enum de la table
// Je vérifie le role du bénévole en BDD
//echo $currentVolunteer['role'];
$role = $currentVolunteer['role'];

// Plus loin dans le code au niveau du select.
// On a fait une condition ternaire pour afficher le role
// Pour que la condition ternaire soit plus claire, c'est comme si on avait écrit :
//if ($role === 'participant') {
//    echo 'selected';
//} else {
//    echo '';
//}
// Attention le selected est "associé" au texte mis en dur dans le html "Admin"

// TODO: Ajouter une gestion des erreur s'il ne trouve pas le bénévole dans le tableau
// Ajoute une condition qui vérifie l'URL

// 3. On met à jour le bénévole => EDITION
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt_update = $pdo->prepare("UPDATE benevoles SET nom = ?, email = ?, role = ? WHERE id = ?");
    $stmt_update->execute([$nom, $email, $role, $currentVolunteerId]);

    // Ici sert à rediriger quand le formulaire est envoyé
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
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Modifier un Bénévole</h1>

        <!-- Formulaire de modification -->
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="nom"
                           value="<?= htmlspecialchars($currentVolunteer['nom']) ?>"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nom du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($currentVolunteer['email']) ?>"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Email du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Rôle</label>
                    <select name="role"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="participant" <?php echo ($role === 'participant') ? 'selected' : ''; ?> >Participant</option>
                        <option value="admin" <?php echo ($role === 'admin') ? 'selected' : ''; ?> >Admin</option>
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

