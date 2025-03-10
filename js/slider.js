document.addEventListener('DOMContentLoaded', () => {
    const rangeSlider = document.getElementById('rangeSlider');
    const currentDate = document.getElementById('currentDate');
    const images = document.querySelectorAll('.slider-image');
    const texts = document.querySelectorAll('.slider-text');
    // console.log(rangeGraduations);




    const dates = Array.from(texts).map(text => text.getAttribute('data-date'));

    let startX;
    let currentIndex = parseInt(rangeSlider.value) - 1;

    function updateContent() {
        const value = rangeSlider.value;
        const selectedDate = dates[value - 1];
        currentIndex = value - 1;

        // Met à jour la date au-dessus du curseur
        currentDate.textContent = selectedDate;

        // Réinitialiser les classes des images
        images.forEach((img, index) => {
            img.classList.remove('active', 'prev', 'next');
            if (index === currentIndex) {
                img.classList.add('active');
            } else if (index === currentIndex - 1) {
                img.classList.add('prev');
            } else if (index === currentIndex + 1) {
                img.classList.add('next');
            }
        });

        // Masquer tous les textes
        texts.forEach(text => text.classList.remove('active'));

        // Afficher le texte correspondant à la valeur du curseur
        const currentText = document.querySelector(`.slider-text[data-date="${selectedDate}"]`);
        if (currentText) {
            currentText.classList.add('active');
        }
    }

    function changeSlide(increment) {
        currentIndex = Math.min(Math.max(currentIndex + increment, 0), images.length - 1);
        rangeSlider.value = currentIndex + 1;
        updateContent();
    }

    function handleSwipeStart(e) {
        startX = e.touches[0].clientX;
    }

    function handleSwipeMove(e) {
        if (!startX) return;
        const x = e.touches[0].clientX;
        const difference = startX - x;

        if (difference > 30) { // Sensible au balayage vers la gauche
            changeSlide(1);
            startX = null; // reset startX to avoid multiple triggers
        } else if (difference < -30) { // Sensible au balayage vers la droite
            changeSlide(-1);
            startX = null; // reset startX to avoid multiple triggers
        }
    }

    rangeSlider.addEventListener('input', updateContent);
    document.getElementById('next').addEventListener('click', () => changeSlide(1));
    document.getElementById('prev').addEventListener('click', () => changeSlide(-1));

    document.getElementById('imageContainer').addEventListener('touchstart', handleSwipeStart);
    document.getElementById('imageContainer').addEventListener('touchmove', handleSwipeMove);

    updateContent();
});
document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour mettre à jour la max-width des graduations
    function updateGraduationsWidth() {
        const rangeGraduations = document.getElementById('rangeGraduations');
        if (!rangeGraduations) return; // Vérifiez si l'élément existe

        // Obtenez la largeur de l'écran
        const screenWidth = window.innerWidth;

        // Définissez la max-width selon la largeur de l'écran
        let newMaxWidth;
        let newMaxWidth2;
        if (screenWidth > 622) {
            newMaxWidth = '584px'; // Valeur fixe lorsque l'écran est plus large que 622px
            newMaxWidth2 = ''; // Valeur fixe lorsque l'écran est plus large que 622px

        } else {
            newMaxWidth = (screenWidth - 38) + 'px'; // Calcul dynamique pour les écrans plus petits
            newMaxWidth2 = (screenWidth - 22) + 'px'; // Calcul dynamique pour les écrans plus petits

        }

        // Appliquez la max-width à l'élément
        rangeGraduations.style.maxWidth = newMaxWidth;
        rangeSlider.style.maxWidth = newMaxWidth2;
    }

    // Met à jour la max-width lors du chargement initial
    updateGraduationsWidth();

    // Met à jour la max-width lors du redimensionnement de la fenêtre
    window.addEventListener('resize', updateGraduationsWidth);
});
