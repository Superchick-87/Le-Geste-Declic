<?php
function custom_replace($input) {
    $input = str_replace(["\r\n", "\n"], "*$#*", $input);
    $input = str_replace("\x03", " ", $input);
    return $input;
}
$csvFilePath = 'datas/datas.csv';

// Vérifier si la requête est bien POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier si les données existent
    if (!isset($_POST['id'], $_POST['intro'], $_POST['intro_Cap'], $_POST['contenu_Gras'], $_POST['contenu_GrasGris'], $_POST['contenu_Fond_Vert'], $_POST['contenu_Maigre'], $_POST['images'], $_POST['positions'])) {
        die(json_encode(["success" => false, "message" => "Données manquantes !"]));
    }

    // Récupération des données envoyées par AJAX
    $ids = json_decode($_POST['id'], true);
    $intros = json_decode($_POST['intro'], true);
    $introsCap = json_decode($_POST['intro_Cap'], true);
    $contenusGras = json_decode($_POST['contenu_Gras'], true);
    $contenusGrasGris = json_decode($_POST['contenu_GrasGris'], true);
    $contenusFondVert = json_decode($_POST['contenu_Fond_Vert'], true);
    $contenusMaigre = json_decode($_POST['contenu_Maigre'], true);
    $images = json_decode($_POST['images'], true);
    $positions = json_decode($_POST['positions'], true); // Positions des boutons radio

    // Vérification de la validité des données reçues
    if (empty($images)) {
        die(json_encode(["success" => false, "message" => "Erreur : données d'images non reçues !"]));
    }

    if (!is_array($ids) || !is_array($images) || !is_array($positions)) {
        die(json_encode(["success" => false, "message" => "Erreur de décodage JSON !"]));
    }

    // Création d'un tableau pour stocker les données avec l'ID comme clé
    $data = [];
    foreach ($ids as $index => $id) {
        // Vérifier et attribuer l'image
        $imageName = isset($images[$id]) ? $images[$id] : $id . ".jpg";

        // Récupérer la position correspondante
        $position = isset($positions[$index]) ? $positions[$index] : ''; // Utiliser la position du bouton radio sélectionné

        // Remplacer les retours à la ligne par `*$#*`
        $data[] = [
            'id' => $id,
            'intro' => custom_replace($intros[$index]),
            'introCap' => custom_replace($introsCap[$index]),
            'contenuGras' => custom_replace($contenusGras[$index]),
            'contenuGrasGris' => custom_replace($contenusGrasGris[$index]),
            'contenuFondVert' => custom_replace($contenusFondVert[$index]),
            'contenuMaigre' => custom_replace($contenusMaigre[$index]),
            'image' => $imageName,
            'position' => $position // Ajouter la position
        ];
    }

    // Tri des données par ID
    usort($data, function ($a, $b) {
        return $a['id'] - $b['id'];
    });

    // Ouvrir le fichier CSV en mode écriture
    $file = fopen($csvFilePath, 'w');

    // Écrire l'en-tête du CSV
    fputcsv($file, ['Id', 'Intro', 'Intro_Cap', 'Contenu_Gras', 'Contenu_GrasGris', 'Contenu_Fond_Vert', 'Contenu_Maigre', 'Image', 'Position']);

    // Écrire les nouvelles données triées
    foreach ($data as $row) {
        fputcsv($file, [
            $row['id'],
            $row['intro'],
            $row['introCap'],
            $row['contenuGras'],
            $row['contenuGrasGris'],
            $row['contenuFondVert'],
            $row['contenuMaigre'],
            $row['image'],
            $row['position'] // Ajouter la position dans le CSV
        ]);
    }

    fclose($file);

    // Répondre en JSON avec un message de succès
    echo json_encode(['success' => true, 'message' => 'Données enregistrées avec succès !']);
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
