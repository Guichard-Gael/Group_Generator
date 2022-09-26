<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../css/reset.css"> -->
    <link rel="stylesheet" href="../css/style.css">
    <title>Générateur de groupes</title>
</head>
<body>
    <div class="container-form">
        <form method="POST" enctype="multipart/form-data">
            <div class="container-form-group">
                <label for="inGroup">Combien de personnes par groupe ?</label>
                <input type="number" name="inGroup" id="inGroup">
            </div>
            <div class="container-form-data">
                <label for="data">Importez votre fichier de données :</label>
                <input type="file" name="fileUploaded" id="data">
                <label for="data" class="button-file">Choose a file</label>
            </div>
            <button>Envoyer</button>
        </form>
    </div>
</body>
</html>

<?php 

if(isset($_POST)){
    
    $maxSize = 50000;
    $validExt = ".json";
    
    if($_FILES["fileUploaded"]["error"] > 0){
        
        echo "Une erreur est survenue.";
        die;
    }
    
    $fileSize = $_FILES['fileUploaded']['size'];
    
    if($fileSize > $maxSize){
        echo "Le fichier est trop lourd.";
        die;
    }
    var_dump($_FILES);

    $fileName = $_FILES['fileUploaded']['name'];
    $fileExt = ".".strtolower(substr(strrchr($fileName, '.'), 1));
    var_dump($fileExt);

    if ($fileExt !== $validExt) {
        echo "Le fichier n'est pas au format json.";
        die;
    }

    $tmpName = $_FILES['fileUploaded']['tmp_name'];
    $uniqueName = md5(uniqid(rand(), true));
    $pathDirectoryFileUploaded = "upload/" . $uniqueName . $fileExt;
    var_dump($pathDirectoryFileUploaded);
    $resultUpload = move_uploaded_file($tmpName, $pathDirectoryFileUploaded);
    if($resultUpload){
        echo "Transfert terminé !";
    }
}


?>