
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




                $query_1="set @tot:=(select count(distinct patients.Pat_id) from patients where active=1 and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18);";

                $resultado1 = ejecutar($query_1,$conexion);

                $query_usuario = " #set @tot:=(select count(distinct patients.Pat_id) from patients where active=1 and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18);
          
select  
t.week AS valor, #t.expires,  
(@tot := @tot - t.expires) AS cantidad

 
 from    (select  count(distinct Patient_ID) as expires,
 
 week(careplans.POC_due) as week

 from careplans
 join patients on careplans.Patient_ID=patients.Pat_id
          and patients.active=1

where  careplans.POC_due>=date(now())
and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18

group by week(careplans.POC_due)
order by  careplans.POC_due asc
limit 12
) as t";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detalleactive_adults_(valor){
                          
                        var valor_n = replaceAll(valor,' ','|');

                            $("#detalle_active_adults_").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_active_adults_").load("../active_adults_/result_active_adults_.php?&valor="+valor_n);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalleactive_adults_[$i] = urlencode("\"javascript:Detalleactive_adults_('".$reporte[$i]["valor"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalleactive_adults_[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_active_adults_"></div>';


                  ?>

