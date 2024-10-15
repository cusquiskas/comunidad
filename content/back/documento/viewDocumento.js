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
        $("button[name=eliminar]").click(function(event){
            yo.modulo.Forms.borraDocumento.executeForm();
        }); 
    }

    documento (s, d, e) {
        if (s) {
            e.form.modul.Forms.descargaDocumento.set(d.root.Detalle);
            e.form.modul.Forms.borraDocumento   .set(d.root.Detalle);
        } else {
            validaErroresCBK(d.root||d);
        }
    }
    
    documentoDel (s, d, e) {
        validaErroresCBK(d.root||d);
        let yo     = e.form.modul.getScript();
        let remoto = {form:{modul:yo.obj.moduloRemoto}};
        yo.obj.callback(s, d, remoto);
    }

}
/*

*/