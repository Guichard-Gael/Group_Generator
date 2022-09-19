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
    <table>
        <thead>
            <tr>
                <th>Groupe n°</th>
                <th>Prénom</th>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>

        <?php
            include('functions/function.php');
            $json = file_get_contents('../data.json');
            $json2 = json_decode($json, true);
            $numberGroup = intval($_POST["perGroup"]);
            $students = groupGenerator($json2, $numberGroup);

            foreach($students as $student):
                for ($index=0; $index < count($student); $index++):          
            ?>
            <tr>
                <td class="groupe<?= $student[$index]["groupe"] ?>"><?= $student[$index]["groupe"] ?></td>
                <td class="groupe<?= $student[$index]["groupe"] ?>"><?= $student[$index]["prénom"] ?></td>
                <td class="groupe<?= $student[$index]["groupe"] ?>"><?= $student[$index]["nom"] ?></td>
            </tr>
            <?php
                endfor;
            endforeach;
            ?>
        </tbody>
    </table>
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