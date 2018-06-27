<?php

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

$_GET['numero_referencia'] = ($_GET['numero_referencia'] == '' || $_GET['numero_referencia'] == null)? 'null': $_GET['numero_referencia'];
$numero_referencia = $_GET['numero_referencia'];

if(!isset($_GET['status'])){
    $_GET['status'] = null;
}


$where = null;

$_GET['insurance_m'] = ($_GET['insurance_m'] == '' || $_GET['insurance_m'] == null)? 'null': $_GET['insurance_m'];
$insurance = $_GET['insurance_m'];
if($insurance != 'null'){
    $where .= ' AND insurance_name = \''.$insurance.'\'';
} else {
$_GET['insurance_m'] = 'null';
}


$_GET['input_date_start_m'] = ($_GET['input_date_start_m'] == '' || $_GET['input_date_start_m'] == null)? 'null': $_GET['input_date_start_m'];
$input_date_start = $_GET['input_date_start_m'];


if($_GET['input_date_end_m'] == null){
    $_GET['input_date_end_m'] = 'null';
}

$_GET['input_date_end'] = ($_GET['input_date_end_m'] == '' || $_GET['input_date_end_m'] == null)? 'null': $_GET['input_date_end_m'];
$input_date_end = $_GET['input_date_end_m'];
if($input_date_start != 'null' && $input_date_end != 'null'){
    $where .= ' AND str_to_date(DOS,\'%m/%d/%Y\') BETWEEN str_to_date(\''.$input_date_start.'\',\'%m/%d/%Y\') AND str_to_date(\''.$input_date_end.'\',\'%m/%d/%Y\')';
}

$_GET['name_patient_m'] = ($_GET['name_patient_m'] == '' || $_GET['name_patient_m'] == null)? 'null': $_GET['name_patient_m'];
$name_patient = $_GET['name_patient_m'];
if($name_patient != 'null'){
    $where .= ' AND pat_id = \''.$name_patient.'\'';
}

$fields = '';
$array_field_data_table = array();
$p = 0;
while ($datos = current($_GET)) {

    $pos_key = strpos(key($_GET),'c_');     
    if ($pos_key !== false) {
        if($_GET[key($_GET)] == 'notes'){             
            $array_field_data_table[$p] = $_GET[key($_GET)];
        }else{
            if($fields == '')
                $fields .= $_GET[key($_GET)];
            else
                $fields .= ','.$_GET[key($_GET)];
            $array_field_data_table[$p] = $_GET[key($_GET)];
        }
        $p++;
    }    
    next($_GET);
}



if($fields == '')
    $fields = "*";
$conexion = conectar();
$sql  = " SELECT ".$fields.", tc.id_treatments_charges, pat_respon, ins_respon,status,status_treatments_charge FROM tbl_treatments_charges tc "
    . " LEFT JOIN tbl_treatments t ON t.campo_4 = tc.treatment_id "
    . " LEFT JOIN tbl_treatments_charge_status tcs ON tcs.id_treatments_charge_status = tc.status "
    . " LEFT JOIN tbl_treatments_associated ta ON ta.id_treatments_charges = tc.id_treatments_charges where true AND ta.numero_referencia is NULL ".$where." ";
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}

?>


<script src="../../../js/devoops_ext.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

 CSS 
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
<link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">



<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">
<script>
                $(document).ready(function() {
                        $('#treatments_charge_modal').DataTable({
                                pageLength: 1500,
                                dom: 'Bfrtip',
                                buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                ]
                        } );
                } );
                
//                function modificar_datos(patient_paid,insurance_paid,status,notes,posicion,id_treatments_charges,pat_respon,ins_respon){
//                    
//                    $('#pat_paid'+posicion).html('<input type="text" id="patient_paid'+posicion+'" name="patient_paid'+posicion+'"  class="form-control" value="'+patient_paid+'">');
//                    $('#ins_paid'+posicion).html('<input type="text" id="insurance_paid'+posicion+'" name="insurance_paid'+posicion+'" class="form-control" value="'+insurance_paid+'">');
//                    $('#status'+posicion).html('<select id="status_'+posicion+'" name="status_'+posicion+'" class="form-control"><option value="1">Billed</option><option value="2">Approved</option><option value="3">Denied</option></select>');
//                    $('#notes'+posicion).html('<input type="text" id="notes_'+posicion+'" name="notes_'+posicion+'" class="form-control" value="'+notes+'">');                                                       
//                    $('#modificar_datos'+posicion).html('<a onclick="guardar_datos(\''+posicion+'\',\''+id_treatments_charges+'\',\''+pat_respon+'\',\''+ins_respon+'\');"><img src="../../../images/save_2.png" style="width:30px"></a>');                                                       
//                    
//                    
//                }
                
//                function guardar_datos(posicion,id_treatments_charges,pat_respon,ins_respon){
//      
// var nombres_campos = '';           
//        
//  if($('#patient_paid'+posicion).val() == ''){
//nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Paid</td></tr></table>';
//
//        }  
//        
//  if($('#insurance_paid'+posicion).val() == ''){
//nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Insurance Paid</td></tr></table>';
//
//        }  
//        
//  if($('#status_'+posicion).val() == ''){
//nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Denied</td></tr></table>';
//
//        }  
//        
//  if($('#notes_'+posicion).val() == ''){
//nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Notes</td></tr></table>';
//
//        }          
//  var patient_paid_input = parseFloat($('#patient_paid'+posicion).val().replace('$','')) ;
//  if(pat_respon == '$0.00'  && patient_paid_input != 0){
//     nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * The pat_respon is 0</td></tr></table>';
//  }     
//  //alert(pat_respon);
///*tabladinamicasvalidacion*/
//
//    if(nombres_campos != ''){ 
//            
//        swal({
//          title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
//          type: "info",
//          html: "<h4>"+nombres_campos+"</h4>",
//          showCancelButton: false,          
//          closeOnConfirm: true,
//          showLoaderOnConfirm: false,
//        });
//            
//            return false; 
//        
//                         } else {
//
//
//var patient_paid = $('#patient_paid'+posicion).val();
//var insurance_paid = $('#insurance_paid'+posicion).val();
//var status = $('#status_'+posicion).val();
//var notes = $('#notes_'+posicion).val();
//
//                         
//                        $.post(
//                                "../../controlador/treatments_charge/insertar_treatments_change.php",
//                                '&patient_paid='+$('#patient_paid'+posicion).val()+'&insurance_paid='+$('#insurance_paid'+posicion).val()+'&status='+$('#status_'+posicion).val()+'&notes='+$('#notes_'+posicion).val()+'&id_treatments_charges='+id_treatments_charges,
//                                function (resultado_controlador) {                                    
//        
//                                    if(resultado_controlador.resultado == 'almacenado'){
//                                        
//                                        $('#pat_paid'+posicion).html('$'+$('#patient_paid'+posicion).val().replace('$',''));
//                                        $('#ins_paid'+posicion).html('$'+$('#insurance_paid'+posicion).val().replace('$',''));
//                                        $('#status'+posicion).html(resultado_controlador.resultadoStatus);
//                                        $('#notes'+posicion).html('<a onclick="ver_notas(\''+id_treatments_charges+'\')" style="cursor: pointer">'+$('#notes_'+posicion).val()+'</a>');
//
//                                        
//                                        
//                                        $('#modificar_datos'+posicion).html('<a onclick="modificar_datos(\''+patient_paid+'\',\''+insurance_paid+'\',\''+status+'\',\''+notes+'\',\''+posicion+'\',\''+id_treatments_charges+'\',\''+pat_respon+'\',\''+ins_respon+'\');"><img src="../../../images/save.png" style="width:20px"></a>');                                         
//                                        
//                                        
//                                        swal({
//                                          title: "<h3><b>Datos Modificados<b></h3>",          
//                                          type: "success"                   
//                                        });      
//
//                                        
//                                        
//                                        
//                                        
//                                    }
//        
//        
//        
//                                },
//                                "json" 
//                                );
//                        
//                        return false;
//                    }                    
//                    
//                    
//                    
//                }
//                
                function ver_notas_modal(id_treatments_charges,origin){
                
                        $.post(
                                "modal_treatments_charge_notes.php",
                                '&id_treatments_charge='+id_treatments_charges+'&origin='+origin,
                                function (resultado_controlador) {                                    
                       
                                swal({
                                  html: resultado_controlador.html,                                                                                                             
                                  closeOnConfirm: true                                  
                    });
                
        
        
                                },
                                "json" 
                                );                    
                    
                    
                    
                   
                    
                    
                    
                
                }
                
                function hab_des_treatments(posicion){
                    
                    var marcado = $('#treatment_'+posicion).prop('checked') ? true : false;
                    
                    if(marcado == true){
                        $('#pat_paid_'+posicion).prop('readonly',false);
                        $('#ins_paid_'+posicion).prop('readonly',false);
                        $('#status_'+posicion).prop('disabled',false);
                        $('#notes_'+posicion).prop('readonly',false);
                    } else {
                        $('#pat_paid_'+posicion).prop('readonly',true);
                        $('#ins_paid_'+posicion).prop('readonly',true);
                        $('#status_'+posicion).prop('disabled',true);
                        $('#notes_'+posicion).prop('readonly',true);
                    }
                    
                }
                
                function asociar_valores(){
//                    var nombres_campos = '';           
//        
//                    if($('#numero_referencia').val() == ''){
//                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Reference Number</td></tr></table>';
//                    }
                    
//                    if(nombres_campos != ''){ 
//
//                        $("#modal_filter_treatments").hide();   
//        
//
//                swal({
//                title: "<h3><b>Complete the following fields<b></h3>",          
//                type: "error",
//                html: "<h4>"+nombres_campos+"</h4>",
//                showCancelButton: false,   
//                confirmButtonColor: "#3085d6",                   
//                confirmButtonText: "Ok",   
//                closeOnConfirm: false,
//                closeOnCancel: false
//                }).then(function(isConfirm) {
//                  if (isConfirm === true) {                       
//                    $("#modal_filter_treatments").show();  
//                  }
//                    });
//
//                            return false; 

//                    } else {
                    var valores;
                    var valores_pat = '&pat_paid=';
                    var valores_ins = '&ins_paid=';
                    var valores_status = '&status=';
                    var valores_notes = '&notes=';
                    var valores_id_treatments_charge = '&ids_treatments_charge=';
                    var numero_referencia = '&numero_referencia='+$('#numero_referencia').val();
                    
                    $.each($("input[type='checkbox']:checked"), function(){            

                        if($(this).attr('name').substring(0, 10) == 'treatment_'){
                            
                           valores_pat += $('#pat_paid_'+$(this).attr('name').substring(10,13)).val()+',';
                           valores_ins += $('#ins_paid_'+$(this).attr('name').substring(10,13)).val()+',';
                           valores_status += $('#status_'+$(this).attr('name').substring(10,13)).val()+',';
                           valores_notes += $('#notes_'+$(this).attr('name').substring(10,13)).val()+',';
                           valores_id_treatments_charge += $('#id_treatments_charge_'+$(this).attr('name').substring(10,13)).val()+',';
                            
                        }

                    });
                    
                  
                    
                    
                    valores_pat = valores_pat.substring(0,valores_pat.length-1);
                    valores_ins = valores_ins.substring(0,valores_ins.length-1);
                    valores_status = valores_status.substring(0,valores_status.length-1);
                    valores_notes = valores_notes.substring(0,valores_notes.length-1);
                    valores_id_treatments_charge = valores_id_treatments_charge.substring(0,valores_id_treatments_charge.length-1);
                    
                    valores = numero_referencia+valores_pat+valores_ins+valores_status+valores_notes+valores_id_treatments_charge;
               
                        $.post(
                                "../../controlador/treatments_charge/asociar_treatments_charge.php",
                                valores,
                                function (resultado_controlador) {                                    
        
                                    if(resultado_controlador.resultado == 'asociado'){
                                      
                                        swal({
                                          title: "<h3><b>Treatments Associate<b></h3>",          
                                          type: "success"                   
                                        }); 
                                        
                                        autocompletar_radio('numero_referencia','numero_referencia','tbl_treatments_associated','selector',$('#reference_number').val(),null,'true','GROUP BY numero_referencia','reference_number');
                                        mostrar_resultados('resultado_modal');                                        
                                        $('#resultado_modal').html('');
                                        $('#modal_filter_treatments').modal('hide');
                                        
                                        
                                        $('#numero_referencia').val(null);
                                        
                                        $.each($("input[type='checkbox']:checked"), function(){            

                                            if($(this).attr('name').substring(0, 10) == 'treatment_'){

                    
                                               $('#treatment_'+$(this).attr('name').substring(10,13)).prop('checked', false);
                                               
                                               $('#pat_paid_'+$(this).attr('name').substring(10,13)).prop('readonly', true);                                                                                              
                                               $('#ins_paid_'+$(this).attr('name').substring(10,13)).prop('readonly', true);                                                                                              
                                               $('#status_'+$(this).attr('name').substring(10,13)).prop('disabled', true);
                                               $('#notes_'+$(this).attr('name').substring(10,13)).prop('readonly', true);

                                            }

                                        }); 
                                        
                                                                                                                                                                
                                    }
                        
                                },
                                "json" 
                                );                    
                    
//                    }
                    
                }
                
                
                
                
</script>
<br>
        <div class="row">
            <div class="col-lg-12 text-center"><b><h4>TREATMENTS CHARGE</h4></b></div>    
        </div>

<div class="col-lg-12">
    <?php 
    //echo '<pre>';
    //print_r($array_field_data_table);?>
    <table id="treatments_charge_modal" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>                    
                    <?php 
                    if(in_array('pat_id', $array_field_data_table))
                        echo '<th>ID PATIENT</th>';
                    if(in_array('patient_name', $array_field_data_table))
                        echo '<th>PATIENT NAME</th>';
                    if(in_array('DOS', $array_field_data_table))
                        echo '<th>DOS</th>';
                    if(in_array('cpt_code', $array_field_data_table))
                        echo '<th>CPT CODE</th>';                                        
                    if(in_array('pat_paid', $array_field_data_table))
                        echo '<th>PATIENT PAID</th>';
                    if(in_array('ins_paid', $array_field_data_table))
                        echo '<th>INSURANCE PAID</th>';
                    if(in_array('status_treatments_charge as status_name', $array_field_data_table))
                        echo '<th>STATUS</th>';
                    if(in_array('notes', $array_field_data_table))
                        echo '<th>NOTES</th>';                    
                    ?>        
                    <th>CHECK</th>
                </tr>
            </thead>


            <tbody>
            <?php		
            $i=0;
            $status = null;
            $posiciones_c = null;
            $status_c = null;
            while (isset($reporte[$i])){ 				
           
                echo '<tr>';
                if(in_array('pat_id', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['pat_id'].'</td>';
                if(in_array('patient_name', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['patient_name'].'</td>';
                if(in_array('DOS', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['DOS'].'</td>';
                if(in_array('cpt_code', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['cpt_code'].'</td>';
                if(in_array('pat_paid', $array_field_data_table))
                    echo '<td><div id="pat_paid'.$i.'"><input type="text" class="form-control" value="'.$reporte[$i]['pat_paid'].'" id="pat_paid_'.$i.'" name="pat_paid_'.$i.'" readonly></div></td>';
                if(in_array('ins_paid', $array_field_data_table))
                    echo '<td><div id="ins_paid'.$i.'"><input type="text" class="form-control" value="'.$reporte[$i]['ins_paid'].'" id="ins_paid_'.$i.'" name="ins_paid_'.$i.'" readonly></div></td>';
                if(in_array('status_treatments_charge as status_name', $array_field_data_table)){                    
                    echo '<td><div id="status'.$i.'"><select id="status_'.$i.'" name="status_'.$i.'" class="form-control" disabled><option value="1">Billed</option><option value="2">Approved</option><option value="3">Denied</option></select></div></td>';                    
                }      
                
                $notes = null;
                
                if(in_array('notes', $array_field_data_table)){
                    $sql_notes = " SELECT * FROM tbl_treatments_charge_notes WHERE id_treatments_charge = ".$reporte[$i]['id_treatments_charges'].' ORDER BY id_treatments_charge_notes DESC limit 1;';                    
                    $resultado = ejecutar($sql_notes,$conexion);
                    while($datos = mysqli_fetch_assoc($resultado)) {            
                        $notes = $datos['notes'];    
                        break;
                    }
                    echo '<td><div id="notes'.$i.'" class="text-center"><input type="text" class="form-control" value="'.$notes.'" id="notes_'.$i.'" name="notes_'.$i.'" readonly><a onclick="ver_notas_modal(\''.$reporte[$i]['id_treatments_charges'].'\',\'modal\')" style="cursor: pointer">Notes</a></div></td>';
                }   
                
                echo '<td align="center"><div id="modificar_datos'.$i.'"><input type="checkbox" name="treatment_'.$i.'" id="treatment_'.$i.'" onclick="return hab_des_treatments('.$i.');"></div></td>';
                echo '<input type="hidden" value="'.$reporte[$i]['id_treatments_charges'].'" id="id_treatments_charge_'.$i.'" name="id_treatments_charge_'.$i.'" ';
                echo '</tr>';
                
                $posiciones_c .= $i.'|';
                $status_c .= $reporte[$i]['status'].'|';
                
                $i++;		
            }	
            
            $posiciones_c = substr ($posiciones_c, 0, strlen($posiciones_c) - 1);
            $status_c = substr ($status_c, 0, strlen($status_c) - 1);            
            
             ?>	
                
                
                
            <tbody/>
            </table>     
    
    
    <script>
    
    marcar_valores('<?php echo $posiciones_c;?>','<?php echo $status_c;?>');
                
    function marcar_valores(posiciones,status){
    
        var posiciones_array = posiciones.split("|");
        var status_array = status.split("|");


            var longitud = posiciones_array.length;

            for (var x = 0; x < longitud; x++) {
                
                $('#status_'+posiciones_array[x]+' option[value='+status_array[x]+']').attr('selected',true);
                                                                             
            }


    }
    
        
        
    </script>

</div>
    <div id="resultado_modal"></div>
    <div class="row">
        <div class="col-lg-12 text-center"><button class="btn btn-success" style="width: 120px" onclick="asociar_valores();">ASSOCIATE</button></div>    
    </div>
<br>