<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}
$conexion = conectar();

$modal=$_REQUEST['modal'];

if($modal=='summary'){   
    $sql  = "SELECT * FROM tbl_templates WHERE type_document_id=3 AND discipline_id=".$_REQUEST['discipline']; 

    $resultado_eval = ejecutar($sql,$conexion); 

    $reporte = [];
    $i = 1;     
    $select='<select id="template_id_Summ" name="template_id_Summ" onchange="modificarCkeditor(this,\'editor_Summary\','.$_REQUEST['discipline'].',3);">';
    $select.="<option value=''>Select</option>";
    while($datos = mysqli_fetch_assoc($resultado_eval)) {
        $reporte = $datos;
        $select.="<option value='".$datos['id']."'>".$datos['name']."</option>";
    }
    $select.='</select>';
    $json_resultado['summary'] = $select;
}else{
    $sql  = "SELECT * FROM tbl_templates WHERE type_document_id=4 AND discipline_id=".$_REQUEST['discipline']; 

    $resultado_eval = ejecutar($sql,$conexion); 

    $reporte = [];
    $i = 1;      
    $select='<select id="template_id_discharge" name="template_id_discharge" onchange="modificarCkeditor(this,\'editor_Discharge\','.$_REQUEST['discipline'].',4);">';
    $select.="<option value=''>Select</option>";
    while($datos = mysqli_fetch_assoc($resultado_eval)) {
        $reporte = $datos;
        $select.="<option value='".$datos['id']."'>".$datos['name']."</option>";
    }
    $select.='</select>';
    $json_resultado['summary'] = $select;
}
echo json_encode($json_resultado); 