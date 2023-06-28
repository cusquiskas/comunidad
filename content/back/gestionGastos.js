var gestionGastos = class {
    constructor (mod, obj) {
        console.log('gestionGastos.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        let form = mod.Forms.gastos;
        form.set({gas_comunidad:obj.comunidad});
        form.executeForm();
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
                                botones+= '<button type="button" data-gasto"'+row.gas_gasto+'" data-piso="'+JSON.stringify(pisos)+'" class="btn btn-'+(row.pisos.length>0?'success':'ligth')+' border border-info gasPiso"><span class="material-icons ">person</span></button>';
                                return botones;
                }}
            ],
            drawCallback: function (set) {
                $('button.gasPiso').click(function(eve){
                    let form = yo.modulo.Forms['guardar'];
                    form.set({gas_gasto:eve.currentTarget.getAttribute('data-gasto')});
                    Moduls.getModalbody().load({ url: 'content/back/selectPiso.html', script: true, parametros:{comunidad:yo.obj.comunidad, gasto: eve.currentTarget.getAttribute('data-gasto'), piso: JSON.parse(eve.currentTarget.getAttribute('data-piso')), type:'checkbox'}});
                    construirModal({title:"Pisos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPisos});
                });
            }
        });
    }

    callbackPisos (valor) {
        debugger;
        let form = Moduls.getBody().Forms.guardar;
        form.set({gas_gasto:this.obj.gasto, gas_piso:Moduls.getModalbody().Forms['listaPisos'].formulario.piso.value});
        form.executeForm();
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