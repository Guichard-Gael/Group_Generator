<?php

function getFileUploaded(){
    if(isset($_POST) && !empty($_POST)){

        if($_FILES["fileUploaded"]["error"] > 0){
            echo "Une erreur est survenue.";
            die;
        }
        
        $maxFileSize = 100000;
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

        $tmpName = $_FILES["fileUploaded"]["tmp_name"];
        // $uniqueID = md5(uniqid(rand(), true));
        

        // $pathDownload = "../upload/" . $uniqueID . $fileExt;

        // move_uploaded_file($tmpName, $pathDownload);

        // unlink($pathDownload);
        return $tmpName;

    }
}