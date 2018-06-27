<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
if(isset($_SESSION['name'])){
	$_POST['name'] = trim($_SESSION['name']);
	$_POST['find'] = $_SESSION['find'];
}


$ruta = $_SERVER['DOCUMENT_ROOT']."/KIDWORKS";

$conexion = conectar();

$nombre_reporte = strtolower($_POST['nombre_reporte']);
$nombre_reporte_uder = str_replace(' ','_',strtolower($_POST['nombre_reporte']));
$tabla = strtolower($_POST['tabla']);
$joins = strtolower($_POST['joins']);
$cantidad = $_POST['cantidad'];
$valor = $_POST['valor'];
$nombre_columna = $_POST['nombre_columna'];

if($_POST['where_order_by'] != null) {
$where_order_by = ' WHERE '.strtolower($_POST['where_order_by']); 
} else {
    $where_order_by = null;
}

    $query_principal = 'SELECT '.$cantidad.','.$valor.' FROM '.$tabla.' '.$joins.' '.$where_order_by.' GROUP BY valor';
    $resultado_query = mysqli_query ($conexion, $query_principal); 

if($resultado_query) {
    

    $crear_tabla = 'CREATE  TABLE `kidswork_therapy`.`tbl_menu_reportes` (
      `id_menu_reportes` INT NOT NULL AUTO_INCREMENT ,
      `nombre_reporte` TEXT NULL ,
      `ruta_carpeta` TEXT NULL ,
      PRIMARY KEY (`id_menu_reportes`) );
    ';
        
    mysqli_query ($conexion, $crear_tabla);     

    $insertar_registro = 'INSERT INTO tbl_menu_reportes (nombre_reporte,ruta_carpeta) VALUES (\''.$nombre_reporte.'\',\'../../vista/'.$nombre_reporte_uder.'/filtro_'.$nombre_reporte_uder.'.php\')';        
    ejecutar($insertar_registro, $conexion);
    
    
    
       if (!file_exists($ruta.'/mvc/vista/'.$nombre_reporte_uder)) {        
           mkdir($ruta.'/mvc/vista/'.$nombre_reporte_uder, 01777, false);  
           chmod($ruta.'/mvc/vista/'.$nombre_reporte_uder,01777);  
       }     
       


    $archivo_filtro_reporte = fopen($ruta.'/mvc/vista/'.$nombre_reporte_uder.'/filtro_'.$nombre_reporte_uder.'.php', "w+");            
    
    
        $linea_filtro_reporte = '<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION[\'user_id\'])){
	echo "<script>alert(\'Must LOG IN First\')</script>";
	echo "<script>window.location=\'../../../index.php\';</script>";
}else{
	if($_SESSION[\'user_type\'] == 2 || !isset($_SESSION[\'user_id\'])){
		echo "<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>";
		echo "<script>window.location=\'home.php\';</script>";
	}
}
?>
        
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">    
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    <link href="../../../css/dataTables/dataTables.bootstrap.css"rel="stylesheet" type="text/css">
    <link href="../../../css/dataTables/buttons.dataTables.css"rel="stylesheet" type="text/css">    
    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>    
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/AjaxConn.js" type="text/javascript" ></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>    
    <script src="../../../js/promise.min.js" type="text/javascript"></script>    
    <script src="../../../js/funciones.js" type="text/javascript"></script>        
    <script src="../../../js/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../../../js/resources/shCore.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/buttons.html5.js" type="text/javascript"></script>    
    <script type="text/javascript" language="javascript">
    

       function validar_formulario(){
                var parametros_formulario = $(\'#form_'.$nombre_reporte_uder.'\').serialize();
                     
                $("#resultado").empty().html(\'<img src="../../../images/loader.gif" width="30" height="30"/>\');
                $("#resultado").load("../'.$nombre_reporte_uder.'/grafico_'.$nombre_reporte_uder.'.php?"+parametros_formulario);
            
            return false;
        }
        
        </script>
';
               
$linea_filtro_reporte .= '  
    
    
    <?php $perfil = $_SESSION[\'user_type\']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>


<form id="form_'.$nombre_reporte_uder.'"  onSubmit="return validar_formulario(this);">    ';
    
        $linea_filtro_reporte .= '          
                <div class="row">
                    <div class="col-lg-12">                        
                        <h3 class="page-header">'.ucwords($nombre_reporte).'</h3>                        
                    </div>
                </div> ';
    

$linea_filtro_reporte .= '  
    
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Tipo de Grafico</font></label>
            <div class="col-sm-8">
                <select class="form-control" id="tipo_grafico" name="tipo_grafico" onchange="validar_formulario()">
                    <option value="Pareto3D">Pareto 3D  &nbsp;&nbsp;&nbsp; </option>
                    <option value="Pareto2D">Pareto 2D  &nbsp;&nbsp;&nbsp; </option>
                    <option value="Column3D">Barra 3D  &nbsp;&nbsp;&nbsp; </option>
                    <option value="Column2D">Barra 2D &nbsp;&nbsp;&nbsp; </option>
                    <option value="Doughnut3D">Dona 3D &nbsp;&nbsp;&nbsp; </option>
                    <option value="Doughnut2D">Dona 2D &nbsp;&nbsp;&nbsp; </option>                    
                </select>
                      </div>
        </div>    
        
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>';


$linea_filtro_reporte .= ' 
        <div id="resultado" class="text-center"></div>';
            
        
        
        
       fwrite($archivo_filtro_reporte,$linea_filtro_reporte);      
       fclose($archivo_filtro_reporte);           
       chmod($ruta.'/mvc/vista/'.$nombre_reporte_uder.'/filtro_'.$nombre_reporte_uder.'.php',01777);      

 //GRAFICO 
       
$archivo_grafico = fopen($ruta.'/mvc/vista/'.$nombre_reporte_uder.'/grafico_'.$nombre_reporte_uder.'.php', "w+");

        $linea_grafico = '
             <?php
               session_start();
               require_once("../../../conex.php");
               require_once("../../modelo/FusionCharts.php");
               if(!isset($_SESSION[\'user_id\'])){
                       echo "<script>alert(\'Must LOG IN First\')</script>";
                       echo "<script>window.location=\'../../../index.php\';</script>";
               }else{
                       if($_SESSION[\'user_type\'] == 2 || !isset($_SESSION[\'user_id\'])){
                               echo "<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>";
                               echo "<script>window.location=\'home.php\';</script>";
                       }
               }
               
                $tipo_grafico = $_GET["tipo_grafico"];
                
                $conexion = conectar();


                $query_usuario = "SELECT '.$cantidad.','.$valor.' FROM '.$tabla.' '.$joins.' '.$where_order_by.' GROUP BY valor";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }



               ?>
                     
                <script type="text/javascript" language="javascript">


                   function Detalle'.$nombre_reporte_uder.'(valor){
                          
                        var valor_n = replaceAll(valor,\' \',\'|\');

                            $("#detalle_'.$nombre_reporte_uder.'").empty().html(\'<img src="../../../images/loader.gif" width="30" height="30"/>\');
                            $("#detalle_'.$nombre_reporte_uder.'").load("../'.$nombre_reporte_uder.'/result_'.$nombre_reporte_uder.'.php?&valor="+valor_n);
                        
                    }

                </script>                
';                   

      $linea_grafico .= '                   
          <?php echo \'<br>\';
        
$strXML = "";

$strXML = "<chart caption = \'Haga click en las barras para ver el detalle\' xAxisName=\'CANTIDAD\' formatNumberScale=\'0\'  baseFontSize=\'12\' >";

$i=0; 
while (isset($reporte[$i])) {
    
    $linkDetalle'.$nombre_reporte_uder.'[$i] = urlencode("\"javascript:Detalle'.$nombre_reporte_uder.'(\'".$reporte[$i]["valor"]."\');';
        
        $linea_grafico .= '\"';
            
$linea_grafico .= '");
    $strXML .= "<set label =\'".strtoupper($reporte[$i]["valor"])."\' value =\'".$reporte[$i]["cantidad"]."\' link = ".$linkDetalle'.$nombre_reporte_uder.'[$i]." />";               
    
$i++;
}

$strXML .= "</chart>";

echo renderChartHTML("../tipo_grafico/".$tipo_grafico.".swf", "",$strXML, "detalle", 870, 350, false); ?>';


$linea_grafico .= '
<?php
echo \'<div id="detalle_'.$nombre_reporte_uder.'"></div>\';


                  ?>

';
   
        
       fwrite($archivo_grafico,$linea_grafico);             
       fclose($archivo_grafico);                        
       chmod($ruta.'/mvc/vista/'.$nombre_reporte_uder.'/grafico_'.$nombre_reporte_uder.'.php',01777);      
                 
//RESULT MOVIMIENTOS REPORTE
     
$archivo_result_reporte = fopen($ruta.'/mvc/vista/'.$nombre_reporte_uder.'/result_'.$nombre_reporte_uder.'.php', "w+");

        $linea_result_reporte = '
                      <?php
               session_start();
               require_once("../../../conex.php");
               require_once("../../modelo/FusionCharts.php");
               if(!isset($_SESSION[\'user_id\'])){
                       echo "<script>alert(\'Must LOG IN First\')</script>";
                       echo "<script>window.location=\'../../../index.php\';</script>";
               }else{
                       if($_SESSION[\'user_type\'] == 2 || !isset($_SESSION[\'user_id\'])){
                               echo "<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>";
                               echo "<script>window.location=\'home.php\';</script>";
                       }
               }
                               
                $valor = $_GET["valor"];
                
                $conexion = conectar();


                $query_usuario = "SELECT * FROM '.$tabla.' WHERE '.$nombre_columna.' = \'$valor\'";
                    
                $resultado = ejecutar($query_usuario,$conexion);

                $reporte = array();

                $i = 0;      
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $reporte[$i] = $datos;
                    $i++;
                }
                
                
   $i=0; 
   while (key($reporte[0])) {
    
        $reporte_clave[$i] = key($reporte[0]);     
       
    next($reporte[0]);
    $i++;
   
}                               
               
?> 
';
         
$linea_result_reporte .= ' 
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
<script>
                $(document).ready(function() {
                        $(\'#tabla_grafico\').DataTable({
                                pageLength: 1500,
                                dom: \'Bfrtip\',
                                "scrollX": true,
                                buttons: [
                                        \'copyHtml5\',
                                        \'excelHtml5\',
                                        \'csvHtml5\',
                                        \'pdfHtml5\'
                                ]
                        } );
                } );
                
$(\'html,body\').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

</script>
    
        <div id="bajar_aqui"></div>
        <div class="row">
            <div class="col-lg-12 text-center"><b><h4>DETALLE DEL GRAFICO</h4></b></div>    
        </div>
<br>

            <div class="col-lg-12">

    <table id="tabla_grafico" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                         <?php
                         
                         $s=0;
                         while(isset($reporte_clave[$s])){
                             
                             echo \'<th>\'.$reporte_clave[$s].\'</th>\';
                                                          
                             $s++;
                         }
                         
                         ?>
                            
                            
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = \'<tr class="odd_gradeX">\';
                        while (isset($reporte[$i])) {

                            echo $color;
                            
                                $r=0;
                                while(isset($reporte_clave[$r])){
                                   
                                    echo \'<td>\'.$reporte[$i][$reporte_clave[$r]].\'</td>\';
                                    
                                    $r++;
                                }
                            
                            
                            $color = ($color == \'<tr class="even_gradeC">\' ? \'<tr class="odd_gradeX">\' : \'<tr class="even_gradeC">\');

                            echo \'</tr>\';

                            $i++;
                        }
                        ?>
            <tbody/>
            </table> 

</div>
                
';
   
        
    fwrite($archivo_result_reporte,$linea_result_reporte);      
    chmod($ruta.'/mvc/vista/'.$nombre_reporte_uder.'/result_'.$nombre_reporte_uder.'.php',01777);      
    fclose($archivo_result_reporte);          
    

    
    
    
    $json_resultado['resultado'] = 'generado';
    
    
     
}else {
    
    $json_resultado['resultado'] = 'error_query';
    
    
}

$json_resultado['query'] = 'SELECT '.$cantidad.','.$valor.' FROM '.$tabla.' '.$joins.' '.$where_order_by.' GROUP BY valor';
    
    echo json_encode($json_resultado);
    ?>
    
    

