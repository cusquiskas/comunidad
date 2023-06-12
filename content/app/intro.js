var intro = class {
    constructor (mod, obj) {
        console.log('intro.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
    };

    addEventos (mod) {
        $("button[name=entrada]").click(function(event){
            Moduls.getModalbody().load({ url: 'content/app/registro.html', script: true });
            construirModal({title:"Registro", w:600, h:750});
          }
        );
    }
}
