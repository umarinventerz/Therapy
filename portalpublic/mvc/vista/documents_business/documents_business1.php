<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">    
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    <link href="../../../css/dataTables/dataTables.bootstrap.css"rel="stylesheet" type="text/css">
    <link href="../../../css/dataTables/buttons.dataTables.css"rel="stylesheet" type="text/css">    
    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>    
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/AjaxConn.js" type="text/javascript" ></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>    
    <script src="../../../js/promise.min.js" type="text/javascript"></script>    
    <script src="../../../js/funciones.js" type="text/javascript"></script>        
    <script src="../../../js/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../../../js/resources/shCore.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/buttons.html5.js" type="text/javascript"></script>    
        
    <script type="text/javascript">
	
        
       $(document).ready(function() {
		$('#example').DataTable({
			pageLength: 1500,
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		} );
            } );          
        
        
        function Validar_Formulario_Documents(){
                    
            var nombres_campos = '';

        camposInput = document.getElementsByTagName('select');        
        
        var departaments = '';
        var tipo_documento = '';
        var version = '';
        var description = '';


        for(j=0;j< camposInput.length;j++){
        nombreCampo = camposInput[j].name.substring(0,15);

            if(nombreCampo=='id_departaments'){

        p = camposInput[j].name.substring(15,17);



        if($('#id_departaments'+p).val() == ''){

            nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Departamento N° "+p+"</td></tr></table>";

        } else {

            departaments += $("#id_departaments"+p).val()+"|";
          
           
                if($('#tipo_documento'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Tipo Documento N° "+p+"</td></tr></table>";

                } else {
                    
                    tipo_documento += $("#tipo_documento"+p).val()+"|";
                    
                if($('#version'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Versión N° "+p+"</td></tr></table>";

                } else {
                    
                    version += $("#version"+p).val()+"|";
                      
                        if($('#description'+p).val() == ''){

                            nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Description N° "+p+"</td></tr></table>";

                        } else {

                            description += $("#description"+p).val()+"|";

                        }                       
                      
                      
                }                    
                    
                    
                                                            
                }
                
                        
                          
            
            


            }
        
        }
       
    }  
    
    
var t=1;
$('input[type=file]').each(function(){

if($(this).val() != '' && $(this).val() != null) {
             
             	var file_size = $(this)[0].files[0].size;
                
             	if(file_size>909715200) {
             	
                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"El Archivo N° "+p+" no puede ser mayor a 10MB</td></tr></table>";
                     
             	} else {
                        
                     switch($(this).val().substring($(this).val().lastIndexOf('.') + 1).toLowerCase()){
                         case 'csv':
                             
                             return true;
                             
                             break; 
                             
                         case 'pdf':
                             
                             return true;
                             
                             break; 
                             
                         default:
             
                        nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"El Archivo N° "+t+" solo puede ser formato CSV</td></tr></table>";          
             
                             break;            
                     }            
                         
                     }	
                     
                } else {
                    
                        nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Falta incluir un archivo en la fila N° "+t+" </td></tr></table>";          
                         
                    
                }    
                
                t++;
    
    });                    
    
        $('#c_id_departaments_c').val(departaments);
        $('#c_id_type_documents_c').val(tipo_documento);
        $('#c_version_c').val(version);  
        $('#c_description_c').val(description);  
    
    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,          
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else { 
                             

       var form = $('#myFormDocument')[0]; 
       var formData = new FormData(form);                                                        

        var campos_formulario = $("#myFormDocument").serialize();


        


        var data = new FormData();    
        $('input[type=file]').each(function(){
                
            data.append(this.name,$(this)[0].files[0]);               
        
        });
        
        
           data.append('campos_formulario',campos_formulario);           

        $.ajax({
            url: "../../controlador/documents_business/insertar_documents_business.php",
            type: "POST",
            data: data, 
            processData: false,
            contentType: false,                                 
            success : function(resultado_controlador_archivo){

               var result = resultado_controlador_archivo.indexOf("insertada");                                         

               if(result != null){     
           
                    swal({
                      type: 'success',                          
                      html: '<h3><b>Documentos Almacenados</b></h3>',
                      timer: 3000    
                    });        
                    
                    $("#resultado_documents_business").load("result_documents_business.php");                               
                    $("#nuevos_registro_documento").empty();
                    posicionCampo=0;
                    nuevo_documento();
           
           
           
           } else {

        swal({
          title: "<h4><b>ERROR AL INSERTAR EL ARCHIVO</h4>",          
          type: "error",              
          showCancelButton: false,              
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });                                     

           }

            }
        });  

                return false;

                }            
            
        }
        
        
        var posicionCampo=0;
        function nuevo_documento(accion2,valor){           

            nuevaFila = document.getElementById("nuevos_registro_documento").insertRow(-1);    
            nuevaFila.id=posicionCampo;

            posicionCampo++;  

            var accion = null;
            var boton = null;
            var valor_boton = null;
            if(posicionCampo == 1){ accion = 'nuevo_documento(null)'; boton = 'success'; valor_boton = '+';  } else { accion = 'eliminarFila(this)'; boton = 'danger'; valor_boton = '-'; }

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','5%');
            nuevaCelda.innerHTML=parseInt(posicionCampo);                    

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','16%');
            nuevaCelda.setAttribute('onchange','verificar_version('+posicionCampo+');');
            nuevaCelda.innerHTML='<select class="populate placeholder" id="id_departaments'+posicionCampo+'" name="id_departaments'+posicionCampo+'"><option value="">--- SELECT ---</option></select>';

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','16%');
            nuevaCelda.setAttribute('onchange','verificar_version('+posicionCampo+');');
            nuevaCelda.innerHTML='<select class="populate placeholder" id="tipo_documento'+posicionCampo+'" name="tipo_documento'+posicionCampo+'"><option value="">--- SELECT ---</option></select>';            

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','16%');
            nuevaCelda.innerHTML='<input type="text" class="form-control" name="version'+posicionCampo+'" id="version'+posicionCampo+'" onkeyup="Mayusculas(event, this)" readonly style="text-align: center">';            

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','25%');
            nuevaCelda.innerHTML='<textarea class="form-control" name="description'+posicionCampo+'" id="description'+posicionCampo+'" onkeyup="Mayusculas(event, this)"></textarea>';            

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','17%');
            nuevaCelda.innerHTML='<input name="file-'+posicionCampo+'" id="file-'+posicionCampo+'" class="file" type="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions=\'["csv","pdf"]\'>';            
            
            nuevaCelda=nuevaFila.insertCell(-1);                
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','5%');                     
            nuevaCelda.innerHTML='<button type="button" class="btn btn-'+boton+'" id="btbuscar" onclick="'+accion+';">'+valor_boton+'</button><div id="css'+posicionCampo+'"></div>'; 
         
            autocompletar_select('id_type_documents','type_documents','tipo_documento'+posicionCampo,"SELECT id_type_documents, type_documents FROM tbl_doc_type_documents td WHERE id_type_documents NOT IN ( SELECT distinct(id_type_documents) FROM tbl_doc_type_persons_documents )");
            autocompletar_select('id_departaments','departaments','id_departaments'+posicionCampo,"Select id_departaments, departaments from tbl_departaments");

            LoadSelect2ScriptExt(function(){

                $('#tipo_documento'+posicionCampo).select2();
                $('#id_departaments'+posicionCampo).select2();

            });  
            
            cargar_css(posicionCampo);
            
            
               }               
               
            function verificar_version(posicion){
                
                var tipo_documento = $('#tipo_documento'+posicion).val();
                var id_departaments = $('#id_departaments'+posicion).val();
                
                if(tipo_documento != '' && id_departaments != '') {
                

                    $.post(
                            "../../controlador/documents_business/verificar_version.php",
                            '&tipo_documento='+tipo_documento+'&id_departaments='+id_departaments,
                            function (resultado_controlador) {

                              $('#version'+posicion).val(resultado_controlador.version);

                            },
                            "json" 
                            );   

                    return false;
                
                }   
                
            }
            
            
            function cargar_result(div,archivo){                             
                $("#"+div).load(archivo);                
            }
               
               
            function cambiar_color(id_boton,color){

                if(id_boton == 'boton_db') {            
                    $("#boton_db").css({'background':color});
                    $("#boton_dep").css({'background':'#5bc0de'});
                    $("#boton_td").css({'background':'#5bc0de'});
                }
            
                if(id_boton == 'boton_dep') {            
                    $("#boton_dep").css({'background':color});
                    $("#boton_db").css({'background':'#5bc0de'});
                    $("#boton_td").css({'background':'#5bc0de'});                    
                }
            
                if(id_boton == 'boton_td') {            
                    $("#boton_td").css({'background':color});
                    $("#boton_dep").css({'background':'#5bc0de'});
                    $("#boton_db").css({'background':'#5bc0de'});                    
                }            

            }
                    
                
                
	</script>
</head>

<body>
    
    <?php $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">
<?php
        if($_SESSION['user_type'] <> 1  ){
?>

            <form id="myFormDocument" onSubmit="return Validar_Formulario_Documents();">
                
                <div class="row">
                    <div class="col-lg-12">                        
                        <h3 class="page-header">Load Documents Business&nbsp;&nbsp;<img src="../../../images/business.png" style="width: 35px"></h3>                        
                   </div>   
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <a onclick="agregar_nuevo_registro('form_type_documents','<form  id=\'form_type_documents\' name=\'form_type_documents\'><table align=\'center\'><tr><td align=\'left\'>Type Document: <input class=\'form-control\' id=\'valor_tipo_documento\' name=\'valor_tipo_documento\'></td></tr></table></form>','../../controlador/type_documents/insertar_type_documents.php');" style="cursor: pointer">New Type Document <img src="../../../images/agregar.png" width="20" height="20"/></a>
                    </div>  
                    <div class="col-lg-2">
                        <a onclick="agregar_nuevo_registro('form_departaments','<form  id=\'form_departaments\' name=\'form_departaments\'><table align=\'center\'><tr><td align=\'left\'>Departament: <input class=\'form-control\' id=\'valor_departament\' name=\'valor_departament\' size=\'30\'></td></tr></table></form>','../../controlador/departaments/insertar_departaments.php');" style="cursor: pointer">New Departament <img src="../../../images/agregar.png" width="20" height="20"/></a>
                    </div>              
                    <div class="col-lg-8"></div>
                    
                </div>
           
                <br>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <div class="col-lg-1 text-left">N°</div>
                        <div class="col-lg-2 text-left">Departament</div>
                        <div class="col-lg-2 text-left">Type Document</div>
                        <div class="col-lg-2 text-left">Version</div>    
                        <div class="col-lg-2 text-left">Description</div>    
                        <div class="col-lg-2 text-center">Load Document</div>
                        <div class="col-lg-1 text-right">A/Q</div>
                    </div>
                </div>                
                <table width="100%" border="1" bordercolor="#D8D8D8" align="center" id="nuevos_registro_documento" cellpadding="0" cellspacing="0"></table>
                <input type="hidden" id="c_id_departaments_c" name="c_id_departaments_c">
                <input type="hidden" id="c_id_type_documents_c" name="c_id_type_documents_c">
                <input type="hidden" id="c_version_c" name="c_version_c">       
                <input type="hidden" id="c_description_c" name="c_description_c">       
            
            <br>       
            
                <div class="form-group col-lg-12">                    
                    <button type="submit" class="btn btn-primary text-left">Load</button> 
                </div>                             
            </form>      
       

       <?php } ?>


           <br><br><br>
            
                <div class="row">     

                    <div class="col-lg-12 text-center">                        
                        <button class="btn btn-info" id="boton_db" type="button" onclick="cargar_result('resultado_documents_business','result_documents_business.php');cambiar_color('boton_db','#01A9DB');"><img src="../../../images/lupa.png" width="20" height="20"/> Documents Uploaded</button>                       
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <?php
        if($_SESSION['user_type'] <> 1  ){
?>

                        <button class="btn btn-info" id="boton_dep" type="button" onclick="cargar_result('resultado_documents_business','../departaments/result_departaments.php');cambiar_color('boton_dep','#01A9DB');"><img src="../../../images/lupa.png" width="20" height="20"/> Departaments</button>                       
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-info" id="boton_td" type="button" onclick="cargar_result('resultado_documents_business','../type_documents/result_type_documents.php');cambiar_color('boton_td','#01A9DB');"><img src="../../../images/lupa.png" width="20" height="20"/> Type Documents</button>

     <?php } ?>

                    </div>                                                             
                </div>            
            <br>
            <div id="resultado_documents_business"></div>
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; THERAPY  AID 2016</p>
                    </div>
                </div>
                
            </footer>

        </div>
    </div>
    <!-- /.container -->
</body>
    <script type="text/javascript">

    nuevo_documento();   
    $("#resultado_documents_business").load("result_documents_business.php");

    </script>
</html>
	
