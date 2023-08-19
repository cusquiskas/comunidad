var gestionPiso = class {
    constructor (mod, obj) {
        console.log('gestionPiso.js -> constructor');
        this.obj = obj;
        let form = mod.Forms.pisos;
        var yo = this;
        form.set({pis_comunidad:obj.comunidad});
        form.executeForm();
        this.tablaD = new DataTable(".listaPisos", {
            language: dataTableIdiomaES,
            //ordering: false,
            columnDefs: [
                {
                  "data": null,
                  "targets": -1
                }
              ],
            order: [[0, 'desc']],
            columns: [
                { data: 'pis_nombre' },
                { data: 'pis_porcentaje' },
                { data: 'pis_propietario' },
                { data: 'pis_comentario' },
                { render: function (data, type, row) { 
                                var botones = '';
                                botones+= '<button type="button" data-piso="'+row.pis_piso+'" data-propietario="'+(row.pis_propietario||'')+'"><span class="material-icons asignarPropietario">person</span></button>';
                                return botones;
                }}
            ],
            drawCallback: function (set) {
                $('.asignarPropietario').click(function (eve) {
                    //form.set({mov_movimiento:eve.currentTarget.getAttribute('data-movimiento')});
                    if (eve.currentTarget.getAttribute('data-propietario') == null || eve.currentTarget.getAttribute('data-propietario') == "") {
                        Moduls.getModalbody().load({ url: 'content/back/selectPropietario.html', script: true, parametros:{comunidad:yo.obj.comunidad, piso: eve.currentTarget.getAttribute('data-piso')}});
                        construirModal({title:"Propietario", w:600, h:750, okfunction:yo.callbackPropietario});
                    } else {
                        Moduls.getModalbody().load({ url: 'content/back/editPropietario.html', script: true, parametros:{comunidad:yo.obj.comunidad, propiertario:eve.currentTarget.getAttribute('data-propietario'), piso: eve.currentTarget.getAttribute('data-piso')}});
                        construirModal({title:"Propietario", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPropietario});
                    }
                });
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