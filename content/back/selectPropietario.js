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
            if (d.root.Detalle == null) {
                $(".panelConfirmarCrear").removeClass("xx");
            } else {
                $(".panelCrear").removeClass("xx");
            }
        } else {
            validaErroresCBK(d.root||d);
        }
    }

    crearPropietario (s, d, e) {
        if (s) {

        } else {
            validaErroresCBK(d.root||d);
        }
    }
};