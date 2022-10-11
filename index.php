<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Générateur de groupes</title>
</head>
<body>
    <header class="container-form">
        <form method="POST" action="/php/list_groups.php" id="form" enctype="multipart/form-data">
            
            <div class="container-form-group">
                <label for="perGroup">Combien de personnes par groupes ?</label>
                <input type="number" name="perGroup" id="perGroup">
            </div>
            <div class="container-form-data">
                <label for="data">Importez votre fichier de données :</label>
                <input type="file" name="fileUploaded" id="data">
                <label for="data" class="button-file">Choose a file</label>
            </div>
            <button>Envoyer</button>
        </form>
    </header>
    <div class="instructions-container">
        <h1>Créer des groupes équilibrés rapidement!</h1>
        <p>Pour que le générateur de groupe fonctionne correctement, il faut :</p>
        <ul>
            <li>L'extension du fichier doit être "<strong>.json</strong>"</li>
            <li>La taille du fichier ne doit pas dépasser <strong>100 Ko</strong> (vérifier le nombre d'octets dans les propriétés de votre fichier)</li>
            <li>Organiser les données comme indiqué sur l'image ci-dessous</li>
            <li>Le niveau de la personne peut être écrit sans les " " MAIS <strong>ne doit pas être négatif</strong></li>
            <li>"actualLevel" / "prénom" / "nom" doivent être écrit <strong>exactement comme sur l'image</strong></li>
        </ul>
        <div class="container-img">
            <img src="../assets/images/structureFichierJSON.PNG" alt="Illustration de la structure attendue pour les données dans le fichier json">       
        </div>
    </div>
    <script src="../assets/js/form.js"></script>
</body>
</html>