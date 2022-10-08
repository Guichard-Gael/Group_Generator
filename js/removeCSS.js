
const remove = {
    init: function () { 
        console.log('initialisation module remove');
        const linkGeneratePDF = document.querySelector('.generate-css');
        console.log(linkGeneratePDF);
        linkGeneratePDF.addEventListener('click', remove.handleRemoveCSS);
    },
    handleRemoveCSS: function(event){
        event.preventDefault();

        // Modifie le style de la page avant la modification du PDF
        document.body.classList.add('screen');
        html2canvas(document.body).then(function(canvas) {

            // Capture d'écran de la div "container-list-groups"
            const imgListGroups = canvas.toDataURL('image/png');

            const imgWidth = 210; 
            const pageHeight = 295;  
            const imgHeight = canvas.height * imgWidth / canvas.width;
            console.log(imgHeight);
            let heightLeft = imgHeight;
            const doc = new jsPDF();
            let position = 0; 

            doc.addImage(imgListGroups, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
            let padding = heightLeft - imgHeight;

            while (heightLeft >= 0) {

                position += padding; // top padding for other pages
                doc.addPage();
                doc.addImage(imgListGroups, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

            }
        
            doc.save( 'file.pdf');
        });
        // Rétablie le style par défaut de la page
        document.body.classList.remove('screen');

     }
}
document.addEventListener("DOMContentLoaded", remove.init());
