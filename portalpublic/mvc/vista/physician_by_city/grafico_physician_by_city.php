
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
               
                $tipo_grafico = $_GET["tipo_grafico"];
                
                $conexion = conectar();


                $query_usuario = "SELECT count(*) as cantidad,City as valor FROM physician   GROUP BY valor";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detallephysician_by_city(valor){
                          
                        var valor_n = replaceAll(valor,' ','%20');

                            $("#detalle_physician_by_city").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_physician_by_city").load("../physician_by_city/result_physician_by_city.php?&valor="+valor_n);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetallephysician_by_city[$i] = urlencode("\"javascript:Detallephysician_by_city('".$reporte[$i]["valor"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetallephysician_by_city[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_physician_by_city"></div>';


                  ?>

