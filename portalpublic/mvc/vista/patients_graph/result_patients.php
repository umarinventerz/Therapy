
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
                               
                $valor = $_GET["valor"];
                
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
                


$query_usuario = "SELECT  * FROM seguros WHERE  ID = '".$_GET['insurance']."' ";
    $resultado = ejecutar($query_usuario,$conexion);

    $reporte = array();

    $i = 0;
    while($datos = mysqli_fetch_assoc($resultado)) {
        $reporte[$i] = $datos;
        $i++;
    }


    $insurrance1=$reporte[0]["insurance"];


$insurance = $_GET["insurance"];

                    $having3 = "and Pri_Ins = '".$insurrance1."'";
                                
                }else{
                    
                    $having3 = null;
                  }



$query_usuario = "SELECT  * FROM patients WHERE true  ".$having1." ".$having." ".$having3."";
                    
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
 
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
<script>
                $(document).ready(function() {
                        $('#tabla_grafico').DataTable({
                           
                                dom: 'Bfrtip',
                                "scrollX": true,
                                buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                ]
                        } );
                } );
                
$('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

</script>
    
        <div id="bajar_aqui"></div>
        <div class="row">
            <div class="col-lg-12 text-center"><b><h4>DETALLE DEL GRAFICO</h4></b></div>    
        </div>
<br>

            <div class="col-lg-12">

    <table id="tabla_grafico" class="table" cellspacing="0" width="%90">
            <thead>
                <tr>
                         <?php
                         
                         $s=0;
                         while(isset($reporte_clave[$s])){
                             
                             echo '<th>'.$reporte_clave[$s].'</th>';
                                                          
                             $s++;
                         }
                         
                         ?>
                            
                            
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php


                        

$i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {

                            echo $color;
                            
                                $r=0;
                                while(isset($reporte_clave[$r])){
                                   
                                    echo '<td>'.$reporte[$i][$reporte_clave[$r]].'</td>';
                                    
                                    $r++;
                                }
                            
                            
                            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $i++;
                        }
                        ?>
            <tbody/>
            </table> 

</div>
                
