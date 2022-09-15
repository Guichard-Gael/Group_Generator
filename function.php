<?php

// une fonction qui permet de générer des groupes de 2/3 personnes en fonction de leurs niveau (de 1 à 5), selon un tableau associatif renseigné en arguments.

function groupGenerator($arrayStudents){
    // calculer la médiane du niveau des élèves de la promo (elle sera arrondi à l'inférieur pour éviter d'avoir une médiane à 5 si une excellente promo).
    $medianPromo = calculMedian($arrayStudents);
    $underMedian = [];
    $egalMedian = [];
    $aboveMedian = [];


    // On catégorise les étudiants en fonction de leurs niveau.
    for($index = 0; $index < count($arrayStudents); $index++){

        if($arrayStudents[$index]["actualLevel"] > $medianPromo){
            $aboveMedian[] = $arrayStudents[$index];
        }
        elseif($arrayStudents[$index]["actualLevel"] < $medianPromo){
            $underMedian[] = $arrayStudents[$index];
        }
        else{
            $egalMedian[] = $arrayStudents[$index];
        }
    }

    // Dans le cas où le nombre d'étudiant est pair.
    if(count($arrayStudents) % 2 === 0){
        
        // Génération d'un nombre de variable égale au nombre de groupe dont on a besoin.
        $numberGroupsNeeded = count($arrayStudents) / 2;
        $listStudientsgroups = createGroups($numberGroupsNeeded);
        
        // Dans le cas où il y a autant d'élèves au dessus et en dessous de la médiane.
        if(count($aboveMedian) === count($underMedian)){
             // Un élève au dessus de la médiane dans chaque groupe. On complète avec les élèves en dessous de la médiane.
            $listStudientsgroups = dispatchStudients($listStudientsgroups, $aboveMedian, 1);
            $listStudientsgroups = dispatchStudients($listStudientsgroups, $underMedian, 1);
            return $$listStudientsgroups;
        }
        // Le cas où il y a plus d'élèves au dessus qu'en en dessous de la médiane ne peut pas arrivé.
        // Dans le cas où il y a moins d'élèves au dessus qu'en en dessous de la médiane.
        else{
            $numberOfGroup = (count($arrayStudents) / 2);

            // Dans le cas où il y a autant d'élèves au dessus de la médiane que de groupes créés.
            if(count($aboveMedian) === $numberOfGroup){

                // Un élève au dessus de la médiane dans chaque groupe. On complète avec les élèves en dessous de la médiane. On reprends à groupe = nombre d'élèves en dessous de la médiane + 1 avec les élèves au niveau de la médiane.
                $listStudientsgroups = dispatchStudients($listStudientsgroups, $aboveMedian, 1);
                $listStudientsgroups = dispatchStudients($listStudientsgroups, $underMedian, 1);
                $listStudientsgroups = dispatchStudients($listStudientsgroups, $egaleMedian, count($underMedian) + 1);

                return $listStudientsgroups; 
            }
            // Dans le cas où il y a moins de personnes au dessus de la médiane que de groupes créés.
            else {
                $listStudientsgroups = dispatchStudients($listStudientsgroups, $aboveMedian, 1);
                $listStudientsgroups = dispatchStudients($listStudientsgroups, $underMedian, 1);
                // S'il y a moins d'élèves égale à la médiane qu'en dessous ---> On reprend la répartition après le dernier élève au dessus de la médiane.
                if (count($egaleMedian) <= count($underMedian)) {
                    $listStudientsgroups = dispatchStudients($listStudientsgroups, $egaleMedian, count($aboveMedian) + 1);
                }
                // S'il y a plus d'élèves égale à la médiane qu'en dessous ---> On reprend la répartition après le dernier élève en dessous de la médiane.
                else{
                    $listStudientsgroups = dispatchStudients($listStudientsgroups, $egaleMedian, count($underMedian) + 1);
                }

                return $listStudientsgroups; 
            }
        }
    }
    // Dans le cas où le nombre d'étudiant est impair.
    else {
        // (count($arrayStudents) - 3) / 2 ---> groupes de 2 élèves + 1 groupe de 3 élèves.
        $numberGroupsNeeded = (count($arrayStudents) - 3) / 2 + 1;
        $listStudientsgroups = createGroups($numberGroupsNeeded);

    }
    // Savoir si le nombre d'élève est pair ou impair --> que des groupes de 2 ou un groupe de 3.
        // Si nombre pair --> nombre de groupe = nombre élèves / 2
        // Si nombre impair --> nombre de groupe = (nombre élèves -3) / 2 + 1
        // Et si nombre impair ---> de préférence il faut que le groupe de 3 soit constitué de 3 élèves avec un niveau égale à la médiane;


    // Créer un nombre de variable égale au nombre de groupe, avec comme valeur un tableau vide.

    // boucler sur le tableau renseigné en argument lors de l'appel de la fonction.
        // trier dans 3 variables différentes, les élèves en fonction de leurs niveau:
            // -les élèves au dessus de la médiane.
            // -les élèves en dessous de la médiane.
            // -les élèves égale à la médiane.

        // les possibilitée d'affectations des élèves dans les groupes:
            
            // nombre impair:
                // la variable avec niveau élève = médiane contient 3 élèves ---> c'est le groupe de 3.
                // la variable avec niveau élève = médiane contient plus que 3 élèves ---> groupe de 3 constitué aléatoirement.
                // la variable avec niveau élève = médiane contient moins que 3 élèves ---> groupe de 3 constitué avec le ou les 2 élèves puis complété avec le dernier élève qu'il restera après les affectations dans tout les groupes.

            // nombre pair: 
                // si élève > médianne et élève < médianne contiennes le même nombre d'élèves ---> 1 de chaque tableau dans chaque groupe.
                // si nombre (élève > médiane) est > à (élève < médiane):
                    // nouvelle médiane sur le tableau des élèves > médiane pour éviter que les meilleurs de la promo se retrouve ensemble.
                    // A partir de cette nouvelle médiane ---> tout les élèves au dessus vont dans des groupes différents, le reste est attribuer aléatoirement.
                // si nombre (élève > médiane) est > à (élève < médiane)  ---> 1 élève > médianne dans chaque groupe.



}

// Créer le nombre de groupe nécessaire.
function createGroups($numberOfGroup){
    $listOfGroups = [];
    for($index = 0; $index < $numberOfGroup; $index++){
        ${'group'.($index+1)} = [];
        $listOfGroups[] = ${'group'.($index+1)};
    }
    return $listOfGroups;
}

// Dispatche aléatoirement les élèves d'une catégorie dans des groupes différents.
function dispatchStudients($arrayGroups, $category, $groupToStart){
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

// Vérifie si le tableau est ordonné dans l'ordre croissant.
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


// Réorganise le tableau dans l'ordre croissant.
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

// Calcul de la médiane
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

// $test = [
//     ["actualLevel" => 1],
//     ["actualLevel" => 8],
//     ["actualLevel" => 4],
//     ["actualLevel" => 2],
//     ["actualLevel" => 12],
//     ["actualLevel" => 6],
//     ["actualLevel" => 13]
// ];

// $test2 = [
//     ["actualLevel" => 1],
//     ["actualLevel" => 2],
//     ["actualLevel" => 4],
//     ["actualLevel" => 6],
//     ["actualLevel" => 8],
//     ["actualLevel" => 12],
//     ["actualLevel" => 13]
// ];




// $arrayStudents = [
//     ["actualLevel" => 1],
//     ["actualLevel" => 8],
//     ["actualLevel" => 4],
//     ["actualLevel" => 2],
//     ["actualLevel" => 12],
//     ["actualLevel" => 6]
// ];

$underMedian = [
    ["actualLevel" => 1],
    ["actualLevel" => 2],
    ["actualLevel" => 4]
];

$aboveMedian = [
    ["actualLevel" => 6],
    ["actualLevel" => 8],
    ["actualLevel" => 12]
];

// var_dump($group1);
// var_dump($group2);
// var_dump($group3);


$result5 = createGroups(5);
$test = dispatchStudients($result5,$underMedian, 2);
$test2 = dispatchStudients($test,$aboveMedian, 2);

var_dump($result5);
var_dump($test);
var_dump($test2);

