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

    $id_persona_contacto = $_POST['id_persona_contacto'];
            
    $tabla_ref = $_POST['type_person'];
    $id_tabla_ref = $_POST['contact'];

    $c_persona_contacto_c = $_POST['c_persona_contacto_c'];
    $c_cargo_persona_contacto_c = $_POST['c_cargo_persona_contacto_c'];

    $c_genero_c = $_POST['c_genero_c'];
    $c_relacion_c = $_POST['c_relacion_c'];
    $c_descripcion_c = $_POST['c_descripcion_c'];

    $c_fecha_nacimiento_c = $_POST['c_fecha_nacimiento_c'];
    $c_direccion_c = $_POST['c_direccion_c'];

    $c_tipo_contacto_c = $_POST['c_tipo_contacto_c'];
    $c_descripcion_contacto_c = $_POST['c_descripcion_contacto_c'];

    $c_contacto_email_c = $_POST['c_contacto_email_c'];
    $c_contacto_telefono_c = $_POST['c_contacto_telefono_c'];
    $c_contacto_fax_c = $_POST['c_contacto_fax_c'];
    $id_carriers = $_POST['id_carriers'];
   

    $conexion = conectar();


if($tabla_ref != 'other'){    
    if($id_persona_contacto == ''){            
        $insert = " INSERT into tbl_contactos (tabla_ref,id_tabla_ref)                     
        values ('".$tabla_ref."','".$id_tabla_ref."');";                  
        $resultado = ejecutar($insert,$conexion);                       
    }
    
    $sql = 'SELECT id_contactos FROM tbl_contactos WHERE tabla_ref = \''.$tabla_ref.'\' AND id_tabla_ref = '.$id_tabla_ref.';';

    $resultado = ejecutar($sql,$conexion);

    $i=0;
    while ($row=mysqli_fetch_array($resultado)) {

        
        $id_contactos = $reporte[$i]['id_contactos'] = $row['id_contactos'];                                           
        $i++;        
    }

}else {$id_contactos= 'OTHER';}

    
    $persona_contacto_array = explode('|',(substr($c_persona_contacto_c, 0, strlen($c_persona_contacto_c) - 1)));
    $cargo_persona_contacto_array = explode('|',(substr($c_cargo_persona_contacto_c, 0, strlen($c_cargo_persona_contacto_c) - 1)));
    
    $genero_array = explode('|',(substr($c_genero_c, 0, strlen($c_genero_c) - 1)));
    $relacion_array = explode('|',(substr($c_relacion_c, 0, strlen($c_relacion_c) - 1)));
    $descripcion_array = explode('|',(substr($c_descripcion_c, 0, strlen($c_descripcion_c) - 1)));
    
    $tipo_contacto_array = explode('|',(substr($c_tipo_contacto_c, 0, strlen($c_tipo_contacto_c) - 1)));
    $descripcion_contacto_array = explode('|',(substr($c_descripcion_contacto_c, 0, strlen($c_descripcion_contacto_c) - 1)));
    
    $fecha_nacimiento_array = explode('|',(substr($c_fecha_nacimiento_c, 0, strlen($c_fecha_nacimiento_c) - 1)));
    $direccion_array = explode('|',(substr($c_direccion_c, 0, strlen($c_direccion_c) - 1)));
     
    $contacto_email_array = explode('|',(substr($c_contacto_email_c, 0, strlen($c_contacto_email_c) - 1)));
    $contacto_telefono_array = explode('|',(substr($c_contacto_telefono_c, 0, strlen($c_contacto_telefono_c) - 1)));     
    $contacto_fax_array = explode('|',(substr($c_contacto_fax_c, 0, strlen($c_contacto_fax_c) - 1)));                             
    
    $i=0;
    while (isset($persona_contacto_array[$i])){
        
        //$id_persona_contacto = null;
        
        if($id_persona_contacto != ''){
            
            $update = " UPDATE tbl_contacto_persona SET persona_contacto = '".utf8_decode($persona_contacto_array[$i])."' ,
            cargo_persona_contacto = '".utf8_decode($cargo_persona_contacto_array[$i])."',genero = '".$genero_array[$i]."',
            relacion = '".utf8_decode($relacion_array[$i])."',descripcion = '".utf8_decode($descripcion_array[$i])."',
            fecha_nacimiento = '".$fecha_nacimiento_array[$i]."',direccion = '".utf8_decode($direccion_array[$i])."',
            email = '".$contacto_email_array[$i]."',telefono = '".$contacto_telefono_array[$i]."',
            fax = '".$contacto_fax_array[$i]."',id_contactos = '".$id_contactos."',id_carriers = '".$id_carriers."' WHERE id_persona_contacto = ".$id_persona_contacto.";";                  
            $resultado = ejecutar($update,$conexion);           
            
        }else{
            $insert = " INSERT into tbl_contacto_persona (persona_contacto,cargo_persona_contacto,genero,relacion,descripcion,fecha_nacimiento,direccion,email,telefono,fax,id_contactos,id_carriers)                     
            values ('".utf8_decode($persona_contacto_array[$i])."','".utf8_decode($cargo_persona_contacto_array[$i])."','".$genero_array[$i]."','".utf8_decode($relacion_array[$i])."','".utf8_decode($descripcion_array[$i])."','".$fecha_nacimiento_array[$i]."','".utf8_decode($direccion_array[$i])."','".$contacto_email_array[$i]."','".$contacto_telefono_array[$i]."','".$contacto_fax_array[$i]."','".$id_contactos."','".$id_carriers."');";                  
            $resultado = ejecutar($insert,$conexion);           

            $sql = 'SELECT id_persona_contacto FROM tbl_contacto_persona WHERE persona_contacto = \''.$persona_contacto_array[$i].'\' AND cargo_persona_contacto = \''.$cargo_persona_contacto_array[$i].'\' AND genero = \''.$genero_array[$i].'\' AND relacion = \''.$relacion_array[$i].'\' AND descripcion = \''.$descripcion_array[$i].'\' AND fecha_nacimiento = \''.$fecha_nacimiento_array[$i].'\' AND direccion = \''.$direccion_array[$i].'\';';

            $resultado = ejecutar($sql,$conexion);
        }                         
        
        $i++;
    }
    
                
    $json_resultado['resultado'] = '<h3><font color="blue">Almacenamiento Satisfactorio</font></h3>';                    
    $json_resultado['mensaje'] = 'Almacenamiento Satisfactorio';


    echo json_encode($json_resultado);                                  

?>