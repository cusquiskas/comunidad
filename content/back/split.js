var split = class {
    constructor (mod, obj) {
        console.log('split.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        let form = mod.Forms.split;
        form.set({spl_movimiento:obj.movimiento, spl_comunidad:obj.comunidad});
        form.executeForm();
    }

    getValor() {
        let resultado;
        let inp = this.mod.Forms.listaSplit.formulario.querySelectorAll('input');
        resultado = [];
        inp.forEach(e=>{
            if (e.value!="")resultado.push(e.value);
        });
        return resultado;
    }

    listado (s,d,e) {
        if (s) {
            let yo = e.form.modul.getScript();
            let form = $('form[name=listaSplit]');
            let opcion = '<input type="text" class="" name="splitDesc" data-split="{{spl_split}}" value="{{spl_detalle}}" autocomplete="off"><input type="number" class="" name="splitImp" data-split="{{spl_split}}" value="{{spl_importe}}" autocomplete="off">';
            for (let i=0; i<d.root.Detalle.length; i++) {
                form.append(opcion.reemplazaMostachos(d.root.Detalle[i]));
            }
            
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }
}