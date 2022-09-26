<?php
/**
 * fonction qui permet de générer des groupes de personnes en fonction de leurs niveau (de 1 à 5), selon un tableau associatif renseigné en argument.
 *
 * @param array $arrayStudents
 * @return array
 */


function groupGenerator($arrayStudents){
    // calculer la médiane du niveau des élèves de la promo (elle sera arrondi à l'inférieur pour éviter d'avoir une médiane à 5 si une excellente promo).
    $medianPromo = calculMedian($arrayStudents);
    $underMedian = [];
    $equaMedian = [];
    $aboveMedian = [];
    $listStudientsgroups = [];

    // On catégorise les étudiants en fonction de leurs niveau.
    for($index = 0; $index < count($arrayStudents); $index++){

        if($arrayStudents[$index]["actualLevel"] > $medianPromo){
            $aboveMedian[] = $arrayStudents[$index];
        }
        elseif($arrayStudents[$index]["actualLevel"] < $medianPromo){
            $underMedian[] = $arrayStudents[$index];
        }
        else{
            $equaMedian[] = $arrayStudents[$index];
        }
    }

    // Dans le cas où le nombre d'étudiant est pair.
    if(count($arrayStudents) % 2 === 0){
        
        // Génération d'un nombre de variable égale au nombre de groupe dont on a besoin.
        $numberGroupsNeeded = count($arrayStudents) / 2;
        $listStudientsgroups = createGroups($numberGroupsNeeded);
    }
    // Dans le cas où le nombre d'étudiant est impair.
    else {
        // On soustrait 3 au total des élèves (pour un groupe de 3), on divise le reste par deux pour des groupes de 2.
        echo "Dans le else , impair";
        $numberGroupsNeeded = (count($arrayStudents) - 3) / 2 + 1;
        var_dump($numberGroupsNeeded);
        $listStudientsgroups = createGroups($numberGroupsNeeded);
    }
    var_dump($listStudientsgroups);
    // Les élèves au dessus de la médiane sont dispatchés.
    $listStudientsgroups = dispatchStudientsInGroup($listStudientsgroups, $aboveMedian, 1);
    // Les élèves en dessous de la médiane sont dispatchés.
    $listStudientsgroups = dispatchStudientsInGroup($listStudientsgroups, $underMedian, 1);
    // On complète les groupes avec le reste des élèves.

    // Trouver l'index du premier groupe à 1 élève.
    // Commence l'ajout des derniers élèves à partir de ce groupe.
    // Tant qu'un groupe n'est pas plein, on ne passe pas au suivant.

    for($groupe = 0; $groupe < count($listStudientsgroups); $groupe++){
        while(count($listStudientsgroups[$groupe]) < 2){

            $indexRandomStudent = array_rand($equaMedian, 1);

            $listStudientsgroups[$groupe][] = $equaMedian[$indexRandomStudent];

            array_splice($equaMedian, $indexRandomStudent, 1);
        }
    }

    // S'il reste 1 élève, on le rajoute dans le dernier groupe.
    if(count($equaMedian) === 1){
        $listStudientsgroups[count($listStudientsgroups) - 1][] = $equaMedian[0];
    }

    return $listStudientsgroups;
}

/**
 * Créer le nombre de groupe nécessaire.
 *
 * @param int $numberOfGroup
 * @return array
*/

function createGroups($numberOfGroup){
    $listOfGroups = [];
    for($index = 0; $index < $numberOfGroup; $index++){
        ${'group'.($index+1)} = [];
        $listOfGroups[] = ${'group'.($index+1)};
    }
    return $listOfGroups;
}

/**
 * Dispatche aléatoirement les élèves d'une catégorie dans des groupes différents.
 *
 * @param array $arrayGroups
 * @param array $category
 * @param int $groupToStart
 * @return array
 */

function dispatchStudientsInGroup($arrayGroups, $category, $groupToStart){
    // La boucle commence à "$groupToStart", il faut donc rajouter "$groupToStart" à count($category) pour parcourir tout le tableau.
    $loopNeeded = count($category) + $groupToStart;
    for ($student = $groupToStart; $student < $loopNeeded; $student++) {
        // S'il reste des étudiants dans la catégorie.
        if (!empty($category)) {
            // Choix aléatoire de l'indince d'un élève.
            $indexRandomStudent = array_rand($category, 1);
            //Ajout de l'élève dans un groupe.
            $arrayGroups[$student - 1][] = $category[$indexRandomStudent];
            // Suppression de l'élève du tableau d'origine.
            array_splice($category, $indexRandomStudent, 1);
        }
    }
    return $arrayGroups;
}


/**
 * Vérifie si le tableau est ordonné dans l'ordre croissant.
 *
 * @param array $array
 * @return bool
 */

function checkOrderArray($array){
    // Comparaison d'une valeur avec celle qui la précède. Empêche la boucle de sortir du tableau. Il faut donc commencer à l'indice 1.
    for($index = 1; $index < count($array); $index++){
        // Variable qui récupère l'élément à l'indice précédent pour comparer les deux valeurs.
        $previousElement = $array[$index - 1]["actualLevel"];
        if ($previousElement < $array[$index]["actualLevel"]) {
            $previousElement = $array[$index];
        }
        else{
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
 * @param array $arrayToOrder
 * @return array
 */

function toOrderArray($arrayToOrder){

    while(!checkOrderArray($arrayToOrder)){

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
 * @param array $array
 * @return float
 */

function calculMedian($array){
    // Dans le cas ou le nombre de valeur dans le tableau est pair
    if(count($array) % 2 === 0){
        // On prend comme indice la moitier de la longueur du tableau -1 (car un tableau commence à l'indice 0).
        $middleValue = $array[(count($array) / 2) - 1]["actualLevel"];
        // On prend comme indice la moitier de la longueur du tableau.
        $nextValueOfMiddle = $array[count($array) / 2]["actualLevel"];
        // Moyenne des deux valeurs arondi à l'inférieur.
        $median = round(($middleValue + $nextValueOfMiddle) / 2, PHP_ROUND_HALF_DOWN);
        var_dump($middleValue);
        var_dump($nextValueOfMiddle);
        return $median;
    }
    else{
        // Indice valeur de la moitier du tableau = (longueur tableau + 1) / 2. On soustrait -1  à la fin car un tableau commence à l'indice 0.
        $median = round($array[((count($array) + 1) / 2) - 1]["actualLevel"], PHP_ROUND_HALF_DOWN);
        return $median;
    }
}


$arrayStudents = [
    ["actualLevel" => 1],
    ["actualLevel" => 1],
    ["actualLevel" => 1],
    ["actualLevel" => 1],
    ["actualLevel" => 1],
    ["actualLevel" => 1],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 3],
    ["actualLevel" => 5],
    ["actualLevel" => 5],
    ["actualLevel" => 5],
    ["actualLevel" => 5],
    ["actualLevel" => 5]
];

var_dump(count($arrayStudents));
var_dump(groupGenerator($arrayStudents));