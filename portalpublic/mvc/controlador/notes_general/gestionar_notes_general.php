 <?php

session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$conexion = conectar();
                
                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modificación'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Eliminación'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = 'id_notes_general = '.$_POST["id_notes_general"];
                
                } else {                                
                
                $id_notes_general = null;
                $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }
                
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){
                
                if(isset($_POST["notes_general"]) && $_POST["notes_general"] != null){ $notes_general = $_POST["notes_general"]; } else { $notes_general = 'null'; }
                    if(isset($_POST["id_type_person"]) && $_POST["id_type_person"] != null){ $id_type_person = $_POST["id_type_person"]; } else { $id_type_person = 'null'; }
                    if(isset($_POST["id_person"]) && $_POST["id_person"] != null){ $id_person = $_POST["id_person"]; } else { $id_person = 'null'; }
                    if(isset($_POST["table_reference"]) && $_POST["table_reference"] != null){ $table_reference = $_POST["table_reference"]; } else { $table_reference = 'null'; }
                    if(isset($_POST["date_notes"]) && $_POST["date_notes"] != null){ $date_notes = $_POST["date_notes"]; } else { $date_notes = 'null'; }
                    
                                }
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                $tabla = 'tbl_notes_general';
                
                if($accion == 'insertar'){
                
                $insert = " INSERT INTO tbl_notes_general(notes_general,id_type_person,id_person,table_reference,id_user,date_notes) VALUES ('".$notes_general."','".$id_type_person."','".$id_person."','".$table_reference."','".$_SESSION['user_id']."','".$date_notes."');";
                $resultado = ejecutar($insert,$conexion); 
                }
                

                if($accion == 'modificar'){
                
                $update = " UPDATE tbl_notes_general SET notes_general = '".$notes_general."',id_type_person = '".$id_type_person."',id_person = '".$id_person."',table_reference = '".$table_reference."',date_notes = '".$date_notes."' ".$where;
                $resultado = ejecutar($update,$conexion); 
                
                }
                
             if($accion == 'eliminar'){
                
                $delete = ' DELETE FROM tbl_notes_general WHERE '.$where;
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