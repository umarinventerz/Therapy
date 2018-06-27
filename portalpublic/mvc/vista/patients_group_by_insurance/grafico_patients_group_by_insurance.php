
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


                $query_usuario = "SELECT ROUND((COUNT(*) / (SELECT COUNT(*) FROM patients WHERE active='1' and Pri_Ins!='')) * 100,1) as cantidad,Pri_Ins as valor FROM patients   WHERE active='1' and Pri_Ins!='' GROUP BY valor order by cantidad desc";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detallepatients_group_by_insurance(valor){
                          
                        var valor_n = replaceAll(valor,' ','%20');

                            $("#detalle_patients_group_by_insurance").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_patients_group_by_insurance").load("../patients_group_by_insurance/result_patients_group_by_insurance.php?&valor="+valor_n);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Click to see Detailed' xAxisName='CANTIDAD IN PERCENTAGE' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetallepatients_group_by_insurance[$i] = urlencode("\"javascript:Detallepatients_group_by_insurance('".$reporte[$i]["valor"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetallepatients_group_by_insurance[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 1400, 350, false); ?>
<?php
echo '<div id="detalle_patients_group_by_insurance"></div>';


                  ?>

