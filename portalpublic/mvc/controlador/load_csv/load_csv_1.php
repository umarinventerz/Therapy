<?php

if(!isset($_FILES['file-1']['name'])){
	echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
	echo '<script>window.location="../../vista/load_csv/load_csv_form_1.php.php";</script>';
}
    
if($_FILES['file-1']['type'] != 'text/csv'){
	echo "<script type=\"text/javascript\">alert(\"File Extencion must be CSV\");</script>";
	echo '<script>window.location="../../vista/load_csv/load_csv_form_1.php";</script>';
} 

$temporal = $_FILES['file-1']['tmp_name'];
$nombre_archivo = $_FILES['file-1']['name'];
$id=fopen($temporal,'r');   

$nameFile = array('InsuranceAuthorizations_dlc.csv',
'InsuranceAuthorizations_kqt.csv',
'InsuranceAuthorizations_dlcquality.csv',
'Patients_dlc.csv',
'Patients_kqt.csv',
'Patients_dlcquality.csv',
'CarePlans_kqt.csv',
'CarePlans_dlc.csv',
'CarePlans_dlcquality.csv',
'Treatments.csv');
//print_r($nameFile);  


if (!in_array(strtolower($_FILES['file-1']['name']),strtolower($nameFile))) {
	echo "<script type=\"text/javascript\">alert(\"File Name Must add : _CompanyName\");</script>";
	echo '<script>window.location="../../vista/load_csv/load_csv_form_1.php";</script>';
	
}

//Chequeo que el archivo alli ha sido cargado correctamente al servidor para guardarlo

if (is_uploaded_file($temporal)) {
	move_uploaded_file($temporal,"../../../CSV/$nombre_archivo");
	chmod("../../../CSV/$nombre_archivo", 0777);
}

header("location: read_file_csv_1.php?file=".$nombre_archivo);
?>
