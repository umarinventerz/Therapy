
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
                $type = $_GET["type"];
                 $typeAge = $_GET["typeAge"];
                 $insurance = $_GET["insurance"];

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

if(isset($_GET['type'])){
    $type = $_GET['type'];    
}

$having1 = "";
if($_GET['type'] == 'active'){
 $having1 = "and active=1 ";
}else{
    if($_GET['type'] == 'inactive'){
       $having1 = "and active=0 ";
    }
        if($_GET['type'] == 'all'){
        $having1 = null;
                 }
}



if(isset($_GET["insurance"]) && $_GET["insurance"] != null){
                $insurance = $_GET["insurance"];

$query_usuario = "SELECT  * FROM seguros WHERE  insurance = '".$_GET['insurance']."' ";
    $resultado = ejecutar($query_usuario,$conexion);

    $reporte = array();

    $i = 0;
    while($datos = mysqli_fetch_assoc($resultado)) {
        $reporte[$i] = $datos;
        $i++;
    }
                        

$having3 = "and Pri_Ins = '".$_GET["insurance"]."'";
                                $insurrance1=count($reporte); 
                }else{
                    
                    $having3 = null;
$insurrance1=$_GET["insurance"];
                  }




             $query_usuario = "SELECT count(distinct Pat_id) as cantidad,'PATIENTS' as valor FROM patients   WHERE true ".$having." ".$having1." ".$having3."  GROUP BY valor";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detallepatients(valor,typeAge,type,insurance){
                          
                        var valor_n = replaceAll(valor,' ','%20');

                            $("#detalle_patients").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_patients").load("../patients_graph/result_patients.php?&valor="+valor_n+"&typeAge="+typeAge+"&type="+type+"&insurance="+insurance);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetallepatients[$i] = urlencode("\"javascript:Detallepatients('".$reporte[$i]["valor"]."','".$_GET["typeAge"]."','".$_GET["type"]."','".$insurrance1."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetallepatients[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_patients"></div>';


                  ?>

