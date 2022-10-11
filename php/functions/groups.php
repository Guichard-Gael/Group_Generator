<?php
/**
 * fonction qui permet de générer des groupes de personnes en fonction de leurs niveau (de 1 à 5), selon un tableau associatif renseigné en argument.
 *
 * @param array $arrayPeoples  Tableau des personnes à dispatcher.
 * @param int $peoplesPerGroup  Nombre de personnes par groupe.
 * @return array
 */
function groupGenerator($arrayPeoples, $peoplesPerGroup){

    $underMedian = [];
    $equalMedian = [];
    $aboveMedian = [];
    
    // calculer la médiane du niveau des personnes (elle sera arrondi à l'inférieur pour éviter d'avoir une médiane à 5 si le niveau est élevé).
    $median = calculMedian($arrayPeoples);

    // On regroupe les personnes en fonction de leur niveau.
    for($index = 0; $index < count($arrayPeoples); $index++){

        if($arrayPeoples[$index]["actualLevel"] > $median){
            $aboveMedian[] = $arrayPeoples[$index];
        }
        elseif($arrayPeoples[$index]["actualLevel"] < $median){
            $underMedian[] = $arrayPeoples[$index];
        }
        else{
            $equalMedian[] = $arrayPeoples[$index];
        }
    }

    // Nombre de groupe (arrondi au supérieur).
    $numberGroupsNeeded = ceil(count($arrayPeoples) / $peoplesPerGroup);
    // Création des groupes
    $listPeoplesgroups = createGroups($numberGroupsNeeded);

    // Les personnes au-dessus de la médiane sont dispatchées.
    $listPeoplesgroups = dispatchPeoplesInGroup($listPeoplesgroups, $aboveMedian, $numberGroupsNeeded, $peoplesPerGroup);
    // Les personnes en-dessous de la médiane sont dispatchées.
    $listPeoplesgroups = dispatchPeoplesInGroup($listPeoplesgroups, $underMedian, $numberGroupsNeeded, $peoplesPerGroup);
    // On complète les groupes avec le reste des personnes.
    $listPeoplesgroups = dispatchPeoplesInGroup($listPeoplesgroups, $equalMedian, $numberGroupsNeeded, $peoplesPerGroup);

    return $listPeoplesgroups;
}

/**
 * Créer le nombre de groupe nécessaire.
 *
 * @param int $numberOfGroup  Nombre de groupes à créer.
 * @return array Tableau contenant autant de tableaux vides que de groupe nécessaire
*/
function createGroups($numberOfGroup){

    $listOfGroups = [];

    for($index = 0; $index < $numberOfGroup; $index++){
        $listOfGroups[] = [];
    }

    return $listOfGroups;
}

/**
 * Dispatche aléatoirement les personnes d'une catégorie dans différents groupes.
 *
 * @param array $listGroups  Tableau contenant les groupes à remplir.
 * @param array $category  Catégorie des personnes par rapport à la médiane.
 * @param int $numberGroupsNeeded  Nombre de groupe nécessaire.
 * @param int $peoplesPerGroup  Nombre de personnes par groupe.
 * @return array Tableau remplit avec les personnes de la catégorie
 */
function dispatchPeoplesInGroup($listGroups, $category, $numberGroupsNeeded, $peoplesPerGroup){

    // Contrôle pour que la boucle "for" ne sorte jamais du tableau
    $limitMax = $numberGroupsNeeded;
    // Initialisation de la boucle à l'index "0". Permettra par la suite de ne pas boucler sur les groupes qui sont déjà pleins.
    $groupToStart = 0;
    while(!empty($category)){

        // S'il ne reste plus assez de personnes, alors on modifie le nombre de groupe que l'on veut par le nombre de personnes restantes pour pouvoir exécuter la boucle "for".
        if($numberGroupsNeeded > count($category)){
            $numberGroupsNeeded = count($category);
        }
        
        $maxLoops = $numberGroupsNeeded + $groupToStart;

        // Si la condition d'arrêt dépasse le nombre maximum de groupes
        if ($maxLoops > $limitMax) {
            // La condition d'arrêt devient le maximum de groupes
            $maxLoops  = $limitMax;
        }
        // La boucle commence à "$groupToStart", il faut donc rajouter "$groupToStart" à count($category) pour parcourir tout le tableau.
        for ($people = $groupToStart; $people < $maxLoops; $people++) {
            
            // On vérifie si le nombre de personnes dans le groupe est inférieur au nombre maximal que l'on veut par groupe.
            if(count($listGroups[$people]) < $peoplesPerGroup){

                // Choix aléatoire de l'indice.
                $indexRandomPeople = array_rand($category, 1);
                //Ajout dans un groupe.
                $listGroups[$people][] = $category[$indexRandomPeople];
                // Suppression dans le tableau d'origine.
                array_splice($category, $indexRandomPeople, 1);
            }
            else{
                
                // On modifie groupToStart pour ne pas reboucler sur les groupes déjà pleins
                $groupToStart = $people + 1;
            }
        }
    }
    return $listGroups;
}


/**
 * Vérifie si le tableau est ordonné dans l'ordre croissant.
 *
 * @param array $arrayToCheck  Tableau à ordonner.
 * @return bool
 */
function isOrderArray($arrayToCheck){
    // Comparaison d'une valeur avec celle qui la précède. Empêche la boucle de sortir du tableau. Il faut donc commencer à l'indice 1.
    for($index = 1; $index < count($arrayToCheck); $index++){
        // Variable qui récupère l'élément à l'indice précédent pour comparer les deux valeurs.
        $previousElement = $arrayToCheck[$index - 1]["actualLevel"];
        // Si la valeur précédente est plus grande que la valeur actuelle
        if ($previousElement > $arrayToCheck[$index]["actualLevel"]) {
            
            // Le tableau n'est pas ordonné.
            return false;
        }
    }
    // Le tableau est ordonné.
    return true;
}

/**
 * Réorganise le tableau dans l'ordre croissant.
 *
 * @param array $arrayToOrder Tableau à ordonner.
 * @return array
 */
function orderArray($arrayToOrder){

    while(!isOrderArray($arrayToOrder)){
        // Comparaison d'une valeur avec celle qui la précède. Empêche la boucle de sortir du tableau. Il faut donc commencer à l'indice 1.
        for($index = 1; $index < count($arrayToOrder); $index++){
            // Variable qui récupère l'élément à l'indice précédent pour comparer les deux valeurs. Sert aussi de stockage temporaire pour intervertir les deux valeurs si l'ordre n'est pas correcte.
            $previousElement = $arrayToOrder[$index - 1];
            if ($previousElement["actualLevel"] > $arrayToOrder[$index]["actualLevel"]) {
                // Changement de l'ordre des deux valeurs comparées.
                $arrayToOrder[$index-1] = $arrayToOrder[$index];
                $arrayToOrder[$index] = $previousElement;
            }
        }
    }
    return $arrayToOrder;
}


/**
 * Calcul de la médiane
 *
 * @param array $listPeople  Tableau contenant toutes les personnes à dispatcher.
 * @return float
 */
function calculMedian($listPeoples){

    // Tri les données du tableau en ordre croissant en fonction du niveau des personnes
    $listPeoplesOrdered = orderArray($listPeoples);

    // Dans le cas ou le nombre de valeur dans le tableau est pair
    if(count($listPeoplesOrdered) % 2 === 0){
        // On prend comme indice la moitier de la longueur du tableau -1 (car un tableau commence à l'indice 0).
        $middleValue = intval($listPeoplesOrdered[(count($listPeoplesOrdered) / 2) - 1]["actualLevel"]);
        // On prend comme indice la moitier de la longueur du tableau.
        $nextValueOfMiddle = intval($listPeoplesOrdered[count($listPeoplesOrdered) / 2]["actualLevel"]);
        // Moyenne des deux valeurs arondi à l'inférieur.
        $median = round(($middleValue + $nextValueOfMiddle) / 2, PHP_ROUND_HALF_DOWN);

    }
    else{
        // Indice valeur de la moitier du tableau = (longueur tableau + 1) / 2. On soustrait -1  à la fin car un tableau commence à l'indice 0.
        $median = round($listPeoplesOrdered[((count($listPeoplesOrdered) + 1) / 2) - 1]["actualLevel"], PHP_ROUND_HALF_DOWN);
    }

    return $median;
}