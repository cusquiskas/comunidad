var selectPropietario = class {
    constructor (mod, obj) {
        console.log('selectPropietario.js -> constructor');
        this.obj = obj;
        this.mod = mod;
        mod.Forms.buscaPropietario.set({com_comunidad:obj.comunidad});
        mod.Forms.crearPropietario.set({pro_comunidad:obj.comunidad, pro_piso: obj.piso});
        $("button.btnSi").click(function () { $(".panelConfirmarCrear").addClass("xx"); $(".panelCrear").removeClass("xx"); });
        $("button.btnNo").click(function () { cerrarModal(); });
    }

    buscaPropietario (s, d, e) {
        if (s) {
            $(".panelBuscar").addClass("xx");
            if (!d.root.Detalle || d.root.Detalle.length == 0) {
                $(".panelConfirmarCrear").removeClass("xx");
            } else {
                let form = e.form.modul.Forms["crearPropietario"];
                form.set(d.root.Detalle[0]);
                $(".panelCrear").removeClass("xx");
                }
        } else {
            validaErroresCBK(d.root||d);
        }
    }

    crearPropietario (s, d, e) {
        if (s) {
            e.form.modul.getScript().obj.callBackParent();
            cerrarModal();
        } else {
            validaErroresCBK(d.root||d);
        }
    }
};