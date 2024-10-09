var addDocumento = class {
    constructor (mod, obj) {
        console.log('addDocumento.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        this.addEventos(mod);
        mod.Forms['guardarDocumento'].set({"doc_comunidad":obj.comunidad, "doc_documento":"", "doc_movimiento":obj.movimiento});
    }

    addEventos(mod) {
        let comunidad = this.obj.comunidad;
        let movimiento = this.obj.movimiento;
        let me = this;
        $("button[name=subir]").click(function(event){
            var archivo = $('input[name=doc_real]')[0].files[0];
            var formData = new FormData();
            formData.append('doc_real', archivo);
            formData.append('doc_comunidad', comunidad);
            formData.append('doc_documento', '');
            formData.append('doc_movimiento', movimiento);

            $.ajax({
                url: 'backend/app/documento/guardar.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    me.guardar(response.success, response.root, me.modulo);
                },
                error: function(response) {
                    me.guardar(false, response, me.modulo);
                }
            });
        });
    }

    guardar (s, d, e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let yo     = e.getScript();
            let remoto = {form:{modul:yo.obj.moduloRemoto}};
            yo.obj.callback(s, d, remoto);
        }
    }

}