
             <?php
               session_start();
               require_once("../../../conex.php");
               require_once("../../modelo/FusionCharts.php");
               if(!isset($_SESSION['user_id'])){
                       echo "<script>alert('Must LOG IN First')</script>";
                       echo "<script>window.location='../../../index.php';</script>";
               }else{
                       if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
                               echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
                               echo "<script>window.location='home.php';</script>";
                       }
               }
               
               
               $conexion = conectar();
               
                $admin = $_GET["admin"];
                $user_id = $_GET["user_id"];
                
                $fecha_inicio = null;
                $fecha_fin = null;                
                
                if(isset($_GET["user_system"]) && $_GET["user_system"] != null){ $where = " and user_id = '".$_GET["user_system"]."' "; $where_j = '&user_system='.$_GET["user_system"].'"'; } else { $where = null; $where_j = ''; }
                if(isset($_GET["id_tareas"]) && $_GET["id_tareas"] != null){ $id_tareas = " and tr.id_tareas = '".$_GET["id_tareas"]."' "; $id_tareas_s = $_GET["id_tareas"]; } else { $id_tareas = null; $id_tareas_s = $_GET["id_tareas"]; }

                
                $fecha_i = date_create($_GET["start_date"]);
                $fecha_f = date_create($_GET["end_date"]);
    

                if((isset($_GET["start_date"]) && $_GET["start_date"] != null) && (isset($_GET["end_date"]) && $_GET["end_date"] != null)){


                   $fechas_inicio_between = ' AND date_format(fecha_inicio,\'%Y-%m-%d\') BETWEEN \''.date_format($fecha_i,'Y-m-d').'\' AND \''.date_format($fecha_f,'Y-m-d').'\'';    
                   $fechas_fin_between = ' AND date_format(fecha_fin,\'%Y-%m-%d\') BETWEEN \''.date_format($fecha_i,'Y-m-d').'\' AND \''.date_format($fecha_f,'Y-m-d').'\'';    


                } else {

                    if(isset($_GET["start_date"]) && $_GET["start_date"] != null){ $fecha_inicio = " and date_format(fecha_inicio,'%Y-%m-%d') = '".date_format($fecha_i,'Y-m-d')."' "; } else { $fecha_inicio = null; }
                    if(isset($_GET["end_date"]) && $_GET["end_date"] != null){ $fecha_fin = " and date_format(fecha_fin,'%Y-%m-%d') = '".date_format($fecha_f,'Y-m-d')."' "; } else { $fecha_fin = null; }

                } 

                if(isset($_GET["id_status_tareas"]) && $_GET["id_status_tareas"] != null && $_GET["id_status_tareas"] != 'null'){ $id_status_tareas = " and tr.id_status_tareas = '".$_GET["id_status_tareas"]."' "; $id_status_tareas_j = '&id_status_tareas='.$_GET["id_status_tareas"].'"'; } else { $id_status_tareas = null; $id_status_tareas_j = ''; }

                
                if($admin != 'si' || ($where != null)){
                    
                    $query_usuario = "select count(status_tareas) as cantidad, status_tareas as valor, id_status_tareas from tbl_tareas tr 
                                        left join tbl_tareas_usuarios using(id_tareas)
                                        left join tbl_tareas_status using(id_status_tareas)
                                        where user_id = ".$user_id." and id_tipo_usuario_tareas = 2 ".$fecha_inicio.$fecha_fin.$id_status_tareas.$id_tareas."
                                        group by valor, id_status_tareas;";
                                        
                } else {
                    
                    $query_usuario = "select count(CONCAT(First_name)) as cantidad, CONCAT(Last_name,' ', First_name) as valor, user_id from tbl_tareas tr 
                                        left join tbl_tareas_usuarios ut using(id_tareas)
                                        left join tbl_tareas_status st using(id_status_tareas)
                                        left join user_system us using(user_id)
                                        where id_tipo_usuario_tareas = 2 ".$where.$fecha_inicio.$fecha_fin.$id_status_tareas.$id_tareas." 
                                        group by valor, user_id;"; 
                    
                }
                
                              
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }
              
                
               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detallereporte_de_prueba(id_status_tareas,user_id,id_tareas){                          
                        $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                        $("#resultado").load("../tareas/result_tareas.php?&id_status_tareas="+id_status_tareas+"&user_system="+user_id+"&id_tareas="+id_tareas);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

if($admin != 'si'){
    $strXML = "<chart caption = 'Status Task' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";
} else {
    $strXML = "<chart caption = 'User Task' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";
}
$i=0; 

while (isset($reporte[$i])) {
    
    
    if(!isset($reporte[0]['user_id'])){
        $linkDetallereporte_de_prueba[$i] = urlencode("\"javascript:Detallereporte_de_prueba('".$reporte[$i]["id_status_tareas"]."','".$user_id."',".$id_tareas_s.");\"");
    } else {
        $linkDetallereporte_de_prueba[$i] = urlencode("\"javascript:Detallereporte_de_prueba('".$reporte[$i]["id_status_tareas"]."','".$reporte[$i]["user_id"]."',".$id_tareas_s.");\"");
    }
    
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetallereporte_de_prueba[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/Pareto3D.swf", "",$strXML, "detalle", 900, 300, false); ?>
<?php
echo '<div id="detalle_reporte_de_prueba"></div>';
        
                  ?>

