//animation du titre de nom  site//
//je creer une variable textwrapper qui me permet de recuperer dans mon fichier html twig ma class.ml2
let textWrapper = document.querySelector('.ml2');
//textcontent me permet grace à la methode replace de modifier le contenu text, chaine de caractére
// que je defini dans value en enlevant ici les espaces
//span letter enveloppe chaques lettres
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
//boucle et fonction pour l'amination
anime.timeline({loop: true})
    //methode add me permet d'ajouter les valeurs que je souhaite
    .add({
        targets: '.ml2 .letter',//lien des classes cyblées
        scale: [4,1],//modifie taille de l'element sur 2 dimensions
        opacity: [0,1], //opacite
        translateZ: 0,//permet de deplacer l'element sur l'axe Z
        easing: "easeOutExpo",//courbe d'accéleration vitesse de changement
        duration: 1500,//durée d'animation
        delay: (el, i) => 70*i//millisecondes av demarrer anim
    }).add({
    targets: '.ml2',
    opacity: 0,
    duration: 1500,
    easing: "easeOutExpo",
    delay: 1500
});

/*caroussel*/






