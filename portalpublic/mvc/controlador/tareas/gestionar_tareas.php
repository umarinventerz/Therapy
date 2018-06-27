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
       
                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Tarea Modificada'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Tarea Eliminada'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = ' WHERE id_tareas = '.$_POST["id_tareas"];
                
                } else {                                
                
                $id_tareas = null;
                $mensaje_almacenamiento = 'Tarea Asignada';
                
                }
             
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){                                                            
                                        
                    if(isset($_POST["user_system"]) && $_POST["user_system"] != null){ $user_system = $_POST["user_system"]; } else { $user_system = 'null'; }
                    if(isset($_POST["user_registrer"]) && $_POST["user_registrer"] != null){ $user_registrer = $_POST["user_registrer"]; } else { $user_registrer = 'null'; }
                    if(isset($_POST["start_date"]) && $_POST["start_date"] != null){ 
                        
                    $fecha_i = date_create($_POST["start_date"]);
                    $fecha_ini = date_format($fecha_i,'Y-m-d');                          
                        
                        
                        $start_date = $fecha_ini; } else { $start_date = 'null'; }                                        
                    if(isset($_POST["end_date"]) && $_POST["end_date"] != null){ 
                        
                    $fecha_f = date_create($_POST["end_date"]);
                    $fecha_fn = date_format($fecha_f,'Y-m-d');                          
                        
                        $end_date = $fecha_fn; } else { $end_date = 'null'; }                    
                    if(isset($_POST["tareas"]) && $_POST["tareas"] != null){ $tareas = $_POST["tareas"]; } else { $tareas = 'null'; } 
                   
                                }
                           
                                
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                
                 
                if($accion == 'insertar'){
                    
                    $insert =" INSERT into tbl_tareas (tareas,id_status_tareas,fecha_inicio,fecha_fin)                     
                    values ('".$tareas."','1','".$start_date."','".$end_date."');";                  
                    $resultado = ejecutar($insert,$conexion);  
                    
                $sql = 'SELECT id_tareas FROM tbl_tareas WHERE tareas = \''.$tareas.'\' AND date_format(fecha_inicio,\'%Y-%m-%d\') = \''.$start_date.'\' AND date_format(fecha_fin,\'%Y-%m-%d\') = \''.$end_date.'\' AND id_status_tareas = 1;';
                $resultado = ejecutar($sql,$conexion);

                $i=0;
                while ($row=mysqli_fetch_array($resultado)) {	
                    $id_tareas = $reporte[$i]['id_tareas'] = $row['id_tareas'];                                                           
                $i++;        
                }    
            
                $fecha_registro = date('Y-m-d');                
                    
                    $insert =" INSERT into tbl_tareas_fechas (fechas_tareas,id_status_tareas,id_tareas)                     
                    values ('".$fecha_registro."','1','".$id_tareas."');";                  
                    $resultado = ejecutar($insert,$conexion);  
                    
                    $insert =" INSERT into tbl_tareas_usuarios (user_id,id_tipo_usuario_tareas,id_tareas)                     
                    values ('".$user_registrer."','1','".$id_tareas."');";                  
                    $resultado = ejecutar($insert,$conexion);   
                    
                    $insert =" INSERT into tbl_tareas_usuarios (user_id,id_tipo_usuario_tareas,id_tareas)                     
                    values ('".$user_system."','2','".$id_tareas."');";                  
                    $resultado = ejecutar($insert,$conexion);                     
                    
                }
                              

                $json_resultado['resultado'] = '<h4><font color="blue">'.$mensaje_almacenamiento.'</font></h4>';
                     
                $json_resultado['mensaje'] = $mensaje_almacenamiento;
                                                                                                                            
                 
                echo json_encode($json_resultado);                                  

?>