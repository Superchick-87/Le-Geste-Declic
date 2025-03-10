<?php
$uploadDir = 'images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $blocId = $_POST['bloc_id']; // ID du bloc auquel appartient l'image

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = $blocId . "_" . time() . "." . $fileExt; // Renommage avec timestamp
        $destination = $uploadDir . $newFileName;

        // Vérifier s'il existe déjà une image pour ce bloc
        $existingFiles = glob($uploadDir . $blocId . "_*"); // Récupérer tous les fichiers correspondant au blocId

        // Si des fichiers existent, on vérifie si l'un d'eux n'est pas '000.jpg' avant de le supprimer
        foreach ($existingFiles as $existingFile) {
            // Ne pas supprimer '000.jpg'
            if (basename($existingFile) !== '000.jpg') {
                unlink($existingFile); // Supprimer l'image existante
            }
        }

        // Déplacer l'image téléchargée
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            echo json_encode(["success" => true, "bloc" => $blocId, "file" => $newFileName]);
        } else {
            echo json_encode(["success" => false, "error" => "Erreur lors du déplacement du fichier."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Erreur lors du téléchargement."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Requête invalide."]);
}
?>

