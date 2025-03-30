var tipoGastos = class {
    constructor (mod, obj) {
        console.log('tipoGastos.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        let form = mod.Forms.gastos;
        form.set({gas_comunidad:obj.comunidad});
        form.executeForm();
        form = mod.Forms.guardarPiso;
        form.set({gas_comunidad:obj.comunidad});
        this.addEventos(obj);
        this.tablaD = new DataTable(".listaGastos", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[0, 'desc']],
            columns: [
                { data: 'gas_nombre' },
                { data: 'rep_nombre' },
                { render: function (data, type, row) {
                                return row.pisos.length;
                }},
                { render: function (data, type, row) { 
                                var botones = '';
                                var pisos = [];
                                row.pisos.forEach(e => {
                                    pisos.push(e.gpi_piso);
                                });
                                botones+= '<button type="button" data-gasto="'+row.gas_gasto+'" data-piso="'+JSON.stringify(pisos)+'" class="btn btn-'+(row.pisos.length>0?'success':'ligth')+' border border-info gasPiso"><span class="material-icons ">person</span></button>';
                                botones+= '<button type="button" data-gasto="'+row.gas_gasto+'" class="btn btn-'+(row.gas_visible==1?'success':'ligth')+' border border-info gasVisible"><span class="material-icons ">'+(row.gas_visible==1?'visibility':'visibility_off')+'</span></button>'
                                return botones;
                }}
            ],
            drawCallback: function (set) {
                $('button.gasPiso').click(function(eve){
                    let form = yo.modulo.Forms['guardarPiso'];
                    yo.gasto = eve.currentTarget.getAttribute('data-gasto');
                    form.set({gas_gasto:eve.currentTarget.getAttribute('data-gasto')});
                    Moduls.getModalbody().load({ url: 'content/back/selectPiso.html', script: true, parametros:{comunidad:yo.obj.comunidad, gasto: eve.currentTarget.getAttribute('data-gasto'), piso: JSON.parse(eve.currentTarget.getAttribute('data-piso')), type:'checkbox'}});
                    construirModal({title:"Pisos", w:600, h:750, oktext:'guardarPiso', okfunction:yo.callbackPisos});
                });
                $('button.gasVisible').click(function(eve){
                    let form = yo.modulo.Forms['guardar'];
                    var row = $(this).closest('tr');
                    var rowData = yo.tablaD.row(row).data();
                    rowData.gas_visible = (rowData.gas_visible == 1)?0:1;
                    form.set(rowData);
                    form.executeForm();
                });
            }
        });
    }

    addEventos() {
        let yo = this;
        $("button[name=anadirGasto]").click(function () {
            // Moduls.getModalbody().load({ url: 'content/back/gastos/masGasto.html', script: true, parametros:{comunidad:yo.comunidad, tipoReparto:yo.tipoReparto} });
            // construirModal({title:"Guardar Gasto"});
        });
    }

    callbackPisos (valor) {
        let modulo = Moduls.getTipogasto();
        let script = modulo.getScript();
        let form = modulo.Forms.guardarPiso;
        form.set({gas_gasto:script.gasto, gas_piso:Moduls.getModalbody().getScript().getValor()});
        form.executeForm();
    }

    listado (s,d,e) {
        if (s) {
            let yo = e.form.modul.getScript()
            let tabla = yo.tablaD;
            tabla.clear();
            tabla.rows.add(d.root.Detalle);
            tabla.draw();
            yo.tipoReparto = d.root.tipoReparto;
        } else {
            validaErroresCBK(d.root||d);
            cerrarModal();
        }
    }

    guardarGasto (s,d,e) {
        if (s) {
            e.form.modul.Forms.gastos.executeForm();
            cerrarModal();
        } else {
            validaErroresCBK(d.root||d);
        }
    }
}