var masDerrama = class {
    constructor (mod, obj) {
        console.log('masDerrama.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms.guardar;
        form.formulario.reset();
        form.set({der_derrama:obj.derrama, der_comunidad:obj.comunidad});
    }

    addEventos(mod) {

    }

    guardar (s,d,e) {
        if (s) {
            if (e.form.modul.name = '#modalBody') {
                cerrarModal();
                Moduls.getDerramas().Forms['derrama'] && Moduls.getDerramas().Forms['derrama'].executeForm();
            } else {
                e.form.modul.Forms && e.form.modul.Forms.derrama && e.form.modul.Forms.derrama.executeForm();
            }
        } else {
            validaErroresCBK(d.root||d);
        }
    }
}