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
        $notes = strtoupper($_POST['notes']);
        $Pat_id = strtoupper($_POST['Pat_id']);
        $discipline = strtoupper($_POST['discipline']);
        //$id_careplans = strtoupper($_POST['id_careplans']);
        $type_report = strtoupper($_POST['type']);
        
        $sqlCareplans = "SELECT * FROM careplans WHERE Patient_ID = '".$Pat_id."' AND Discipline = '".$discipline."' AND status = 1";
        $resultado = ejecutar($sqlCareplans ,$conexion);

        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $id_careplans = $datos['id_careplans'];
            $i++;
        }  
//        ALTER TABLE `kidswork_therapy`.`careplans` ADD COLUMN `id_careplans` INTEGER  NOT NULL AUTO_INCREMENT AFTER `tx_sent_time`,
// ADD PRIMARY KEY (`id_careplans`);

             
                    
        
        $insert_notes = "INSERT into tbl_notes (pat_id, discipline, notes, id_careplans,type_report,user_id) values ('".$Pat_id."','".$discipline."','".$notes."','".$id_careplans."','".$type_report."','".$_SESSION['user_id']."');";
        ejecutar($insert_notes,$conexion);          
            
        
        $json_resultado['resultado'] = 'INSERTADO';   
   
        $json_resultado['cargar_resultado'] = 'null'; 
        $json_resultado['identificador_resultado'] = 'null';
        $json_resultado['recargar_pagina'] = 'si';  
                       
         echo json_encode($json_resultado);   


?>

                