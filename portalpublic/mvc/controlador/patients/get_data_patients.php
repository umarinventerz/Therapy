<?php

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


        $patients_id = $_POST["patients_id"]; 
        if(!isset($_POST["fields"]) || $_POST["fields"] == '')
            $fields = '*';
        else
            $fields = $_POST["fields"];

            $consultar = "SELECT ".$fields." FROM patients WHERE id = ".$patients_id.";";
            $resultado = ejecutar($consultar,$conexion);                     
            
            $data_patients = [];
            while ($row=mysqli_fetch_array($resultado)) {	
                $data_patients = $row;
            } 
            $json_resultado['patients'] = $data_patients;
                 
            echo json_encode($json_resultado);                                  

?>