var header = class {
    constructor (mod, obj) {
        console.log('header.js -> constructor');
        this.addEventos();
    };

    addEventos () {
        $('button.loginHeader').click(function () {
            Moduls.getModalbody().load({ url: 'content/app/login.html', script: true });
            construirModal({title:"Login", w:400, h:700});
        });
    }

    setUser(name) {
        $("span[name='nombre']").empty();
        $("span[name='nombre']").append(name);
        $('button.exitHeader').toggleClass ('xx');
        $('button.loginHeader').toggleClass('xx');
    }
    
    salir (s, d, e) {
        validaErroresCBK(d.root, 1000);
        sessionStorage.setItem('id', '');
        document.location.reload();
    }
}
