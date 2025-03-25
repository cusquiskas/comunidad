var footer = class {
    constructor (mod, obj) {
        console.log('footer.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        this.addEventos();
    };

    addEventos () {
        $('a.infoCookie').click(function () {
            Moduls.getModalbody().load({ url: 'content/cookie.html', script: false });
            construirModal({title:"Aviso sobre el uso de cookies", w:500, h:700});
        });
        
        $('a.infoPrivacidad').click(function () {
            Moduls.getModalbody().load({ url: 'content/privacidad.html', script: false });
            construirModal({title:"Política de Privacidad"});
        });
        
    }

}
