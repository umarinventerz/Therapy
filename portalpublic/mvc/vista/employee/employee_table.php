
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


        
        $type_salary = $_GET['type_salary'];
        $status_terapist = $_GET['status_terapist'];
        $type_terapist = $_GET['type_terapist'];


////////////////////////

        if(isset($_GET["type_salary"]) && $_GET["type_salary"] != null){
                $type_salary = $_GET["type_salary"];

                    $having1 = "and type_salary = '".$type_salary."' ";
                                
                }else{
                    
                    $having1 = null;
                  }


/////////////////////////

        if(isset($_GET["status_terapist"]) && $_GET["status_terapist"] != null){
                $status_terapist = $_GET["status_terapist"];

                    $having2 = "and status='".$status_terapist."' ";
                                
                }else{
                    
                    $having2 = null;
                  }
        
/////////////////////////

        if(isset($_GET["type_terapist"]) && $_GET["type_terapist"] != null){
                $type_terapist = $_GET["type_terapist"];

                    $having3 = "and kind_employee = '".$type_terapist."'";
                                
                }else{
                    
                    $having3 = null;
                  }




              $query_usuario = " SELECT  CONCAT(TRIM(last_name),',',TRIM(first_name)) as Name,pay_to, kind_employee,dob,hire_date,adress, phone_number,status,type_salary from employee 
      WHERE true ".$having1." ".$having2."  ".$having3."     ";
                    
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
