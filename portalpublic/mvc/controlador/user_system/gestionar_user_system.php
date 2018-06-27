 <?php
/*
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['id_usuario'])){
    header("Location: http://10.100.1.250/KIDWORKS/index.php?&session=finalizada", true);
    
}
*/

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../home/home.php";</script>';
    }
}



$conexion = conectar();
                
                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modificación'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Eliminación'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = 'user_id = '.$_POST["user_id"];
                
                } else {                                
                
                $user_id = null;
                $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }
                
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){
                
                if(isset($_POST["Last_name"]) && $_POST["Last_name"] != null){ $Last_name = $_POST["Last_name"]; } else { $Last_name = 'null'; }
                    if(isset($_POST["First_name"]) && $_POST["First_name"] != null){ $First_name = $_POST["First_name"]; } else { $First_name = 'null'; }
                    if(isset($_POST["user_name"]) && $_POST["user_name"] != null){ $user_name = $_POST["user_name"]; } else { $user_name = 'null'; }
                    if(isset($_POST["password"]) && $_POST["password"] != null){ $password = $_POST["password"]; } else { $password = 'null'; }
                    if(isset($_POST["status_id"]) && $_POST["status_id"] != null){ $status_id = $_POST["status_id"]; } else { $status_id = 'null'; }
                    if(isset($_POST["User_type"]) && $_POST["User_type"] != null){ $User_type = $_POST["User_type"]; } else { $User_type = 'null'; }
                    
                                }
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                $tabla = 'user_system';
                
                if($accion == 'insertar'){
                
                $insert = " INSERT INTO user_system(Last_name,First_name,user_name,password,status_id,User_type) VALUES ('".$Last_name."','".$First_name."','".$user_name."','".$password."','".$status_id."','".$User_type."');";
                $resultado = ejecutar($insert,$conexion); 
                }
                

                if($accion == 'modificar'){
                
                $update = " UPDATE user_system SET Last_name = '".$Last_name."',First_name = '".$First_name."',user_name = '".$user_name."',password = '".$password."',status_id = '".$status_id."',User_type = '".$User_type."' ".$where;
                $resultado = ejecutar($update,$conexion); 
                
                }
                
             if($accion == 'eliminar'){
                
                $delete = ' DELETE FROM user_system '.$where;
                $resultado = ejecutar($delete,$conexion);                     
                
                }
                
                 if($resultado) {
                
                     $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';
                     
                 $json_resultado['mensaje'] = $mensaje_almacenamiento;
                 
                 if($_POST['accion'] == 'eliminar'){
                     $json_resultado['mensaje_data_table'] = '<h5><font color="#FE642E"><b>Eliminado</b></font></h5>';
                 }
             
              
                 } else {
                
                 $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
                    
                 } 
                 
                 echo json_encode($json_resultado);                                  

?>