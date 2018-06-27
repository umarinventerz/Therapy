
             <?php
               session_start();
               require_once("../../../conex.php");
               require_once("../../modelo/FusionCharts.php");
               if(!isset($_SESSION['user_id'])){
                       echo "<script>alert('Must LOG IN First')</script>";
                       echo "<script>window.location='../../../../index.php';</script>";
               }else{
                       if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
                               echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
                               echo "<script>window.location='../../../home/home.php';</script>";
                       }
               }
               
                $tipo_grafico = $_GET["tipo_grafico"];
                
                $conexion = conectar();

                $tipo_grafico = $_GET["tipo_grafico"];
                $Discipline = $_GET["Discipline"];
                $type = $_GET["type"];
                $typeAge = $_GET["typeAge"];
                $calendar = $_GET["calendar"];
                 $start_date = $_GET["start_date"];
                 $end_date = $_GET["end_date"];

                  
                if(isset($_GET["Discipline"]) && $_GET["Discipline"] != null){
                
                    $having = "and campo_10 = '".$_GET["Discipline"]."'";
                                
                }else{
                    
                    $having = null;
                    
                }
                
                if(isset($_GET["type"]) && $_GET["type"] != null){
                
                    $having2 = "and type='".$_GET["type"]."'";                
                
                }else{
                    
                    $having2 = null;
                    
                }


                if(isset($_GET['typeAge'])){
                $typeAge = $_GET['typeAge'];    
                }

              $having3 = "";
              if($_GET['typeAge'] == 'adults'){
               $having3 = "and id_seguros_type_person=2";
              }else{
              if($_GET['typeAge'] == 'pedriatics'){
             $having3 = "and id_seguros_type_person=1";
               }
            if($_GET['typeAge'] == 'all'){
           $having3 = null;
                 }
              }


               if(isset($_GET['calendar'])){
                $calendar = $_GET['calendar'];    
                }

              $having4 = "";
              if($_GET['calendar'] == 'weeks'){
               $having4 = "WEEK(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y'),'%a %m-%d-%Y')";
              }else{
              if($_GET['calendar'] == 'months'){
             $having4 = "MONTHNAME(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y'))";
               }
            if($_GET['calendar'] == 'days'){
           $having4 = "date(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y'))";
                 }
              }






       $query_usuario = "SELECT count(distinct campo_3) as cantidad,
                ".$having4." as valor 
                FROM tbl_treatments 
                join patients on patients.Pat_id=tbl_treatments.campo_5
                left join cpt on tbl_treatments.campo_11=cpt.cpt 
                where true 
                ".$having." ".$having2." ".$having3." 
     and str_to_date(tbl_treatments.campo_1,'%m/%d/%Y')>= str_to_date('".$start_date."','%m/%d/%Y') and str_to_date(tbl_treatments.campo_1,'%m/%d/%Y')<=str_to_date('".$end_date."','%m/%d/%Y')
                GROUP BY valor
                order by  STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y') desc
                #limit 15
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


                   function Detalletreatments_by_day_(valor,Discipline,type,typeAge,calendar,start_date,end_date){
                          
                        Discipline = replaceAll(Discipline,' ','|');
                        type = replaceAll(type,' ','|');
                        typeAge = replaceAll(typeAge,' ','|');
                        valor = replaceAll(valor,' ','|');

                      $("#detalle_treatments_by_day_").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                      $("#detalle_treatments_by_day_").load("../treatments_by_day_/result_treatments_by_day_.php?&typeAge="+typeAge+"&Discipline="+Discipline+"&type="+type+"&valor="+valor+"&calendar="+calendar+"&start_date="+start_date+"&end_date="+end_date);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
  $linkDetalletreatments_by_day_[$i] = urlencode("\"javascript:Detalletreatments_by_day_('".$reporte[$i]["valor"]."','".$_GET["Discipline"]."','".$_GET["type"]."','".$_GET["typeAge"]."','".$_GET["calendar"]."','".$_GET["start_date"]."','".$_GET["end_date"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalletreatments_by_day_[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 1000, 400, false); ?>
<?php
echo '<div id="detalle_treatments_by_day_"></div>';


                  ?>

