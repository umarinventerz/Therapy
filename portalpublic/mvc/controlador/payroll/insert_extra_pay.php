<?php 

             session_start();
             
require_once("../../../conex.php");
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
        
        $id_employee = sanitizeString($conexion, $_POST['id_employee']);
        $extra_pay = sanitizeString($conexion, $_POST['extra_pay']);                   
        $type_extra_pay = sanitizeString($conexion, $_POST['type_extra_pay']);
        $description = sanitizeString($conexion, $_POST['description']);
        
        $insert_extra_pay = "INSERT into tbl_extra_pay (id_employee, extra_pay, status_extra_pay, user_id, type_extra_pay, description) "
                . "values ('".$id_employee."',".$extra_pay.",0,".$_SESSION['user_id'].",'".$type_extra_pay."','".$description."');";
        ejecutar($insert_extra_pay,$conexion);          
            
        
        $json_resultado['resultado'] = 'INSERTADO';     
        $json_resultado['recargar_pagina'] = 'no';  
        $json_resultado['realizar_submit'] = 'si';  
                       
         echo json_encode($json_resultado);   


?>

                