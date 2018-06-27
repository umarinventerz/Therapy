<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
} 

if(isset($_GET['Pat_id'])){ 
    
$accion = 'modificar';
$titulo = 'Modificar';
$generar_input = '<input type="hidden" id="Pat_id" name="Pat_id" value="'.$_GET['Pat_id'].'">';
} else {
$accion = 'insertar';
$titulo = 'Registrar';
$generar_input = null;
}

?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">
    <script src="../../../js/devoops_ext.js"></script>
    <script type="text/javascript" language="javascript">

        function importar_datos_excel(){
        
            var formato_archivo = $('#archivo').val().substring($('#archivo').val().lastIndexOf('.') + 1).toLowerCase();
            var nombres_campos = '';
             
            if($('#archivo').val() == '' || $('#archivo').val() == null) {
                nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Introduzca un archivo .CSV</td></tr></table>';
            }  
            
            if(formato_archivo != 'csv') {
                nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * El formato Permitido es .CSV</td></tr></table>';
            }             
             
            if($('#caracter_separacion').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Caracter de Separación entre Columnas</td></tr></table>';
            }
            
            if(document.getElementById('nombre_tabla')){
                
            if($('#nombre_tabla').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Nombre de la nueva Tabla</td></tr></table>';
            }                
                
            }
        
        
                 if(nombres_campos != ''){ 
                         
                     swal({
                       title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
                       type: "info",
                       html: "<h4>"+nombres_campos+"</h4>",
                       showCancelButton: false,
                       animation: "slide-from-top",
                       closeOnConfirm: true,
                       showLoaderOnConfirm: false,
                     });
                         
                         return false; 
                     
                                      } else {          
        var crear_tabla;
        var no_update_files;
        var borrar_contenido_tabla;
   
        if ($('input[name="crear_tabla"]').is(':checked')) {       
            crear_tabla = 'si';       
        } else {       
            crear_tabla = 'no';       
        }  
        
        if ($('input[name="no_update_files"]').is(':checked')) {       
            no_update_files = 'si';       
        } else {       
            no_update_files = 'no';       
        }          
        
        if ($('input[name="borrar_contenido_tabla"]').is(':checked')) {       
            borrar_contenido_tabla = 'si';       
        } else {       
            borrar_contenido_tabla = 'no';       
        }          
        
        var form = $('form_gestion_actividad')[0]; 
        var formData = new FormData(form);                                                          

        var data = new FormData();
          
           data.append('archivo', $('input[type=file]')[0].files[0]);
           data.append('caracter_separacion',$('#caracter_separacion').val());
           data.append('nombre_tabla',$('#nombre_tabla').val());
           data.append('crear_tabla',crear_tabla);
           data.append('borrar_contenido_tabla',borrar_contenido_tabla);
           data.append('no_update_files',no_update_files);
           data.append('numero_linea_comprobacion',$('#numero_linea_comprobacion').val());
           
            if(document.getElementById('nombre_tabla')) {
                data.append('nombre_nueva_tabla',$('#nombre_tabla').val());
            }
            
            if(document.getElementById('nombre_schema')) {
                data.append('schema',$('#nombre_schema').val());
            }            
         
        $.ajax({
            url: "../../controlador/load_csv/importar_datos_excel.php",
            type: "POST",
            data: data, 
            processData: false,
            contentType: false,    
            dataType: 'json',
            success : function(resultado_controlador){

            if(resultado_controlador.resultado == 'datos_importados'){
            
                swal({
                  type: 'success',
                  title: '<h4><b>DATA ADDED TO THE TABLE!</b></h4>',  
                  timer: 3000          
                });  
                                                                
                $('#resultado').html(resultado_controlador.querys);                
                                                
            } else {                
                
                if(resultado_controlador.resultado == 'error_query'){
                    
                    swal({
                      type: 'error',
                      title: '<h4><b>ERROR EN EL QUERY</b></h4>',  
                      text: 'Please review the last Query Generated'
                    });                    
                    
                    $('#resultado').html(resultado_controlador.querys);                                       
                    
                }
                
                
                
                if(resultado_controlador.resultado == 'error_columnas'){
                
                    swal({
                      type: 'error',
                      title: '<h4><b>ERROR IN THE NUMBER OF COLUMNS</b></h4>',  
                      text: 'Please review the number of columns in the CSV file and Table:<br> <b>'+$('#nombre_tabla').val()+'</b>'
                    });                  
                
                }
                
                if(resultado_controlador.resultado == 'error_tabla'){
                
                    swal({
                      type: 'error',
                      title: '<h4><b>ERROR IN TABLE</b></h4>',  
                      text: 'Please check if the table <b>'+$('#nombre_tabla').val()+'</b> is available for data logging'
                    });                  
                
                }   
                
                if(resultado_controlador.resultado == 'error_tabla_existente'){
                
                    swal({
                      type: 'error',
                      title: '<h4><b>TABLE EXISTS</b></h4>',  
                      text: 'Please clear the table creation option and try again'
                    });                  
                
                }                
                
            }

              

            }
        }); 
        
        return false; 
        
            }

        }
        
        function validar_creacion_tabla(){
            
            if ($('input[name="crear_tabla"]').is(':checked')) {  
                $('#numero_linea_comprobacion').prop('readonly',true);
                $('#numero_linea_comprobacion').val(null);
                $('#borrar_contenido_tabla').prop('disabled',true);
            } else {
                $('#numero_linea_comprobacion').prop('readonly',false);
                $('#borrar_contenido_tabla').prop('disabled',false);
            }
            
        }        
        
        
        </script>
        
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>

          <form id="form_datos_excel" onsubmit="return importar_datos_excel(this);">                   
          
              
                  <div class="col-lg-8 text-center">
          <div class="form-group">              
                <label for="message-text" class="control-label"><font size="3" color="#848484">Select the file (.csv)</font></label>
                <input id="archivo" type="file" class="file" multiple=true data-preview-file-type="any">                        
          </div>
          <div class="form-group text-left">              
                <label for="message-text" class="control-label"><font size="2" color="#848484">Separator character Columns</font></label>
                <input id="caracter_separacion" name="caracter_separacion" type="text" class="form-control" style="width:40%;text-align: center" autocomplete="off">
          </div>            
          <div class="form-group">
            <div class="col-lg-3 text-left">
                <label for="message-text" class="control-label"><font size="2" color="#848484">¿Do you want to create the table for this file?</font><br></label>                
            </div>
              <div class="col-lg-1 text-left">
                  <input id="crear_tabla" name= "crear_tabla" type="checkbox" class="form-control" onclick="validar_creacion_tabla();">
            </div>
            <div class="col-lg-3 text-left">
                <label for="message-text" class="control-label"><font size="2" color="#848484">¿Do you want to delete the contents of table and insert the data?</font><br></label>                
            </div>
              <div class="col-lg-1 text-left">
                  <input id="borrar_contenido_tabla" name="borrar_contenido_tabla" type="checkbox" class="form-control" onclick="validar_creacion_tabla();">
            </div>               
            <div class="col-lg-3 text-left">
                <label for="message-text" class="control-label"><font size="2" color="#848484">¿Do you want to ignore rows modification?</font><br></label>                
            </div>
              <div class="col-lg-1 text-left">
                  <input id="no_update_files" name="no_update_files" type="checkbox" class="form-control">
            </div>              
          </div> 
            <br><br><br>
            <div class="col-lg-6">
                <input type="text" class="form-control" name="nombre_tabla" id="nombre_tabla" placeholder="Nombre de la Tabla" style="text-align:center" autocomplete="off">
            </div>
            <div class="col-lg-6">
                <input type="text" class="form-control" name="numero_linea_comprobacion" id="numero_linea_comprobacion" placeholder="Column number Check" style="text-align:center" autocomplete="off">
            </div>            
            <br><br><br>
            <div class="col-lg-12 text-left">
                <label for="message-text" class="control-label"><font size="2" color="#848484">Note: The first row of the file .csv corresponds to the column headings so will not be taken into account for insertion into the table</font></label>             
            </div>
            <br><br><br><br>
        <div class="form-group col-lg-12 text-left">            
            <button type="submit" class="btn btn-primary">Accept</button>
        </div>
    </div>
    <div class="col-lg-4"></div>
           
        </form>   
        <div class="row col-lg-12">
            <div id="resultado"></div>
        </div>