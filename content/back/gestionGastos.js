var gestionGastos = class {
    constructor (mod, obj) {
        console.log('gestionGastos.js -> constructor');
        this.obj = obj;
        let form = mod.Forms.pisos;
        form.set({pis_comunidad:obj.comunidad});
        form.executeForm();
        this.tablaD = new DataTable(".listaGastos", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[0, 'desc']],
            columns: [
                { data: 'gas_nombre' },
                { data: 'rep_nombre' },
                { data: 'pis_cuenta' },
                { render: function (data, type, row) { 
                                var botones = '';
                                botones+= '<button type="button">btn</button>';
                                return botones;
                }}
            ],
            drawCallback: function (set) {
                /*
                $('button.movPiso').click(function(eve){
                    let form = yo.modulo.Forms['guardar'];
                    form.set({mov_movimiento:eve.currentTarget.getAttribute('data-movimiento')});
                    Moduls.getModalbody().load({ url: 'content/back/selectPiso.html', script: true, parametros:{comunidad:yo.object.comunidad, piso: eve.currentTarget.getAttribute('data-piso')}});
                    construirModal({title:"Pisos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPisos});
                });
                */
            }
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
}