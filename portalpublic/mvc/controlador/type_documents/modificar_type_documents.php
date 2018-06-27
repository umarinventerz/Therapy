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
        
        $identificador = $_POST['identificador'];
        $valor_campo = strtoupper($_POST['valor_campo']);
        $nombre_tabla = $_POST['nombre_tabla'];        
        $nombre_campo = $_POST['nombre_campo_bd'];
        $nombre_identificador = $_POST['nombre_identificador'];
             
        $conexion = conectar();            
        
        $modificar_campo = "UPDATE $nombre_tabla SET $nombre_campo = '$valor_campo' WHERE $nombre_identificador = $identificador ;";
        ejecutar($modificar_campo,$conexion);          
          
        $json_resultado['resultado'] = 'modificado';   
     
         echo json_encode($json_resultado);   


?>

                