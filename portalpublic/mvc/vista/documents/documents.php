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
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css">   
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>    
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>    
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/fileinput.js" type="text/javascript"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>    
    <script src="../../../js/funciones.js" type="text/javascript"></script>    
    
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <script type="text/javascript">
	
        function Validar_Formulario_Documents(){
                    
            var nombres_campos = '';

        camposInput = document.getElementsByTagName('select');        
        
        var tipo_persona = '';
        var Pat_id = '';
        var id_employee = '';
        var id_insurance = '';
        var id_physician = '';
        var id_referral = '';
        var id_contacts = '';
        
        var tipo_documento = '';


        for(j=0;j< camposInput.length;j++){
        nombreCampo = camposInput[j].name.substring(0,12);

            if(nombreCampo=='tipo_persona'){

        p = camposInput[j].name.substring(12,14);



        if($('#tipo_persona'+p).val() == ''){

            nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Tipo de Persona N° "+p+"</td></tr></table>";

        } else {

            tipo_persona += $("#tipo_persona"+p).val()+"|";

            if(document.getElementById('Pat_id'+p)) {

                if($('#Pat_id'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Patient ID N° "+p+"</td></tr></table>";

                } else {
                   
                   Pat_id += $("#Pat_id"+p).val()+"|";
                                                            
                }
                
            } else {
            
            Pat_id += "null|";
            
            }
            
            if(document.getElementById('id_employee'+p)) {

                if($('#id_employee'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Employee ID N° "+p+"</td></tr></table>";

                } else {
                    
                    id_employee += $("#id_employee"+p).val()+"|";
                                                            
                }
                
            } else {
            
            id_employee += "null|";
            
            }
            
            
            if(document.getElementById('id_insurance'+p)) {

                if($('#id_insurance'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Insurance ID N° "+p+"</td></tr></table>";

                } else {
                    
                    id_insurance += $("#id_insurance"+p).val()+"|";
                                                            
                }
                
            } else {
            
            id_insurance += "null|";
            
            }
            
            
            
            if(document.getElementById('id_physician'+p)) {

                if($('#id_physician'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Physician ID N° "+p+"</td></tr></table>";

                } else {
                    
                    id_physician += $("#id_physician"+p).val()+"|";
                                                            
                }
                
            } else {
            
            id_physician += "null|";
            
            }
            
            //referral
            if(document.getElementById('id_referral'+p)) {

                if($('#id_referral'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Referral ID N° "+p+"</td></tr></table>";

                } else {
                   
                   id_referral += $("#id_referral"+p).val()+"|";
                                                            
                }
                
            } else {
            
            id_referral += "null|";
            
            }
            
            
            //contacts 
            
            if(document.getElementById('id_persona_contacto'+p)) {

                if($('#id_persona_contacto'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Contacts ID N° "+p+"</td></tr></table>";

                } else {
                   
                   id_contacts += $("#id_persona_contacto"+p).val()+"|";
                                                            
                }
                
            } else {
            
            id_contacts += "null|";
            
            }
            
            if(document.getElementById('tipo_persona_temp')) {

                if($('#tipo_persona_temp'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Type Person N° "+p+"</td></tr></table>";

                }
                
            }            
           
                if($('#tipo_documento'+p).val() == ''){

                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Tipo Documento N° "+p+"</td></tr></table>";

                } else {
                    
                    tipo_documento += $("#tipo_documento"+p).val()+"|";
                                                            
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
    
    
    
    
    
        $('#c_tipo_persona_c').val(tipo_persona);
        $('#c_Pat_id_c').val(Pat_id);
        $('#c_id_employee_c').val(id_employee);
        $('#c_id_insurance_c').val(id_insurance);
        $('#c_id_physician_c').val(id_physician);
        $('#c_id_referral_c').val(id_referral);
        $('#c_id_persona_contacto_c').val(id_contacts);
        $('#c_tipo_documento_c').val(tipo_documento);
        
    
    
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
            url: "../../controlador/documents/insertar_archivo.php",
            type: "POST",
            data: data, 
            processData: false,
            contentType: false,                                 
            success : function(resultado_controlador_archivo){

               var result = resultado_controlador_archivo.indexOf("insertada");                                         

               if(result != null){     
           
                    swal({
                      type: 'success',                          
                      html: '<h3><b>Datos de Personas Almacenados</b></h3>',
                      timer: 3000    
                    }
                    );            
                    
                    
                 setTimeout(function(){location.reload()},1000);
           
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
            nuevaCelda.setAttribute('width','22,5%');
            nuevaCelda.innerHTML='<select class="populate placeholder" id="tipo_persona'+posicionCampo+'" name="tipo_persona'+posicionCampo+'" onchange="cargar_valores_select(this.value,'+posicionCampo+')"><option value="">--- SELECT ---</option></select>';

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','22,5%');
            nuevaCelda.innerHTML='<div class="col-lg-3 text-center" id="tipo_persona_select'+posicionCampo+'"><select name="tipo_persona_temp'+posicionCampo+'" id="tipo_persona_temp'+posicionCampo+'" class="populate placeholder" style="width: 250px"><option value="">--- SELECT TYPE PERSON ---</option></select></div>';
            
            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','22,5%');
            nuevaCelda.innerHTML='<select name="tipo_documento'+posicionCampo+'" id="tipo_documento'+posicionCampo+'" class="populate placeholder"><option value="">--- SELECT ---</option></select>';            

            nuevaCelda=nuevaFila.insertCell(-1);  
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','22,5%');
            nuevaCelda.innerHTML='<input name="file-'+posicionCampo+'" id="file-'+posicionCampo+'" class="file" type="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions=\'["csv","pdf"]\'>';            

            nuevaCelda=nuevaFila.insertCell(-1);                
            nuevaCelda.setAttribute('align','center');
            nuevaCelda.setAttribute('width','5%');                     
            nuevaCelda.innerHTML='<button type="button" class="btn btn-'+boton+'" id="btbuscar" onclick="'+accion+';">'+valor_boton+'</button><div id="css'+posicionCampo+'"></div>'; 
         
            autocompletar_select('id_type_persons','type_persons','tipo_persona'+posicionCampo,"Select id_type_persons, type_persons from tbl_doc_type_persons order by id_type_persons");                             
         
         
                LoadSelect2ScriptExt(function(){
                    
                    $('#tipo_persona'+posicionCampo).select2();
                    $('#tipo_documento'+posicionCampo).select2();                    
                    $('#tipo_persona_temp'+posicionCampo).select2();                    
                
                }); 
                
                
                cargar_css(posicionCampo);
         
         

               }
               
               
            function cargar_valores_select(valor,posicion){
               
                if(valor == '1'){
                    $('#tipo_persona_select'+posicion).html('<select class="populate placeholder" style="width: 250px" id="Pat_id'+posicion+'" name="Pat_id'+posicion+'"><option value="">--- SELECT PATIENT ---</option></select>');
                    autocompletar_select('Pat_id','Patient_name','Pat_id'+posicion,"Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name");                    
                }

                if(valor == '2'){
                    $('#tipo_persona_select'+posicion).html('<select class="populate placeholder" style="width: 250px" id="id_employee'+posicion+'" name="id_employee'+posicion+'"><option value="">--- SELECT EMPLOYEE ---</option></select>');                
                    autocompletar_select('id_employee','terapist_name','id_employee'+posicion,"Select Distinct id as id_employee, concat(first_name,' ',last_name) as terapist_name from employee order by first_name");                    
                }
                
                if(valor == '3'){
                    $('#tipo_persona_select'+posicion).html('<select class="populate placeholder" style="width: 250px" id="id_insurance'+posicion+'" name="id_insurance'+posicion+'"><option value="">--- SELECT INSURE ---</option></select>');                
                    autocompletar_select('id_insurance','insurance','id_insurance'+posicion,"SELECT Distinct id as id_insurance,insurance FROM seguros order by insurance");                    
                }
                
                if(valor == '4'){
                    $('#tipo_persona_select'+posicion).html('<select class="populate placeholder" style="width: 250px" id="id_physician'+posicion+'" name="id_physician'+posicion+'"><option value="">--- SELECT PHYSICIAN ---</option></select>');                
                    autocompletar_select('id_physician','physician','id_physician'+posicion,"Select Distinct Phy_id as id_physician, Name as physician from physician order by Name");                    
                }
                if(valor == '5'){
                    $('#tipo_persona_select'+posicion).html('<select class="populate placeholder" style="width: 250px" id="id_referral'+posicion+'" name="id_referral'+posicion+'"><option value="">--- SELECT REFERRAL ---</option></select>');                
                    autocompletar_select('id_referral','Referral_name','id_referral'+posicion,"Select id_referral, concat(Last_name,', ',First_name) as Referral_name from tbl_referral order by First_name");                    
                }
                
                if(valor == '6'){
                    $('#tipo_persona_select'+posicion).html('<select class="populate placeholder" style="width: 250px" id="id_persona_contacto'+posicion+'" name="id_persona_contacto'+posicion+'"><option value="">--- SELECT CONTACTS ---</option></select>');                
                    autocompletar_select('id_persona_contacto','persona_contacto','id_persona_contacto'+posicion,"Select id_persona_contacto, persona_contacto from tbl_contacto_persona order by persona_contacto");                    
                }
                
                $('#tipo_documento'+posicion).html('<select class="populate placeholder" style="width: 250px" id="tipo_documento'+posicion+'" name="tipo_documento'+posicion+'"><option value="">--- SELECT ---</option></select>');
                autocompletar_select('id_type_documents','type_documents','tipo_documento'+posicion,"Select id_type_documents, type_documents from tbl_doc_type_persons_documents left join tbl_doc_type_documents td using(id_type_documents) where id_type_persons = "+valor+" order by id_type_documents");                    

                LoadSelect2ScriptExt(function(){

                    $('#id_employee'+posicion).select2();
                    $('#Pat_id'+posicion).select2();
                    $('#id_insurance'+posicion).select2();
                    $('#id_physician'+posicion).select2();
                    $('#id_referral'+posicion).select2();
                    $('#id_persona_contacto'+posicion).select2();

                });                


            }      
                                        
            $(document).ready(function() {
		$('#example').DataTable({

            dom: 'Bfrtip',
            lengthMenu: [[ 10, 25, 50, -1], [ 10, 25, 50, "All"]],
            buttons: [
                'pageLength',
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "pageLength": 25
		} );
            } );  
               
                
                
	</script>
</head>

<body>
    
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    
    <div class="panel-collapse collapse in" style="padding-top: 30px;padding-bottom: 10px;padding-right: 30px;padding-left: 30px;">

        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <form id="myFormDocument" onSubmit="return Validar_Formulario_Documents();">
                
                <div class="row">
                    <div class="col-lg-12">                        
                        <h3 class="page-header">Load Documents</h3>                        
                    </div>
                    <div class="col-lg-12">
                        <a onclick="agregar_nuevo_registro('form_type_documents','<form  id=\'form_type_documents\' name=\'form_type_documents\'><table align=\'center\'><tr><td align=\'left\'>Type Person: <select class=\'populate placeholder\' id=\'tipo_persona_agregar\' name=\'tipo_persona_agregar\'><option value=\'\'>--- SELECT ---</option></select></td></tr><tr><td>&nbsp;</td></tr><tr><td align=\'left\'>Type Document: <input class=\'form-control\' id=\'valor_tipo_documento\' name=\'valor_tipo_documento\'></td></tr></table></form>','../../controlador/type_persons_documents/insertar_type_persons_documents.php'); autocompletar_select('id_type_persons','type_persons','tipo_persona_agregar','Select id_type_persons, type_persons from tbl_doc_type_persons order by type_persons');LoadSelect2ScriptExt(function(){$('#tipo_persona_agregar').select2();});" style="cursor: pointer">New Type Document <img src="../../../images/agregar.png" width="20" height="20"/></a>
                    </div>
                    
                </div>
           
                <br>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <div class="col-lg-3 text-center">Type Person</div>
                        <div class="col-lg-3 text-center">Name</div>
                        <div class="col-lg-3 text-center">Type Document</div>                    
                        <div class="col-lg-3 text-center">Load Document</div>
                    </div>
                </div>                
                <table width="100%" border="1" bordercolor="#D8D8D8" align="center" id="nuevos_registro_documento" cellpadding="0" cellspacing="0"></table>
                <input type="hidden" id="c_tipo_persona_c" name="c_tipo_persona_c">
                <input type="hidden" id="c_Pat_id_c" name="c_Pat_id_c">
                <input type="hidden" id="c_id_employee_c" name="c_id_employee_c">
                <input type="hidden" id="c_id_insurance_c" name="c_id_insurance_c">
                <input type="hidden" id="c_id_physician_c" name="c_id_physician_c">
                <input type="hidden" id="c_id_referral_c" name="c_id_referral_c">
                <input type="hidden" id="c_id_persona_contacto_c" name="c_id_persona_contacto_c">
                <input type="hidden" id="c_tipo_documento_c" name="c_tipo_documento_c">
            
            <br>       
            
                <div class="form-group col-lg-12">                    
                    <button type="submit" class="btn btn-primary text-left">Load</button> 
                </div>                             
            </form>      
        
            <br>
            
            <div id="resultado_type_persons_documents"></div>
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
    $("#resultado_type_persons_documents").load("result_type_persons_documents.php");

    </script>
</html>
	
