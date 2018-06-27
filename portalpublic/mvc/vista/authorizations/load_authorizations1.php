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
	$patient_id = $_POST["Pat_id"];
		
	$display = 'block';
	     $sql1  = "Select Distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients where Pat_id = '".$patient_id."' order by Patient_name";
	
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
if($_POST['discipline'] == ''){
	$disabledCPT = 'disabled';
}else{
	$disabledCPT = '';
}
if($_POST['cpt'] == ''){
	$disabledInsurance = 'disabled';
}else{
	$disabledInsurance = '';
}

if($_POST['NPI']==''){
	$displayPhysician = 'none';
}else{
	$displayPhysician = 'block';
}
//echo '<pre>';
//print_r($_POST);die();
if($_POST['received'] == 'Fax'){
	$disabledFile = '';
}else{
	$disabledFile = 'disabled';
}
if($_POST['insert'] == " Add "){
	
	$flag = 0;
	if($_POST['received'] == 'Fax'){
                        if(!isset($_FILES['file-1']['name'])){
                                echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
                                echo '<script>window.location="load_authorizations.php";</script>';
                                $flag = 1;
                        }else
                        if($_FILES['file-1']['type'] != 'application/pdf'){
                                echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
                                echo '<script>window.location="load_authorizations.php";</script>';
                                $flag = 1;
                        }else{
                        
                        $patient_name_directorio = str_replace(' ','_',$patient_name);
                        
                        if (!file_exists('../../../patients')) {        
                            mkdir('../../../patients', 0777, false);  
                        }

                        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
                            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
                        }    

                        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 2";
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
		}
	}
	if($flag == 0){
		$conexion = conectar();
		list($discipline,$cpt,$authorization) = explode('-',$_POST['rowHidden']);
		
		if($nombre_archivo == '')
			$route_file_var = '';
		else
			$route_file_var = 'authorizations/'.$nombre_archivo;
		
	 $sqlConsulta = "SELECT * FROM authorizations WHERE `Auth_#` = '".$_POST['auth_number']."' AND Pat_id = '".$patient_id."' AND Discipline = '".$_POST['discipline']."' ;";
		$result01 = mysqli_query($conexion, $sqlConsulta);					
		$row_cnt = mysqli_num_rows($result01);
	
		if($row_cnt > 0){
			echo "<script type=\"text/javascript\">alert(\"AN AUTHORIZATION ALREADY ASSOCIATE TO THAT PATIENT\");</script>";
		}else{
			//echo '<pre>';
			//print_r($_POST);
			//die();
			$update = "UPDATE authorizations SET status = 0 WHERE Pat_id  ='".$patient_id."' AND Discipline ='".$_POST['discipline']."'";
			ejecutar($update,$conexion);
			

                        $insert="INSERT into authorizations (`Patient_name`,`Pat_id`,`Discipline`,`CPT`,`Insurance_name`,`Auth_#`,`Auth_start`,`Auth_thru`,`Received_by`,`Company`,`route_file`,`status`) 
			values ('".$patient_name."','".$patient_id."','".$_POST['discipline']."','".$_POST['cpt']."','".$_POST['insurance']."','".$_POST['auth_number']."','".$_POST['auth_start']."','".$_POST['auth_thru']."','".$_POST['received']."','".$_POST['company']."','".$ruta."',1);";
			ejecutar($insert,$conexion);
                        
                        //consulto el ultimo id registrado                         
                        $sql_autorizacion  = "SELECT max(id_authorizations) as identificador FROM authorizations;";
                        $resultado_autorizacion = ejecutar($sql_autorizacion,$conexion); 
                        $j = 0;      
                        $id_autorizacion = '';
                        while($datos_autorizacion = mysqli_fetch_assoc($resultado_autorizacion)) {            
                            $id_autorizacion = $datos_autorizacion['identificador'];
                            $j++;
                        }
                        
                        //guardo auditoria en tbl_audit_generales
                        $fecha=date('Y-m-d H:i:s');
                        $audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$id_autorizacion."','3','".$fecha."')";
                        ejecutar($audit_general,$conexion);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	// UPDATE DE REPORTE NUEVO CUANDO LLEGA LA AUTORIZACION PARA TRATAMIENTO SE PONE READY FOR TREATMENTS SOLO SI TIENE EL WAITING FOR AUTH TX COROBORANDO QUE SI NECESITA AUTH TX 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $update_new_report="UPDATE patients_copy set waiting_tx_auth_".$_POST['discipline']."='0' , waiting_old_tx_auth_".$_POST['discipline']."='0'
    , ready_treatment_".$_POST['discipline']."='1' WHERE Pat_id='".$patient_id."' "; 
    ejecutar($update_new_report,$conexion);




		/////////////////////////////////////	///  UPDATE PARA LAS NOTAS POR TITULOS A STATUS = 0
		 $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$patient_id."'  AND discipline= '".$_POST['discipline']."' 
  AND (type_report = 'WAITING FOR AUTHORIZATION TX' OR type_report = 'ASK FOR AUTHORIZATION FOR TX' OR type_report = 'READY FOR TREATMENT')";  
			ejecutar($update_notes,$conexion);
                        
                        $max_id_authorizations = "SELECT MAX(id_authorizations) AS id_authorizations FROM authorizations";
                        $result_authorizations = mysqli_query($conexion, $max_id_authorizations);
                        $id_tbl_authorizations = 0;
                        while($row = mysqli_fetch_array($result_authorizations,MYSQLI_ASSOC)){
                                $id_tbl_authorizations = $row['id_authorizations'];
                        }

                        $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
                                . " VALUES ('2',1,".$id_tbl_authorizations.",'".$patient_id."','".$ruta1."')";
                        ejecutar($sql_insert_document,$conexion);
                        
                        
			echo "<script type=\"text/javascript\">alert(\"Authorization Save\");</script>";					
		}
		$_POST["patientUpdate"] = '';
		$_POST["Pat_id_old"] = '';
		$_POST["rowHidden_old"] = '';
		$_POST["NPI_old"] = '';
		$_POST["physician_name_old"] = '';
		$_POST["Pat_id"] = '';
		$_POST["disciplineUpdate"] = '';
		$_POST["discipline_hidden"] = '';
		$_POST["discipline"] = '';
		$_POST["cptUpdate"] = '';
		$_POST["cpt_hidden"] = '';
		$_POST["cpt"] = '';
		$_POST["insuranceUpdate"] = '';
		$_POST["insurance_hidden"] = '';
		$_POST["insurance"] = '';
		$_POST["auth_startUpdate"] = '';
		$_POST["auth_start"] = '';
		$_POST["auth_thruUpdate"] = '';
		$_POST["auth_thru"] = '';
		$_POST["auth_numberUpdate"] = '';
		$_POST["auth_number"] = '';
		$_POST["receivedUpdate"] = '';
		$_POST["received"] = '';
	
		$display = 'none';
		$disabled = 'disabled';
		$disabledFile = 'disabled';
		$patient_name = '';
		$patient_id = '';
		$company = '';
		echo '<script>window.location="load_authorizations.php";</script>';
	}
	
			
}else{
	if($_POST['reset'] == " Reset "){
		echo '<script>window.location="load_authorizations.php";</script>';
	}else{
		if($_POST['insert'] == " Update "){
			if(!isset($_FILES['file-1']['name'])){
				echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
				echo '<script>window.location="load_authorizations.php";</script>';
			}
			else    
			if($_FILES['file-1']['type'] != 'application/pdf'){
				echo "<script type=\"text/javascript\">alert(\"File must be PDF\");</script>";
				echo '<script>window.location="load_authorizations.php";</script>';
			}else{
				$temporal = $_FILES['file-1']['tmp_name'];
				$nombre_archivo = $_FILES['file-1']['name'];
				$id=fopen($temporal,'r'); 
				if (is_uploaded_file($temporal)) {
					move_uploaded_file($temporal,"authorizations/$nombre_archivo");
					chmod("authorizations/$nombre_archivo", 0777);
                                        $route_doc = 'authorizations/'.$nombre_archivo;
				}
				$conexion = conectar();				
				list($patient_id_old,$company_old) = explode("-",$_POST['Pat_id_old']);
				list($discipline,$cpt,$authorization) = explode('-',$_POST['rowHidden']);
				list($discipline_old,$cpt_old,$authorization_old) = explode('-',$_POST['rowHidden_old']);
                                
                                $sql_authorizations = "SELECT * FROM authorizations WHERE Patient_id ='".$patient_id_old."' AND Discipline ='".$discipline_old."' AND CPT ='".$cpt_old."' AND Authorization = '".$authorization_old."' AND Table_name = '".$company_old."'";
                                $result_authorizations = mysqli_query($conexion, $sql_authorizations);
                                $id_tbl_authorizations = 0;
                                while($row = mysqli_fetch_array($result_authorizations,MYSQLI_ASSOC)){
                                        $id_tbl_authorizations = $row['id_prescription'];
                                }
                                
				$update="UPDATE authorizations SET Patient_id ='".$patient_id."', patient_name='".$patient_name."', Discipline ='".$discipline."', CPT ='".$cpt."', Authorization ='".$authorization."', Physician_name ='".$_POST['physician_name']."', Physician_NPI ='".$_POST['NPI']."', Table_name = '".$company."', Route_file ='authorizations/".$nombre_archivo."' WHERE id_authorizations = ".$id_tbl_authorizations;
				ejecutar($update,$conexion);	
                                
                                $sql_delete = "DELETE FROM tbl_documents WHERE type_document = 'authorizations' AND type_person = 'Patients' AND id_person = '".$patient_id_old."' AND id_table_relation = ".$id_tbl_authorizations;
                                ejecutar($sql_delete,$conexion);

                                $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
                                        . " VALUES (2,1,".$id_tbl_authorizations.",'".$patient_id."',$route_doc)";
                                ejecutar($sql_insert_document,$conexion);
                                
				echo "<script type=\"text/javascript\">alert(\"Prescription Update\");</script>";
				$_POST["Pat_id"] = '';
				$_POST['rowHidden'] = '';		
				$_POST['physician_name'] = '';
				$_POST['NPI'] = '';
				$_POST["Pat_id_old"] = '';
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
    
    <title>.: KIDWORKS THERAPY :.</title>
       <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>
<script src="../../../js/funciones.js"></script>

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
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
 
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
<script src="../../../js/devoops_ext.js"></script>


    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css"> 



    <script>

	function submitPatient(){			
		if(document.getElementById('action').value == 'update'){
			document.getElementById('patientUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
        function submitCompany(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('companyUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
	function submitDiscipline(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('disciplineUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
	function submitCpt(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('cptUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
	function submitInsurance(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('insuranceUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
		
	function deleteAuthorizations(pat_name,pat_id,discipline,authorization){
		var respuesta=  confirm("POSITIVE YOU WANT TO DELETE  "+pat_name+" WITH THE AUTHORIZATION  "+authorization+"?");
		if(respuesta){
		    	var myAjax = new AJAXConn('result_d'+pat_id, '<img src="../../../imagenes/loader.gif">');
			variable = myAjax.connect("../../controlador/authorizations/delete_authorizations.php", "GET", "&patient_id="+pat_id+
			"&discipline="+discipline+"&authorization="+authorization);
			alert('AUTHORIZATIONS DELETED');
			window.location.reload();
		}				    
	}	
	function submitReceived(){
		if(document.getElementById('action').value == 'update'){
			document.getElementById('receivedUpdate').value = 'si'
		}
		document.getElementById("myForm").submit();
	}
    </script>

<script type="text/javascript" language="javascript" class="init">

        $(document).ready(function() {
            $('#table_autorizations').DataTable();
        } );
    </script>


 <script type="text/javascript" language="javascript" class="init">  

         
   function DemoSelect2(){
  $('#Pat_id').select2(); 
}
// Run timepicker

$(document).ready(function() {
 
  $('.form-control').tooltip();
  LoadSelect2ScriptExt(DemoSelect2);
  
});



   

    </script> 




</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
            <br>
             <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
		
		<h3>TX AUTHORIZATIONS</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8">
                        <form id="myForm" enctype="multipart/form-data" action="load_authorizations.php" method="post" class="form-horizontal">
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">Patient Name:
					<input type="hidden" id="action" name="action" value="<?php echo $_POST['action'];?>"> 
					<input type="hidden" id="patientUpdate" name="patientUpdate" value="<?php echo $_POST['patientUpdate'];?>"></label>	
					<input type="hidden" id="Pat_id_old" name="Pat_id_old" value="<?php echo $_POST['Pat_id_old'];?>"> 
					<input type="hidden" id="rowHidden_old" name="rowHidden_old" value="<?php echo $_POST['rowHidden_old'];?>">
					<input type="hidden" id="NPI_old" name="NPI_old" value="<?php echo $_POST['NPI_old'];?>">
					<input type="hidden" id="physician_name_old" name="physician_name_old" value="<?php echo $_POST['physician_name_old'];?>">    					
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['Pat_id']) || $_POST['Pat_id']=='') || ($_POST['action'] == 'update' && $_POST['patientUpdate'] == 'no')){?>
						    <select name='Pat_id' id='Pat_id' onchange='submitPatient();' class='form-control' required>
							<option value=''>--- SELECT ---</option>				
						    <?php
							$sql  = "Select Distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name ";
							$conexion = conectar();
							$resultado = ejecutar($sql,$conexion);
							while ($row=mysqli_fetch_array($resultado)) 
							{	
								if((trim($row["Pat_id"])."-".$row["Table_name"]) == (trim($patient_id)."-".$company))
									print("<option value='".trim($row["Pat_id"])."' selected>".$row["Patient_name"] .$row["Table_name"]    ." </option>");
								else
									print("<option value='".trim($row["Pat_id"])."'>".$row["Patient_name"]." </option>");
							}
						
						    ?>
							  
						    </select>
					    <?php
					    }else{
					    ?>
						<input name='Pat_id' id='Pat_id' type="hidden" value="<?php echo $_POST['Pat_id'];?>">
						<p class="form-control-static"><?php echo $patient_name;?></p>
					    <?php
					    }
					    ?>
				    </div>
				</div>				
				<div class="form-group" style="display:<?php echo $display;?>">
				    <label for="inputText" class="control-label col-xs-2">Patient ID:</label>	
				    <div class="col-xs-10">
				    	<p class="form-control-static"><?php echo $patient_id?></p>
				    </div>
				</div>                            
				<div class="form-group" style="display:<?php echo $display;?>">
				    <label for="inputText" class="control-label col-xs-2">Company:<?php print("<input name='companyUpdate' id='companyUpdate' value='".$_POST['companyUpdate']."' type='hidden' readonly>");?></label>	
				    <div class="col-xs-10">
                                        <?php	   
								if ($_POST['Pat_id']!=""){
									
									$conexion = conectar();
									if ($_POST['company']!="" && ($_POST['action'] == 'insert' || $_POST['companyUpdate'] == 'si')) {										
                                                                                $Company_t = $_POST['company'];
                                                                                print("<input type='hidden' name='Table_name_t' value=".$Company_t.">");
                                                                                print("<input type='hidden' name='company' value=".$Company_t.">");                                                                                
										echo '<p class="form-control-static">'.$Company_t.'</p>'; 
									} else {
										print("<select name='company'  id='company' onchange='submitCompany();' class='form-control'>
											  <option value=''>--- SELECT ---</option>");
										$sql5  = "Select company_name from companies ";
										
										$resultado5 = ejecutar($sql5,$conexion);

										while ($row=mysqli_fetch_array($resultado5)) 
										{	
											if($_POST['company'] == $row["company_name"])
												print("<option value='".$row["company_name"]."' selected>".$row["company_name"]." </option>"); 
											else
												print("<option value='".$row["company_name"]."'>".$row["company_name"]." </option>"); 

										}
										print("</select>");
									}
								}else{
									
									print("<select name='company'  id='company' onchange='updateCompany();' disabled class='form-control'>
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
				</div>	
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">Discipline:<input type="hidden" id="disciplineUpdate" name="disciplineUpdate" value="<?php echo $_POST['disciplineUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['discipline']) || $_POST['discipline']=='') || ($_POST['action'] == 'update' && $_POST['disciplineUpdate'] == 'no')){?>
					    <select name='discipline' id='discipline' onchange='submitDiscipline();' class='form-control' <?php echo $disabled;?>>
					    <option value='' selected>--- SELECT ---</option>
						  <?php 
						  $sqlDiscipline = "Select name as discipline  from discipline order by name";						  				
						  $resultadoDiscipline = ejecutar($sqlDiscipline,$conexion);
						  while ($row=mysqli_fetch_array($resultadoDiscipline)) 
						  {	
							if($_POST['discipline'] == $row["discipline"])
								print("<option value='".$row["discipline"]."' selected>".$row["discipline"]." </option>"); 
							else
								print("<option value='".$row["discipline"]."'>".$row["discipline"]." </option>"); 
						  }?>
					    </select>
					    <?php
						}else{
						  $sqlDiscipline = "Select name as discipline from discipline where name = '".$_POST['discipline']."' order by name";					  
    						  $resultadoDiscipline = ejecutar($sqlDiscipline,$conexion);
						  while ($row=mysqli_fetch_array($resultadoDiscipline)) 
						  {	
							echo "<input name='discipline_hidden' id='discipline_hidden' type='hidden' value='".$row['discipline']."'>";
							$discipline = $row['discipline'];
						  }
					    ?>
						<input name='discipline' id='discipline' type="hidden" value="<?php echo $_POST['discipline'];?>">
						<p class="form-control-static"><?php echo $discipline;?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">cpt:<input type="hidden" id="cptUpdate" name="cptUpdate" value="<?php echo $_POST['cptUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['cpt']) || $_POST['cpt']=='') || ($_POST['action'] == 'update' && $_POST['cptUpdate'] == 'no')){ ?>
					    <select name='cpt' id='cpt' onchange='submitCpt();' class='form-control' <?php echo $disabledCPT;?>>
					    <option value='' selected>--- SELECT ---</option>
						  <?php 
						  $sqlcpt = "Select Discipline,type,cpt  from cpt where type='TX' OR type='EVAL'  order by cpt";						  				
						  $resultadocpt = ejecutar($sqlcpt,$conexion);
						  while ($row=mysqli_fetch_array($resultadocpt)) 
						  {	
						  	if($_POST['discipline'] == $row['Discipline']){
                                                                if($_POST['cpt'] == $row["cpt"])
                                                                        print("<option value='".$row["cpt"]."' selected>".$row["type"]."-".$row["cpt"]." </option>"); 
                                                                else
                                                                        print("<option value='".$row["cpt"]."'>".$row["type"]."-".$row["cpt"]." </option>"); 
						  	}							
						  }
                                                  ?>
					    </select>					  
					    <?php
						}else{
						  $sqlcpt = "Select type,cpt from cpt where cpt = '".$_POST['cpt']."' order by cpt";
						  $resultadocpt = ejecutar($sqlcpt,$conexion);
						  while ($row=mysqli_fetch_array($resultadocpt)) 
						  {	
							echo "<input name='cpt_hidden' id='cpt_hidden' type='hidden' value='".$row['cpt']."'>";
							$cpt = $row['type'].'-'.$row['cpt'];
						  }
					    ?>
						<input name='cpt' id='cpt' type="hidden" value="<?php echo $_POST['cpt'];?>">
						<p class="form-control-static"><?php echo $cpt;?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">Insurance:<input type="hidden" id="insuranceUpdate" name="insuranceUpdate" value="<?php echo $_POST['insuranceUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['insurance']) || $_POST['insurance']=='') || ($_POST['action'] == 'update' && $_POST['insuranceUpdate'] == 'no')){?>
					    <select name='insurance' id='insurance' onchange='submitInsurance();' class='form-control' <?php echo $disabledInsurance;?>>
					    <option value='' selected>--- SELECT ---</option>
						  <?php 
						  $sqlInsurance = "Select id, insurance  from seguros order by insurance";						  				
						  $resultadoInsurance = ejecutar($sqlInsurance,$conexion);
						  while ($row=mysqli_fetch_array($resultadoInsurance)) 
						  {	
							if($_POST['insurance'] == $row["insurance"])
								print("<option value='".$row["insurance"]."' selected>".$row["insurance"]." </option>"); 
							else
								print("<option value='".$row["insurance"]."'>".$row["insurance"]." </option>"); 
						  }?>
					    </select>
					    <?php
						}else{
						  $sqlInsurance = "Select id, insurance  from seguros where insurance = '".$_POST['insurance']."' order by insurance";						  				
						  $resultadoInsurance = ejecutar($sqlInsurance,$conexion);
						  while ($row=mysqli_fetch_array($resultadoInsurance)) 
						  {	
							echo "<input name='insurance_hidden' id='insurance_hidden' type='hidden' value='".$row['insurance']."'>";
							$insurance = $row['insurance'];
						  }
					    ?>
						<input name='insurance' id='insurance' type="hidden" value="<?php echo $_POST['insurance'];?>">
						<p class="form-control-static"><?php echo $insurance;?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>
				
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">auth_number:<input type="hidden" id="auth_numberUpdate" name="auth_numberUpdate" value="<?php echo $_POST['auth_numberUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['auth_number']) || $_POST['auth_number']=='') || ($_POST['action'] == 'update' && $_POST['auth_numberUpdate'] == 'no')){?>
					    <input type="text" name='auth_number' id='auth_number' class='form-control' <?php echo $disabled;?>>					  
					    <?php
						}else{						 
					    ?>
						<input name='auth_number' id='auth_number' type="hidden" value="<?php echo $_POST['auth_number'];?>">
						<p class="form-control-static"><?php echo $_POST['auth_number'];?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>	
				
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">auth_start:<input type="hidden" id="auth_startUpdate" name="auth_startUpdate" value="<?php echo $_POST['auth_startUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['auth_start']) || $_POST['auth_start']=='') || ($_POST['action'] == 'update' && $_POST['auth_startUpdate'] == 'no')){?>
					    <input type="date" name='auth_start' id='auth_start' class='form-control' style="height:45px;" <?php echo $disabled;?> required>					  
					    <?php
						}else{						  
					    ?>
						<input name='auth_start' id='auth_start' type="hidden" value="<?php echo $_POST['auth_start'];?>">
						<p class="form-control-static"><?php echo $_POST['auth_start'];?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>
				
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">auth_thru:<input type="hidden" id="auth_thruUpdate" name="auth_thruUpdate" value="<?php echo $_POST['auth_thruUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['auth_thru']) || $_POST['auth_thru']=='') || ($_POST['action'] == 'update' && $_POST['auth_thruUpdate'] == 'no')){?>
					    <input type="date" name='auth_thru' id='auth_thru' class='form-control' style="height:45px;" <?php echo $disabled;?> required>					  					    
					    <?php
						}else{						
					    ?>
						<input name='auth_thru' id='auth_thru' type="hidden" value="<?php echo $_POST['auth_thru'];?>">
						<p class="form-control-static"><?php echo $_POST['auth_thru'];?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>
							        											
				<div class="form-group">
				    <label for="inputText" class="control-label col-xs-2">Received by:<input type="hidden" id="receivedUpdate" name="receivedUpdate" value="<?php echo $_POST['receivedUpdate'];?>"></label>	
				   
				    <div class="col-xs-10">
					    <?php if((!isset($_POST['received']) || $_POST['received']=='') || ($_POST['action'] == 'update' && $_POST['receivedUpdate'] == 'no')){?>
					    <select name='received' id='received' onchange='submitReceived();' class='form-control' <?php echo $disabled;?>>
						    <option value='' selected>--- SELECT ---</option>
						    <option value='Fax' >Fax</option>
						    <option value='Email' >Email</option>
					    	    <option value='Phone' >Phone</option>
				    	    </select>
					    <?php
						}else{						  
					    ?>
						<input name='received' id='received' type="hidden" value="<?php echo $_POST['received'];?>">
						<p class="form-control-static"><?php echo $_POST['received'];?></p>
					    <?php						
						}
					    ?>
				    </div>
				</div>				
				<div class="form-group">
					<label for="inputText" class="control-label col-xs-2">File:</label>	
					<div class="col-xs-10">
						<input name="file-1" id="file-1" type="file" class="file" multiple="true" data-preview-file-type="any"  <?php echo $disabledFile;?> required>					
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-offset-2 col-xs-10">
						<?php
							if($_POST['action'] == 'insert') $actionButton = 'Add';
							else $actionButton = 'Update';
						?>
				    		<input name='insert' type='submit' value=" <?php echo $actionButton;?> " class="btn btn-success btn-lg">
						<input name='reset' type='button' value=" Reset " onclick= "window.location.href = 'load_authorizations.php';" class="btn btn-warning btn-lg">
					</div>
				</div>
			</form>
            </div>

            <div class="col-lg-12">
			<table   class="table" id="table_autorizations">
									<thead>
            <tr>	
		<th>Patient ID</th>			
                <th>NAME</th>
               
		<th>CPT</th>
                <th>Auth number</th>
		<th>Insurance</th>
                <th>Company</th>
		<th>Received By</th>  
	
	
<th>Create</th>		<th>Actions</th>
		            
            </tr>
									</thead>
					
					
					<tbody>
					<?php
					$conexion = conectar();
					
					$query01 = " select * from authorizations order by TIMESTAMP desc
					";
					
					$result01 = mysqli_query($conexion, $query01);
					
					while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
						if($row['status'] == 1){
							$status = 'Activo';
						}else{
							$status = 'Inactivo';
						}
						echo '<tr>	 						
							<td>'.$row['Pat_id'].'</td>
							<td>'.$row['Patient_name'].'</td>
					
							<td>'.$row['CPT'].'</td>
						
<td><a onclick="window.open(\''.$row['route_file'].'\',\'\',\'width=900px,height=700px,noresize\');">'.$row['Auth_#'].'</a>('.$status.')</td>



							<td>'.$row['Insurance_name'].'</td>
							<td>'.$row['Company'].'</td>
							<td>'.$row['Received_by'].'</td>
							

<td>'.$row['TIMESTAMP'].'</td>
							<td><div id="result_d'.$row['Pat_id'].'"><button type="button" class="btn btn-danger btn-lg" onclick="deleteAuthorizations(\''.$row['Patient_name'].'\',\''.$row['Pat_id'].'\',\''.$row['Discipline'].'\',\''.$row['Auth_#'].'\');">Delete</button></div></td>										
						</tr>';
					}
					
					 ?>	
					
					<tbody/>

					</table>                
            </div>

        </div>
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



</html>
