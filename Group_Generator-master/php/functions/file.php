<?php

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
    }
}