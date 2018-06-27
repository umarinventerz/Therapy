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

$id_tipo_contacto = sanitizeString($conexion, $_POST['id_tipo_contacto']);
$descripcion_contacto = sanitizeString($conexion, $_POST['descripcion_contacto']);
$id_contacto_tipo_contacto = sanitizeString($conexion, $_POST['id_contacto_tipo_contacto']);

    $sql = 'UPDATE tbl_contacto_tipo_contacto SET id_tipo_contacto = '.$id_tipo_contacto.', descripcion_contacto = \''.$descripcion_contacto.'\' WHERE id_contacto_tipo_contacto = \''.$id_contacto_tipo_contacto.'\';';
    ejecutar($sql,$conexion);

    $sql2 = 'SELECT tipo_contacto FROM tbl_tipo_contacto WHERE id_tipo_contacto = '.$id_tipo_contacto.';';

    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	
           
            $tipo_contacto = $row['tipo_contacto'];
              
                
        $k++;        
        }     
    
    
           
    $json_resultado['mensaje'] = 'Modificación Satisfactoria';
    $json_resultado['tipo_contacto'] = $tipo_contacto;
                                                                     
                 echo json_encode($json_resultado);                                  

?>