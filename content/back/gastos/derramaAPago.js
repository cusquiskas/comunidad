var derramaAPago = class {
    constructor (mod, obj) {
        debugger;
        console.log('derramaAPago.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.listaPisos = [];
        this.addEventos(mod);
        let form = mod.Forms.pisos;
        form.formulario.reset();
        form.set({pis_comunidad:obj.comunidad});
        form.executeForm();
        form = mod.Forms.formPromesaPago;
        form.set({nombre:obj.nombre, total:formatoEsp(obj.total, 2)});
    }

    addEventos(mod) {
        $("tbody.lista-vecinos").on("click", "input[type='checkbox']", function(e) {
            console.log("Checkbox clickeado");
            mod.script.recalcular();
        });
    }

    pintaListaPisos() {
        let fila = '<tr><td><input type="checkbox" name="vecino" value="{{pis_piso}} data-id="{{pis_piso}}"/></td><td>{{pis_nombre}}</td><td>{{porcentaje}}%</td><td>{{aplicado}}%</td><td>{{imp_total}}</td><td>{{imp_cuota}}</td></tr>';
        let tabla = $('tbody.lista-vecinos');
        this.listaPisos.forEach(obj => {
            tabla.append(fila.reemplazaMostachos(obj));
        });
    }

    //
    recalcular() {
        let form = this.modulo.Forms.formPromesaPago;

        let cuotas = parseInt(form.num_cuotas.value) || 1;
        let tipo = form.tipo_reparto.value;
        let importeTotal = this.object.importeTotal;

        // Obtener IDs seleccionados
        let seleccionados = [];
        $('input[name="vecino"]:checked').each((i, chk) => {
            seleccionados.push($(chk).data('id'));
        });

        let numSel = seleccionados.length;

        // Recalcular cada piso
        this.listaPisos.forEach(piso => {
            if (!seleccionados.includes(piso.pis_id)) {
                piso.imp_total = 0;
                piso.imp_cuota = 0;
                return;
            }

            let total = 0;

            if (tipo === 'igual') {
                total = importeTotal / numSel;
            } else {
                total = importeTotal * (piso.pis_porcentaje / 100);
            }

            let cuota = total / cuotas;

            piso.imp_total = formatoEsp(total, 2);
            piso.imp_cuota = formatoEsp(cuota, 2);
        });

        // Repintar tabla
        this.pintaListaPisos();
    }

    //

    listadoPisos(s,d,e) {
        
        if (s) {
            let listaPisos = d.root.Detalle;
            let modulo = e.form.modul.script;
            for (let i=0; i<listaPisos.length; i++) {
                listaPisos[i].imp_total = 0;
                listaPisos[i].imp_cuota = 0;
                listaPisos[i].porcentaje = formatoEsp(listaPisos[i].pis_porcentaje, 1);
                listaPisos[i].aplicado = 0;
            }
            modulo.listaPisos = listaPisos;
            modulo.pintaListaPisos();
        } else {
            validaErroresCBK(d.root||d);
        }
    }
    /*guardar (s,d,e) {
        if (s) {
            if (e.form.modul.name = '#modalBody') {
                cerrarModal();
                Moduls.getTipoGastos().Forms['gastos'] && Moduls.getTipoGastos().Forms['gastos'].executeForm();
            } else {
                e.form.modul.Forms && e.form.modul.Forms.gastos && e.form.modul.Forms.gastos.executeForm();
            }
        } else {
            validaErroresCBK(d.root||d);
        }
    }*/
}