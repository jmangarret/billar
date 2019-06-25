//Click sobre mesa
$('.me-bp,.me-ka').click(function(){
    var numero = $(this).data('numero');
    showCuenta(numero);
});
//Botones de accion detalle de cuenta
var buttonsCuentas = function(idcta,mesa){
    var html = '';
    html += '<div class="buttons">';
    html += '<button type="button" onclick="setMesa(this)" class="btn btn-success" data-mesa="'+mesa+'" data-toggle="modal" data-target="#modalcuentanew" title="Crear Cuenta">';
    html += '<i class="glyphicon glyphicon-plus"></i>';
    html += '</button>';
    html += '<button type="button" onclick="setMesa(this)" class="btn btn-primary" data-cuenta="'+idcta+'" data-mesa="'+mesa+'" data-toggle="modal" data-target="#modalgallery" title="Agregar Pedido">';
    html += '<i class="glyphicon glyphicon-cutlery"></i>';
    html += '</button>';
    html += '<button type="button" onclick="setPasarCuenta(this)" class="btn btn-info" data-cuenta="'+idcta+'" data-mesa="'+mesa+'" data-toggle="modal" data-target="#modalpasarcuenta" title="Pasar Cuenta a otra Mesa">';
    html += '<i class="glyphicon glyphicon-share"></i>';
    html += '</button>'; 
    html += '<button type="button" onclick="" class="btn btn-warning" data-cuenta="'+idcta+'" data-mesa="'+mesa+'" data-toggle="modal" data-target="#modalkaraokesearch" title="Buscar Karaoke">';
    html += '<i class="glyphicon glyphicon-music"></i>';
    html += '</button>'; 
    html += '<button type="button" onclick="closeCuenta(this)" class="btn btn-danger" data-cuenta="'+idcta+'" data-mesa="'+mesa+'" title="Cerrar Cuenta">';
    html += '<i class="glyphicon glyphicon-off"></i>';
    html += '</button>';     
    html += '</div>';
    return html;
}
//Funcion para obtener detalle de cuentas por mesa
var showCuenta=function(idmesa){
    $('#contenido').html('');
    $.ajax({
        url : 'cuenta/mesa/'+idmesa,
        dataType: 'json',
        success : function(response) {
            var cuenta  = JSON.parse(response.cuenta);
            var detalle = JSON.parse(response.detalle);
            var cliente = "";
            var usuario = "";
            var html    = "";
            var prod    = "";
            var idcta   = 0;
            var cant    = 0;
            var prec    = 0;
            var monto   = 0;
            var total   = 0;
            if(cuenta.length > 0)
            {
                for(c in cuenta)
                {
                    total   = 0;
                    idcta   = cuenta[c].id;
                    mesa    = cuenta[c].mesa.nombre.replace(/[""]+/g,'');
                    cliente = cuenta[c].cliente.nombre.replace(/[""]+/g,'');
                    usuario = cuenta[c].usuario.nombre.replace(/[""]+/g,'');
                    html =  '<details open>';
                    html += '<summary class="btn-primary">';
                    html += mesa   + ' - ';
                    html += 'Cuenta: #';
                    html += idcta  + ' - ';
                    html += cliente;
                    html += ' ('   + usuario +')';
                    html += '</summary>';    
                    html += '<table class="table">';
                    for(d in detalle){ 
                        if (detalle[d].cuenta.id==idcta){
                            prod  = detalle[d].producto.nombre.replace(/[""]+/g,'');                                 
                            cant  = detalle[d].cantidad;//.toFixed(2);   
                            cant  = detalle[d].cantidad.toFixed(2);   
                            monto = detalle[d].precio*cant;//* (60/100); 
                            monto = formatearMonto(monto); 
                            prec  = formatearMonto(detalle[d].precio,2);  
                            html += '<tr>';
                            html += '<td>'+prod;
                            if (detalle[d].producto.tipoProducto=='Tiempo')
                            html += '<td>'+convertToTime(cant);
                            else
                            html += '<td>'+cant;
                            html += '<td>'+prec;
                            if (detalle[d].tipoPrecio=='P')
                            html += ' * ';
                            html += '<td align="right">'+monto;
                            total = total +(detalle[d].precio*cant);// * (60/100);
                        }   
                    }//end for detalle
                    total = formatearMonto(total,2);
                    html += '<tr>';
                    html += '<td><b>Total</b>';
                    html += '<td>';
                    html += '<td>';
                    html += '<td align="right">'+ total;
                    html += '</tr>';           
                    html += '</tr>';       
                    html += '</table>';                            
                    html += buttonsCuentas(idcta,idmesa);
                    html += '</details>';
                    $('#contenido').append(html);                            
                }//end for cuentas
            }else{//end if 
                html += '<h4>';
                html += '<div class="alert alert-info">';
                html += 'Esta mesa no posee cuentas activas. Haga click para abrir una nueva Cuenta.';
                html += '</div>';
                html += '</h4>';
                html += buttonsCuentas(0,idmesa);
                $('#contenido').append(html); 
            }//end else if   
        },//end success
    });//end ajax
};
//funcion para asignar mesa a ventanas modales: productos, cliente; al hacer click desde el detalle de la cuenta
var setMesa=function(btn) {
    var mesa    = $(btn).data('mesa');
    var cuenta  = $(btn).data('cuenta');
    $('#form input[name=idmesa]').val(mesa);//campo oculto idmesa en modal de productos
    $('#form input[name=idcuenta]').val(cuenta);//campo oculto idcuenta en modal de productos
    $('#billarbundle_cuenta_mesa').val(mesa);////campo mesa readonly en modal de abrir cuenta
    return false;
};
//funcion click sobre producto gallery del modal
$(".producto-item").on("click", function(event){
    var form    = $(this).parent().serialize();
    addProducto(form);
    event.preventDefault(); 
});
//funcion para agregar producto a mesa por cuenta
var addProducto=function(form){
    $.ajax({
        type: "POST",
        url: "cuenta/addProducto/",
        data: form,
        dataType: "json",
        success: function(response) {
            showSuccess();
            showCuenta(response.data.idmesa); 
            console.log(response.data.idmesa);
        },
        error: function() {
            alert('Error al agregar producto.');
        }
    });    
};
//Mensaje de exito al agregar producto
var showSuccess= function(){
   $(".alert").show();
    setTimeout(function() {
        $(".alert").hide();
    }, 2000);
}
//Boton nuevo cliente (+) modal Registrar cliente
var htmlBtnNuevoCliente  = '';
    htmlBtnNuevoCliente += '<span style="float:right">';
    htmlBtnNuevoCliente += 'Nuevo Cliente ';
    htmlBtnNuevoCliente += '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalclientenew" title="Registrar Nuevo Cliente">';
    htmlBtnNuevoCliente += '<i class="glyphicon glyphicon-plus"></i>';
    htmlBtnNuevoCliente += '</button>';
    htmlBtnNuevoCliente += '</span>';
$('#billarbundle_cuenta_cliente').before(htmlBtnNuevoCliente);
//Cronometro campo hora al abrir cuenta ventana modal
setInterval(function(){ 
    $('#formCuenta input[name=hora]').val(horaActual())
    },1000 //cada 1 segundo
);
//Submit form nuevo cliente
$('#formCliente').submit(function(e){
    var form = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "cliente/new",
        data: form,
        dataType: "json",
        success: function(response) {
            console.log(response);
            var id      = response.cliente.id;
            var nombre  = response.cliente.nombre;
            $('#modalclientenew').modal('toggle'); 
            $('#billarbundle_cuenta_cliente').append('<option value='+id+' selected>'+nombre+'</option>');
        },
        error: function() {
            alert('Error al agregar cliente...');
        } 
    });
    e.preventDefault();
});
//Submit form nueva cuenta
$('#formCuenta').submit(function(e){
    var form = $(this).serialize();
    $.ajax({
        type:   "POST",
        url:    "cuenta/new",
        data:   form,
        dataType: "json",
        success: function(response) {
            $('#modalcuentanew').modal('toggle'); 
            $('#mesa-'+response.idmesa).addClass('mesa-ocupada');
            showCuenta(response.idmesa); 
            console.log('mesa '+response.idmesa);
            console.log('data '+response.data);
        },
        error: function() {
            alert('Error al crear Cuenta...');
        } 
    });
    e.preventDefault();
});
//Funcion para cerrar cuenta de mesa
var closeCuenta = function(btn){
    if (confirm("Seguro deseea cerrar esta cuenta?")){  
        var mesa    = $(btn).data('mesa');
        var cuenta  = $(btn).data('cuenta');
        $.ajax({
            type: "POST",
            url:  "cuenta/close/"+cuenta,
            success: function(response){
                if (response.cerrarmesa==1)
                $('#mesa-'+response.idmesa).removeClass('mesa-ocupada');
                showCuenta(mesa);
                $.ajax({
                    type: "POST",
                    url:  "cuenta/getDatosMesa/"+cuenta,
                    success: function(response){
                        var cuenta = response.cuenta;
                        var details = response.cuenta.detalle;
                        var ventimp = window.open('cuenta/imprimir/'+cuenta.id, 'popimpr');
                    }
                });                
            },
            error: function(){
                alert('Error al cerrar cuenta...');
            }
        });
    }
};
//Funcion para setear datos de formulario pasar cuenta a otra mesa
var setPasarCuenta=function(btn) {
    var mesa    = $(btn).data('mesa');
    var cuenta  = $(btn).data('cuenta');
    $.ajax({
        type: "POST",
        url:  "cuenta/getDatosMesa/"+cuenta,
        success: function(response){
            $('#formPasarCuenta input[name=cuenta]').val('Cuenta #'+response.cuenta.id);
            $('#formPasarCuenta input[name=idcuenta]').val(response.cuenta.id);
            $('#formPasarCuenta input[name=mesaactual]').val(response.cuenta.mesa);
            console.log(response.cuenta);
            for (d in response.cuenta.detalle){
                console.log(response.cuenta.detalle[d].producto);
                console.log(response.cuenta.detalle[d].precio);
            }
        },  
        error: function(){
            alert('Error al obtener datos de la cuenta...');
        }
    });
};
//Submit del form pasar cuenta a otra mesa
$('#formPasarCuenta').submit(function(e){
    var form    = $(this).serialize(); 
    var cuenta  = $('#formPasarCuenta input[name=idcuenta]').val();
    if (confirm("Seguro desea pasar cuenta?")){
        $.ajax({
            url:  "cuenta/pasarCuenta/"+cuenta,
            type: "POST",
            data: form,
            dataType: "json",
            success: function(response){
                alert('Cuenta cambiada a la Mesa '+response.idmesa);
                $('#mesa-'+response.idmesa).addClass('mesa-ocupada');
                showCuenta(response.idmesa);
                $('#modalpasarcuenta').modal('toggle'); 
                if (response.idmesamod>0)
                    $('#mesa-'+response.idmesamod).removeClass('mesa-ocupada');
            },
            error: function(){
                alert('Error al pasar cuenta...');
            }
        });
    }
    e.preventDefault();
});