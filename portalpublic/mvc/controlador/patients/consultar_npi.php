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


        $Phy_id = $_POST["Phy_id"]; 

            $consultar = "SELECT NPI FROM physician WHERE Phy_id = ".$Phy_id.";";
            $resultado = ejecutar($consultar,$conexion);                     

            $k=0;
            while ($row=mysqli_fetch_array($resultado)) {	

                $npi = $row['NPI'];

            $k++;        
            } 
            
            
                 
                 $json_resultado['npi'] = $npi;                                      
                 
                 echo json_encode($json_resultado);                                  

?>