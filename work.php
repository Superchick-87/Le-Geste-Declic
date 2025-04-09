<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le geste déclic</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/stylesBack.css">
    <link rel="stylesheet" href="css/stylesPictPositions.css">
</head>

<body>
    <?php


    $csvFilePath = 'datas/datas.csv';

    if (!file_exists($csvFilePath)) {
        die("Le fichier CSV n'existe pas.");
    }

    if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
        $rows = [];

        // Lire l'en-tête
        $header = fgetcsv($handle);

        // Stocker les lignes dans un tableau
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);

        // Inverser les lignes
        // $rows = array_reverse($rows);

        // Réinsérer l'en-tête
        // array_unshift($rows, $header);
        $bloc = 1;

        echo '
        <div class="hautFixe">
            <img src="images/brand.jpg" class="brand" alt="Logo Declic">
            <progress id="progressBar" value="0" max="100" style="display: none;"></progress>

            <div class="barreMenu">
                <div class="add-btn" id="add-btn" onclick="goUp(); addInputs();">+</div>
                <input id="save" class="save" type="submit" name="save" value="">
                <div class="link" id="link" onclick="openNewTab()"></div>
                <div class="vertical-line"></div>
                <div class="download" id="download" onclick="downloadFile()"></div>
                <div class="upload" id="upload" onclick="triggerUpload()"></div>
            </div>
        </div>
        <div id="inputs-container">
        ';



        foreach ($rows as $data) {
            // Générer un ID unique pour chaque bloc
            $uniqueId = 'bloc_' . $bloc;

            $imageFileName = !empty(htmlspecialchars($data[7])) ? htmlspecialchars($data[7]) : '';
            $imagePath = 'images/' . $data[7];
            $imageSrc = file_exists($imagePath) ? $imagePath : 'images/000.jpg';
            $backgroundSize = ($imageSrc === 'images/000.jpg') ? 'background-size: 100%;' : '';

            echo '
                <div id="containBackFront_' . $data[0] . '" class="containBackFront">
                    <div id="' . $bloc . '" class="bloc">
                        <input id="number_' . $bloc  . '" type="number" name="id[]" value="' . $data[0] . '" readonly="readonly"/>
                        <textarea id="intro_textarea_' . $uniqueId . '" name="intro[]" placeholder="Intro" rows="1">' . str_replace("*$#*", "\n", htmlspecialchars($data[1])) . '</textarea>
                        <textarea id="intro_Cap_textarea_' . $uniqueId . '" name="intro_Cap[]" placeholder="Intro Cap" rows="1">' . str_replace("*$#*", "\n", htmlspecialchars($data[2])) . '</textarea>
                        <textarea id="contenu_Gras_textarea_' . $uniqueId . '" name="contenu_Gras[]" placeholder="Contenu Gras" rows="1">' . str_replace("*$#*", "\n", htmlspecialchars($data[3])) . '</textarea>
                        <textarea id="contenu_GrasGris_textarea_' . $uniqueId . '" name="contenu_GrasGris[]" placeholder="Contenu Gras" rows="1">' . str_replace("*$#*", "\n", htmlspecialchars($data[4])) . '</textarea>
                        <textarea id="contenu_Fond_Vert_textarea_' . $uniqueId . '" name="contenu_Fond_Vert[]" placeholder="Contenu Fond Vert" rows="1">' . str_replace("*$#*", "\n", htmlspecialchars($data[5])) . '</textarea>
                        <textarea id="contenu_Maigre_textarea_' . $uniqueId . '" name="contenu_Maigre[]" placeholder="Contenu Maigre" rows="1">' . str_replace("*$#*", "\n", htmlspecialchars($data[6])) . '</textarea>
                        
                        <div id="bloc_img_' . $bloc . '" class="image-upload">
                            <form class="upload-form" data-bloc="' . $bloc . '" enctype="multipart/form-data">
                                
                                <!-- ✅ Ajout du champ caché pour stocker le nom de l\'image -->
                                <input type="hidden" name="image_name[]" data-id="' . $bloc . '" value="' . $imageFileName . '">
                                
                                <label for="image_' . $bloc . '" class="label">
                                    <input type="file" id="image_' . $bloc . '" name="image[]" accept="image/*">
                                    <span class="button-text">' . ($imageFileName ? "Changer l'image" : "Uploader une image") . '</span>
                                </label>
                                <span class="image-name">' . ($imageFileName ? $imageFileName : "Aucune image sélectionnée") . '</span>
                                ' . ($imageSrc ? '<img src="' . $imageSrc . '" id="preview_' . $bloc . '" class="image-preview" style="display:block; max-width:200px;">' : '<img id="preview_' . $bloc . '" class="image-preview" style="display:none; max-width:200px;">') . '
                           

                            <div id ="radio-buttons_' . $bloc . '" class="radio-buttons">
                        ';
            $positions = [
                "top-left",
                "top-center",
                "top-right",
                "left-center",
                "center-center",
                "center-right",
                "bottom-left",
                "bottom-center",
                "bottom-right"
            ];

            foreach ($positions as $position) {
                // Vérifier si la position enregistrée dans $data[8] correspond à cette position
                $checked = ($position ===  htmlspecialchars($data[8])) ? "checked" : "";

                echo '
                <input type="radio" 
                    id="position_' . $position . '_' . $uniqueId . '" 
                    name="position_' . $bloc . '" 
                    data-id="' . $bloc . '" 
                    value="' . $position . '" 
                    onclick="changeImageClass(\'' . $uniqueId . '\', \'' . $position . '\')" ' . $checked . ' >
                <label class="grille" for="position_' . $position . '_' . $uniqueId . '"></label>';
            }
            echo '
                            </div>
                        </div> </form>
                    </div>

                    <div id="bloc_front_' . $bloc . '" class="bloc_front">
                        <div id="imageContainer">
                            <div id="bloc_imgg_' . $uniqueId . '" class="image ' . $data[8] . '" style="background-image:url(' . $imageSrc . '); ' . $backgroundSize . '"></div>
                            <div class="masqueB"></div>
                            <div class="containerText_B">
                                <div>
                                    <p id="intro_F_' . $uniqueId . '" class="txtIntro">' . str_replace("*$#*", "</br>", htmlspecialchars($data[1])) . '</p>
                                    <p id="intro_Cap_F_' . $uniqueId . '" class="txtCap">' . str_replace("*$#*", "</br>", htmlspecialchars($data[2])) . '</p>
                                </div>
                                <div class="padd_bas">
                                    <p id="contenu_Gras_F_' . $uniqueId . '" class="txtGras descriptif">' . str_replace("*$#*", "</br>", htmlspecialchars($data[3])) . '</p>
                                    <p id="contenu_GrasGris_F_' . $uniqueId . '" class="txtGrasGris descriptif">' . str_replace("*$#*", "</br>", htmlspecialchars($data[4])) . '</p>
                                    <mark id="contenu_Fond_Vert_F_' . $uniqueId . '" class="descriptif">' . str_replace("*$#*", "</br>", htmlspecialchars($data[5])) . '</mark>
                                    <p id="contenu_Maigre_F_' . $uniqueId . '" class="txtMaigre descriptif">' . str_replace("*$#*", "</br>", htmlspecialchars($data[6])) . '</p>
                                </div>
                            </div>
                            </div>
                            <legend>
                                <div class="logoDeclic"></div>
                                <div class="txtLengend">en partenariat avec l\'Ademe</div>
                                <div class="logoSo"></div>
                            </legend>
                        </div>
                        <div class="export-btn" onclick="exportToJpg(\'bloc_front_' . $bloc . '\')"></div>
                </div>
            ';
            $bloc++;
        }
    } else {
        echo "Impossible d'ouvrir le fichier.";
    }
    echo '
        </div>
    ';
    ?>

    <script>
        function changeImageClass(uniqueId, newClass) {
            var imageDiv = document.getElementById("bloc_imgg_" + uniqueId);
            imageDiv.className = "image " + newClass;
            console.log(document.getElementById(uniqueId));
        }
    </script>
    <script>
        //* Fonction bouttons menu haut
        function openNewTab() {
            window.open('index.php', '_blank');
        }

        function downloadFile() {
            const link = document.createElement('a'); // Créer un lien
            link.href = 'images/Slider_Declic.ai'; // Chemin du fichier à télécharger
            link.download = 'Slider_Declic.ai'; // Nom du fichier téléchargé
            link.click(); // Simuler un clic pour démarrer le téléchargement
        }

        function triggerUpload() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.style.display = 'none';
            fileInput.accept = '.ai';

            fileInput.addEventListener('change', function() {
                uploadFile(fileInput.files[0]);
            });

            fileInput.click();
        }

        function uploadFile(file) {
            if (!file) {
                alert('Aucun fichier sélectionné.');
                return;
            }

            const formData = new FormData();
            formData.append('file', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'uploadFile.php', true);

            const progressBar = document.getElementById('progressBar');
            progressBar.style.display = 'block'; // Afficher la barre de progression

            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    let percentComplete = (event.loaded / event.total) * 100;
                    progressBar.value = percentComplete;
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Fichier téléchargé avec succès !');
                } else {
                    alert('Erreur lors du téléchargement du fichier.');
                }
                progressBar.style.display = 'none'; // Cacher la barre après l'upload
                progressBar.value = 0;
            };

            xhr.send(formData);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        //* Fonction pour changer l'image et mettre à jour dans la vignette de prévisualisation et le rendu final
        function majImages() {
            // Ajouter un écouteur d'événements pour chaque formulaire de téléchargement
            document.querySelectorAll('.upload-form').forEach(form => {
                form.addEventListener('change', function(event) {
                    let input = event.target; // L'élément qui a déclenché l'événement
                    if (input.type === 'file') {
                        // Créer un FormData pour envoyer l'image via Ajax
                        let formData = new FormData();
                        formData.append('image', input.files[0]); // Ajouter l'image au FormData
                        formData.append('bloc_id', this.dataset.bloc); // Ajouter l'ID du bloc à FormData

                        // Envoyer la requête via fetch
                        fetch('upload.php', {
                                method: 'POST',
                                body: formData // Passer les données dans le corps de la requête
                            })
                            .then(response => response.json()) // Recevoir la réponse en JSON
                            .then(data => {
                                // Si le fichier a été téléchargé avec succès
                                if (data.success) {
                                    let blocId = data.bloc; // ID du bloc retourné par le serveur
                                    let newFileName = data.file; // Nouveau nom du fichier retourné par le serveur

                                    // ✅ Mettre à jour l'input caché avec le nouveau nom du fichier
                                    let hiddenInput = document.querySelector(`input[type="hidden"][data-id='${blocId}']`);
                                    if (hiddenInput) {
                                        hiddenInput.value = newFileName; // Mettre à jour la valeur avec le nom du fichier
                                    }

                                    // ✅ Mettre à jour le texte affiché avec le nom du fichier
                                    let imageNameSpan = this.querySelector(".image-name");
                                    if (imageNameSpan) {
                                        imageNameSpan.textContent = newFileName; // Mettre à jour le texte avec le nom du fichier
                                    }

                                    // ✅ Mettre à jour la prévisualisation de l'image
                                    let img = document.getElementById("preview_" + blocId);
                                    if (img) {
                                        // Ajouter un timestamp pour éviter le cache du navigateur
                                        img.src = "images/" + newFileName + "?t=" + new Date().getTime(); // Mettre à jour la source de l'image
                                        img.style.display = "block"; // Afficher l'image après le téléchargement
                                    }

                                    // ✅ Mettre à jour l'arrière-plan de l'image de rendu
                                    let backgroundDiv = document.querySelector("#bloc_imgg_bloc_" + blocId);
                                    if (backgroundDiv) {
                                        // Ajouter un timestamp pour forcer la mise à jour de l'arrière-plan
                                        backgroundDiv.style.backgroundImage = "url('images/" + newFileName + "?t=" + new Date().getTime() + "')";
                                    }

                                    // ✅ Ajouter la classe 'image' à la div contenant l'image (bloc_imgg_bloc_${blocId})
                                    let imageDiv = document.querySelector("#bloc_imgg_bloc_" + blocId);
                                    if (imageDiv) {
                                        imageDiv.classList.add('image'); // Ajouter la classe 'image' à la div contenant l'image
                                        imageDiv.style.backgroundSize = 80 + "%"; // Optionnel : mettre une taille de fond
                                    }

                                } else {
                                    // Si l'upload échoue, afficher un message d'erreur
                                    alert("Erreur : " + data.error);
                                }
                            })
                            .catch(error => {
                                // Si l'upload échoue, afficher l'erreur dans la console
                                console.error('Erreur:', error);
                            });
                    }
                });
            });
        }
        // Appeler la fonction majImages() dès que le DOM est chargé
        document.addEventListener('DOMContentLoaded', majImages);
        // window.addEventListener("load", majImages);
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script>
        function exportToJpg(blocId) {
            const now = new Date();
            const formattedDate =
                String(now.getDate()).padStart(2, '0') + "" +
                String(now.getMonth() + 1).padStart(2, '0') + "" +
                now.getFullYear() + "_" +
                String(now.getHours()).padStart(2, '0') + "" +
                String(now.getMinutes()).padStart(2, '0') + "" +
                String(now.getSeconds()).padStart(2, '0');

            let bloc = document.getElementById(blocId);
            domtoimage.toJpeg(bloc, {
                    quality: 1,
                    cacheBust: true
                })
                .then(function(dataUrl) {
                    let link = document.createElement("a");
                    link.href = dataUrl;
                    link.download = "Declic_Pict_" + formattedDate + ".jpg";
                    // link.download = "Declic_Pict_" + blocId + ".jpg";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    alert("L'image a été téléchargée !");
                })
                .catch(function(error) {
                    console.error('Erreur lors de la capture : ', error.message, error);
                    alert("Une erreur est survenue : " + error.message);
                });
        }
    </script>

    <script>
        $(document).ready(function() {
            $("#save").click(function(e) {
                e.preventDefault(); // Éviter le rechargement de la page

                let formData = new FormData();
                let ids = [];
                let intros = [];
                let introsCap = [];
                let contenusGras = [];
                let contenusGrasGris = [];
                let contenusFondVert = [];
                let contenusMaigre = [];
                let images = {}; // Stocke les nouveaux noms de fichiers
                let positions = []; // Stocke les positions des images

                // Récupération des données de formulaire
                $("input[name='id[]']").each(function() {
                    ids.push($(this).val());
                });

                $("textarea[name='intro[]']").each(function() {
                    intros.push($(this).val());
                });

                $("textarea[name='intro_Cap[]']").each(function() {
                    introsCap.push($(this).val());
                });

                $("textarea[name='contenu_Gras[]']").each(function() {
                    contenusGras.push($(this).val());
                });

                $("textarea[name='contenu_GrasGris[]']").each(function() {
                    contenusGrasGris.push($(this).val());
                });

                $("textarea[name='contenu_Fond_Vert[]']").each(function() {
                    contenusFondVert.push($(this).val());
                });

                $("textarea[name='contenu_Maigre[]']").each(function() {
                    contenusMaigre.push($(this).val());
                });

                // Récupération des noms des images renommées
                $("input[name='image_name[]']").each(function() {
                    let id = $(this).attr("data-id");
                    if (id) {
                        images[id] = $(this).val();
                    }
                });

                // ✅ Récupération des positions cochées uniquement
                $("input[type='radio']:checked").each(function() {
                    positions.push($(this).val());
                });

                // Ajout des données au FormData
                formData.append("id", JSON.stringify(ids));
                formData.append("intro", JSON.stringify(intros));
                formData.append("intro_Cap", JSON.stringify(introsCap));
                formData.append("contenu_Gras", JSON.stringify(contenusGras));
                formData.append("contenu_GrasGris", JSON.stringify(contenusGrasGris));
                formData.append("contenu_Fond_Vert", JSON.stringify(contenusFondVert));
                formData.append("contenu_Maigre", JSON.stringify(contenusMaigre));
                formData.append("images", JSON.stringify(images) || "{}");
                formData.append("positions", JSON.stringify(positions));

                console.log("Images envoyées:", JSON.stringify(images));
                console.log("Positions envoyées:", JSON.stringify(positions));
                console.log("FormData:", [...formData.entries()]);

                // Envoi des données par AJAX
                $.ajax({
                    url: "done_csv.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert("Données enregistrées avec succès !");
                    },
                    error: function() {
                        alert("Erreur lors de l'enregistrement.");
                    }
                });
            });
        });
    </script>



    <script>
        //* Fonction pour lier l'événement de mise à jour du texte dans le "textarea" au texte dans le "p"
        function updateTextContent(textareaId, targetId) {
            const textarea = document.getElementById(textareaId);
            const targetElement = document.getElementById(targetId);

            if (textarea && targetElement) {
                // Vérifier si les éléments existent
                textarea.addEventListener('input', function() {
                    targetElement.innerHTML = textarea.value.replace(/\n/g, '<br>');
                });
            } else {
                console.warn("Problème de cible pour", textareaId, targetId);
            }
        }
        // Configuration de la mise à jour des champs
        function setupTextUpdates() {
            document.querySelectorAll('textarea[id^="intro_textarea_bloc_"]').forEach((textarea) => {
                const uniqueId = textarea.id.split('_')[3];
                updateTextContent(`intro_textarea_bloc_${uniqueId}`, `intro_F_bloc_${uniqueId}`);
            });

            document.querySelectorAll('textarea[id^="intro_Cap_textarea_bloc_"]').forEach((textarea) => {
                const uniqueId = textarea.id.split('_')[4];
                updateTextContent(`intro_Cap_textarea_bloc_${uniqueId}`, `intro_Cap_F_bloc_${uniqueId}`);
            });

            document.querySelectorAll('textarea[id^="contenu_Gras_textarea_bloc_"]').forEach((textarea) => {
                const uniqueId = textarea.id.split('_')[4];
                updateTextContent(`contenu_Gras_textarea_bloc_${uniqueId}`, `contenu_Gras_F_bloc_${uniqueId}`);
            });

            document.querySelectorAll('textarea[id^="contenu_GrasGris_textarea_bloc_"]').forEach((textarea) => {
                const uniqueId = textarea.id.split('_')[4];
                updateTextContent(`contenu_GrasGris_textarea_bloc_${uniqueId}`, `contenu_GrasGris_F_bloc_${uniqueId}`);
            });

            document.querySelectorAll('textarea[id^="contenu_Fond_Vert_textarea_bloc_"]').forEach((textarea) => {
                const uniqueId = textarea.id.split('_')[5];
                updateTextContent(`contenu_Fond_Vert_textarea_bloc_${uniqueId}`, `contenu_Fond_Vert_F_bloc_${uniqueId}`);
            });

            document.querySelectorAll('textarea[id^="contenu_Maigre_textarea_bloc_"]').forEach((textarea) => {
                const uniqueId = textarea.id.split('_')[4];
                updateTextContent(`contenu_Maigre_textarea_bloc_${uniqueId}`, `contenu_Maigre_F_bloc_${uniqueId}`);
            });
        }
        // Initialisation de la mise à jour des textes à la fin du chargement du DOM
        document.addEventListener('DOMContentLoaded', setupTextUpdates);
    </script>

    <script>
        //** Fonction pour remonter en haut de la page
        function goUp() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        //** Ajout boite
        async function addInputs() {
            try {

                // Attribution de l'id
                const uniqueId = document.querySelectorAll(".containBackFront").length + 1;
                const container = document.getElementById("inputs-container");

                // Création de la nouvelle boîte containBackFront (bloc | bloc_front)
                const containBackFront = document.createElement("div");
                containBackFront.id = "containBackFront_" + uniqueId;
                containBackFront.classList.add("containBackFront");
                container.prepend(containBackFront);



                //** Block back
                // Création de la partie bloc (number | textarea)
                const bloc = document.createElement("div");
                bloc.id = "bloc_" + uniqueId;
                bloc.classList.add("bloc");
                containBackFront.appendChild(bloc);

                // Création de la partie bloc_front (img Logo | imageContainer)
                const bloc_front = document.createElement("div");
                bloc_front.id = "bloc_front_" + uniqueId;
                bloc_front.classList.add("bloc_front");
                containBackFront.appendChild(bloc_front);

                // Création de la partie bloc (input | textarea | bloc_img)
                const number = document.createElement("input");
                number.id = "number_" + uniqueId;
                number.type = "number";
                number.name = "id[]";
                number.value = uniqueId;
                number.readOnly = true;

                const intro_textarea_bloc = document.createElement("textarea");
                intro_textarea_bloc.id = "intro_textarea_bloc_" + uniqueId;
                intro_textarea_bloc.name = "intro[]";
                intro_textarea_bloc.placeholder = "Intro";
                intro_textarea_bloc.rows = "1";

                const intro_Cap_textarea_bloc = document.createElement("textarea");
                intro_Cap_textarea_bloc.id = "intro_Cap_textarea_bloc_" + uniqueId;
                intro_Cap_textarea_bloc.name = "intro_Cap[]";
                intro_Cap_textarea_bloc.placeholder = "Intro Cap";
                intro_Cap_textarea_bloc.rows = "1";

                const contenu_Gras_textarea_bloc = document.createElement("textarea");
                contenu_Gras_textarea_bloc.id = "contenu_Gras_textarea_bloc_" + uniqueId;
                contenu_Gras_textarea_bloc.name = "contenu_Gras[]";
                contenu_Gras_textarea_bloc.placeholder = "Contenu Gras";
                contenu_Gras_textarea_bloc.rows = "1";

                const contenu_GrasGris_textarea_bloc = document.createElement("textarea");
                contenu_GrasGris_textarea_bloc.id = "contenu_GrasGris_textarea_bloc_" + uniqueId;
                contenu_GrasGris_textarea_bloc.name = "contenu_GrasGris[]";
                contenu_GrasGris_textarea_bloc.placeholder = "Contenu Gras gris";
                contenu_GrasGris_textarea_bloc.rows = "1";

                const contenu_Fond_Vert_textarea_bloc = document.createElement("textarea");
                contenu_Fond_Vert_textarea_bloc.id = "contenu_Fond_Vert_textarea_bloc_" + uniqueId;
                contenu_Fond_Vert_textarea_bloc.name = "contenu_Fond_Vert[]";
                contenu_Fond_Vert_textarea_bloc.placeholder = "Contenu Fond Vert";
                contenu_Fond_Vert_textarea_bloc.rows = "1";

                const contenu_Maigre_textarea_bloc = document.createElement("textarea");
                contenu_Maigre_textarea_bloc.id = "contenu_Maigre_textarea_bloc_" + uniqueId;
                contenu_Maigre_textarea_bloc.name = "contenu_Maigre[]";
                contenu_Maigre_textarea_bloc.placeholder = "Contenu Maigre";
                contenu_Maigre_textarea_bloc.rows = "1";

                // Création de la partie bloc img prévisue (form_img)
                const bloc_img = document.createElement("div");
                bloc_img.id = "bloc_img_" + uniqueId;
                bloc_img.classList.add("image-upload");

                bloc.appendChild(number);
                bloc.appendChild(intro_textarea_bloc);
                bloc.appendChild(intro_Cap_textarea_bloc);
                bloc.appendChild(contenu_Gras_textarea_bloc);
                bloc.appendChild(contenu_GrasGris_textarea_bloc);
                bloc.appendChild(contenu_Fond_Vert_textarea_bloc);
                bloc.appendChild(contenu_Maigre_textarea_bloc);
                bloc.appendChild(bloc_img);

                // Création du form de la partie bloc img prévisue (input | label | span | img)
                const form_img = document.createElement("form");
                form_img.classList.add("upload-form");
                form_img.setAttribute("data-bloc", uniqueId);
                form_img.enctype = "multipart/form-data";

                bloc_img.appendChild(form_img);

                const image_name = document.createElement("span");
                image_name.classList.add("image-name");

                const input_hi = document.createElement("input");
                input_hi.type = "hidden";
                input_hi.name = "image_name[]";
                input_hi.setAttribute("data-id", uniqueId);
                input_hi.setAttribute("value", " ");


                const label = document.createElement("label"); // Création du label (input | span)
                label.htmlFor = "image_" + uniqueId;
                label.classList.add("label");


                const image_default = document.createElement("img");
                image_default.src = "images/000.jpg";
                image_default.id = "preview_" + uniqueId;
                image_default.classList.add("image-preview");
                image_default.style.display = "block";
                image_default.style.maxWidth = "200px";

                // Création de la partie boutons radio (input | labbel)
                const bloc_radio = document.createElement("div");
                bloc_radio.id = "radio-buttons_" + uniqueId;
                bloc_radio.classList.add("radio-buttons");




                form_img.appendChild(input_hi);
                form_img.appendChild(label);
                form_img.appendChild(image_name);
                form_img.appendChild(image_default);
                // form_img.appendChild(bloc_radio);


                const filex = document.createElement("input");
                filex.type = "file";
                filex.id = "image_" + uniqueId;
                filex.name = "image";
                filex.accept = "image/*";

                const mess = document.createElement("span");
                mess.classList.add("button-text");
                mess.innerHTML = "Changer l'image";

                label.appendChild(filex);
                label.appendChild(mess);

                //!!
                const definePositions = [
                    "top-left", "top-center", "top-right",
                    "left-center", "center-center", "center-right",
                    "bottom-left", "bottom-center", "bottom-right"
                ];

                // Vérifier que bloc est bien une chaîne (sinon récupérer son id)
                const blocId = typeof bloc === "string" ? bloc : bloc.id;

                let selectedPosition = "center-center"; // Valeur par défaut

                definePositions.forEach(position => {
                    const input = document.createElement("input");
                    input.type = "radio";
                    input.id = `position_${position}_${uniqueId}`;
                    input.name = `position_${blocId}`; // Correction ici
                    input.dataset.id = blocId; // Correction ici
                    input.value = position;
                    input.onclick = () => changeImageClass(blocId, position);

                    if (position === selectedPosition) {
                        input.checked = true;
                    }

                    const label = document.createElement("label");
                    label.classList.add("grilleJs");
                    label.setAttribute("for", `position_${position}_${uniqueId}`);

                    bloc_radio.appendChild(input);
                    bloc_radio.appendChild(label);
                });
                form_img.appendChild(bloc_radio);
                //!

                //** Block front
                const image_brand = document.createElement("legend");
                image_brand.innerHTML =
                    "<div class='logoDeclic'></div>" +
                    "<div class='txtLengend'>en partenariat avec l'Ademe</div>" +
                    "<div class='logoSo'></div>";
                // image_brand.src = "images/brand.jpg";
                // image_brand.classList.add("brand");
                // image_brand.style.alt = "Logo Declic";

                const imageContainer = document.createElement("div"); //  (masque | bloc imgg bloc | container Text_B)
                imageContainer.id = "imageContainer";

                bloc_front.appendChild(imageContainer);
                bloc_front.appendChild(image_brand);

                const masque = document.createElement("div");
                masque.classList.add("masqueB");

                const bloc_imgg_bloc = document.createElement("div");
                bloc_imgg_bloc.id = "bloc_imgg_bloc_" + uniqueId;
                bloc_imgg_bloc.classList.add("image");
                bloc_imgg_bloc.style.backgroundImage = "url('images/000.jpg')";
                bloc_imgg_bloc.style.backgroundSize = "cover";



                imageContainer.appendChild(bloc_imgg_bloc);
                imageContainer.appendChild(masque);

                const containerText_B = document.createElement("div"); //  (introBold | div)
                containerText_B.classList.add("containerText_B");

                imageContainer.appendChild(containerText_B);

                const introBold = document.createElement("div"); //  (intro_txt_Bold | intro_txt_Bold_Cap)

                containerText_B.appendChild(introBold);

                const intro_txt_Bold = document.createElement("p");
                intro_txt_Bold.id = "intro_F_bloc_" + uniqueId;
                intro_txt_Bold.classList.add("txtIntro");
                // intro_txt_Bold.innerText = "Intro";

                const intro_txt_Bold_Cap = document.createElement("p");
                intro_txt_Bold_Cap.id = "intro_Cap_F_bloc_" + uniqueId;
                intro_txt_Bold_Cap.classList.add("txtCap");

                introBold.appendChild(intro_txt_Bold);
                introBold.appendChild(intro_txt_Bold_Cap);

                const txt_bas = document.createElement("div"); //  (contenu_Gras_F_bloc | mark | contenu_Maigre_F_bloc)
                txt_bas.classList.add("padd_bas");

                containerText_B.appendChild(txt_bas);

                const contenu_Gras_F_bloc = document.createElement("p");
                contenu_Gras_F_bloc.id = "contenu_Gras_F_bloc_" + uniqueId;
                contenu_Gras_F_bloc.classList.add("txtGras", "descriptif");

                const contenu_GrasGris_F_bloc = document.createElement("p");
                contenu_GrasGris_F_bloc.id = "contenu_GrasGris_F_bloc_" + uniqueId;
                contenu_GrasGris_F_bloc.classList.add("txtGrasGris", "descriptif");

                const contenu_Fond_Vert_F_bloc = document.createElement("mark");
                contenu_Fond_Vert_F_bloc.id = "contenu_Fond_Vert_F_bloc_" + uniqueId;
                contenu_Fond_Vert_F_bloc.classList.add("descriptif");

                const contenu_Maigre_F_bloc = document.createElement("p");
                contenu_Maigre_F_bloc.id = "contenu_Maigre_F_bloc_" + uniqueId;
                contenu_Maigre_F_bloc.classList.add("txtMaigre", "descriptif");

                txt_bas.appendChild(contenu_Gras_F_bloc);
                txt_bas.appendChild(contenu_GrasGris_F_bloc);
                txt_bas.appendChild(contenu_Fond_Vert_F_bloc);
                txt_bas.appendChild(contenu_Maigre_F_bloc);

                // Création boutton export image (bloc | bloc_front)
                const exportImage = document.createElement("div");
                exportImage.classList.add("export-btn");
                exportImage.onclick = function() {
                    exportToJpg('bloc_front_' + uniqueId);
                };
                // exportImage.innerText = "Exporter en JPG";
                containBackFront.appendChild(exportImage);

                setupTextUpdates();
                majImages();

            } catch (error) {
                console.error("Error in addInputs:", error);
            }
        }
        // window.addEventListener("load", addInputs);
    </script>
</body>

</html>