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
$id_persona_contacto = sanitizeString($conexion, $_POST['id_persona_contacto']);
$id_tabla_ref = sanitizeString($conexion, $_POST['id_tabla_ref']);


    $sql = 'INSERT INTO tbl_contacto_tipo_contacto (id_tipo_contacto,descripcion_contacto) values ('.$id_tipo_contacto.',\''.$descripcion_contacto.'\');';
    ejecutar($sql,$conexion);

    $sql2 = 'SELECT id_contacto_tipo_contacto FROM tbl_contacto_tipo_contacto WHERE id_tipo_contacto = '.$id_tipo_contacto.' AND descripcion_contacto = \''.$descripcion_contacto.'\';';
    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	           
            $id_contacto_tipo_contacto = $row['id_contacto_tipo_contacto'];                              
        $k++;        
        }   
        
    $sql = 'INSERT INTO tbl_persona_tipo_contacto (id_persona_contacto,id_contacto_tipo_contacto) values ('.$id_persona_contacto.','.$id_contacto_tipo_contacto.');';
    ejecutar($sql,$conexion);
    
    $sql2 = 'SELECT id_persona_tipo_contacto FROM tbl_persona_tipo_contacto WHERE id_persona_contacto = '.$id_persona_contacto.' AND id_contacto_tipo_contacto = \''.$id_contacto_tipo_contacto.'\';';
    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	           
            $id_persona_tipo_contacto = $row['id_persona_tipo_contacto'];                              
        $k++;        
        } 
        
    $sql2 = 'SELECT id_contactos FROM tbl_contactos WHERE tabla_ref = \'patients\' AND id_tabla_ref = '.$id_tabla_ref.';';
    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	           
            $id_contactos = $row['id_contactos'];                              
        $k++;        
        }         
        
    $sql = 'INSERT INTO tbl_contacto_persona_tipo_contacto (id_contactos,id_persona_tipo_contacto) values ('.$id_contactos.','.$id_persona_tipo_contacto.');';
    ejecutar($sql,$conexion);        
        
    $json_resultado['mensaje'] = 'Inserción Satisfactoria';    
                                                                     
                 echo json_encode($json_resultado);                                  

?>