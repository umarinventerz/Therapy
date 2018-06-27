<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
if(isset($_SESSION['name'])){
	$_POST['name'] = trim($_SESSION['name']);
	$_POST['find'] = $_SESSION['find'];
}
$conexion = conectar();
if($_REQUEST['Discipline'] == 'OT'){
    $sql_company = "SELECT * FROM companies WHERE company_name LIKE '%DLC%';";
        
}else{
    if($_REQUEST['Discipline'] == 'ST'){
        if(rtrim($_GET['insurance']) == 'MOLINA HEALTH CARE OF FLORIDA'){
            $sql_company = "SELECT * FROM companies WHERE company_name LIKE '%DLC%';";
        }else{
            $sql_company = "SELECT * FROM companies WHERE company_name LIKE '%KQT%';";
        }
    }else{
        if($_REQUEST['Discipline'] == 'PT'){
            if(rtrim($_GET['insurance']) == 'MOLINA HEALTH CARE OF FLORIDA' || rtrim($_GET['insurance']) == 'WELLCARE (STAYWELL)'){
                $sql_company = "SELECT * FROM companies WHERE company_name LIKE '%DLC%';";
            }else{
                $sql_company = "SELECT * FROM companies WHERE company_name LIKE '%KQT%';";
            }
        }else{
            die('Error en la disciplina');
        }
    }
}

$resultado = ejecutar($sql_company,$conexion);
$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}
    $sql_treatments = "SELECT * FROM tbl_treatments t "
        . " LEFT JOIN cpt c ON c.cpt = t.campo_11 "
        . " LEFT JOIN employee e ON e.licence_number = t.license_number"
        . " WHERE c.type = 'EVAL' AND t.campo_10 = '".$_REQUEST['Discipline']."'  AND campo_5 = '".$_REQUEST['patient_id']."' ORDER BY campo_1 desc Limit 1 "; //AND c.type = 'EVAL'
    $resultado_treatments = ejecutar($sql_treatments,$conexion);
    $reporte_treatments = array();
    
    $i = 0;      
    while($datos = mysqli_fetch_assoc($resultado_treatments)) {            
        $reporte_treatments[$i] = $datos;
        $i++;
    }
    //echo $reporte_treatments[0]['first_name'];
    //die();
    $sql_patient = "SELECT * FROM patients WHERE Pat_id = '".$_REQUEST['patient_id']."'";
    $resultado_patient = ejecutar($sql_patient,$conexion);
    $reporte_patient = array();

    $i = 0;      
    while($datos = mysqli_fetch_assoc($resultado_patient)) {            
        $reporte_patient[$i] = $datos;
        $i++;
    }
    $sql_user_system = "SELECT * FROM user_system WHERE user_type >=1 AND user_type < 11 ORDER BY Last_name";
    $resultado_user_system = ejecutar($sql_user_system,$conexion);
    $reporte_user_system = array();

    $i = 0;      
    while($datos = mysqli_fetch_assoc($resultado_user_system)) {            
        $reporte_user_system[$i] = $datos;
        $i++;
    }
	
    $sql_list_company = "SELECT * FROM companies";
    $resultado_list_company = ejecutar($sql_list_company,$conexion);
    $reporte_list_company = array();

    $i = 0;      
    while($datos = mysqli_fetch_assoc($resultado_list_company)) {            
        $reporte_list_company[$i] = $datos;
        $i++;
    }
    $sql_prescription = "SELECT * FROM prescription p LEFT JOIN diagnosiscodes d ON d.DiagCodeValue = p.Diagnostic"
    . " join discipline on p.Discipline=discipline.Name and d.TreatDiscipId=discipline.DisciplineId "
    . " WHERE Patient_ID = '".$_REQUEST['patient_id']."' AND Discipline = '".$_REQUEST['Discipline']."' AND status = 1";// AND Discipline = '".$_REQUEST['Discipline']."' AND status = 1";
    $resultado_prescription = ejecutar($sql_prescription,$conexion);
    $reporte_prescription = array();
   
    $i = 0;      
    while($datos = mysqli_fetch_assoc($resultado_prescription)) {            
        $reporte_prescription[$i] = $datos;
        $i++;
    }
    //print_r($reporte_prescription);
    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: KIDWORKS THERAPY :.</title>

    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css">  
        <link href="../../../css/datepicker.css" rel="stylesheet" type="text/css">  
        <link href="../../../css/fileinput.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript" language="javascript" src="../../../plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../plugins/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/devoops_ext.js"></script>        
        <script type="text/javascript" language="javascript" src="../../../js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/listas.js"></script> 
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/promise.min.js"></script> 
        <script type="text/javascript" language="javascript" src="../../../js/jquery.price_format.2.0.min.js"></script> 
        <script type="text/javascript" language="javascript" src="../../../js/funciones.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/fileinput.js"></script>	
    <!-- End Style-->
    <script type="text/javascript" language="javascript">

    function Validar_Formulario_Gestion_Patient_intake_form(nombre_formulario) {              

        var nombres_campos = '';

      /*if($('#facility_group_name').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Facility Group Name</td></tr></table>';

            }
      if($('#tin_number').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Tin Number</td></tr></table>';

            }
      if($('#facility_group_address').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Facility Group Address</td></tr></table>';

            }
      if($('#facility_group_npi').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Facility Group Npi</td></tr></table>';

            }
      if($('#city').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * City</td></tr></table>';

            }
      if($('#state').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * State</td></tr></table>';

            }
      if($('#zip').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Zip</td></tr></table>';

            }
      if($('#contact_person').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Contact Person</td></tr></table>';

            }
      if($('#phone').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Phone</td></tr></table>';

            }
      if($('#fax').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Fax</td></tr></table>';

            }
      if($('#treating_therapist_name').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Treating Therapist Name</td></tr></table>';

            }
      if($('#treating_therapist_npi').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Treating Therapist Npi</td></tr></table>';

            }
      if($('#refering_provider_name').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Refering Provider Name</td></tr></table>';

            }
      if($('#refering_provider_npi').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Refering Provider Npi</td></tr></table>';

            }
      if($('#patient_name').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Last Name</td></tr></table>';

            }      
      if($('#patient_id').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Id</td></tr></table>';

            }
      if($('#patient_county').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient county</td></tr></table>';

            }
      if($('#patient_date_of_birth').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Date Of Birth</td></tr></table>';

            }
      if($('#medicare').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Medicare</td></tr></table>';

            }
      if($('#medicaid').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Medicaid</td></tr></table>';

            }
      if($('#medicaid_healthy_kids').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Medicaid Healthy Kids</td></tr></table>';

            }
      if($('#office_11').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Office 11</td></tr></table>';

            }
      if($('#independent_clinic_49').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Independent Clinic 49</td></tr></table>';

            }
      if($('#other').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Other</td></tr></table>';

            }
      if($('#primary_diagnosis_description').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Primary Diagnosis Description</td></tr></table>';

            }
      if($('#icd_code_1').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Icd Code 1</td></tr></table>';

            }
      if($('#icd_code_2').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Icd Code 2</td></tr></table>';

            }
      if($('#icd_code_3').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Icd Code 3</td></tr></table>';

            }
      if($('#icd_code_4').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Icd Code 4</td></tr></table>';

            }
      if($('#status_post_surgery').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Status Post Surgery</td></tr></table>';

            }
      if($('#date_of_surgery').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Date Of Surgery</td></tr></table>';

            }
      if($('#for_cerebral_vascular_accident').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * For Cerebral Vascular Accident</td></tr></table>';

            }
      if($('#physical_therapy').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Please Checkbox To Confirm 1</td></tr></table>';

            }
      if($('#occupational_therapy').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Please Checkbox To Confirm 2</td></tr></table>';

            }
      if($('#speech_therapy').val() == ''){
    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Please Checkbox To Confirm 3</td></tr></table>';

            }*/

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
                if($('#patient_date_of_birth').prop('checked')) { $('#patient_date_of_birth').val(true);}

                if($('#date_of_surgery').prop('checked')) { $('#date_of_surgery').val(true);}


                        var campos_formulario = $("#form_gestion_patient_intake_form").serialize();
                        
                        $.post(
                                "../../controlador/form_pif/insert_form_pif.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                        
                                if(resultado_controlador.resultado == 'insertado') {
        
                                    mostrar_datos(resultado_controlador);
                                    //resetear_formulario(nombre_formulario);
                                    
                                }
                                    
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
                    text: "Formulario registrado",
                    type: "success",
                    showCancelButton: true,   
                    confirmButtonColor: "#3085d6",   
                    cancelButtonColor: "#d33",   
                    confirmButtonText: "VER PDF",   
                    closeOnConfirm: false,
                    closeOnCancel: false
                    }).then(function(isConfirm) {
                      if (isConfirm === true) {                                    
                            window.location.href = resultado_controlador.ruta; 
                            //window.open(resultado_controlador.ruta,'','width=2500px,height=1200px,noresize');		                            
                      }else{
                           location.reload(true);
                      }
                });
                                            
           }
           
	   function changeCompany(valor){
                var dataCompany = new Array();
                dataCompany = valor.split('|');
                var dataFields = new Array()
                dataFields = dataCompany[1].split(',');               
		$('#tin_number').val(dataFields[0]);
		$('#facility_group_address').val(dataFields[1]);
    		$('#facility_group_npi').val(dataFields[2]);
		$('#city').val(dataFields[3]);
		$('#state').val(dataFields[4]);
		$('#zip').val(dataFields[5]);
		$('#phone').val(dataFields[6]);
		$('#fax').val(dataFields[7]);
	   }

           function updateNpi(valor){
               var dataTherapist = new Array();
               dataTherapist = valor.split('|');
               $('#treating_therapist_npi').val(dataTherapist[1]);
           }
           
           function desmarcarOpciones(desmarcar){
               var arregloDesmarcar = desmarcar.split(',');               
               var t = 0;
               while(arregloDesmarcar[t]){
                   $("#"+arregloDesmarcar[t]).prop("checked", "");                   
                   t++;
               }
           }
        </script>  
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
               <br>			 
			<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
         
        <div class="row">

            <div class="col-md-12">
<form id="form_gestion_patient_intake_form" onSubmit="return Validar_Formulario_Gestion_Patient_intake_form('form_gestion_patient_intake_form');">      
 
        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">Register Patient Intake Form</font></h3>   </div>                  
            <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"><img class="img-responsive" src="imagenes/imagen_sistema.jpg" alt="" width="50px" height="50px"></a></div>
        </div>                    
   
        <div class="form-group row">   
            <div class="col-sm-1"><input type="checkbox" name="routine" id="routine" onclick="desmarcarOpciones('urgent');" checked></div>
            <label class="col-sm-1 form-control-label text-left"><font color="#848484">Routine</font></label>            
            <div class="col-sm-1"><input type="checkbox" name="urgent" id="urgent" onclick="desmarcarOpciones('routine');"></div>
            <label class="col-sm-8 form-control-label text-left"><font color="#848484">Urgent(please indicate Medical reason in the Additional Information section below)</font></label>            
        </div>
    
        <div class="form-group row">   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Facility Group Name</font></label>            
            <div class="col-sm-4">
		<select class="form-control" id="facility_group_name" name="facility_group_name" onchange="changeCompany(this.value);">
			<option value="">---SELECT---</option>
			<?php 
				
				$t=0;
				while(isset($reporte_list_company[$t])){
					$selected = ($reporte_list_company[$t]['id'] == $reporte[0]['id'])?'selected':'';
					$stringData = $reporte_list_company[$t]['tin_number'].','.$reporte_list_company[$t]['facility_address'].','.$reporte_list_company[$t]['group_npi'].','.$reporte_list_company[$t]['facility_city'].','.$reporte_list_company[$t]['facility_state'].','.$reporte_list_company[$t]['facility_zip'].','.$reporte_list_company[$t]['facility_fax'];
					echo '<option value="'.$reporte_list_company[$t]['id'].'|'.$stringData.'" '.$selected.'>'.$reporte_list_company[$t]['company_name'].'</option>';
					$t++;
				}
			?>
		  </select>
	    </div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Tin Number</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="tin_number" name="tin_number" placeholder="Tin Number" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['tin_number'])) { echo $reporte[0]['tin_number']; }?>" readonly></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Facility Group Address</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="facility_group_address" name="facility_group_address" placeholder="Facility Group Address" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_address'])) { echo $reporte[0]['facility_address']; }?>" readonly></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Facility Group Npi</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="facility_group_npi" name="facility_group_npi" placeholder="Facility Group Npi" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['group_npi'])) { echo $reporte[0]['group_npi']; }?>" readonly></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">City</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="city" name="city" placeholder="City" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_city'])) { echo $reporte[0]['facility_city']; }?>" readonly></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">State</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="state" name="state" placeholder="State" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_state'])) { echo $reporte[0]['facility_state']; }?>" readonly></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Zip</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_zip'])) { echo $reporte[0]['facility_zip']; }?>" readonly></div>            
        </div>   
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Contact Person</font></label>
            <div class="col-sm-2"><select class="form-control" id="contact_person" name="contact_person">
					<option value="">---SELECT---</option>
					<?php 
						$t=0;
						while(isset($reporte_user_system[$t])){
							echo $selected = ($reporte_user_system[$t]['user_id'] == $_SESSION['user_id'])?'selected':'';
							echo '<option value="'.$reporte_user_system[$t]['user_id'].'" '.$selected.'>'.$reporte_user_system[$t]['Last_name'].' '.$reporte_user_system[$t]['First_name'].'</option>';
							$t++;
						}
					?>
				  </select>
	    </div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Phone</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_phone'])) { echo $reporte[0]['facility_phone']; }?>" readonly></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Fax</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="fax" name="fax" placeholder="Fax" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_fax'])) { echo $reporte[0]['facility_fax']; }?>" readonly></div>
        </div>   
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Treating Therapist Name</font></label>
            <div class="col-sm-4">
                                    <select class="form-control" id="treating_therapist_name" name="treating_therapist_name" onchange="updateNpi(this.value)">
					<option value="">---SELECT---</option>
					<?php 
                                                
                                                function quitarCaracteres($cadena){
                                                    $cadena = str_replace('MD','',$cadena);
                                                    $cadena = str_replace(',','',$cadena);
                                                    $cadena = str_replace('.','',$cadena);
                                                    $cadena = str_replace('-','',$cadena);
                                                    $cadena = str_replace(';','',$cadena);
                                                    $cadena = str_replace(' ','',$cadena);
                                                    $cadena = strtolower($cadena);
                                                    return $cadena;
                                                }
                                                $sql_treating_therapist = 'SELECT concat(Last_Name," ",First_Name) as therapist_name,npi_number as NPI FROM employee WHERE kind_employee <> "Administrative"';
                                                $resultado_treating_therapist = ejecutar($sql_treating_therapist,$conexion);
                                                $reporte_treating_therapist = array();

                                                $i = 0;      
                                                while($datos = mysqli_fetch_assoc($resultado_treating_therapist)) {            
                                                    $reporte_treating_therapist[$i] = $datos;
                                                    $i++;
                                                }
						$t=0;
                                                $selected = '';
						while(isset($reporte_treating_therapist[$t])){
                                                        $name_treatments = quitarCaracteres($reporte_treatments[0]['campo_9']);
                                                        $name_physician = quitarCaracteres($reporte_treating_therapist[$t]['therapist_name']);
                                                        if(isset($reporte_treatments[0]['campo_9']) && $name_treatments == $name_physician) { 
                                                            $selected = 'selected'; 
                                                            echo $reporte_treatments[0]['campo_9'];
                                                            echo $NPI = $reporte_treating_therapist[$t]['NPI'];
                                                        }else{
                                                            $selected = '';                                                                
                                                        }
							echo '<option value="'.$reporte_treating_therapist[$t]['therapist_name'].'|'.$reporte_treating_therapist[$t]['NPI'].'" '.$selected.'>'.$reporte_treating_therapist[$t]['therapist_name'].'</option>';
							$t++;
						}
					?>
                                    </select>
                <!--<input type="text" class="form-control" id="treating_therapist_name" name="treating_therapist_name" placeholder="Treating Therapist Name" onkeyup="Mayusculas(event, this)" value="<?php //if(isset($reporte_treatments[0]['campo_9'])) { echo $reporte_treatments[0]['campo_9']; }?>" readonly>-->   
            </div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Treating Therapist Npi</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="treating_therapist_npi" name="treating_therapist_npi" placeholder="Treating Therapist Npi" onkeyup="Mayusculas(event, this)" value="<?php echo $NPI; ?>" readonly></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Refering Provider Name</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="refering_provider_name" name="refering_provider_name" placeholder="Refering Provider Name" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte_prescription[0]['Physician_name'])) { echo $reporte_prescription[0]['Physician_name']; }?>" readonly></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Refering Provider Npi</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="refering_provider_npi" name="refering_provider_npi" placeholder="Refering Provider Npi" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte_prescription[0]['Physician_NPI'])) { echo $reporte_prescription[0]['Physician_NPI']; }?>" readonly></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Patient Name</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="patient_name" name="patient_name" placeholder="Patient Name" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_REQUEST['patient_name'])) { echo str_replace(' ','',$_REQUEST['patient_name']); }?>" readonly></div>               
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Patient Id</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="patient_id" name="patient_id" placeholder="Patient Id" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_REQUEST['patient_id'])) { echo str_replace('|',' ',$_REQUEST['patient_id']); }?>" readonly></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Patient county</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="patient_county" name="patient_county" placeholder="Patient county" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte[0]['facility_county'])) { echo $reporte[0]['facility_county']; }?>"></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Patient Date Of Birth</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="patient_date_of_birth" name="patient_date_of_birth" placeholder="Patient Date Of Birth" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte_patient[0]['DOB'])) { echo $reporte_patient[0]['DOB']; }?>"></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-3 form-control-label text-right"><font color="#A4A4A4">Line of Business</font></label>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Medicare</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="medicare" name="medicare" <?php if(isset($_GET['medicare']) && $_GET['medicare'] == 't') { echo 'checked'; }?>></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Medicaid</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="medicaid" name="medicaid" checked></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Medicaid Healthy Kids</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="medicaid_healthy_kids" name="medicaid_healthy_kids" <?php if(isset($_GET['medicaid_healthy_kids']) && $_GET['medicaid_healthy_kids'] == 't') { echo 'checked'; }?>></div>
        </div>
           
        <div class="form-group row">
            <label class="col-sm-3 form-control-label text-right"><font color="#A4A4A4">Place of Service</font></label>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Office 11</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="office_11" name="office_11" checked></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Independent Clinic 49</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="independent_clinic_49" name="independent_clinic_49" <?php if(isset($_GET['independent_clinic_49']) && $_GET['independent_clinic_49'] == 't') { echo 'checked'; }?>></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Other</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="other" name="other" <?php if(isset($_GET['other']) && $_GET['other'] == 't') { echo 'checked'; }?>></div>        
        </div>           
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Primary Diagnosis Description</font></label>
            <div class="col-sm-10"><input type="text" class="form-control" id="primary_diagnosis_description" name="primary_diagnosis_description" placeholder="Primary Diagnosis Description" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte_prescription[0]['DiagCodeDescrip'])) { echo $reporte_prescription[0]['DiagCodeDescrip']; }?>"></div>
        </div>
   
        <div class="form-group row">            
	    <label class="col-sm-1 form-control-label text-right"><font color="#A4A4A4">ICD 10</font>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <div class="col-sm-1"><input type="checkbox" class="form-control" id="icd_code_10" name="icd_code_10" placeholder="Icd Code 10" <?php if(isset($_GET['icd_code_10'])) { echo 'checked'; }?> checked></div>            
	    <label class="col-sm-1 form-control-label text-right"><font color="#848484">Icd Code 1</font></label>
	    <div class="col-sm-1"><input type="text" class="form-control" id="icd_code_1" name="icd_code_1" placeholder="Icd Code 1" onkeyup="Mayusculas(event, this)" value="<?php if(isset($reporte_prescription[0]['DiagCodeValue'])) { echo str_replace('|',' ',$reporte_prescription[0]['DiagCodeValue']); }?>"></div>   
            <label class="col-sm-1 form-control-label text-right"><font color="#848484">Icd Code 2</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="icd_code_2" name="icd_code_2" placeholder="Icd Code 2" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['icd_code_2'])) { echo str_replace('|',' ',$_GET['icd_code_2']); }?>"></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#848484">Icd Code 3</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="icd_code_3" name="icd_code_3" placeholder="Icd Code 3" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['icd_code_3'])) { echo str_replace('|',' ',$_GET['icd_code_3']); }?>"></div>   
            <label class="col-sm-1 form-control-label text-right"><font color="#848484">Icd Code 4</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="icd_code_4" name="icd_code_4" placeholder="Icd Code 4" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['icd_code_4'])) { echo str_replace('|',' ',$_GET['icd_code_4']); }?>"></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Status Post Surgery</font></label>
            <div class="col-sm-10"><input type="text" class="form-control" id="status_post_surgery" name="status_post_surgery" placeholder="Status Post Surgery" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['status_post_surgery'])) { echo str_replace('|',' ',$_GET['status_post_surgery']); }?>"></div>
        </div>
   
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Date Of Surgery</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="date_of_surgery" name="date_of_surgery" placeholder="Date Of Surgery" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['date_of_surgery'])) { echo str_replace('|',' ',$_GET['date_of_surgery']); }?>"></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">For Cerebral Vascular Accident</font></label>
            <div class="col-sm-4"><input type="text" class="form-control" id="for_cerebral_vascular_accident" name="for_cerebral_vascular_accident" placeholder="For Cerebral Vascular Accident" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['for_cerebral_vascular_accident'])) { echo str_replace('|',' ',$_GET['for_cerebral_vascular_accident']); }?>"></div>
        </div>

        <div class="form-group row">            
            <label class="col-sm-3 form-control-label text-right"><font color="#848484">Please check box to confirm</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="confirm_1" name="confirm_1" checked ></div>   
            <label class="col-sm-3 form-control-label text-right"><font color="#848484">Please check box to confirm</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="confirm_2" name="confirm_2" checked ></div>
            <label class="col-sm-3 form-control-label text-right"><font color="#848484">Please check box to confirm</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="confirm_3" name="confirm_3" checked ></div>
        </div>

	<div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Times/Per Weeks</font></label>
            <div class="col-sm-2">
              <select class="form-control" id="times_per_week" name="times_per_week">
		<option value="1">1</option>
	        <option value="2" selected>2</option>
	        <option value="3">3</option>
                <option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
	      </select>
	    </div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Number of Weeks</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="number_weeks" name="number_weeks" placeholder="Number of Weeks" value="26"></div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Physical Therapy</font></label>
            <div class="col-sm-1 text-left"><input type="hidden" value="<?php echo $_GET['Discipline'];?>" id="discipline" name="discipline"><input type="checkbox" id="physical_therapy" name="physical_therapy" <?php if(isset($_GET['Discipline']) && $_GET['Discipline'] == 'PT') { echo 'checked'; }?> onclick="desmarcarOpciones('occupational_therapy,speech_therapy');"></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Occupational Therapy</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="occupational_therapy" name="occupational_therapy" <?php if(isset($_GET['Discipline']) && $_GET['Discipline'] == 'OT') { echo 'checked'; }?> onclick="desmarcarOpciones('physical_therapy,speech_therapy');"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Speech Therapy</font></label>
            <div class="col-sm-1 text-left"><input type="checkbox" id="speech_therapy" name="speech_therapy" <?php if(isset($_GET['Discipline']) && $_GET['Discipline'] == 'ST') { echo 'checked'; }?> onclick="desmarcarOpciones('physical_therapy,occupational_therapy');"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Evaluation Date</font></label>
            <div class="col-sm-1 text-left"><input type="text" class="form-control" id="evaluation_date" name="evaluation_date" placeholder="Evaluation Date" value=""></div>
        </div>   

        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Test Used</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="test_used" name="test_used" onkeyup="Mayusculas(event, this)" value=""></div>   
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Test Result(Standard Devation)</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="result_standar" name="result_standar" onkeyup="Mayusculas(event, this)" value="" ></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Test Result(Age Equivalency)</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="result_age" name="result_age" onkeyup="Mayusculas(event, this)" value="" ></div>
	    <div class="col-sm-2">
                <!--<div class="form-group row">-->
                    <!--<div class="col-sm-4">-->
                        <input type="checkbox" class="form-control" id="month" name="month">
                    <!--</div><label class="col-sm-2 form-control-label text-left">-->
                        Month
                    <!--</label>-->                    
                <!--</div>-->
                <!--<div class="form-group row">-->
                    <!--<div class="col-sm-4">-->
                        <input type="checkbox" class="form-control" id="year" name="year" >
                    <!--</div><label class="col-sm-2 form-control-label text-left">-->
                        Year
                    <!--</label>-->
                </div>
	    </div>
	    <div class="col-sm-1">
		<br/>
		
	    </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Note/Comments</font></label>
            <div class="col-sm-10"><textarea class="form-control" id="note_comments" name="note_comments" placeholder="Notes" onkeyup="Mayusculas(event, this)"></textarea></div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Since Evaluation Date</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="since_evaluation_date" name="since_evaluation_date" placeholder="Since Evaluation Date" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['since_evaluation_date'])) { echo str_replace('|',' ',$_GET['since_evaluation_date']); }?>"></div>   
            <label class="col-sm-3 form-control-label text-right"><font color="#848484">Number Of Visits Attended</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="number_of_visits_attended" name="number_of_visits_attended" placeholder="Number Of Visits Attended" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['number_of_visits_attended'])) { echo str_replace('|',' ',$_GET['number_of_visits_attended']); }?>"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Date of Last Visit</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="date_of_last_visit" name="date_of_last_visit" placeholder="Date of Last Visit" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['date_of_last_visit'])) { echo str_replace('|',' ',$_GET['date_of_last_visit']); }?>"></div>            
        </div> 

	<div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Since Evaluation Date</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="since_evaluation_date_2" name="since_evaluation_date_2" placeholder="Since Evaluation Date" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['since_evaluation_date'])) { echo str_replace('|',' ',$_GET['since_evaluation_date']); }?>"></div>   
            <label class="col-sm-3 form-control-label text-right"><font color="#848484">Number Of Visits Attended</font></label>
            <div class="col-sm-1"><input type="text" class="form-control" id="number_of_visits_attended_2" name="number_of_visits_attended_2" placeholder="Number Of Visits Attended" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['number_of_visits_attended'])) { echo str_replace('|',' ',$_GET['number_of_visits_attended']); }?>"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Date of Last Visit</font></label>
            <div class="col-sm-2"><input type="text" class="form-control" id="date_of_last_visit_2" name="date_of_last_visit_2" placeholder="Date of Last Visit" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['date_of_last_visit'])) { echo str_replace('|',' ',$_GET['date_of_last_visit']); }?>"></div>            
        </div>       
    
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#848484">Additional Information</font></label>
            <div class="col-sm-10"><textarea class="form-control" id="additional_information" name="additional_information" placeholder="Additional Information" onkeyup="Mayusculas(event, this)"></textarea></div>
        </div>    
           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Aceptar</button> </div>
        </div>
  
</form>            
            </div>	    
        </div>
       
	<hr>
        
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p>
                </div>
            </div>
        </footer>

    </div>
    
    <!-- /.container -->
</body>
<script>
    
    $('#tin_number').attr('onkeypress','return SoloLetras(event)');
    $('#facility_group_npi').attr('onkeypress','return SoloLetras(event)');
    $('#zip').attr('onkeypress','return SoloLetras(event)');
    $('#treating_therapist_npi').attr('onkeypress','return SoloLetras(event)');
    $('#refering_provider_npi').attr('onkeypress','return SoloLetras(event)');
    $('#patient_id').attr('onkeypress','return SoloLetras(event)');
    $('#patient_date_of_birth').datepicker({ format: 'mm-dd-yyyy'});
    $('#patient_date_of_birth').prop('readonly', true);
    $('#date_of_surgery').datepicker({ format: 'mm-dd-yyyy'});
    $('#date_of_surgery').prop('readonly', true);
    $('#date_of_last_visit').datepicker({ format: 'mm-dd-yyyy'});
    $('#date_of_last_visit').prop('readonly', true);  
    $('#evaluation_date').datepicker({ format: 'mm-dd-yyyy'});
    $('#since_evaluation_date').datepicker({ format: 'mm-dd-yyyy'});
    $('#since_evaluation_date').prop('readonly', true);        
    $('#since_evaluation_date_2').datepicker({ format: 'mm-dd-yyyy'});
    $('#since_evaluation_date_2').prop('readonly', true);           
    $('#date_of_last_visit_2').datepicker({ format: 'mm-dd-yyyy'});
    $('#date_of_last_visit_2').prop('readonly', true);  
</script>
</html>
