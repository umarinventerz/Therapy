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

$id_contacto_tipo_contacto = sanitizeString($conexion, $_POST['id_contacto_tipo_contacto']);

    $sql2 = 'SELECT id_persona_tipo_contacto FROM tbl_persona_tipo_contacto WHERE id_contacto_tipo_contacto = \''.$id_contacto_tipo_contacto.'\';';

    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	
           
            $id_persona_tipo_contacto = $row['id_persona_tipo_contacto'];
                              
        $k++;        
        } 



    

    $eliminar_ptc = " DELETE FROM tbl_contacto_tipo_contacto WHERE id_contacto_tipo_contacto = ".$id_contacto_tipo_contacto.";";                 
    $resultado = ejecutar($eliminar_ptc,$conexion);  
    
    
    $eliminar_ptc = " DELETE FROM tbl_persona_tipo_contacto WHERE id_contacto_tipo_contacto = ".$id_contacto_tipo_contacto.";";                 
    $resultado = ejecutar($eliminar_ptc,$conexion);   
    
    $eliminar_ptc = " DELETE FROM tbl_contacto_persona_tipo_contacto WHERE id_persona_tipo_contacto = ".$id_persona_tipo_contacto.";";                 
    $resultado = ejecutar($eliminar_ptc,$conexion);     
    
                                 
    $json_resultado['mensaje'] = 'Eliminaci&oacute;n Satisfactoria';
                                                                     
                 echo json_encode($json_resultado);                                  

?>