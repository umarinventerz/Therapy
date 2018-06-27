
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


                $query_usuario = "select count(distinct Patient_ID) as cantidad, week(careplans.POC_due) as valor
 from careplans
 join patients on careplans.Patient_ID=patients.Pat_id
          and patients.active=1

where  careplans.POC_due>=date(now())
and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18

group by valor
order by  careplans.POC_due asc
limit 12";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detalleadults_to_be_expired_by_week(valor){
                          
                        var valor_n = replaceAll(valor,' ','|');

                            $("#detalle_adults_to_be_expired_by_week").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_adults_to_be_expired_by_week").load("../adults_to_be_expired_by_week/result_adults_to_be_expired_by_week.php?&valor="+valor_n);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalleadults_to_be_expired_by_week[$i] = urlencode("\"javascript:Detalleadults_to_be_expired_by_week('".$reporte[$i]["valor"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalleadults_to_be_expired_by_week[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_adults_to_be_expired_by_week"></div>';


                  ?>

