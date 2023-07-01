var selectPiso = class {
    constructor (mod, obj) {
        console.log('selectPiso.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        if (!this.obj.type || this.obj.type == "") this.obj.type = "radio"; 
        let form = mod.Forms.pisos;
        form.set({pis_comunidad:obj.comunidad});
        form.executeForm();
    }

    getValor() {
        let resultado;
        if (this.obj.type == "radio") {
            resultado = this.mod.Forms.listaPisos.piso.value;
        } else {
            let inp = this.mod.Forms.listaPisos.formulario.querySelectorAll('input');
            resultado = [];
            inp.forEach(e=>{
                if (e.checked==true)resultado.push(e.value);
            });
        }
        return resultado;
    }

    listado (s,d,e) {
        if (s) {
            let yo = e.form.modul.getScript();
            let piso = e.form.modul.script.obj.piso;
            let form = $('form[name=listaPisos]');
            let opcion = '<input type="'+yo.obj.type+'" class="btn-check" name="piso" id="piso{{pis_piso}}" value="{{pis_piso}}" autocomplete="off"><label class="btn btn-light" for="piso{{pis_piso}}">{{pis_nombre}}</label>';
            for (let i=0; i<d.root.Detalle.length; i++) {
                form.append(opcion.reemplazaMostachos(d.root.Detalle[i]));
            }
            if (yo.obj.type == 'radio')
                form[0].piso.value = piso;
            else
                piso.forEach(e => { document.getElementById('piso'+e).checked = true; });
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }
}