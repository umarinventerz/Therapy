<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
$conexion = conectar();
$type_terapist = sanitizeString($conexion, $_GET['type_terapist']);
$status_terapist = sanitizeString($conexion, $_GET['status_terapist']);
$type_salary = sanitizeString($conexion, $_REQUEST['type_salary']);
$where_type_salary = '';
if($type_salary == 'Salary'){
	$where_type_salary = ' AND type_salary = \''.$type_salary.'\'';
}else{
	if($type_terapist != 'Administrative')
		$where_type_salary = ' AND type_salary <> \'Salary\'';
}


       $sql  = "SELECT distinct licence_number,time_pay, concat(last_name,', ',first_name) as terapist_name 
FROM employee 
WHERE kind_employee like '%".$type_terapist."%' AND status like '".$status_terapist."' ".$where_type_salary.";";

        $resultado = ejecutar($sql,$conexion);

        $reporte = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i] = $datos;
            $i++;
        } 
        
        
?>

    <select name='name_terapist' id='name_terapist' class='form-control' onchange="validar_campos_llenos();">
            <?php 
		if($type_terapist == 'Administrative'){
                	echo "<option value='all'>--- ALL ---</option>";
		}else {
			if($type_salary == 'Salary'){
				echo "<option value='all_therapist'>--- ALL ---</option>";
			}else{
				echo "<option value=''>--- SELECT ---</option>";
			}
		}
            $i=0;
            while (isset($reporte[$i])){	                                                                    
                if($reporte[$i]["licence_number"] == '' || $reporte[$i]["licence_number"] == 'None'){
                	$reporte[$i]["licence_number"] = $reporte[$i]["terapist_name"];
                	$license_number = '';
                }else{
                	$license_number = $reporte[$i]["licence_number"];
                }           
                echo ("<option value='".$reporte[$i]["licence_number"]."-".$reporte[$i]["time_pay"]."' >".utf8_decode($reporte[$i]["terapist_name"])."-".$reporte[$i]["licence_number"]." </option>");
            
            $i++;
                            
            }

        ?>

    </select>
<script>$('#name_terapist').attr('disabled',false);</script>

