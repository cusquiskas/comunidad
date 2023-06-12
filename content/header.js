var header = class {
    constructor (mod, obj) {
        console.log('header.js -> constructor');
        let form = mod.Forms['sesion'];
        form.set({'usu_correo':sessionStorage.getItem('id')});
        form.executeForm();
        this.addEventos();
    };

    addEventos () {
        $('button.loginHeader').click(function () {
            debugger;
            Moduls.getModalbody().load({ url: 'content/app/login.html', script: true });
            construirModal({title:"Login", w:400, h:700});
        });
        
        $(".comboComunidad").change(function(eve){
            Moduls.getBody().load({ url: 'content/app/panelprincipal.html', script: true, parametros:{comunidad:eve.currentTarget.value}});
        });
    }

    setUser(name) {
        $("span[name='nombre']").empty();
        $("span[name='nombre']").append(name);
        if (name == "") {
            $('.loginHeader').removeClass('xx');
            $('.exitHeader').addClass('xx');
        } else {
            $('.loginHeader').addClass('xx');
            $('.exitHeader').removeClass('xx');
        }
    }
    
    salir (s, d, e) {
        validaErroresCBK(d.root, 1000);
        sessionStorage.setItem('id', '');
        document.location.reload();
    }

    sesion (s,d,e) {
        let headerClass = e.form.modul.getScript();
        if (!s) {
            if (sessionStorage.getItem('id')) validaErroresCBK(d.root||d);
            sessionStorage.setItem('id','');
            sessionStorage.setItem('nombre','');
            Moduls.getBody().load({ url: 'content/app/intro.html', script: true });
        } else {
            sessionStorage.setItem('id',d.root.Detalle.usu_correo);
            sessionStorage.setItem('nombre',d.root.Detalle.usu_nombre);
            $(".PanelDeComunidad").toggleClass('xx');
            if (d.root.Comunidades.length == 0) {
                $(".SinComunidad").toggleClass('xx');
            } else {
                let combo = $(".comboComunidad");
                combo.toggleClass('xx');
                combo.empty();
                for (let i=0; i<d.root.Comunidades.length; i++) {
                    combo.append($("<option>", {value: d.root.Comunidades[i].com_comunidad, text: d.root.Comunidades[i].com_nombre}));
                }
                combo.change();
            }   
            
        }
        headerClass.setUser(sessionStorage.getItem('nombre'));
    }
}
