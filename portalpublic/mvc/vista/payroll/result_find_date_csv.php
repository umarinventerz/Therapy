<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>.: THERAPY AID :.</title>
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
    <link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">
    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
    <script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
    <script src="../../../plugins/tinymce/tinymce.min.js"></script>
    <script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js"></script>
    <script src="../../../js/funciones.js" type="text/javascript"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <style>
        table { font-size: 13px; }
    </style>
<?php
$conexion = conectar();
$date_start = sanitizeString($conexion, $_REQUEST['input_date_start']);
$date_end = sanitizeString($conexion, $_REQUEST['input_date_end']);
$type_terapist = sanitizeString($conexion, $_REQUEST['type_terapist']);
$status_terapist = sanitizeString($conexion, $_REQUEST['status_terapist']);
//$name_terapist = sanitizeString($conexion, $_REQUEST['name_terapist']);
if($_REQUEST['name_terapist'] != 'all' && $_REQUEST['name_terapist'] != 'all_therapist')
    list($name_terapist,$time_pay) = explode('-',sanitizeString($conexion, $_REQUEST['name_terapist']));
if(($_REQUEST['name_terapist'] == 'all') || (($time_pay == 'Perhour' || $time_pay == 'Bikeweekly') && $_REQUEST['type_salary']!='Perdiem')){    
            if($_REQUEST['name_terapist'] == 'all'){
               $sql  = "SELECT *,employee.id as id_employee FROM employee WHERE kind_employee like '%Administrative%' and status= ".$status_terapist.";";
            }else{
               $sql  = "SELECT *,emp.id as id_employee FROM employee emp WHERE (concat(emp.last_name,', ',emp.first_name) LIKE '%".$name_terapist."%' OR licence_number LIKE '%".$name_terapist."%');";//".$condicion_type_terapist.$condicion_status_terapist."
            }
	}else{
	    if(($_REQUEST['name_terapist'] == 'all_therapist')) {
	 echo	 $sql  = "SELECT *,employee.id as id_employee FROM employee WHERE kind_employee like '%Therapist%' AND type_salary = 'Salary' and status= ".$status_terapist.";";
	    }else{
		$sql  = "SELECT *,emp.id as id_employee, treat_c.status as treat_charges_status 
                FROM tbl_treatments treat 
                left join tbl_treatments_charges treat_c ON treat_c.treatment_id = treat.campo_4
                left join employee emp on treat.license_number = emp.licence_number 
                left join employee_treatment emp_t on emp_t.id_employee = emp.id 
                left join cpt c on c.cpt = treat.campo_11 and c.discipline = treat.campo_10 
                WHERE treat.id_treatments IN 
                (
                    SELECT id_treatments 
                    FROM tbl_treatments treat 
                    left join employee emp on treat.license_number = emp.licence_number 
                    WHERE (((str_to_date(campo_1,'%m/%d/%Y') <= str_to_date('".$date_end."','%m/%d/%Y'))) 
                    AND (campo_20 is not null OR (campo_11 like '%NO%')) AND ((pay = false or pay is null)  
                    AND (license_number LIKE '%".$name_terapist."%' OR concat(emp.last_name,', ',emp.first_name) LIKE '%".$name_terapist."%')) ) 
                )
                ORDER BY campo_4;";//".$condicion_type_terapist.$condicion_status_terapist."
	    }
	    
	}       
     	
        $resultado = ejecutar($sql,$conexion);

        $reporte = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i] = $datos;
            $i++;
        }        
?>
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/datatables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/datatables.responsive.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">	
    <!-- End Style-->
    <script type="text/javascript" language="javascript" class="init">
	    
	$(document).ready(function() {
		$('#example').DataTable({
			pageLength: 1500,
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            // Total over this page
                            pageTotal = api
                                .column( 8, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                
                            total_pay = api
                                .column( 10, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                
                            // Update footer
                            $( api.column( 8 ).footer() ).html(
                                pageTotal
                            );                            
                            $( api.column( 10 ).footer() ).html(
                                '$'+total_pay
                            );
                            $( api.column( 11 ).footer() ).html(
                                '<div id="selected">$ 0</div>'
                            );
                        }
		} );
                $('#example_1').DataTable({
			pageLength: 1500,
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]                      
		} );
                showSum();
	} );
     

        function showSum(){
	    
            var total= 0;
            var str;
            var res;
            $('input:checkbox:checked').map(function() {
                if(this.value != 'on') {                  
                    str = this.name;
                    res = str.split("-"); 
                    total = parseFloat(total) + parseFloat(res[1]);
                }
            });
            //total = parseFloat(total).toFixed(2);
            alert('Total: $'+number_format(total, 2, '.', ''));
            $("#selectedTreatments").val(number_format(total, 2, '.', ''));                 
            $("#selected").html('$'+number_format(total, 2, '.', ''));
        }
        function send_therapies_email(correo){
            var valores_check = '';

		$('input:checkbox:checked').map(function() {
                    if(this.value != 'on') {
                        valores_check += this.value+',';
                    }
		});
            //$('#datos_send').load('print_teraphist.php?identificadores_check='+valores_check);
            var datos_envio = '&identificadores_check='+valores_check+'&correo='+correo;
            //$('#datos_send').hide();
            
            $.post(
                    "../../controlador/payroll/send_mail_therapies.php",
                    datos_envio,
                    function (resultado_envio) {
                    
                        alert(resultado_envio.resultado);
                    
                    },
                    "json" 
                    );
	    
            return false;            
                   
        }
        
        function comprobar_seleccion(){

            if ($('input[name="seleccionar_todo"]').is(':checked')) {

                  $('input:checkbox:checked').map(function() {
                      $("input:checkbox").prop('checked',true);
                  });            

            } else {

                  $('input:checkbox:checked').map(function() {
                      $("input:checkbox").prop('checked',false);
                  });              

            }
            
        }
        
        function comprobar_seleccion_paid(){
            $('input:checkbox:checked').map(function() {
                if(this.id != 'seleccionar_todo_paid')
                    $("input:checkbox").prop('checked',false);
            });
            if ($('input[name="seleccionar_todo_paid"]').is(':checked')) {
                var arrTreatmentsPaid = $('#treatments_paid_insurance').val().split(','); 
                var i = 0;
                while(arrTreatmentsPaid[i]){
                    $("#"+arrTreatmentsPaid[i]).prop('checked',true);
                    i++;
                }
            }
        }
        function showSumAdministrative(){
             var total_pre_check_hidden = 0;            
             $('input:hidden').map(function() {
                //alert(this.name.substr(0,12));
                if(this.name.substr(0,12) == 'total_hidden'){
                    //alert($("#"+this.id).val());
                    if($("#"+this.id).val() != '' && $("#"+this.id).val() != null)
                        total_pre_check_hidden = parseFloat(total_pre_check_hidden) + parseFloat($("#"+this.id).val());
                }                 
            });                        
            $("#total_pre_check").html('$'+number_format(total_pre_check_hidden,2,'.',''));
            $("#total_pre_check_hidden").val(total_pre_check_hidden);
        }

        function calculatePreCheck(id_employee, amount_salary, dependencies, civil_status, type_contract, time_pay, input_type = 'default') {
            var gross = 0; var social_security = 0; var medicaid = 0; var federal_withholding = 0; var total = 0;
            if (time_pay == 'Bikeweekly')
                gross = amount_salary;
            else {
                var cantHours = $("#cantHours_" + id_employee).val();
                if (cantHours > 80) {
                    var extraCantHours = cantHours - 80;
                    gross = 80 * amount_salary;
                    var extra_gross = extraCantHours * (1.5 * amount_salary);
                    gross = number_format((gross + extra_gross), 2, '.', '');
                 } else
                    gross = number_format((cantHours * amount_salary), 2, '.', '');
            }
            if($("#total_extra_pay_hidden_" + id_employee).val() != '')
                gross = number_format((parseFloat(gross) + parseFloat($("#total_extra_pay_hidden_" + id_employee).val())), 2, '.', '');
            //if(cantHours != '') {
                $("#gross_" + id_employee).html('$' + gross);
                if (type_contract != '1099') {
                    if (input_type == 'default') {
                        social_security = number_format((gross * 6.2 / 100), 2, '.', '');
                        medicaid = number_format((gross * 1.45 / 100), 2, '.', '');
                        $("#ss" + id_employee).val(social_security);
                        $("#mc" + id_employee).val(medicaid);
                        $.post('../../controlador/payroll/get_federal_withholding.php',
                            'id_employee=' + id_employee + "&gross=" + gross + "&dependencies=" + dependencies + "&civil_status=" + civil_status,
                            function(resultado_controlador) {
                                federal_withholding = number_format(resultado_controlador.variable_federal, 2, '.', ''); 
                                $("#fw" + id_employee).val(federal_withholding);
                                total = gross - social_security - medicaid - federal_withholding;
                                $("#total_"+id_employee).html('$'+number_format(total, 2, '.',''));
                                $("#total_hidden_"+id_employee).val(number_format(total, 2, '.',''));
                                showSumAdministrative();
                            }, 'json');
                    }
                    social_security = $("#ss" + id_employee).val();
                    medicaid = $("#mc" + id_employee).val();
                    federal_withholding = $("#fw" + id_employee).val();
                    total = gross - social_security - medicaid - federal_withholding;
                } else
                    total = gross;
                $("#total_"+id_employee).html('$'+number_format(total, 2, '.',''));
                $("#total_hidden_"+id_employee).val(number_format(total, 2, '.',''));
                showSumAdministrative();
            //}
        }

        $("#datos_imprimir").hide();
        
        function generate_pre_check(type_paystub){
            var total_pre_check_hours = ''; var employee = ''; var id_employee = '';
            var total_extra_pay = ''; var selected_extra_pay = '';
            var ss = ''; var mc = ''; var fw = '';
            //var type_employee_list = '';
            $('input:hidden').map(function() {
                if(this.name.substr(0,12) == 'total_hidden') {
                    id_employee = this.name.replace("total_hidden_", "");
                    if($("#cantHours_"+id_employee).length > 0) {
                        if($("#cantHours_"+id_employee).val() != '') {
                            total_pre_check_hours = total_pre_check_hours + $("#cantHours_"+id_employee).val()+',';                                
                            employee = employee + id_employee + ',';
                            //type_employee_list = type_employee_list + 'Perhour' + ',';
                        }
                    }else{
                        if($("#"+this.id).val()!= '') {
                            total_pre_check_hours = total_pre_check_hours +'X'+',';
                            employee = employee + id_employee + ',';
                            //type_employee_list = type_employee_list + 'Bikeweekly' + ',';
                        }
                    }
                    selected_extra_pay = selected_extra_pay + $("#selected_extra_pay_hidden_"+id_employee).val()+',';
                    total_extra_pay = total_extra_pay + $("#total_extra_pay_hidden_"+id_employee).val()+',';
                    ss = ss + $("#ss" + id_employee).val() + ',';
                    mc = mc + $("#mc" + id_employee).val() + ',';
                    fw = fw + $("#fw" + id_employee).val() + ',';
                }
            });
            //$(input .extradata)
            //alert(total_pre_check_hours);
            if(employee == '') {
                alert('Debe generar al menos un calculo para emitir el '+type_paystub);
            } else {
                $("#resultado").load("../../controlador/payroll/generate_pre_check.php?type_paystub="+type_paystub+"&employee="+employee+"&total_pre_check_hours="+total_pre_check_hours+"&date_start="+$("#date_start").val()+"&date_end="+$("#date_end").val()+"&name_employee="+$("#name_employee").val()+"&type_employee="+$("#type_employee").val()+"&selected_extra_pay="+selected_extra_pay+"&total_extra_pay="+total_extra_pay+"&ss="+$("#ss" + id_employee).val()+"&ss="+ss+"&mc="+mc+"&fw="+fw);
            }                
        } 
                     
        function modificar_selected(id_extra_pay,id_employee,valor){
            $("input:checkbox[name=extra_pay_"+id_extra_pay+"]").each(function(){
			if(this.checked == true){
				if($("#selected_extra_pay_hidden_"+id_employee).val() == '')
					$("#selected_extra_pay_hidden_"+id_employee).val(id_extra_pay);					
				else{
                                    cadena = $("#selected_extra_pay_hidden_"+id_employee).val()+'-'+id_extra_pay+'';                                    
                                    res = cadena.replace("--", "-");
                                    $("#selected_extra_pay_hidden_"+id_employee).val(res);
                                }
                                var total = parseFloat($("#total_extra_pay_hidden_"+id_employee).val())+parseFloat(valor);
                                $("#div_extra_pay_"+id_employee).html('$'+total);
                                $("#total_extra_pay_hidden_"+id_employee).val(total);
			}else{
				var str = $("#selected_extra_pay_hidden_"+id_employee).val();	
                                //alert(str);
				var res = str.replace(id_extra_pay,"");                                
				res = res.replace("--", "-");
                                if(res.substring(0,1)== '-'){
                                    res = res.substring(1,res.length);
                                }
				$("#selected_extra_pay_hidden_"+id_employee).val(res);
                                var total = parseFloat($("#total_extra_pay_hidden_"+id_employee).val())-parseFloat(valor);
                                $("#div_extra_pay_"+id_employee).html('$'+total);
                                $("#total_extra_pay_hidden_"+id_employee).val(total);
			}
            });		
            if($("#total_hidden_"+id_employee).val() != ''){
                var datos_array = $("#datos_employee_calculate_"+id_employee).val().split(',');                
                calculatePreCheck(id_employee,datos_array[0],datos_array[1],datos_array[2],datos_array[3],datos_array[4]);
            }
        }
       
        function seleccionarExtraPay(html){
            swal({
                title: "<h4><b>SELECT AN EXTRA PAYMENT</h4>",          
                type: 'success',              
                showCancelButton: false,              
                closeOnConfirm: true,
                html: html,
                showLoaderOnConfirm: false,
              });
        }
    </script>
</head>




<div class="row">

<div class="col-lg-12 text-center" >
<?php 
if(($_REQUEST['name_terapist'] == 'all' || $_REQUEST['name_terapist'] == 'all_therapist') || (($time_pay == 'Perhour' || $time_pay == 'Bikeweekly') && $_REQUEST['type_salary']!='Perdiem')){
?>        
        <div class="row">
            <div class="col-lg-4"></div>	
          <!--  <div class="col-lg-2 text-center">
                <button class="btn btn-primary btn-label-left" onclick=" return generate_pre_check('check');">			    
                        GENERATE PAYSTUB
                </button>                 
            </div> -->
	    <div class="col-lg-2  text-center">
		        <button class="btn btn-primary btn-label-left" onclick=" return ver_factura('<?php echo $name_terapist;?>');">			    
				    VIEW PREVIOUS PAYSTUBS
			    </button>
		</div>
        </div>
        <table id="example_1" class="table table-striped table-bordered" cellspacing="0" width="100%">
			
        <?php                                             
            $datos_result = '';             
            $datos_result .= '
                    <thead>
                        <tr>
                            <th>NAME</th>                            
                            <th>PAY TO</th>                                                                                    
                            <th>TYPE SALARY</th>
                            <th>TIME PAY</th>
                            <th>AMOUNT SALARY</th>
                            <th>AMOUNT HOURS</th>                             
                            <th>EXTRA PAY</th>
                            <th>TOTAL EXTRA PAY</th>
                            <th>GROSS</th>
                            <th>SOCIAL SECURITY</th>
                            <th>MEDICAID</th>
                            <th>FEDERAL WITHHOLDING</th>
                            <th>TOTAL($)</th>			                                
                        </tr>
                    </thead>  
                    <tfoot>
                        <th colspan="12" style="text-align:right"><b>Total:</b></th>
                        <th><div id="total_pre_check"></div><input id="total_pre_check_hidden" name="total_pre_check_hidden" value="0" type="hidden"></th>
                    </tfoot>
            <tbody>';
				
            $i=0;	
            $total_dur = 0;
            $total_pay = 0;
	    $gross = '';
	    $social_security = '';
    	    $medicate = '';
    	    $federal_withholding = '';
    	    $total_pay = '';
	    $cantHours = '';
            while (isset($reporte[$i])){                     
                    $datos_result .= '<tr>';				
                    $datos_result .= '<td>'.$reporte[$i]['first_name'].' '.$reporte[$i]['last_name'].'</td>';                    
                    $datos_result .= '<td>'.$reporte[$i]['pay_to'].'</td>';                                        
                    $datos_result .= '<td>'.$reporte[$i]['type_salary'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['time_pay'].'</td>'; 
                    $datos_result .= '<td>'.$reporte[$i]['amount_salary'].'</td>';
                    
                    $sql_event = "SELECT date_start FROM tbl_event WHERE descripcion LIKE '%".$type_terapist."%' AND date_start >= date(now()) Limit 1";                                        
                    
            $result_event = mysqli_query($conexion, $sql_event);
            $date_pay = ''; // adicionado porque daba error
		    while($row = mysqli_fetch_array($result_event,MYSQLI_ASSOC)){
		            $date_pay = $row['date_start'];
		    }
                    $sql_pre_check = "SELECT * FROM tbl_pre_check WHERE id_employee = ".$reporte[$i]['id_employee']." AND date_pay = '".$date_pay."' AND kind_employee = '".$type_terapist."'";
                    //echo '<br>';
                    $data_pre_check = ejecutar($sql_pre_check,$conexion); $data_pre_check = ejecutar($sql_pre_check,$conexion);                
                    $reporte_pre_check = null;
                    
                    $k = 0;      
                    while($datos1 = mysqli_fetch_assoc($data_pre_check)) {            
                        $reporte_pre_check[$k] = $datos1;
                        $k++;
                    }
                    
                    $gross = '';
                    $social_security = '';
                    $medicate = '';
                    $federal_withholding = '';
                    $total_pay = '';
                    $cantHours = '';
            $total_pay_ = ""; // adicionado para que no de error
		    if($reporte_pre_check != null){
			$gross = '$'.number_format($reporte_pre_check[0]['gross'],2,'.','');
		    	$social_security = number_format($reporte_pre_check[0]['social_security'],2,'.','');
	            	$medicate = number_format($reporte_pre_check[0]['medicate'],2,'.','');
		    	$federal_withholding = number_format($reporte_pre_check[0]['federal_withholding'],2,'.','');
		    	$total_pay = '$'.number_format($reporte_pre_check[0]['total_pay'],2,'.','');
			$total_pay_ = $reporte_pre_check[0]['total_pay'];
			$cantHours = ($reporte_pre_check[0]['amount_hour']);                        
		    }
                    
                    if($reporte[$i]['time_pay'] == 'Bikeweekly')
                        $datos_result .= '<td>'.$reporte[$i]['amount_salary'].'&nbsp;&nbsp;&nbsp;<img src="../../../images/3.gif" onclick="calculatePreCheck('.$reporte[$i]['id_employee'].','.$reporte[$i]['amount_salary'].','.$reporte[$i]['dependencies'].',\''.$reporte[$i]['civil_status'].'\',\''.$reporte[$i]['type_contract'].'\',\''.$reporte[$i]['time_pay'].'\')"></td>';
                    else
                        $datos_result .= '<td><input type="text" value="'.$cantHours.'" name="cantHours_'.$reporte[$i]['id_employee'].'" id="cantHours_'.$reporte[$i]['id_employee'].'" onkeyup="calculatePreCheck('.$reporte[$i]['id_employee'].','.$reporte[$i]['amount_salary'].','.$reporte[$i]['dependencies'].',\''.$reporte[$i]['civil_status'].'\',\''.$reporte[$i]['type_contract'].'\',\''.$reporte[$i]['time_pay'].'\')"></td>';
                    
                    
                    $datos_result .= '<td><a onclick="agregar_nuevo_registro(\'form_extra_pay\',\'<form  id=form_extra_pay name=form_extra_pay><table align=center><tr><td>&nbsp;</td></tr><tr><td align=left>Type: <select class=populate placeholder id=type_extra_pay name=type_extra_pay><option>--- SELECT ---</option><option value=Extra>Extra</option><option value=Bond>Bond</option><option value=Supervision>Supervision</option></select></td></tr><tr><td>&nbsp;</td><tr><td align=left>Extra Pay: <input class=form-control id=extra_pay name=extra_pay><input class=form-control id=id_employee name=id_employee type=hidden value='.$reporte[$i]['id_employee'].'></td></tr><tr><td>&nbsp;</td><tr><td align=left>-Description: <textarea class=form-control id=description name=description></textarea></td></tr></table></form>\',\'../../controlador/payroll/insert_extra_pay.php\');" style="cursor: pointer" title="Add new extra pay">Add New <img src="../../../images/agregar.png" width="20" height="20"/></a></td>';
                    
                    $reporte_extra_pay_asignados = array();
                    if($reporte_pre_check != null){
                        $sql_extra_pay_asignados = "SELECT id_extra_pay FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id'];
                        $data_extra_pay_asignados = ejecutar($sql_extra_pay_asignados,$conexion);                                        
                        $k = 0;      
                        while($datos = mysqli_fetch_assoc($data_extra_pay_asignados )) {            
                            $reporte_extra_pay_asignados[$k] = $datos['id_extra_pay'];
                            $k++;
                        }                        
                    }
                    $where_or = '';
                    if($reporte_pre_check != null){
                        $where_or = "OR id_extra_pay IN ("
                        . " SELECT pcep.id_extra_pay FROM tbl_pre_check_extra_pay pcep "
                        . " LEFT JOIN tbl_extra_pay ep ON ep.id_extra_pay = pcep.id_extra_pay WHERE pcep.id_pre_check = ".$reporte_pre_check[0]['id']
                        . ")";
                    }
                    
                    $sql_extra_pay = "SELECT * FROM tbl_extra_pay WHERE (id_employee = ".$reporte[$i]['id_employee']." AND status_extra_pay = 0) ".$where_or;
                    
                    $data_extra_pay = ejecutar($sql_extra_pay,$conexion);                                               
                             
                    
               
                    
                    
                    
                    
                    $table_extra_pay = '<table border=1 aling=center width=100% cellspacing=5px cellpalding=5px>'
                            . '<tr><td aling=center><b>TYPE</b></td><td aling=center><b>DESCRIPTION</b></td><td align=center><b>EXTRA PAY</b></td></tr>';
                    $exist_extra_pay = 'no';
                    $total_extra_pay = 0;
                    $selected_extra_pay = '';
                    $_array_extra_pay = null;
                    $checked_extra_pay = '';
                    while($datos = mysqli_fetch_assoc($data_extra_pay)) {            
                        $_array_extra_pay = $datos;
                        $exist_extra_pay = 'si';
                        $table_extra_pay .= '<tr>';
                        $table_extra_pay .= '<td>'.$datos['id_extra_pay'].'</td>';
                        $table_extra_pay .= '<td>'.$datos['description'].'</td>';
                        $table_extra_pay .= '<td>'.$datos['extra_pay'].'</td>';
                        if(isset($reporte_extra_pay_asignados[0])){                            
                            if(in_array($datos['id_extra_pay'],$reporte_extra_pay_asignados)){
                                $checked_extra_pay = 'checked=checked';               
                                $selected_extra_pay += $datos['id_extra_pay'].',';
                                $total_extra_pay += $datos['extra_pay'];
                            }else{
                                $checked_extra_pay = '';
                            }
                        }else{
                            $checked_extra_pay = '';                            
                        }                        
                        $table_extra_pay .= '<td><input type=checkbox id=extra_pay_'.$datos['id_extra_pay'].' name=extra_pay_'.$datos['id_extra_pay'].' '.$checked_extra_pay.' onclick=modificar_selected('.$datos['id_extra_pay'].','.$reporte[$i]['id_employee'].','.$datos['extra_pay'].')></td>';
                        $table_extra_pay .= '</tr>';                        
                    }
                    $table_extra_pay .= '</table>';
                                                                                  
                    
                    $datos_result .= '<td><input type="hidden" id="datos_employee_calculate_'.$reporte[$i]['id_employee'].'" name="datos_employee_calculate_'.$reporte[$i]['id_employee'].'" value="'.$reporte[$i]['amount_salary'].','.$reporte[$i]['dependencies'].','.$reporte[$i]['civil_status'].','.$reporte[$i]['type_contract'].','.$reporte[$i]['time_pay'].'" >'
                            . '<input type="hidden" name="selected_extra_pay_hidden_'.$reporte[$i]['id_employee'].'" id="selected_extra_pay_hidden_'.$reporte[$i]['id_employee'].'" value="'.$selected_extra_pay.'">'
                            . '<input type="hidden" name="total_extra_pay_hidden_'.$reporte[$i]['id_employee'].'" id="total_extra_pay_hidden_'.$reporte[$i]['id_employee'].'" value="'.$total_extra_pay.'">';
                    
                    if($_array_extra_pay!= null)
                        $datos_result .= '<a onclick="$(\'#modal_'.$reporte[$i]['id_employee'].'\').modal(\'show\');" style="cursor: pointer"><div id=div_extra_pay_'.$reporte[$i]['id_employee'].'>$'.$total_extra_pay.'</div></a>';
                    else
                        $datos_result .= '$0';
                    $datos_result .= '</td>';
                    
                    $datos_result .= '<td><div id="gross_'.$reporte[$i]['id_employee'].'">'.$gross.'</div></td>';
                    $ie = $reporte[$i]['id_employee']; $as = $reporte[$i]['amount_salary'];
                    $de = $reporte[$i]['dependencies']; $cs = $reporte[$i]['civil_status'];
                    $tc = $reporte[$i]['type_contract']; $tp = $reporte[$i]['time_pay'];
                    $datos_result .= '<td><div id="social_security_' . $ie . '"><input class="mydata" id="ss' . $ie . '" name="ss' . $ie . '" type="text" value="' . $social_security . '" onkeyup="calculatePreCheck(' . $ie . ',' . $as . ',' . $de . ',\'' . $cs . '\',\'' . $tc . '\',\'' .$tp .'\', \'manual\')" /></div></td>';
                    $datos_result .= '<td><div id="medicate_' . $ie . '"><input class="mydata" id="mc' . $ie . '" name="mc' . $ie . '" type="text" value="' . $medicate . '" onkeyup="calculatePreCheck(' . $ie . ',' . $as . ',' . $de . ',\'' . $cs . '\',\'' . $tc . '\',\'' .$tp .'\', \'manual\')" /></div></td>';
                    $datos_result .= '<td><div id="federal_withholding_' . $ie . '"><input class="mydata" id="fw' . $ie . '" name="fw' . $ie . '" type="text" value="' . $federal_withholding . '" onkeyup="calculatePreCheck(' . $ie . ',' . $as . ',' . $de . ',\'' . $cs . '\',\'' . $tc . '\',\'' .$tp .'\', \'manual\')" /></div></td>';
                    $datos_result .= '<td><div id="total_'.$reporte[$i]['id_employee'].'">'.$total_pay.'</div><input id="total_hidden_'.$reporte[$i]['id_employee'].'" name="total_hidden_'.$reporte[$i]['id_employee'].'" value="'.$total_pay_.'" type="hidden">
                    
                    <div class="modal fade" id="modal_'.$reporte[$i]['id_employee'].'" tabindex="-1" role="dialog" aria-labelledby="'.$reporte[$i]['id_employee'].'Label">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">              
                            <div class="modal-body text-center"> 
                                <img src="../../../images/payments.jpg" width="100" height="70"/><br>
                                <h2 class="modal-title" id="'.$reporte[$i]['id_employee'].'Label"><font color="#848484"><b>Select an extra payment!</b></font></h2>
                                <br>
                               '.$table_extra_pay.'
                            </div>
                          </div>
                        </div>
                    </div>
                    </td>';
                                                                         
                    $datos_result .= '</tr>';

                   
                    
                    
                    $i++;		
            }			                        
			$datos_result .= '</tbody>';                         
                        
                        echo $datos_result;                                              
                        //LoadSelect2ScriptExt(function(){$(\'#type_extra_pay\').select2();});
                        ?>
                        
                        
                        
			</table>
                        <div class="row">                            
                            <div class="col-lg-3 text-center">
                                <button class="btn btn-success" onclick="return generate_pre_check('pre_check');">    
                                    GENERATE PRE CHECK
                                </button>  
                                
                                <input type="hidden" id="name_employee" name="name_employee" value="<?php echo str_replace(' ','|',sanitizeString($conexion, $_REQUEST['name_terapist']));?>" >
                                <input type="hidden" id="date_start" name="date_start" value="<?php echo $date_start;?>" >
                                <input type="hidden" id="date_end" name="date_end" value="<?php echo $date_end;?>" >    
                                <input type="hidden" id="type_employee" name="type_employee" value="<?php echo $type_terapist;?>" >
                            </div>
                        </div>
			<script>showSumAdministrative();</script>
        
  <?php      
    }else{
    if($type_terapist == 'Therapist' && $_REQUEST['type_salary'] == 'Perdiem'){    
   ?>
    <div class="form-group has-feedback centered">
		<div class="col-lg-5  text-center"></div>			
		<!--<div class="col-lg-2  text-center">
                        <button class="btn btn-primary btn-label-left" onclick=" return generar_factura('<?php echo $date_start;?>','<?php echo $date_end;?>','<?php echo $time_pay;?>');">			    
				    GENERATE PAYSTUB
			    </button>
		</div>-->	
		<div class="col-lg-2  text-center">
                        <button class="btn btn-primary btn-label-left" onclick=" return ver_factura('<?php echo $name_terapist;?>');">			    
				    VIEW PREVIOUS PAYSTUBS
			    </button>
		</div>
    </div>
    <div class="form-group has-feedback centered">
                <?php if(isset($reporte[0])){?>
                <div class="col-lg-7 text-left">
                    <a onclick="agregar_nuevo_registro('form_extra_pay','<form  id=\'form_extra_pay\' name=\'form_extra_pay\'><table align=\'center\'><tr><td>&nbsp;</td></tr><tr><td align=\'left\'>Type: <select class=\'populate placeholder\' id=\'type_extra_pay\' name=\'type_extra_pay\'><option value=\'\'>--- SELECT ---</option><option value=\'Extra\'>Extra</option><option value=\'Bond\'>Bond</option><option value=\'Supervision\'>Supervision</option></select></td></tr><tr><td>&nbsp;</td><tr><td align=\'left\'>Extra Pay: <input class=\'form-control\' id=\'extra_pay\' name=\'extra_pay\'><input class=\'form-control\' id=\'id_employee\' name=\'id_employee\' type=\'hidden\' value=\'<?php echo $reporte[0]['id_employee'];?>\'></td></tr><tr><td>&nbsp;</td><tr><td align=\'left\'>-Description: <textarea class=\'form-control\' id=\'description\' name=\'description\'></textarea></td></tr></table></form>','../../controlador/payroll/insert_extra_pay.php');LoadSelect2ScriptExt(function(){$('#type_extra_pay').select2();});" style="cursor: pointer">Add new extra pay <img src="../../../images/agregar.png" width="20" height="20"/></a>
                </div>
		<div class="col-lg-3  text-right">
                        Select All&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="seleccionar_todo" name="seleccionar_todo" onclick="comprobar_seleccion();showSum();">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>                
                <div class="col-lg-2  text-right">
                        Select Paid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="seleccionar_todo_paid" name="seleccionar_todo_paid" onclick="comprobar_seleccion_paid();showSum();">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>                
		<?php }?>
	</div> 	
   
<?php }else{
?>
<div class="col-lg-12 text-center" >
	<label>THIS THERAPIST DOESN'T HAVE THERAPIES FOR SELECTED DATES</label>    
</div>
<?php
} 
?>
</div>

<div class="col-lg-12">&nbsp;</div>	
<?php 

if(isset($reporte[0]) &&  $_REQUEST['type_salary'] == 'Perdiem'){?>        
            <div class="col-lg-12" id="prueba_impresion">		                           
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			
        <?php                         
        
            $datos_result = '';
                        /*
            
            */
            $datos_result .= '
                    <thead>
                        <tr>
                            <th>TREAT_ID</th>                            
                            <th>VISIT ID</th>
                            <th>DATE</th>                            
                            <th>PATIENT</th>                            
                            <th>UNITS</th>
                            <th>LOCATION</th>
                            <th>TYPE</th>
                            <th>CPT</th>                            
                            <th>DUR</th>
                            <th>PAY RATE($)</th>
                            <th>TOTAL($)</th>			    
                            <th>MARCAR</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="8" style="text-align:right">Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
            <tbody>';
	    $sql_event = "SELECT date_start FROM tbl_event WHERE descripcion LIKE '%".$type_terapist."%' AND date_start >= '".date('Y-m-d')."' Limit 1";
	    $result_event = mysqli_query($conexion, $sql_event);
	    while($row = mysqli_fetch_array($result_event,MYSQLI_ASSOC)){
	            $date_pay = $row['date_start'];
	    }
            $sql_pre_check = "SELECT * FROM tbl_pre_check pc 
	    LEFT JOIN tbl_pre_check_treatments pct ON pct.id_pre_check = pc.id 
	    WHERE id_employee = ".$reporte[0]['id_employee']." AND 
	    date_pay = '".$date_pay."' AND kind_employee = '".$type_terapist."'";
            

            $data_pre_check = ejecutar($sql_pre_check,$conexion);                
            $reporte_pre_check = array();
	    $array_treatments = array();
            $k = 0;      
            while($datos = mysqli_fetch_assoc($data_pre_check)) {            
                $reporte_pre_check[$k] = $datos;
		$array_treatments[$k] = $datos['id_treatments'];
                $k++;
            }
	    //die();
            $i=0;	
            $total_dur = 0;
            $total_pay = 0;
            $treatments_paid = '';
            while (isset($reporte[$i])){                     
                    if( (int)(str_replace('$', '', $reporte[$i]['ins_paid'])) > 0 || $reporte[$i]['treat_charges_status'] == 2){
                        $treatments_paid .= ($treatments_paid == '')?$reporte[$i]['id_treatments']:','.$reporte[$i]['id_treatments'];
                    }
                    $datos_result .= '<tr>';				
                    $datos_result .= '<td>'.$reporte[$i]['campo_4'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_3'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_1'].'</td>';                    
                    $datos_result .= '<td>'.$reporte[$i]['campo_6'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_17'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_2'].'</td>'; 
                    $datos_result .= '<td>'.$reporte[$i]['campo_10'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_11'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_15'].'</td>';
                    $total_dur +=  $reporte[$i]['campo_15'];
                        $pay_treatment = 0;
                        if($reporte[$i]['type'] == 'TX' || $reporte[$i]['type'] == 'SUPERVISION'){
                            if($reporte[$i]['campo_2'] == 11)
                                $pay_treatment = $reporte[$i]['tx_in'];
                            else
                                $pay_treatment = $reporte[$i]['tx_out'];
                        }else{
                            if($reporte[$i]['type'] == 'EVAL'){
                                if($reporte[$i]['type'] == 11)
                                    $pay_treatment = $reporte[$i]['eval_in'];
                                else
                                    $pay_treatment = $reporte[$i]['eval_out'];
                            }else{
                                $pay_treatment = $reporte[$i]['transportation'];
                            }
                        }                    
                    $datos_result .= '<td>'.number_format($pay_treatment,2,'.',',').'</td>';
                    $total_pay_treatment = 0;
                    $total_pay_treatment = $reporte[$i]['campo_15'] * $pay_treatment/60;
                    $datos_result .= '<td>'.number_format($total_pay_treatment,2,'.',',').'</td>';
		    if(in_array($reporte[$i]['id_treatments'],$array_treatments)) $checked = 'checked';
		    else $checked = '';
                    $datos_result .= '<td align="center"><input type="checkbox" onclick="showSum();" name="'.$reporte[$i]['id_treatments'].'-'.$total_pay_treatment.'" id="'.$reporte[$i]['id_treatments'].'" value="'.$reporte[$i]['id_treatments'].'" '.$checked.'></td>';
                    $datos_result .= '</tr>';

                    $i++;		
           }	
           $treat_id = $reporte[$i-1]['campo_4'];
	   
            if($reporte_pre_check[0]['id_pre_check'] != null){
                $where_pre_check = " OR pcep.id_pre_check = ".$reporte_pre_check[0]['id_pre_check'];
            }
            $sql_extra_pay = "SELECT *,ep.id_extra_pay as id_extra_pay_ FROM tbl_extra_pay ep 
            LEFT JOIN tbl_pre_check_extra_pay pcep ON pcep.id_extra_pay = ep.id_extra_pay 
            WHERE (status_extra_pay = 0 AND id_employee = ".$reporte[0]['id_employee']." AND pcep.id_pre_check IS NULL) ".$where_pre_check;                    
                    //. "id_extra_pay NOT IN ("
                    //. " SELECT pcep.id_extra_pay FROM tbl_pre_check_extra_pay pcep "
                    //. " LEFT JOIN tbl_extra_pay ep ON ep.id_extra_pay = pcep.id_extra_pay WHERE ep.id_employee = ".$reporte[0]['id_employee']
                    //. ")";

            $data_extra_pay = ejecutar($sql_extra_pay,$conexion);                
            $reporte_extra_pay = array();            
            $g = 0;      
            while($datos = mysqli_fetch_assoc($data_extra_pay)) {            
                $reporte_extra_pay[$g] = $datos;
                //$array_extra_pay[$k] = $datos['id_extra_pay'];
                $g++;
            }

            $array_extra_pay = array();
            if($reporte_pre_check[0]['id_pre_check'] != null){
                $sql_extra_pay_pre_check = "SELECT * FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'];

                $data_extra_pay = ejecutar($sql_extra_pay_pre_check,$conexion);                            
                
                $k = 0;      
                while($datos = mysqli_fetch_assoc($data_extra_pay)) {                            
                    $array_extra_pay[$k] = $datos['id_extra_pay'];
                    $k++;
                }
            }
           
            
           $y = 0;
           $separador = '';
           while (isset($reporte_extra_pay[$y])){                
                $datos_result .= '<tr>';				
                $datos_result .= '<td>'.($treat_id + 1).'</td>';
                $datos_result .= '<td>-</td>';
                $datos_result .= '<td>'.date("m/d/Y",strtotime($reporte_extra_pay[$y]['date'])).'</td>';                    
                $datos_result .= '<td>-</td>';
                $datos_result .= '<td>-</td>';
                $datos_result .= '<td>-</td>'; 
                $datos_result .= '<td>'.$reporte_extra_pay[$y]['type_extra_pay'].'</td>';
                $datos_result .= '<td>'.$reporte_extra_pay[$y]['description'].'</td>';
                $datos_result .= '<td>0</td>';                            
                $datos_result .= '<td>'.number_format($reporte_extra_pay[$y]['extra_pay'],2,'.',',').'</td>';                
                $datos_result .= '<td>'.number_format($reporte_extra_pay[$y]['extra_pay'],2,'.',',').'</td>';
                
                if(in_array($reporte_extra_pay[$y]['id_extra_pay'],$array_extra_pay)) $checked = 'checked';
                else $checked = '';
                                
                $datos_result .= '<td align="center"><input type="checkbox" onclick="showSum();" name="ext_'.($treat_id + 1).'-'.$reporte_extra_pay[$y]['extra_pay'].'" id="ext_'.($treat_id + 1).'-'.$reporte_extra_pay[$y]['extra_pay'].'" value="'.$reporte_extra_pay[$y]['id_extra_pay_'].'" '.$checked.'></td>';
                $datos_result .= '</tr>';
               $treat_id = $treat_id + 1; 
               $y++;
           }
           
           
           $datos_result .= '</tbody>';                         
                        
                        echo $datos_result;                                                
                        
                        ?>
			</table>		 
            </div>


</div>
<input type="hidden" value="<?php echo $treatments_paid;?>" id="treatments_paid_insurance" name="treatments_paid_insurance" readonly>
<?php

        $encabezado_datos_result = '<br><br><table><tr><td><img src="images/LOGO_1.png"></td></tr><tr><td align="center"><font size="3" color="#BDBDBD"><b>THERAPIES '.$date_start.' - '.$date_end.'</b></td></tr></table><br><br><table border="1" width="100%" bordercolor="#A4A4A4" cellspacing="0" cellspacing="0">';
        $inferior_datos_result = '</table>'; 

        $imprimir_datos_result = $encabezado_datos_result.$datos_result.$inferior_datos_result;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-12">
            <a onclick="agregar_nuevo_registro('form_extra_pay','<form  id=\'form_extra_pay\' name=\'form_extra_pay\'><table align=\'center\'><tr><td>&nbsp;</td></tr><tr><td align=\'left\'>Type: <select class=\'populate placeholder\' id=\'type_extra_pay\' name=\'type_extra_pay\'><option value=\'\'>--- SELECT ---</option><option value=\'Extra\'>Extra</option><option value=\'Bond\'>Bond</option><option value=\'Supervision\'>Supervision</option></select></td></tr><tr><td>&nbsp;</td><tr><td align=\'left\'>Extra Pay: <input class=\'form-control\' id=\'extra_pay\' name=\'extra_pay\'><input class=\'form-control\' id=\'id_employee\' name=\'id_employee\' type=\'hidden\' value=\'<?php echo $reporte[0]['id_employee'];?>\'></td></tr><tr><td>&nbsp;</td><tr><td align=\'left\'>-Description: <textarea class=\'form-control\' id=\'description\' name=\'description\'></textarea></td></tr></table></form>','../../controlador/payroll/insert_extra_pay.php');LoadSelect2ScriptExt(function(){$('#type_extra_pay').select2();});" style="cursor: pointer">Add new extra pay <img src="../../../images/agregar.png" width="20" height="20"/></a>           
        </div>
    </div>	
</div
<br><br>
<div class="row">
    <div class="col-lg-3"></div>	
<div class="col-lg-3 text-center">
    <button class="btn btn-success" onclick="imprimirTerapiasSeleccionadas();">    
            PRINT SELECTED 
    </button>    
    <input type="hidden" id="selectedTreatments" name="selectedTreatments" value="" >
    <input type="hidden" id="date_start" name="date_start" value="<?php echo $date_start;?>" >
    <input type="hidden" id="date_end" name="date_end" value="<?php echo $date_end;?>" >    
    <input type="hidden" id="type_employee" name="type_employee" value="<?php echo $type_terapist;?>" >
</div>
<div class="col-lg-3 text-center">
        <button class="btn btn-success" onclick=" return send_therapies_email('<?php echo $reporte[0]['email'];?>');">    
            SEND MAIL
    </button>    
</div>
<div class="col-lg-3"></div>
<!--<div class="col-lg-12" id="datos_imprimir"><?php echo $imprimir_datos_result;?></div>-->
</div>
	<!--<script>showSum();</script>-->
        <?php }
        
    }?>

