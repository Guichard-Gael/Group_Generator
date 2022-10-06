
const remove = {
    init: function () { 
        console.log('initialisation module remove');
        const linkGeneratePDF = document.querySelector('.generate-css');
        console.log(linkGeneratePDF);
        linkGeneratePDF.addEventListener('click', remove.handleRemoveCSS);
    },
    handleRemoveCSS: function(event){
        const containerListGroups = document.querySelector('.container-list-groups');
        event.preventDefault();
        //  const linkCSS = document.querySelector('.remove-css');
        //  linkCSS.remove();

        let newPDF = new jsPDF();
        newPDF.fromHTML(containerListGroups);
        newPDF.setFontType("bold");
        newPDF.save("test.pdf");

        // html2canvas(containerListGroups).then(canvas => document.body.appendChild(canvas));
        
     }
}
document.addEventListener("DOMContentLoaded", remove.init());
