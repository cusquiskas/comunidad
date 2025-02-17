var promesaPagos = class {
    constructor (mod, obj) {
        console.log('promesaPagos.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        let form = mod.Forms.promesa;
        form.set({psm_comunidad:obj.comunidad});
        form.executeForm();
        form = mod.Forms.guardar;
        form.set({psm_comunidad:obj.comunidad});
        this.tablaD = new DataTable(".listaPromesas", {
            language: dataTableIdiomaES,
            //ordering: false,
            order: [[0, 'desc']],
            columns: [
                { data: 'psm_detalle' },
                { data: 'psm_fdesde',  render: function (data, type, row) {return  data.hazFecha('yyyy-mm-dd','dd/mm/yyyy');  }},
                { data: 'psm_fhasta',  render: function (data, type, row) {return  data.hazFecha('yyyy-mm-dd','dd/mm/yyyy');  }},
                { data: 'psm_importe', render: function (data, type, row) {return  formatoEsp(data,2);                        }},
                { render: function (data, type, row) {
                                return row.pisos.length;
                }},
                { render: function (data, type, row) { 
                                var botones = '';
                                var pisos = [];
                                row.pisos.forEach(e => {
                                    pisos.push(e.prp_piso);
                                });
                                botones+= '<button type="button" data-promesa="'+row.psm_promesa+'" data-piso="'+JSON.stringify(pisos)+'" class="btn btn-'+(row.pisos.length>0?'success':'ligth')+' border border-info psmPiso"><span class="material-icons ">person</span></button>';
                                return botones;
                }}
            ],
            drawCallback: function (set) {
                $('button.psmPiso').click(function(eve){
                    let form = yo.modulo.Forms['guardar'];
                    yo.promesa = eve.currentTarget.getAttribute('data-promesa');
                    form.set({gas_gasto:eve.currentTarget.getAttribute('data-promesa')});
                    Moduls.getModalbody().load({ url: 'content/back/selectPiso.html', script: true, parametros:{comunidad:yo.obj.comunidad, gasto: eve.currentTarget.getAttribute('data-promesa'), piso: JSON.parse(eve.currentTarget.getAttribute('data-piso')), type:'checkbox'}});
                    construirModal({title:"Pisos", w:600, h:750, oktext:'Guardar', okfunction:yo.callbackPisos});
                });
            }
        });
    }

    callbackPisos (valor) {
        let modulo = Moduls.getBody();
        let script = modulo.getScript();
        let form = modulo.Forms.guardar;
        form.set({psm_promesa:script.promesa, psm_piso:Moduls.getModalbody().getScript().getValor()});
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