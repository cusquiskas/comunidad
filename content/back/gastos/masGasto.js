var masGasto = class {
    constructor (mod, obj) {
        console.log('masGasto.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms.guardar;
        form.formulario.reset();
        form.set({gas_gasto:obj.gastos, gas_comunidad:obj.comunidad});
    }

    addEventos(mod) {

    }

    guardar (s,d,e) {
        if (s) {
            if (e.form.modul.name = '#modalBody') {
                cerrarModal();
                Moduls.getTipoGastos().Forms['gastos'] && Moduls.getTipoGastos().Forms['gastos'].executeForm();
            } else {
                e.form.modul.Forms && e.form.modul.Forms.gastos && e.form.modul.Forms.gastos.executeForm();
            }
        } else {
            validaErroresCBK(d.root||d);
        }
    }
}