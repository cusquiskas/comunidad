var masMovimiento = class {
    constructor (mod, obj) {
        console.log('masMovimiento.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms.movimiento;
        form.formulario.reset();
        form.set({mov_movimiento:obj.movimiento, mov_comunidad:obj.comunidad});
    }

    addEventos(mod) {

    }

    guardar (s,d,e) {
        if (s) {
            if (e.form.modul.name = '#modalBody') {
                cerrarModal();
                Moduls.getBody().Forms['detalleMovimientos'] && Moduls.getBody().Forms['detalleMovimientos'].executeForm();
            } else {
                e.form.modul.Forms && e.form.modul.Forms.detalleMovimientos && e.form.modul.Forms.detalleMovimientos.executeForm();
            }
        } else {
            validaErroresCBK(d.root||d);
        }
    }
}