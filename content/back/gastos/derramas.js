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
                    let botones = 'botón';
                    //botones+= '<button type="button" data-promesa="'+row.psm_promesa+'" data-piso="'+JSON.stringify(pisos)+'" class="btn btn-'+(row.pisos.length>0?'success':'ligth')+' border border-info psmPiso"><span class="material-icons ">person</span></button>';
                    return botones;
                }}
            ],
            drawCallback: function (set) {
                $('button.docDerrama').click(function(eve){
                    let form = yo.modulo.Forms['guardar'];
                    
                });
            }
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