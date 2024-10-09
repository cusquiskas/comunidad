var viewDocumento = class {
    constructor (mod, obj) {
        console.log('viewDocumento.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        let form = mod.Forms['detalleDocumento'];
        form.set({'doc_comunidad':obj.comunidad, 'doc_documento':obj.documento});
        form.executeForm();
        this.addEvents();
    }

    addEvents () {
        let yo = this;
        $("button[name=descargar]").click(function(event){
            yo.modulo.Forms.descargaDocumento.formulario.submit();
        });
    }

    documento (s, d, e) {
        if (s) {
            e.form.modul.Forms.descargaDocumento.set(d.root.Detalle);
        } else {
            validaErroresCBK(d.root||d);
        }
    }

}
/*

*/