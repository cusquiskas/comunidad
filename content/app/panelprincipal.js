var panelprincipal = class {
    constructor (mod, obj) {
        console.log('panelprincipal.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms['avisosComunidad'];
        form.set({'com_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['datosPisos'];
        form.set({'pis_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['datosMovimiento'];
        form.set({'mov_comunidad':obj.comunidad});
        form.executeForm();
    };

    addEventos (modulo) {
        
        let comunidad = this.object.comunidad;
        $("p.gestionMovimientos").click(function () {
            Moduls.getBody().load({ url: 'content/back/movimiento.html', script: true, parametros:{comunidad} });
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

}
