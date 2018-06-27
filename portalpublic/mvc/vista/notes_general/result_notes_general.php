                                          
<?php

session_start();
require_once("../../../conex.php");

if(!isset($_SESSION['user_id'])){ ?>
	<script>alert('MUST LOG IN')</script>
	<script>window.location="../../../index.php";</script>
<?php } 
                
            if(isset($_GET["id_type_person"]) && $_GET["id_type_person"] != null){ $id_type_person = "id_type_person = '".strtolower($_GET["id_type_person"]."'"); } else { $id_type_person = 'true'; }
            if(isset($_GET["id_person"]) && $_GET["id_person"] != null){ $id_person = "id_person = '".$_GET["id_person"]."'"; } else { $id_person = 'true'; }
            if(isset($_GET["id_user"]) && $_GET["id_user"] != null){ $id_user = "id_user = '".$_GET["id_user"]."'"; }else { $id_user = 'true'; }            
            if(isset($_GET["date_notes"]) && $_GET["date_notes"] != null){ $date_notes = "date_notes = '".date("Y-m-d", strtotime($_GET["date_notes"]))."'"; } else { $date_notes = 'true'; }
             
            $conexion = conectar();
                 $joins = ' LEFT JOIN tbl_doc_type_persons dtp ON dtp.id_type_persons = ng.id_type_person '
                         . ' LEFT JOIN user_system us ON us.user_id = ng.id_user ';
           
        // PARA QUE NO TODO EL MUNDO PUEDA VER LAS NOTAS DE LOS EMPLEADOS 
            if($_SESSION['user_type'] >= 10  ){
            $where = " WHERE ".$id_type_person." and ".$id_person." and ".$id_user." and ".$date_notes.'';
        }else{
            $where = " WHERE id_type_person != 2 and ".$id_type_person."  
            AND CASE WHEN id_type_person = 1 THEN id_user='".$_SESSION['user_id']."' ELSE TRUE END  and
            ".$id_person." and ".$id_user." and ".$date_notes.'';
        }
            
            echo $sql  = "SELECT * FROM tbl_notes_general ng".$joins.$where;
       
            $resultado = ejecutar($sql,$conexion);

            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
                
                
?>
    <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>        
        
        <script  language="JavaScript" type="text/javascript">
                                      
                $(document).ready(function() {
                        $('#table_kidswork_therapy').dataTable( {                               
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


                                    
                function Modificar_notes_general(id_notes_general,notes_general,id_type_person,id_person,table_reference,date_notes){
                        notes_general = replaceAll(notes_general,' ','|');
                            id_type_person = replaceAll(id_type_person,' ','|');
                            id_person = replaceAll(id_person,' ','|');
                            table_reference = replaceAll(table_reference,' ','|');
                            date_notes = replaceAll(date_notes,' ','|');
                            
                    
                window.location.href = "../notes_general/registrar_notes_general.php?&id_notes_general="+id_notes_general+"&notes_general="+notes_general+"&id_type_person="+id_type_person+"&id_person="+id_person+"&table_reference="+table_reference+"&date_notes="+date_notes;   
             
                }
                
                function showPatient(name_patient){
                    window.open('../patients/search.php?name='+name_patient,'w','width=1300px,height=1000px,noresize');
                }
                function showPhysician(phy_id){
                    window.open('../physician/search_physician.php?name='+phy_id,'w','width=1300px,height=1000px,noresize');
                }
                function showInsurance(id){
                    window.open('../seguros/search_seguros.php?name='+id,'w','width=1300px,height=1000px,noresize');
                }
                function showEmployee(id){
                    window.open('../employee/search_employee.php?name='+id,'w','width=1300px,height=1000px,noresize');
                }
                function showContacts(id){
                    window.open('../contacts/search_contacts.php?name='+id,'w','width=1300px,height=1000px,noresize');
                }
                function showReferral(id){
                    window.open('../referral/search_referral.php?name='+id,'w','width=1300px,height=1000px,noresize');
                }
    

        function Eliminar_notes_general(id_notes_general,incrementador,accion){
                    
                                        swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+id_notes_general+"?",
          type: "warning",
          showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Eliminar",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                 
                
                   var campos_formulario = '&id_notes_general='+id_notes_general+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/notes_general/gestionar_notes_general.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+id_notes_general+' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                                    setTimeout(function(){
                                        validar_formulario();
                                    },1500);
                                    
                                },
                                "json" 
                                ); 
            
          }
        });
                    
                                                                            
                }
                    

/*modulos_dependientes_j*/

            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 1000);
    
        </script>
        
    <div id="bajar_aqui"></div>
        <br>
            <table align="center" border="0">
                <tr>
                    <td align="center"><b><font size="3">Resultado de la Consulta</font></b></td>
                </tr>
            </table>
        
<div class="col-lg-12">

                        <table id="table_kidswork_therapy" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>    
                        <th style="width:10px"; >ID NOTES GENERAL</th>                        
                        <th>TYPE PERSON</th>
                        <th>PERSON</th>
                        <th>USER</th>
                        <th>NOTES GENERAL</th>
                        <th>DATE NOTES</th>
                        <th>ACCIÓN</th>
                 
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        
                        $ids_table['seguros'][0] = 'ID';
                        $ids_table['seguros'][1] = 'insurance as complete_name';
                        $ids_table['physician'][0] = 'Phy_id';
                        $ids_table['physician'][1] = 'Name as complete_name';
                        $ids_table['employee'][0] = 'id';
                        $ids_table['employee'][1] = "concat(last_name,',',first_name) as complete_name";
                        $ids_table['patients'][0] = 'Pat_id';
                        $ids_table['patients'][1] = "concat(Last_name,',',First_name) as complete_name";
                        $ids_table['tbl_referral'][0] = 'id_referral';
                        $ids_table['tbl_referral'][1] = "concat(Last_name,',',First_name) as complete_name";
                        $ids_table['tbl_contacto_persona'][0] = 'id_persona_contacto';
                        $ids_table['tbl_contacto_persona'][1] = "persona_contacto as complete_name";
                        
                        while (isset($reporte[$i])) {
                        
                          
                    echo $color;
                            
            echo '<td align="center"><font size="2"><b>'.$reporte[$i]['id_notes_general'].'</b></font></td>';             
            echo '<td align="center"><font size="2">'.$reporte[$i]['type_persons'].'</font></td>';
             $sqlPerson = " SELECT *,".$ids_table[$reporte[$i]['table_reference']][1]." FROM ".$reporte[$i]['table_reference']." WHERE ".$ids_table[$reporte[$i]['table_reference']][0]." = '".$reporte[$i]['id_person']."'";
            $resultadoSqlPerson = ejecutar($sqlPerson,$conexion);
            
            $reporteSqlPerson = array();

            $p = 0;      
            while($datos = mysqli_fetch_assoc($resultadoSqlPerson)) {            
               $reporteSqlPerson[$p] = $datos;
               $p++;
            }
            
            if($reporte[$i]['table_reference']=='patients'){
                echo '<td align="center"><font size="2"><a onclick="showPatient(\''.$reporte[$i]['id_person'].'\')">'.$reporteSqlPerson[0]['complete_name'].'</a></font></td>';
            }
            if($reporte[$i]['table_reference']=='physician'){
                echo '<td align="center"><font size="2"><a onclick="showPhysician(\''.$reporte[$i]['id_person'].'\')">'.$reporteSqlPerson[0]['complete_name'].'</a></font></td>';
            }
            if($reporte[$i]['table_reference']=='seguros'){
                echo '<td align="center"><font size="2"><a onclick="showInsurance(\''.$reporte[$i]['id_person'].'\')">'.$reporteSqlPerson[0]['complete_name'].'</a></font></td>';
            }
            if($reporte[$i]['table_reference']=='employee'){
                echo '<td align="center"><font size="2"><a onclick="showEmployee(\''.$reporte[$i]['id_person'].'\')">'.$reporteSqlPerson[0]['complete_name'].'</a></font></td>';
                
            }
            if($reporte[$i]['table_reference']=='tbl_referral'){
                echo '<td align="center"><font size="2"><a onclick="showReferral(\''.$reporte[$i]['id_person'].'\')">'.$reporteSqlPerson[0]['complete_name'].'</a></font></td>';
                
            }
            if($reporte[$i]['table_reference']=='tbl_contacto_persona'){
                echo '<td align="center"><font size="2"><a onclick="showContacts(\''.$reporte[$i]['id_person'].'\')">'.$reporteSqlPerson[0]['complete_name'].'</a></font></td>';
                
            }
            echo '<td align="center"><font size="2">'.strtoupper($reporte[$i]['Last_name'].', '.$reporte[$i]['First_name']).'</font></td>';
            echo '<td align="center"><font size="2">'.$reporte[$i]['notes_general'].'</font></td>';
            echo '<td align="center"><font size="2">'.$reporte[$i]['date_notes'].'</font></td>';
            echo '<td align="center"><font size="2"><div id="resultado_'.$i.'">'
                     //. '<a onclick="Modificar_notes_general(\''.$reporte[$i]['id_notes_general'].'\',\''.$reporte[$i]['notes_general'].'\',\''.$reporte[$i]['id_type_person'].'\',\''.$reporte[$i]['id_person'].'\',\''.$reporte[$i]['table_reference'].'\',\''.$reporte[$i]['date_notes'].'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/save.png" alt="Modificar Notes General"  title="Modificar Notes General" width="25" height="25" border="0" align="absmiddle"></a>'                     
                     . '<a onclick="Eliminar_notes_general(\''.$reporte[$i]['id_notes_general'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar Notes General"  title="Eliminar Notes General" width="25" height="25" border="0" align="absmiddle"></a>
</div></font></td>';
            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $i++;
                        }
                        ?>

                    </tbody>
                </table>
            </div>        
      
               <div class="spacer"></div>
       
</html>

