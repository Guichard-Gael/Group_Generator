const form = {
    init: function (){
        const formElement = document.querySelector('#form');
        formElement.addEventListener('submit', form.handleStockUserInfo);
        form.createUniqID()
    },
    /**
     * Stock le nom du fichier et le nombre de personnes par groupe dans les cookies
     */
    handleStockUserInfo: function(){
        // Stockage le nombre de personnes par groupes dans un cookie 
        document.cookie = `perGroup=${document.querySelector('#perGroup').value}`;

        // Stockage du nom du fichier chargé par l'utilisateur dans un cookie
        const fileInputValue = document.querySelector('#data').files[0];
        document.cookie = `nameUserFile=${fileInputValue['name']}`;
    },
    /**
     * Génère un ID unique et le stock dans les cookies
     */
    createUniqID: function(){
        const randomID = crypto.getRandomValues(new Uint32Array(1))[0]
        document.cookie = `fileID=${randomID}`;
    }

}
