var panelprincipal = class {
    constructor (mod, obj) {
        console.log('panelprincipal.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms['sesion'];
        form.set({'usu_correo':sessionStorage.getItem('id')});
        form.executeForm();
    };

    addEventos (modulo) {
        $(".comboComunidad").change(function(eve){
            let form;
            form = modulo.Forms['avisosComunidad'];
            form.set({'com_comunidad':eve.currentTarget.value});
            form.executeForm();
            form = modulo.Forms['datosPisos'];
            form.set({'pis_comunidad':eve.currentTarget.value});
            form.executeForm();
            form = modulo.Forms['datosMovimiento'];
            form.set({'mov_comunidad':eve.currentTarget.value});
            form.executeForm();
        });
    };

    avisos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root.avisos||d, 6000);
        }
        if (d.root.perfil && d.root.perfil <= 2) {
            $("p.gestionAcceso").removeClass('xx');
        }
        if (d.root.perfil && d.root.perfil > 2) {
            $("p.gestionAcceso").addClass('xx');
        }
    }

    pisos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let form = e.form.modul.Forms['dashboard'];
            form.set({nVecinos:d.root.Detalle.length});
        }
    }
    
    movimientos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let form = e.form.modul.Forms['dashboard'];
            form.set({saldoU:d.root.Detalle.saldo, fondo:d.root.Detalle.fondo});
        }
    }

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
                combo.change();
            }   
            
        }
        headerClass.setUser(sessionStorage.getItem('nombre'));
    }

}
