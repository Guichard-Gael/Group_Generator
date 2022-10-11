<?php
/**
 * Vérifie la conformité de l'extension et du poids du fichier
 *
 * @return string Retourne le chemin temporaire du fichier de l'utilisateur 
 */
function getFileUploaded(){
    if(isset($_POST) && !empty($_POST)){

        if($_FILES["fileUploaded"]["error"] > 0){
            echo createErrorMessage("Le fichier n'a pas pu être téléchargé");
            die;
        }
        
        $maxFileSize = 100000;
        $fileSize = $_FILES["fileUploaded"]["size"];
        
        if($fileSize > $maxFileSize){
            echo createErrorMessage("Le fichier est trop lourd.");
            die;
        }
        
        $fileName = $_FILES["fileUploaded"]["name"];
        $fileExt = ".".strtolower(substr(strrchr($fileName, '.'),1));
        $valideExtension = ".json";

        if($fileExt !== $valideExtension){
            echo createErrorMessage("Ce n'est pas un fichier json.");
            die;
        }

        // Chemin temporaire du fichier
        $tmpName = $_FILES["fileUploaded"]["tmp_name"];

        return $tmpName;

    }
}

/**
 * Créer un message d'erreur
 *
 * @param string $message Message à afficher
 * @return void Retourne l'erreur structurée et stylisée
 */
function createErrorMessage($message){
    $error =
    "
    <div class='container-error'>
        <p>{$message}</p>
        <a href='../index.php' class='back-to-home'>Retour à l'accueil</a>
    </div>
    ";

    return $error;
}
/**
 * Vérifie la conformité des données dans le fichier importé par l'utilisateur
 *
 * @param [type] $userData
 * @return void
 */
function checkUserData($userData){

    // Vérifie si le fichier est vide
    if(empty($userData)){
        echo createErrorMessage("Le fichier est vide");
        die;
    }

    // Pour chaque donnée dans le fichier
    foreach($userData as $data){
        // Vérifie si les clés existe
        if(!isset($data["actualLevel"]) || !isset($data["prénom"]) || !isset($data["nom"])) {
            echo createErrorMessage("'actualLevel', 'prénom' et 'nom' ne sont pas présents pour chaque personne ou sont mal orthographiés.");
            die;
        }
        
        // Vérifie si toutes les clés ont des valeurs
        if(empty($data["actualLevel"]) || empty($data["prénom"]) || empty($data["nom"])) {
            echo createErrorMessage("Il manque une ou plusieurs valeur(s) pour au moins une personne");
            die;
        }

        // Regex pour vérifier que la valeur ne contient que des chiffres
        preg_match('#^\d+$#', $data['actualLevel'], $matches);

        if(empty($matches)){
            echo createErrorMessage("Une ou plusieurs valeur(s) pour 'actualLevel' n'est pas un nombre conforme ou est inférieur à zéro");
            die;
        }

        // Vérifie le type de données et la valeur pour 'actualLevel'
        if(
            gettype($data["actualLevel"]) !== "integer" && 
            gettype($data["actualLevel"]) !== "double" && 
            gettype($data["actualLevel"]) !== "string"
        ){
            echo createErrorMessage("Valeur de 'actualLevel' incorrect pour une ou plusieurs personnes");
            die;
        }

        // Vérifie le type de données pour 'prénom'
        if(gettype($data["prénom"]) !== "string"){
            echo createErrorMessage("Valeur de 'prénom' incorrect pour une ou plusieurs personnes");
            die;
        }

        // Vérifie le type de données pour 'nom'
        if(gettype($data["nom"]) !== "string"){
            echo createErrorMessage("Valeur de 'nom' incorrect pour une ou plusieurs personnes");
            die;
        }
    }
}