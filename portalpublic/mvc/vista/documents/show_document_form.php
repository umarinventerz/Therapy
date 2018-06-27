<?php
session_start();
require_once("../../../conex.php");
require_once("../../../queries.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
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
    
    <title>.: KIDWORKS THERAPY :.</title>
<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">

<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">
<!--<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>-->
<link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
<link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
<link href="../../../css/style_v1.css" rel="stylesheet">
<link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
<script src="../../../js/listas.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="../../../js/devoops_ext.js"></script>
    <script>

       
                
                function mostrar_resultados(){

                    if($('#tipo_persona').val() == '' && $('#input_date_start').val() == '' && $('#input_date_end').val() == ''){
                        alert('Debe seleccionar al menos 1 campo');
                        return false;
                    }else{
                        var datos_formulario = $('#form_find_date').serialize();              
                
                        $('#resultado').load('show_document.php?'+datos_formulario);
                        return false;
                    }                                    
                }
               
               
                function cargar_valores_select(valor){                                   
                    if(valor == '1'){
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="Pat_id" name="Pat_id"><option value="">--- SELECT PATIENT ---</option></select>');
                        autocompletar_select('Pat_id','Patient_name','Pat_id',"Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name");                                                                    
                    }

                    if(valor == '2'){
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_employee" name="id_employee"><option value="">--- SELECT EMPLOYEE ---</option></select>');                
                        autocompletar_select('id_employee','terapist_name','id_employee',"Select Distinct id as id_employee, concat(first_name,' ',last_name) as terapist_name from employee order by first_name");                    
                    }                

                    if(valor == '3'){
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_insurance" name="id_insurance"><option value="">--- SELECT INSURANCE ---</option></select>');
                        autocompletar_select('id_insurance','insurance','id_insurance',"Select Distinct ID AS id_insurance, trim(insurance) as insurance from seguros order by insurance");                                                                    
                    }

                    if(valor == '4'){
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="Phy_id" name="Phy_id"><option value="">--- SELECT PHYSICIAN ---</option></select>');
                        autocompletar_select('Phy_id','Physician_name','Phy_id',"Select Distinct Phy_id, Name as Physician_name from physician ");                                                                    
                    }
                    if(valor == '5'){
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_referral" name="id_referral"><option value="">--- SELECT REFERRAL ---</option></select>');                
                        autocompletar_select('id_referral','First_name','id_referral',"Select id_referral, First_name from tbl_referral order by First_name");                    
                    }
                
                if(valor == '6'){
                    $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_persona_contacto" name="id_persona_contacto"><option value="">--- SELECT CONTACTS ---</option></select>');                
                    autocompletar_select('id_persona_contacto','persona_contacto','id_persona_contacto',"Select id_persona_contacto, persona_contacto from tbl_contacto_persona order by persona_contacto");                    
                }
                    
                    $('#type_document_div').html('<select class="populate placeholder" style="width: 250px" id="tipo_documento" name="tipo_documento"><option value="">--- SELECT TYPE DOCUMENT ---</option></select>');
                        autocompletar_select('id_type_documents','type_documents','tipo_documento',"Select td.id_type_documents,td.type_documents from tbl_doc_type_documents td LEFT JOIN tbl_doc_type_persons_documents tpd ON tpd.id_type_documents = td.id_type_documents WHERE id_type_persons = "+valor+" order by type_documents");                                            
                        
                    LoadSelect2ScriptExt(function(){
                            
                        $('#id_employee').select2();
                        $('#Pat_id').select2();
                        $('#id_insurance').select2();
                        $('#Phy_id').select2();
                        $('#id_referral').select2();
                        $('#id_persona_contacto').select2();
                        $('#tipo_documento').select2();  
                        

                    });                


                }
                
                function cargar_select_inicial(){   
                    <?php
                if($_SESSION['user_type'] >= 8){   ?>              
                    $('#tipo_persona_div').html('<select class="populate placeholder" style="width: 250px" id="tipo_persona" name="tipo_persona" onchange="cargar_valores_select(this.value);"><option value="">--- SELECT PERSON ---</option></select>');
 autocompletar_select('id_type_persons','type_persons','tipo_persona',"Select id_type_persons,type_persons from tbl_doc_type_persons order by id_type_persons ");
                          <?php  }else{ ?>
        $('#tipo_persona_div').html('<select class="populate placeholder" style="width: 250px" id="tipo_persona" name="tipo_persona" onchange="cargar_valores_select(this.value);"><option value="">--- SELECT PERSON ---</option></select>');
        autocompletar_select('id_type_persons','type_persons','tipo_persona',"Select id_type_persons,type_persons from tbl_doc_type_persons where id_type_persons!=2 order by id_type_persons");
                        <?php            } ?>
                }



  function eliminar_registro_document(id_document,ruta_archivo){
        
        swal({
                title: "Confirmation",
                text: "You want to unregister n° "+id_document+"?",
                type: "warning",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "Delete",   
                closeOnConfirm: false,
                closeOnCancel: false
                        }).then(function(isConfirm) {
                          if (isConfirm === true) {                                 

                           var campos_formulario = '&id_document='+id_document+'&ruta_archivo='+ruta_archivo;

                                $.post(
                                        "../../controlador/documents/eliminar_documents.php",
                                        campos_formulario,
                                        function (resultado_controlador) {

                                            if(resultado_controlador.resultado == 'eliminado') {

                                                swal({
                                                  title: "<h4><b>DOCUMENT DELETED</h4>",          
                                                  type: "success",              
                                                  showCancelButton: false,              
                                                  closeOnConfirm: true,
                                                  showLoaderOnConfirm: false,
                                                }); 
                                                
                                                var datos_formulario = $('#form_find_date').serialize();
                                                $("#resultado").load("show_document.php?"+datos_formulario);                                        


                                            }


                                        },
                                        "json" 
                                        ); 

                  }
                });    

        }

                
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    <!-- Page Content -->
    <div class="container" style="width:80%">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <br>
<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">			
                <h3>CHOOSE FROM THE DROPDOWN LISTS BELLOW</h3>                
            </div>
        </div>
<br/>
<div class="row">
	<div class="col-xs-12 col-sm-12">					
				<form class="form-horizontal" id="form_find_date" onSubmit="return mostrar_resultados();">										                                    
                                    <div class="form-group has-feedback">
                                        <label class="col-sm-2 control-label">Type Person</label>
                                        <div class="col-sm-4">
                                            <div id="tipo_persona_div"></div>    
                                        </div>
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-4">
                                            <div class="col-lg-3 text-center" id="tipo_persona_select">
                                                <select name="tipo_persona_temp" id="tipo_persona_temp" class="populate placeholder" style="width: 250px">
                                                    <option value="">--- SELECT TYPE PERSON ---</option>
                                                </select>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="form-group has-feedback"> 
                                        <label class="col-sm-2 control-label">Type Document</label>
                                        <div class="col-sm-4">
                                            <div id="type_document_div">
                                                <select name="tipo_documento" id="tipo_documento" class="populate placeholder">
                                                    <option value="">--- SELECT TYPE DOCUMENT ---</option>                                                    
                                                </select>      
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Start Date</label>
						<div class="col-sm-4">
                                                    <input type="text" id="input_date_start" name="input_date_start" class="form-control" placeholder="Date" >
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>	
						<label class="col-sm-2 control-label">End Date</label>
						<div class="col-sm-4">
							<input type="text" id="input_date_end" name="input_date_end" class="form-control" placeholder="Date" >
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>												
					</div>                                    
					<div class="clearfix"></div>
					<div class="form-group">												
                                            <div class="col-sm-2"></div>
                                                <div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left" style="width: 120px" value="submit" name="submit" id="submit">							
								SUBMIT
							</button>
						</div>	
						<div class="col-sm-2">
                                                    <button type="button" class="btn btn-primary btn-label-left" style="width: 120px" value="reset" name="reset" id="reset" onclick= "window.location.href = 'show_document_form.php';">							
								Reset
							</button>
						</div>				
					</div>
                                        
				</form>					
	</div>
</div><div id="resultado"></div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; KIDWORKS THERAPY 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
</body>
<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
	//$('#s2_with_tag').select2({placeholder: "Select Status"});	
	$('#tipo_persona').select2();
	$('#tipo_documento').select2();
        $('#tipo_persona_temp').select2();        
}
// Run timepicker
function DemoTimePicker(){
	$('#input_time').timepicker({setDate: new Date()});
}
$(document).ready(function() {
	// Create Wysiwig editor for textare
	// Add slider for change test input length
	// Initialize datepicker
	$('#input_date_start').datepicker({setDate: new Date()});
	$('#input_date_end').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	// Add tooltip to form-controls
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	// Load example of form validation
	// Add drag-n-drop feature to boxes
	
        $('#name_terapist').attr('disabled',true);
        cargar_select_inicial();
});
</script>
</html>
