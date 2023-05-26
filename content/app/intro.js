var intro = class {
    constructor (mod, obj) {
        console.log('intro.js -> constructor');
        if (sessionStorage.getItem('id') !== "") {
            Moduls.getBody().load({ url: 'content/app/panelprincipal.html', script: true });
        } else {
            let modulo = mod;
            let object = obj;
            this.addEventos(modulo);
        }
    };

    addEventos () {
        $("button[name=entrada]").click(function(event){
            Moduls.getModalbody().load({ url: 'content/app/registro.html', script: true });
            construirModal({title:"Registro", w:600, h:750});
          }
        );
    }
}
