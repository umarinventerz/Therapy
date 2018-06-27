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

$identificador_patient = sanitizeString($conexion, $_POST['identificador_patient']);
$id_persona_contacto = sanitizeString($conexion, $_POST['id_persona_contacto']);

    

    $agregar_contactos = "INSERT INTO tbl_contactos (tabla_ref,id_tabla_ref) VALUES ('patients',".$identificador_patient.")";
    $resultado = ejecutar($agregar_contactos,$conexion);    
    
    $sql = 'SELECT id_contactos FROM tbl_contactos WHERE tabla_ref = \'patients\' AND id_tabla_ref = \''.$identificador_patient.'\';';

    $resultado = ejecutar($sql,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	

            $id_contactos = $reporte[$k]['id_contactos'] = $row['id_contactos'];                                           

        $k++;        
        }      
        
    $sql2 = 'SELECT id_persona_tipo_contacto FROM tbl_persona_tipo_contacto WHERE id_persona_contacto = \''.$id_persona_contacto.'\';';

    $resultado = ejecutar($sql2,$conexion);

        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	
           
            $agregar_contactos2 = "INSERT INTO tbl_contacto_persona_tipo_contacto (id_contactos,id_persona_tipo_contacto) VALUES (".$id_contactos.",".$row['id_persona_tipo_contacto'].")";
            ejecutar($agregar_contactos2,$conexion);    
                
        $k++;        
        }         
        
                               
    $json_resultado['mensaje'] = 'Almacenamiento Satisfactorio';
                                                                     
                 echo json_encode($json_resultado);                                  

?>