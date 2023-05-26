var header = class {
    constructor (mod, obj) {
        console.log('header.js -> constructor');
        this.addEventos();
    };

    addEventos () {
    
    }

    darkAndLigth () {
        $('nav.bannerHeader').toggleClass('navbar-dark');
        $('nav.bannerHeader').toggleClass('bg-dark');
        $('nav.bannerHeader').toggleClass('navbar-ligth');
        $('nav.bannerHeader').toggleClass('bg-ligth');
        $('nav.bannerHeader').toggleClass('text-white');
        $('nav.bannerHeader').toggleClass('text-black');
        $('button.exitHeader').toggleClass('btn-dark');
        $('button.exitHeader').toggleClass('btn-ligth');
    }

    setUser(name) {
        $("span[name='nombre']").empty();
        $("span[name='nombre']").append(name);
        $('button.exitHeader').toggleClass('xx');
    }
    
    salir (s, d, e) {
        validaErroresCBK(d.root, 1000);
        sessionStorage.setItem('id', '');
        document.location.reload();
    }
}
