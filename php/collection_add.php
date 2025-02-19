<?php
require 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->query("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

// Récupère la liste des déchets dans la table dechets_collectes et on affiche dans le select
$stmt_dechets = $pdo->query("SELECT DISTINCT type_dechet FROM dechets_collectes");
$stmt_dechets->execute();
$dechets = $stmt_dechets->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"]; 
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"];  // ID du bénévole choisi, modifié ici pour correspondre au formulaire

    // Insérer la collecte avec le bénévole sélectionné dans la table collectes
    $stmt_collecte = $pdo->prepare("INSERT INTO collectes (date_collecte, lieu, id_benevole) VALUES (?, ?, ?)");
    if (!$stmt_collecte->execute([$date, $lieu, $benevole_id])) {
        die('Erreur lors de l\'insertion dans la base de données.');
    }

    // Récupérer l'ID de la collecte qui vient d'être insérée
    $id_collecte = $pdo->lastInsertId();

    $stmt_insert_dechets = $pdo->prepare("INSERT INTO dechets_collectes (type_dechet, quantite_kg, id_collecte) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($_POST['type']); $i++) {
        $type = $_POST['type'][$i];
        $quantite = $_POST['quantite'][$i];

        $stmt_insert_dechets->execute([$type, $quantite, $id_collecte]);
    }
    
    header("Location: collection_list.php?success");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <div class="bg-cyan-800 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li>
                <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole
                </a>
            </li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>

        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Ajouter une collecte</h1>

        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Lieu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Bénévole responsable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole Responsable :</label>
                    <select name="benevole" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] ==  'selected' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Select pour indiquez le nombre de type de déchets -->
                <label class="block text-sm font-medium text-gray-700">Nombre de type de déchet :</label>
                <input type="number" id="input-number-type" min="0" step="0.01" name="nombre-type"  placeholder="Indiquez le nombre de type de déchet à ajouter" class="w-full p-2 border border-gray-300 rounded-lg" required>
                <button id="btn-validate" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow">Valider</button>

                <template id="template-inputs-waste">
                    <div class="flex flex-row">
                        <!-- Type de déchet -->
                        <div class="basis-1/2">
                            <label class="block text-sm font-medium text-gray-700">Type de déchet :</label>
                            <select name="type[]" id="type" required
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionner un type de déchet</option>
                                <?php foreach ($dechets as $dechet): ?>
                                    <option>
                                        <?= htmlspecialchars($dechet['type_dechet']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Quantité de dechet -->
                        <div class="basis-1/2 ml-6">
                            <label class="block text-sm font-medium text-gray-700">Quantité de déchet (en kg):</label>
                            <input type="number" min="0" step="0.01" name="quantite[]" id="quantite" placeholder="Quantité (kg)" class="w-full p-2 border border-gray-300 rounded-lg" required>
                        </div>
                    </div>
                </template>

                <div id="add-inputs"></div>

                <script>
                    const inputNumberType = document.querySelector("#input-number-type");
                    const btnValidate = document.querySelector("#btn-validate");
                    const divInputs = document.querySelector("#add-inputs")
                    const template = document.querySelector("#template-inputs-waste");

                    // Pour suivre le nombre d'inputs à créer
                    let numberOfInputs = 0;
                    // Pour stocker les informations sur les déchets avant modification du nombre d'inputs
                    let datasSavedDechets = [];

                    function addNumberType() {
                        // Empêcher le comportement par défaut du formulaire
                        event.preventDefault();

                        const add = parseInt(inputNumberType.value);
                        numberOfInputs = add;

                        if (add < 0 || isNaN(add) || add > 4) {
                            alert('Veuillez entrer un nombre valide et ne pas dépasser 4 types de déchet');
                            return;
                        }

                        saveDatas();

                        divInputs.innerHTML = '';

                        for (let i = 0; i < numberOfInputs; i++) {
                            const newInput = template.content.cloneNode(true);

                            const selectType = newInput.querySelector('select[name="type[]"]');
                            const inputQuantite = newInput.querySelector('input[name="quantite[]"]');


                            if (datasSavedDechets[i]) {
                                selectType.value = datasSavedDechets[i].type;
                                inputQuantite.value = datasSavedDechets[i].quantity;
                            }

                            divInputs.appendChild(newInput);
                        }

                        fetch('collection_add.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'numberOfInputs=' + numberOfInputs
                        })

                        return numberOfInputs;
                    }

                    function saveDatas() {
                        const existingTypes = Array.from(divInputs.querySelectorAll('select[name="type[]"]')).map(select => select.value);
                        const existingQuantities = Array.from(divInputs.querySelectorAll('input[name="quantite[]"]')).map(input => input.value);

                        datasSavedDechets = existingTypes.map((type, index) => ({
                            type: type,
                            quantity: existingQuantities[index]
                        }));

                        return datasSavedDechets;
                    }

                    btnValidate.addEventListener("click", addNumberType);
                </script>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Annuler</a>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow">
                        ➕ Ajouter
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

</body>
</html>

