var movimiento = class {
    constructor (mod, obj) {
        console.log('movimiento.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.addEventos(mod);
        let form = mod.Forms['detalleMovimientos'];
        form.set({'mov_comunidad':$(".comboComunidad").val()});
        form.executeForm();
        let tabla = $(".listaMovimientos").DataTable({
            columns: [
                { data: 'mov_fecha' },
                { data: 'mov_detalle' },
                { data: 'mov_movimiento' },
                { data: 'mov_importe' }
            ]
        });
    };

    addEventos(mod) {

    }

    movimiento (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let tabla = $(".listaMovimientos").DataTable();
            tabla.clear();
            tabla.rows.add(d.root.Detalle);
            tabla.draw();
        }
    }
}