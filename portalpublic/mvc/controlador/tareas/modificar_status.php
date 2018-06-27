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

            $fecha = date('Y-m-d');


            $conexion = conectar();
                

                if($_POST['status'] == 'si' && $_POST['notificacion'] == 'no'){    
                                                
                    $update =" UPDATE tbl_tareas SET id_status_tareas = ".$_POST['id_status']." WHERE id_tareas = ".$_POST['id_tareas'].";";                  
                    $resultado = ejecutar($update,$conexion);  
                    
                    $insert =" INSERT into tbl_tareas_fechas (fechas_tareas,id_status_tareas,id_tareas)                     
                    values ('".$fecha."','".$_POST['id_status']."','".$_POST['id_tareas']."');";                  
                    $resultado = ejecutar($insert,$conexion);                     

                    if($_POST['id_status'] == 2){                        
                        $mensaje_resultado = 'finalizada';                         
                        $mensaje_vista = '<b><font color="green">Finished</font></b>';
                    }
                    
                    if($_POST['id_status'] == 3){                        
                        $mensaje_resultado = 'cancelada';                         
                        $mensaje_vista = '<b><font color="red">Canceled</font></b>';
                    }                    
                    
                    $json_resultado['resultado'] = $mensaje_resultado;
                    $json_resultado['mensaje'] = $mensaje_vista;
                                                                          
                    
                }   
                
                if($_POST['status'] == 'no' && ($_POST['notificacion'] == '0' || $_POST['notificacion'] == '1')){    
                      
                $sql = 'SELECT * FROM tbl_tareas t 
                        LEFT JOIN tbl_tareas_usuarios ut using(id_tareas) 
                        WHERE ut.user_id = \''.$_SESSION['user_id'].'\' AND t.notificado = 0 and ut.id_tipo_usuario_tareas = 2;';
                $resultado = ejecutar($sql,$conexion);

                $i=0;
                while ($row=  mysqli_fetch_assoc($resultado)) {	
                    $reporte[] = $row;                                                           
                $i++;        
                }  
                
                
                
                $r=0;
                while (isset($reporte[$r])){
                    
                    $update = 'UPDATE tbl_tareas SET notificado = \'1\'                         
                        WHERE id_tareas = '.$reporte[$r]['id_tareas'].';';                  
                    $resultado = ejecutar($update,$conexion);                     
                    
                    $r++;
                }
                                                             

                    $json_resultado['resultado'] = 'notificado';
                    
                }                  
                 
                echo json_encode($json_resultado);                                  

?>