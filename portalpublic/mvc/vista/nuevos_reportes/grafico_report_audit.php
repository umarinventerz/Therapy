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

 $tipo_grafico = $_GET["tipo_grafico"];
 $calendar = $_GET["calendar"];
 $start_date = $_GET["start_date"];
 $end_date = $_GET["end_date"];

if(isset($_GET['calendar'])){

    $calendar = $_GET['calendar'];    
}
$having4 = "";
if($_GET['calendar'] == 'weeks'){
   $having4 = "WEEK(created_at)";
}else{
     if($_GET['calendar'] == 'months'){
        $having4 = "MONTHNAME(created_at)";
    }
    if($_GET['calendar'] == 'days'){
        $having4 = "date(created_at)";
    }
}
switch ($_GET['type_report']){
    
    case 'report_amount':
        $consulta=6;
    break;
    
    case 'referral':
        $consulta=1;
    break;

    case 'patients':
        $consulta=2;
    break;

    case 'authorizations':
        $consulta=3;
    break;

    case 'signed_doctor':
        $consulta=4;
    break;

    case 'prescriptions':
        $consulta=5;
    break;
    
    case 'all':
        $consulta=7;
    break;
}


if($consulta!=7){
    
    $query_usuario = "SELECT  G.type,G.user_id,count(G.id) as cantidad, ".$having4." as valor, CONCAT(U.Last_name,', ',U.First_name) AS full_name
                      FROM tbl_audit_generales G LEFT JOIN user_system U on(G.user_id=U.user_id)
                      WHERE true and G.type='".$consulta."'
                      and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y')
                      GROUP BY valor
                      order by  G.created_at desc
                      ";
}elseif($consulta==7){
    
   $query_usuario = "SELECT  G.type,G.user_id,count(G.id) as cantidad, ".$having4." as valor, CONCAT(U.Last_name,', ',U.First_name) AS full_name
                      FROM tbl_audit_generales G LEFT JOIN user_system U on(G.user_id=U.user_id)
                      WHERE true
                      and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y')
                      GROUP BY valor#,G.type,G.user_id
                      order by  G.created_at desc
                      ";
    
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


    /*function Detallereport_audit(valor,calendar,start_date,end_date){
        var valor = replaceAll(valor,' ','|');
        $("#detalle_report_audit").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
        $("#detalle_report_audit").load("../nuevos_reportes/result_report_audit.php?&type_report=0&valor="+valor+"&calendar="+calendar+"&start_date="+start_date+"&end_date="+end_date);
    }*/
    function DetalleGeneralAudit(valor,calendar,start_date,end_date,consulta,type){
        
        $("#detalle_report_audit").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
        $("#detalle_report_audit").load("../nuevos_reportes/result_report_audit.php?&consulta="+consulta+"&type_report="+type+"&valor="+valor+"&calendar="+calendar+"&start_date="+start_date+"&end_date="+end_date);
    }

</script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])){
    
        
        $linkDetallereport_audit[$i] = urlencode("\"javascript:DetalleGeneralAudit('".$reporte[$i]["valor"]."','".$_GET["calendar"]."','".$_GET["start_date"]."','".$_GET["end_date"]."','".$consulta."','".$reporte[$i]["type"]."');\"");
        $strXML .= "<set label ='".$reporte[$i]["valor"]."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetallereport_audit[$i]." />";
   
    $i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_report_audit"></div>';


                  ?>

