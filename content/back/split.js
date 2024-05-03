var split = class {
    constructor (mod, obj) {
        console.log('split.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        let form = mod.Forms.split;
        form.set({spl_movimiento:obj.movimiento, spl_comunidad:obj.comunidad});
        form.executeForm();
    }

    addEventoAnadir(mod) {
        $("button.anadirSplit").click(function(event){
            let form = $('form[name=listaSplit]');
            let opcion = '<div class="row"><div class="col col-7"><input type="text" class="form-control" name="splitDesc" value="{{spl_detalle}}" autocomplete="off"></div><div class="col col-3"><input type="number" class="form-control text-end" name="splitImp" value="{{spl_importe}}" autocomplete="off"></div><div class="col"><button type="button" class="btn btn-danger">-</button></div></div>';
            form.append(opcion.reemplazaMostachos({"spl_detalle":"", "spl_importe":0}));
            
        });
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
            let opcion0 = '<div class="row"><div class="col col-7"><input type="text" class="form-control" name="splitDesc" data-split="{{spl_split}}" value="{{spl_detalle}}" autocomplete="off"></div><div class="col col-3"><input type="number" class="form-control text-end" name="splitImp" data-split="{{spl_split}}" value="{{spl_importe}}" autocomplete="off" disabled></div><div class="col"><button type="button" class="btn btn-primary anadirSplit">+</button></div></div>';
            let opcionN = '<div class="row"><div class="col col-7"><input type="text" class="form-control" name="splitDesc" data-split="{{spl_split}}" value="{{spl_detalle}}" autocomplete="off"></div><div class="col col-3"><input type="number" class="form-control text-end" name="splitImp" data-split="{{spl_split}}" value="{{spl_importe}}" autocomplete="off"</div><div class="col"><button type="button" class="btn btn-danger">-</button></div></div>';
            for (let i=0; i<d.root.Detalle.length; i++) {
                if (i > 0) form.append(opcionN.reemplazaMostachos(d.root.Detalle[i]));
                else       form.append(opcion0.reemplazaMostachos(d.root.Detalle[i])); 
            }
            yo.addEventoAnadir(yo);
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }
}