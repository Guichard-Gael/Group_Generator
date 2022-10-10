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
    <header>
        <a href="#" class="generate-pdf" data-html2canvas-ignore>Générer un PDF</a>
    </header>
    <section class="container-list-groups">
        <?php
            include 'functions/file.php';
            include 'functions/groups.php';

            if(!empty($_COOKIE) && isset($_COOKIE)):

                $numberGroup = intval($_COOKIE["perGroup"]);
                
                if(!empty($_POST) && isset($_POST)){

                    
                    $json = file_get_contents(getFileUploaded());
                    $json2 = json_decode($json, true);
                    $allGroups = groupGenerator($json2, $numberGroup);
                    
                }

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
                endif ;
            ?>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="../assets/js/createPDF.js"></script>
</body>
</html>