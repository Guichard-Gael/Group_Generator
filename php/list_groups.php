<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../css/stylePDF.css"> -->
    <link rel="stylesheet" href="../css/style.css" class="remove-css">
    <title>Document</title>
</head>
<body>
    <a href="genpdf.php" class="generate-css">Générer un PDF</a>
    <section class="container-list-groups">
        <?php
            include 'functions/file.php';
            include 'functions/groups.php';

            if(!empty($_COOKIE) && isset($_COOKIE)){

                $numberGroup = intval($_COOKIE["perGroup"]);
                
                if(!empty($_POST) && isset($_POST)){

                    $uniqueID = md5(uniqid(rand(), true));
                    $json = file_get_contents(getFileUploaded($_COOKIE["fileID"]));
                    $json2 = json_decode($json, true);
                    $students = groupGenerator($json2, $numberGroup);
                }
            }
        ?>
    </section>
        <script src="../js/groups.js"></script>
        <script src="../js/removeCSS.js"></script>
</body>
</html>