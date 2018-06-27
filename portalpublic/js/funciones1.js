                        
    	function imprimir(div,archivo){
	  
          var objeto=document.getElementById(div);  //obtenemos el objeto a imprimir
	  var ventana=window.open('',archivo,'width=1200, height=800, scrollbars=1, location=no,left=300,top=50');  //abrimos una ventana vacía nueva
	  ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
	  ventana.document.close();  //cerramos el documento
	  ventana.print();  //imprimimos la ventana
	  //ventana.close();  //cerramos la ventana
	}  


	function imprimirTerapiasSeleccionadas(){
		var valores_check = '';

		$('input:checkbox:checked').map(function() {
                    if(this.value != 'on') {
                        valores_check += this.value+',';
                    }
		});
                
                alert(valores_check);
                
                if(valores_check == ''){
                	alert('Debe seleccionar alguna terapia');
                }else{
                	//$('#resultado').load('print_teraphist.php?identificadores_check'+valores_check);
		    	var ventana=window.open('../../controlador/pay_roll/print_teraphist.php?identificadores_check='+valores_check+'&date_start='+$("#date_start").val()+'&date_end='+$("#date_end").val()+'&type_employee='+$("#type_employee").val()+'&selectedTreatments='+$("#selectedTreatments").val(),'print_teraphist','width=1200, height=800, scrollbars=1, location=no,left=300,top=50');                 		        
			//ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
			ventana.document.close();  //cerramos el documento
			ventana.print();  //imprimimos la ventana
			//ventana.close();
                }
		
	}
	function abrirVentana(discipline,cpt,type){		
		window.open('windows_report_amount.php?discipline='+discipline+'&cpt='+cpt+'&type='+type,'','width=2500px,height=700px,noresize');		
	}
        
        function abrirVentanaHome(discipline,cpt,type){		
		window.open('../report_amount/windows_report_amount.php?discipline='+discipline+'&cpt='+cpt+'&type='+type,'','width=2500px,height=700px,noresize');		
	}
        
function number_format (number, decimals, decPoint, thousandsSep) {   
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''

  var toFixedFix = function (n, prec) {
    var k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }

  return s.join(dec)
}


    function SoloLetras(e) {        
        tecla_codigo = (document.all) ? e.keyCode : e.which;
        if(tecla_codigo==8 || tecla_codigo==0)return true;
        patron =/[0-9]/;
        tecla_valor = String.fromCharCode(tecla_codigo);
        return patron.test(tecla_valor);
    }
    
    function Mayusculas(e, elemento) {
        tecla=(document.all) ? e.keyCode : e.which; 
        elemento.value = elemento.value.toUpperCase();
    }     
            
    
    function resetear_formulario(nombre_formulario) {                
        $("#"+nombre_formulario).reset();                
    }    
    
    function eliminarFila(obj){    

        var oTr = obj;
        while(oTr.nodeName.toLowerCase()!='tr'){ oTr=oTr.parentNode; }
        var root = oTr.parentNode;

        root.removeChild(oTr);
    }     
    function LoadSelect2Script(callback){
        if (!$.fn.select2){
            $.getScript('plugins/select2/select2.min.js', callback); 
        }
        else {
            if (callback && typeof(callback) === "function") {
                callback();
            }
        }
    }
    
    function LoadSelect2ScriptExt(callback){
        if (!$.fn.select2){
            $.getScript('../../../plugins/select2/select2.min.js', callback); 
        }
        else {
            if (callback && typeof(callback) === "function") {
                callback();
            }
        }
    }  
    
    
    function cambiar_campo(identificador,nombre_campo_bd,nombre_identificador,div,div_boton,valor_campo,ruta_modificacion,nombre_tabla,nombre_campo){
        
        $('#'+div).html('<textarea class="form-control" id="'+nombre_campo+'" name="'+nombre_campo+'" rows="2" cols="2">'+valor_campo+'</textarea>')
        $('#'+div_boton).html('<a onclick="modificar_campo('+identificador+',\''+div+'\',\''+div_boton+'\',\''+ruta_modificacion+'\',\''+nombre_tabla+'\',\''+nombre_campo_bd+'\',\''+nombre_identificador+'\',\''+ruta_modificacion+'\',\''+nombre_tabla+'\',\''+nombre_campo+'\');"><img src="../../../images/save_2.png" style="width:25px"></a>')
        
    }
    
    function modificar_campo(identificador,div,div_boton,ruta_modificacion,nombre_tabla,nombre_campo_bd,nombre_identificador,ruta_modificacion,nombre_tabla,nombre_campo){
        
        var valor_campo = $('#'+nombre_campo).val();
        
        $.post(
                    ruta_modificacion,
                    '&identificador='+identificador+'&valor_campo='+valor_campo+'&nombre_tabla='+nombre_tabla+'&nombre_campo_bd='+nombre_campo_bd+'&nombre_identificador='+nombre_identificador,
                    function (resultado_controlador) {

                        if(resultado_controlador.resultado == 'modificado') {

                            swal({
                              type: 'success',                          
                              html: '<h3><b>Campo Modificado</b></h3>',
                              timer: 3000    
                            });  
                            
                            
                            $('#'+div).html(valor_campo);
                            $('#'+div_boton).html('<a onclick="cambiar_campo('+identificador+',\''+nombre_campo_bd+'\',\''+nombre_identificador+'\',\''+div+'\',\''+div_boton+'\',\''+valor_campo+'\',\''+ruta_modificacion+'\',\''+nombre_tabla+'\');"><img src="../../../images/save.png" style="width:20px"></a>');
                            
                            

                        }
                    
                    },
                    "json" 
                    );   

            return false;

        }         
        
               function cargar_css(posicion){
                   
                   $("#css"+posicion).load("../../vista/documents_business/result_css.php");       
               }        
        
    