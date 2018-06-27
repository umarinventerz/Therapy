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
$conexion = conectar();
$sql  = "SELECT * FROM tbl_check_treatments ct LEFT JOIN tbl_treatments t ON t.id_treatments = ct.id_treatment "
        . " left join employee emp on t.license_number = emp.licence_number
            left join employee_treatment emp_t on emp_t.id_employee = emp.id 
            left join cpt c on c.cpt = t.campo_11 and c.discipline = t.campo_10 
            WHERE id_check =".sanitizeString($conexion, $_GET['id_check']).";";
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
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="js/funciones.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
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
			]
		} );
	} );                
        
                                                        
    </script>
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
                    $total_pay_treatment = $reporte[$i]['campo_15'] * $pay_treatment/60;
                    $datos_result .= '<td>'.number_format($total_pay_treatment,2,'.',',').'</td>';                    
                    $datos_result .= '</tr>';
                    $sum_total_pay_treatment += $total_pay_treatment;
                    $i++;		
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

