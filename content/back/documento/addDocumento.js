var addDocumento = class {
    constructor (mod, obj) {
        console.log('addDocumento.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        this.addEventos(mod);
        mod.Forms['guardarDocumento'].set({"doc_comunidad":obj.comunidad, "doc_documento":""});
    }

    addEventos(mod) {
        let comunidad = this.obj.comunidad;
        $("button[name=subir]").click(function(event){
            var archivo = $('input[name=doc_real]')[0].files[0];
            var formData = new FormData();
            formData.append('doc_real', archivo);
            formData.append('doc_comunidad', comunidad);
            formData.append('doc_documento', '');

            $.ajax({
                url: 'backend/app/documento/guardar.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    debugger;
                },
                error: function(response) {
                    debugger;
                }
            });
        });
    }

    guardar (s, d, e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        }
    }

}