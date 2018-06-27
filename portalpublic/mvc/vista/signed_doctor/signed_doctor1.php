<?php
session_start();
require_once("../../../conex.php");
require_once("../../../queries.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
if(isset($_POST['Pat_id'])){
	$conexion = conectar();
	$patient_id= $_POST["Pat_id"];

		
	$display = 'block';
	$sql1  = 	"Select  Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name 
								from patients 								
								where Pat_id = '".$patient_id."'
								group by Pat_id, Table_name
								order by Patient_name asc ";
	$resultado1 = ejecutar($sql1,$conexion);
	
	while ($row=mysqli_fetch_array($resultado1)) 
	{
		$patient_name = str_replace(',',' ',str_replace(' ','',$row["Patient_name"]));	
	}
	$disabled = '';
}else{
	$disabled = 'disabled';
	$display = 'none';
}
if($_POST['NPI']==''){
	$displayPhysician = 'none';
}else{
	$displayPhysician = 'block';
}

if($_POST['rowHidden'] == '' ||  $_POST['Pat_id'] == '' || $_POST['NPI'] == ''){
	$disabledFile = 'disabled';
}else{
	$disabledFile = '';
}
if($_POST['insert'] == " Add "){

	if(!isset($_FILES['file-1']['name'])){
		echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
		echo '<script>window.location="signed_doctor1.php";</script>';
	}
	else    
	if($_FILES['file-1']['type'] != 'application/pdf'){
		echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
		echo '<script>window.location="signed_doctor1.php";</script>';
	}else{		
		$conexion = conectar();
		//Si existe un registro en la tabla signed_doctor con el Patient_ID, CPT y la Discipline iguales
		// Modifica a status = 0 ese registro e inserta el nuevo registro
		list($discipline,$cpt,$authorization) = explode('-',$_POST['rowHidden']);		
		$selectVerificar = "SELECT * FROM signed_doctor WHERE ".
		"Patient_ID = '".$patient_id."' AND CPT ='".$cpt."' AND Discipline= '".$discipline."' AND status = 1;";
		$resultadoVerificar = ejecutar($selectVerificar,$conexion);					
		$row_cnt = mysqli_num_rows($resultadoVerificar);
		if($row_cnt > 0){
			$update="UPDATE signed_doctor SET status = 0 WHERE ".
			"Patient_ID = '".$patient_id."' AND CPT ='".$cpt."' AND Discipline= '".$discipline."' AND status = 1;";
			ejecutar($update,$conexion);
		}      
                 $patient_name_directorio = str_replace(' ','_',$patient_name);
                if (!file_exists('../../../patients')) {        
                    mkdir('../../../patients', 0777, false);  
                }

                if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
                    mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
                }    

                $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 3";
                $resultado_type_document = ejecutar($sql_type_document,$conexion);

                while ($row=mysqli_fetch_array($resultado_type_document)) {
                         $reporte_tipo_documento = $row["type_documents"];
                }

                if (!file_exists('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento)) {        
                    mkdir('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento, 0777, false);  
                }
                               
                $temporal = $_FILES['file-1']['tmp_name'];
		$vowels = array(" ", "-","_");
		$nombre_archivo = str_replace($vowels,"",$_FILES['file-1']['name']);
		$id=fopen($temporal,'r'); 
		if (is_uploaded_file($temporal)) {
                        $ruta = '../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                        $ruta1 = 'patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
			move_uploaded_file($temporal,$ruta);
			chmod($ruta, 0777);
                        //$route_doc = "../../../signed_doctor/$nombre_archivo";                        
		}
                
                $insert="INSERT into signed_doctor (`Patient_ID`, `Patient_name`, `Discipline`, `CPT`, `Authorization`, `Physician_name`, `Physician_NPI`, `Table_name`, `TIMESTAMP`, `Route_file`) 
		values ('".$patient_id."','".$patient_name."','".$discipline."','".$cpt."','".$authorization."','".$_POST['physician_name']."','".$_POST['NPI']."','".$_POST['company']."','".date('y-m-d')."','".$ruta."');";
		ejecutar($insert,$conexion);
                
                //consulto el ultimo id registrado                         
                $sql_doctor  = "SELECT max(id_signed_doctor) as identificador FROM signed_doctor;";
                $resultado_doctor = ejecutar($sql_doctor,$conexion); 
                $j = 0;      
                $id_doctor = '';
                while($datos_doctor = mysqli_fetch_assoc($resultado_doctor)) {            
                    $id_doctor = $datos_doctor['identificador'];
                    $j++;
                }
                        
                //guardo auditoria en tbl_audit_generales
                $fecha=date('Y-m-d H:i:s');
                $audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$id_doctor."','4','".$fecha."')";
                ejecutar($audit_general,$conexion);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// UPDATE DE REPORTE NUEVO CUANDO LLEGA LA PRESCRIPTION PORNER STATUS THE WAITING =0 Y STATUS DE ASK TX  = 1 SI NECESITA OR  EVAL_PATIENT = 1 SI NO NECESITA 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$consulta_if_need_auth_eval = "SELECT * from  patients
join seguros on seguros.insurance=patients.Pri_Ins
join tbl_seguros_table st on seguros.ID=st.id_seguros
                                 and st.id_seguros_type_person=patients.id_seguros_type_person
                                 and st.id_seguros_type_task='4'
                                 and st.discipline='".$discipline."'
where patients.Pat_id='".$patient_id."' ";
$result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
$row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
if($row_cnt_need_eval > 0){

					// ESTA CONSULTA ES PARA LOS SEGUROS QUE SON LAS PRIMERAS VISITAS NO NECESITAN AUTHS
				$consulta_si_es_nuevo = "SELECT * 
											FROM patients p
													JOIN seguros s on p.Pri_Ins=s.insurance
													JOIN tbl_seguro_special_relation sr on s.ID=sr.id_seguro
														AND sr.id_seguro_special='1'
													JOIN tbl_seguro_special_relation_discipline sdr ON sdr.id_seguro_special_relation= sr.id_seguro_special_relation
				      									AND sdr.discipline='".$discipline."'				
												#	LEFT JOIN authorizations on authorizations.Pat_id=p.Pat_id
												#		AND authorizations.status='1'
												#		AND authorizations.Discipline='".$discipline."'
			   										LEFT join careplans on p.Pat_id=careplans.Patient_ID
														AND careplans.Discipline='".$discipline."'
						
											WHERE
												p.active=1
													AND careplans.Patient_ID is null 
														#AND authorizations.id_authorizations is not null
															AND p.Pat_id='".$patient_id."'  ";
                                        $resultado_nuevo = ejecutar($consulta_si_es_nuevo,$conexion);
                                        $datos_verificar_nuevos = null;
                while ($row=mysqli_fetch_array($resultado_nuevo)) { 
                      $datos_verificar_nuevos = $row['id'];
                      } 

                      // ESTA CONSULTA ES PARA CUANDO LAS AUTH VIENEN JUNTAS
                      $consulta_si_juntas = "SELECT * 
											FROM patients p
													JOIN seguros s on p.Pri_Ins=s.insurance	
													JOIN tbl_seguro_special_relation sr on s.ID=sr.id_seguro
														AND sr.id_seguro_special='2'
													JOIN tbl_seguro_special_relation_discipline sdr ON sdr.id_seguro_special_relation= sr.id_seguro_special_relation
				      									AND sdr.discipline='".$discipline."'	
													LEFT JOIN authorizations on authorizations.Pat_id=p.Pat_id
														AND authorizations.status='1'
														AND authorizations.Discipline='".$discipline."'
											WHERE
												p.active=1
													#AND careplans.Patient_ID is null 
														AND authorizations.id_authorizations is not null
															AND p.Pat_id='".$patient_id."'  ";
                                        $resultado_juntas = ejecutar($consulta_si_juntas,$conexion);
                                        $datos_juntas = null;
                while ($row=mysqli_fetch_array($resultado_juntas)) { 
                      $datos_juntas = $row['id'];
                      } 

    
         if($datos_verificar_nuevos != null || $datos_juntas != null){       

    $update_new_report="UPDATE patients_copy SET waiting_signature_".$discipline."='0' , ready_treatment_".$discipline."='1' 
     						WHERE Pat_id='".$patient_id."' and waiting_signature_".$discipline."='1'   "; 
    	ejecutar($update_new_report,$conexion);

     } else {  

      $update_new_report="UPDATE patients_copy SET waiting_signature_".$discipline."='0' , tx_auth_".$discipline."='1' , waiting_tx_auth_".$discipline."='0'
     								WHERE Pat_id='".$patient_id."' and waiting_signature_".$discipline."='1'   "; 
    	ejecutar($update_new_report,$conexion);
			}

}else{
    $update_new_report="UPDATE patients_copy SET waiting_signature_".$discipline."='0' , ready_treatment_".$discipline."='1'
     WHERE Pat_id='".$patient_id."' and waiting_signature_".$discipline."='1'   "; 
    ejecutar($update_new_report,$conexion);
}


		///  UPDATE PARA LAS NOTAS POR TITULOS A STATUS = 0
		 $update_notes = " UPDATE tbl_notes SET t status = 0 where pat_id = '".$patient_id."'  AND discipline= '".$discipline."' 
  AND (type_report = 'NEED EVALUTATION' OR type_report = 'NOT SIGNED BY DOCTOR YET' OR type_report = 'WAITING FOR DOCTOR SIGNATURE' OR type_report = 'WAITING FOR AUTH EVAL')";  
			ejecutar($update_notes,$conexion);
                
                $max_id_signed_doctor = "SELECT MAX(id_signed_doctor) AS id_signed_doctor FROM signed_doctor";
                $result_signed_doctor = mysqli_query($conexion, $max_id_signed_doctor);
                $id_tbl_signed_doctor = 0;
                while($row = mysqli_fetch_array($result_signed_doctor,MYSQLI_ASSOC)){
                        $id_tbl_signed_doctor = $row['id_signed_doctor'];
                }
                
                $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
                        . " VALUES (3,1,".$id_tbl_signed_doctor.",'".$patient_id."','".$ruta1."')";
                ejecutar($sql_insert_document,$conexion);
                //die;
                

		echo "<script type=\"text/javascript\">alert(\"Signed Doctor Save\");</script>";
		//header('Refresh: 0');
		$_POST["Pat_id"] = '';
		$_POST['rowHidden'] = '';		
		$_POST['physician_name'] = '';
		$_POST['NPI'] = '';
		$display = 'none';
		$disabled = 'disabled';
		$disabledFile = 'disabled';
		$displayPhysician = 'none';
		$patient_name = '';
		$patient_id = '';
		$company = '';
		echo '<script>window.location="signed_doctor1.php";</script>';
	}
}else{
	if($_POST['reset'] == " Reset "){
		echo '<script>window.location="signed_doctor1.php";</script>';
	}else{
		if($_POST['insert'] == " Update "){
			if(!isset($_FILES['file-1']['name'])){
				echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
				echo '<script>window.location="signed_doctor1.php";</script>';
			}
			else    
			if($_FILES['file-1']['type'] != 'application/pdf'){
				echo "<script type=\"text/javascript\">alert(\"File must be PDF\");</script>";
				echo '<script>window.location="signed_doctor1.php";</script>';
			}else{
				
				$conexion = conectar();
				$patient_id_old = $_POST['Pat_id_old'];
                                $company_old = $_POST['company_old'];
				list($discipline,$cpt,$authorization) = explode('-',$_POST['rowHidden']);
				list($discipline_old,$cpt_old,$authorization_old) = explode('-',$_POST['rowHidden_old']);                                                                	
                                
                                $patient_name_directorio = str_replace(' ','_',$patient_name);
                                if (!file_exists('../../../patients')) {        
                                    mkdir('../../../patients', 0777, false);  
                                }

                                if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
                                    mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
                                }    

                                $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 3";
                                $resultado_type_document = ejecutar($sql_type_document,$conexion);

                                while ($row=mysqli_fetch_array($resultado_type_document)) {
                                         $reporte_tipo_documento = $row["type_documents"];
                                }

                                if (!file_exists('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento)) {        
                                    mkdir('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento, 0777, false);  
                                }

                                $temporal = $_FILES['file-1']['tmp_name'];
                                $vowels = array(" ", "-","_");
                                $nombre_archivo = str_replace($vowels,"",$_FILES['file-1']['name']);
                                $id=fopen($temporal,'r'); 
                                if (is_uploaded_file($temporal)) {
                                        $ruta = '../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                                        $ruta1 = 'patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                                        move_uploaded_file($temporal,$ruta);
                                        chmod($ruta, 0777);
                                        //$route_doc = "../../../signed_doctor/$nombre_archivo";                        
                                }
                                
                                $sql_signed_doctor = "SELECT * FROM signed_doctor WHERE Patient_id ='".$patient_id_old."' AND Discipline ='".$discipline_old."' AND CPT ='".$cpt_old."' AND Authorization = '".$authorization_old."' AND Table_name = '".$company_old."'";
                                $result_signed_doctor = mysqli_query($conexion, $sql_signed_doctor);
                                $id_tbl_signed_doctor = 0;
                                while($row = mysqli_fetch_array($result_signed_doctor,MYSQLI_ASSOC)){
                                        $id_tbl_signed_doctor = $row['id_signed_doctor'];
                                }
                                
                                $sql_delete = "DELETE FROM tbl_documents WHERE id_type_document = 3 AND id_type_person = 1 AND id_person = '".$patient_id_old."' AND id_table_relation = ".$id_tbl_signed_doctor;
                                ejecutar($sql_delete,$conexion);
                                
                                
				$update="UPDATE signed_doctor SET Patient_id ='".$patient_id."', patient_name='".$patient_name."', Discipline ='".$discipline."', CPT ='".$cpt."', Authorization ='".$authorization."', Physician_name ='".$_POST['physician_name']."', Physician_NPI ='".$_POST['NPI']."', Table_name = '".$_POST['company']."', Route_file ='".$ruta."' WHERE id_signed_doctor = ".$id_tbl_signed_doctor;
				ejecutar($update,$conexion);
                                
                                $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
                                        . " VALUES (3,1,".$id_tbl_signed_doctor.",'".$patient_id."','".$ruta1."')";
                                ejecutar($sql_insert_document,$conexion);
                                
				echo "<script type=\"text/javascript\">alert(\"Prescription Update\");</script>";
				$_POST["Pat_id"] = '';
				$_POST['rowHidden'] = '';		
				$_POST['physician_name'] = '';
				$_POST['NPI'] = '';
				$_POST["Pat_id_old"] = '';
                                $_POST["company_old"] = '';
				$_POST['rowHidden_old'] = '';		
				$_POST['physician_name_old'] = '';
				$_POST['NPI_old'] = '';
				$display = 'none';
				$disabled = 'disabled';
				$disabledFile = 'disabled';
				$displayPhysician = 'none';
				$patient_name = '';
				$patient_id = '';
				$company = '';
			}
		}
	}
}
if($_POST['action'] == ''){
	$_POST['action'] = 'insert';
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

    <link href="../../../css/bootstrap.min.css" rel="stylesheet" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <!--<link href="../../../css/resources/shCore.css" rel="stylesheet" type="text/css">--> 
    <!--<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">-->
    <!--<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">-->
    
    
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/fileinput.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js"></script>
    <script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script> 
    <!--<script src="plugins/jquery/jquery.min.js"></script>-->
    <!--<script src="plugins/jquery-ui/jquery-ui.min.js"></script>-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">
    <script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>
 <script>

	function submitPatient(){			
		if(document.getElementById('action').value == 'update'){
			document.getElementById('patientUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
	function submitPhysician(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('physicianUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
	function loadInputHidden(data){
		document.getElementById("rowHidden").value = data;
		document.getElementById("myForm").submit();
	}
	function deleteSignedDoctor(pat_id,company,discipline,cpt,authorization){
		var myAjax = new AJAXConn('result_d'+pat_id, '<img src="../../../imagenes/loader.gif">');
		variable = myAjax.connect("../../controlador/signed_doctor/delete_signed_doctor.php", "GET", "&patient_id="+pat_id+"&company="+company+
		"&discipline="+discipline+"&cpt="+cpt+"&authorization="+authorization);
		alert('SIGNED DOCTOR DELETED');
		window.location.reload();		    
	}
	function showUpdateSignedDoctor(pat_id,patient_name,cpt,authorization,physician_name,physician_npi,discipline,table_name){
		document.getElementById('Pat_id').value = pat_id;
		document.getElementById('Pat_id_old').value = pat_id;
                document.getElementById('company_old').value = table_name;
                document.getElementById('company').value = table_name;
		document.getElementById('rowHidden').value = discipline+'-'+cpt+'-'+authorization;
		document.getElementById('rowHidden_old').value = discipline+'-'+cpt+'-'+authorization;				
		document.getElementById('NPI').value = physician_npi;		
		document.getElementById('NPI_old').value = physician_npi;
		document.getElementById('physician_name_old').value = physician_name;					
		
		document.getElementById('action').value = 'update';
		document.getElementById('patientUpdate').value = 'no';
		document.getElementById('physicianUpdate').value = 'no';
                document.getElementById('companyUpdate').value = 'no';
		document.getElementById('NPI').disabled = false;		
		document.getElementById("myForm").submit();						
	}
	function updatePrescription(pat_id,patient_name,issue_date,end_date,physician_name,discipline,diagnostic,table_name){
		var myAjax = new AJAXConn('result_d'+pat_id, '<img src="../../../imagenes/loader.gif">');
		variable = myAjax.connect("../../controlador/prescription/update_prescription.php", "GET", "&patient_id="+pat_id+"&patient_name="+patient_name+
		"&issue_date="+issue_date+"&end_date="+end_date+"&physician_name="+physician_name+"&discipline="+discipline+
		"&diagnostic="+diagnostic+"&company="+table_name);			
		alert('Modificado');
		window.location.reload();
	}
        function submitCompany(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('companyUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>


    <!-- Page Content -->
    <div class="panel-collapse collapse in" style="padding-top: 30px;padding-bottom: 10px;padding-right: 30px;padding-left: 30px;">

        <!-- Portfolio Item Heading -->
        <div class="row">

				 			  
				</ul>
		      </li>		
		
		<h3>SIGNED DOCTOR</h3>

        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-lg-12">

                        <form id="myForm" enctype="multipart/form-data" action="signed_doctor1.php" method="post" class="form-horizontal">
				<div class="form-group">

                    <div class="col-lg-3">


                    <label for="inputText" class="control-label ">Patient Name:
					<input type="hidden" id="action" name="action" value="<?php echo $_POST['action'];?>"> 
					<input type="hidden" id="patientUpdate" name="patientUpdate" value="<?php echo $_POST['patientUpdate'];?>"></label>	
					<input type="hidden" id="Pat_id_old" name="Pat_id_old" value="<?php echo $_POST['Pat_id_old'];?>"> 
                                        <input type="hidden" id="company_old" name="company_old" value="<?php echo $_POST['company_old'];?>"> 
					<input type="hidden" id="rowHidden_old" name="rowHidden_old" value="<?php echo $_POST['rowHidden_old'];?>">
					<input type="hidden" id="NPI_old" name="NPI_old" value="<?php echo $_POST['NPI_old'];?>">
					<input type="hidden" id="physician_name_old" name="physician_name_old" value="<?php echo $_POST['physician_name_old'];?>">    					

					    <?php if((!isset($_POST['Pat_id']) || $_POST['Pat_id']=='') || ($_POST['action'] == 'update' && $_POST['patientUpdate'] == 'no')){?>
						    <select name='Pat_id' id='Pat_id' onchange='submitPatient();' class="populate placeholder" style="width:100%;" required>
							<option value=''>--- SELECT ---</option>				
						    <?php
							$sql  = "Select  Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name 
								from patients 
								group by Pat_id, Table_name
								order by Patient_name asc";
							$conexion = conectar();
							$resultado = ejecutar($sql,$conexion);
							while ($row=mysqli_fetch_array($resultado)) 
							{	
								if((trim($row["Pat_id"])) == (trim($patient_id)))
									print("<option value='".trim($row["Pat_id"])."' selected>".$row["Patient_name"]." </option>");
								else
									print("<option value='".trim($row["Pat_id"])."'>".$row["Patient_name"]." </option>");
							}
						
						    ?>
							  
						    </select>
					    <?php
					    }else{
					    ?>
						<input name='Pat_id' id='Pat_id' type="hidden" value="<?php echo $_POST['Pat_id'];?> ">
						<p class="form-control-static"><?php echo $patient_name;?>(<?php echo $patient_id?>)</p>
					    <?php
					    }
					    ?>
				    </div>








                    <div class="col-lg-2">
				    <label for="inputText" class="control-label" >Company:<?php print("<input name='companyUpdate' id='companyUpdate' value='".$_POST['companyUpdate']."' type='hidden' readonly>");?></label>
                                        <?php	   
								if ($_POST['Pat_id']!=""){
									
									$conexion = conectar();
									if ($_POST['company']!="" && ($_POST['action'] == 'insert' || $_POST['companyUpdate'] == 'si')) {										
                                                                                $Company_t = $_POST['company'];
                                                                                print("<input type='hidden' name='Table_name_t' value='".$Company_t."'>");
                                                                                print("<input type='hidden' name='company' value='".$Company_t."'>");                                                                                
										echo '<p class="form-control-static">'.$Company_t.'</p>'; 
									} else {
										print("<select name='company'  id='company' onchange='submitCompany();' class='form-control'>
											  <option value=''>--- SELECT ---</option>");
										$sql5  = "Select company_name from companies ";
										
										$resultado5 = ejecutar($sql5,$conexion);

										while ($row=mysqli_fetch_array($resultado5)) 
										{	
											if($_POST['company_old'] == $row["company_name"])
												print("<option value='".$row["company_name"]."' selected>".$row["company_name"]." </option>"); 
											else
												print("<option value='".$row["company_name"]."'>".$row["company_name"]." </option>"); 

										}
										print("</select>");
									}
								}else{
									
									print("<select name='company'  id='company' disabled class='form-control'>
											  <option value='' selected>--- SELECT ---</option>");
										$sql5  = "Select company_name from companies ";
										
										$resultado5 = ejecutar($sql5,$conexion);
										while ($row=mysqli_fetch_array($resultado5)) 
										{	
										print("<option value='".$row["company_name"]."'>".$row["company_name"]." </option>"); 
										}
										print("</select>");
								}	
								?>
                    </div>

                    <div class="col-lg-3">


					    <table class="table" >
						<thead>
						    <tr>
							<th>Discipline<input type="hidden" id="rowHidden" name="rowHidden" value="<?php echo $_POST['rowHidden'];?>" readonly></th>
							<th>CPT</th>
							<th>Authorization</th>
							<th>Select</th>
						    </tr>
						</thead>
						<tbody>
						   	<?php
								$sql5 = querySignedDoctor($patient_id,$company);						  				
								$resultado5 = ejecutar($sql5,$conexion);
								$find = 0;
								$checked = '';
								while ($row=mysqli_fetch_array($resultado5)) 
								{	
									if($_POST['rowHidden'] == ($row['Discipline'].'-'.$row['CPT'].'-'.$row['Auth_#'])){
										$checked = 'checked';
									}else{
										$checked = '';									
									}
									echo '<tr>';
										echo '<td>'.$row['Discipline'].'</td>';
										echo '<td>'.$row['CPT'].'</td>';
										echo '<td>'.$row['Auth_#'].'</td>';
										echo '<td><input type="radio" name="row" id="row" onclick="loadInputHidden(\''.$row['Discipline'].'-'.$row['CPT'].'-'.$row['Auth_#'].'\');" value="'.$row['Discipline'].'-'.$row['CPT'].'-'.$row['Auth_#'].'" '.$checked.'></td>';
									echo '</tr>';
									$find = 1;
								}
								if($find == 0 && isset($_POST['Pat_id']) && $_POST['Pat_id']!=''){
									echo "<script type=\"text/javascript\">alert(\"Error Do not exist data for this patient\");</script>";
									echo '<script>window.location="signed_doctor1.php";</script>';								
								}
							?>
						</tbody>
					    </table>
					   

                </div>
				<div class="col-lg-3">
				    <label for="inputText" class="control-label">Physician Name:<input type="hidden" id="physicianUpdate" name="physicianUpdate" value="<?php echo $_POST['physicianUpdate'];?>"></label>
				   

					    <?php if((!isset($_POST['NPI']) || $_POST['NPI']=='') || ($_POST['action'] == 'update' && $_POST['physicianUpdate'] == 'no')){?>
					    <select name='NPI' id='NPI' onchange='submitPhysician();' class='form-control' style="width:1000px;" <?php echo $disabled;?>>
					    <option value='' selected>--- SELECT ---</option>
						  <?php 
						  $sqlNPI = "Select NPI, Name  from physician order by Name";						  				
						  $resultadoNPI = ejecutar($sqlNPI,$conexion);
						  while ($row=mysqli_fetch_array($resultadoNPI)) 
						  {	
							if($_POST['NPI'] == $row["NPI"])
								print("<option value='".$row["NPI"]."' selected>".$row["Name"]." </option>"); 
							else
								print("<option value='".$row["NPI"]."'>".$row["Name"]." </option>"); 
						  }?>
					    </select>
					    <?php
                                                echo "<input name='physician_name' id='physician_name' type='hidden' value='".$_POST['physician_name_old']."'>";
						}else{
                            ################parte de detecccion de las cosas que estan mal ##################
						  $sqlNPI = "Select NPI, Name  from physician where NPI = '".$_POST['NPI']."' order by Name";
						  $resultadoNPI = ejecutar($sqlNPI,$conexion);
						  while ($row=mysqli_fetch_array($resultadoNPI)) 
						  {	
							echo "<input name='physician_name' id='physician_name' type='hidden' value='".$row['Name']."'>";
							$physician_name = $row['Name'];
						  }
					    ?>
						<input name='NPI' id='NPI' type="hidden" value="<?php echo $_POST['NPI'];?>">
						<p class="form-control-static"><?php echo $physician_name;?>(<?php echo $_POST['NPI']?>)</p>
					    <?php						
						}
					    ?>
				    </div>
                </div>


				<div class="form-group">
                    <div class="col-lg-2">
					<label for="inputText" class="control-label ">File:</label>

						<input name="file-1" id="file-1" type="file" style="height:35px;"  multiple="true" data-preview-file-type="any"  <?php echo $disabledFile;?> required>
					</div>


                    <div class="col-lg-2" >
                        <label for="inputText" class="control-label ">Actions:</label>
                        <br>
                        <?php
                        if($_POST['action'] == 'insert') $actionButton = 'Add';
                        else $actionButton = 'Update';
                        ?>
                        <input style="height:35px;" name='insert' type='submit' value=" <?php echo $actionButton;?> " class="btn btn-success ">
                        <input style="height:35px;" name='reset' type='button' value=" Reset " onclick= "window.location.href = 'signed_doctor1.php';" class="btn btn-warning ">
                    </div>

				</div>


			</form>
            </div>
        </div>
<br>
        <div class="row">
            <div class="col-lg-12">
			<table   id="paginations" class="table table-striped">
									<thead>
            <tr>	
		<th>Patient ID</th>			
                <th>NAME</th>
                <th>Discipline</th> 
		<th>CPT</th>
                <th>Authorization</th>
		<th>Physician_name</th>
                <th>COMPANY</th> 
		<th>Status</th>
		<th>File Route</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
									</thead>
					
					
					<tbody>
					<?php
					$conexion = conectar();
					
					
					$query01 = " select `Patient_ID`, `Patient_name`, `Discipline`, `CPT`, `Authorization`, `Physician_name`, `Physician_NPI`, `Table_name`, `TIMESTAMP`
					,Route_file as File

,CASE WHEN Route_file IS NOT NULL  THEN 'FILE_LINK'  ELSE 'NONE' END AS Route_file

					, `status` from signed_doctor where status = 1 order by TIMESTAMP desc";
					
					$result01 = mysqli_query($conexion, $query01);
					
					while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
						if($row['status'] == 0){
							$status = 'Inactivo';
						}else{
							$status = 'Activo';
						}
						echo '<tr>	 						
							<td>'.$row['Patient_ID'].'</td>
							<td>'.$row['Patient_name'].'</td>
							<td>'.$row['Discipline'].'</td>
							<td>'.$row['CPT'].'</td>
							<td>'.$row['Authorization'].'</td>
							<td>'.$row['Physician_name'].'</td>
							<td>'.$row['Table_name'].'</td>
							<td>'.$status.'</td>
							<td><a onclick="window.open(\''.$row['File'].'\',\'\',\'width=900px,height=700px,noresize\');">'.$row['Route_file'].'</a></td>
							<td><div id="result_u'.$row['Patient_ID'].'"><button style="height:35px;" type="button" class="btn btn-info " onclick="showUpdateSignedDoctor(\''.$row['Patient_ID'].'\',\''.$row['Patient_name'].'\',\''.$row['CPT'].'\',\''.$row['Authorization'].'\',\''.$row['Physician_name'].'\',\''.$row['Physician_NPI'].'\',\''.$row['Discipline'].'\',\''.$row['Table_name'].'\');">Update</button></div></td>
							<td><div id="result_d'.$row['Patient_ID'].'"><button style="height:35px;" type="button" class="btn btn-danger " onclick="deleteSignedDoctor(\''.$row['Patient_ID'].'\',\''.$row['Table_name'].'\',\''.$row['Discipline'].'\',\''.$row['CPT'].'\',\''.$row['Authorization'].'\');">Delete</button></div></td>										
						</tr>';
					}
					
					 ?>	
					
					<tbody/>

					</table>                
            </div>
    </div>


        <!-- /.row -->

        <!-- Related Projects Row -->

        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; THERAPY  AID 2016</p>
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

  //$('#NPI').select2(); 
}
// Run timepicker

$(document).ready(function() {


    $('#paginations').dataTable( {


        order: [[ 1, "asc" ]],

        dom: 'Bfrtip',
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        buttons: [
            'pageLength',
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "pageLength": 25

    } );
  // Create Wysiwig editor for textare
  //TinyMCEStart('#wysiwig_simple', null);
  //TinyMCEStart('#wysiwig_full', 'extreme');
  // Add slider for change test input length
  //FormLayoutExampleInputLength($( ".slider-style" ));
  // Initialize datepicker
  //$('#input_date_licence').datepicker({setDate: new Date()});
  //$('#input_date_finger').datepicker({setDate: new Date()});
  //$('#dob').datepicker({setDate: new Date()});
  //$('#hireDate').datepicker({setDate: new Date()});
  //$('#terminationDate').datepicker({setDate: new Date()});
  // Load Timepicker plugin
  //LoadTimePickerScript(DemoTimePicker);
  // Add tooltip to form-controls
  $('.form-control').tooltip();
  LoadSelect2ScriptExt(DemoSelect2);
  // Load example of form validation
  //LoadBootstrapValidatorScript(DemoFormValidator);
  // Add drag-n-drop feature to boxes
  //WinMove();
  //ShowDivEdit();
  //enableField();
});
</script>
</html>
