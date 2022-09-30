const form = {
    init: function (){
        const formElement = document.querySelector('#form');
        formElement.addEventListener('submit', form.handleStockUserInfo);
        console.log("Initialisation de form");
        form.createUniqID()

    },
    handleStockUserInfo: function(){
        console.log("Evenement lancé");
        // Stockage de la valeur "input[type='number']" dans un cookie 
        document.cookie = `perGroup=${document.querySelector('#perGroup').value}`;

        // Stockage du nom du fichier chargé par l'utilisateur dans un cookie
        const fileInputValue = document.querySelector('#data').files[0];
        fileInputValue['name']="123456";
        document.cookie = `nameUserFile=${fileInputValue['name']}`;


        // Téléchargement du fichier inporter par l'utilisateur
        form.downloadUserFile(fileInputValue);
    },
    downloadUserFile: function (fileInputValue) { 
        const formData = new FormData();
        formData.append("perGroup", fileInputValue);

        fetch("../php/test.php",{
        method: "post",
        body: formData
        }).catch(console.error);
     },
     createUniqID: function(){
        const randomID = crypto.getRandomValues(new Uint32Array(1))[0]
        document.cookie = `fileID=${randomID}`;
     }

}
