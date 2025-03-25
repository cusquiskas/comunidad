var alerta = class {
    constructor (mod, obj) {
        console.log('alerta.js -> constructor');
        this.mod = mod;
        this.obj = obj;
        if (sessionStorage.getItem('cookie') !== "true") {
            sessionStorage.setItem('cookie', 'true');
            validaErroresCBK({tipo:'Confirmacion', Detalle:'Este sitio utiliza una cookie de sesión esencial y obligatoria para su funcionamiento. Al continuar navegando, aceptas su uso.'},8000);
        }
    };
}
