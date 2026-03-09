var derramas = class {
    constructor (mod, obj) {
        console.log('derramas.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        let form = mod.Forms.derrama;
        form.set({der_comunidad:obj.comunidad});
        form.executeForm();
        this.addEventos(obj);
        this.tablaD = new DataTable(".listaDerramas", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[0, 'desc']],
            columns: [
                { data: 'der_nombre' },
                { data: 'der_total', render: function (data, type, row) {return  formatoEsp(data,2); }},
                { data: 'der_iva',   render: function (data, type, row) {return  formatoEsp(data,2); }},
                { render: function (data, type, row) { 
                    let botones = '';
                    botones+= (!row.der_promesa)?'<button type="button" class="reparteDerrama"><span class="material-symbols-outlined">payment_arrow_down</span></button>':'';
                    return botones;
                }}
            ],
        });
        this.tablaD.on('click', 'button.reparteDerrama', function (eve) { 
            let data = yo.tablaD.row($(this).closest('tr')).data();
            Moduls.getModalbody().load({ url: 'content/back/gastos/derramaAPago.html',  script: true, parametros:{"comunidad":data.der_comunidad, "derrama": data.der_derrama, "total":data.der_total, "nombre":data.der_nombre, callParent:yo.callbackPisos}});
            construirModal({title:"Convertir Derrama en Pagos", w:800, oktext:'Guardar', okfunction:() => {Moduls.getModalbody().script.grabaPagos();}, canceltext:'Cancelar', cancelfunction: cerrarModal});
        });
    }

    addEventos(obj) {
        $("button[name=anadirDerrama").click(function () {
            Moduls.getModalbody().load({ url: 'content/back/gastos/masDerrama.html', script: true, parametros:{comunidad:obj.comunidad} });
            construirModal({title:"Guardar Derrama"});
        });
    }

    listado (s,d,e) {
        if (s) {
            let tabla = e.form.modul.getScript().tablaD;
            tabla.clear();
            tabla.rows.add(d.root.Detalle);
            tabla.draw();
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }

    guardar (s,d,e) {
        if (s) {
            e.form.modul.Forms.derrama.executeForm();
            cerrarModal();
        } else {
            validaErroresCBK(d.root||d);
        }
    }

    callbackPisos (s, d, e) {
        cerrarModal();
        Moduls.getBody().load({ url: 'content/back/gestionGastos.html', script: true, parametros:{comunidad:this.comunidad} });
        setTimeout(() => {
            document.querySelector('button[data-bs-target=".div-tab3"]').click();
        }, 1000);
        
    }

}