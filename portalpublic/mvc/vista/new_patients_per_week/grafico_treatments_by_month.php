
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
                $Discipline = $_GET["Discipline"];
                $type = $_GET["type"];
                $type = $_GET["typeAge"];


                 $calendar = $_GET["calendar"];
                 $start_date = $_GET["start_date"];
                 $end_date = $_GET["end_date"];
               

               
                if(isset($_GET['calendar'])){
                $calendar = $_GET['calendar'];    
                }

              $having5 = "";
              if($_GET['calendar'] == 'weeks'){
               $having5 = "WEEK(admision_date)";
              }else{
              if($_GET['calendar'] == 'months'){
             $having5 = "MONTHNAME(admision_date)";
               }
            if($_GET['calendar'] == 'days'){
           $having5 = "date(admision_date)";
                 }
              }



                  
            //    if(isset($_GET["Discipline"]) && $_GET["Discipline"] != null){
                
              //      $having = "and campo_10 = '".$_GET["Discipline"]."'";
                                
              //  }else{
                    
                //    $having = null;
                    
              //  }
                
              //  if(isset($_GET["type"]) && $_GET["type"] != null){
               // 
                //    $having2 = "and type='".$_GET["type"]."'";                
                
               // }else{
                    
               //     $having2 = null;
                    
              //  }


                if(isset($_GET['typeAge'])){
    $typeAge = $_GET['typeAge'];    
}

$having = "";
if($_GET['typeAge'] == 'adults'){
 $having = "and id_seguros_type_person= 2";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
       $having = "and id_seguros_type_person = 1";
    }
        if($_GET['typeAge'] == 'all'){
        $having = null;
                 }
}


              $query_usuario = "select  count(distinct Pat_id) as cantidad,
 
".$having5." as valor

 from patients
 

where  admision_date!='0000-00-00' ".$having."
and admision_date>= str_to_date('".$start_date."','%m/%d/%Y') and admision_date<=str_to_date('".$end_date."','%m/%d/%Y')

group by valor  
order by valor asc
";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detalletreatments_by_month(valor,Discipline,typeAge,calendar,start_date,end_date){
                          
                        Discipline = replaceAll(Discipline,' ','|');
                        typeAge = replaceAll(typeAge,' ','|');
                        valor = replaceAll(valor,' ','|');
                        calendar = replaceAll(calendar,' ','|');
                        start_date = replaceAll(start_date,' ','|');
                        end_date = replaceAll(end_date,' ','|');

                            $("#detalle_treatments_by_month").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
  $("#detalle_treatments_by_month").load("../new_patients_per_week/result_treatments_by_month.php?&Discipline="+Discipline+"&typeAge="+typeAge+"&valor="+valor+"&calendar="+calendar+"&start_date="+start_date+"&end_date="+end_date);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalletreatments_by_month[$i] = urlencode("\"javascript:Detalletreatments_by_month('".$reporte[$i]["valor"]."','".$_GET["Discipline"]."','".$_GET["typeAge"]."','".$_GET["calendar"]."','".$_GET["start_date"]."','".$_GET["end_date"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalletreatments_by_month[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_treatments_by_month"></div>';


                  ?>

