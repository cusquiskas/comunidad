var panelprincipal = class {
    constructor (mod, obj) {
        console.log('panelprincipal.js -> constructor');
        let modulo = mod;
        let object = obj;
        this.addEventos(modulo);
        let form = modulo.Forms['sesion'];
        form.set({'usu_correo':sessionStorage.getItem('id')});
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
            sessionStorage.setItem('id',d.root.Detalle.usu_correo);
            sessionStorage.setItem('nombre',d.root.Detalle.usu_nombre);
            $(".PanelDeComunidad").toggleClass('xx');
            if (d.root.Comunidades.length == 0) {
                $(".SinComunidad").toggleClass('xx');
            } else {
                let combo = $(".comboComunidad");
                combo.toggleClass('xx');
                combo.empty();
                for (let i=0; i<d.root.Comunidades.length; i++) {
                    combo.append($("<option>", {value: d.root.Comunidades[i].com_comunidad, text: d.root.Comunidades[i].com_nombre}));
                }
            }   
            
        }
        headerClass.setUser(sessionStorage.getItem('nombre'));
    }

}
