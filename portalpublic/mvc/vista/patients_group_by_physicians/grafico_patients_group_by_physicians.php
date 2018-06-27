
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
                $pcp = $_GET["pcp"];
                $typeAge = $_GET["typeAge"];

 if(isset($_GET['typeAge'])){
    $typeAge = $_GET['typeAge'];    
}

$having = "";
if($_GET['typeAge'] == 'adults'){
 $having = "and id_seguros_type_person=2 ";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
       $having = "and id_seguros_type_person=1 ";
    }
        if($_GET['typeAge'] == 'all'){
        $having = null;
                 }
}

if(isset($_GET["pcp"]) && $_GET["pcp"] != null ){
                $insurance = $_GET["pcp"];

                    $having3 = "and PCP = '".$_GET["pcp"]."'";
                                
                }else{

                    
                    $having3 = null;
                  }



             echo $query_usuario = "SELECT ROUND((COUNT(*) / (SELECT COUNT(*) FROM patients WHERE active='1' and PCP!='')) * 100,1) as cantidad,physician.Organization as valor 
                FROM patients   
                JOIN physician ON patients.PCP_NPI=physician.NPI

                WHERE true  and active='1' and pcp!='' ".$having3."
                 ".$having." GROUP BY valor order by cantidad desc";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detallepatients_group_by_physicians(valor,typeAge,pcp){
                          
                        var valor_n = replaceAll(valor,' ','%20');

                            $("#detalle_patients_group_by_physicians").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_patients_group_by_physicians").load("../patients_group_by_physicians/result_patients_group_by_physicians.php?&valor="+valor_n+"&pcp="+pcp+"&typeAge="+typeAge);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart style='align:left; margin: 0 auto' caption = 'Click to see Detalied' xAxisName='CANTIDAD IN  PERCENTAGE' formatNumberScale='0'  baseFontSize='14' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetallepatients_group_by_physicians[$i] = urlencode("\"javascript:Detallepatients_group_by_physicians('".$reporte[$i]["valor"]."','".$_GET["typeAge"]."','".$_GET["pcp"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetallepatients_group_by_physicians[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 1300, 400, false); ?>
<?php
echo '<div id="detalle_patients_group_by_physicians"></div>';


                  ?>

