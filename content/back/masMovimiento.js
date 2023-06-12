var masMovimiento = class {
    constructor (mod, obj) {
        console.log('masMovimiento.js -> constructor');
        debugger;
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
        validaErroresCBK(d.root||d);
    }
}