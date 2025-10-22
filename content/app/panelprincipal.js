var panelprincipal = class {
    constructor (mod, obj) {
        console.log('panelprincipal.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.ctx = document.getElementById('gastosChart').getContext('2d');
        this.banco = [];
        this.gastosChart = 
            new Chart(this.ctx, {
                type: 'bar',
                data: {
                    labels:   [],
                    datasets: []
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                        stacked: true
                        },
                        y: {
                        beginAtZero: true,
                        stacked: true
                        }
                    }
                },
                interaction: {
                    intersect: true,
                  }
            });
        this.addEventos(mod);
        let form = mod.Forms['avisosComunidad'];
        form.set({'com_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['datosPisos'];
        form.set({'pis_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['datosMovimiento'];
        form.set({'mov_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['datosGasto'];
        form.set({'mov_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['derramasActivas'];
        form.set({'der_comunidad':obj.comunidad});
        form.executeForm();
        form = mod.Forms['detallePiso'];
        form.set({'pis_comunidad':obj.comunidad});
    };

    addEventos (modulo) {
        
        let comunidad = this.object.comunidad;
        let forms     = this.modulo.Forms;
        let me        = this;
        $("p.detalleBanco").click( function () {
            let cadena = "";
            let banco  = JSON.parse(me.banco[0].IBAN);
            if (banco.length == 0) {
                cadena = '<h3 class="text-center w-100">No se ha indicado ninguna información</h3>';
            } else {
                cadena = "<h5>Cuenta del Banco</h5>";
                cadena+= '<ul style="list-style: none">';
                for (let i=0; i<banco.length; i++) cadena+= "<li><b>"+banco[i].banco+"</b> - <i>"+banco[i].cuenta+"</i></li>";
                cadena+= "</ul>";
            }
            Moduls.getModalbody().pintaHTML(cadena);
            construirModal({title:"Información de la comunidad", w:450});
        });
        $("p.gestionMovimientos").click(function () {
            Moduls.getBody().load({ url: 'content/back/movimiento.html', script: true, parametros:{comunidad, limitado:false} });
        });

        $(".consultaMovimientos").click(function () {
            Moduls.getBody().load({ url: 'content/back/movimiento.html', script: true, parametros:{comunidad, limitado:true} });
        });

        $("p.gestionPisos").click(function() {
            Moduls.getBody().load({ url: 'content/back/gestionPiso.html', script: true, parametros:{comunidad} });
        });

        $("p.gestionGastos").click(function() {
            Moduls.getBody().load({ url: 'content/back/gestionGastos.html', script: true, parametros:{comunidad} });
        });

        $("p.detalleGastos").click(function() {
            let form = forms['detalleGasto'];
            form.set({'mov_comunidad':comunidad});
            form.executeForm();
        });

        // para los elementos que aun no se han pintado, los capturará el tbody
        $("tbody.listaSaldoPisos").on("click", "span.btn", function() {
            let form = forms['detallePiso'];
            form.set({"pis_piso":$(this).attr("piso")});
            form.executeForm();
        });

    };

    avisos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root.avisos||d, 6000);
        }
        if (d.root.perfil && d.root.perfil <= 2) {
            $(".gestionAcceso").removeClass('xx');
            $(".consultaMovimientos").addClass('xx');
        }
        if (d.root.perfil && d.root.perfil > 2) {
            $(".gestionAcceso").addClass('xx');
        }
    }

    pisos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let form = e.form.modul.Forms['dashboard'];
            form.set({nVecinos:d.root.Detalle.length});
            e.form.modul.script.banco = d.root.banco;
        }
    }

    detallePisos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let fila = '<tr><td>{{detalle}}</td><td>{{fecha}}</td><td class="text-end"><span class="font-monospace text-xs text-{{color1}} font-weight-bold">{{importe}}€</span></td><td class="text-end"><span class="font-monospace text-end text-xs text-{{color2}} font-weight-bold">{{sumatorio}}€</span></td></tr>';
            let tabla = $('tbody.listaMovimientosPiso');
            tabla.empty();
            for (let i=d.root.Detalle.length-1; i>=0; i--) {
                d.root.Detalle[i].color1    = (d.root.Detalle[i].importe  <0)?'danger':'primary';
                d.root.Detalle[i].color2    = (d.root.Detalle[i].sumatorio<0)?'danger':'primary';
                d.root.Detalle[i].importe   = formatoEsp(d.root.Detalle[i].importe,   2);
                d.root.Detalle[i].sumatorio = formatoEsp(d.root.Detalle[i].sumatorio, 2);
                d.root.Detalle[i].fecha     = d.root.Detalle[i].fecha.hazFecha('yyyy-mm-dd','dd/mm/yyyy');
                tabla.append(fila.reemplazaMostachos(d.root.Detalle[i]));
            }
        }
    }

    gastos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let form = e.form.modul.Forms['dashboard'];
            form.set({gastoEjercicio:formatoEsp(d.root.Detalle.ejercicio,2), gastoAnterior:formatoEsp(d.root.Detalle.anterior,2)});
        }
    }

    detalleGastos (s,d,e) {
        if (s) {
            e.form.modul.script.gastosChart.data.datasets = d.root.datos;
            e.form.modul.script.gastosChart.data.labels   = d.root.control.bimestres;
            e.form.modul.script.gastosChart.update();
        } else {
            validaErroresCBK(d.root||d);
        }
    }

    derramasActivas (s,d,e) {
        if (s) {
            let fila = '<tr>'
                     + '<td><div class="d-flex px-2 py-1"><div class="d-flex flex-column justify-content-center"><h6 class="mb-0 text-sm">{{der_nombre}}</h6></div></div></td>'
                     + '<td class="align-middle text-center text-sm"><span class="text-xs font-weight-bold"> {{der_total}}€ </span></td>'
                     + '<td class="align-middle text-center text-sm"><span class="text-xs font-weight-bold"> {{der_parcial}}€ </span></td>'
                     + '<td class="align-middle"><div class="progress-wrapper w-75 mx-auto"><div class="progress-info"><div class="progress-percentage"><span class="text-xs font-weight-bold">{{x100}}%</span></div></div><div class="progress"><div class="progress-bar bg-gradient-info w-{{x100}}" role="progressbar"aria-valuenow="{{x100}}" aria-valuemin="0" aria-valuemax="100"></div></div></div></td>'
                     + '</tr>';
            let tBody = $('tbody.listaDerramasActivas');
            let row;
            tBody.empty();
            if (d.root.Detalle.length < 1) {
                tBody.html('<tr><td colspan="4" class="text-center text-success"><h3>No hay ninguna derrama abierta</h3></td></tr>');
            } else {
                for (let i=0; i<d.root.Detalle.length; i++) {
                    row = d.root.Detalle[i];
                    row.der_parcial = formatoEsp(row.der_parcial, 2);
                    row.der_total   = formatoEsp(row.der_total,   2);
                    tBody.append(fila.reemplazaMostachos(row));
                }
            }
        } else {
            validaErroresCBK(d.root||d);
        }
    }

    movimientos (s,d,e) {
        if (!s) {
            validaErroresCBK(d.root||d);
        } else {
            let form = e.form.modul.Forms['dashboard'];
            let fila = '<tr><td><div class="d-flex px-1 py-1"><div class="d-flex flex-column justify-content-center"><span  data-bs-toggle="modal" data-bs-target="#detallePisosModal" class="material-icons btn" piso="{{cpiso}}" style="margin-top: -10px; height:10px">info</span></div></div></td><td><div class="d-flex px-2 py-1"><div class="d-flex flex-column justify-content-center"><h6 class="mb-0 text-sm">{{piso}} # {{propietario}}</h6></div></div></td><td class="align-middle text-end text-sm"><span class="font-monospace text-xs text-{{color}} font-weight-bold">{{importe}}€</span></td></tr>';
            form.set({saldoU:formatoEsp(d.root.Detalle.saldoCuenta,2), fondo:formatoEsp(d.root.Detalle.fondoCuenta,2), fechaU:d.root.Detalle.ultimoMovimiento.hazFecha('yyyy-mm-dd','dd/mm/yyyy')});
            let tabla = $('tbody.listaSaldoPisos');
            tabla.empty();
            for (let i=0; i<d.root.Detalle.saldoPiso.length; i++) {
                //d.root.Detalle.saldoPiso[i].propietario = '';
                d.root.Detalle.saldoPiso[i].color = (d.root.Detalle.saldoPiso[i].importe<0)?'danger':'primary';
                d.root.Detalle.saldoPiso[i].importe = formatoEsp(d.root.Detalle.saldoPiso[i].importe, 2);
                tabla.append(fila.reemplazaMostachos(d.root.Detalle.saldoPiso[i]));
            }
        }
    }

}
