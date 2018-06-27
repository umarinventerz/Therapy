<?php

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


                $sql = 'SELECT * FROM tbl_tareas t 
                        LEFT JOIN tbl_tareas_usuarios ut using(id_tareas) 
                        WHERE ut.user_id = \''.$_SESSION['user_id'].'\' and ut.id_tipo_usuario_tareas = 2 and id_status_tareas = 1;';
                $resultado = ejecutar($sql,$conexion);

                $i=0;
                while ($row=  mysqli_fetch_assoc($resultado)) {	
                    $cantidad_tareas_array[] = $row;                                                           
                $i++;        
                }                 
                
                if(isset($cantidad_tareas_array)){
                    $cantidad_tareas = count($cantidad_tareas_array);                             
                    $color="#81DAF5";
                } else {
                    $cantidad_tareas = 0;  
                    $color="green";
                }
                
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
                    
                    echo '<script>';
                    echo 'Notificar_Tarea(\''.$reporte[$r]['tareas'].'\',\''.$reporte[$r]['id_tareas'].'\')';
                    echo '</script>';
                    
                    
                    
                    $update = 'UPDATE tbl_tareas SET notificado = \'1\'                         
                        WHERE id_tareas = '.$reporte[$r]['id_tareas'].';';                  
                    $resultado = ejecutar($update,$conexion);                     
                    
                    $r++;
                }                
                
                
                //if($_GET['no_mostrar'] == 'si')
                echo 'TASKS (<font color="'.$color.'"><b>'.$cantidad_tareas.'</b></font>)';
                