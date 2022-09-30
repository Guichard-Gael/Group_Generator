const groups = {
    underMedian: [],
    equalMedian: [],
    aboveMedian: [],
    listPeoplesGroups: [],
    orderedPeopleArray: [],
    init: function () { 
        console.log("Initialisation de l'objet Groups");
        if(groups.getCookie('fileID') && groups.getCookie('perGroup')){
            console.log(document.cookie);
            fetch(`../upload/${groups.getCookie('fileID')}.json`)
            .then((response) => response.json())
            .then((data) => {
                // Nombre de groupe nécessaire = arrondi au suppérieur(longueur tableau / nombre personnes par groupe) 
                const numberGroupsNeeded = Math.ceil(data.length / groups.getCookie('perGroup'));

                // Création des groupes
                groups.createGroups(numberGroupsNeeded);

                // Tri des données du tableau
                groups.orderArray(data);

                groups.sortPeople(groups.orderedPeopleArray);

                // Les personnes au-dessus de la médiane sont dispatchées.
                groups.dispatchPeoplesInGroup(groups.aboveMedian, numberGroupsNeeded, groups.getCookie('perGroup'));
                // Les personnes en-dessous de la médiane sont dispatchées.
                groups.dispatchPeoplesInGroup(groups.underMedian, numberGroupsNeeded, groups.getCookie('perGroup'));
                // On complète les groupes avec le reste des personnes.
                groups.dispatchPeoplesInGroup(groups.equalMedian, numberGroupsNeeded, groups.getCookie('perGroup'));

                // On génère le code html nécessaire pour l'affichage des groupes
                groups.createListGroupsElement(groups.listPeoplesGroups);

                


            });
            
        }
    },
    /**
     * Vérifie si le tableau est ordonné dans l'ordre croissant.
     * 
     * @param {array} arrayToCheck Tableau à ordonner.
     * @returns {boolean}
     */
    isOrderArray: function (arrayToCheck) { 
        // Comparaison d'une valeur avec celle qui la précède. Empêche la boucle de sortir du tableau. Il faut donc commencer à l'indice 1.
        for(let index = 1; index < arrayToCheck.length; index++){

            // Variable qui récupère l'élément à l'indice précédent pour comparer les deux valeurs.
            let previousElement = arrayToCheck[index - 1]["actualLevel"];
            if (previousElement > arrayToCheck[index]["actualLevel"]) {

                // Le tableau n'est pas ordonné.
                return false;
            }  
        }
        // Le tableau est ordonné.
        return true;
    },
    /**
     * Réorganise le tableau dans l'ordre croissant.
     * 
     * @param {array} arrayToOrder Tableau à ordonner.
     * @returns {array}
     */
    orderArray: function (arrayToOrder) { 
        while(!groups.isOrderArray(arrayToOrder)){

            // Comparaison d'une valeur avec celle qui la précède. Empêche la boucle de sortir du tableau. Il faut donc commencer à l'indice 1.
            for(let index = 1; index < arrayToOrder.length; index++){

                // Variable qui récupère l'élément à l'indice précédent pour comparer les deux valeurs. Sert aussi de stockage temporaire pour intervertir les deux valeurs si l'ordre n'est pas correcte.
                let previousElement = arrayToOrder[index - 1];

                if (previousElement["actualLevel"] > arrayToOrder[index]["actualLevel"]) {

                    // Changement de l'ordre des deux valeurs comparées.
                    arrayToOrder[index-1] = arrayToOrder[index];
                    arrayToOrder[index] = previousElement;
                }
            }
        }
        groups.orderedPeopleArray = arrayToOrder;
    },
    /**
     * Calcul de la médiane
     * 
     * @param {array} arrayPeoples Tableau contenant toutes les personnes à dispatcher.
     * @return float
     */
    calculMedian: function(arrayPeoples){
        // Dans le cas ou le nombre de valeur dans le tableau est pair
        let median;

        if(arrayPeoples.length % 2 === 0){

            // On prend les deux valeurs au milieu du tableau (le "-1" car un tableau commence à l'indice 0).
            const middleValue = parseInt(arrayPeoples[(arrayPeoples.length / 2) - 1]["actualLevel"]);

            // On prend comme indice la moitier de la longueur du tableau.
            const secondMiddleValue = parseInt(arrayPeoples[arrayPeoples.length / 2]["actualLevel"]);

            // Moyenne des deux valeurs arondi à l'inférieur.
            median = Math.floor((middleValue + secondMiddleValue) / 2);
            
            
        }
        else{

            // Indice valeur de la moitier du tableau = (longueur tableau + 1) / 2. On soustrait -1  à la fin car un tableau commence à l'indice 0.
            let indexMedian = (arrayPeoples.length + 1) / 2 - 1
            median = Math.floor(parseInt(arrayPeoples[indexMedian]["actualLevel"]));
        }
        return median;
    },
    /**
     * Trie les personnes en fonction de le niveau
     * 
     * @param {array} arrayPeoples Tableau contenant toutes les personnes à dispatcher.
     */
    sortPeople: function(arrayPeoples){

        // Calcul de la médiane
        const median = groups.calculMedian(arrayPeoples)
        console.log(median);
        // On dispatche les personnes en fonction de leur niveau.
        for(let index = 0; index < arrayPeoples.length; index++){
            
            if(arrayPeoples[index]["actualLevel"] > median){
                groups.aboveMedian.push(arrayPeoples[index]);
            }
            else if(arrayPeoples[index]["actualLevel"] < median){
                groups.underMedian.push(arrayPeoples[index]);
            }
            else{
                groups.equalMedian.push(arrayPeoples[index]);
            }
        }
    },
    /**
     * Dispatche aléatoirement les personnes d'une catégorie dans différents groupes.
     * 
     * @param {array} category Tableau de personnes par rapport à la médiane
     * @param {integer} numberGroupsNeeded Nombre de groupe nécessaire
     * @param {integer} peoplesPerGroup Nombre de personnes par groupe
     */
    dispatchPeoplesInGroup: function(category, numberGroupsNeeded, peoplesPerGroup){
        // Servira de valeur de départ pour la boucle "for". Permettra de ne pas boucler sur les groupes qui sont déjà pleins.
        let groupToStart = 0;
        while(category.length !== 0){
            
            // S'il ne reste plus assez de personnes, alors on modifie le nombre de groupe que l'on veut par le nombre de personnes restantes pour pouvoir exécuter la boucle "for".
            if(numberGroupsNeeded > category.length){
                numberGroupsNeeded = category.length;
            }
            
            // La boucle commence à "groupToStart", il faut donc rajouter "groupToStart" à numberGroupNeeded pour parcourir tout le tableau.
            let maxLoop = numberGroupsNeeded + groupToStart
            for (let people = groupToStart; people < maxLoop; people++) {

                // On vérifie si le nombre de personnes dans le groupe est inférieur au nombre maximal que l'on veut par groupe.
                if(groups.listPeoplesGroups[people].length < peoplesPerGroup){

                    // Choix aléatoire de l'indince.
                    let randomIndex = Math.floor(Math.random() * category.length);
                    //Ajout dans un groupe.
                    groups.listPeoplesGroups[people].push(category[randomIndex]);
                    // Suppression dans le tableau d'origine.
                    category.splice(randomIndex, 1);
                }
                else{
                    // On modifie groupToStart pour ne pas reboucler sur les groupes déjà pleins
                    groupToStart = people + 1;
                }
            }
        }
    },
    /**
     * Créer le nombre de groupe nécessaire.
     * 
     * @param {integer} numberOfGroup Nombre de groupes à créer.
     * @returns {array} Un tableau vide avec autant de tableaux vides que de groupe necessaire
     */
    createGroups: function(numberOfGroup){
        for(let index = 0; index < numberOfGroup; index++){
            let newGroup = [];
            groups.listPeoplesGroups.push(newGroup);
        }
    },
    getCookie: function(name){
        if(document.cookie.length == 0){
            return null;
        }
        const regSepCookie = new RegExp('(; )', 'g');
        const cookies = document.cookie.split(regSepCookie);
        for(let cookie = 0; cookie < cookies.length; cookie++){
            const regInfo = new RegExp('=', 'g');
            const infos = cookies[cookie].split(regInfo);
            if(infos[0] == name){
                return infos[1];
            }
        }
        return null;
    },
    createPeopleElement: function (firstName, lastName) {
        const allGroupsElement = document.querySelectorAll('.container-group');
        const lastGroupElement = allGroupsElement[allGroupsElement.length - 1];
        let peopleModel = `<p class="people">${firstName} ${lastName}</p>`;
        lastGroupElement.insertAdjacentHTML('beforeend',peopleModel);
    },
    createGroupElement: function (groupNumber, containerElement) { 
        let groupsModel = 
        `<div class="container-group">
            <div class="number-of-group"><p>Groupe ${groupNumber}</p></div>
        </div>
        `; 
        containerElement.insertAdjacentHTML('beforeend',groupsModel);
    },
    createListGroupsElement: function(data){
        let count = 1;
        const containerListGroups = document.querySelector('.container-list-groups');
        for(group of data){
            groups.createGroupElement(count, containerListGroups);
            for(people of group){
                groups.createPeopleElement(people['prénom'], people['nom']);
            }
            count++;
        }
    }
}
document.addEventListener("DOMContentLoaded", groups.init());


