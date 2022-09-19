<?php
/**
 * fonction qui permet de générer des groupes de personnes en fonction de leurs niveau (de 1 à 5), selon un tableau associatif renseigné en argument.
 *
 * @param array $arrayStudents
 * @return array
 */


function groupGenerator($arrayStudents, $studientsPerGroup){
    // calculer la médiane du niveau des élèves de la promo (elle sera arrondi à l'inférieur pour éviter d'avoir une médiane à 5 si une excellente promo).
    $medianPromo = calculMedian($arrayStudents);
    $underMedian = [];
    $equalMedian = [];
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
            $equalMedian[] = $arrayStudents[$index];
        }
    }

    // Génère un nombre de groupe (arrondi au supérieur).
    $numberGroupsNeeded = ceil(count($arrayStudents) / $studientsPerGroup);
    $listStudientsgroups = createGroups($numberGroupsNeeded);

    // Les élèves au dessus de la médiane sont dispatchés.
    $listStudientsgroups = dispatchStudientsInGroup($listStudientsgroups, $aboveMedian, $numberGroupsNeeded, $studientsPerGroup);
    // Les élèves en dessous de la médiane sont dispatchés.
    $listStudientsgroups = dispatchStudientsInGroup($listStudientsgroups, $underMedian, $numberGroupsNeeded, $studientsPerGroup);
    // On complète les groupes avec le reste des élèves.
    $listStudientsgroups = dispatchStudientsInGroup($listStudientsgroups, $equalMedian, $numberGroupsNeeded, $studientsPerGroup);

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
 * @param array $listGroups liste des groupes d'étudiants.
 * @param array $category catégorie des étudiants par rapport à la médiane.
 * @param int $numberGroupsNeeded nombre de groupe dont on a besoin.
 * @param int $studientsPerGroup  Nombre d'étudiants par groupe.
 * @return array
 */

function dispatchStudientsInGroup($listGroups, $category, $numberGroupsNeeded, $studientsPerGroup){
    // La boucle commence à "$groupToStart", il faut donc rajouter "$groupToStart" à count($category) pour parcourir tout le tableau.
    while(!empty($category)){
        // Initialisation de la boucle à l'index "0". Permettra par la suite de ne pas boucler sur les groupes qui sont déjà pleins.
        $groupToStart = 0;

        // S'il ne reste plus assez d'élèves, alors on modifie le nombre de groupe que l'on veut par le nombre d'élèves restant pour pouvoir exécuter la boucle "for".
        if($numberGroupsNeeded > count($category)){
            $numberGroupsNeeded = count($category);
        }
        
        for ($student = $groupToStart; $student < ($numberGroupsNeeded + $groupToStart); $student++) {

            // Si le nombre de personnes dans le groupe est inférieur au nombre maximal que l'on veut par groupe, alors on ajoute un nouvel étudiant.
            if(count($listGroups[$student]) < $studientsPerGroup){

                // Choix aléatoire de l'indince d'un élève.
                $indexRandomStudent = array_rand($category, 1);
                // Ajout du numéro de groupe en information sur l'étudiant.
                $category[$indexRandomStudent]["groupe"] = $student + 1;
                //Ajout de l'élève dans un groupe.
                $listGroups[$student][] = $category[$indexRandomStudent];
                // Suppression de l'élève du tableau d'origine.
                array_splice($category, $indexRandomStudent, 1);
            }
            else{
                $groupToStart = $student + 1;
            }
        }
    }
    return $listGroups;
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

        return $median;
    }
    else{
        // Indice valeur de la moitier du tableau = (longueur tableau + 1) / 2. On soustrait -1  à la fin car un tableau commence à l'indice 0.
        $median = round($array[((count($array) + 1) / 2) - 1]["actualLevel"], PHP_ROUND_HALF_DOWN);
        return $median;
    }
}

function getFileUploaded(){
    if(isset($_POST) && !empty($_POST)){
        
        if($_FILES["fileUploaded"]["error"] > 0){
            echo "Une erreur est survenue.";
            die;
        }
        
        $maxFileSize = 1000000;
        $fileSize = $_FILES["fileUploaded"]["size"];
        
        if($fileSize > $maxFileSize){
            echo "Le fichier est trop lourd.";
            die;
        }
        
        $fileName = $_FILES["fileUploaded"]["name"];
        $fileExt = ".".strtolower(substr(strrchr($fileName, '.'),1));
        $valideExtension = ".json";
        if($fileExt !== $valideExtension){
            echo "Ce n'est pas un fichier json!";
        }

        // $tmpFileName = $_FILES["fileUploaded"]["tmp_name"];
        // $uniqueName = md5(uniqid(rand(), true));
        // $pathFileUploaded = "upload/" . $uniqueName . $fileExt;
        // $resultUPload = move_uploaded_file($tmpFileName, $pathFileUploaded);
            // if($resultUpload){
    //     echo "Transfert terminé !";
    // }
    }
}