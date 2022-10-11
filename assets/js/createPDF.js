
const createPDF = {
    progressBar: {},
    init: function () { 
        const linkGeneratePDF = document.querySelector('.generate-pdf');
        linkGeneratePDF.addEventListener('click', createPDF.handleCreationPDF);
        createPDF.progressBar = document.querySelector('.download-animation');
    },
    /**
     * Déclenche la création du PDF
     * @param {*} event 
     */
    handleCreationPDF: function(event){
        event.preventDefault();
        createPDF.progressBar.classList.add('active');
        // Modifie le style de la page avant la création du PDF
        document.body.classList.add('screen');

        createPDF.takeScreenShot();

        // Rétablie le style par défaut de la page
        document.body.classList.remove('screen');

    },
    /**
     * Prend une Capture d'écran de la page
     */
    takeScreenShot: function() {

        // Capture d'écran de la page
        html2canvas(document.body).then(function(canvas) {

            createPDF.createPDF(canvas);

        });
    },
    /**
     * Génère le PDF
     * @param {any} canvas Canvas généré par html2canvas
     */
    createPDF: function (canvas) { 

        const imgListGroups = canvas.toDataURL('image/jpeg');
        const imgWidth = 210; 
        const pageHeight = 295; 
        const imgHeight = canvas.height * imgWidth / canvas.width;
        let heightLeft = imgHeight;
        // Nouveau PDF
        const newPDF = new jsPDF();

        // Position de départ du PDF par rapport au haut de l'image
        let positionY = 0; 

        // Ajout d'une portion de l'image au PDF
        newPDF.addImage(imgListGroups, 'JPEG', 0, positionY, imgWidth, imgHeight, 'FAST');
        heightLeft -= pageHeight;

        // Partie suppérieur de l'image à ne pas afficher
        let padding = heightLeft - imgHeight;

        // Tant que le bas de l'image n'est pas atteint
        while (heightLeft >= 0) {

            positionY += padding;
            // Nouvelle page au PDF
            newPDF.addPage();
            // Ajout d'une nouvelle portion de l'image à la nouvelle page
            newPDF.addImage(imgListGroups, 'JPEG', 0, positionY, imgWidth, imgHeight, 'FAST');
            // Savoir si le bas de l'image à été atteint
            heightLeft -= pageHeight;
        }
        
        // Téléchargement du PDF par l'utilisateur
        newPDF.save( 'list_groups.pdf');
        createPDF.progressBar.classList.remove('active');
    }
}
document.addEventListener("DOMContentLoaded", createPDF.init());
