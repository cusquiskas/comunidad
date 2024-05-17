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
        //form = mod.Forms['guardar'];
        //form.set({'mov_comunidad':obj.comunidad});
        this.tablaD = new DataTable(".listaMovimientos", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[0, 'desc']],
            columns: [
                {   data: 'fecha',
                    render: function(data, type, row) {
                    if (type == 'display' || type == 'filter') return data.hazFecha('yyyy-mm-dd','dd/mm/yyyy');
                    return data;
                    }
                },
                { data: 'detalle' },
                { data: 'movimiento' },
                { data: 'importe' },
                { render: function (data, type, row) { 
                                var botones = '';
                                botones+= '<button type="button" data-movimiento="'+row.movimiento+'" data-split="'+(row.split||'')+'" data-piso="'+(row.piso||'')+'" class="btn btn-'+(row.piso!=null?'success':'ligth')+' border border-info movPiso"><span class="material-icons ">person</span></button>';
                                botones+= '<button type="button" data-movimiento="'+row.movimiento+'" data-split="'+(row.split||'')+'" data-gasto="'+(row.gasto||'')+'" class="btn btn-'+(row.gasto!=null?'success':'ligth')+' border border-info movGasto"><span class="material-icons ">electrical_services</span></button>';
                                botones+= '<button type="button" data-movimiento="'+row.movimiento+'" data-split="'+(row.split||'')+'" class="btn btn-'+(row.split!=null?'success':'ligth')+' border border-info movSplit"><span class="material-symbols-outlined">arrow_split</span></button>';
                                return botones;
                          }}
            ],
            drawCallback: function (set) {
                $('button.movPiso').click(function(eve){
                    Moduls.getModalbody().load({ url: 'content/back/selectPiso.html',  script: true, parametros:{comunidad:yo.object.comunidad, movimiento: eve.currentTarget.getAttribute('data-movimiento'), split:eve.currentTarget.getAttribute('data-split'), piso: eve.currentTarget.getAttribute('data-piso')}});
                    construirModal({title:"Pisos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPisos, canceltext:'Borrar', cancelfunction:yo.callbackPisosBorrar});
                });
                $('button.movGasto').click(function(eve){
                    Moduls.getModalbody().load({ url: 'content/back/selectGasto.html', script: true, parametros:{comunidad:yo.object.comunidad, movimiento: eve.currentTarget.getAttribute('data-movimiento'), split:eve.currentTarget.getAttribute('data-split'), gasto: eve.currentTarget.getAttribute('data-gasto')}});
                    construirModal({title:"Gastos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackGastos, canceltext:'Borrar', cancelfunction:yo.callbackGastosBorrar});
                });
                $('button.movSplit').click(function(eve){
                    let form = yo.modulo.Forms['guardarSplit'];
                    form.set({spl_comunidad: obj.comunidad, spl_movimiento:eve.currentTarget.getAttribute('data-movimiento')});
                    Moduls.getModalbody().load({ url: 'content/back/split.html', script: true, parametros:{comunidad:yo.object.comunidad, movimiento:eve.currentTarget.getAttribute('data-movimiento'), split:eve.currentTarget.getAttribute('data-split')}});
                    construirModal({title:"Split", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackSplit, canceltext:'Borrar', cancelfunction:yo.callbackSplitBorrar});
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
        let obj = Moduls.getModalbody().getScript().obj;
        let form;
        if (obj.split && obj.split != null) {
            form = Moduls.getBody().Forms.guardarSpl;
            form.set({spl_piso:Moduls.getModalbody().Forms['listaPisos'].formulario.piso.value, spl_gasto:-1, spl_comunidad: obj.comunidad, spl_split: obj.split});
        } else {
            form = Moduls.getBody().Forms.guardarMov;
            form.set({mov_piso:Moduls.getModalbody().Forms['listaPisos'].formulario.piso.value, mov_gasto:-1, mov_comunidad: obj.comunidad, mov_movimiento: obj.movimiento});
        }
        form.executeForm();
    }

    callbackPisosBorrar() {
        let obj = Moduls.getModalbody().getScript().obj;
        let form;
        if (obj.split && obj.split != null) {
            form = Moduls.getBody().Forms.guardarSpl;
            form.set({spl_piso:null, spl_gasto:-1, spl_comunidad:obj.comunidad, spl_split:obj.split});
        } else {
            form = Moduls.getBody().Forms.guardarMov;
            form.set({mov_piso:null, mov_gasto:-1, mov_comunidad:obj.comunidad, mov_movimiento:obj.movimiento});
        }
        form.executeForm();
    }

    callbackGastos() {
        let obj = Moduls.getModalbody().getScript().obj;
        let form;
        if (obj.split && obj.split != null) {
            form = Moduls.getBody().Forms.guardarSpl;
            form.set({spl_gasto:Moduls.getModalbody().Forms['listaGastos'].formulario.gasto.value, spl_piso:-1, spl_comunidad:obj.comunidad, spl_split:obj.split});
        } else {
            form = Moduls.getBody().Forms.guardarMov;
            form.set({mov_gasto:Moduls.getModalbody().Forms['listaGastos'].formulario.gasto.value, mov_piso:-1, mov_comunidad:obj.comunidad, mov_movimiento:obj.movimiento});
        }
        form.executeForm();
    }

    callbackGastosBorrar() {
        let obj = Moduls.getModalbody().getScript().obj;
        let form;
        if (obj.split && obj.split != null) {
            form = Moduls.getBody().Forms.guardarSpl;
            form.set({spl_gasto:null, spl_piso:-1, spl_comunidad:obj.comunidad, spl_split:obj.split});
        } else {
            form = Moduls.getBody().Forms.guardarMov;
            form.set({mov_gasto:null, mov_piso:-1, mov_comunidad:obj.comunidad, mov_movimiento:obj.movimiento});
        }
        form.executeForm();
    }

    callbackSplit() {
        debugger;
        let form = Moduls.getBody().Forms.guardarSplit;
        form.set({spl_detalle:Moduls.getModalbody().getScript().getValor()});
        form.executeForm();
    }

    callbackSplitBorrar() {
        let form = Moduls.getBody().Forms.guardarSplit;
        form.set({spl_detalle:'DEL'});
        form.executeForm();
    }

    guardar (s,d,e) {
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