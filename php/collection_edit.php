<?php
require 'config.php';

// Vérifier si un ID de collecte est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: collection_list.php");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations de la collecte
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
$stmt->execute([$id]);
$collecte = $stmt->fetch();

if (!$collecte) {
    header("Location: collection_list.php");
    exit;
}

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

// Récupérer la liste des types de déchets
$stmt_dechets = $pdo->prepare("SELECT DISTINCT type_dechet FROM dechets_collectes");
$stmt_dechets->execute();
$dechets = $stmt_dechets->fetchAll();

// Récupérer la liste des déchets de la collectes
$id_collecte = $_GET['id'];
$current_collecte_dechets = $pdo->prepare("SELECT * FROM dechets_collectes WHERE id_collecte = ?");
$current_collecte_dechets->execute([$id_collecte]);
$collecte_dechets = $current_collecte_dechets->fetchAll();

// Mettre à jour la collecte
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"];
    $type_dechet = $_POST["type_dechet"];
    $quantite_kg = $_POST["quantite"];

    $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
    $stmt->execute([$date, $lieu, $benevole_id, $id]);

    $stmt = $pdo->prepare("UPDATE dechets_collectes SET type_dechet = ?, quantite_kg = ?, id_collecte = ? WHERE id = ?");
    $stmt->execute([$type_dechet, $quantite_kg, $id, $id]);

    header("Location: collection_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <!-- Dashboard -->
    <div class="bg-cyan-800 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        <ul>
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Liste des bénévoles</a></li>
            <li><a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Ajouter un bénévole</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Mon compte</a></li>
        </ul>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier une collecte</h1>

        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($collecte['date_collecte']) ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" value="<?= htmlspecialchars($collecte['lieu']) ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole :</label>
                    <select name="benevole" required class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="" disabled selected>Sélectionnez un·e bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-row">
                    <!-- Type de déchet -->
                    <div class="basis-1/2">
                        <label class="block text-sm font-medium text-gray-700">Type de déchet :</label>
                        <select name="type_dechet" required class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="">Sélectionner un type de déchet</option>
                            <?php foreach ($collecte_dechets as $collecte_dechet): ?>
                                <option value="<?= htmlspecialchars($collecte_dechet['type_dechet']) ?>" <?= $collecte_dechet['id_collecte'] == $collecte['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($collecte_dechet['type_dechet']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Quantité de dechet -->
                    <div class="basis-1/2 ml-6">
                        <label class="block text-sm font-medium text-gray-700">Quantité de déchet (en kg) :</label>
                        <input type="number" min="0" step="0.01" name="quantite" placeholder="<?= htmlspecialchars($collecte_dechet['quantite_kg']) ?>" class="w-full p-2 border border-gray-300 rounded-lg" required>
                    </div>
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
                            <select name="type_dechet[]" required class="w-full p-2 border border-gray-300 rounded-lg">
                                <option value="">Sélectionner un type de déchet</option>
                                <?php foreach ($dechets as $dechet): ?>
                                    <option value="<?= htmlspecialchars($dechet['type_dechet']) ?>">
                                        <?= htmlspecialchars($dechet['type_dechet']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <!-- Quantité de dechet -->
                        <div class="basis-1/2 ml-6">
                            <label class="block text-sm font-medium text-gray-700">Quantité de déchet (en kg) :</label>
                            <input type="number" min="0" step="0.01" name="quantite[]" placeholder="Quantité (kg)"  value="<?= $collecte_dechets['quantite_kg'] ?>" class="w-full p-2 border border-gray-300 rounded-lg" required>
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
                        const existingTypes = Array.from(divInputs.querySelectorAll('select[name="type_dechet[]"]')).map(select => select.value);
                        const existingQuantities = Array.from(divInputs.querySelectorAll('input[name="quantite[]"]')).map(input => input.value);

                        datasSavedDechets = existingTypes.map((type, index) => ({
                            type: type,
                            quantity: existingQuantities[index]
                        }));

                        return datasSavedDechets;
                    }

                    btnValidate.addEventListener("click", addNumberType);
                </script>

                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                    <button type="submit" class="bg-cyan-500 text-white px-4 py-2 rounded-lg">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
