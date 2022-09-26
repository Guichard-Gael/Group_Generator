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
        <a href="genpdf.php">Générer un PDF</a>
        <form method="POST" enctype="multipart/form-data">
            <div class="container-form-group">
                <label for="perGroup">Combien de personnes par groupe ?</label>
                <input type="number" name="perGroup" id="perGroup">
            </div>
            <div class="container-form-data">
                <label for="data">Importez votre fichier de données :</label>
                <input type="file" name="fileUploaded" id="data">
                <label for="data" class="button-file">Choose a file</label>
            </div>
            <button>Envoyer</button>
        </form>
    </div>
    <section class="container-list-groups">
        <?php
            include('functions/groups.php');

            $json = file_get_contents('../data.json');
            $json2 = json_decode($json, true);
            $numberGroup = intval($_POST["perGroup"]);
            $students = groupGenerator($json2, $numberGroup);
            
            foreach($students as $index => $student):
        ?>
        <div class="container-group">
            <div class="number-of-group"><p>Groupe <?= $index + 1 ?></p></div>
            <?php
                for ($index=0; $index < count($student); $index++):          
            ?>
            <p class="people"><?= $student[$index]['prénom']. " ". $student[$index]['nom'] ?></p>
            <?php
                endfor;
            ?>
        </div>
        <?php
            endforeach;
        ?>
    </section>

</body>
</html>

<?php 


// echo "<pre>";
// var_dump($json2[0]["nom"]);
// echo "<pre>";

// $json = file_get_contents($_FILES["../data.json"]);
// echo "<pre>";
// var_dump(json_decode($json));
// echo "<pre>";


?>