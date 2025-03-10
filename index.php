<!DOCTYPE html>
<html lang="fr">
<!-- /**
 * width : 660
 * height : 585
 */ -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le geste déclic</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/stylesPictPositions.css">
</head>

<body>
    <!-- <img src="images/brand.jpg" alt="brand"> -->
    <!-- <h1>L'histoire de Sud Ouest</h1> -->
    <img src="images/brand.jpg" class="brand" alt="Logo Declic">
    <?php
    // Chemin vers le fichier CSV
    $csvFile = 'datas/datas.csv';
    $dataArray = [];

    // Ouverture du fichier en mode lecture
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        // Lire et ignorer la première ligne (en-tête)
        fgetcsv($handle, 1000, ',');

        // Lecture de chaque ligne du fichier CSV et stockage dans un tableau
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $dataArray[] = $data;
        }
        // Fermeture du fichier
        fclose($handle);
        $dataArray = array_reverse($dataArray);

        // Génération du premier bloc HTML
        echo '<div id="imageContainer">';
        echo '<button class="arrow left" id="prev"><b>&#9664;</b></button>';

        foreach ($dataArray as $index => $data) {
            $class = ($index == 0) ? 'slider-image active' : 'slider-image';
            echo '<div class="' . $class . '">
                
            <div class="masque"></div>
            <div class="image" style="background-image:url(images/' . $data[7] . ')"></div>
            <div class="containerText">
                <div>
                    <p class="txtIntro">' . str_replace("*$#*", "</br>",htmlspecialchars($data[1])) . '</p>
                    <p class="txtCap">' . str_replace("*$#*", "</br>",htmlspecialchars($data[2])) . '</p>
                </div>
                <div>
                    <p class="txtGras descriptif">' . str_replace("*$#*", "</br>",htmlspecialchars($data[3])) . '</p>
                    <p class="txtGrasGris">' . str_replace("*$#*", "</br>",htmlspecialchars($data[4])) . '</p>
                    <mark class="descriptif">' . str_replace("*$#*", "</br>",htmlspecialchars($data[5])) . '</mark>
                    <p class="txtMaigre descriptif">' . str_replace("*$#*", "</br>",htmlspecialchars($data[6])) . '</p>
                </div>
            
            </div>
            </div>';
        }

        echo '<button class="arrow right" id="next"><b>&#9654;</b></button>';
        echo '</div>';
        echo '
            <legend>
                <div class="logoDeclic"></div>
                <div class="txtLengend">en partenariat avec l\'Ademe</div>
                <div class="logoSo"></div>
            </legend>
        ';

        // <img src="images/' . $data[3] . '"  class="image" alt="' . $data[0] . '" data-date="' . $data[0] . '">
        // Génération de la barre de défilement
        echo '<div class="rangeBarre">';
        echo '<div id="currentDate" class="current-date"></div>';

        // Génération des graduations
        echo '<div id="rangeGraduations" class="rangeGraduations">';
        for ($i = 0; $i < count($dataArray); $i++) {
            echo '<div class="rangeGraduation"></div>';
        }
        echo '</div>';

        echo '<input type="range" id="rangeSlider" min="1" max="' . count($dataArray) . '" value="1">';
        echo '</div>';

        // Génération des blocs texte
        echo '<div id="textContainer">';
        foreach ($dataArray as $index => $data) {
            $class = ($index == 0) ? 'slider-text active' : 'slider-text';
            echo '<div class="' . $class . '" data-date="' . $data[0] . '">
             <div class="descriptif">' . $data[2] . '</div>
            </div>';
            echo '<p class="' . $class . '" data-date="' . $data[0] . '">' . $data[1] . '</p>';
        }
        echo '</div>';
    } else {
        echo 'Erreur: impossible d\'ouvrir le fichier CSV.';
    }
    ?>
    <script src="js/slider.js"></script>
</body>

</html>