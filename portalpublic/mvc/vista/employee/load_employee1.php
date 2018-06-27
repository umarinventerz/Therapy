<?php
session_start();
require_once("../../../conex.php");
require_once("../../../queries.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
$conexion = conectar();

if($_POST['submit'] == 'submit' && ($_POST['action'] != 'edit_submit' && $_POST['action'] != 'edit')){
$carpeta = 'employee/'.$_POST['firstName'].'_'.$_POST['lastName'];
if (!file_exists("../../../".$carpeta)) {
    mkdir("../../../".$carpeta, 0777, true);
}

$files_upload = scandir ('../../../server/php/files');

$i = 2;
$p = 0;
$length = count($files_upload);
while($i < ($length-1)){
        chmod("../../../server/php/files/".$files_upload[$i], 0777);	
        rename ("../../../server/php/files/".$files_upload[$i],"../../../".$carpeta."/".$files_upload[$i]);
        chmod("../../../".$carpeta."/".$files_upload[$i], 0777);	
        $route_file[$i] = $carpeta."/".$files_upload[$i];
$i++;
}

    
$kindEmployee = $_POST['kindEmployee'];
$firstName=$_POST['firstName']; 
$lastName=$_POST['lastName']; 
$userName=$_POST['userName']; 
$password=$_POST['password'];
$payTo=$_POST['payTo']; 
$socialSecurity=$_POST['socialSecurity']; 
list($m_dob,$d_dob,$a_dob) = explode('/',$_POST['dob']);
$dob = $a_dob.'-'.$m_dob.'-'.$d_dob;
list($m_hire,$d_hire,$a_hire) = explode('/',$_POST['hireDate']);
$hireDate = $a_hire.'-'.$m_hire.'-'.$d_hire;
list($m_termination,$d_termination,$a_termination) = explode('/',$_POST['terminationDate']);
$terminationDate = $a_termination.'-'.$m_termination.'-'.$d_termination;
$address=$_POST['address']; 
$phoneNumber=$_POST['phoneNumber'];
$id_carriers=$_POST['id_carriers'];
$credentials=$_POST['credentials']; 
$email=$_POST['email']; 
$licenceNumber=$_POST['licenceNumber']; 
list($m,$d,$a) = explode('/',$_POST['input_date_licence']);
$input_date_licence = $a.'-'.$m.'-'.$d; 
$fingerPrint=$_POST['fingerPrint']; 
list($mm,$dd,$aa) = explode('/',$_POST['input_date_finger']);
$input_date_finger = $aa.'-'.$mm.'-'.$dd; 
$npiNumber=$_POST['npiNumber']; 
$caqhNumber=$_POST['caqhNumber']; 
$timePay=$_POST['timePay'];
$status=$_POST['status']; 
$typeContrato = $_POST['typeContrato'];
$typeSalary = $_POST['typeSalary'];
$calendar=$_POST['calendar'];
$view_none=$_POST['view_none'];
$view_own=$_POST['view_own'];
$view_all=$_POST['view_all'];
$edit_none=$_POST['edit_none'];
$edit_own=$_POST['edit_own'];
$edit_all=$_POST['edit_all'];
$discipline_id=$_POST['discipline'];
$assistant=$_POST['assitant'];
$supervisor_id=$_POST['supervisor_id'];
if($assistant=='on'){    
    $guardar_assitant=1;
    $guardar_supervisor=$supervisor_id;    
}else{
    $guardar_assitant=0;
    $guardar_supervisor='';
}
if($calendar=='on'){
        if($view_none=='si'){
            $variable_view='view_none';
        }
        if($view_own=='si'){
            $variable_view='view_own';
        }
        if($view_all=='si'){
            $variable_view='view_all';
        }

        if($edit_none=='si'){
            $variable_edit='edit_none';
        }
        if($edit_own=='si'){
            $variable_edit='edit_own';
        }
        if($edit_all=='si'){
            $variable_edit='edit_all';
        }
        $concatenar_permisos=$variable_view.",".$variable_edit;
        if($concatenar_permisos==','){
            $concatenar_permisos_calendar='';
        }else{
            $concatenar_permisos_calendar=$concatenar_permisos;
        }
}else{
   $concatenar_permisos_calendar=''; 
}
if($_POST['amount_salary'] == '') $_POST['amount_salary'] = 'null';
$amountSalary = $_POST['amount_salary'];
$dependencies = $_POST['dependencies'];
$treatment[] = $_POST['treatment'];
$civilStatus = $_POST['civilStatus'];
	
$insert="INSERT into employee (`first_name`,`last_name`,`pay_to`,`social_security`,`adress`,`phone_number`,`email`,`Credentials`,`licence_number`,`expiration_date`,`finger_print`,`expiration_finger`,`npi_number`,`caqh_number`,`status`,`type_salary`,`kind_employee`
,`dependencies`,`time_pay`,`type_contract`,`civil_status`,`amount_salary`,`dob`,`hire_date`,`termination_date`,`id_carriers`,`permission_calendar`,`discipline_id`,`assistant`,`supervisor`) 
values ('".$firstName."','".$lastName."','".$payTo."','".$socialSecurity."','".$address."','".$phoneNumber."','".$email."','".$credentials."','".$licenceNumber."'
,'".$input_date_licence."','".$fingerPrint."','".$input_date_finger."','".$npiNumber."','".$caqhNumber."','".$status."','".$typeSalary."'
,'".$kindEmployee."',".$dependencies.",'".$timePay."','".$typeContrato."','".$civilStatus."',".$amountSalary.",'".$dob."','".$hireDate."','".$terminationDate."','".$id_carriers."','".$concatenar_permisos_calendar."','".$discipline_id."','".$guardar_assitant."','".$guardar_supervisor."');";

ejecutar($insert,$conexion);

$max_id = "SELECT MAX(id) AS id FROM employee";
$result01 = mysqli_query($conexion, $max_id);					
while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
	$id = $row['id'];
}

//PRUEBA PARA INSERTAR DE UNA A TABLA DE USER SYSTEM

$insert_user_system="INSERT into user_system (`Last_name`,`First_name`,`user_name`,`password`,`status_id`,`User_type`) 
values ('".$lastName."','".$firstName."','".$userName."','".$password."','1','1');";

ejecutar($insert_user_system,$conexion);

//$i = 0;
if(isset($treatment[0][0])){
	if($_POST['amount_tx_in']=='') $_POST['amount_tx_in'] = 'null';
	if($_POST['amount_evals_in']=='') $_POST['amount_evals_in'] = 'null';
	if($_POST['amount_tx_out']=='') $_POST['amount_tx_out'] = 'null';
	if($_POST['amount_evals_out']=='') $_POST['amount_evals_out'] = 'null';
	if($_POST['amount_transportation']=='') $_POST['amount_transportation'] = 'null';
	$insertTreatment = "INSERT INTO employee_treatment (`id_employee`,`tx_in`,`eval_in`,`tx_out`,`eval_out`,`transportation`) values ('".$id."',".$_POST['amount_tx_in'].",".$_POST['amount_evals_in'].",".$_POST['amount_tx_out'].",".$_POST['amount_evals_out'].",".$_POST['amount_transportation'].") ";
	ejecutar($insertTreatment,$conexion);
	//$i++;
}
$i = 2;
while(isset($route_file[$i])){
    $insertDocuments = "INSERT into tbl_documents (id_type_document, id_type_person, id_table_relation, id_person , route_document) 
    values ('5','2','0','".$id."','".$route_file[$i]."');";         
    ejecutar($insertDocuments,$conexion);
    $i++;
}

$insert_employee_year_to_date="INSERT into tbl_employee_year_to_date (`id_employee`,`gross`,`federal_withholdings`,`social_security`,`medicate`) 
values (".$id.",0,0,0,0);";
ejecutar($insert_employee_year_to_date,$conexion);

$insert_barcodes="INSERT into tbl_barcodes (`id_relation`,`bardcode`,`id_type_person`) 
values (".$id.",'E".$phoneNumber."','2');";
ejecutar($insert_barcodes,$conexion);

$_POST['firstName']=""; 
$_POST['lastName']=""; 
$_POST['payTo']=""; 
$_POST['socialSecurity']=""; 
$_POST['dob']="";
$_POST['hireDate']="";
$_POST['terminationDate']="";
$_POST['address']=""; 
$_POST['phoneNumber']=""; 
$_POST['credentials']=""; 
$_POST['email']=""; 
$_POST['licenceNumber']=""; 
$_POST['input_date_licence']=""; 
$_POST['fingerPrint']=""; 
$_POST['input_date_finger']=""; 
$_POST['npiNumber']=""; 
$_POST['caqhNumber']=""; 
$_POST['timePay']=""; 
$_POST['status']=""; 
$_POST['typeSalary'] = '';

echo "<script type=\"text/javascript\">alert(\"Employee Save\");</script>";
echo '<script>window.location="load_employee.php";</script>';
}else{
      
        
	if($_POST['submit'] == 'submit' && $_POST['action'] == 'edit'){
        
        $carpeta = 'employee/'.$_POST['firstName'].'_'.$_POST['lastName'];
        if (!file_exists("../../../".$carpeta)) {
            mkdir("../../../".$carpeta, 0777, true);
        }
        
        $files_upload = scandir ('../../../server/php/files');
        
        $i = 2;
        $p = 0;
        $length = count($files_upload);
        
        while($i < ($length-1)){
                chmod("../../../server/php/files/".$files_upload[$i], 0777);	
                rename ("../../../server/php/files/".$files_upload[$i],"../../../".$carpeta."/".$files_upload[$i]);
                chmod("../../../".$carpeta."/".$files_upload[$i], 0777);	
                $route_file[$i] = $carpeta."/".$files_upload[$i];
        $i++;
        }
               
	$kindEmployee = $_POST['kindEmployee'];
	$firstName=$_POST['firstName']; 
	$lastName=$_POST['lastName']; 
        $userName=$_POST['userName']; 
	$password=$_POST['password']; 
	$payTo=$_POST['payTo']; 
	$socialSecurity=$_POST['socialSecurity']; 
	list($m_dob,$d_dob,$a_dob) = explode('/',$_POST['dob']);
	$dob = $a_dob.'-'.$m_dob.'-'.$d_dob;
	list($m_hire,$d_hire,$a_hire) = explode('/',$_POST['hireDate']);
	$hireDate = $a_hire.'-'.$m_hire.'-'.$d_hire;
	list($m_termination,$d_termination,$a_termination) = explode('/',$_POST['terminationDate']);
	$terminationDate = $a_termination.'-'.$m_termination.'-'.$d_termination;
	$address=$_POST['address']; 
	$phoneNumber=$_POST['phoneNumber']; 
	$credentials=$_POST['credentials']; 
	$email=$_POST['email']; 
	$licenceNumber=$_POST['licenceNumber']; 
	list($m,$d,$a) = explode('/',$_POST['input_date_licence']);
	$input_date_licence = $a.'-'.$m.'-'.$d; 
	$fingerPrint=$_POST['fingerPrint']; 
	list($mm,$dd,$aa) = explode('/',$_POST['input_date_finger']);
	$input_date_finger = $aa.'-'.$mm.'-'.$dd; 
	$npiNumber=$_POST['npiNumber']; 
	$caqhNumber=$_POST['caqhNumber']; 
	$timePay=$_POST['timePay'];
	$status=$_POST['status']; 
	$typeContrato = $_POST['typeContrato'];
	$typeSalary = $_POST['typeSalary'];
        $calendar=$_POST['calendar'];
        $view_none=$_POST['view_none_edit'];
        $view_own=$_POST['view_own_edit'];
        $view_all=$_POST['view_all_edit'];
        $edit_none=$_POST['edit_none_edit'];
        $edit_own=$_POST['edit_own_edit'];
        $edit_all=$_POST['edit_all_edit'];
        $discipline_id=$_POST['discipline'];
        $assistant=$_POST['assitant'];
        $supervisor_id=$_POST['supervisor_id'];
        if($assistant=='on'){    
            $guardar_assitant=1;
            $guardar_supervisor=$supervisor_id;    
        }else{
            $guardar_assitant=0;
            $guardar_supervisor='';
        }
        if($calendar=='on'){
                if($view_none=='si'){
                    $variable_view='view_none';
                }
                if($view_own=='si'){
                    $variable_view='view_own';
                }
                if($view_all=='si'){
                    $variable_view='view_all';
                }

                if($edit_none=='si'){
                    $variable_edit='edit_none';
                }
                if($edit_own=='si'){
                    $variable_edit='edit_own';
                }
                if($edit_all=='si'){
                    $variable_edit='edit_all';
                }
                $concatenar_permisos=$variable_view.",".$variable_edit;
                if($concatenar_permisos==','){
                    $concatenar_permisos_calendar='';
                }else{
                    $concatenar_permisos_calendar=$concatenar_permisos;
                }
        }else{
           $concatenar_permisos_calendar=''; 
        }
	if($_POST['amount_salary'] == '') $_POST['amount_salary'] = 'null';
	$amountSalary = $_POST['amount_salary'];
	$dependencies = $_POST['dependencies'];
	$treatment[] = $_POST['treatment'];
	$civilStatus = $_POST['civilStatus'];
        $id_carriers=$_POST['id_carriers'];
        

	$insert="UPDATE employee SET `first_name` = '".$firstName."',`last_name`='".$lastName."',
	`pay_to`='".$payTo."',`social_security`='".$socialSecurity."',`adress` ='".$address."',
	`phone_number`='".$phoneNumber."',`email`='".$email."',`Credentials`= '".$credentials."',`licence_number`='".$licenceNumber."',
	`expiration_date`='".$input_date_licence."',`finger_print`='".$fingerPrint."',`expiration_finger`='".$input_date_finger."',
	`npi_number`= '".$npiNumber."',`caqh_number`='".$caqhNumber."',`status`='".$status."',`type_salary`='".$typeSalary."',
	`kind_employee` = '".$kindEmployee."',`dependencies`=".$dependencies.",`time_pay`='".$timePay."',
	`type_contract`='".$typeContrato."',`civil_status`='".$civilStatus."',`amount_salary`=".$amountSalary.", 
	`dob`='".$dob."',`hire_date`='".$hireDate."',`termination_date`='".$terminationDate."' ,`id_carriers`='".$id_carriers."',`permission_calendar`='".$concatenar_permisos_calendar."',`discipline_id`='".$discipline_id."',`assistant`='".$guardar_assitant."',`supervisor`='".$guardar_supervisor."' WHERE id = ".$_POST['id_employee'];

	ejecutar($insert,$conexion);
        
        $update_user_system=" UPDATE user_system SET `user_name` = '".$userName."',`password` = '".$password."' "
                . "WHERE user_id = ".$_POST['id_employee'].";";

        ejecutar($update_user_system,$conexion);

	if(isset($treatment[0][0])){
                
		if($_POST['amount_tx_in']=='') $_POST['amount_tx_in'] = 'null';
		if($_POST['amount_evals_in']=='') $_POST['amount_evals_in'] = 'null';
		if($_POST['amount_tx_out']=='') $_POST['amount_tx_out'] = 'null';
		if($_POST['amount_evals_out']=='') $_POST['amount_evals_out'] = 'null';
		if($_POST['amount_transportation']=='') $_POST['amount_transportation'] = 'null';
		$max_id = "SELECT * FROM employee_treatment WHERE id_employee = ".$_POST['id_employee'];
		$result01 = mysqli_query($conexion, $max_id);					
		$id = '';
		while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
			$id = $row['id'];
		}
		if($id != ''){
	 		$insertTreatment = "UPDATE employee_treatment SET `tx_in` = ".$_POST['amount_tx_in'].",`eval_in`=".$_POST['amount_evals_in'].",`tx_out` = ".$_POST['amount_tx_out'].",`eval_out`=".$_POST['amount_evals_out'].",`transportation`=".$_POST['amount_transportation']." WHERE id_employee = ".$_POST['id_employee'].";";	
                        ejecutar($insertTreatment,$conexion);
                }else {
			$insertTreatment = "INSERT INTO employee_treatment (`id_employee`,`tx_in`,`eval_in`,`tx_out`,`eval_out`,`transportation`) values ('".$_POST['id_employee']."',".$_POST['amount_tx_in'].",".$_POST['amount_evals_in'].",".$_POST['amount_tx_out'].",".$_POST['amount_evals_out'].",".$_POST['amount_transportation'].") "; 
			ejecutar($insertTreatment,$conexion);
			$insertYeartodate = "INSERT INTO tbl_employee_year_to_date (`id_employee`,`gross`,`federal_withholdings`,`social_security`,`medicate`) values ('".$_POST['id_employee']."','0','0','0','0') ";
                        ejecutar($insertYeartodate,$conexion);
		}
		
		
	}
        
        $i = 2;	
        while(isset($route_file[$i])){
                $insertDocuments = "INSERT into tbl_documents (id_type_document, id_type_person, id_table_relation, id_person , route_document) 
                values ('5','2','0','".$_POST['id_employee']."','".$route_file[$i]."');";         
                ejecutar($insertDocuments,$conexion);
                $i++;
        }      
        
	$_POST['firstName']=""; 
	$_POST['lastName']=""; 
	$_POST['payTo']=""; 
	$_POST['socialSecurity']="";
	$_POST['dob']="";
	$_POST['hireDate']="";
	$_POST['terminationDate']=""; 
	$_POST['address']=""; 
	$_POST['phoneNumber']=""; 
	$_POST['credentials']=""; 
	$_POST['email']=""; 
	$_POST['licenceNumber']=""; 
	$_POST['input_date_licence']=""; 
	$_POST['fingerPrint']=""; 
	$_POST['input_date_finger']=""; 
	$_POST['npiNumber']=""; 
	$_POST['caqhNumber']=""; 
	$_POST['timePay']=""; 
	$_POST['status']=""; 
	$_POST['typeSalary'] = '';
	$_POST['amount_salary'] = '';
	echo "<script type=\"text/javascript\">alert(\"Employee Edit\");</script>";
	echo '<script>window.location="edit_employee.php";</script>';
	//die();
	}

}



if($_POST['action'] == 'edit_submit'){
	
	$_POST['name_terapist'] = str_replace('-.','',$_POST['name_terapist']);
	list($name,$idEmployee) = explode('-',$_POST['name_terapist']);
	$max_id = "SELECT * FROM employee e LEFT JOIN employee_treatment et ON et.id_employee = e.id WHERE e.id = ".$idEmployee;	
	$result01 = mysqli_query($conexion, $max_id);		
	//die();
        $employee_disc  = "select discipline_id from employee where id=".$idEmployee;
        $ejecutar_employee_disc = ejecutar($employee_disc,$conexion); 
        while ($row_employee_disc=mysqli_fetch_array($ejecutar_employee_disc))  
        {   
            $discipline_employee_id=$row_employee_disc['discipline_id'];

        } 
	$type = '';
	$first_name = '';
	$last_name = '';
	$pay_to = '';
	$social_security = '';
	$dob = '';
	$hire_date = '';
	$termination_date = '';
	$adress = '';
	$phone_number = '';
	$email = '';
	$Credentials = '';
	$licence_number = '';
	$expiration_date = '';
	$finger_print = '';
	$expiration_finger = '';
	$npi_number = '';
	$caqh_number = '';
	$status = '';
	$type_salary = '';
	$amount_salary = '';
	$kind_employee = '';
	$dependencies = '';
	$time_pay = '';
	$type_contract = '';
	$tx = '';
	$eval = '';
	$civil_status = '';
        $id_carriers_edit = '';
        $update_permisos='permisos';
	while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
		$type = $row['id'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$pay_to = $row['pay_to'];
		$social_security = $row['social_security'];		
		$adress = $row['adress'];
		$phone_number = $row['phone_number'];
		list($a_dob,$m_dob,$d_dob) = explode('-',$row['dob']);
		$dob = $m_dob.'/'.$d_dob.'/'.$a_dob;
		list($a_hire,$m_hire,$d_hire) = explode('-',$row['hire_date']);
		$hire_date = $m_hire.'/'.$d_hire.'/'.$a_hire;
		list($a_termination,$m_termination,$d_termination) = explode('-',$row['termination_date']);
		$termination_date = $m_termination.'/'.$d_termination.'/'.$a_termination;
		list($a,$m,$d) = explode('-',$row['expiration_date']);
		$expiration_date = $m.'/'.$d.'/'.$a; 
		list($aa,$mm,$dd) = explode('-',$row['expiration_finger']);
		$expiration_finger = $mm.'/'.$dd.'/'.$aa; 
		$email = $row['email'];
		$Credentials = $row['Credentials'];
		$licence_number = $row['licence_number'];
		$finger_print = $row['finger_print'];
		$npi_number = $row['npi_number'];
		$caqh_number = $row['caqh_number'];
		$status = $row['status'];
		$type_salary = $row['type_salary'];
		$amount_salary = $row['amount_salary'];
		$kind_employee = $row['kind_employee'];
		$dependencies = $row['dependencies'];
		$tx_in = $row['tx_in'];
		$eval_in = $row['eval_in'];
		$tx_out = $row['tx_out'];
		$eval_out = $row['eval_out'];
		$transportation = $row['transportation'];
		$time_pay = $row['time_pay'];
		$type_contract = $row['type_contract'];
		$civil_status = $row['civil_status'];
                $id_carriers_edit = $row['id_carriers'];
                $id_calendar = $row['permission_calendar'];
                $assitant_update = $row['assistant'];
                $supervisor_update = $row['supervisor'];
	}
        
        $permisos=explode(',',$id_calendar);
        
        $user_system = "SELECT * FROM user_system WHERE user_id = ".$idEmployee;	
	$resultUserSystem = mysqli_query($conexion, $user_system);		
        
        while($row = mysqli_fetch_array($resultUserSystem,MYSQLI_ASSOC)){
            $user_name_text = $row['user_name'];
            $password_text = $row['password'];
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
    
    <title>.: KIDWORKS THERAPY :.</title>
<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">

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

<link rel="stylesheet" href="../../../css/style.css">
<link rel="stylesheet" href="../../../css/jquery.fileupload.css">
<link rel="stylesheet" href="../../../css/jquery.fileupload-ui.css">

<script src="../../../js/vendor/jquery.ui.widget.js"></script>
<script src="../../../js/tmpl.min.js"></script>
<script src="../../../js/load-image.all.min.js"></script>
<script src="../../../js/jquery.fileupload.js"></script>
<script src="../../../js/jquery.fileupload-process.js"></script>
<script src="../../../js/jquery.fileupload-image.js"></script>
<script src="../../../js/jquery.fileupload-validate.js"></script>
<script src="../../../js/jquery.fileupload-ui.js"></script>
<script src="../../../js/main.js"></script>
    <script>
	function showTimePay(){
		$("#divTimePay").show();
		$("#divAmountSalary").show();		
		$("#timePay").prop('disabled', false);			
		$("#divTreatment").hide();
		$("#divAmountTreatment").hide();
	}
        
        function permisos(){            
            $("#permisos_view").toggle();
        }
	
	function showTreatment(){
		$("#divTreatment").show();
		$("#divAmountTreatment").show();
		$("#treatment1").prop('disabled', false);
		$("#treatment2").prop('disabled', false);
		$("#treatment3").prop('disabled', false);
		$("#treatment4").prop('disabled', false);  
		$("#treatment5").prop('disabled', false);
		$("#divTimePay").hide();
		$("#divAmountSalary").hide();
	} 
	function showField(value){
		if(value == 'Administrative'){
                        $("#hide_discipline").hide();
                        $("#hide_supervisor").hide();
			$("#divNpiAndCaqh").hide();
			$("#divFinger").hide();
			$("#divLicence").hide();divCredentialsEmail
			$("#divEmailAdministrativo").show();
			$("#divCredentialsEmail").hide();
		}else{
                    if(value===''){                        
			$("#divNpiAndCaqh").show();
			$("#divFinger").show();
			$("#divLicence").show();
			$("#divEmailAdministrativo").hide();
			$("#divCredentialsEmail").show();
                    }else{
                        $("#hide_discipline").show();                        
			$("#divNpiAndCaqh").show();
			$("#divFinger").show();
			$("#divLicence").show();
			$("#divEmailAdministrativo").hide();
			$("#divCredentialsEmail").show();
                    }
		}
	}
        function mostrar_supervisor(){
            
            if($("#assitant").is(":checked")){
               var chequeado='si'; 
            }else{
                chequeado='no';
            }
            if(chequeado==='si'){
                $("#hide_supervisor").toggle();
            }else{
                $("#hide_supervisor").toggle();
            }
        }
        function calendar(data){
            
            if(data===1){
                
                $("#view_nones").val('si');
                $("#view_owns").val('');
                $("#view_alls").val('');
                
            }
            if(data===2){               
                $("#view_nones").val('');
                $("#view_owns").val('si');
                $("#view_alls").val('');
            }
            
            if(data===3){
                
                $("#view_nones").val('');
                $("#view_owns").val('');
                $("#view_alls").val('si');
                
            }
            
            if(data===4){
                
                $("#edit_nones").val('si');
                $("#edit_owns").val('');
                $("#edit_alls").val('');
            }
            
            if(data===5){
                
                $("#edit_nones").val('');
                $("#edit_owns").val('si');
                $("#edit_alls").val('');
            }
            
            if(data===6){
                
                $("#edit_nones").val('');
                $("#edit_owns").val('');
                $("#edit_alls").val('si');
            }
        }
        
        function calendar_edit(data){
            
            if(data===1){
                
                $("#view_nones_edit").val('si');
                $("#view_owns_edit").val('');
                $("#view_alls_edit").val('');
                
            }
            if(data===2){               
                $("#view_nones_edit").val('');
                $("#view_owns_edit").val('si');
                $("#view_alls_edit").val('');
            }
            
            if(data===3){
                
                $("#view_nones_edit").val('');
                $("#view_owns_edit").val('');
                $("#view_alls_edit").val('si');
                
            }
            
            if(data===4){
                
                $("#edit_nones_edit").val('si');
                $("#edit_owns_edit").val('');
                $("#edit_alls_edit").val('');
            }
            
            if(data===5){
                
                $("#edit_nones_edit").val('');
                $("#edit_owns_edit").val('si');
                $("#edit_alls_edit").val('');
            }
            
            if(data===6){
                
                $("#edit_nones_edit").val('');
                $("#edit_owns_edit").val('');
                $("#edit_alls_edit").val('si');
            }
        }
	function unlockNextField(nextfield,value,field){
            
		if(field != ''){
			if( $('#'+field).prop('checked') ) {
			    $("#"+nextfield).prop('disabled', false);	
			}else{
				$("#"+nextfield).val('');
				$("#"+nextfield).prop('disabled', true);				
			}		
		}else{
			if(nextfield == 'credentials' && $("#kindEmployee").val() == 'Administrativo')
				nextfield = 'email1';
				
			if(value != '')
				$("#"+nextfield).prop('disabled', false);	
			else{
				$("#"+nextfield).val('');
				$("#"+nextfield).prop('disabled', true);				
			}
		}						
	} 
	function ShowDivEdit(){
		if( $('#typeSalary1').prop('checked') ) {
			showTimePay();
		}
		if( $('#typeSalary2').prop('checked') ) {
			showTreatment();
		}
		showField($('#kindEmployee').val())
	}
	function enableField(){
		if($('#action').val()=='edit_submit'){
			$("#last_name").prop('disabled', false);
			$("#first_name").prop('disabled', false);
			$("#pay_to").prop('disabled', false);
			$("#social_security").prop('disabled', false);
			$("#dob").prop('disabled', false);
			$("#hire_date").prop('disabled', false);
			$("#termination_date").prop('disabled', false);
			$("#adress").prop('disabled', false);
			$("#phone_number").prop('disabled', false);
			$("#email").prop('disabled', false);
			$("#amount_tx").prop('disabled', false);
			$("#amount_eval").prop('disabled', false);
			$("#timePay").prop('disabled', false);
			$("#treatment1").prop('disabled', false);
			$("#treatment2").prop('disabled', false);
		}
	}
    </script>
    <style>
        .table_calendar {            
            width: 10%;  
            border: 1px solid #129FEA;
            border-collapse: collapse;
        }
    </style>
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
				 			  
				</ul>
		      </li>		
		
		<h3>Load Employee</h3>
            </div>
        </div>
<br/>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">			
			<div class="box-content">
				<h4 class="page-header">Registration form</h4>
				<form id="fileupload" role="form" enctype="multipart/form-data" class="form-horizontal"action="load_employee.php" method="post">
					<?php if($_POST['action'] != ''){
						$disabled = '';
					?>					
					<input type="hidden" id="action" name="action" value="<?php echo ($_POST['action'] == 'edit_submit')?'edit':'';?>">
					<input type="hidden" id="id_employee" name="id_employee" value="<?php echo $idEmployee;?>">
					<div class="form-group">												
						<div class="col-sm-12">
							<button type="button" class="btn btn-primary btn-label-left" value="return" name="return_button" id="return_button" onclick="window.location.href='edit_employee.php'">
							<span><i class="fa fa-clock-o"></i></span>
								RETURN EDIT
							</button>
						</div>
					</div>
					<?php }else{
						$disabled = 'disabled';
					}?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Type</label>
						<div class="col-sm-4">
							<select class="populate placeholder" name="kindEmployee" id="kindEmployee" onchange="showField(this.value);">
								<option value="">-- Select employee role--</option>
								<option value="Administrative" <?php if($kind_employee == 'Administrative') echo 'selected';?>>Administrative</option>
								<option value="Therapist" <?php if($kind_employee == 'Therapist') echo 'selected';?>>Therapist</option>								
							</select>
						</div>
                                                                                                                                            
					</div>                                        
					<div class="form-group">
						<label class="col-sm-2 control-label">First name</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('lastName',this.value,'');" value="<?php echo $first_name;?>" class="form-control" placeholder="First name" data-toggle="tooltip" data-placement="bottom" title="Tooltip for name" name="firstName" id="firstName">
						</div>
						<label class="col-sm-2 control-label">Last name</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('userName',this.value,'');" value="<?php echo $last_name;?>" class="form-control" placeholder="Last name" data-toggle="tooltip" data-placement="bottom" title="Tooltip for last name" name="lastName" id="lastName" <?php echo $disabled;?>>
						</div>
					</div>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">User name</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('password',this.value,'');" value="<?php echo $user_name_text;?>" class="form-control" placeholder="User name" data-toggle="tooltip" data-placement="bottom" title="Tooltip for name" name="userName" id="userName" <?php echo $disabled;?>>
						</div>
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-4">
							<input type="password" onkeyup="unlockNextField('payTo',this.value,'');" value="<?php echo $password_text;?>" class="form-control" placeholder="password" data-toggle="tooltip" data-placement="bottom" title="Tooltip for last name" name="password" id="password" <?php echo $disabled;?>>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Pay To</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('socialSecurity',this.value,'');" value="<?php echo $pay_to;?>" class="form-control" placeholder="Pay to" name="payTo" id="payTo" <?php echo $disabled;?>>
						</div>
						<label class="col-sm-2 control-label">Social Security</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('dob',this.value,'');unlockNextField('hireDate',this.value,'');unlockNextField('terminationDate',this.value,'');unlockNextField('address',this.value,'');" value="<?php echo $social_security;?>" class="form-control" placeholder="Social Security" name="socialSecurity" id="socialSecurity" <?php echo $disabled;?>>
							<span class="fa fa-check-square-o txt-success form-control-feedback"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-2 control-label">DOB</label>
						<div class="col-sm-4">
							<input type="text" value="<?php echo $dob;?>" class="form-control" placeholder="DOB" name="dob" id="dob" <?php echo $disabled;?>>
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>
						<label class="col-sm-2 control-label">Hire Date</label>
						<div class="col-sm-4">
							<input type="text" value="<?php echo $hire_date;?>" class="form-control" placeholder="Hire Date" name="hireDate" id="hireDate" <?php echo $disabled;?>>
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>							
						</div>						
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Termination Date</label>
						<div class="col-sm-4">
							<input type="text" value="<?php echo $termination_date;?>" class="form-control" placeholder="Termination Date" name="terminationDate" id="terminationDate" <?php echo $disabled;?>>
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>					
					</div>
					
					<div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Address</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('phoneNumber',this.value,'');" value="<?php echo $adress;?>" class="form-control" placeholder="Address" name="address" id="address" <?php echo $disabled;?>>
							<span class="fa fa-check-square-o txt-success form-control-feedback"></span>
						</div>
						<label class="col-sm-2 control-label">Phone number</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('id_carriers',this.value,'');" value="<?php echo $phone_number;?>" class="form-control" name="phoneNumber" id="phoneNumber" <?php echo $disabled;?>/>
                                                </div><br>
                                                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Telephone provider</font></label>
                                                <div class="col-sm-4">
                                                    <select class="populate placeholder" onchange="unlockNextField('credentials',this.value,'');" id="id_carriers" name="id_carriers" <?php echo $disabled;?>>
                                                        <option value="">Seleccione</option>
                                                        <?php 
                                                            $sql  = "Select id_patients_carriers, carrier from tbl_patients_carriers order by id_patients_carriers asc"; 
                                                              $conexion = conectar(); 
                                                              $resultado = ejecutar($sql,$conexion); 
                                                              while ($row=mysqli_fetch_array($resultado))  
                                                              {   
                                                                  if(isset($id_carriers_edit)){
                                                                        if($row["id_patients_carriers"]==$id_carriers_edit){
                                                                            print("<option value='".$row["id_patients_carriers"]."' selected>".$row["carrier"]."</option>"); 
                                                                        }else{
                                                                                print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                                                        }
                                                                  }else{
                                                                      print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                                                  }
                                                              }       

                                                        ?> 
                                                    </select>
                                                </div>
                                                
					</div>
					<div class="form-group" id="divCredentialsEmail">						
						<label class="col-sm-2 control-label">Credentials</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('email',this.value,'');" value="<?php echo $Credentials;?>" class="form-control" name="credentials" id="credentials" <?php echo $disabled;?>/>
						</div>
						<label class="col-sm-2 control-label">Email address</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('licenceNumber',this.value,'');unlockNextField('input_date_licence',this.value,'');" value="<?php echo $email;?>" class="form-control" name="email" id="email" <?php echo $disabled;?>/>
						</div>						
					</div>
					<div class="form-group" id="divEmailAdministrativo" style="display:none;">
						<label class="col-sm-2 control-label">Email address</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('licenceNumber',this.value,'');unlockNextField('input_date_licence',this.value,'');" value="<?php echo $email;?>" class="form-control" name="email" id="email1" <?php echo $disabled;?>/>
						</div>						
					</div>
					<div class="form-group has-feedback" id="divLicence">
						<label class="col-sm-2 control-label">Licence Number</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('fingerPrint',this.value,'');unlockNextField('input_date_finger',this.value,'');" value="<?php echo $licence_number;?>" class="form-control" placeholder="No info" data-toggle="tooltip" data-placement="top" title="Hello world!" name="licenceNumber" id="licenceNumber" <?php echo $disabled;?>>
						</div>
						<label class="col-sm-2 control-label">Expiration Date</label>
						<div class="col-sm-4">
							<input type="text" id="input_date_licence" name="input_date_licence" value="<?php echo $expiration_date;?>" class="form-control" placeholder="Date" <?php echo $disabled;?>>
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>												
					</div>
					<div class="form-group has-feedback" id="divFinger">
						<label class="col-sm-2 control-label">Finger Prints</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('npiNumber',this.value,'');" value="<?php echo $finger_print;?>" class="form-control" placeholder="No info" data-toggle="tooltip" data-placement="top" title="Hello world!" name="fingerPrint" id="fingerPrint" <?php echo $disabled;?>>
						</div>
						<label class="col-sm-2 control-label">Expiration Date</label>
						<div class="col-sm-4">
							<input type="text" id="input_date_finger" name="input_date_finger" value="<?php echo $expiration_finger;?>" class="form-control" placeholder="Date" <?php echo $disabled;?>>
							<span class="fa fa-calendar txt-danger form-control-feedback"></span>
						</div>												
					</div>
					<div class="form-group has-feedback" id="divNpiAndCaqh">
						<label class="col-sm-2 control-label">NPI number</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('caqhNumber',this.value,'');" value="<?php echo $npi_number;?>" class="form-control" placeholder="NPI Number" name="npiNumber" id="npiNumber" <?php echo $disabled;?>>
							<span class="fa fa-check-square-o txt-success form-control-feedback"></span>
						</div>
						<label class="col-sm-2 control-label">CAQH number</label>
						<div class="col-sm-4">
							<input type="text" onkeyup="unlockNextField('typePay1',this.value,'');unlockNextField('typePay2',this.value,'');" value="<?php echo $caqh_number;?>" class="form-control" placeholder="CAQH Number" name="caqhNumber" id="caqhNumber" <?php echo $disabled;?>>
							<span class="fa fa-check-square-o txt-success form-control-feedback"></span>
						</div>								`			
					</div>
					<div class="form-group">
							<label class="col-sm-2 control-label">Type of Contratc</label>
							<div class="col-sm-2">
								<div class="radio">
									<label>
										<input type="radio"  value="W-2" name="typeContrato" id="typeContrato1" <?php if($type_contract == 'W-2') echo 'checked';?>/> W-2
										<i class="fa fa-square-o small" ></i>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="radio">
									<label>
										<input type="radio" value="1099" name="typeContrato" id="typeContrato2" <?php if($type_contract == '1099') echo 'checked';?>/> 1099
										<i class="fa fa-square-o small" ></i>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Payment Type</label>
							<div class="col-sm-2">
								<div class="radio">
									<label>
										<input type="radio"  onclick="showTimePay();" value="Salary" name="typeSalary" id="typeSalary1" <?php if($type_salary == 'Salary') echo 'checked';?>/> Salary 
										<i class="fa fa-square-o small"></i>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="radio">
									<label>
										<input type="radio"  onclick="showTreatment()" value="Perdiem" name="typeSalary" id="typeSalary2" <?php if($type_salary == 'Perdiem') echo 'checked';?>/> Perdiem
										<i class="fa fa-square-o small"></i>
									</label>
								</div>
							</div>
						</div>
					<div class="form-group" id="divTreatment" style="display:none;">
						<label class="col-sm-2 control-label">Treatment</label>
						<div class="col-sm-2">
							<div class="checkbox">
								<label>
									<input onclick="unlockNextField('amount_tx_in',this.value,'treatment1');" type="checkbox" value="tx_in" name="treatment[]" id="treatment1" <?php echo $disabled;?> <?php if($tx_in > 0) echo 'checked';?>/> TX_IN
									<i class="fa fa-square-o small"></i>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="checkbox">
								<label>
									<input onclick="unlockNextField('amount_tx_out',this.value,'treatment2');" type="checkbox" value="tx_out" name="treatment[]" id="treatment2" <?php echo $disabled;?> <?php if($tx_out > 0) echo 'checked';?>/> TX_OUT
									<i class="fa fa-square-o small"></i>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="checkbox">
								<label>
									<input onclick="unlockNextField('amount_evals_in',this.value,'treatment3');" type="checkbox"  value="evals_in" name="treatment[]" id="treatment3" <?php echo $disabled;?> <?php if($eval_in > 0) echo 'checked';?>/> Evals_in
									<i class="fa fa-square-o small"></i>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="checkbox">
								<label>
									<input onclick="unlockNextField('amount_evals_out',this.value,'treatment4');" type="checkbox"  value="evals_out" name="treatment[]" id="treatment4" <?php echo $disabled;?> <?php if($eval_out > 0) echo 'checked';?>/> Evals_out
									<i class="fa fa-square-o small"></i>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="checkbox">
								<label>
									<input onclick="unlockNextField('amount_transportation',this.value,'treatment5');" type="checkbox"  value="transportation" name="treatment[]" id="treatment5" <?php echo $disabled;?> <?php if($transportation > 0) echo 'checked';?>/> Transportation
									<i class="fa fa-square-o small"></i>
								</label>
							</div>
						</div>											
					</div>
					<div class="form-group" id="divAmountTreatment" style="display:none;">
						<label class="col-sm-2 control-label">Amount $</label>
						<div class="col-sm-2">
							<div >
								<label>
									<input onkeyup="unlockNextField('civilStatus',this.value,'');" type="text" value="<?php echo $tx_in;?>" name="amount_tx_in" id="amount_tx_in" <?php echo $disabled;?>/>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div >
								<label>
									<input onkeyup="unlockNextField('civilStatus',this.value,'');" type="text" value="<?php echo $tx_out;?>" name="amount_tx_out" id="amount_tx_out" <?php echo $disabled;?>/>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div>
								<label>
									<input onkeyup="unlockNextField('civilStatus',this.value,'');" type="text"  value="<?php echo $eval_in;?>" name="amount_evals_in" id="amount_evals_in" <?php echo $disabled;?>/>
								</label>
							</div>
						</div>						
						<div class="col-sm-2">
							<div>
								<label>
									<input onkeyup="unlockNextField('civilStatus',this.value,'');" type="text"  value="<?php echo $eval_out;?>" name="amount_evals_out" id="amount_evals_out" <?php echo $disabled;?>/>
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div>
								<label>
									<input onkeyup="unlockNextField('civilStatus',this.value,'');" type="text"  value="<?php echo $transportation;?>" name="amount_transportation" id="amount_transportation" <?php echo $disabled;?>/>
								</label>
							</div>
						</div>
					</div>
					<div class="form-group" id="divTimePay" style="display:none;">
						<label class="col-sm-2 control-label">Time of Pay</label>
						<div class="col-sm-4">
							<select onchange="unlockNextField('amount_salary',this.value,'');" class="populate placeholder" name="timePay" id="timePay" <?php echo $disabled;?>>
								<option value="">-- Select --</option>
								<option value="Bikeweekly" <?php if($time_pay == 'Bikeweekly') echo 'selected';?>>Bikeweekly</option>
								<option value="Perhour" <?php if($time_pay == 'Perhour') echo 'selected';?>>Perhour</option>								
							</select>
						</div>						
					</div>
					<div class="form-group" id="divAmountSalary" style="display:none;">
						<label class="col-sm-2 control-label">Amount $</label>
						<div class="col-sm-2">
							<div >
								<label>
									<input onkeyup="unlockNextField('civilStatus',this.value,'');" type="text" value="<?php echo $amount_salary;?>" name="amount_salary" id="amount_salary" <?php echo $disabled;?>/>
								</label>
							</div>
						</div>											
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Civil Status</label>
						<div class="col-sm-4">
							<select onchange="unlockNextField('dependencies',this.value,'');" class="populate placeholder" name="civilStatus" id="civilStatus" <?php echo $disabled;?>>
								<option value="">-- Select civil Status --</option>
								<option value="Single" <?php if($civil_status == 'Single') echo 'selected';?>>Single</option>
								<option value="Married" <?php if($civil_status == 'Married') echo 'selected';?>>Married</option>												
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Dependencies</label>
						<div class="col-sm-4">
							<select onchange="unlockNextField('status',this.value,'');" class="populate placeholder" name="dependencies" id="dependencies" <?php echo $disabled;?>>
								<option value="">-- Select dependencies --</option>
								<option value="0" <?php if($dependencies == 0) echo 'selected';?>>0</option>
								<option value="1" <?php if($dependencies == 1) echo 'selected';?>>1</option>
								<option value="2" <?php if($dependencies == 2) echo 'selected';?>>2</option>
								<option value="3" <?php if($dependencies == 3) echo 'selected';?>>3</option>
								<option value="4" <?php if($dependencies == 4) echo 'selected';?>>4</option>
								<option value="5" <?php if($dependencies == 5) echo 'selected';?>>5</option>
								<option value="6" <?php if($dependencies == 6) echo 'selected';?>>6</option>
								<option value="7" <?php if($dependencies == 7) echo 'selected';?>>7</option>
								<option value="8" <?php if($dependencies == 8) echo 'selected';?>>8</option>
								<option value="9" <?php if($dependencies == 9) echo 'selected';?>>9</option>
								<option value="10" <?php if($dependencies == 10) echo 'selected';?>>10</option>				
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<select onchange="unlockNextField('submit',this.value,'');" class="populate placeholder" name="status" id="status" <?php echo $disabled;?>>
								<option value="">-- Select a status --</option>
								<option value="1" <?php if($status == '1') echo 'selected';?>>Active</option>
								<option value="0" <?php if($status == '0') echo 'selected';?>>Inactive</option>								
							</select>
						</div>						
					</div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Calendar Privilege</label>
                                            <div class="checkbox col-sm-1">
                                                <label>
                                                    <input type="radio" onchange="permisos();" name="calendar" id="calendar" <?php if($id_calendar!=''){?>checked="" <?php }  ?>/><br>
                                                        
                                                </label>
                                            </div>
                                            <br>
                                            
                                            <div id="permisos_view" style="display:none">
                                                <table align="left" class="table_calendar" width="10%">
                                                    <tr>
                                                        <td><b>View none</b> 
                                                            <?php if($update_permisos!=='permisos'){ ?>
                                                                <input type="radio" name="calendar_view" id="view_none" checked=""/>
                                                                <input type="hidden"  name="view_none" id="view_nones" value="si"/>
                                                            <?php }else{?>
                                                                <?php if(count($permisos)>1){?>
                                                                        <input type="radio" name="calendar_view" id="view_none_edit" <?php if($permisos[0]=='view_none'){?>checked="" <?php } ?>/>
                                                                        <input type="hidden"  name="view_none_edit" id="view_nones_edit"/>
                                                                <?php }else{?>
                                                                        <input type="radio" name="calendar_view" id="view_none_edit" checked=""/>
                                                                        <input type="hidden"  name="view_none_edit" id="view_nones_edit"/>
                                                                <?php }?>
                                                            <?php }?>
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>View own</b>
                                                            <?php if($update_permisos!=='permisos'){?>
                                                                <input type="radio" name="calendar_view" id="view_own"/>
                                                                <input type="hidden"  name="view_own" id="view_owns"/>
                                                            <?php }else{?>
                                                                  <?php if(count($permisos)>1){?>
                                                                        <input type="radio" name="calendar_view" id="view_own_edit" <?php if($permisos[0]=='view_own'){?>checked="" <?php } ?>/>
                                                                        <input type="hidden"  name="view_own_edit" id="view_owns_edit"/>
                                                                  <?php }else{?>
                                                                        <input type="radio" name="calendar_view" id="view_own_edit"/>
                                                                        <input type="hidden"  name="view_own_edit" id="view_owns_edit"/>
                                                                  <?php }?>
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>View all</b> 
                                                            <?php if($update_permisos!=='permisos'){?>
                                                                <input type="radio" name="calendar_view" id="view_all"/>
                                                                <input type="hidden"  name="view_all" id="view_alls"/>
                                                            <?php }else{?>
                                                                  <?php if(count($permisos)>1){?>
                                                                        <input type="radio" name="calendar_view" id="view_all_edit" <?php if($permisos[0]=='view_all'){?>checked="" <?php } ?>/>
                                                                        <input type="hidden"  name="view_all_edit" id="view_alls_edit"/>
                                                                    <?php }else{?>
                                                                        <input type="radio" name="calendar_view" id="view_all_edit"/>
                                                                        <input type="hidden"  name="view_all_edit" id="view_alls_edit"/>
                                                                     <?php }?>   
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Edit none</b>
                                                            <?php if($update_permisos!=='permisos'){?>
                                                                <input type="radio" name="calendar_edit" id="edit_none" checked=""/>
                                                                <input type="hidden"  name="edit_none" id="edit_nones" value="si"/>
                                                            <?php }else{?>
                                                                <?php if(count($permisos)>1){?>
                                                                        <input type="radio" name="calendar_edit" id="edit_none_edit" <?php if($permisos[1]=='edit_none'){?>checked="" <?php } ?>/>
                                                                        <input type="hidden"  name="edit_none_edit" id="edit_nones_edit"/>
                                                                <?php }else{ ?>
                                                                        <input type="radio" name="calendar_edit" id="edit_none_edit" checked=""/>
                                                                        <input type="hidden"  name="edit_none_edit" id="edit_nones_edit" value="si"/>
                                                                <?php }?>
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Edit own</b> 
                                                            <?php if($update_permisos!=='permisos'){?>
                                                                <input type="radio" name="calendar_edit" id="edit_own"/>
                                                                <input type="hidden"  name="edit_own" id="edit_owns"/>
                                                            <?php }else{?>
                                                                 <?php if(count($permisos)>1){?>
                                                                        <input type="radio" name="calendar_edit" id="edit_own_edit" <?php if($permisos[1]=='edit_own'){?>checked="" <?php } ?>/>
                                                                        <input type="hidden"  name="edit_own_edit" id="edit_owns_edit"/>
                                                                <?php }else{ ?>
                                                                        <input type="radio" name="calendar_edit" id="edit_own_edit"/>
                                                                        <input type="hidden"  name="edit_own_edit" id="edit_owns_edit"/>
                                                                 <?php }?>       
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Edit all</b>
                                                            <?php if($update_permisos!=='permisos'){?>
                                                                <input type="radio" name="calendar_edit" id="edit_all"/>
                                                                <input type="hidden"  name="edit_all" id="edit_alls"/>
                                                            <?php }else{?>
                                                                <?php if(count($permisos)>1){?>
                                                                        <input type="radio" name="calendar_edit" id="edit_all_edit" <?php if($permisos[1]=='edit_all'){?>checked="" <?php } ?>/>
                                                                        <input type="hidden"  name="edit_all_edit" id="edit_alls_edit"/>
                                                                <?php }else{ ?>
                                                                        <input type="radio" name="calendar_edit" id="edit_all_edit"/>
                                                                        <input type="hidden"  name="edit_all_edit" id="edit_alls_edit"/>  
                                                                <?php }?>  
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                           
                                        </div><br>
                                        
                                            <div class="form-group">
                                                    <div id="hide_discipline" style="display:none">
                                                        <label class="col-sm-2 control-label">Discipline</label>
                                                        <div class="col-sm-4">
                                                                <select class="populate placeholder" name="discipline" id="discipline">                                                            
                                                                        <?php                                                                
                                                                            $sql_disc  = "select * from discipline";
                                                                            $ejecutar_disc = ejecutar($sql_disc,$conexion); 
                                                                            if(isset($discipline_employee_id)){                                                                        
                                                                                while ($row_disc=mysqli_fetch_array($ejecutar_disc))  
                                                                                {  
                                                                                    if($row_disc["DisciplineId"]==$discipline_employee_id){
                                                                                        print("<option value='".$row_disc["DisciplineId"]."' selected='true'>".$row_disc["Name"]."</option>"); 
                                                                                    }else{
                                                                                        print("<option value='".$row_disc["DisciplineId"]."'>".$row_disc["Name"]."</option>"); 
                                                                                    }
                                                                                }    
                                                                            }else{
                                                                               while ($row_disc=mysqli_fetch_array($ejecutar_disc))  
                                                                                {   
                                                                                    print("<option value='".$row_disc["DisciplineId"]."'>".$row_disc["Name"]."</option>"); 

                                                                                } 
                                                                            }
                                                                        ?> 							
                                                                </select>
                                                        </div>
                                                        <label class="col-sm-2 control-label">Assistant</label>
                                                        <div class="col-sm-4">
                                                            <!--Update-->
                                                            <input type="hidden" name="valor_assistant" id="valor_assistant" value="<?=$assitant_update?>" />
                                                            <input type="checkbox" <?php if(isset($assitant_update) && $assitant_update==1){?> checked=""<?php } ?>name="assitant" id="assistant" onchange="mostrar_supervisor();">
                                                        </div>
                                                </div>
                                            </div>
                                        <span id="hide_supervisor" style="display:none">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Supervisor</label>
                                                <div class="col-sm-4">                                       
                                                        <select class="populate placeholder" name="supervisor_id" id="supervisor_id">                                                            
                                                                <?php                                                                
                                                                    $sql_sup  = "select id,concat(Last_name,', ',First_name) as employee from employee";
                                                                    $ejecutar_sup = ejecutar($sql_sup,$conexion); 
                                                                    if(isset($supervisor_update)){                                                                        
                                                                        while ($row_disc=mysqli_fetch_array($ejecutar_sup))  
                                                                        {  
                                                                            if($row_disc["id"]==$supervisor_update){
                                                                                print("<option value='".$row_disc["id"]."' selected='true'>".$row_disc["employee"]."</option>"); 
                                                                            }else{
                                                                                print("<option value='".$row_disc["id"]."'>".$row_disc["employee"]."</option>"); 
                                                                            }
                                                                        }    
                                                                    }else{
                                                                       while ($row_disc=mysqli_fetch_array($ejecutar_sup))  
                                                                        {   
                                                                            print("<option value='".$row_disc["id"]."'>".$row_disc["employee"]."</option>"); 

                                                                        } 
                                                                    }
                                                                ?> 							
                                                        </select>
                                                </div>
                                            </div>
                                        </span><br><br>
                                        
                                        <hr>
					<div class="clearfix"></div>
					<div class="row fileupload-buttonbar">
					    <label class="col-sm-2 control-label">Upload File</label>
					    <div class="col-lg-10">
						<!-- The fileinput-button span is used to style the file input field as button -->
						<span class="btn btn-success fileinput-button">
						    <i class="glyphicon glyphicon-plus"></i>
						    <span>Add files...</span>
						    <input type="file" name="files[]" multiple>
						</span>
						<button type="submit" class="btn btn-primary start">
						    <i class="glyphicon glyphicon-upload"></i>
						    <span>Start upload</span>
						</button>
						<button type="reset" class="btn btn-warning cancel">
						    <i class="glyphicon glyphicon-ban-circle"></i>
						    <span>Cancel upload</span>
						</button>
						<button type="button" class="btn btn-danger delete">
						    <i class="glyphicon glyphicon-trash"></i>
						    <span>Delete</span>
						</button>
						<input type="checkbox" class="toggle">
						<!-- The global file processing state -->
						<span class="fileupload-process"></span>
					    </div>
					    <!-- The global progress state -->
					    <div class="col-lg-5 fileupload-progress fade">
						<!-- The global progress bar -->
						<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
						</div>
						<!-- The extended global progress state -->
						<div class="progress-extended">&nbsp;</div>
					    </div>
					</div>
					<!-- The table listing the files available for upload/download -->
					<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-2">
							<button type="cancel" class="btn btn-default btn-label-left" onclick= "window.location.href = 'load_employee.php';" value="reset" name="reset" id="reset">
							<span><i class="fa fa-clock-o txt-danger"></i></span>
								Reset
							</button>
						</div>						
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left" value="submit" name="submit" id="submit" <?php echo $disabled;?>>
							<span><i class="fa fa-clock-o"></i></span>
								Submit
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
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
<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-upload fade">
		<td>
		    <span class="preview"></span>
		</td>
		<td>
		    <p class="name">{%=file.name%}</p>
		    <strong class="error text-danger"></strong>
		</td>
		<td>
		    <p class="size">Processing...</p>
		    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
		</td>
		<td>
		    {% if (!i && !o.options.autoUpload) { %}
		        <button class="btn btn-primary start" disabled>
		            <i class="glyphicon glyphicon-upload"></i>
		            <span>Start</span>
		        </button>
		    {% } %}
		    {% if (!i) { %}
		        <button class="btn btn-warning cancel">
		            <i class="glyphicon glyphicon-ban-circle"></i>
		            <span>Cancel</span>
		        </button>
		    {% } %}
		</td>
	    </tr>
	{% } %}
    </script>
    <script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
	$('#s2_with_tag').select2({placeholder: "Select Status"});	
	$('#status').select2();
        $('#discipline').select2();
	$('#timePay').select2();
	$('#kindEmployee').select2();
        $('#id_carriers').select2();
	$('#dependencies').select2();
	$('#civilStatus').select2();
        $("#supervisor_id").select2();
	
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
	$('#input_date_licence').datepicker({setDate: new Date()});
	$('#input_date_finger').datepicker({setDate: new Date()});
	$('#dob').datepicker({setDate: new Date()});
	$('#hireDate').datepicker({setDate: new Date()});
	$('#terminationDate').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	LoadTimePickerScript(DemoTimePicker);
	// Add tooltip to form-controls
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	// Load example of form validation
	LoadBootstrapValidatorScript(DemoFormValidator);
	// Add drag-n-drop feature to boxes
	WinMove();
	ShowDivEdit();
	//enableField();
        
        //data para insertar
        $('#view_none').change(function() {
            calendar(1);
        });
        $('#view_own').change(function() {
            calendar(2);
        });
        $('#view_all').change(function() {
            calendar(3);
        });
        $('#edit_none').change(function() {
            calendar(4);
        });
        $('#edit_own').change(function() {
            calendar(5);
        });
        $('#edit_all').change(function() {
            calendar(6);
        });
        if($("#calendar").is(':checked')){
            $("#permisos_view").toggle();
        }
        
        //data para actualizar 
        
        $('#view_none_edit').change(function() {
            calendar_edit(1);
        });
        $('#view_own_edit').change(function() {
            calendar_edit(2);
        });
        $('#view_all_edit').change(function() {
            calendar_edit(3);
        });
        $('#edit_none_edit').change(function() {
            calendar_edit(4);
        });
        $('#edit_own_edit').change(function() {
            calendar_edit(5);
        });
        $('#edit_all_edit').change(function() {
            calendar_edit(6);
        });
        if($("#view_none_edit").is(':checked')){
            $("#view_nones_edit").val('si');
        }
        if($("#view_own_edit").is(':checked')){
            $("#view_owns_edit").val('si');
        }
        if($("#view_all_edit").is(':checked')){
            $("#view_alls_edit").val('si');
        }        
        if($("#edit_none_edit").is(':checked')){
            $("#edit_nones_edit").val('si');
        }
        if($("#edit_own_edit").is(':checked')){
            $("#edit_owns_edit").val('si');
        }
        if($("#edit_all_edit").is(':checked')){
            $("#edit_alls_edit").val('si');
        }
        
        if($("#valor_assistant").val()==='1'){
            $("#hide_supervisor").show();
        }else{
            $("#hide_supervisor").hide();
        }
        
        
});
</script>
</html>