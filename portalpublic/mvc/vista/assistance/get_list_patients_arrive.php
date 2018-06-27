                                          
<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
      
            $conexion = conectar();

            $sql  = "SELECT * FROM tbl_arrive_patients WHERE (notified = 0 OR notified IS NULL) AND date_today = CURDATE() ORDER BY date_hour ASC Limit 1;";
            
            $resultado = ejecutar($sql,$conexion);

            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
            
            if(isset($reporte[0]['patient_name'])){
                
               $sqlUpdate  = "UPDATE tbl_arrive_patients SET notified = 1 WHERE patient_id = ".$reporte[0]['patient_id']." AND date_today = CURDATE();";            
               ejecutar($sqlUpdate,$conexion);
                
                $json_resultado['patient_name'] = $reporte[0]['patient_name'];
                $json_resultado['notify'] = 'yes';
            }else{
                $json_resultado['notify'] = 'no';
            }
            
            echo json_encode($json_resultado);
?> 