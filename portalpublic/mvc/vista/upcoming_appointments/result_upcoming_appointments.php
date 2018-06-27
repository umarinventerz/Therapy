
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
                               
                $valor = $_GET["valor"];
                
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

                
                    $having3 = "and CONCAT(trim(user_last_name),',',trim(user_first_name)) = '".$_GET["therapist"]."'";
                                
                }else{
                    
                    $having3 = null;
                  }

      /////////////////////////////////

                  if(isset($_GET["pending_authorizations"]) && $_GET["pending_authorizations"] != null){
                $pending_authorizations = $_GET["pending_authorizations"];

                    $having4 = "and Type = '".$_GET["pending_authorizations"]."' and Type not like 'Not Available%'";
                                
                }else{
                    
                    $having4 = "and Type not like  'Pending%' and Type not like 'Not Available%'";
                  }




               $query_usuario = "SELECT * FROM appointments_cs WHERE ".$having."= '$valor' 
                and patient_last_name!='null' and location!='null' and  user_first_name!='null' 
                ".$having3." ".$having4."
                and str_to_date(date,'%m/%d/%Y')>= str_to_date('".$start_date."','%m/%d/%Y') and str_to_date(date,'%m/%d/%Y')<=str_to_date('".$end_date."','%m/%d/%Y')
                  order by STR_TO_DATE(date,'%m/%d/%Y') asc";
                    
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

    <table id="tabla_grafico" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
                
