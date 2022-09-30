const remove = {
    init: function () { 
        const linkGeneratePDF = document.querySelector('.generate-css');
        console.log(linkGeneratePDF);
        linkGeneratePDF.addEventListener('click', this.handleRemoveCSS);
    },
    handleRemoveCSS: function(event){
        event.preventDefault();
        //  const linkCSS = document.querySelector('.remove-css');
        //  linkCSS.remove();
        fetch('../php/genpdf.php')
        .then(response => console.log(location.url = response.url))
        
     }
}
document.addEventListener("DOMContentLoaded", remove.init());