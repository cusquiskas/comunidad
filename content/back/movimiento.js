var movimiento = class {
    constructor (mod, obj) {
        console.log('movimiento.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms['detalleMovimientos'];
        form.set({'mov_comunidad':$(".comboComunidad").val()});
        form.executeForm();
        this.tablaD = new DataTable(".listaMovimientos", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[0, 'desc']],
            columns: [
                {   data: 'mov_fecha',
                    render: function(data, type, row) {
                    if (type == 'display' || type == 'filter') return data.hazFecha('yyyy-mm-dd','dd/mm/yyyy');
                    return data;
                    }
                },
                { data: 'mov_detalle' },
                { data: 'mov_movimiento' },
                { data: 'mov_importe' }
            ]
        });
    };

    addEventos(mod) {
        let comunidad = this.object.comunidad;
        $("button[name=anadirMovimiento]").click(function(event){
            Moduls.getModalbody().load({ url: 'content/back/masMovimiento.html', script: true, parametros:{comunidad, movimiento:null}});
            construirModal({title:"Guardar Movimiento", w:600, h:750});
          }
        );
    }

    movimiento (s,d,e) {
        let yo = e.form.modul.getScript();
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let tabla = yo.tablaD;
            tabla.clear();
            tabla.rows.add(d.root.Detalle);
            tabla.draw();
        }
    }
}