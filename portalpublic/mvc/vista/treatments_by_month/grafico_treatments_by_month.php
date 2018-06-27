
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


                $query_usuario = "SELECT count(distinct campo_3) as cantidad,MONTHNAME(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y')) as valor FROM tbl_treatments left join cpt on tbl_treatments.campo_11=cpt.cpt  WHERE year(str_to_date(tbl_treatments.campo_1,'%m/%d/%Y'))='2016' 
".$having." ".$having2." 
GROUP BY valor
order by  STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y') asc
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


                   function Detalletreatments_by_month(valor,Discipline,type){
                          
                        Discipline = replaceAll(Discipline,' ','|');
                        type = replaceAll(type,' ','|');
                        valor = replaceAll(valor,' ','|');

                            $("#detalle_treatments_by_month").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                            $("#detalle_treatments_by_month").load("../treatments_by_month/result_treatments_by_month.php?&Discipline="+Discipline+"&type="+type+"&valor="+valor);
                        
                    }

                </script>                
                   
          <?php echo '<br>';
        
$strXML = "";

$strXML = "<chart caption = 'Haga click en las barras para ver el detalle' xAxisName='CANTIDAD' formatNumberScale='0'  baseFontSize='12' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalletreatments_by_month[$i] = urlencode("\"javascript:Detalletreatments_by_month('".$reporte[$i]["valor"]."','".$_GET["Discipline"]."','".$_GET["type"]."');\"");
    $strXML .= "<set label ='".strtoupper($reporte[$i]["valor"])."' value ='".$reporte[$i]["cantidad"]."' link = ".$linkDetalletreatments_by_month[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>
<?php
echo '<div id="detalle_treatments_by_month"></div>';


                  ?>

