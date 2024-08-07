var gestionGastos = class {
    constructor (mod, obj) {
        console.log('gestionGastos.js -> constructor');
        this.obj = obj;
        this.modulo = mod;
        let yo = this;
        Moduls.getTipogasto()   .load({ url: 'content/back/gastos/tipoGastos.html',   script: true, parametros:{comunidad:obj.comunidad}});
        Moduls.getPromesaspago().load({ url: 'content/back/gastos/promesaPagos.html', script: true, parametros:{comunidad:obj.comunidad}});
    }

    
}