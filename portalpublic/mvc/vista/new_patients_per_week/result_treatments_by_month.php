
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
                

                 $calendar = $_GET["calendar"];
                 $start_date = $_GET["start_date"];
                 $end_date = $_GET["end_date"];
               

                  
                if(isset($_GET['calendar'])){
                $calendar = $_GET['calendar'];    
                }

              $having = "";
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






             //   $Discipline = $_GET["Discipline"];
              //  $type = $_GET["type"];
                
               if(isset($_GET["valor"]) && $_GET["valor"] != null){
                
                  $valor = str_replace('|',' ',$_GET["valor"]);
                                
               }

               // if(isset($_GET["Discipline"]) && $_GET["Discipline"] != null){
                
                  //  $having = "and campo_10 = '".str_replace('|',' ',$_GET["Discipline"])."'";
                                
               // }else{                    
              //      $having = null;                    
              //  }
                
              //  if(isset($_GET["type"]) && $_GET["type"] != null){
                
               //     $having2 = "and type='".str_replace('|',' ',$_GET["type"])."'";                
                
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


              echo $query_usuario = "select * 

 from patients
 

where ".$having5." = '".$valor."' ".$having."
 and admision_date>= str_to_date('".$start_date."','%m/%d/%Y') and admision_date<=str_to_date('".$end_date."','%m/%d/%Y')

";                                                  
                
                
                $resultado_1 = ejecutar($query_usuario,$conexion);

                $reporte_1 = array();

                $r = 0;      
                while($datos_1 = mysqli_fetch_assoc($resultado_1)) {            
                    $reporte_1[$r] = $datos_1;
                    $r++;
                }
                                                            
                
   $r=0; 
   while (key($reporte_1[0])) {
    
        $reporte_clave[$r] = key($reporte_1[0]);     
       
    next($reporte_1[0]);
    $r++;
   
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

    <table id="tabla_grafico" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                         <?php
                         
                                echo '<th>N°</th>';
                         
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
                        while (isset($reporte_1[$i])) {

                            $n = $i+1;
                            
                            echo $color;
                            
                            
                            echo '<td>'.$n.'</td>';
                            
                                $r=0;
                                while(isset($reporte_clave[$r])){
                                   
                                    echo '<td>'.$reporte_1[$i][$reporte_clave[$r]].'</td>';
                                    
                                    $r++;
                                }
                            
                            
                            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $n++;
                            
                            $i++;
                        }
                        ?>
            <tbody/>
            </table> 

</div>
                
