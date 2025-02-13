-- Désactiver temporairement la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Vider les tables dans l'ordre en respectant les dépendances
TRUNCATE TABLE dechets_collectes;  -- Table enfant qui référence collectes
TRUNCATE TABLE collectes;          -- Table enfant qui référence benevoles
TRUNCATE TABLE benevoles;          -- Table parent

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Insertion des données dans la table benevoles
INSERT INTO benevoles (id, nom, email, mot_de_passe, role) VALUES
                                                               (1, 'Alice Dupont', 'alice.dupont@example.com', '5504b4f70ca78f97137ff8ad5f910248', 'admin'),
                                                               (2, 'Bob Martin', 'bob.martin@example.com', '2e248e7a3b4fbaf2081b3dff10ee402b', 'participant'),
                                                               (3, 'Charlie Dubois', 'charlie.dubois@example.com', '9148b120a413e9e84e57f1231f04119a', 'participant');

-- Insertion des données dans la table collectes
INSERT INTO collectes (id, date_collecte, lieu, id_benevole) VALUES
                                                                 (1, '2024-02-01', 'Parc Central', 1),
                                                                 (2, '2024-02-05', 'Plage du Sud', 2),
                                                                 (3, '2024-02-10', 'Quartier Nord', 1),
                                                                 (4, '2025-01-04', 'paris', 3),
                                                                 (6, '3058-06-25', 'lyon', 3),
                                                                 (7, '2029-04-07', 'toulon', 3),
                                                                 (8, '2026-04-25', 'lille', 1),
                                                                 (9, '2028-05-10', 'toulouse', 3),
                                                                 (10, '0008-02-02', 'vertou', 1);

-- Insertion des données dans la table dechets_collectes
INSERT INTO dechets_collectes (id, id_collecte, type_dechet, quantite_kg) VALUES
                                                                              (1, 1, 'plastique', 5.2),
                                                                              (2, 1, 'verre', 3.1),
                                                                              (3, 2, 'métal', 2.4),
                                                                              (4, 2, 'papier', 1.7),
                                                                              (5, 3, 'organique', 6.5),
                                                                              (6, 3, 'plastique', 4.3);