
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

                $valor = $_GET["valor"];
                $Discipline = $_GET["Discipline"];


                  $calendar = $_GET["calendar"];
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


                if(isset($_GET["valor"]) && $_GET["valor"] != null){
                
                    $valor = str_replace('|',' ',$_GET["valor"]);
                                
                }



                 if(isset($_GET["Discipline"]) && $_GET["Discipline"] != null){
                
                    $having = "and discipline = '".str_replace('|',' ',$_GET["Discipline"])."'";
                                
                }else{                    
                    $having = null;                    
                }



                $query_usuario = "
              SELECT concat(trim(user_system.Last_name),', ',trim(user_system.First_name)) as User ,date,
 patient_id , concat(trim(patients.Last_name),', ',trim(patients.First_name)) as Patient,
 discipline

 FROM tbl_audit_discharge_patient
 
 join user_system on tbl_audit_discharge_patient.user_id=user_system.user_id
 join patients on tbl_audit_discharge_patient.patient_id=patients.Pat_id
  WHERE ".$having4." = '".$valor."' ".$having."
 and date(date)>= str_to_date('".$start_date."','%m/%d/%Y') and date(date)<=str_to_date('".$end_date."','%m/%d/%Y')
   ";
                    
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
                                pageLength: 1500,
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
                
