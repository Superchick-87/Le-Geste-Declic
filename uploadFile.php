<?php
// Dossier de destination pour l'upload
// $uploadDir = 'images/';

// // Vérifie si le fichier a bien été uploadé via POST
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
//     $file = $_FILES['file'];

//     // Vérifier s'il n'y a pas d'erreur dans l'upload
//     if ($file['error'] !== UPLOAD_ERR_OK) {
//         echo "Erreur lors du téléchargement du fichier.";
//         exit;
//     }

//     $fileName = basename($file['name']);  // Le nom du fichier uploadé
//     $filePath = $uploadDir . $fileName;   // Le chemin complet du fichier sur le serveur

//     // Si un fichier avec le même nom existe déjà, on le renomme
//     if (file_exists($filePath)) {
//         // Ajouter un suffixe au nom du fichier pour éviter l'écrasement
//         $fileName = pathinfo($file['name'], PATHINFO_FILENAME) . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
//         $filePath = $uploadDir . $fileName;  // Nouveau chemin avec le nom modifié
//     }

//     // Déplacer le fichier uploadé vers le dossier de destination
//     if (move_uploaded_file($file['tmp_name'], $filePath)) {
//         echo "Fichier téléchargé et enregistré sous le nom : $fileName";
//     } else {
//         echo "Erreur lors de l'enregistrement du fichier.";
//     }
// } else {
//     echo "Aucun fichier reçu.";
// }


// Dossier de destination pour l'upload
$uploadDir = 'images/';

// Nom du fichier fixe
$fileName = 'Slider_Declic.ai';
$filePath = $uploadDir . $fileName;

// Vérifie si le fichier a bien été uploadé via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Vérifier s'il n'y a pas d'erreur dans l'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erreur lors du téléchargement du fichier.";
        exit;
    }

    // Déplacer le fichier uploadé vers le dossier de destination avec le nom fixe
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo "Fichier téléchargé et enregistré sous le nom : $fileName";
    } else {
        echo "Erreur lors de l'enregistrement du fichier.";
    }
} else {
    echo "Aucun fichier reçu.";
}
?>
