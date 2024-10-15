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
        /*
        function inicializaTablaPacks() {
            var tablaDinamicaPacks = $('#tablaListadoPacks').DataTable({
                searching: true, 
                dom: 'lfrBtip',
                buttons: [
                        {text: 'Nuevo Precio Pack',
                        action: function ( e, dt, node, config ) {
                                    abreModalPackTarifa();
                                }
                        }
                    ],
                data:[],
                order:[[2, "asc"]],
                language: {
                    "emptyTable": "No hay informaci&oacute;n",
                    "info": "Mostrando START a END de TOTAL Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de MAX total entradas)",
                    "infoPostFix": "",
                    "lengthMenu": "Mostrar MENU Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                    "first": "Primero",
                    "last": "&Uacute;ltimo",
                    "next": "Siguiente",
                    "previous": "Anterior"}
                },
                columnDefs: [
                                { "targets": 1, "render": function (data, type, row, meta) { return '<span style="color: '+(row.PRECIO_POST && row.PRECIO_POST > 0 ? "red" : "black")+';">' + $.fn.dataTable.render.date('YYYYMMDD', 'DD/MM/YYYY').display(data) + '</span>' } },
                            //{ "targets": [2,3], "render": $.fn.dataTable.render.text() },
                                { "targets": 4, "className": "dt-body-right"},
                                { "targets": 4, "render": $.fn.dataTable.render.number('', ',', 2, '', '&nbsp;&euro;')} 
                            ],
                columns: [
                    {render: function (data, type, row, meta ) { 
                        var cadena = '';
                            //cadena+= '<button style="cursor:pointer" class="borrar" type="button"><span class="material-icons">delete</span></button>';
                            cadena+= '<button style="cursor:pointer" class="editarPack" type="button"><span class="material-icons">edit_square</span></button>';
                            return cadena;
                    }},
                    {data:"FECVIGENCI"},
                    {data:"TXPACK"},
                    {data:"TXLOCATA"},
                    {data:"TARIFA"}
                ]
            });
            tablaDinamicaPacks.on('click', 'button.editarPack', function (eve) { 
                let data = $('#tablaListadoPacks').DataTable().row($(this).closest('tr')).data();
                abreModalPackTarifa(data);
            });
        }
        */
        this.tablaD = new DataTable(".listaMovimientos", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[1, 'desc']],
            columnDefs: [
                { "targets": 1, "render"   : function (data, type) { if (type == 'display') return data.hazFecha('yyyy-mm-dd','dd/mm/yyyy'); else return data; } },
                { "targets": 4, "className": "dt-body-right"},
                { "targets": 4, "render"   : function (data, type) { if (type == 'display') return formatoEsp(data, 2) + ' &euro;';          else return data; } }
            ],
            columns: [
                { data: 'movimiento' },
                { data: 'fecha'      },
                { data: 'detalle'    },
                { data: 'gastoX'     },
                { data: 'importe'    },
                { render: function (data, type, row) { 
                    var botones = '';
                    botones+= '<button type="button" class="btn btn-'+(row.piso !=null?'success':'ligth')+' border border-info movPiso"><span class="material-icons ">person</span></button>';
                    botones+= '<button type="button" class="btn btn-'+(row.gasto!=null?'success':'ligth')+' border border-info movGasto"><span class="material-icons ">electrical_services</span></button>';
                    botones+= '<button type="button" class="btn btn-'+(row.split!=null?'success':'ligth')+' border border-info movSplit"><span class="material-symbols-outlined">arrow_split</span></button>';
                    if (row.gasto != null) 
                        botones+= '<button type="button" class="btn btn-'+(row.documento!=null?'success':'ligth')+' border border-info movAdjunto"><span class="material-symbols-outlined">attach_file</span></button>';
                    return botones;
                }}
            ]/*,
            drawCallback: function (set) {
                
            }*/
        });
        this.tablaD.on('click', 'button.movPiso', function (eve) { 
            let data = yo.tablaD.row($(this).closest('tr')).data();
            Moduls.getModalbody().load({ url: 'content/back/selectPiso.html',  script: true, parametros:{comunidad:yo.object.comunidad, movimiento: data.movimiento, split:(data.split||''), piso: (data.piso||'')}});
            construirModal({title:"Pisos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPisos, canceltext:'Borrar', cancelfunction:yo.callbackPisosBorrar});
        });
        this.tablaD.on('click', 'button.movGasto', function(eve){
            let data = yo.tablaD.row($(this).closest('tr')).data();
            Moduls.getModalbody().load({ url: 'content/back/selectGasto.html', script: true, parametros:{comunidad:yo.object.comunidad, movimiento: data.movimiento, split:(data.split||''), gasto:(data.gasto||'')}});
            construirModal({title:"Gastos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackGastos, canceltext:'Borrar', cancelfunction:yo.callbackGastosBorrar});
        });
        this.tablaD.on('click', 'button.movSplit', function(eve){
            let data = yo.tablaD.row($(this).closest('tr')).data();
            let form = yo.modulo.Forms['guardarSplit'];
            form.set({spl_comunidad: obj.comunidad, spl_movimiento:data.movimiento});
            Moduls.getModalbody().load({ url: 'content/back/split.html', script: true, parametros:{comunidad:yo.object.comunidad, movimiento:data.movimiento, split:data.split}});
            construirModal({title:"Split", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackSplit, canceltext:'Borrar', cancelfunction:yo.callbackSplitBorrar});
        });
        this.tablaD.on('click', 'button.movAdjunto', function(eve) {
            let data = yo.tablaD.row($(this).closest('tr')).data();
            let form = yo.modulo.Forms['guardarAdjunto'];
            form.set({doc_comunidad: obj.comunidad});
            if (data.documento == "" || data.documento == null) {
                Moduls.getModalbody().load({ url: 'content/back/documento/addDocumento.html',  script: true, parametros:{comunidad:yo.object.comunidad, movimiento: data.movimiento, callback:yo.guardar, moduloRemoto:yo.modulo}});
                construirModal({title:"Subir Documento", w:600, h:750});
            } else {
                Moduls.getModalbody().load({ url: 'content/back/documento/viewDocumento.html', script: true, parametros:{comunidad:yo.object.comunidad, documento:data.documento,    callback:yo.guardar, moduloRemoto:yo.modulo}});
                construirModal({title:"Descargar Documento", w:600, h:750});
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
        let form = Moduls.getBody().Forms.guardarSplit;
        let datos = Moduls.getModalbody().getScript().getValor();
        form.set({spl_detalle:JSON.stringify(datos)});
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