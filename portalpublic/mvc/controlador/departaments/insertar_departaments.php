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
                         
        $valor_departament = strtoupper($_POST['valor_departament']);
        
        $conexion = conectar();            
        
        $insert_departaments = "INSERT into tbl_departaments (departaments) values ('".$valor_departament."');";
        ejecutar($insert_departaments,$conexion);          
            
        
        $json_resultado['resultado'] = 'INSERTADO';   
   
        $json_resultado['cargar_resultado'] = 'null'; 
        $json_resultado['identificador_resultado'] = 'null';                 
        $json_resultado['recargar_pagina'] = 'si';         
        
         echo json_encode($json_resultado);   


?>

                