var selectPiso = class {
    constructor (mod, obj) {
        console.log('selectPiso.js -> constructor');
        let form = mod.Forms.pisos;
        form.set({pis_comunidad:obj.comunidad});
        form.executeForm();
    }

    listado (s,d,e) {
        if (s) {
            let yo = e.form.modul.getScript();
            let form = $('form[name=listaPisos]');
            let opcion = '<input type="radio" class="btn-check" name="piso" id="piso{{pis_piso}}" value="{{pis_piso}}" autocomplete="off"><label class="btn btn-light" for="piso{{pis_piso}}">{{pis_nombre}}</label>';
            for (let i=0; i<d.root.Detalle.length; i++) {
                form.append(opcion.reemplazaMostachos(d.root.Detalle[i]));
            }
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }
}