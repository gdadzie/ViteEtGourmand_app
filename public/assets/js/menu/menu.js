 window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    const triggerHeight = 550; // hauteur après laquelle la couleur change (ajuste selon la vidéo)

    if (window.scrollY > triggerHeight) {
    navbar.classList.add('scrolled');
    navbar.classList.remove('transparent');
} else {
    navbar.classList.add('transparent');
    navbar.classList.remove('scrolled');
}
});
