var container = document.getElementById('container'); // Récupère l'élément contenant le carrousel
var slider = document.getElementById('slider'); // Récupère l'élément slider
var slides = document.getElementsByClassName('slide').length; // Compte le nombre de diapositives
var buttons = document.getElementsByClassName('btn'); // Récupère les boutons de navigation

var currentPosition = 0; // Position actuelle du carrousel
var currentMargin = 0; // Marge actuelle du carrousel
var slidesPerPage = 0; // Nombre de diapositives visibles par page
var slidesCount = slides - slidesPerPage; // Nombre total de diapositives - celles visibles
var containerWidth = container.offsetWidth; // Largeur du conteneur
var prevKeyActive = false; // Indique si le bouton précédent est actif
var nextKeyActive = true; // Indique si le bouton suivant est actif

window.addEventListener("resize", checkWidth); // Écoute les changements de taille de la fenêtre

function checkWidth() {
    containerWidth = container.offsetWidth; // Met à jour la largeur du conteneur
    setParams(containerWidth); // Met à jour les paramètres
}

function setParams(w) {
    if (w < 551) {
        slidesPerPage = 1; // 1 diapositive visible sur les petits écrans
    } else if (w < 901) {
        slidesPerPage = 2; // 2 diapositives visibles sur les écrans moyens
    } else if (w < 1101) {
        slidesPerPage = 3; // 3 diapositives visibles sur les grands écrans
    } else {
        slidesPerPage = 4; // 4 diapositives visibles sur les très grands écrans
    }
    slidesCount = slides - slidesPerPage; // Recalcule le nombre de diapositives restantes
    if (currentPosition > slidesCount) {
        currentPosition = slidesCount; // Ajuste la position actuelle si elle dépasse le nombre de diapositives restantes
    }
    currentMargin = -currentPosition * (100 / slidesPerPage); // Calcule la marge gauche actuelle
    slider.style.marginLeft = currentMargin + '%'; // Applique la marge gauche
    updateButtonsState(); // Met à jour l'état des boutons
}

function updateButtonsState() {
    if (currentPosition === 0) {
        buttons[0].classList.add('inactive'); // Désactive le bouton précédent si la position est 0
    } else {
        buttons[0].classList.remove('inactive'); // Active le bouton précédent sinon
    }

    if (currentPosition >= slidesCount) {
        buttons[1].classList.add('inactive'); // Désactive le bouton suivant si on est à la dernière diapositive
    } else {
        buttons[1].classList.remove('inactive'); // Active le bouton suivant sinon
    }
}

function slideRight() {
    if (currentPosition > 0) {
        currentPosition--; // Décrémente la position actuelle
        currentMargin += (100 / slidesPerPage); // Ajuste la marge gauche
        slider.style.marginLeft = currentMargin + '%'; // Applique la nouvelle marge
    }
    updateButtonsState(); // Met à jour l'état des boutons
}

function slideLeft() {
    if (currentPosition < slidesCount) {
        currentPosition++; // Incrémente la position actuelle
        currentMargin -= (100 / slidesPerPage); // Ajuste la marge gauche
        slider.style.marginLeft = currentMargin + '%'; // Applique la nouvelle marge
    }
    updateButtonsState(); // Met à jour l'état des boutons
}

setParams(); // Initialise les paramètres