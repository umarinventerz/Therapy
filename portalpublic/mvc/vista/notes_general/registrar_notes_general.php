         
<?php

session_start();
require_once("../../../conex.php");

if(!isset($_SESSION['user_id'])){ ?>
	<script>alert('MUST LOG IN')</script>
	<script>window.location="../../../index.php";</script>
<?php }


if(isset($_GET['Phy_id'])){ 
    
$accion = 'modificar';
$titulo = 'Modificar';
$generar_input = '<input type="hidden" id="Phy_id" Name="Phy_id" value="'.$_GET['Phy_id'].'">';
} else {
$accion = 'insertar';
$titulo = 'Registrar';
$generar_input = null;
}

?>
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
    <script type="text/javascript" language="javascript">

    function Validar_Formulario_Gestion_Notes_general(nombre_formulario) {              
         
    var nombres_campos = '';
    
    <?php  if(!isset($_GET['id_notes_general'])){ ?>
                          if($('#id_notes_general').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Id Notes General</td></tr></table>';
                                
        }
        <?php } ?>
                        
                  if($('#notes_general').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Notes General</td></tr></table>';
                                
        }
          if($('#id_type_person').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Id Type Person</td></tr></table>';
                                
        }
          if($('#id_person').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Id Person</td></tr></table>';
                                
        }
          if($('#table_reference').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Table Reference</td></tr></table>';
                                
        }
          if($('#date_notes').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Date Notes</td></tr></table>';
                                
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
                if($('#date_notes').prop('checked')) { $('#date_notes').val(true);}
                 

                        var campos_formulario = $("#form_gestion_notes_general").serialize();
                        
                        $.post(
                                "../../controlador/notes_general/gestionar_notes_general.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    mostrar_datos(resultado_controlador);                                    
                                },
                                "json" 
                                );
                        
                        return false;
                    }
            }            
            
            function mostrar_datos(resultado_controlador) {                                         
           
            $('#resultado').html(resultado_controlador.resultado);
                      
            swal({
                title: resultado_controlador.mensaje,
                text: "Deseas Consultar los Registros Insertados?",
                type: "success",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "Consultar",   
                closeOnConfirm: false,
                closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                    
                    window.location.href = "../notes_general/consultar_notes_general.php?&consultar=si";                    
                  }else{
                      window.location.href = "../notes_general/registrar_notes_general.php";                                          
                  }
                    });             
                                            
           }    
           
                                      
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
                        autocompletar_select('id_referral','Referral_name','id_person',"Select id_referral, concat(Last_name,', ',First_name) as Referral_name from tbl_referral order by First_name");                    
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
        </script>

<body>
    
    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">

<form id="form_gestion_notes_general" onSubmit="return Validar_Formulario_Gestion_Notes_general('form_gestion_notes_general');">      
        <input type="hidden" class="form-control" id="table_reference" name="table_reference" placeholder="Table Reference" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['table_reference'])) { echo str_replace('|',' ',$_GET['table_reference']); }?>">
        <input type="hidden" class="form-control" id="date_notes" name="date_notes" placeholder="Date Notes" onkeyup="Mayusculas(event, this)" value="<?php echo date('Y-m-d h:i:s');?>">
        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> Notes General</font></h3>   </div>                  
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
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Notes General</font></label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" id="notes_general" name="notes_general" placeholder="Notes General" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['notes_general'])) { echo str_replace('|',' ',$_GET['notes_general']); }?>"></textarea>                                                
                                            </div>
                                        </div>                                                                                                                                                                                                                                      
                             </div>
                           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Aceptar</button> </div>
        </div>
    <input type="hidden" id="accion" name="accion" value="<?php echo $accion?>">
    <?php echo $generar_input;?>
</form>

        </div>
    </div>


        <div id="resultado" class="text-center"></div>
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
	// Load example of form validation
	// Add drag-n-drop feature to boxes
	
        //$('#name_terapist').attr('disabled',true);
        //cargar_select_inicial();
});
</script>