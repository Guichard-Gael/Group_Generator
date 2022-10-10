const form = {
    init: function (){
        const formElement = document.querySelector('#form');
        formElement.addEventListener('submit', form.handleStockUserInfo);
    },
    /**
     * Stock le nombre de personnes par groupes dans un cookie
     */
    handleStockUserInfo: function(){
        // Stockage le nombre de personnes par groupes dans un cookie 
        document.cookie = `perGroup=${document.querySelector('#perGroup').value}`;
    }
}
document.addEventListener('DOMContentLoaded', form.init);