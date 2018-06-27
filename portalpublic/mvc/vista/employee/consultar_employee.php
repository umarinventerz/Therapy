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


        $emp_id = $_POST["emp_id"]; 

            $consultar = "SELECT licence_number, concat(last_name,', ',first_name) as employee_name FROM employee WHERE id = ".$emp_id.";";
            $resultado = ejecutar($consultar,$conexion);                     

            $k=0;
            while ($row=mysqli_fetch_array($resultado)) {	

                $licence_number = $row['licence_number'];
                $therapyst_name = $row['employee_name'];

            $k++;        
            } 
            
            
                 
                 $json_resultado['licence_number'] = $licence_number;                                      
                 $json_resultado['therapyst_name'] = $therapyst_name;
                 
                 
                 echo json_encode($json_resultado);                                  

?>