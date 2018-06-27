// Manejo de listas de opciones con arreglos JavaScript
function agregarOpcionCombo(control, opcion) {
	if (document.all) {
		control.add(opcion);
	}
	else {
		control.add(opcion,null);
	}
}


function autocompletar_select(id_campo,texto_campo,nombre_campo,sql,identificador){
      
        $.getJSON('../../../mvc/controlador/autocompletar_select/autocompletar_select.php','&sql='+sql+'&id_campo='+id_campo+'&texto_campo='+texto_campo,
                    function (resultado_controlador) {
                          
                        $.each(resultado_controlador, function (i, item) {
                               
                               
                            var marcar = null;
                            
                            if(resultado_controlador[i][id_campo] == identificador){
                                marcar = true;
                            }
                              
                             //  alert(marcar);
                            $('#'+nombre_campo).append($('<option>', { 
                                value: resultado_controlador[i][id_campo],
                                text : resultado_controlador[i][texto_campo],
                                selected: marcar
                            }));
                            
                            marcar = null;
                            
                                                        
                        });
         
                    },
                    "json" 
                    );
                       
    }
    
function autocompletar_radio(nombre_campo,id_campo,nombre_tabla,tipo,identificador_marcar,posicion_campo,campo_filtro,valor_filtro,nombre_campo_formulario){

        if(nombre_campo_formulario == null){ nombre_campo_formulario = id_campo; }

        if(campo_filtro == undefined){ campo_filtro = null;}
        if(valor_filtro == undefined){ valor_filtro = null;}  
        
    if(tipo == 'selector'){
                
        $.getJSON('../../../mvc/controlador/autocompletar_select/autocompletar_radio_select.php','&nombre_tabla='+nombre_tabla+'&variables_formulario='+id_campo+','+nombre_campo+'&campo_filtro='+campo_filtro+'&valor_filtro='+valor_filtro+'&id_campo='+id_campo+'&texto_campo='+nombre_campo,
                    function (resultado_controlador) {
                          
                    $("#"+nombre_campo_formulario+" option").each(function(){                          
                           
                        if($(this).val() != '') {                                                   
                            $("#"+nombre_campo_formulario+" option[value='"+$(this).val()+"']").remove();                          
                          
                        }
                          
                    });                         
                          
                          
                          
                        $.each(resultado_controlador, function (i, item) {
                            
                            var marcar;
                            if(i == 0)                            
                            if(resultado_controlador[i][id_campo] == identificador_marcar){                                
                                marcar = true;                                
                            }
                            
                            $('#'+nombre_campo_formulario).append($('<option>', { 
                                value: resultado_controlador[i][resultado_controlador[i]['value']],
                                text : resultado_controlador[i][resultado_controlador[i]['description']],
                                selected: marcar
                            }));
                            
                            marcar = null;
                            
                        });
                        
                        $('#'+nombre_campo_formulario).val(identificador_marcar).change();
                        
                        
         
                    },
                    "json" 
                    );
        
        
        } 
             
                       
    }    
    
    

    
           function agregar_nuevo_registro(form,html,ruta){                                                 
                    swal({
                        title: '<h3><b>Introduza el nuevo valor</b></h3>',                        
                        imageUrl: '../../../images/save.png',
                        imageWidth: 80,
                        imageHeight: 80, 
                        html: html,                        
                        showCancelButton: true
                        
                    }).then(function(isConfirm) {
                    if (isConfirm) {
                    
                    var campos_formulario = $('#'+form).serialize();             

        $.post(
                ruta,
                campos_formulario,
                function (resultado_controlador) {
                  
                  if(resultado_controlador.resultado == 'INSERTADO' || resultado_controlador.resultado == 'MODIFICADO'){
                            
                        var mensaje = 'REGISTRO '+resultado_controlador.resultado+'</b>';
                        
                        swal({
                          type: 'success',
                          timer: 2000,  
                          html: mensaje
                        });
                        
                        if(resultado_controlador.cargar_resultado != 'null'){                            
                            $("#"+resultado_controlador.identificador_resultado).load(resultado_controlador.cargar_resultado);                            
                         
                            
                            
                        }
                        
                        if(resultado_controlador.recargar_pagina == 'si'){                                                            
                            setTimeout(function(){location.reload()},1000);
                        }
                        
                        if(resultado_controlador.realizar_submit == 'si'){ 
                            var datos_formulario = $('#form_find_date').serialize();                                                                                     
                            setTimeout(function(){$('#resultado').load('result_find_date_csv.php?'+datos_formulario); return false;},1000);
                        }


                    } 
                    
                  if(resultado_controlador.resultado == 'error'){
                  
                        swal({
                          type: 'error',
                          html: 'Error al Insertar'
                        });
                        
                    }                     

                },
                "json" 
            ); 
                    }
                });               
               
           }   

function vaciarCombo(control) {
    for (var q = control.options.length; q >= 0; q--)
		control.options[q] = null;
}

function llenarCombo(control, arreglo, columnaValor, columnaTexto, opcionDefecto, valorDefecto) {
	var myEle;
	var longitud = arreglo.length;

	vaciarCombo(control);
	if (opcionDefecto && opcionDefecto!="") {
		myEle = document.createElement('option');
		myEle.value = valorDefecto;
		myEle.text = opcionDefecto;
		agregarOpcionCombo(control, myEle);
	}
	
	for (var x = 0; x < longitud; x++) {
		myEle = document.createElement('option');
		myEle.value = arreglo[x][columnaValor];
		myEle.text = arreglo[x][columnaTexto];
		agregarOpcionCombo(control, myEle);
	}
}

function buscarValorAsociadoEnArreglo(arreglo, columnasFiltro, valoresFiltro, columnaValorBuscado) {
	var columnas = columnasFiltro.split("|");
	var valores = valoresFiltro.split("|");
	var longitud = arreglo.length;

	for (var x = 0; x < longitud; x++) {
		if (comprobarFiltro(arreglo[x],columnasFiltro, valoresFiltro)) {
			return arreglo[x][columnaValorBuscado];
		}
	}
	
	return "NO ENCONTRADO";
}

function comprobarFiltro(filaArreglo, columnasFiltro, valoresFiltro) {
	var columnas = columnasFiltro.split("|");
	var valores = valoresFiltro.split("|");
	var valoresLista;

	for (var i=0; i<columnas.length; i++) {
		if (valores[i].indexOf("^")<0) {
			//filtrar por un valor unico
			if (filaArreglo[columnas[i]] != valores[i]) {
				return false;
			}
		}
		else {
			//filtrar por una lista de valores
			valoresLista = valores[i].split("^");
			if (!estaContenidaEnArreglo(valoresLista, filaArreglo[columnas[i]])) {
				return false;
			}
		}
	}
	
	return true;
}

function llenarComboHijo(control, arreglo, columnaValor, columnaTexto, opcionDefecto, valorDefecto, columnasFiltro, valoresFiltro) {
    var myEle;
	var longitud = arreglo.length;

	vaciarCombo(control);
	
	if (valoresFiltro=="")
		return;

	if (opcionDefecto && opcionDefecto!="") {
		myEle = document.createElement('option');
		myEle.value = valorDefecto;
		myEle.text = opcionDefecto;
		agregarOpcionCombo(control, myEle);
	}
	
	for (var x = 0; x < longitud; x++) {
		if (comprobarFiltro(arreglo[x],columnasFiltro, valoresFiltro)) {
			myEle = document.createElement('option');
			myEle.value = arreglo[x][columnaValor];
			myEle.text = arreglo[x][columnaTexto];
			agregarOpcionCombo(control, myEle);
		}
	}
}


function seleccionarOpcion(combo, valorIni) {
	var x;
	var encontrada = false;

	for (x = 0; x < combo.length; x++) {
    if (combo[x].value == valorIni) {
      combo.selectedIndex = x;
			encontrada = true;
      break;
    }
	}
	
	return encontrada;
}

function seleccionarOpcionRadio(radio, valor) {
	var x;
	var encontrada = false;

	for (x = 0; x < radio.length; x++) {
    if (radio[x].value == valor) {
      radio[x].checked = true;
			encontrada = true;
      break;
    }
	}
	
	return encontrada;
}

function obtenerOpcionSeleccionadaRadio(radio) {
	var x;
	var encontrada = false;

	for (x = 0; x < radio.length; x++) {
    if (radio[x].checked == true) {
			encontrada = radio[x];
      break;
    }
	}
	
	return encontrada;
}

function estaOpcionContenidaEnCombo(combo, valor) {
	var x;
	var encontrada = false;

	for (x = 0; x < combo.length; x++) {
    if (combo[x].value == valor) {
			encontrada = true;
      break;
    }
	}
	
	return encontrada;
}

function estaContenidaEnArreglo(arreglo, valor) {
	var encontrada = false;
	
	for (var i=0; i < arreglo.length; i++) {
		if (arreglo[i]==valor) {
			encontrada = true;
			break;
		}
	}
	
	return encontrada;
}

if(typeof Array.prototype.copy=='undefined')
Array.prototype.copy=function(a) {
	var	i=0, b=[];
	for(i;i<this.length;i++)
		b[i]=(typeof this[i].copy!='undefined')?
	this[i].copy():
	this[i];
	return b
};
