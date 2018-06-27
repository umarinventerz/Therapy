
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
               
                $tipo_grafico = $_GET["tipo_grafico"];
                
                $conexion = conectar();

                $tipo_grafico = $_GET["tipo_grafico"];
                $calendar = $_GET["calendar"];
                 $start_date = $_GET["start_date"];
                 $end_date = $_GET["end_date"];
                  $therapist = $_GET["therapist"];
                  $pending_authorizations = $_GET["pending_authorizations"];

               

                  
                if(isset($_GET['calendar'])){
                $calendar = $_GET['calendar'];    
                }

           
              $having = "";
              if($_GET['calendar'] == 'weeks'){
               $having = "WEEK(STR_TO_DATE(date,'%m/%d/%Y'),'%a %m-%d-%Y')";
              }else{
              if($_GET['calendar'] == 'months'){
             $having = "MONTHNAME(STR_TO_DATE(date,'%m/%d/%Y'))";
               }
            if($_GET['calendar'] == 'days'){
           $having = "date(STR_TO_DATE(date,'%m/%d/%Y'))";
                 }
              }


              ///////////////////////////
  if(isset($_GET["therapist"]) && $_GET["therapist"] != null){
                $therapist = $_GET["therapist"];

                    $having3 = "and CONCAT(trim(user_last_name),',',trim(user_first_name)) = '".$_GET["therapist"]."'";
                                
                }else{
                    
                    $having3 = null;
                  }

                  /////////////////////////////////

                  if(isset($_GET["pending_authorizations"]) && $_GET["pending_authorizations"] != null){
                $pending_authorizations = $_GET["pending_authorizations"];

                    $having4 = "and Type = '".$_GET["pending_authorizations"]."' and Type not like 'Not Available%' ";
                                
                }else{
                    
                    $having4 = "and Type not like  'Pending%' and Type not like 'Not Available%' ";
                  }





         $query_usuario = "SELECT  count(*) as cantidad,  ".$having." as valor 
                FROM appointments_cs  
                WHERE patient_last_name!='null' and location!='null' and  user_first_name!='null' 
                ".$having3." ".$having4."
                and str_to_date(date,'%m/%d/%Y')>= str_to_date('".$start_date."','%m/%d/%Y') and str_to_date(date,'%m/%d/%Y')<=str_to_date('".$end_date."','%m/%d/%Y')

                GROUP BY valor  order by STR_TO_DATE(date,'%m/%d/%Y') asc  ";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detalleupcoming_appointments(valor,calendar,start_date,end_date,therapist,pending_authorizations){
                          
                        var valor_n = replaceAll(valor,' ','|');
                        calendar = replaceAll(calendar,' ','|');
                        start_date = replaceAll(start_date,' ','|');
                        end_date = replaceAll(end_date,' ','|');
                        therapist = replaceAll(therapist,' ','|');
                        pending_authorizations = replaceAll(pending_authorizations,' ','%20');

                            $("#detalle_upcoming_appointments").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
         $("#detalle_upcoming_appointments").load("../upcoming_appointments/result_upcoming_appointments.php?&valor="+valor_n+"&calendar="+calendar+"&start_date="+start_date+"&end_date="+end_date+"&therapist="+therapist+"&pending_authorizations="+pending_authorizations);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalleupcoming_appointments[$i] = urlencode("\"javascript:Detalleupcoming_appointments('".$reporte[$i]["valor"]."','".$_GET["calendar"]."','".$_GET["start_date"]."','".$_GET["end_date"]."','".$_GET["therapist"]."','".$_GET["pending_authorizations"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalleupcoming_appointments[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 950, 400, false); ?>
<?php
echo '<div id="detalle_upcoming_appointments"></div>';


                  ?>

