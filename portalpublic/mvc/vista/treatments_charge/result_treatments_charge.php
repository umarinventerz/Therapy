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
$where = null;
$leftJoinEDI = '';
$fieldsReference = '';
$_GET['reference_number'] = ($_GET['reference_number'] == '' || $_GET['reference_number'] == null)? 'null': $_GET['reference_number'];
if(isset($_GET['reference_number']) && $_GET['reference_number'] != '' && $_GET['reference_number'] != 'null'){
    $leftJoinEDI = " LEFT JOIN tbl_treatments_associated tca ON tca.id_treatments_charges = tc.id_treatments_charges ";
    $where = " AND tca.numero_referencia = '".$_GET['reference_number']."' ";
    $fieldsReference = ',tca.numero_referencia';
}

if(!isset($_GET['status'])){
    $_GET['status'] = null;
}

$_GET['insurance'] = ($_GET['insurance'] == '' || $_GET['insurance'] == null)? 'null': $_GET['insurance'];
$insurance = $_GET['insurance'];
if($insurance != 'null'){
    $where .= ' AND insurance_name = \''.$insurance.'\'';
}

$_GET['input_date_start'] = ($_GET['input_date_start'] == '' || $_GET['input_date_start'] == null)? 'null': $_GET['input_date_start'];
$input_date_start = $_GET['input_date_start'];

$_GET['input_date_end'] = ($_GET['input_date_end'] == '' || $_GET['input_date_end'] == null)? 'null': $_GET['input_date_end'];
$input_date_end = $_GET['input_date_end'];
if($input_date_start != 'null' && $input_date_end != 'null'){
    $where .= " AND str_to_date(DOS,'%m/%d/%Y') BETWEEN str_to_date('".$input_date_start."','%m/%d/%Y') AND str_to_date('".$input_date_end."','%m/%d/%Y') ";
    //$where .= ' AND DOS BETWEEN \''.$input_date_start.'\' AND \''.$input_date_end.'\'';
}

$_GET['name_patient'] = ($_GET['name_patient'] == '' || $_GET['name_patient'] == null)? 'null': $_GET['name_patient'];
$name_patient = $_GET['name_patient'];
if($name_patient != 'null'){
    $where .= ' AND pat_id = \''.$name_patient.'\'';
}

$_GET['name_therapyst'] = ($_GET['name_therapyst'] == '' || $_GET['name_therapyst'] == null)? 'null': $_GET['name_therapyst'];
$name_therapyst = $_GET['name_therapyst'];
if($name_therapyst != 'null'){
    $where .= ' AND campo_4 = \''.$name_therapyst.'\'';
}

$_GET['status'] = ($_GET['status'] == '' || $_GET['status'] == null)? 'null': $_GET['status'];
$status = $_GET['status'];
if($status != 'null'){
    $where .= ' AND status = '.$status;
}

$_GET['pay_status'] = ($_GET['pay_status'] == '' || $_GET['pay_status'] == null)? 'null': $_GET['pay_status'];
$pay_status = $_GET['pay_status'];
if($pay_status != 'null' && $pay_status != 'all'){
    $where .= ' AND tc.status = \''.$pay_status.'\'';
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

if(strpos($fields, 'numero_referencia')){
    $leftJoinEDI = " LEFT JOIN tbl_treatments_associated tca ON tca.id_treatments_charges = tc.id_treatments_charges ";    
}
$groupByPatient = '';
$totalLabelTittle = '';
if($_GET['groupByPatient'] == 1){
    $totalLabelTittle = 'TOTAL ';
    $groupByPatient = ' GROUP BY pat_id';
    $array_field_data_table= array('patient_name','pat_respon','ins_respon','charge','pat_paid','ins_paid','total_paid','balance');
    $fields = 'patient_name,sum(trim(replace(pat_respon,\'$\',\'\'))) as pat_respon,sum(trim(replace(ins_respon,\'$\',\'\'))) as ins_respon,
 sum(trim(replace(charge,\'$\',\'\'))) as charge, sum(trim(replace(pat_paid,\'$\',\'\'))) as pat_paid, sum(trim(replace(ins_paid,\'$\',\'\'))) as ins_paid,
(sum(trim(replace(pat_paid,\'$\',\'\'))) + sum(trim(replace(ins_paid,\'$\',\'\')))) as total_paid, sum(trim(replace(balance,\'$\',\'\'))) as balance';
}

$conexion = conectar();
  $sql  = " SELECT ".$fields.$fieldsReference.", tc.id_treatments_charges, pat_respon, ins_respon FROM tbl_treatments_charges tc "
    . " LEFT JOIN tbl_treatments t ON t.id_treatments = tc.treatment_id "    
    . " LEFT JOIN tbl_treatments_charge_status tcs ON tcs.id_treatments_charge_status = tc.status ".$leftJoinEDI." where true ".$where.$groupByPatient;
$resultado = ejecutar($sql,$conexion);
//die;
$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}


?>
<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
<link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">
<script src="../../../js/funciones.js" type="text/javascript"></script>
<script>
                $(document).ready(function() {
                        $('#treatments_charge').DataTable({
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
                
                function modificar_datos(patient_paid,insurance_paid,status,notes,posicion,id_treatments_charges,pat_respon,ins_respon,reference_number){
                    
                    $('#pat_paid'+posicion).html('<input type="text" id="patient_paid'+posicion+'" name="patient_paid'+posicion+'"  class="form-control" value="'+patient_paid+'">');
                    $('#ins_paid'+posicion).html('<input type="text" id="insurance_paid'+posicion+'" name="insurance_paid'+posicion+'" class="form-control" value="'+insurance_paid+'">');
                    $('#status'+posicion).html('<select id="status_'+posicion+'" name="status_'+posicion+'" class="form-control"><option value="1">Billed</option><option value="2">Approved</option><option value="3">Denied</option></select>');
                    $('#notes'+posicion).html('<input type="text" id="notes_'+posicion+'" name="notes_'+posicion+'" class="form-control" value="'+notes+'">');                                                       
                    if($('#reference_number'+posicion))
                        $('#reference_number'+posicion).html('<input type="text" id="reference_number_'+posicion+'" name="reference_number_'+posicion+'" class="form-control" value="'+reference_number+'">');                                                       
                    $('#modificar_datos'+posicion).html('<a onclick="guardar_datos(\''+posicion+'\',\''+id_treatments_charges+'\',\''+pat_respon+'\',\''+ins_respon+'\');"><img src="../../../images/save_2.png" style="width:30px"></a>');                                                       
                    
                    
                }
                
                function guardar_datos(posicion,id_treatments_charges,pat_respon,ins_respon){
      
 var nombres_campos = '';           
        
  if($('#patient_paid'+posicion).val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Paid</td></tr></table>';

        }  
        
  if($('#insurance_paid'+posicion).val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Insurance Paid</td></tr></table>';

        }  
        
  if($('#status_'+posicion).val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Denied</td></tr></table>';

        }         
 if($('#reference_number_'+posicion).length > 0 ){
    if($('#reference_number_'+posicion).val() == ''){
        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Reference Number</td></tr></table>';
    }
 }        
  
        
  var patient_paid_input = parseFloat($('#patient_paid'+posicion).val().replace('$','')) ;
  if(pat_respon == '$0.00'  && patient_paid_input != 0){
     nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * The pat_respon is 0</td></tr></table>';
  }     
  //alert(pat_respon);
/*tabladinamicasvalidacion*/

    if(nombres_campos != ''){ 
        $("#modal_filter_treatments").hide();   
        
        swal({
          title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,          
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        },function(){
            $("#modal_filter_treatments").show();
        });
        
            
            return false; 
        
                         } else {


                    var patient_paid = $('#patient_paid'+posicion).val();
                    var insurance_paid = $('#insurance_paid'+posicion).val();
                    var status = $('#status_'+posicion).val();
                    var notes = $('#notes_'+posicion).val();
                    var reference_number = '';
                    if($('#reference_number_'+posicion).length > 0 ){
                        reference_number = $('#reference_number_'+posicion).val();
                    }
                    

                         
                        $.post(
                                "../../controlador/treatments_charge/insertar_treatments_change.php",
                                '&patient_paid='+$('#patient_paid'+posicion).val()+'&insurance_paid='+$('#insurance_paid'+posicion).val()+'&status='+$('#status_'+posicion).val()+'&notes='+$('#notes_'+posicion).val()+'&id_treatments_charges='+id_treatments_charges+'&reference_number='+reference_number,
                                function (resultado_controlador) {                                    
        
                                    if(resultado_controlador.resultado == 'almacenado'){
                                        
                                        $('#pat_paid'+posicion).html('$'+$('#patient_paid'+posicion).val().replace('$',''));
                                        $('#ins_paid'+posicion).html('$'+$('#insurance_paid'+posicion).val().replace('$',''));
                                        $('#status'+posicion).html(resultado_controlador.resultadoStatus);
                                        $('#notes'+posicion).html('<a onclick="ver_notas(\''+id_treatments_charges+'\')" style="cursor: pointer">'+$('#notes_'+posicion).val()+'</a>');
                                        $('#reference_number'+posicion).html($('#reference_number_'+posicion).val());

                                        
                                        
                                        $('#modificar_datos'+posicion).html('<a onclick="modificar_datos(\''+patient_paid+'\',\''+insurance_paid+'\',\''+status+'\',\''+notes+'\',\''+posicion+'\',\''+id_treatments_charges+'\',\''+pat_respon+'\',\''+ins_respon+'\',\''+reference_number+'\');"><img src="../../../images/save.png" style="width:20px"></a>');
                                        
                                        autocompletar_radio('numero_referencia','numero_referencia','tbl_treatments_associated','selector',reference_number,null,'true','GROUP BY numero_referencia','reference_number');                                        
                                        setTimeout(function(){
                                            
                                            mostrar_resultados('resultado');
                                        
                                        },1000);                                       
                                        
                                        
                                        swal({
                                          title: "<h3><b>Datos Modificados<b></h3>",          
                                          type: "success"                   
                                        });      

                                        
                                        
                                        
                                        
                                    }
        
        
        
                                },
                                "json" 
                                );
                        
                        return false;
                    }                    
                    
                    
                    
                }
                
                function desasociar_datos(id_treatments_charge, reference_number){
                    swal({
                        title: "Desasociar EDI",
                        text: "Are you sure that deseas desasociar this treatments?",
                        type: "warning",
                        showCancelButton: true,   
                        confirmButtonColor: "#3085d6",   
                        cancelButtonColor: "#d33",   
                        confirmButtonText: "Desasociar",   
                        closeOnConfirm: false,
                        closeOnCancel: false
                        }).then(function(isConfirm) {
                          if (isConfirm === true) {                       
                              $.post(
                                "../../controlador/treatments_charge/delete_treatments_charge_associated.php",
                                '&id_treatments_charge='+id_treatments_charge+'&reference_number='+reference_number,
                                function (resultado_controlador) {                                    
                                        mostrar_resultados('resultado');                                                    
                                        swal({
                                          title: "<h3><b>"+resultado_controlador.resultado+"<b></h3>",          
                                          type: "success"                   
                                        });      

                                },
                                "json" 
                                );
                          }
                    });
                }
                
                function ver_notas(id_treatments_charges,origin){                    
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
                    
//                    $('#resultado_modal_main').load('modal_treatments_charge_notes.php?&id_treatments_charge='+id_treatments_charges+'&origin='+origin,function(){
//                       
//                       $('#ModalTreatments_'+origin).modal('show');
//                        
//                    });
                
                }
                
                function llenarTotales(totalCharge, totalPatRespon, totalInsRespon, totalPatPaid, totalInsPaid, totalPaid, totalBalance, totalWriteOff, totalPatBalance, totalInsBalance){
                    $('#totalCharge').html('$'+totalCharge);
                    $('#totalPatRespon').html('$'+totalPatRespon);
                    $('#totalInsRespon').html('$'+totalInsRespon);
                    $('#totalPatPaid').html('$'+totalPatPaid);
                    $('#totalInsPaid').html('$'+totalInsPaid);
                    $('#totalInsPaid').html('$'+totalInsPaid);
                    $('#totalPaid').html('$'+totalPaid);                    
                    $('#totalBalance').html('$'+totalBalance);
                    $('#totalWriteOff').html('$'+totalWriteOff);
                    $('#totalPatBalance').html('$'+totalPatBalance);
                    $('#totalInsBalance').html('$'+totalInsBalance);
                    
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
    <table id="treatments_charge" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>                    
                    <?php 
                   
                    if(in_array('pat_id', $array_field_data_table))
                        echo '<th>ID PATIENT</th>';
                    if(in_array('patient_name', $array_field_data_table))
                        echo '<th>PATIENT NAME</th>';
                    if(in_array('DOS', $array_field_data_table))
                        echo '<th>DOS</th>';
                    if(in_array('DOB', $array_field_data_table))
                        echo '<th>DOB</th>';
                    if(in_array('cpt_code', $array_field_data_table))
                        echo '<th>CPT CODE</th>';
                    if(in_array('campo_4 as therapyst_name', $array_field_data_table))
                        echo '<th>THERAPYST NAME</th>';
                    if(in_array('charge', $array_field_data_table))
                        echo '<th>'.$totalLabelTittle.'CHARGE</th>';
                    if(in_array('pat_respon', $array_field_data_table))
                        echo '<th>'.$totalLabelTittle.'PATIENT RESPONSABILITY</th>';
                    if(in_array('ins_respon', $array_field_data_table))
                        echo '<th>'.$totalLabelTittle.'INSURANCE RESPONSABILITY</th>';
                    if(in_array('pat_paid', $array_field_data_table))
                        echo '<th>'.$totalLabelTittle.'PATIENT PAID</th>';
                    if(in_array('ins_paid', $array_field_data_table))
                        echo '<th>'.$totalLabelTittle.'INSURANCE PAID</th>';
                    if(in_array('total_paid', $array_field_data_table))
                        echo '<th>TOTAL PAID</th>';
                    if(in_array('balance', $array_field_data_table))
                        echo '<th>'.$totalLabelTittle.'BALANCE</th>';
                    if(in_array('status_treatments_charge as status_name', $array_field_data_table))
                        echo '<th>STATUS</th>';
                    if(in_array('notes', $array_field_data_table))
                        echo '<th>NOTES</th>';
                    if($fieldsReference != '' || in_array('numero_referencia', $array_field_data_table))
                        echo '<th>REFERENCE NUMBER</th>';
                    
                    if(in_array('insurance_name', $array_field_data_table))
                        echo '<th>INSURANCE NAME</th>';
                    if(in_array('discipline', $array_field_data_table))
                        echo '<th>DISCIPLINE</th>';
                    if(in_array('units', $array_field_data_table))
                        echo '<th>UNITS</th>';
                    if(in_array('write_off', $array_field_data_table))
                        echo '<th>WRITE OFF</th>';
                    if(in_array('pat_balance', $array_field_data_table))
                        echo '<th>PATIENT BALANCE</th>';
                    if(in_array('ins_balance', $array_field_data_table))
                        echo '<th>INSURANCE BALANCE</th>';
                                                            
                    if($_GET['groupByPatient']!= 1)
                        echo '<th>ACTION</th>';
                    ?>        
                    
                </tr>
            </thead>
                    <?php
                        echo '<tfoot>';
                            if(in_array('pat_id', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('patient_name', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('DOS', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('DOB', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('cpt_code', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('campo_4 as therapyst_name', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('charge', $array_field_data_table))
                                echo '<th><div id="totalCharge"></div></th>';
                            if(in_array('pat_respon', $array_field_data_table))
                                echo '<th><div id="totalPatRespon"></div></th>';
                            if(in_array('ins_respon', $array_field_data_table))
                                echo '<th><div id="totalInsRespon"></div></th>';
                            if(in_array('pat_paid', $array_field_data_table))
                                echo '<th><div id="totalPatPaid"></div></th>';
                            if(in_array('ins_paid', $array_field_data_table))
                                echo '<th><div id="totalInsPaid"></div></th>';
                            if(in_array('total_paid', $array_field_data_table))
                                echo '<th><div id="totalPaid"></div></th>';
                            if(in_array('balance', $array_field_data_table))
                                echo '<th><div id="totalBalance"></div></th>';
                            if(in_array('status_treatments_charge as status_name', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('notes', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if($fieldsReference != '' || in_array('numero_referencia', $array_field_data_table))
                                echo '<th>&nbsp;</th>';

                            if(in_array('insurance_name', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('discipline', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('units', $array_field_data_table))
                                echo '<th>&nbsp;</th>';
                            if(in_array('write_off', $array_field_data_table))
                                echo '<th><div id="totalWriteOff"></div></th>';
                            if(in_array('pat_balance', $array_field_data_table))
                                echo '<th><div id="totalPatBalance"></div></th>';
                            if(in_array('ins_balance', $array_field_data_table))
                                echo '<th><div id="totalInsBalance"></div></th>';

                            if($_GET['groupByPatient']!= 1)
                                echo '<th>&nbsp;</th>';                           
                        echo '</tfoot>';
                    ?>

            <tbody>
            <?php		
            $i=0;
            $total_charge = 0;
            $total_pat_respon = 0;
            $total_ins_respon = 0;
            $total_pat_paid = 0;
            $total_ins_paid = 0;
            $total_paid = 0;
            $total_balance = 0;
            $total_write_off = 0;
            $total_pat_balance = 0;
            $total_ins_balance = 0;
                    
            while (isset($reporte[$i])){ 				
           
                echo '<tr>';
                if(in_array('pat_id', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['pat_id'].'</td>';
                if(in_array('patient_name', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['patient_name'].'</td>';
                if(in_array('DOS', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['DOS'].'</td>';
                if(in_array('DOB', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['DOB'].'</td>';
                if(in_array('cpt_code', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['cpt_code'].'</td>';
                if(in_array('campo_4 as therapyst_name', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['therapyst_name'].'</td>';
                if(in_array('charge', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['charge'].'</td>';
                    $total_charge += (float)(str_replace('$', '', $reporte[$i]['charge']));
                }                    
                if(in_array('pat_respon', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['pat_respon'].'</td>';
                    $total_pat_respon += (float)(str_replace('$', '', $reporte[$i]['pat_respon']));
                }
                    
                if(in_array('ins_respon', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['ins_respon'].'</td>';
                    $total_ins_respon += (float)(str_replace('$', '', $reporte[$i]['ins_respon']));
                }
                    
                if(in_array('pat_paid', $array_field_data_table)){
                    if($_GET['groupByPatient']!= 1){
                        echo '<td><div id="pat_paid'.$i.'">'.$reporte[$i]['pat_paid'].'</div></td>';
                    }else{
                        echo '<td>'.$reporte[$i]['pat_paid'].'</td>';
                    }
                    $total_pat_paid += (float)(str_replace('$', '', $reporte[$i]['pat_paid']));
                }
                    
                if(in_array('ins_paid', $array_field_data_table)){
                    if($_GET['groupByPatient']!= 1){
                        echo '<td><div id="ins_paid'.$i.'">'.$reporte[$i]['ins_paid'].'</div></td>';
                    }else{
                        echo '<td>'.$reporte[$i]['ins_paid'].'</td>';
                    }
                    $total_ins_paid += (float)(str_replace('$', '', $reporte[$i]['ins_paid']));
                }
                
                if(in_array('total_paid', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['total_paid'].'</td>';    
                    $total_paid += (float)(str_replace('$', '', $reporte[$i]['total_paid']));
                }
                    
                if(in_array('balance', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['balance'].'</td>';
                    $total_balance += (float)(str_replace('$', '', $reporte[$i]['balance']));
                }
                    
                if(in_array('status_treatments_charge as status_name', $array_field_data_table)){                    
                    echo '<td><div id="status'.$i.'">'.$reporte[$i]['status_name'].'</div></td>';
                }               
                if(in_array('notes', $array_field_data_table)){
                    $sql_notes = " SELECT * FROM tbl_treatments_charge_notes WHERE id_treatments_charge = ".$reporte[$i]['id_treatments_charges'].' ORDER BY id_treatments_charge_notes DESC limit 1;';                    
                    $resultado = ejecutar($sql_notes,$conexion);
                    while($datos = mysqli_fetch_assoc($resultado)) {            
                        $notes = $datos['notes'];    
                        break;
                    }
                    echo '<td><div id="notes'.$i.'" class="text-center"><a onclick="ver_notas(\''.$reporte[$i]['id_treatments_charges'].'\',\'main\')" style="cursor: pointer">'.$notes.'</a></div></td>';
                }  
                if($fieldsReference != '')
                    echo '<td><div id="reference_number'.$i.'" class="text-center">'.$reporte[$i]['numero_referencia'].'</div></td>';
                else{
                    if(in_array('numero_referencia', $array_field_data_table)){
                        echo '<td>'.$reporte[$i]['numero_referencia'].'</td>';
                    }
                }
                
                if(in_array('insurance_name', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['insurance_name'].'</td>';
                if(in_array('discipline', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['discipline'].'</td>';
                if(in_array('units', $array_field_data_table))
                    echo '<td>'.$reporte[$i]['units'].'</td>';
                
                if(in_array('write_off', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['write_off'].'</td>';
                    $total_write_off += (float)(str_replace('$', '', $reporte[$i]['write_off']));
                }
                    
                if(in_array('pat_balance', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['pat_balance'].'</td>';
                    $total_pat_balance += (float)(str_replace('$', '', $reporte[$i]['pat_balance']));
                }
                    
                if(in_array('ins_balance', $array_field_data_table)){
                    echo '<td>'.$reporte[$i]['ins_balance'].'</td>';
                    $total_ins_balance += (float)(str_replace('$', '', $reporte[$i]['ins_balance']));
                }
                    
                
                
                if($_GET['groupByPatient']!= 1){
                    echo '<td align="center">'
                            . '<div class="form-group has-feedback">'
                                . '<div id="modificar_datos'.$i.'" class="col-lg-6"><a onclick="modificar_datos(\''.$reporte[$i]['pat_paid'].'\',\''.$reporte[$i]['ins_paid'].'\',\''.$status.'\',\''.$notes.'\',\''.$i.'\',\''.$reporte[$i]['id_treatments_charges'].'\',\''.$reporte[$i]['pat_respon'].'\',\''.$reporte[$i]['ins_respon'].'\',\''.$reporte[$i]['numero_referencia'].'\');"><img src="../../../images/save.png" style="width:20px"></a></div>';
                                if(isset($_GET['reference_number']) && $_GET['reference_number']!= '' && $_GET['reference_number']!= 'null'){
                                    echo '<div id="desasociar_datos'.$i.'" class="col-lg-6"><a onclick="desasociar_datos(\''.$reporte[$i]['id_treatments_charges'].'\',\''.$_GET['reference_number'].'\');"><img src="../../../images/Patient_on_Hold.png" style="width:20px" title="desasociar EDI (Find in the line 361 for change this message)"></a></div>';
                                }
                            echo '</div>'
                        . '</td>';
                }
                echo '</tr>';
                
                $notes = null;
                
                $i++;		
            }			
             ?>				
            <tbody/>
            </table> 
    <script> llenarTotales(<?php echo $total_charge;?>,<?php echo $total_pat_respon;?>,<?php echo $total_ins_respon;?>,<?php echo $total_pat_paid;?>,<?php echo $total_ins_paid;?>,<?php echo $total_paid;?>,<?php echo $total_balance;?>,<?php echo $total_write_off;?>,<?php echo $total_pat_balance;?>,<?php echo $total_ins_balance;?>);</script>
    <div id="resultado_modal_main"></div>

</div>