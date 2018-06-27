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
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<!--<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>-->
<link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
<link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
<link href="../../../css/style_v1.css" rel="stylesheet">
<link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>

<script src="../../../js/devoops_ext.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

 CSS 
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
<link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">
    <script>
	         
        function mostrar_resultados(resultado){
            var nombres_campos = '';
            if(resultado == 'resultado') {
                
                    if($("input[name=groupByPatient]:checked").length == 1){
                        if($('#insurance').val() == ''){
                            nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Insurance</td></tr></table>';
                        }
                    }else{
                        if($('#reference_number').val() == '' && $('#insurance').val() == '' && $('#name_patient').val() == ''){

                                nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Reference Number</td></tr></table>';
                                nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> OR</table>';
                                nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Insurance or Patient name</td></tr></table>';
                        }
                    }
                    
                
                if(nombres_campos != ''){ 
                            swal({
                            title: "<h3><b>Complete the following fields<b></h3>",          
                            type: "error",
                            html: "<h4>"+nombres_campos+"</h4>",
                              
                            confirmButtonColor: "#3085d6",                   
                            confirmButtonText: "Ok",   
                            closeOnConfirm: false
                            
                            });

                            return false; 

                    }else{
                        var datos_formulario = $('#form_find_date').serialize();                              
                        $('#resultado').load('result_treatments_charge.php?'+datos_formulario);
                    }
                
                
            }
            
            if(resultado == 'resultado_modal') {
                var nombres_campos;
                var reference_number = '';
                var insurance_patient = '';
                if($('#numero_referencia').val() == ''){
                    //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Reference Number</td></tr></table>';                                        
                    reference_number = 'empty';
                }
                if($('#insurance_m').val() == '' && $('#name_patient_m').val() == ''){
                    //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Insurance or Patient name</td></tr></table>';
                    insurance_patient = 'empty';
                }
                
                if(reference_number == 'empty'){
                    alert('debe llenar reference_numbre');
                }else{
                    if(insurance_patient == 'empty'){
                        alert('debe llenar INSURANCE O PATIENT');
                    }else{
                        var datos_formulario = $('#form_find_modal').serialize();   
                
                        $('#resultado_modal').load('result_treatments_modal.php?'+datos_formulario);
                    }
                }
                
                
//                if(nombres_campos != ''){ 
//                swal({
//                title: "<h3><b>Complete the following fields<b></h3>",          
//                type: "error",
//                html: "<h4>"+nombres_campos+"</h4>",
//                showCancelButton: false,   
//                confirmButtonColor: "#3085d6",                   
//                confirmButtonText: "Ok",   
//                closeOnConfirm: false,
//                closeOnCancel: false
//                });
//
//                            return false; 
//
//                    }else{
                        
//                    }
                
                
            }
            
            return false;
            
        }

        function marcarCheckEstatico(check){
            alert('Unable to uncheck this field' )
            $("#"+check.id).prop('checked','checked');
        }    

        function disabledFields(valor){           
            if(valor != ''){
                $('#insurance').attr('disabled',true);
                $('#name_therapyst').attr('disabled',true);
                $('#name_patient').attr('disabled',true);
                $('#input_date_start').attr('disabled',true);
                $('#input_date_end').attr('disabled',true);
                $('#pay_status').attr('disabled',true);
                $('#status').attr('disabled',true);
                $('#groupByPatient').attr('disabled',true);
                
            }else{
                $('#insurance').attr('disabled',false);
                $('#name_therapyst').attr('disabled',false);
                $('#name_patient').attr('disabled',false);
                $('#input_date_start').attr('disabled',false);
                $('#input_date_end').attr('disabled',false);
                $('#pay_status').attr('disabled',false);
                $('#status').attr('disabled',false);
                $('#groupByPatient').attr('disabled',false);                
            }
        }
               
        function resetModal(){
            $('#numero_referencia').val('');
            $('#insurance_m').val('').change();            
            $('#name_patient_m').val('').change();            
            $('#input_date_start_m').val('');
            $('#input_date_end_m').val('');
            $('#resultado_modal').html('');
        }
        
        function bloquearPatient(){
            if($("input[name=groupByPatient]:checked").length == 1){
                $("#name_patient").prop('disabled','disabled');
            }else{
                $("#name_patient").prop('disabled','');
            }
        }
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

<div class="modal fade" id="modal_filter_treatments" tabindex="-1" role="dialog" aria-labelledby="modal_filter_treatments">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
    <div class="modal-content">              
      <div class="modal-body text-center"> 
          <img src="../../../images/treatments.jpg" width="70" height="80"/><br><br>
          <h2 class="modal-title" id="modal_filter_treatments"><font color="#848484"><b>Treatments</b></font></h2>
          <br>
          <form class="form_modal" id="form_find_modal" onSubmit="return mostrar_resultados('resultado_modal');">
            <div class="form-group has-feedback">
                <div><b>Reference Number</b></div>
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <input type="text" class="form-control" name="numero_referencia" id="numero_referencia" style="text-align: center" required>
                </div>                               
                <div class="col-lg-3"></div>
            </div>
              <br><br>
              
              <div class="form-group has-feedback">
                <label class="col-sm-2 control-label">Insurance</label>
                <div class="col-sm-4">
                    <select name='insurance_m' id='insurance_m'>
                            <option value=''>--- SELECT ---</option>				
                        <?php
                            $sql  = "select distinct insurance from seguros where true";
                            $conexion = conectar();
                            $resultado = ejecutar($sql,$conexion);
                            while ($row=  mysqli_fetch_assoc($resultado)){	                                                                    
                                            print("<option value='".$row["insurance"]."' >".utf8_decode($row["insurance"])." </option>");
                            }

                        ?>

                    </select>                                                   
                </div>
                <label class="col-sm-2 control-label"> Patients </label>
                <div class="col-sm-4" id="nombre_paciente">
                    <select name='name_patient_m' id='name_patient_m'>
                            <option value=''>--- SELECT ---</option>				
                        <?php
                            $sql  = "Select concat(Last_name,', ',First_name) as patient_name,Pat_id from patients order by patient_name ";
                            $conexion = conectar();
                            $resultado = ejecutar($sql,$conexion);
                            while ($row=mysqli_fetch_array($resultado)){	                                                                    
                                            print("<option value='".$row["Pat_id"]."' >".utf8_decode($row["patient_name"])." </option>");
                            }

                        ?>

                    </select>                                                   
                </div>                
        </div>	
              <br><br><br>              

    <div class="form-group has-feedback">
                <label class="col-sm-2 control-label">Start Date</label>
                <div class="col-sm-4">
                    <input type="text" id="input_date_start_m" name="input_date_start_m" class="form-control" placeholder="Date" readonly="true" >
                        <span class="fa fa-calendar txt-danger form-control-feedback"></span>
                </div>	
                <label class="col-sm-2 control-label">End Date</label>
                <div class="col-sm-4">
                        <input type="text" id="input_date_end_m" name="input_date_end_m" class="form-control" placeholder="Date" readonly="true" >
                        <span class="fa fa-calendar txt-danger form-control-feedback"></span>
                </div>												
        </div>
              <br><br><br>
            <div class="row">            
                <div class="col-sm-2"> <input name='c_id_patient' id='c_id_patient' type="hidden" value="pat_id" ></div>
                <div class="col-sm-2"> <input name='c_patient_name' id='c_patient_name' type="hidden" value="patient_name" ></div>
                <div class="col-sm-2"> <input name='c_dos' id='c_dos' type="hidden" value="DOS" ></div>                                        
                <div class="col-sm-3"> <input name='c_cpt_code' id='c_cpt_code' type="hidden" value="cpt_code" ></div>
                
                <div class="col-sm-2"> <input name='c_status' id='c_status' type="hidden" value="status_treatments_charge as status_name" checked readonly="readonly"></div>
                <div class="col-sm-3"> <input name='c_notes' id='c_notes' type="hidden" value="notes" checked readonly="readonly"></div>
                <div class="col-sm-3"> <input name='c_patient_paid' id='c_patient_paid' type="hidden" value="pat_paid" checked readonly="readonly"></div>
                <div class="col-sm-4"> <input name='c_insurance_paid' id='c_insurance_paid' type="hidden" value="ins_paid" checked readonly="readonly"></div>
            </div>              
              <br>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary" onclick="mostrar_resultados('resultado_modal');">Consultar</button>
            </div>
          </form>
          
        <div class="row text-center">
            <div id="resultado_modal" class="col-sm-12"></div>
        </div>          
          
          
      </div>

    </div>
  </div>
</div>    
    
    <div class="form-group has-feedback">
                <div><b>Reference Number</b></div>
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
<!--                    <input type="text" class="form-control" name="numero_referencia" id="numero_referencia" style="text-align: center">-->
                </div>                               
                <div class="col-lg-3"></div>
            </div>
    
    <!-- Page Content -->
    <div class="container" style="width:90%">

        <!-- Portfolio Item Heading -->
			
<div class="row">
    
    <div class="col-sm-3"><h3>Select the reference number</h3></div>        
</div>
<div class="row">        
    
</div>        
<div class="row">
    
    
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">					
				<form class="form-horizontal" id="form_find_date" onSubmit="return mostrar_resultados('resultado');">
                                    
                                    <div class="form-group has-feedback">
                                        <label class="col-sm-2 control-label">Reference Number</label>
                                        <div class="col-sm-4" id="div_reference_number">
                                            <select name='reference_number' id='reference_number' onchange="disabledFields(this.value)">
                                                    <option value=''>--- SELECT ---</option>				
                                                <?php
                                                    $sql  = "Select  numero_referencia from tbl_treatments_associated group by numero_referencia";
                                                    $conexion = conectar();
                                                    $resultado = ejecutar($sql,$conexion);
                                                    while ($row=mysqli_fetch_array($resultado)){	                                                                    
                                                                    print("<option value='".$row["numero_referencia"]."' >".$row["numero_referencia"]." </option>");
                                                    }

                                                ?>
                                            </select>                                                   
                                        </div>	                                                												
                                    </div>
    <div class="form-group has-feedback">
        <div class="col-lg-10"><h3>Complete the following fields</h3></div>
        <div class="col-lg-2"><a style="cursor:pointer" onclick="resetModal();$('#modal_filter_treatments').modal('show');"><img src="../../../images/agregar.png" width="20" height="20"/> Add EDI&nbsp;&nbsp;<img src="../../../images/treatments.jpg" style="width: 30px"></a></div>
    </div>
                                    
<div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Insurance</label>
						<div class="col-sm-4">
                                                    <select name='insurance' id='insurance'>
                                                            <option value=''>--- SELECT ---</option>				
                                                        <?php
                                                            $sql  = "select distinct insurance from seguros where true";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){	                                                                    
                                                                            print("<option value='".$row["insurance"]."' >".utf8_decode($row["insurance"])." </option>");
                                                            }

                                                        ?>

                                                    </select>                                                   
						</div>	
                                                <label class="col-sm-2 control-label"> Group By Patient </label>
                                                <div class="col-sm-4" id="nombre_paciente" style="">                                                    
                                                    <input name='groupByPatient' id='groupByPatient' type="checkbox" value="1" onclick="bloquearPatient();"> <label class="col-sm-2 control-label"> </label>
                                                </div>
					</div>	  									
                                    <div class="form-group has-feedback centered">
						<label class="col-sm-2 control-label"> Therapyst </label>
						<div class="col-sm-4" id="nombre_terapista">
                                                    <select name='name_therapyst' id='name_therapyst' >
                                                            <option value=''>--- SELECT ---</option>				
                                                        <?php
                                                            $sql  = "Select concat(last_name,', ',first_name) as terapist_name,time_pay,licence_number from employee order by last_name ";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=mysqli_fetch_array($resultado)){	                                                                    
                                                                            print("<option value='".$row["licence_number"]."-".$row["time_pay"]."' >".utf8_decode($row["terapist_name"])."-".$row["licence_number"]." </option>");
                                                            }

                                                        ?>

                                                    </select>                                                   
						</div>
                                                <label class="col-sm-2 control-label"> Patients </label>
						<div class="col-sm-4" id="nombre_paciente">
                                                    <select name='name_patient' id='name_patient'>
                                                            <option value=''>--- SELECT ---</option>				
                                                        <?php
                                                            $sql  = "Select concat(Last_name,', ',First_name) as patient_name,Pat_id from patients order by patient_name ";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=mysqli_fetch_array($resultado)){	                                                                    
                                                                            print("<option value='".$row["Pat_id"]."' >".utf8_decode($row["patient_name"])." </option>");
                                                            }

                                                        ?>

                                                    </select>                                                   
						</div>
					</div>  										                                    
                                    
                                    <div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Start Date</label>
						<div class="col-sm-4">
                                                    <input type="text" id="input_date_start" name="input_date_start" class="form-control" placeholder="Date" readonly="true" >
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>	
						<label class="col-sm-2 control-label">End Date</label>
						<div class="col-sm-4">
							<input type="text" id="input_date_end" name="input_date_end" class="form-control" placeholder="Date" readonly="true" >
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>												
					</div>
                                    
                                    <div class="form-group has-feedback">                                                                                        
                                            <label class="col-sm-2 control-label">Pay Status</label>
                                            <div class="col-sm-4">
                                                <select name='pay_status' id='pay_status'>
                                                        			
                                                        <option value=''>-- All --</option>
                                                        <option value='1'>Billed</option>
                                                        <option value='2'>Approved</option>
                                                        <option value='3'>Denied</option>
                                                </select>                                                
                                            </div>
                                    </div>
                                    <input name='status' id='status' type="checkbox" value="3"> <label class="col-sm-2 control-label">Approved Incomplet </label>                                    
                                    <hr>
                                    <h3>Select the fields for to show</h3>
                                    <div class="form-group has-feedback">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-2"> <input name='c_id_patient' id='c_id_patient' type="checkbox" value="pat_id" checked> ID PATIENT </div>
                                        <div class="col-sm-2"> <input name='c_patient_name' id='c_patient_name' type="checkbox" value="patient_name" checked> PATIENT NAME</div>
                                        <div class="col-sm-2"> <input name='c_dos' id='c_dos' type="checkbox" value="DOS" checked> DOS</div>                                        
                                        <div class="col-sm-3"> <input name='c_cpt_code' id='c_cpt_code' type="checkbox" value="cpt_code" checked> CPT CODE</div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <div class="col-sm-3"></div>                                        
                                        <div class="col-sm-2"> <input name='c_therapyst_name' id='c_therapyst_name' type="checkbox" value="campo_9 as therapyst_name" checked> THERAPYST NAME</div>
                                        <div class="col-sm-2"> <input name='c_charge' id='c_charge' type="checkbox" value="charge" checked> CHARGE</div>
                                        <div class="col-sm-2"> <input name='c_patient_paid' id='c_patient_paid' type="checkbox" value="pat_paid" checked readonly="readonly" onclick="marcarCheckEstatico(this);"> PATIENT PAID</div>
                                        <div class="col-sm-3"> <input name='c_insurance_paid' id='c_insurance_paid' type="checkbox" value="ins_paid" checked readonly="readonly" onclick="marcarCheckEstatico(this);"> INSURANCE PAID</div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <div class="col-sm-3"></div>                                                                                
                                        <div class="col-sm-2"> <input name='c_balance' id='c_balance' type="checkbox" value="balance" checked> BALANCE</div>
                                        <div class="col-sm-2"> <input name='c_status' id='c_status' type="checkbox" value="status_treatments_charge as status_name" checked readonly="readonly" onclick="marcarCheckEstatico(this);"> STATUS</div>
                                        <div class="col-sm-2"> <input name='c_notes' id='c_notes' type="checkbox" value="notes" checked readonly="readonly" onclick="marcarCheckEstatico(this);"> NOTES </div>
                                        <div class="col-sm-3"> <input name='c_insurance_name' id='c_insurance_name' type="checkbox" value="insurance_name"> INSURANCE NAME</div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <div class="col-sm-3"></div>                                                                                
                                        <div class="col-sm-2"> <input name='c_discipline' id='c_discipline' type="checkbox" value="discipline"> DISCIPLINE</div>
                                        <div class="col-sm-2"> <input name='c_units' id='c_units' type="checkbox" value="units"> UNITS</div>
                                        <div class="col-sm-2"> <input name='c_write_off' id='c_write_off' type="checkbox" value="write_off"> WRITE OFF</div>
                                        <div class="col-sm-3"> <input name='c_patient_respon' id='c_patient_respon' type="checkbox" value="pat_respon"> PATIENT RESPONSABILITY</div>
                                    </div>                                    
                                    <div class="form-group has-feedback">
                                        <div class="col-sm-3"></div>                                        
                                        <div class="col-sm-2"> <input name='c_insurance_respon' id='c_insurance_respon' type="checkbox" value="ins_respon"> INSURANCE RESPONSABILITY</div>
                                        <div class="col-sm-2"> <input name='c_patient_balance' id='c_patient_balance' type="checkbox" value="pat_balance"> PATIENT BALANCE</div>
                                        <div class="col-sm-2"> <input name='c_insurance_balance' id='c_insurance_balance' type="checkbox" value="ins_balance"> INSURANCE BALANCE</div>
                                        <div class="col-sm-3"> <input name='c_reference_number' id='c_reference_number' type="checkbox" value="numero_referencia" checked> NUMBER REFERENCE</div>
                                    </div>    
                                    <div class="form-group has-feedback">
                                        <div class="col-sm-3"></div>                                        
                                        <div class="col-sm-2"> <input name='c_dob' id='c_dob' type="checkbox" value="DOB"> DOB</div>                                                                                
                                    </div>
                                    <hr>
                                    
					<div class="clearfix"></div>
					<div class="form-group">												
                                            <div class="col-sm-2"></div>
                                                <div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left" style="width: 120px" value="submit" name="submit" id="submit">							
								SUBMIT
							</button>
						</div>	
						<div class="col-sm-2">
                                                    <button type="button" class="btn btn-primary btn-label-left" style="width: 120px" value="reset" name="reset" id="reset" onclick= "window.location.href = 'filter_treatments_charge.php';">							
								Reset
							</button>
						</div>				
					</div>
                                        
				</form>					
	</div>
</div>


        <!-- /.row -->

       
        <hr>
        <div class="row">
            <div id="resultado" class="col-sm-12"></div>
        </div>
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
	$('#name_therapyst').select2();	
	$('#name_patient').select2();	
	$('#name_patient_m').select2();	
	$('#insurance').select2();	
	$('#insurance_m').select2();	
	$('#pay_status').select2();
        $('#reference_number').select2();
}
// Run timepicker
function DemoTimePicker(){
	$('#input_time').timepicker({setDate: new Date()});
}
$(document).ready(function() {
	// Create Wysiwig editor for textare
	TinyMCEStart('#wysiwig_simple', null);
	TinyMCEStart('#wysiwig_full', 'extreme');
	// Add slider for change test input length
	FormLayoutExampleInputLength($( ".slider-style" ));
	// Initialize datepicker
	$('#input_date_start').datepicker({setDate: new Date()});
	$('#input_date_end').datepicker({setDate: new Date()});
        $('#input_date_start_m').datepicker({setDate: new Date()});
	$('#input_date_end_m').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	LoadTimePickerScript(DemoTimePicker);
	// Add tooltip to form-controls
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	// Load example of form validation
	LoadBootstrapValidatorScript(DemoFormValidator);
	// Add drag-n-drop feature to boxes
	WinMove();
        
        //$('#name_terapist').attr('disabled',true);
      
});
</script>
</html>

