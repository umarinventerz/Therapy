                                          
<?php
error_reporting(0);
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
            
            if(isset($_GET["employee"]) && $_GET["employee"] != null){ $employee = ' id_usuario = '.$_GET["employee"]; } else { $employee = 'true'; }
            if(isset($_GET["fecha_inicio"]) && $_GET["fecha_inicio"] != null){ $fecha_inicio = $_GET["fecha_inicio"]; $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));} else { $fecha_inicio = 'true'; }
            if(isset($_GET['fecha_fin']) && $_GET['fecha_fin'] != null){ $fecha_fin = $_GET['fecha_fin']; $fecha_fin= date("Y-m-d",strtotime($fecha_fin));} else { $fecha_fin = 'true'; }
            
            
            
            
            if($fecha_inicio != 'true' && $fecha_fin != 'true'){
                $whereFecha = 'date_of_admission BETWEEN \''.$fecha_inicio.'\' AND \''.$fecha_fin.'\'';
            }else{
                if($fecha_inicio != 'true'){
                    $whereFecha = ' date_of_admission >= \''.$fecha_inicio.'\'';
                }else{
                    if($fecha_fin != 'true'){
                        $whereFecha = ' date_of_admission =< \''.$fecha_fin.'\'';;
                    }else{
                        $whereFecha = 'true';
                    }
                }
            }
            $conexion = conectar();
                 $joins = ' LEFT JOIN employee e ON e.id = a.id_usuario ';
            
            $where = " WHERE ".$employee." and ".$whereFecha;

            $sql  = "SELECT * FROM tbl_assistance as a".$joins.$where." ORDER BY date_of_admission,date_datetime,id_usuario DESC;";
            
            $resultado = ejecutar($sql,$conexion);

            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
            $j=0;
            $total_hour = '00:00:00';
            $id_usuario_ant = $reporte[0]['id_usuario'];
            $name = $reporte[0]['last_name'].", ".$reporte[0]['first_name'];
            $in = $reporte[0]['hour_of_admission'];
            $date_of_admission = $reporte[0]['date_of_admission'];
            
            $p=0;
            $b = 0;
            while(isset($reporte[$j])){
                $total_bloque = 0;
                if($reporte[$j]['id_usuario'] == $reporte[$j+1]['id_usuario'] && $reporte[$j+1]['operacion']== 'out'){ 
                    $total_bloque = calculaHoras($reporte[$j]['hour_of_admission'],$reporte[$j+1]['hour_of_admission'],'resta');
                    $bloque_correcto = true;
                    if($id_usuario_ant == $reporte[$j]['id_usuario'] && $date_of_admission == $reporte[$j]['date_of_admission']){
                        $total_hour = calculaHoras($total_hour,$total_bloque,'suma');
                        $out = $reporte[$j+1]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['in'] = $reporte[$j]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['in_id_a'] = $reporte[$j]['id_assistance'];
                        $array_totales[$p]['bloque'][$b]['out'] = $reporte[$j+1]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['out_id_a'] = $reporte[$j+1]['id_assistance'];
                        $b++;
                    }else{
                        $array_totales[$p]['name_usuario'] = $name;
                        $array_totales[$p]['id_usuario'] = $id_usuario_ant;
                        $array_totales[$p]['total_horas'] = $total_hour;
                        $array_totales[$p]['in'] = $in;
                        $array_totales[$p]['out'] = $out;
                        $array_totales[$p]['date_of_admission'] = $date_of_admission;
                        $p++;
                        $b= 0;
                        $array_totales[$p]['bloque'][$b]['in'] = $reporte[$j]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['in_id_a'] = $reporte[$j]['id_assistance'];
                        $array_totales[$p]['bloque'][$b]['out'] = $reporte[$j+1]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['out_id_a'] = $reporte[$j+1]['id_assistance'];
                        $b++;
                        $total_hour = 0;
                        $total_hour = calculaHoras($total_hour,$total_bloque,'suma');                        
                        $name = $reporte[$j]['last_name'].", ".$reporte[$j]['first_name'];
                        $id_usuario_ant = $reporte[$j]['id_usuario'];
                        $in = $reporte[$j]['hour_of_admission'];
                        $out = $reporte[$j+1]['hour_of_admission'];
                        $date_of_admission = $reporte[$j]['date_of_admission'];
                    }                                        
                }
                else{
                    
                    $array_totales[$p]['name_usuario'] = $name;
                    $array_totales[$p]['id_usuario'] = $id_usuario_ant;
                    $array_totales[$p]['total_horas'] = $total_hour;
                    $array_totales[$p]['in'] = $in;
                    $array_totales[$p]['date_of_admission'] = $date_of_admission;
                    
                    //echo $id_usuario_ant.'<->'.$reporte[$j]['id_usuario'];
                    if($id_usuario_ant == $reporte[$j]['id_usuario'] && $date_of_admission == $reporte[$j]['date_of_admission']){
                        $array_totales[$p]['out'] = '-';       
                        $array_totales[$p]['bloque'][$b]['in'] = $reporte[$j]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['in_id_a'] = $reporte[$j]['id_assistance'];
                        $array_totales[$p]['bloque'][$b]['out'] = '-';
                        $array_totales[$p]['bloque'][$b]['out_id_a'] = '-';                        
                        $b++;
                    }else{                        
                        $array_totales[$p]['out'] = $out;                        
                        $p++;
                        $array_totales[$p]['name_usuario'] = $reporte[$j]['last_name'].", ".$reporte[$j]['first_name'];
                        $array_totales[$p]['id_usuario'] = $reporte[$j]['id_usuario'];
                        $array_totales[$p]['total_horas'] = '00:00:00';
                        $array_totales[$p]['in'] = $reporte[$j]['hour_of_admission'];
                        $array_totales[$p]['out'] = '-';
                        $array_totales[$p]['date_of_admission'] = $reporte[$j]['date_of_admission'];
                        $b = 0;
                        $array_totales[$p]['bloque'][$b]['in'] = $reporte[$j]['hour_of_admission'];
                        $array_totales[$p]['bloque'][$b]['in_id_a'] = $reporte[$j]['id_assistance'];
                        $array_totales[$p]['bloque'][$b]['out'] = '-';
                        $array_totales[$p]['bloque'][$b]['out_id_a'] = $reporte[$j]['id_assistance'];
                        $p++;
                        $total_hour = '00:00:00';
                        $name = $reporte[$j+1]['last_name'].", ".$reporte[$j+1]['first_name'];
                        $id_usuario_ant = $reporte[$j+1]['id_usuario'];
                        $in = $reporte[$j+1]['hour_of_admission'];
                        $date_of_admission = $reporte[$j+1]['date_of_admission'];
                    }                    
                    $bloque_correcto = false;
                }    
                
                
                if($bloque_correcto){                    
                    if(!isset($reporte[$j+2])){
                        $array_totales[$p]['name_usuario'] = $name;
                        $array_totales[$p]['id_usuario'] = $id_usuario_ant;
                        $array_totales[$p]['total_horas'] = $total_hour;
                        $array_totales[$p]['in'] = $in;
                        $array_totales[$p]['out'] = $out;
                        $array_totales[$p]['date_of_admission'] = $date_of_admission;
                        break;
                    }
                    $j = $j+2;                    
                }                    
                else{
//                    if(!isset($reporte[$j+1])){
//                        $array_totales[$p]['name_usuario'] = $name;
//                        $array_totales[$p]['id_usuario'] = $id_usuario_ant;
//                        $array_totales[$p]['total_horas'] = $total_hour;
//                        $array_totales[$p]['in'] = $in;
//                        $array_totales[$p]['out'] = $out;
//                        $array_totales[$p]['date_of_admission'] = $date_of_admission;
//                        break;
//                    }
                    $j++;
                }                    
            }

function calculaHoras($horaini,$horafin,$operacion)
{
	$horai=substr($horaini,0,2);
	$mini=substr($horaini,3,2);
	$segi=substr($horaini,6,2);
 
	$horaf=substr($horafin,0,2);
	$minf=substr($horafin,3,2);
	$segf=substr($horafin,6,2);
 
	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf);
 
        if($operacion == 'resta')
            $dif=$fin-$ini;
        else
            $dif=$fin+$ini;
 
	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);
	return date("H:i:s",mktime($difh,$difm,$difs));
}
//            echo '<pre>';
//            print_r($array_totales);die;
                
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

                
/*modulos_dependientes_j*/

            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 1000);
            
            function gestionarRegistros(modalShow,accion){                
                if(accion == 'agregar'){
                    $('#agregar_div').show();
                    $('#eliminar_modificar_div').hide();
                }else{
                    $('#agregar_div').hide();
                    $('#eliminar_modificar_div').show();
                }
                $('#'+modalShow).modal('show');  
            }
            function gestionarAssistance(action, form, modalShow){  
                
                var campos_formulario = $("#"+form).serialize();                
                var ids_assistance = '';
                var pag;
                var url = '';
                if(action!= 'agregar'){
                    $("input:checkbox:checked").each(function() {
                        ids_assistance += $(this).val()+',';
                    });
                    pag = 'gestionar_assistance.php';
                    url = '&action='+action+'&ids_assistance='+ids_assistance;
                }else{
                    pag = 'registrar_assistance_extra.php';
                }
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();              
                 $.post(
                    "../../controlador/assistance/"+pag,
                    campos_formulario+url,
                    function (resultado_controlador) {                         
                        swal({
                            title: "<h3><b>Message<b></h3>",          
                            type: resultado_controlador.type_message,
                            html: "<h4>"+resultado_controlador.resultado+"</h4>",
                            showCancelButton: false,
                            animation: "slide-from-top",
                            closeOnConfirm: true,
                            showLoaderOnConfirm: false,
                          });
                          setTimeout(function(){
                            validar_formulario();
                        },1500);
                    },
                    "json" 
                    );
                
            }
            function mostrarOcultarBotton(posicion,valor){
                var check;                
                var res;
                                
                if( $("#id_"+valor).is(':checked') ) {
                    check = true;
                    //alert($(this).val());
                    res = valor.split("_"); 
                    //return false;
                    $("#hour_"+res[0]).prop("disabled",false);
                    $("#minutes_"+res[0]).prop("disabled",false);
                    $("#seconds_"+res[0]).prop("disabled",false);
                    
                    $("#hour_"+res[1]).prop("disabled",false);
                    $("#minutes_"+res[1]).prop("disabled",false);
                    $("#seconds_"+res[1]).prop("disabled",false);
                }else{
                    
                    //alert($(this).val());
                    res = valor.split("_");                    
                    //return false;
                    $("#hour_"+res[0]).prop("disabled",true);
                    $("#minutes_"+res[0]).prop("disabled",true);
                    $("#seconds_"+res[0]).prop("disabled",true);
                    
                    $("#hour_"+res[1]).prop("disabled",true);
                    $("#minutes_"+res[1]).prop("disabled",true);
                    $("#seconds_"+res[1]).prop("disabled",true);
                }   
                check = false;
                $("input:checkbox:checked").each(function(){
                    check = true;
                });
                
//                $("input:checkbox:not(:checked)").each(function() {
//                    $("#hour_"+res[0]).prop("disabled",true);
//                    $("#minutes_"+res[0]).prop("disabled",true);
//                    $("#seconds_"+res[0]).prop("disabled",true);
//                    
//                    $("#hour_"+res[1]).prop("disabled",true);
//                    $("#minutes_"+res[1]).prop("disabled",true);
//                    $("#seconds_"+res[1]).prop("disabled",true);                    
//                });
//                
                if(check){
                    $('#buttonEliminar_'+posicion).show('slow');  
                    $('#buttonModificar_'+posicion).show('slow');  
                }else{
                    $('#buttonEliminar_'+posicion).hide('slow');  
                    $('#buttonModificar_'+posicion).hide('slow');  
                }
//                
            }
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
            <th>NAME</th>   
            <th>DATE OF ADMISSION</th>
            <th>IN</th>
            <th>OUT</th>            
            <th>RANGE OF IN AND OUT</th>
            <th>TOTAL HOURS</th>
            <th>TO SEE IN AND OUT</th>
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        //echo recursive_array_search(null,$reporte); die;
                        
                        while (isset($array_totales[$i])) {
                        
                          
                    echo $color;             
             
             echo '<td align="center"><font size="2">'.$array_totales[$i]['name_usuario'].'</font></td>';             
             echo '<td align="center"><font size="2">'.$array_totales[$i]['date_of_admission'].'</font></td>';             
             echo '<td align="center"><font size="2">'.$array_totales[$i]['in'].'</font></td>';
             echo '<td align="center"><font size="2">'.$array_totales[$i]['out'].'</font></td>';
             echo '<td align="center"><font size="2">';
             echo '<div class="row">';
                echo '<div class="col-sm-6"><b>IN</b></div>';
                echo '<div class="col-sm-6"><b>OUT</b></div>';
             echo '</div>';
             $d = 0;
             while($array_totales[$i]['bloque'][$d]){
                 
                 echo '<div class="row">';
                    echo '<div class="col-sm-6">'.$array_totales[$i]['bloque'][$d]['in'].'</div>';
                    echo '<div class="col-sm-6">'.$array_totales[$i]['bloque'][$d]['out'].'</div>';
                 echo '</div>';
                 $d++;
             }
             echo '</font></td>';
             echo '<td align="center"><font size="2">'.$array_totales[$i]['total_horas'].'</font></td>';             
             echo '<td align="center">'
            . '<img src="../../../images/agregar.png" style="width: 20px;height 20px;" onclick="gestionarRegistros(\'modal_'.$i.'\',\'agregar\');">'
            . '<img src="../../../images/pencil.png" style="width: 20px;height 20px;" onclick="gestionarRegistros(\'modal_'.$i.'\',\'\');">'
            .'<div class="modal" id="modal_'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" >              
      <div class="modal-body text-center"> 
          <img src="../../../images/save.png" width="120" height="90"/><br>
          <h2 class="modal-title" id="exampleModalLabel"><font color="#848484"><b>Asistance!</b></font></h2>
          <br>
          <form id="form_modal_'.$i.'" >';
            echo '<div id="eliminar_modificar_div">';
            $joins = ' LEFT JOIN employee e ON e.id = a.id_usuario ';
            $where = ' WHERE a.date_of_admission = \''.$array_totales[$i]['date_of_admission'].'\' AND a.id_usuario='.$array_totales[$i]['id_usuario'];            

            $sql  = "SELECT * FROM tbl_assistance as a".$joins.$where." ORDER BY date_of_admission,id_usuario,date_datetime;";
            $resultado = ejecutar($sql,$conexion);

            $reporteDetalle = array();

            $g = 0;      
            while($datosDetalle = mysqli_fetch_assoc($resultado)) {            
                $reporteDetalle[$g] = $datosDetalle;
                $g++;
            }
            echo '<div class="row">'
                . '<div class="form-group col-lg-12">'
                    . '<div class="col-lg-2" style="text-align:right;"><b>Name: </b></div>'
                    . '<div class="col-lg-3" style="text-align:left;"><input type="hidden" id="id_usuario_hidden" name="id_usuario_hidden" value="'.$array_totales[$i]['id_usuario'].'">'.$array_totales[$i]['name_usuario'].'</div>'                    
                    . '<div class="col-lg-3" style="text-align:right;"><b>Date of Admision:</b></div>'
                    . '<div class="col-lg-3" style="text-align:left;"><input type="hidden" id="id_date_hidden" name="id_date_hidden" value="'.$array_totales[$i]['date_of_admission'].'">'.$array_totales[$i]['date_of_admission'].'</div>'                    
                . '</div>'
            . '</div>';        
            
            echo'<div class="row">                    
                    <div class="form-group col-lg-12">                        
                        <label for="message-text" class="col-sm-5 control-label"><font size="3" color="#848484">IN</font></label>
                        <label for="message-text" class="col-sm-5 control-label"><font size="3" color="#848484">OUT</font></label>                        
                        <label for="message-text" class="col-sm-2 control-label">To select</label>                          
                    </div>
                </div>';
            
                $d = 0;
                $r = 1;
                $sin_id_out = $r.'new';
                while($array_totales[$i]['bloque'][$d]){

                    echo '<div class="row">';
                        echo '<div class="form-group col-lg-12">';
                            echo '<div class="col-sm-5">';
                                echo '<select class="form-control" id="hour_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'" name="hour_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'" disabled>';
                                    $g = 0;
                                    while($g < 24){
                                        echo '<option value="'.(($g < 10)?'0'.$g:$g).'" '.(((int)(substr($array_totales[$i]['bloque'][$d]['in'], 0, 2)) == $g)?'selected':'').'>'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                        $g++;
                                    }                        
                                echo '</select>:'
                                . '<select class="form-control" id="minutes_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'" name="minutes_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'" disabled>';
                                    $g = 0;
                                    while($g < 60){
                                        echo '<option value="'.(($g < 10)?'0'.$g:$g).'" '.(((int)(substr($array_totales[$i]['bloque'][$d]['in'], 3, 2)) == $g)?'selected':'').'>'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                        $g++;
                                    }
                                echo '</select>'
                                . '<select class="form-control" id="seconds_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'" name="seconds_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'" disabled>';
                                    $g = 0;
                                    while($g < 60){
                                        echo '<option value="'.(($g < 10)?'0'.$g:$g).'" '.(((int)(substr($array_totales[$i]['bloque'][$d]['in'], 6, 2)) == $g)?'selected':'').'>'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                        $g++;
                                    }
                                echo '</select>';
                            //.$array_totales[$i]['bloque'][$d]['in'].
                            echo '</div>';
                            echo '<div class="col-sm-5">';
                            echo '<select class="form-control" id="hour_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" name="hour_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" disabled>';
                                    $g = 0;
                                    while($g < 24){
                                        echo '<option value="'.(($g < 10)?'0'.$g:$g).'" '.(((int)(substr($array_totales[$i]['bloque'][$d]['out'], 0, 2)) == $g)?'selected':'').'>'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                        $g++;
                                    }                        
                                echo '</select>:'
                                . '<select class="form-control" id="minutes_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" name="minutes_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" disabled>';
                                    $g = 0;
                                    while($g < 60){
                                        echo '<option value="'.(($g < 10)?'0'.$g:$g).'" '.(((int)(substr($array_totales[$i]['bloque'][$d]['out'], 3, 2)) == $g)?'selected':'').'>'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                        $g++;
                                    }
                                echo '</select>'
                                . '<select class="form-control" id="seconds_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" name="seconds_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" disabled>';
                                    $g = 0;
                                    while($g < 60){
                                        echo '<option value="'.(($g < 10)?'0'.$g:$g).'" '.(((int)(substr($array_totales[$i]['bloque'][$d]['out'], 6, 2)) == $g)?'selected':'').'>'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                        $g++;
                                    }
                                echo '</select>';
                                
                            //.$array_totales[$i]['bloque'][$d]['out'].
                            echo '</div>';
                            echo '<div class="col-sm-2"><input type="checkbox" class="form-control" id="id_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" name="id_'.$array_totales[$i]['bloque'][$d]['in_id_a'].'_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'" onclick="mostrarOcultarBotton(\''.$i.'\',this.value);" value="'.$array_totales[$i]['bloque'][$d]['in_id_a'].'_'.(($array_totales[$i]['bloque'][$d]['out_id_a']=='-')?$sin_id_out:$array_totales[$i]['bloque'][$d]['out_id_a']).'"></div>';
                        echo '</div>';
                    echo '</div>';
                    if($array_totales[$i]['bloque'][$d]['out_id_a']=='-'){
                        $r++;
                        $sin_id_out = $r.'new';
                    }
                    $d++;
                }
                                          
            echo '<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" onclick="gestionarAssistance(\'eliminar\',\'form_modal_'.$i.'\',\'modal_'.$i.'\')" id="buttonEliminar_'.$i.'" class="btn btn-primary" style="display: none;">Eliminar</button>              
                    <button type="button" onclick="gestionarAssistance(\'modificar\',\'form_modal_'.$i.'\',\'modal_'.$i.'\')" id="buttonModificar_'.$i.'"class="btn btn-primary" style="display: none;">Modificar</button>
                  </div>
            </div>
            <div id="agregar_div">';
            echo '<div class="row">'
                . '<div class="form-group col-lg-12">'
                    . '<div class="col-lg-1"></div>'                    
                    . '<div class="col-lg-2" style="text-align:right;"><b>User Name: </b></div>'
                    . '<div class="col-lg-3" style="text-align:left;"><input type="hidden" id="codigo" name="codigo" class="form-control" value="'.$array_totales[$i]['id_usuario'].'" />'.$array_totales[$i]['name_usuario'].'</div>'                    
                    . '<div class="col-lg-3" style="text-align:right;"><b>Date of Admision:</b></div>'
                    . '<div class="col-lg-3" style="text-align:left;"><input type="hidden" id="date_of_admission" name="date_of_admission" class="form-control" value="'.$array_totales[$i]['date_of_admission'].'" />'.$array_totales[$i]['date_of_admission'].'</div>'                    
                . '</div>'
            . '</div>'; 
            echo '<hr>';
                echo '<div class="row"><div class="form-group col-lg-12">
                    <label for="message-text" class="col-sm-2 control-label"></label>                          
                    <label for="message-text" class="col-sm-4 control-label"><font size="4" color="#848484">IN</font></label>                          
                    <label for="message-text" class="col-sm-4 control-label"><font size="4" color="#848484">OUT</font></label>                                                                       
                </div></div>
                    <div class="row">
                    <div class="form-group col-lg-12">';
                        echo '<div class=" col-sm-2"></div>';
                        echo '<div class=" col-sm-4">'
                            . '<select class="form-control" id="hour_admission_in" name="hour_admission_in">';
                                $g = 0;
                                while($g < 24){
                                    echo '<option value="'.(($g < 10)?'0'.$g:$g).'">'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                    $g++;
                                }                        
                            echo '</select>:'
                            . '<select class="form-control" id="minutes_admission_in" name="minutes_admission_in">';
                                $g = 0;
                                while($g < 60){
                                    echo '<option value="'.(($g < 10)?'0'.$g:$g).'">'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                    $g++;
                                }
                            echo '</select>'
                            . '<select class="form-control" id="seconds_admission_in" name="seconds_admission_in">';
                                $g = 0;
                                while($g < 60){
                                    echo '<option value="'.(($g < 10)?'0'.$g:$g).'">'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                    $g++;
                                }
                            echo '</select></div>';
                            echo '<div class=" col-sm-4">'
                            . '<select class="form-control" id="hour_admission_out" name="hour_admission_out">';
                                $g = 0;
                                while($g < 24){
                                    echo '<option value="'.(($g < 10)?'0'.$g:$g).'">'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                    $g++;
                                }                        
                            echo '</select>:'
                            . '<select class="form-control" id="minutes_admission_out" name="minutes_admission_out">';
                                $g = 0;
                                while($g < 60){
                                    echo '<option value="'.(($g < 10)?'0'.$g:$g).'">'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                    $g++;
                                }
                            echo '</select>'
                            . '<select class="form-control" id="seconds_admission_out" name="seconds_admission_out">';
                                $g = 0;
                                while($g < 60){
                                    echo '<option value="'.(($g < 10)?'0'.$g:$g).'">'.(($g < 10)?'0'.$g:$g).'</option>';                            
                                    $g++;
                                }
                            echo '</select></div>';
                        //echo '<div class=" col-sm-3"><input id="hour_of_admission" name="hour_of_admission" class="form-control" /></div>
                                            
                    echo '</div>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" onclick="gestionarAssistance(\'agregar\',\'form_modal_'.$i.'\',\'modal_'.$i.'\')" id="buttonAgregar_'.$i.'" class="btn btn-primary">Agregar</button>                                  
                </div>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>'
                     . '</td>';
                           
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

