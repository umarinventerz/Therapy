                                          
<?php
/*
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['id_usuario'])){
    header("Location: http://10.100.1.250/KIDWORKS/index.php?&session=finalizada", true);
    
}
*/

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../home/home.php";</script>';
    }
}


                if(isset($_GET["Name"]) && $_GET["Name"] != null){ $Name = " and user_id = '".$_GET["Name"]."' "; } else { $Name = null; }
                if(isset($_GET["user_id"]) && $_GET["user_id"] != null){ $user_id = "user_id = ''".strtolower($_GET["user_id"]."''"); } else { $user_id = 'true'; }
            if(isset($_GET["Last_name"]) && $_GET["Last_name"] != null){ $Last_name = "Last_name = ''".strtolower($_GET["Last_name"]."''"); } else { $Last_name = 'true'; }
            if(isset($_GET["First_name"]) && $_GET["First_name"] != null){ $First_name = "First_name = ''".strtolower($_GET["First_name"]."''"); } else { $First_name = 'true'; }
            if(isset($_GET["user_name"]) && $_GET["user_name"] != null){ $user_name = "user_name = ''".strtolower($_GET["user_name"]."''"); } else { $user_name = 'true'; }
            if(isset($_GET["password"]) && $_GET["password"] != null){ $password = "password = ''".strtolower($_GET["password"]."''"); } else { $password = 'true'; }
            if(isset($_GET["status_id"]) && $_GET["status_id"] != null){ $status_id = "status_id = ''".strtolower($_GET["status_id"]."''"); } else { $status_id = 'true'; }
            if(isset($_GET["User_type"]) && $_GET["User_type"] != null){ $User_type = "User_type = ''".strtolower($_GET["User_type"]."''"); } else { $User_type = 'true'; }
             
            $conexion = conectar();
                 $joins = '';
            
            $where = ' WHERE  '.$user_id." ".$Name." and ".$Last_name." and ".$First_name." and ".$user_name." and ".$password." and ".$status_id." and ".$User_type.'';

             $sql  = "SELECT * FROM user_system user".$joins.$where;

            $resultado = ejecutar($sql,$conexion);

            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
                
                
?>
    <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>  
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

        
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


                                    
                function Modificar_user_system(user_id,Last_name,First_name,user_name,password,status_id,User_type){
                        Last_name = replaceAll(Last_name,' ','|');
                            First_name = replaceAll(First_name,' ','|');
                            user_name = replaceAll(user_name,' ','|');
                            password = replaceAll(password,' ','|');
                            status_id = replaceAll(status_id,' ','|');
                            User_type = replaceAll(User_type,' ','|');
                            
                    
                window.location.href = "../user_system/registrar_user_system.php?&user_id="+user_id+"&Last_name="+Last_name+"&First_name="+First_name+"&user_name="+user_name+"&password="+password+"&status_id="+status_id+"&User_type="+User_type;   
             
                }
    

        function Eliminar_user_system(user_id,incrementador,accion){
                    
                                        swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+user_id+"?",
          type: "warning",
          showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Eliminar",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                 
                
                   var campos_formulario = '&user_id='+user_id+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/user_system/gestionar_user_system.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+user_id+' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                                    
                                },
                                "json" 
                                ); 
            
          }
        });
                    
                                                                            
                }
                    
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
                        <th style="width:10px"; >USER ID</th>
            <th>LAST NAME</th>
            <th>FIRST NAME</th>
            <th>USER NAME</th>
            <th>PASSWORD</th>
            <th>STATUS ID</th>
            <th>USER TYPE</th>
            <th>ACCIÓN</th>
                 
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {
                        
                          
                    echo $color;
                            
 echo '<td align="center"><font size="2"><b>'.$reporte[$i]['user_id'].'</b></font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['Last_name'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['First_name'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['user_name'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['password'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['status_id'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['User_type'].'</font></td>';
             echo '<td align="center"><font size="2"><div id="resultado_'.$i.'"><a onclick="Modificar_user_system(\''.$reporte[$i]['user_id'].'\',\''.$reporte[$i]['Last_name'].'\',\''.$reporte[$i]['First_name'].'\',\''.$reporte[$i]['user_name'].'\',\''.$reporte[$i]['password'].'\',\''.$reporte[$i]['status_id'].'\',\''.$reporte[$i]['User_type'].'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modificar User System"  title="Modificar User System" width="25" height="25" border="0" align="absmiddle"></a><br><br><a onclick="Eliminar_user_system(\''.$reporte[$i]['user_id'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar User System"  title="Eliminar User System" width="25" height="25" border="0" align="absmiddle"></a></div></font></td>';
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

