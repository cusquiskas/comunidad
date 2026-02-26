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
                    botones+= '<button type="button" class="reparteDerrama" data-derrama="'+row.der_derrama+'" data-total="'+row.der_total+' data-nombre="'+row.der_nombre+'""><span class="material-symbols-outlined">payment_arrow_down</span></button>';
                    return botones;
                }}
            ],
        });
        this.tablaD.on('click', 'button.reparteDerrama', function (eve) { 
            let data = yo.tablaD.row($(this).closest('tr')).data();
            Moduls.getModalbody().load({ url: 'content/back/gastos/derramaAPago.html',  script: true, parametros:{"comunidad":yo.obj.comunidad, "derrama": data.derrama, "total":data.total, "nobre":data.nombre}});
            construirModal({title:"Convertir Derrama en Pagos", w:800, oktext:'Guardar', okfunction:yo.callbackPisos, canceltext:'Cancelar', cancelfunction:function(){cerrarModal();}});
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

}