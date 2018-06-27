                                          
<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
       
            $conexion = conectar();
            $sql  = "SELECT id_arrive_patients,patient_id,UPPER(patient_name) as patient_name , UPPER(physician_name) as physician_name,notified, date_today ,date_hour FROM tbl_arrive_patients WHERE notified = 1 AND date_today = CURDATE() ORDER BY date_hour DESC ";
            
            $resultado = ejecutar($sql,$conexion);

            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
                
                
?>
    <!--<script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>-->        
        
        <script  language="JavaScript" type="text/javascript">
                                      
                $(document).ready(function() {
                         $('#table_kidswork_therapy').dataTable( {                               
                                 dom: 'Bfrtip',
                                 "scrollX": true,
                                
                         } );
                 } );           
    
        </script>
<style>
#table_kidswork_therapy tr td {
   
}
table.display tbody tr:nth-child(even) td{
    background-color: #000099 !important;
    color: #FFFFFF !important;
    font-weight: bold !important;
}
 
table.display tbody tr:nth-child(odd) td {
    background-color: #FFFFFF !important;
    color: #000099 !important;
    font-weight: bold !important;
}
</style>
        
    <div id="bajar_aqui"></div>
       
        


                        <table id="table_kidswork_therapy" class="table table-striped table-bordered display" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>                            
                <th style="text-align: center; background-color: #000000 !important; color: #FFFFFF !important;">P A T I E N T  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  N A M E</th>
                <th style="text-align: center; background-color: #000000 !important; color: #FFFFFF !important;">H O U R &nbsp&nbsp&nbsp&nbsp OF  &nbsp&nbsp&nbsp&nbsp ARRIVED</th>
                <th style="text-align: center; background-color: #000000 !important; color: #FFFFFF !important;">P H Y S I C I A N &nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp N A M E</th>                 
                        </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        //echo recursive_array_search(null,$reporte); die;
                        $valoresNull = 'no';
                        while (isset($reporte[$i])) {                        
                            echo $color;             
                            echo '<td align="center"><font size="6">'.$reporte[$i]['patient_name'].'</font></td>';
                            echo '<td align="center"><font size="6">'.substr($reporte[$i]['date_hour'],11,8).'</font></td>';
                            echo '<td align="center"><font size="6">'.$reporte[$i]['physician_name'].'</font></td>';             
                            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');
                            echo '</tr>';

                            $i++;
                        }
                        
                        ?>

                    </tbody>
                </table>
                   
      
            <div class="spacer"></div>
       
</html>

