var movimiento = class {
    constructor (mod, obj) {
        console.log('movimiento.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let yo = this;
        let form = mod.Forms['detalleMovimientos'];
        form.set({'mov_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['guardar'];
        form.set({'mov_comunidad':obj.comunidad});
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
                { data: 'mov_importe' },
                { render: function (data, type, row) { 
                                var botones = '';
                                botones+= '<button type="button" data-movimiento="'+row.mov_movimiento+'" data-piso="'+row.mov_piso+'" class="btn border border-info movPiso"><span class="material-symbols-'+(!row.mov_piso?'rounded':'outlined')+'">person</span></button>';
                                return botones;
                          }}
            ],
            drawCallback: function (set) {
                $('button.movPiso').click(function(eve){
                    let form = yo.modulo.Forms['guardar'];
                    form.set({mov_movimiento:eve.currentTarget.getAttribute('data-movimiento')});
                    Moduls.getModalbody().load({ url: 'content/back/selectPiso.html', script: true, parametros:{comunidad:yo.object.comunidad, piso: eve.currentTarget.getAttribute('data-piso')}});
                    construirModal({title:"Pisos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPisos});
                });
            }
        });
    };

    addEventos(mod) {
        let comunidad = this.object.comunidad;
        $("button[name=anadirMovimiento]").click(function(event){
            Moduls.getModalbody().load({ url: 'content/back/masMovimiento.html', script: true, parametros:{comunidad}});
            construirModal({title:"Guardar Movimiento", w:600, h:750});
          }
        );
    }

    callbackPisos() {
        let form = Moduls.getBody().Forms.guardar;
        form.set({mov_piso:Moduls.getModalbody().Forms['listaPisos'].formulario.piso.value});
        form.executeForm();
    }

    guardarPiso (s,d,e) {
        if (s) {
            cerrarModal();
            e.form.modul.Forms.detalleMovimientos.executeForm();
        } else {
            validaErroresCBK(d.root||d);
        }
    }

    movimiento (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let tabla = e.form.modul.getScript().tablaD;
            tabla.clear();
            tabla.rows.add(d.root.Detalle);
            tabla.draw();
        }
    }
}