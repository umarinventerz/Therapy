                        
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
		    	var ventana=window.open('print_teraphist.php?identificadores_check='+valores_check+'&date_start='+$("#date_start").val()+'&date_end='+$("#date_end").val()+'&type_employee='+$("#type_employee").val()+'&selectedTreatments='+$("#selectedTreatments").val(),'print_teraphist','width=1200, height=800, scrollbars=1, location=no,left=300,top=50');                 		        
			//ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
			ventana.document.close();  //cerramos el documento
			ventana.print();  //imprimimos la ventana
			//ventana.close();
                }
		
	}
	function abrirVentana(discipline,cpt,type){		
		window.open('windows_report_amount.php?discipline='+discipline+'&cpt='+cpt+'&type='+type,'','width=2500px,height=700px,noresize');		
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
