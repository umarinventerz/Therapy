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
                         
        $valor_tipo_documento = strtoupper($_POST['valor_tipo_documento']);
             
        $conexion = conectar();            
        
        $insert_type_documents = "INSERT into tbl_doc_type_documents (type_documents) values ('".$valor_tipo_documento."');";
        ejecutar($insert_type_documents,$conexion);          
            
        
        $json_resultado['resultado'] = 'INSERTADO';   
   
        $json_resultado['cargar_resultado'] = 'null'; 
        $json_resultado['identificador_resultado'] = 'null';
        $json_resultado['recargar_pagina'] = 'si';  
                       
         echo json_encode($json_resultado);   


?>

                