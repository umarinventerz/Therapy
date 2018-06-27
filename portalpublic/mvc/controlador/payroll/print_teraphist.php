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
    
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="../../../daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="../../../daterangepicker/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../../daterangepicker/moment.js"></script>
    <script type="text/javascript" src="../../../daterangepicker/daterangepicker.js"></script>

    <link href="../../../css/portfolio-item.css" rel="stylesheet">
<?php
$selectedTreatments = sanitizeString($conexion, $_GET['selectedTreatments']);
list($m,$d,$y) = explode('/',sanitizeString($conexion, $_REQUEST['date_start']));
list($mm,$dd,$yy) = explode('/',sanitizeString($conexion, $_REQUEST['date_end']));
$date_start = $y.'-'.$m.'-'.$d; 
$date_end = $yy.'-'.$mm.'-'.$dd;

$type_employee = sanitizeString($conexion, $_REQUEST['type_employee']);
$conexion = conectar();
list($identificadores,$extras) = explode('**',sanitizeString($conexion, $_REQUEST['identificadores_check']));

$identificadores = substr ($identificadores, 0, strlen($identificadores) - 1);
$extras = substr ($extras, 0, strlen($extras) - 1);

if($identificadores != null)
    $array_identificadores = explode(',',$identificadores);
if($extras != null)
    $array_extras = explode(',',$extras);


$t = 0;
 $sql  = "SELECT *,emp.id as id_employee FROM tbl_treatments t left join employee emp on t.license_number = emp.licence_number 
            left join employee_treatment emp_t on emp_t.id_employee = emp.id 
            left join cpt c on c.cpt = t.campo_11 and c.discipline = t.campo_10
            WHERE id_treatments IN (".$identificadores.");";
$resultado = ejecutar($sql,$conexion);

        $reporte = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {     
            if($datos['type'] == 'TX' || $datos['type'] == 'SUPERVISION'){
                    if($datos['campo_2'] == 11)
                            {$amount_tx_in += ($datos['campo_15'] * $datos['tx_in']/60); $count_tx_in++;}
                    else    	
                            {$amount_tx_out += ($datos['campo_15'] * $datos['tx_out']/60); $count_tx_out++;}
                }           			
                else{
                    if($datos['type'] == 'EVAL'){
                            if($datos['campo_2'] == 11)
                                    {$amount_eval_in += $datos['campo_15'] * $datos['eval_in']/60; $count_eval_in++;}
                            else
                                    {$amount_eval_out += $datos['campo_15'] * $datos['eval_out']/60;; $count_eval_out++;}
                    }else{
                            $count_transportation++;
                            $transportation = $datos['transportation'];
                    }
                }       
            $reporte[$i] = $datos;
            $i++;
        }
	$gross = $amount_tx_in + $amount_tx_out + $amount_eval_in + $amount_eval_out + ($count_transportation*$transportation);
        
        if($extras != null){
            $sql_extra_pay = 'SELECT * FROM tbl_extra_pay where id_extra_pay IN ('.$extras.')';
            $resultado_extra_pay = ejecutar($sql_extra_pay,$conexion);
            $reporte_extra_pay = array();
            $total_extra_pay = 0;
            $o = 0;
            while($datos_extra_pay = mysqli_fetch_assoc($resultado_extra_pay)) {
                $total_extra_pay += $datos_extra_pay['extra_pay'];
                $reporte_extra_pay[$o] = $datos_extra_pay;
                $o++;
            }
            $gross += $total_extra_pay;
        }
        
        
	if($reporte[0]['type_contract'] == '1099'){
		$social_security = 0;
		$medicate = 0;
		$federal_withholding = 0;
	    }else{
		$social_security = $gross * 6.2/100;
		$medicate = $gross * 1.45/100;
		$sql_federal  = "SELECT dependence_".$reporte[0]['dependencies']." as dependence FROM federal_withholdings fw WHERE '".$gross."'>=at_least and '".$gross."'<but_less_then AND civil_status = '".$reporte[0]['civil_status']."';";
		$data_federal_withholdings = ejecutar($sql_federal,$conexion);                

		$reporte_federal_withholdings = array();

		$k = 0;      
		while($datos = mysqli_fetch_assoc($data_federal_withholdings)) {            
		    $reporte_federal_withholdings[$k] = $datos;
		    $k++;
		}
		$federal_withholding = $reporte_federal_withholdings[0]['dependence'];
		
	    }

        $sql_event = "SELECT date_start FROM tbl_event WHERE descripcion LIKE '%".$type_employee."%' AND date_start > '".date('Y-m-d')."' Limit 1";
        $result_event = mysqli_query($conexion, $sql_event);
        while($row = mysqli_fetch_array($result_event,MYSQLI_ASSOC)){
        	$date_pay = $row['date_start'];
    	}

        //$date_pay = '2016-11-11';
        $varificarPreCheck = "SELECT * FROM tbl_pre_check WHERE `id_employee` = ".$reporte[0]['id_employee']." AND date_pay='".$date_pay."'";
        $data_pre_check = ejecutar($varificarPreCheck,$conexion);

        $reporte_pre_check = array();

        $i = 0;      
        while($datos = mysqli_fetch_assoc($data_pre_check)) {            
            $reporte_pre_check[$i] = $datos;
            $i++;
        }
        
        if($reporte_pre_check[0]!= null){
           
           $sql_delete_rows = 'DELETE FROM tbl_pre_check_treatments WHERE id_pre_check = '.$reporte_pre_check[0]['id'];
           ejecutar($sql_delete_rows,$conexion); 
           
           $sql_delete_rows = 'DELETE FROM tbl_pre_check WHERE id = '.$reporte_pre_check[0]['id'];
           ejecutar($sql_delete_rows,$conexion);                       
            
           $sql_delete_rows = 'DELETE FROM tbl_pre_check_extra_pay WHERE id_pre_check = '.$reporte_pre_check[0]['id'];
           ejecutar($sql_delete_rows,$conexion); 
        }        

        $sql_insert_rows = 'INSERT INTO tbl_pre_check (`id_employee`, `total_pay`, `date_start`, `date_end`, `kind_employee`, `date_pay`, `gross`, `social_security`, `medicate`, `federal_withholding`) values ('.$reporte[0]['id_employee'].','.$selectedTreatments.','.'\''.$date_start.'\',\''.$date_end.'\',\''.$type_employee.'\',\''.$date_pay.'\','.number_format($gross,2,'.','').','.number_format($social_security,2,'.','').','.number_format($medicate,2,'.','').','.number_format($federal_withholding,2,'.','').');';
        ejecutar($sql_insert_rows,$conexion);
        
        $max_id_pre_check = "SELECT MAX(id) AS id_pre_check FROM tbl_pre_check";
	$result_pre_check = mysqli_query($conexion, $max_id_pre_check);
	$id_tbl_pre_check = 0;
	while($row = mysqli_fetch_array($result_pre_check,MYSQLI_ASSOC)){
		$id_tbl_pre_check = $row['id_pre_check'];
	}
        
        $i = 0;        
        while(isset($array_identificadores[$i])){

                $sql_insert_rows = 'INSERT INTO tbl_pre_check_treatments (id_pre_check,id_treatments) values ('.$id_tbl_pre_check.','.$array_identificadores[$i].');';                                
                ejecutar($sql_insert_rows,$conexion);

                $i++;
        }
        
        $f = 0;
        while(isset($array_extras[$f])){
            $sql_insert_rows = 'INSERT INTO tbl_pre_check_extra_pay (id_pre_check,id_extra_pay) values ('.$id_tbl_pre_check.','.$array_extras[$f].');';                                
            ejecutar($sql_insert_rows,$conexion);
            $f++;
        }
        
?>
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/funciones.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">	
    <!-- End Style-->
    <!-- <script type="text/javascript" language="javascript" class="init">
	    
	$(document).ready(function() {
		$('#example').DataTable({
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
        
                                                        
    </script> -->
</head>

<div class="row">

<div class="col-lg-12">&nbsp;</div>	
	    <div class="col-lg-1" ></div>
            <div class="col-lg-10" id="prueba_impresion">
		
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			
        <?php                         
        
            $datos_result = '';
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
                        </tr>
                    </thead>

            <tbody>';
				
            $i=0;		
            $sum_total_pay_treatment = 0;
            $total_dur = 0;
            while (isset($reporte[$i])){ 

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
                    
                        if($reporte[$i]['type'] == 'TX' || $reporte[$i]['type'] == 'SUPERVISION'){
                            if($reporte[$i]['campo_2'] == 11)
                                $pay_treatment = $reporte[$i]['tx_in'];
                            else
                                $pay_treatment = $reporte[$i]['tx_out'];
                        }else{
                            if($reporte[$i]['type'] == 'EVAL'){
                                if($reporte[$i]['campo_2'] == 11)
                                    $pay_treatment = $reporte[$i]['eval_in'];
                                else
                                    $pay_treatment = $reporte[$i]['eval_out'];
                            }else{
                                $pay_treatment = $reporte[$i]['transportation'];
                            }
                        }                    
                    $datos_result .= '<td>'.number_format($pay_treatment,2,'.',',').'</td>';
                    $total_pay_treatment = 0;
                    
                    if($reporte[$i]['type'] == 'TX' || $reporte[$i]['type'] == 'EVAL' || $reporte[$i]['type'] == 'SUPERVISION')
                        $total_pay_treatment = $reporte[$i]['campo_15'] * $pay_treatment/60;
                    else
                        $total_pay_treatment = $pay_treatment;
                    
                    $datos_result .= '<td>'.number_format($total_pay_treatment,2,'.',',').'</td>';                    
                    $datos_result .= '</tr>';
                    $sum_total_pay_treatment += $total_pay_treatment;
                    $i++;		
            }		
            $treat_id = $reporte[$i-1]['campo_4'];
            $y = 0;           
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
                $datos_result .= '</tr>';
                $sum_total_pay_treatment += $reporte_extra_pay[$y]['extra_pay'];
                $treat_id = $treat_id + 1; 
               $y++;
            }
            
            
                        $datos_result .= '<tr><td colspan="8" align="right"><b>Total:</b></td>';
                        $datos_result .= '<td><b>'.$total_dur.' min</b></td>';
                        $datos_result .= '<td>&nbsp;</td>';
                        $datos_result .= '<td><b>$'.$sum_total_pay_treatment.'</b></td>';
			$datos_result .= '</tbody>';                         
                        
                        echo $datos_result;                                                
                        
                        ?>
			</table>
		 
            </div>


</div>
<?php

        $encabezado_datos_result = '<br><br><table><tr><td><img src="../../../images/LOGO_1.png"></td></tr><tr><td align="center"><font size="3" color="#BDBDBD"><b>THERAPIES '.$date_start.' - '.$date_end.'</b></td></tr></table><br><br><table border="1" width="100%" bordercolor="#A4A4A4" cellspacing="0" cellspacing="0">';
        $inferior_datos_result = '</table>'; 

        $imprimir_datos_result = $encabezado_datos_result.$datos_result.$inferior_datos_result;
?>

