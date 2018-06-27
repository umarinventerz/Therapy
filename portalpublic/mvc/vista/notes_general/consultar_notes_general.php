
            
<?php

session_start();
require_once("../../../conex.php");

if(!isset($_SESSION['user_id'])){ ?>
	<script>alert('MUST LOG IN')</script>
	<script>window.location="../../../index.php";</script>
<?php } ?>
      
     <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>

<link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
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
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="../../../js/devoops_ext.js"></script>    
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>        
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/datatables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/datatables.responsive.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">
    <script type="text/javascript" language="javascript">
    
       function validar_formulario(){
                var parametros_formulario = $('#form_consultar_notes_general').serialize();
                               
                $("#resultado").empty().html('<img src="../../../imagenes/loader.gif" width="30" height="30"/>');                                
                $("#resultado").load("../notes_general/result_notes_general.php?"+parametros_formulario);
            
            return false;
        }
        $('#date_notes').datepicker({ format: 'dd-mm-yyyy'});
                                     $('#date_notes').prop('readonly', true);
                        

           function cargar_valores_select(valor){                                   
                    $('#table_reference').val('');
                    if(valor == '1'){ 
                        $('#table_reference').val('patients');
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_person" name="id_person"><option value="">--- SELECT PATIENT ---</option></select>');
                        autocompletar_select('Pat_id','Patient_name','id_person',"Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name");                                                                    
                    }

                    if(valor == '2'){    
                        $('#table_reference').val('employee');
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_person" name="id_person"><option value="">--- SELECT EMPLOYEE ---</option></select>');
                        autocompletar_select('id_employee','terapist_name','id_person',"Select Distinct id as id_employee, concat(first_name,' ',last_name) as terapist_name from employee order by first_name");                    
                    } 
                    
                    if(valor == '3'){
                        $('#table_reference').val('seguros');
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_person" name="id_person"><option value="">--- SELECT INSURANCE ---</option></select>');                
                        autocompletar_select('id_insurance','insurance','id_person',"SELECT Distinct id as id_insurance,insurance FROM seguros order by insurance");                    
                    }

                    if(valor == '4'){
                        $('#table_reference').val('physician');
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_person" name="id_person"><option value="">--- SELECT PHYSICIAN ---</option></select>');                
                        autocompletar_select('id_physician','physician','id_person',"Select Distinct Phy_id as id_physician, Name as physician from physician order by Name");                    
                    } 
                    
                    if(valor == '5'){
                        $('#table_reference').val('tbl_referral');
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_person" name="id_person"><option value="">--- SELECT REFERRAL ---</option></select>');                
                        autocompletar_select('id_referral','First_name','id_person',"Select id_referral, First_name from tbl_referral order by First_name");                    
                    }
                
                    if(valor == '6'){
                        $('#table_reference').val('tbl_contacto_persona');
                        $('#tipo_persona_select').html('<select class="populate placeholder" style="width: 250px" id="id_person" name="id_person"><option value="">--- SELECT CONTACTS ---</option></select>');                
                        autocompletar_select('id_persona_contacto','persona_contacto','id_person',"Select id_persona_contacto, persona_contacto from tbl_contacto_persona order by persona_contacto");                    
                    }
                    
                    LoadSelect2ScriptExt(function(){                            
                        $('#id_person').select2();                        
                    });
                }   
                
                function actualizar(){
                    window.location.href = "consultar_notes_general.php?consultar=si";
                }
        </script>

    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">  

<form id="form_consultar_notes_general"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD">Consultar Notes General</font></h3>   </div>                  
        </div>                    
                   
           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Person</font></label>

                                            <div class="col-sm-8">
                                                <select class="populate placeholder" style="width: 250px" id="id_type_person" name="id_type_person" onchange="cargar_valores_select(this.value);"><option value="">--- SELECT TYPE PERSON ---</option></select>
                                            </div>                                                               
                                        </div>
                                           
                                        <div class="form-group row">                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Person</font></label>
                                            <div class="col-sm-8" id="tipo_persona_select">
                                                <select name="id_person" id="id_person" class="populate placeholder" style="width: 250px">
                                                    <option value="">--- SELECT PERSON ---</option>
                                                </select>                                                
                                            </div>                                                               
                                        </div>
                                           
                                        <div class="form-group row">                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">User</font></label>                                            
                                            <div class="col-sm-8">
                                                <select name="id_user" id="id_user" class="populate placeholder" style="width: 250px">
                                                    <option value="">--- SELECT USER ---</option>
                                                </select>                                                                                            
                                            </div>
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Date Notes</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="date_notes" name="date_notes" placeholder="Date Notes" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['date_notes'])) { echo str_replace('|',' ',$_GET['date_notes']); }?>"></div>                                                               
                                            </div>
                                                                                                       
                             </div>
                           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-2" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
            <div class="col-sm-8" align="left"> <button onclick="actualizar();" class="btn btn-primary text-left">Reset</button> </div>
        </div>
</form>

        </div>
    </div>


        
        <div id="resultado" class="col-lg-12"></div>
        <br><br>
        <footer> 
            <div class="row"> 
                <div class="col-lg-12 text-center"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer>              
<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
	//$('#s2_with_tag').select2({placeholder: "Select Status"});	
	$('#id_type_person').select2();	
        $('#id_person').select2();	
        $('#id_user').select2();
        
}
// Run timepicker

$(document).ready(function() {
	// Create Wysiwig editor for textare
	// Add slider for change test input length
	// Initialize datepicker
	//$('#date_notes').datepicker({setDate: new Date()});
	//$('#input_date_end').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	// Add tooltip to form-controls
	//$('.form-control').tooltip();
        $('#date_notes').datepicker({setDate: new Date()});
        $('#date_notes').prop('readonly', true);
	LoadSelect2ScriptExt(DemoSelect2);
        autocompletar_select('id_type_persons','type_persons','id_type_person',"Select id_type_persons,type_persons from tbl_doc_type_persons order by id_type_persons ");
        autocompletar_select('user_id','complete_name','id_user',"SELECT *,concat(Last_name,\', \',First_name) as complete_name FROM user_system  order by Last_name ");
	// Load example of form validation
	// Add drag-n-drop feature to boxes
	
        //$('#name_terapist').attr('disabled',true);
        //cargar_select_inicial();
});
</script>

      <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') { echo '<script>validar_formulario();</script>'; } ?>


        