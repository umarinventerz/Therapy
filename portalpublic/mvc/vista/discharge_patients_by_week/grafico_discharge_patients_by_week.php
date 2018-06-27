
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
                               echo "<script>window.location='../../home/home.php';</script>";
                       }
               }
               
               
                
                $conexion = conectar();

                $tipo_grafico = $_GET["tipo_grafico"];
                $Discipline = $_GET["Discipline"];

                $alendar = $_GET["calendar"];
                $start_date = $_GET["start_date"];
                $end_date = $_GET["end_date"];





               if(isset($_GET['calendar'])){
                $calendar = $_GET['calendar'];    
                }

              $having4 = "";
              if($_GET['calendar'] == 'weeks'){
               $having4 = "WEEK(date)";
              }else{
              if($_GET['calendar'] == 'months'){
             $having4 = "MONTHNAME(date)";
               }
            if($_GET['calendar'] == 'days'){
           $having4 = "date(date)";
                 }
              }




                if(isset($_GET["Discipline"]) && $_GET["Discipline"] != null){
                
                    $having = "AND discipline = '".$_GET["Discipline"]."'";
                                
                }else{
                    
                    $having = null;
                    
                }


           $query_usuario = "SELECT count(t.patient_id) as cantidad, ".$having4." as valor FROM tbl_audit_discharge_patient t 
                    WHERE true ".$having."  
                    and date(date)>= str_to_date('".$start_date."','%m/%d/%Y') and date(date)<=str_to_date('".$end_date."','%m/%d/%Y')
                    GROUP BY valor";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detalledischarge_patients_by_week(valor,Discipline,calendar,start_date,end_date){


                         Discipline = replaceAll(Discipline,' ','|');      
                         valor = replaceAll(valor,' ','|');

                            $("#detalle_discharge_patients_by_week").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_discharge_patients_by_week").load("../discharge_patients_by_week/result_discharge_patients_by_week.php?&Discipline="+Discipline+"&valor="+valor+"&calendar="+calendar+"&start_date="+start_date+"&end_date="+end_date);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalledischarge_patients_by_week[$i] = urlencode("\"javascript:Detalledischarge_patients_by_week('".$reporte[$i]["valor"]."','".$_GET["Discipline"]."','".$_GET["calendar"]."','".$_GET["start_date"]."','".$_GET["end_date"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalledischarge_patients_by_week[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_discharge_patients_by_week"></div>';


                  ?>

