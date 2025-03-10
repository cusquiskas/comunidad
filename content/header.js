var header = class {
    constructor (mod, obj) {
        console.log('header.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        this.validaSesion();
        this.addEventos();
    };

    validaSesion() {
        if (sessionStorage.getItem('id') && sessionStorage.getItem('id') !== "") {
            let form = this.mod.Forms['sesion'];
            form.set({'usu_correo':sessionStorage.getItem('id')});
            form.executeForm();
        }
    }

    addEventos () {
        $('button.loginHeader').click(function () {
            Moduls.getModalbody().load({ url: 'content/app/login.html', script: true });
            construirModal({title:"Login", w:400, h:700});
        });
        
        $(".comboComunidad").change(function(eve){
            Moduls.getBody().load({ url: 'content/app/panelprincipal.html', script: true, parametros:{comunidad:eve.currentTarget.value}});
        });

        $("a.iconoHome").click(function(eve) {
            $(".comboComunidad").change();
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
            if (sessionStorage.getItem('id')) {
                validaErroresCBK(d.root||d);
                document.location.href = "/comunidad/";
            }
            sessionStorage.setItem('id','');
            sessionStorage.setItem('nombre','');
            //Moduls.getBody().load({ url: 'content/app/intro.html', script: true });
            
        } else {
            sessionStorage.setItem('id',d.root.Detalle.usu_correo);
            sessionStorage.setItem('nombre',d.root.Detalle.usu_nombre);
            $(".PanelDeComunidad").toggleClass('xx');
            if (d.root.Comunidades.length == 0) {
                //$(".SinComunidad").toggleClass('xx');
                Moduls.getBody().load({ url: 'content/app/sinComunidad.html', script: false});
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
