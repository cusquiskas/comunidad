var split = class {
    constructor (mod, obj) {
        console.log('split.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        this.importe = 0;
        let form = mod.Forms.split;
        form.set({spl_movimiento:obj.movimiento, spl_comunidad:obj.comunidad});
        form.executeForm();
    }

    addEventoAnadir(mod) {
        $("button.anadirSplit").click(function(event){
            let form = $('form[name=listaSplit]');
            let i = $("[data-id]").length;
            let opcion = '<div class="row" data-id="filaS"><div class="col col-7"><input type="text" class="form-control" name="splitDesc" value="{{spl_detalle}}" autocomplete="off"></div><div class="col col-3"><input type="number" class="form-control text-end fila'+i+'" name="splitImp" value="{{spl_importe}}" autocomplete="off"></div><div class="col"><button type="button" class="btn btn-danger fila'+i+'">-</button></div></div>';
            form.append(opcion.reemplazaMostachos({"spl_detalle":"", "spl_importe":0}));
            $("button.fila"+i).click(function(event){ mod.quitarElementoSplit(event); });
            $("input.fila"+i).change(function(event){ mod.calculaImporte(); });
        });
    }

    addEventoQuitar(mod) {
        $("button.borrarSplit").click(function(event){ mod.quitarElementoSplit(event); });
    }

    addEventoCambiar(mod) {
        $("input[name=splitImp]").change(function(event){ mod.calculaImporte(); });
    }

    quitarElementoSplit(eve) {
        eve.currentTarget.parentElement.parentElement.remove();
        this.calculaImporte();
    }

    calculaImporte() {
        let inputs = $("input[name=splitImp]");
        let importe = 0;
        inputs.each((i, input) => { importe += input.value*1; });
        inputs[0].value = ((inputs[0].value*1)-(importe-this.importe));
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
            let opcion0 = '<div class="row" data-id="filaS"><div class="col col-7"><input type="text" class="form-control" name="splitDesc" data-split="{{spl_split}}" value="{{spl_detalle}}" autocomplete="off"></div><div class="col col-3"><input type="number" class="form-control text-end" name="splitImp" data-split="{{spl_split}}" value="{{spl_importe}}" autocomplete="off" disabled></div><div class="col"><button type="button" class="btn btn-primary anadirSplit">+</button></div></div>';
            let opcionN = '<div class="row" data-id="filaS"><div class="col col-7"><input type="text" class="form-control" name="splitDesc" data-split="{{spl_split}}" value="{{spl_detalle}}" autocomplete="off"></div><div class="col col-3"><input type="number" class="form-control text-end" name="splitImp" data-split="{{spl_split}}" value="{{spl_importe}}" autocomplete="off"</div><div class="col"><button type="button" class="btn btn-danger borrarSplit">-</button></div></div>';
            for (let i=0; i<d.root.Detalle.length; i++) {
                yo.importe += d.root.Detalle[i].spl_importe * 1;
                if (i > 0) form.append(opcionN.reemplazaMostachos(d.root.Detalle[i]));
                else       form.append(opcion0.reemplazaMostachos(d.root.Detalle[i])); 
            }
            yo.addEventoAnadir (yo);
            yo.addEventoQuitar (yo);
            yo.addEventoCambiar(yo);
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }
}