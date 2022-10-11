<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Document</title>
</head>
<body>
    <header class="container-download" data-html2canvas-ignore>
        <a href="../index.php" class="back-to-home"><img src="../assets/images/home-solid.svg" alt="icone d'une maison"></a>
        <a href="#" class="generate-pdf">Générer un PDF</a>
        <div class="download-bar">
            <div class="download-animation"></div>
            <p class="placeholder">Barre de téléchargement</p>
        </div>
    </header>
    <section class="container-list-groups">
        <?php
            include 'functions/file.php';
            include 'functions/groups.php';

            // L'utilisateur n'a pas renseigné le nombre de personnes par groupes
            if(!isset($_COOKIE["perGroup"]) || empty($_COOKIE["perGroup"])){
                echo createErrorMessage("Veuillez renseigner le nombre de personnes par groupes");
                die;
            }
            // Si le nombre de personnes par groupes est inférieur à 2
            if ($_COOKIE["perGroup"] < 2) {
                echo createErrorMessage("Le nombre de personnes par groupes ne peut pas être inférieur à 2.");
                die;
            }
            
            $numberGroup = intval($_COOKIE["perGroup"]);
            $user_json_data = file_get_contents(getFileUploaded());
            $user_data = json_decode($user_json_data, true);

            // Vérification de la conformité des données
            checkUserData($user_data);
            
            $allGroups = groupGenerator($user_data, $numberGroup);

            foreach($allGroups as $index => $group):
                ?>
                <div class="container-group">
                    <div class="number-of-group"><p>Groupe <?= $index + 1 ?></p></div>
                <?php foreach($group as $people): ?>
                    <p class="people"><?= $people['prénom'] ?> <?= $people['nom'] ?></p>
                <?php endforeach; ?>       
                </div>
        <?php 
            endforeach; 
        ?>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../assets/js/createPDF.js"></script>
</body>
</html>