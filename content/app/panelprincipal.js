var panelprincipal = class {
    constructor (mod, obj) {
        console.log('panelprincipal.js -> constructor');
        let modulo = mod;
        let object = obj;
        this.addEventos(modulo);
        let form = modulo.Forms['sesion'];
        form.set({'JGD_JUGADOR':sessionStorage.getItem('id')});
        form.executeForm();
    };

    addEventos () {

    };

    sesion (s,d,e) {
        let headerClass = Moduls.getHeader().getScript();
        if (!s) {
            if (sessionStorage.getItem('id')) validaErroresCBK(d.root||d);
            sessionStorage.setItem('id','');
            sessionStorage.setItem('nombre','');
            Moduls.getBody().load({ url: 'content/app/intro.html', script: true });
        } else {
            sessionStorage.setItem('id',d.root.Detalle.JGD_JUGADOR);
            sessionStorage.setItem('nombre',d.root.Detalle.JGD_NOMBRE);
        }
        headerClass.setUser(sessionStorage.getItem('nombre'));
    }

}
