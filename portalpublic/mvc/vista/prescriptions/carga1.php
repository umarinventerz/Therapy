<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}
if (isset($_POST["Pat_id"])) 	{	$Pat_id = $_POST["Pat_id"];	$Pat_id_t = $Pat_id;      }

if (isset($_POST["Pat_id_t"])) 	{	$Pat_id_t   	= $_POST["Pat_id_t"];	 }

if (isset($_POST["company"])) 	{	$Company_t     	= $_POST["company"];	  $Table_name_t   	= $_POST["Table_name"];		 }

if (isset($_POST["Table_name_t"])) 	{	$Table_name_t   	= $_POST["Table_name_t"]; $Table_name = $_POST["Table_name_t"]; }

if (isset($_POST["Discipline"])) 	{	$Discipline     	= $_POST["Discipline"];	 $Discipline_t   	= $_POST["Discipline"];}

if (isset($_POST["Discipline_t"])) 	{	$Discipline_t   	= $_POST["Discipline_t"];	 }

if (isset($_POST["Diagnostic"])) 	{	$Diagnostic     	= $_POST["Diagnostic"]; $Diagnostic_t   	= $_POST["Diagnostic"];		 }

if (isset($_POST["Diagnostic_t"])) 	{	$Diagnostic_t   	= $_POST["Diagnostic_t"];	 }

if (isset($_POST["Patient_name"])) 	{	$Patient_name     	= $_POST["Patient_name"];	}

if (isset($_POST["Issue_date"])) 	{	$Issue_date     	= $_POST["Issue_date"];	
										$Issue_date_t   	= $_POST["Issue_date"];		 }
if (isset($_POST["Issue_date_t"])) 	{	$Issue_date_t   	= $_POST["Issue_date_t"];	 
						$Issue_date             = $_POST["Issue_date_t"];}

if (isset($_POST["End_date"])) 	{	$End_date     	= $_POST["End_date"];	
					$End_date_t   	= $_POST["End_date"];		 }
if (isset($_POST["End_date_t"])) 	{	$End_date_t   	= $_POST["End_date_t"];	 
						$End_date = $_POST["End_date_t"]; }


if (isset($_POST["NPI"])) 	{	$NPI     	= $_POST["NPI"];	
												$NPI_t   	= $_POST["NPI"];		 }
if (isset($_POST["NPI_t"])) 	{	$NPI_t   	= $_POST["NPI_t"];	 }

if (isset($_POST["Name"])) 	{	$Name     	= str_replace('|', ' ',$_POST["Name"]);	}


/* if($Pat_id_t!=""){
	
	$conexion = conectar();
$insert="SELECT * FROM prescription where Patient_id='".$Pat_id_t."' and Issue_date=current_date()";
$resultado=ejecutar($insert,$conexion);
if(!$resultado->num_rows){
	echo "<script type=\"text/javascript\">alert(\"This Patient ya se registro today\");</script>";
	$Pat_id="";
$Pat_id_t="";
$Patient_name="";
}

	
} */


if ($_POST["reset"] == "Reset")   {	

	
$Pat_id="";
$Pat_id_t="";
$Patient_id="";
$Patient_id_t="";
$Patient_name="";
$Discipline="";
$Discipline_t="";
$Diagnostic='';
$Diagnostic_t="";
$Issue_date="";
$Issue_date_t="";
$End_date="";
$End_date_t="";
$NPI="";
$NPI_t="";
$Name="";
$Table_name="";
$Table_name_t="";
}

$listo=0;
if($Pat_id_t!="" && $Discipline_t!="" && $Diagnostic_t!="" && $Issue_date_t!="" && $End_date_t!="" && $NPI){$listo=1;}

if ($_POST["insert"] == " Add ")   {	

if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="carga.php";</script>';
        $flag = 1;
}else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="carga.php";</script>';
        $flag = 1;
}else{
        
if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="carga.php";</script>';
        $flag = 1;
}else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="carga.php";</script>';
        $flag = 1;
}else{
        $conexion = conectar();
        $patient_name_directorio = str_replace(',','',str_replace(' ','_',$Patient_name));
                        
        if (!file_exists('../../../patients')) {        
            mkdir('../../../patients', 0777, false);  
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
        }    

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 1";
        $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                 $reporte_tipo_documento = $row["type_documents"];
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento)) {        
            mkdir('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento, 0777, false);  
        }

        $temporal = $_FILES['file-1']['tmp_name'][0];
        $vowels = array(" ", "-","_");
        $nombre_archivo = str_replace($vowels,"",$_FILES['file-1']['name'][0]);
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



$selectVerificar = "SELECT * FROM prescription WHERE ".
"Patient_id = '".$Pat_id_t."'  AND Discipline= '".$Discipline_t."' ;";
$resultadoVerificar = ejecutar($selectVerificar,$conexion);					
$row_cnt = mysqli_num_rows($resultadoVerificar);
if($row_cnt > 0){
	$update="UPDATE prescription SET status = 0 WHERE ".
	"Patient_id='".$Pat_id_t."'  AND Discipline= '".$Discipline_t."' ;";; 
	ejecutar($update,$conexion);
}

$insert="INSERT into prescription (Patient_id, patient_name, Discipline, Diagnostic , Issue_date , End_date, Physician_name , Physician_NPI , Table_name, status) 
values ('".$Pat_id_t."','".$Patient_name."','".$Discipline_t."','".$Diagnostic_t."','".$Issue_date_t."','".$End_date_t."','".$Name."','".$NPI_t."', '".$Table_name_t."' , '1');";
ejecutar($insert,$conexion);

//consulto el ultimo id registrado                         
$sql_prescription  = "SELECT max(id_prescription) as identificador FROM prescription;";
$resultado_prescription = ejecutar($sql_prescription,$conexion); 
$j = 0;      
$id_prescription = '';
while($datos_prescription = mysqli_fetch_assoc($resultado_prescription)) {            
    $id_prescription = $datos_prescription['identificador'];
    $j++;
}
//guardo auditoria en tbl_audit_generales
$fecha=date('Y-m-d H:i:s');
$audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$id_prescription."','5','".$fecha."')";
ejecutar($audit_general,$conexion);


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// UPDATE DE REPORTE NUEVO CUANDO LLEGA LA PRESCRIPTION PORNER STATUS THE WAITING =0 Y STATUS DE EVAL_AUTH = 1 SI NECESITA OR  EVAL_PATIENT = 1 SI NO NECESITA 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$consulta_if_need_auth_eval = "SELECT * from  patients
join seguros on seguros.insurance=patients.Pri_Ins
join tbl_seguros_table st on seguros.ID=st.id_seguros
                                 and st.id_seguros_type_person=patients.id_seguros_type_person
                                 and st.id_seguros_type_task='2'
                                 and st.discipline='".$Discipline_t."'
where patients.Pat_id='".$Pat_id_t."' ";
$result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
$row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
if($row_cnt_need_eval > 0){

                    ////////////////// consulta seguros que no necesites la primera vez que vienen

                    // ESTA CONSULTA ES PARA LOS SEGUROS QUE SON LAS PRIMERAS VISITAS NO NECESITAN AUTHS
                $consulta_si_es_nuevo = "SELECT * 
                                            FROM patients p
                                                    JOIN seguros s on p.Pri_Ins=s.insurance
                                                    JOIN tbl_seguro_special_relation sr on s.ID=sr.id_seguro
                                                        AND sr.id_seguro_special='1'
                                                    JOIN tbl_seguro_special_relation_discipline sdr ON sdr.id_seguro_special_relation= sr.id_seguro_special_relation
                                                        AND sdr.discipline='".$Discipline_t."'                
                                                #   LEFT JOIN authorizations on authorizations.Pat_id=p.Pat_id
                                                #       AND authorizations.status='1'
                                                #       AND authorizations.Discipline='".$Discipline_t."'
                                                    LEFT join careplans on p.Pat_id=careplans.Patient_ID
                                                        AND careplans.Discipline='".$Discipline_t."'
                        
                                            WHERE
                                                p.active=1
                                                    AND careplans.Patient_ID is null 
                                                        #AND authorizations.id_authorizations is not null
                                                            AND p.Pat_id='".$Pat_id_t."'  ";
                                        $resultado_nuevo = ejecutar($consulta_si_es_nuevo,$conexion);
                                        $datos_verificar_nuevos = null;
                while ($row=mysqli_fetch_array($resultado_nuevo)) { 
                      $datos_verificar_nuevos = $row['id'];
                      } 

         if($datos_verificar_nuevos != null) {

    $update_new_report="UPDATE patients_copy set waiting_prescription_".$Discipline_t."='0' , eval_patient_".$Discipline_t."='1' WHERE Pat_id='".$Pat_id_t."'   "; 
    ejecutar($update_new_report,$conexion);

    }else{
        $update_new_report="UPDATE patients_copy set waiting_prescription_".$Discipline_t."='0' , eval_auth_".$Discipline_t."='1' WHERE Pat_id='".$Pat_id_t."'   "; 
    ejecutar($update_new_report,$conexion);

    }



}else{
    $update_new_report="UPDATE patients_copy set waiting_prescription_".$Discipline_t."='0' , eval_patient_".$Discipline_t."='1' WHERE Pat_id='".$Pat_id_t."'   "; 
    ejecutar($update_new_report,$conexion);
}





///  UPDATE PARA LAS NOTAS POR TITULOS A STATUS = 0
 $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$Pat_id_t."'  AND discipline= '".$Discipline_t."' 
                                                        AND (type_report = 'POC EXPIRED NO PRESCRIPTION' OR type_report = 'WAITING FOR PRESCRIPTION')";  
ejecutar($update_notes,$conexion);

$max_id_prescription = "SELECT MAX(id_prescription) AS id_prescription FROM prescription";
$result_prescription = mysqli_query($conexion, $max_id_prescription);
$id_tbl_prescription = 0;
while($row = mysqli_fetch_array($result_prescription,MYSQLI_ASSOC)){
        $id_tbl_prescription = $row['id_prescription'];
}

$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
        . " VALUES ('1','1',".$id_tbl_prescription.",'".$Pat_id_t."','".$ruta1."')";
ejecutar($sql_insert_document,$conexion);

//die();
echo "<script type=\"text/javascript\">alert(\"Prescription Save\");</script>";
header('Refresh: 0');
$Pat_id="";
$Pat_id_t="";
$Patient_name="";
$Discipline="";
$Discipline_t="";
$Diagnostic='';
$Diagnostic_t="";
$Issue_date="";
$Issue_date_t="";
$End_date="";
$End_date_t="";
$NPI="";
$NPI_t="";
$Name="";
$Name_t="";
$Table_name="";
$Table_name_t="";
}

if ($_POST["insert"] == " Update ")   {
$conexion = conectar();

if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="carga.php";</script>';
        $flag = 1;
}else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="carga.php";</script>';
        $flag = 1;
}else{
        $patient_name_directorio = str_replace(',','',str_replace(' ','_',$Patient_name));
                        
        if (!file_exists('../../../patients')) {        
            mkdir('../../../patients', 0777, false);  
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
        }    

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 1";
        $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                 $reporte_tipo_documento = $row["type_documents"];
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento)) {        
            mkdir('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento, 0777, false);  
        }

        $temporal = $_FILES['file-1']['tmp_name'][0];
        $vowels = array(" ", "-","_");
        $nombre_archivo = str_replace($vowels,"",$_FILES['file-1']['name'][0]);
        $id=fopen($temporal,'r'); 
        if (is_uploaded_file($temporal)) {
                $ruta = '../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                 $ruta1 = 'patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                move_uploaded_file($temporal,$ruta);
                chmod($ruta, 0777);
                //$route_doc = "../../../signed_doctor/$nombre_archivo";                        
        }
}

$sql_id_prescription = "SELECT * FROM prescription WHERE Patient_id ='".$_POST['Pat_id_t_old']."' AND Discipline ='".$_POST['discipline_old']."' AND Diagnostic ='".$_POST['diagnostic_old']."' AND Table_name = '".$_POST['Table_name_t_old']."'"; 
$result_prescription = mysqli_query($conexion, $sql_id_prescription);
$id_tbl_prescription = 0;
while($row = mysqli_fetch_array($result_prescription,MYSQLI_ASSOC)){
        $id_tbl_prescription = $row['id_prescription'];
}

$update="UPDATE prescription SET Patient_id ='".$Pat_id_t."', patient_name='".$Patient_name."', Discipline ='".$Discipline_t."', Diagnostic ='".$Diagnostic_t."', Issue_date ='".$Issue_date_t."', End_date = '".$End_date_t."', Physician_name ='".$Name."', Physician_NPI ='".$NPI_t."', Table_name = '".$Table_name_t."' WHERE id_prescription = ".$id_tbl_prescription;
ejecutar($update,$conexion);

$sql_delete = "DELETE FROM tbl_documents WHERE type_document = 'prescription' AND type_person = 'Patients' AND id_person = '".$_POST['Pat_id_t_old']."' AND id_table_relation = ".$id_tbl_prescription;
ejecutar($sql_delete,$conexion);

$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
        . " VALUES ('1','1',".$id_tbl_prescription.",'".$Pat_id_t."','".$ruta1."')";
ejecutar($sql_insert_document,$conexion);
//die;
echo "<script type=\"text/javascript\">alert(\"Prescription Update\");</script>";
$Pat_id="";
$Pat_id_t="";
$Patient_name="";
$Discipline="";
$Discipline_t="";
$Diagnostic='';
$Diagnostic_t="";
$Issue_date="";
$Issue_date_t="";
$End_date="";
$End_date_t="";
$NPI="";
$NPI_t="";
$Name="";
$Name_t="";
$Table_name="";
$Table_name_t="";
$_POST['Pat_id_t_old'] = "";
$_POST['Table_name_t_old'] = "";
$_POST['discipline_old'] = "";
$_POST['diagnostic_old'] = "";
$_POST['disciplineUpdate'] = "";
$_POST['patientNameUpdate'] = "";
$_POST['diagnosticUpdate'] = "";
$_POST['npiUpdate'] = "";
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


<script type="text/javascript" language="javascript" class="init">

        $(document).ready(function() {
            $('#table_carga').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            } );
        } );
        </script>


<script>
		function deletePrescription(pat_id,discipline,diagnostic,company){
			var myAjax = new AJAXConn('result_d'+pat_id, '<img src="../../../imagenes/loader.gif">');
			variable = myAjax.connect("../../controlador/prescriptions/delete_prescription.php", "GET", "&patient_id="+pat_id+"&company="+company+
			"&discipline="+discipline+"&diagnostic="+diagnostic);
			alert('PRESCRIPTION DELETED');
			window.location.href = 'carga.php';		    
		}
		function showUpdatePrescription(pat_id,patient_name,issue_date,end_date,physician_name,physician_npi,discipline,diagnostic,table_name){
                    //alert(table_name);
			//alert(pat_id+'-'+table_name);
			document.getElementById('Pat_id').value = pat_id;
			document.getElementById('Pat_id_t_old').value = pat_id;
			document.getElementById('Table_name_t_old').value = table_name;
                        document.getElementById('company').disabled = false;
                        document.getElementById('company').value = table_name;                        
			document.getElementById('discipline_old').value = discipline;
			document.getElementById('diagnostic_old').value = diagnostic;
			document.getElementById('Discipline').disabled = false;
			document.getElementById('Discipline').value = discipline;
			document.getElementById('Diagnostic').disabled = false;
			document.getElementById('Diagnostic').value = diagnostic;
			document.getElementById('Issue_date').disabled = false;
			document.getElementById('End_date').disabled = false;
			document.getElementById('Issue_date').value = issue_date;
			document.getElementById('End_date').value = end_date;
			document.getElementById('NPI').disabled = false;
			document.getElementById('NPI').value = physician_npi;
			document.getElementById('action').value = 'update';
			document.getElementById("myForm").submit();						
		}
		function updatePrescription(pat_id,patient_name,issue_date,end_date,physician_name,discipline,diagnostic,table_name){
			var myAjax = new AJAXConn('result_d'+pat_id, '<img src="../../../imagenes/loader.gif">');
                        physician_name = physician_name.replace("|", " "); 
			variable = myAjax.connect("../../controlador/prescriptions/update_prescription.php", "GET", "&patient_id="+pat_id+"&patient_name="+patient_name+
			"&issue_date="+issue_date+"&end_date="+end_date+"&physician_name="+physician_name+"&discipline="+discipline+
			"&diagnostic="+diagnostic+"&company="+table_name);			
			alert('Modificado');
			window.location.href = 'carga.php'
		}
		function updateDiscipline(){
			//alert(document.getElementById('action').value);
			if(document.getElementById('action').value == 'update')
				document.getElementById('disciplineUpdate').value = 'si';

			document.getElementById("myForm").submit();
		}
                function updateCompany(){
			//alert(document.getElementById('action').value);
			if(document.getElementById('action').value == 'update')
				document.getElementById('companyUpdate').value = 'si';

			document.getElementById("myForm").submit();
		}
		function submitPatient(){			
			if(document.getElementById('action').value == 'update')
				document.getElementById('patientNameUpdate').value = 'si';

			document.getElementById("myForm").submit();
		}
		function updateDiagnostic() {

			if(document.getElementById('action').value == 'update')
				document.getElementById('diagnosticUpdate').value = 'si';

		}
		function updateNpi(){
			if(document.getElementById('action').value == 'update')
				document.getElementById('npiUpdate').value = 'si';

			document.getElementById("myForm").submit();
		}
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
 <!-- 
			  <h1 class="page-header">  KIDWORKS  
	 -->		  
			
<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
              

 <!-- 			  <small>Therapy rehab</small>
                </h1>
 -->




				</div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8">
                <div class="pure-u-1-12">
                <p><a href="../diagnosticcodes/diagnosticcode.php">Add New Diagnostic Code</a> </p>
                <form id="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="pure-form pure-form-stacked">

                    <table  class="table table-striped" style="width:780px;" >
					
				
                <!--                       
					   <tr>
                            <th colspan="2" style="text-align:right;"><a href="Prescriptions.php">Prescriptions</a> - <a href="Second.php">Authorizations</a> - <a href="index.php">Cerrar</a></th>
                        </tr>
                    -->    
                      
						 <tr>
						    <th  style="text-align:center;">Patient Name<input type="hidden" value="<?php echo $_POST['action'];?>" id="action" name="action" readonly>
							<input type="hidden" value="<?php echo $_POST['patientNameUpdate'];?>" id="patientNameUpdate" name="patientNameUpdate" readonly></th>
							<?php
								//datos viejos
								print("<input type='hidden' name='Pat_id_t_old' id='Pat_id_t_old' value='".$_POST['Pat_id_t_old']."' readonly>");
								print("<input type='hidden' name='Table_name_t_old' id='Table_name_t_old' value='".$_POST['Table_name_t_old']."' readonly>");
								print("<input type='hidden' name='discipline_old' id='discipline_old' value='".$_POST['discipline_old']."'  readonly>");
								print("<input type='hidden' name='diagnostic_old' id='diagnostic_old' value='".$_POST['diagnostic_old']."'  readonly>");
							?>
                            <td style="text-align:center;">
								<?php	 								 	
									$conexion = conectar();									
									if ($Pat_id_t!="" && ($_POST['action'] == 'insert' || $_POST['patientNameUpdate'] == 'si')){
										print("<input type='hidden' name='Pat_id_t' value='".$Pat_id_t."'>");
//										
										
                                                                                //if($Table_name != ''){
                                                                                    //$where_table_name = " AND  table_name = '".$Table_name."' ";
                                                                                //}else{
                                                                                    //$where_table_name = "";
                                                                                //}
										$sql1  = 	"Select distinct Table_name, Pat_id, Last_name, First_name from patients   
													where Pat_id = '".$Pat_id_t."' order by Last_name";
										$resultado1 = ejecutar($sql1,$conexion);
										
										while ($row=mysqli_fetch_array($resultado1)) 
										{
											$Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]); 
											//$Table_name_t = $row["Table_name"];
											$Pat_id_t = $row["Pat_id"];
										}
										echo $Patient_name; 
									
									    
										print("<input type='hidden' name='Patient_name' value='".$Patient_name."'>");
									 
									} else 
									{
										$sql  = "Select Distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients
													  order by Patient_name";
										//echo $sql;
										
										print("<select name='Pat_id' id='Pat_id' onchange='submitPatient();' class='populate placeholder'>
										
											  <option value=''>--- SELECT ---</option>");
										
										
										
										
										$resultado = ejecutar($sql,$conexion);
										while ($row=mysqli_fetch_array($resultado)) 
										{	
											if((trim($row["Pat_id"])) == (trim($Pat_id_t)))
												print("<option value='".trim($row["Pat_id"])."' selected>".$row["Patient_name"]." </option>");
											else
												print("<option value='".trim($row["Pat_id"])."'>".$row["Patient_name"]." </option>");
										}
										print("</select>");
									}
								?>
							</td>
						</tr>
			<?php if($Pat_id_t!=''){?>			
			<tr>
                            <th style="text-align:center;">Patient ID</th>
                            <td style="text-align:center;">
				<?php echo $Pat_id_t; ?>
			    </td>							
                        </tr>                        			
			<?php }?>
                        
                        <tr>
                            <th style="text-align:center;">COMPANY<?php print("<input name='companyUpdate' id='companyUpdate' value='".$_POST['companyUpdate']."' type='hidden' readonly>");?></th>
                            <td style="text-align:center;">
							<?php	   
								if ($Pat_id_t!=""){
									
									$conexion = conectar();
									if ($Company_t!="" && ($_POST['action'] == 'insert' || $_POST['companyUpdate'] == 'si')) {										
                                                                                print("<input type='hidden' name='Table_name_t' value=".$Company_t.">");
                                                                                print("<input type='hidden' name='company' value=".$Company_t.">");
                                                                                
//										$sql2  = "Select * from discipline where name = '".$Discipline_t."';";
//										$resultado2 = ejecutar($sql2,$conexion);
//										while ($row=mysqli_fetch_array($resultado2)) 
//										{ 
//											$Company_t = $row["Name"]; 
//										}
										echo $Company_t; 
									} else 
									{
										print("<select name='company'  id='company' onchange='updateCompany();' class='populate placeholder'>
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
									
									print("<select name='company'  id='company' onchange='updateCompany();' disabled class='populate placeholder'>
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
							</td>
                        </tr>                       
						<tr>
                            <th style="text-align:center;">Discipline<?php print("<input name='disciplineUpdate' id='disciplineUpdate' value='".$_POST['disciplineUpdate']."' type='hidden' readonly>");?></th>
                            <td style="text-align:center;">
							<?php	   
								if ($Company_t!=""){
									
									$conexion = conectar();
									if ($Discipline_t!="" && ($_POST['action'] == 'insert' || $_POST['disciplineUpdate'] == 'si')) {
										print("<input type='hidden' name='Discipline_t' value=".$Discipline_t.">");
										$sql2  = "Select * from discipline where name = '".$Discipline_t."';";
										$resultado2 = ejecutar($sql2,$conexion);
										while ($row=mysqli_fetch_array($resultado2)) 
										{ 
											$Discipline_t = $row["Name"]; 
										}
										echo $Discipline_t; 
									} else 
									{
										print("<select name='Discipline'  id='Discipline' onchange='updateDiscipline();' class='populate placeholder'>
											  <option value=''>--- SELECT ---</option>");
										$sql5  = "Select Name from discipline ";
										
										$resultado5 = ejecutar($sql5,$conexion);

										while ($row=mysqli_fetch_array($resultado5)) 
										{	
											if($_POST['Discipline'] == $row["Name"])
												print("<option value='".$row["Name"]."' selected>".$row["Name"]." </option>"); 
											else
												print("<option value='".$row["Name"]."'>".$row["Name"]." </option>"); 

										}
										print("</select>");
									}
								}else{
									
									print("<select name='Discipline'  id='Discipline' onchange='updateDiscipline();' disabled class='populate placeholder'>
											  <option value='' selected>--- SELECT ---</option>");
										$sql5  = "Select Name from discipline ";
										
										$resultado5 = ejecutar($sql5,$conexion);
										while ($row=mysqli_fetch_array($resultado5)) 
										{	
										print("<option value='".$row["Name"]."'>".$row["Name"]." </option>"); 
										}
										print("</select>");
								}	
								?>	
							</td>
                        </tr>
						<tr>
                            <th style="text-align:center;">Diagnostic<?php print("<input name='diagnosticUpdate' id='diagnosticUpdate' value='".$_POST['diagnosticUpdate']."' type='hidden' readonly>");?></th>
                            <td style="text-align:center;">
								<?php	if ($Discipline_t != "") { ?>
									<?php  if ($Diagnostic_t != "" && ($_POST['action'] == 'insert' || $_POST['diagnosticUpdate']=='si')) { 
									echo $Diagnostic_t;?>
									<input  type='hidden' name='Diagnostic_t' id="Diagnostic_t" value="<?php echo $Diagnostic_t; ?>">
									<?php } else { 	 
									//echo $_POST['Diagnostic'];
									print("<select name='Diagnostic'  id='Diagnostic' onchange='updateDiscipline();' class='populate placeholder'>
											  <option value=''>--- SELECT ---</option>");
										$sql5  = "SELECT * FROM diagnosiscodes d left join discipline di on di.DisciplineId = d.TreatDiscipId Where di.name = '".$Discipline_t."' and DiagCodeValue <> '';";
										
										$resultado5 = ejecutar($sql5,$conexion);

										while ($row=mysqli_fetch_array($resultado5)) 
										{	
											if($_POST['Diagnostic'] == $row["DiagCodeValue"])
												print("<option value='".$row["DiagCodeValue"]."' selected>".$row["DiagCodeValue"]." </option>"); 
											else
												print("<option value='".$row["DiagCodeValue"]."'>".$row["DiagCodeValue"]." </option>"); 

										}
										print("</select>");									
									
									} ?>
								<?php }else{ 
                                                                                
                                                                                print("<select name='Diagnostic'  id='Diagnostic' onchange='updateDiscipline();' class='populate placeholder' disabled>
											  <option value=''>--- SELECT ---</option>");
										$sql5  = "SELECT * FROM diagnosiscodes d left join discipline di on di.DisciplineId = d.TreatDiscipId Where di.name = '".$Discipline_t."' and DiagCodeValue <> '';";
										
										$resultado5 = ejecutar($sql5,$conexion);

										while ($row=mysqli_fetch_array($resultado5)) 
										{												
												print("<option value='".$row["DiagCodeValue"]."'>".$row["DiagCodeValue"]." </option>"); 

										}
										print("</select>");
                                                                                
                                                                    } ?>
							</td>
                        </tr>
						<tr>
                            <th style="text-align:center;">Issue Date</th>
                            <td style="text-align:center;">
								<?php	if ($Diagnostic_t != "") { ?>
									<?php  if ($Issue_date == "") { ?>
									<input  name="Issue_date" type="date" id="Issue_date" value="<?php echo $Issue_date; ?>">
									<?php } else { 	echo $Issue_date_t;  ?>
									<input  type='hidden' name='Issue_date_t' id="Issue_date_t" value='<?php echo $Issue_date_t; ?>'>
									<?php } ?>
								<?php }else{ ?>
									<input  name="Issue_date" type="date"id="Issue_date" value="" disabled>
								<?php } ?>
							</td>
                        </tr>
						<tr>
                            <th style="text-align:center;">End Date</th>
                            <td style="text-align:center;">
								<?php	if ($Diagnostic_t != "") { ?>
									<?php  if ($End_date == "") { ?>
									<input  name="End_date" type="date" id="End_date" value="<?php echo $End_date; ?>">
									<?php } else { 	echo $End_date_t;  ?>
									<input  type='hidden' name='End_date_t' id="End_date_t" value='<?php echo $End_date_t; ?>'>
									<?php } ?>
								<?php }else{ ?>
									<input  name="End_date" type="date" id="End_date" value="" disabled>
								<?php } ?>
							</td>
                        </tr>
						
						<tr>
						    <th style="text-align:center;">Physician Name<?php print("<input name='npiUpdate' id='npiUpdate' value='".$_POST['npiUpdate']."' type='hidden' readonly>");?></th>
                            <td style="text-align:center;">
						
								<?php	   
								if ($Diagnostic_t!=""){
									$conexion = conectar();
									if ($NPI_t!="" && ($_POST['action'] == 'insert' || $_POST['npiUpdate']=='si')) {
										print("<input type='hidden' name='NPI_t' value=".$NPI_t.">");
										$sql4  = "Select NPI , Name from physician where NPI = '".$NPI_t."'";
										$resultado4 = ejecutar($sql4,$conexion);
										while ($row=mysqli_fetch_array($resultado4)) 
										{ 
										$Name = $row['Name']; 
										}
										echo $Name; 
										print("<input type='hidden' name='Name' value='".$Name."'>");
									} else 
									{
										print("<select name='NPI' id='NPI' onchange='updateNpi();' class='populate placeholder'>
											  <option value='' selected>--- SELECT ---</option>");
										$sql  = "Select NPI, Name  from physician order by Name";
										//echo $sql;
										$resultado = ejecutar($sql,$conexion);
										while ($row=mysqli_fetch_array($resultado)) 
										{	
										//$Pat_id_t=$row["Pat_id"];
										
										if($NPI_t == $row["NPI"])
											print("<option value='".$row["NPI"]."' selected>".$row['Name']." </option>"); 
										else
											print("<option value='".$row["NPI"]."'>".$row['Name']." </option>"); 
										}
										print("</select>");
									}
								}else{
									print("<select name='NPI' id='NPI' onchange='submit();' class='populate placeholder' disabled>
											  <option value='' selected>--- SELECT ---</option>");
										$sql  = "Select NPI, Name  from physician order by Name";
										//echo $sql;
										$resultado = ejecutar($sql,$conexion);
										while ($row=mysqli_fetch_array($resultado)) 
										{	
										//$Pat_id_t=$row["Pat_id"];
										print("<option value='".$row["NPI"]."'>".$row["Name"]." </option>"); 
										}
										print("</select>");
								}
								?>
								
							</td>
                        </tr>
				
			<?php if($NPI_t != ''){?>		
			<tr>
                            <th style="text-align:center;">Physician ID</th>
                            <td style="text-align:center;">
				 <?php echo $NPI_t;?>
			    </td>
							
                        </tr> 
			<?php }?>                        
                        <?php if($NPI_t != ''){?>
                                <tr>
                            <th style="text-align:center;">Attachment</th>
                            <td style="text-align:center;">
				<input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>					
                            </td>
                            </tr>
                        <?php }else {?>
                                <tr>
                                    <th style="text-align:center;">Attachment</th>
                                    <td style="text-align:center;">
                                        <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]' disabled="disabled">					
                                    </td>
                            </tr>
                        <?php }?>    
						
						<tr>
						<td colspan=2 style="text-align:center;">
							<!-- <input name='csub' type='submit' value=" Submit " class="btn btn-primary btn-lg"> -->
							 <input name='reset' type='submit' value="Reset" class="btn btn-primary btn-lg">
							 <?php if($listo==1){
								if($_POST['action'] == 'insert') $actionButton = 'Add';
								else $actionButton = 'Update';
							?>
							 <input name='insert' type='submit' value=" <?php echo $actionButton;?> " class="btn btn-success btn-lg">
							 <?php } ?>
						</td>
                    </table>
					<br><br>
					
					</div>
					
 </div>
<div class="row">
<div class="col-lg-12">
					<table   class="table" cellspacing="0" width="100%"  id="table_carga">
									<thead>
            <tr>	
				<th>Patient ID</th>			
                <th>NAME</th>
                <th>Discipline</th> 
				<th>Diagnostic</th>
                <th>Issue_Date</th>
                <th>End Date</th> 
				<th>Physician_name</th>
                <th>Physician_NPI</th>
                <th>COMPANY</th> 
				             <th>UPDATES</th><th>DELETE</th>
            </tr>
									</thead>
					
					
					<tbody>
					<?php
					$conexion = conectar();
					
					$query01 = " select Patient_ID , Patient_name , Discipline ,Diagnostic , Issue_date , End_date ,
		Physician_name , Physician_NPI , Table_name  from prescription order by TIMESTAMP desc
					";
					
					$result01 = mysqli_query($conexion, $query01);
					
					while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
						echo '<tr>	 
						
						<td>'.$row['Patient_ID'].'</td>
					  <td>'.$row['Patient_name'].'</td>
					  <td>'.$row['Discipline'].'</td>
					  <td>'.$row['Diagnostic'].'</td>
					  <td>'.$row['Issue_date'].'</td>
					  <td>'.$row['End_date'].'</td>
					  <td>'.$row['Physician_name'].'</td>
					  <td>'.$row['Physician_NPI'].'</td>
					 <td>'.$row['Table_name'].'</td>
					 <td><div id="result_u'.$row['Patient_ID'].'"><button type="button" class="btn btn-info btn-lg" onclick="showUpdatePrescription(\''.$row['Patient_ID'].'\',\''.$row['Patient_name'].'\',\''.$row['Issue_date'].'\',\''.$row['End_date'].'\',\''.$row['Physician_name'].'\',\''.$row['Physician_NPI'].'\',\''.$row['Discipline'].'\',\''.$row['Diagnostic'].'\',\''.$row['Table_name'].'\');">Update</button></div></td>
					 <td><div id="result_d'.$row['Patient_ID'].'"><button type="button" class="btn btn-danger btn-lg" onclick="deletePrescription(\''.$row['Patient_ID'].'\',\''.$row['Discipline'].'\',\''.$row['Diagnostic'].'\',\''.$row['Table_name'].'\');">Delete</button></div></td> 			 			
					
					
					</tr>';
					}
					
					 ?>	
					
					<tbody/>

					</table>
					</div>
					
					
                </form>
            </div>

            <div class="col-md-4">
                
            </div>

        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
       
        <!-- /.row -->

<br>
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
	$('#Diagnostic').select2();
	$('#Pat_id').select2();
	$('#Discipline').select2();				
        $('#company').select2();
	$('#NPI').select2();
}
// Run timepicker

$(document).ready(function() {	
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	
});
</script>
</html>
	
