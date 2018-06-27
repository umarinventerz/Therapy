<?php 
error_reporting(0);
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

    $conexion = conectar();                
    $ids_assistances = explode(',',(substr($_POST['ids_assistance'], 0, strlen($_POST['ids_assistance']) - 1)));
    $y = 0;
    while(isset($ids_assistances[$y])){
        unset($id_1);
        unset($id_2);
        list($id_1,$id_2) = explode('_',$ids_assistances[$y]);
        $ids_assistance_cadena .= $id_1.'|'.$id_2.'|';        
        $ids_assistance_operacion .= 'in|out|'; 
        $y++;
    }
    
    $ids_assistance_array = explode('|',(substr($ids_assistance_cadena, 0, strlen($ids_assistance_cadena) - 1)));    
    $ids_assistance_operacion = explode('|',(substr($ids_assistance_operacion, 0, strlen($ids_assistance_operacion) - 1)));

    $t = 0;
    while(isset($ids_assistance_array[$t])){
         if($_POST['action'] == 'eliminar'){
                if(strpos($ids_assistance_array[$t], 'new') === false){
                    $delete = "DELETE FROM tbl_assistance WHERE id_assistance = ".$ids_assistance_array[$t];
                    ejecutar($delete,$conexion);                  
                }
                $mensaje = 'Registro eliminado';
            }else{
                if(strpos($ids_assistance_array[$t], 'new') === false){
                    $modificar_campo = "UPDATE tbl_assistance SET "
                            . " hour_of_admission = '".$_POST['hour_'.$ids_assistance_array[$t]].":".$_POST['minutes_'.$ids_assistance_array[$t]].":".$_POST['seconds_'.$ids_assistance_array[$t]]."',"
                            . " operacion = '".$ids_assistance_operacion[$t]."',"
                            . " date_datetime = '".$_POST['id_date_hidden']." ".$_POST['hour_'.$ids_assistance_array[$t]].":".$_POST['minutes_'.$ids_assistance_array[$t]].":".$_POST['seconds_'.$ids_assistance_array[$t]]."'"
                            . " WHERE id_assistance = ".$ids_assistance_array[$t].";";
                    ejecutar($modificar_campo,$conexion);  
                }else{
                    $insert  = "INSERT INTO tbl_assistance (
                    id_usuario,
                    date_of_admission,
                    hour_of_admission,operacion,date_datetime) VALUES ('".$_POST['id_usuario_hidden']."','".$_POST['id_date_hidden']."','".$_POST['hour_'.$ids_assistance_array[$t]].":".$_POST['minutes_'.$ids_assistance_array[$t]].":".$_POST['seconds_'.$ids_assistance_array[$t]]."','".$ids_assistance_operacion[$t]."','".$_POST['id_date_hidden']." ".$_POST['hour_'.$ids_assistance_array[$t]].":".$_POST['minutes_'.$ids_assistance_array[$t]].":".$_POST['seconds_'.$ids_assistance_array[$t]]."')";

                    $resultado = ejecutar($insert,$conexion);
                }
                $mensaje = 'Registro modificado';
            }
        $t++;
    }    
          
    $json_resultado['resultado'] = $mensaje;   
    $type_message = 'success';
    $json_resultado['type_message'] = $type_message;
     
    echo json_encode($json_resultado);   
    


?>

             