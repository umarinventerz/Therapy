<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}
        

$fecha_inicio = null;
$fecha_fin = null;


if(isset($_GET["user_system"]) && $_GET["user_system"] != null){ $where = " and user_id = '".$_GET["user_system"]."' "; } else { $where = null; }
if(isset($_GET["id_tareas"]) && $_GET["id_tareas"] != null){ $id_tareas = " and tr.id_tareas = '".$_GET["id_tareas"]."' "; } else { $id_tareas = null; }

if(isset($_GET["start_date"])){        
    $fecha_i = date_create($_GET["start_date"]);
} else {
    $fecha_i = null;    
}

if(isset($_GET["end_date"])){    
    $fecha_f = date_create($_GET["end_date"]);    
} else {
    $fecha_f = null;    
}

$fechas_inicio_between = null;
$fechas_fin_between = null;
    
    
    



if((isset($_GET["start_date"]) && $_GET["start_date"] != null) && (isset($_GET["end_date"]) && $_GET["end_date"] != null)){
    

   $fechas_inicio_between = ' AND date_format(fecha_inicio,\'%Y-%m-%d\') BETWEEN \''.date_format($fecha_i,'Y-m-d').'\' AND \''.date_format($fecha_f,'Y-m-d').'\'';    
   $fechas_fin_between = ' AND date_format(fecha_fin,\'%Y-%m-%d\') BETWEEN \''.date_format($fecha_i,'Y-m-d').'\' AND \''.date_format($fecha_f,'Y-m-d').'\'';    
 
    
} else {

    if(isset($_GET["start_date"]) && $_GET["start_date"] != null){ $fecha_inicio = " and date_format(fecha_inicio,'%Y-%m-%d') = '".date_format($fecha_i,'Y-m-d')."' "; } else { $fecha_inicio = null; }
    if(isset($_GET["end_date"]) && $_GET["end_date"] != null){ $fecha_fin = " and date_format(fecha_fin,'%Y-%m-%d') = '".date_format($fecha_f,'Y-m-d')."' "; } else { $fecha_fin = null; }

}


if(isset($_GET["id_status_tareas"]) && $_GET["id_status_tareas"] != null && $_GET["id_status_tareas"] != 'null'){ $id_status_tareas = " and st.status_tareas = '".$_GET["id_status_tareas"]."' "; } else { $id_status_tareas = null; }
   

$conexion = conectar();
$sql  = "SELECT *, fecha_inicio, fecha_fin as fecha_fin FROM tbl_tareas tr
        left join tbl_tareas_usuarios ut using(id_tareas)        
        left join tbl_tareas_tipo_usuario tut using(id_tipo_usuario_tareas)
        left join tbl_tareas_status st on st.id_status_tareas = tr.id_status_tareas
        left join user_system us using(user_id) WHERE ut.id_tipo_usuario_tareas = 2 and TRUE".$where.$fecha_inicio.$fecha_fin.$id_status_tareas.$fechas_inicio_between.$fechas_fin_between.$id_tareas.";"; 

$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}   
                    
?>




        <script  language="JavaScript" type="text/javascript">

                $(document).ready(function() {
                        $('#table_notes').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                            buttons: [
                                'pageLength',
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            "pageLength": 10
                        } );
                } );
                
                function finalizar_tarea(id_tareas,id_status,div){
                    
                    var mensaje;
                    var boton;
                    
                    if(id_status == 2){
                        
                        mensaje = '<h4>Sure you want to end the task number '+id_tareas+'?</h4>';
                        boton  = 'Finish Task!';
                        
                    }
                    
                    if(id_status == 3){
                        
                        mensaje = '<h4>Sure you want to cancel the task number '+id_tareas+'?</h4>';
                        boton  = 'Cancel Task!';
                        
                    }                    
                    
                    swal({
                      title: mensaje,                      
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: boton
                    }).then(function(isConfirm) {
                      if (isConfirm) {
                       
                        $.post(
                                "../../controlador/tareas/modificar_status.php",
                                '&id_tareas='+id_tareas+'&id_status='+id_status+'&status=si&notificacion=no',
                                function (resultado_controlador) {
                                      
                                      var mensaje_vista_p = null;
                                      
                                    if(resultado_controlador.resultado == 'finalizada') {
                                        mensaje_vista_p = 'Task Finished';                                       
                                    }
                                    
                                    if(resultado_controlador.resultado == 'cancelada') {
                                        mensaje_vista_p = 'Task Canceled';                                       
                                    }                                    
                                      
                                      if(resultado_controlador.resultado == 'finalizada' || resultado_controlador.resultado == 'cancelada') {
                                      
                                        swal({
                                          type: 'success',
                                          html: mensaje_vista_p
                                        });                                        
                                      
                                        $('#'+div).html(resultado_controlador.mensaje);                                                                                
                                    
                                      }
                                    
                                },
                                "json" 
                                );
                       
                       
                      }
                    });                                       
                    
                }
                
                function cambiar_status_notificacion(user_id,notificacion){
                    
                        $.post(
                                "../../controlador/tareas/modificar_status.php",
                                '&user_id='+user_id+'&notificacion='+notificacion+'&status=no',
                                function (resultado_controlador) {
                                      
                                      if(resultado_controlador.resultado == 'notificado') {
                                      
                                    
                                      }
                                    
                                },
                                "json" 
                                );                    
                    
                }
                
                
            $(document).ready(function() {
                    cambiar_status_notificacion(<?php echo $_SESSION['user_id']?>,'1');                    
            }); 
                
                
                
                               
            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

        </script>    
    <div id="bajar_aqui"></div>
            <table align="center" border="0">
                <tr>
                    <td align="center"><b><font size="Query Result"></font></b></td>
                </tr>
            </table>
        
<div class="col-lg-12">

    <table id="table_notes" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>

                            <th style="width:10px;" >N° TASK</th>     
                                <th>FROM</th>                          
                                <th>TO</th>                                
                                <th>TASK</th>
                                <th>STATUS</th>
                                <th>START DATE</th>                                
                                <th>END DATE</th>
                                <th>DATES</th>
                                <th>ACTION</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {
  
$sql_fechas  = "SELECT *,fechas_tareas FROM tbl_tareas_fechas left join tbl_tareas_status st using(id_status_tareas) WHERE id_tareas = ".$reporte[$i]['id_tareas'].";";
$resultado_fechas = ejecutar($sql_fechas,$conexion);

$reporte_fechas_tareas = array();

$t = 0;      
while($datos_fechas = mysqli_fetch_assoc($resultado_fechas)) {            
    $reporte_fechas_tareas[$t] = $datos_fechas;
    $t++;
}     
$conexion = conectar();
 $sql_2  = "SELECT *, concat(trim(user_system.Last_name),', ',trim(user_system.First_name)) as user_creator
FROM tbl_tareas_usuarios 
left join user_system on tbl_tareas_usuarios.user_id=user_system.user_id
WHERE id_tareas = '".$reporte[$i]['id_tareas']."'
and id_tipo_usuario_tareas='1'";
 $resultado_2 = ejecutar($sql_2,$conexion);

 $reporte_2 = array();

$r = 0;      
while($datos_2 = mysqli_fetch_assoc($resultado_2)) {            
  $reporte_2[$r] = $datos_2;

    $r++;
}    
                            
                            
                    echo $color;
                            

                        echo '<td align="center"><font size="2"><b>'.$reporte[$i]['id_tareas'].'</b></font></td>'; 
                         $r=0;
                            while (isset($reporte_2[$r])){
                        echo '<td align="center"><font size="2">'.$reporte_2[$r]['user_creator'].'</font></td>'; 
                         $r++;
                            }                      
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Last_name'].'&nbsp;'.$reporte[$i]['First_name'].'</font></td>';
                        echo '<td align="justify"><font size="2">'.$reporte[$i]['tareas'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['status_tareas'].'</font></td>';                                                                    
                        echo '<td align="center"><font size="2">'.date("m-d-Y",strtotime($reporte[$i]['fecha_inicio'])).'</font></td>';                        
                        echo '<td align="center"><font size="2">'.date("m-d-Y",strtotime($reporte[$i]['fecha_fin'])).'</font></td>';                        
                        echo '<td align="center">';
                        echo '<table border="0">';
                            $f=0;
                            while (isset($reporte_fechas_tareas[$f])){

                                if($reporte_fechas_tareas[$f]['id_status_tareas'] == 1){                                    
                                    $color = '#298A08';                                    
                                }                                
                                if($reporte_fechas_tareas[$f]['id_status_tareas'] == 2){                                    
                                    $color = '#5882FA';                                    
                                }
                                if($reporte_fechas_tareas[$f]['id_status_tareas'] == 3){                                    
                                    $color = '#FE9A2E';                                    
                                }                                
                                
                                echo '<tr>';

                                echo '<td style="width:30px" align="center"><font color="'.$color.'" size="5">*</font></td>';
                                echo '<td><font size="2">'.date("m-d-Y",strtotime($reporte_fechas_tareas[$f]['fechas_tareas'])).'</font></td>';
                                echo '</tr>';
                                
                                $f++;
                            }
                        echo '</table></td>';
                        if($reporte[$i]['id_status_tareas'] == 1) {
                            echo '<td align="center"><div id="action_'.$i.'"><a onclick="finalizar_tarea('.$reporte[$i]['id_tareas'].',2,\'action_'.$i.'\');" style="cursor:pointer"><b>CLOSE</b></a><br><a onclick="finalizar_tarea('.$reporte[$i]['id_tareas'].',3,\'action_'.$i.'\');" style="cursor:pointer"><font color="red"><b>Cancel</b></font></a></div></td>';
                        } else {
                            echo '<td align="center">--</td>';                            
                        }
                        
                        
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