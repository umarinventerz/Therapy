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
$id_persona_contacto = sanitizeString($conexion, $_POST['id_persona_contacto']);


//$pat_id = sanitizeString($conexion, $_POST['pat_id']);
//
//    $sql2 = 'SELECT id_contactos FROM tbl_contactos WHERE id_tabla_ref = \''.$pat_id.'\';';
//
//    $resultado = ejecutar($sql2,$conexion);
//
//        $k=0;
//        while ($row=mysqli_fetch_array($resultado)) {	
//           
//            $id_contactos = $row['id_contactos'];
//              
//                
//        $k++;        
//        } 


    $id_contactos = sanitizeString($conexion, $_POST['id_contactos']);
    $sql2 = 'SELECT * FROM tbl_contacto_persona WHERE id_persona_contacto = \''.$id_persona_contacto.'\';';

    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	
           
            $agregar_contactos2 = "DELETE FROM  tbl_contacto_persona WHERE id_persona_contacto = ".$row['id_persona_contacto']." #AND id_contactos = ".$id_contactos."
            ;";
            $querys .= '-----'.$agregar_contactos2.'------';
            ejecutar($agregar_contactos2,$conexion);    
                
        $k++;        
        } 


                             
    $json_resultado['mensaje'] = 'Eliminaci&oacute;n Satisfactoria';
    $json_resultado['querys'] = $querys;
    
                                                                     
                 echo json_encode($json_resultado);                                  

?>