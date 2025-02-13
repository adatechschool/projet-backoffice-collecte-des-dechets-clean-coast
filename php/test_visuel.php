<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test visuel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Liste des collectes TEST</h1>

        <!-- Tableau des collectes passées -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Lieu de la collecte</th>
                    <th class="py-3 px-4 text-left">Quantité de déchets collectés</th>
                    <th class="py-3 px-4 text-left">Date</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4">Paris</td>
                        <td class="py-3 px-4">4.5 kg</td>
                        <td class="py-3 px-4">21/12/2024</td>
                        <td class="py-3 px-4 flex space-x-2">
                            <a href="#"
                               class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                                ✏️ Modifier
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>