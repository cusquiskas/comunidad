var selectGasto = class {
    constructor (mod, obj) {
        console.log('selectGasto.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        if (!this.obj.type || this.obj.type == "") this.obj.type = "radio"; 
        let form = mod.Forms.gastos;
        form.set({gas_comunidad:obj.comunidad});
        form.executeForm();
    }

    getValor() {
        let resultado;
        if (this.obj.type == "radio") {
            resultado = this.mod.Forms.listaGastos.gasto.value;
        } else {
            let inp = this.mod.Forms.listaGastos.formulario.querySelectorAll('input');
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
            let gasto = e.form.modul.script.obj.gasto;
            let form = $('form[name=listaGastos]');
            let opcion = '<input type="'+yo.obj.type+'" class="btn-check" name="gasto" id="gasto{{gas_gasto}}" value="{{gas_gasto}}" autocomplete="off"><label class="btn btn-light" for="gasto{{gas_gasto}}">{{gas_nombre}}</label>';
            form.empty();
            for (let i=0; i<d.root.Detalle.length; i++) {
                form.append(opcion.reemplazaMostachos(d.root.Detalle[i]));
            }
            if (yo.obj.type == 'radio')
                form[0].gasto.value = gasto;
            else
                gasto.forEach(e => { document.getElementById('gasto'+e).checked = true; });
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }
}