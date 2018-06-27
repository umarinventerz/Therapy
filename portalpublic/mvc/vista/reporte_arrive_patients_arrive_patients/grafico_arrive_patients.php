
         <?php
               session_start();
               require_once("../../../conex.php");
               require_once("../../modelo/FusionCharts.php");
               if(!isset($_SESSION['user_id'])){
                       echo "<script>alert('Must LOG IN First')</script>";
                       echo "<script>window.location='../../../../index.php';</script>";
               }else{
                       if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
                               echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
                               echo "<script>window.location='../../../home/home.php';</script>";
                       }
               }
                    
                $tipo_grafico = $_GET["tipo_grafico"];
                $start_date = $_GET["start_date"];
                
                $conexion = conectar();
                          
                $joins = '';
                
              $query_usuario = "SELECT count(id_arrive_patients) as cantidad,date_today as valor  
                FROM tbl_arrive_patients 
                WHERE date_today = str_to_date('".$start_date."','%m/%d/%Y')
                 GROUP BY valor;";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                } ?>
                
                <script type="text/javascript" language="javascript" src="../../../js/funciones.js"></script>
                <script type="text/javascript" language="javascript">


                   function Detallearrivepatients(valor,start_date){
                          
                    valor = replaceAll(valor,' ','|'); 


                            $("#detalle_arrive_patients").empty().html('<img src="../../../imagenes/loader.gif" width="30" height="30"/>');
                            $("#detalle_arrive_patients").load("../reporte_arrive_patients_arrive_patients/result_arrive_patients.php?&valor="+valor+"&start_date="+start_date);
                        
                    }

                </script>                
                
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD DE ARRIVE PATIENTS' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalleARRIVEPATIENTS[$i] = urlencode("\"javascript:Detallearrivepatients('".$reporte[$i]["valor"]."','".$_GET["start_date"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalleARRIVEPATIENTS[$i]." />";   
              
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false);

echo '<div id="detalle_arrive_patients"></div>';


                  ?>

