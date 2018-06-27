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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/fileinput.js" type="text/javascript"></script>
    <script src="../../../js/funciones.js" type="text/javascript"></script>
</head>
<?php
$conexion = conectar();
$identificadores = substr (sanitizeString($conexion, $_REQUEST['identificadores_check']), 0, strlen($_REQUEST['identificadores_check']) - 1);
$array_identificadores  = explode(',',$identificadores);

$_GET['name_terapist'] = str_replace('-.','', sanitizeString($conexion, $_GET['name_terapist']));
$_GET['name_terapist'] = rtrim($_GET['name_terapist']);
list($_GET['name_terapist'],$time_pay) = explode('-',$_GET['name_terapist']);
$sql_employee  = "SELECT *, AES_DECRYPT(e.social_security,'kidworks_therapy') as socialsecurity,e.id as id_e FROM employee e LEFT JOIN employee_treatment et 
ON et.id_employee = e.id WHERE (licence_number = '".$_GET['name_terapist']."' OR concat(e.last_name,', ',e.first_name) LIKE '%".$_GET['name_terapist']."%');";
$data_employee = ejecutar($sql_employee,$conexion);

        $reporte_employee = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($data_employee)) {            
            $reporte_employee[$i] = $datos;
            $i++;
        }

$name_employee = $reporte_employee[0]['last_name'].', '.$reporte_employee[0]['first_name'];

list($m,$d,$y) = explode('/',sanitizeString($conexion, $_GET['input_date_start']));
list($mm,$dd,$yy) = explode('/',sanitizeString($conexion, $_GET['input_date_end']));
$_GET['input_date_start'] = $y.'-'.$m.'-'.$d; 
$_GET['input_date_end'] = $yy.'-'.$mm.'-'.$dd;

 $sql_event = "SELECT date_start FROM tbl_event WHERE descripcion LIKE '%".$reporte_employee[0]['kind_employee']."%' AND date_start >= '".date('Y-m-d')."' Limit 1";
$result_event = mysqli_query($conexion, $sql_event);
while($row = mysqli_fetch_array($result_event,MYSQLI_ASSOC)){
        $date_pay = $row['date_start'];
} 

$varificarCheck = "SELECT * FROM tbl_check WHERE `id_employee` = ".$reporte_employee[0]['id_e']." AND `pay_date`='".$date_pay."'";
$data_check = ejecutar($varificarCheck,$conexion);

$reporte_check = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($data_check)) {            
    $reporte_check[$i] = $datos;
    $i++;
}
if($reporte_check[0]!= null){

	echo '<script>alert(\'PAYSTUB SAVED\')</script>';
	echo '<script>window.close();</script>';
}else{

	if($identificadores == '' && $_GET['cantHours']=='undefined' && $reporte_employee[0]['time_pay'] != 'Bikeweekly'){
		echo '<script>alert(\'No existen datos para emitir la factura\')</script>';
		echo '<script>window.close();</script>';
	}

	if($identificadores != ''){
		$i=0;
		while(isset($array_identificadores[$i])){
		
			$sql_insert_rows = 'INSERT INTO tbl_status_treatments (id_treatments,id_description_status_treatments) values ('.$array_identificadores[$i].',3);';                                
			ejecutar($sql_insert_rows,$conexion);
		
			$i++;
		}
		$sql  = "SELECT * FROM tbl_treatments t LEFT JOIN cpt c ON c.cpt = t.campo_11 AND c.discipline = t.campo_10 WHERE t.id_treatments in (".$identificadores.");";
		$resultado = ejecutar($sql,$conexion);

		$reporte = array();

		$i = 0;      
		$count_tx_in = 0;
		$count_tx_out = 0;
		$count_eval_in = 0;
		$count_eval_out = 0;
		$amount_tx_in = 0;
		$amount_tx_out = 0;
		$amount_eval_in = 0;
		$amount_eval_out = 0;
		$count_transportation = 0;
		while($datos = mysqli_fetch_assoc($resultado)) {
		    if($datos['type'] == 'TX' || $datos['type'] == 'SUPERVISION'){
		    	if($datos['campo_2'] == 11)
			    	{$amount_tx_in += ($datos['campo_17']/4)*$reporte_employee[0]['tx_in']; $count_tx_in++;}
			else    	
				{$amount_tx_out += ($datos['campo_17']/4)*$reporte_employee[0]['tx_out']; $count_tx_out++;}
		    }           			
		    else{
		    	if($datos['type'] == 'EVAL'){
			    	if($datos['campo_2'] == 11)
				    	{$amount_eval_in += $datos['campo_17']*$reporte_employee[0]['eval_in']; $count_eval_in++;}
			    	else
			    		{$amount_eval_out += $datos['campo_17']*$reporte_employee[0]['eval_out']; $count_eval_out++;}
	    		}else{
	    			$count_transportation++;
	    		}
		    }			
		    $reporte[$i] = $datos;
		    $i++;
		}
	}
        //GROSS = $A1
	if($reporte_employee[0]['amount_salary'] != '' && $reporte_employee[0]['amount_salary']!= null){
		if($reporte_employee[0]['time_pay'] == 'Bikeweekly')
			$A1 = $reporte_employee[0]['amount_salary'];
		else{
			if($_GET['cantHours'] >80){
				$dif_hour = sanitizeString($conexion, $_GET['cantHours']) - 80;
				$ext_hour = $dif_hour*(1.5*$reporte_employee[0]['amount_salary']);
				$A1 = $reporte_employee[0]['amount_salary']*80;
				$A1 = $A1 + $ext_hour;
			}else{
				$A1 = $reporte_employee[0]['amount_salary']*sanitizeString($conexion, $_GET['cantHours']);
			}			
		}
			
	}else{
		$A1 = $amount_tx_in + $amount_tx_out + $amount_eval_in + $amount_eval_out + ($count_transportation*$reporte_employee[0]['transportation']);
	}
	/**************************************************************************************/
	/************************* Calculo del Federal withholding ****************************/
	/**************************************************************************************/
	$sql_federal  = "SELECT dependence_".$reporte_employee[0]['dependencies']." as dependence FROM federal_withholdings fw WHERE '".$A1."'>=at_least and '".$A1."'<but_less_then AND civil_status = '".$reporte_employee[0]['civil_status']."';";
	$data_federal_withholdings = ejecutar($sql_federal,$conexion);                
        
        $reporte_federal_withholdings = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($data_federal_withholdings)) {            
            $reporte_federal_withholdings[$i] = $datos;
            $i++;
        }
        
        if($reporte_federal_withholdings[0]['dependence'] == null){
            $variable_federal = 0;
        } else {
            $variable_federal =  $reporte_federal_withholdings[0]['dependence'];
        }

	//****************************** Fin del calculo ************************************/
	
	if($identificadores != ''){
		$updateStatusPay = "UPDATE tbl_treatments SET pay = TRUE WHERE id_treatments in (".$identificadores.")";
		ejecutar($updateStatusPay,$conexion);
	}
	
	$consulta_year_to_date = "SELECT * FROM tbl_employee_year_to_date WHERE id_Employee =".$reporte_employee[0]['id_e'];
	$data_year_to_date = ejecutar($consulta_year_to_date,$conexion);
	$reporte_employee_year_to_Date = array();        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($data_year_to_date)) {            
            $reporte_employee_year_to_Date[$i] = $datos;
            $i++;
        }
	if($reporte_employee[0]['type_contract'] != '1099'){
		$C1 = ($A1*6.2/100) + $reporte_employee_year_to_Date[0]['social_security'];
		$C2 = ($A1*1.45/100) + $reporte_employee_year_to_Date[0]['medicate'];
		$C3 = $reporte_federal_withholdings[0]['dependence'] + $reporte_employee_year_to_Date[0]['federal_withholdings'];
		$c1 = ($A1*6.2/100);
		$c2 = ($A1*1.45/100);
		$c3 = ($reporte_federal_withholdings[0]['dependence']);
	}else{
		$C1 = 0;
		$C2 = 0;
		$C3 = 0;
		$c1 = 0;
		$c2 = 0;
		$c3 = 0;
		$variable_federal = 0;
	}
	
	$A2 = $A1 + $reporte_employee_year_to_Date[0]['gross'];

	$max_id = "SELECT MAX(number_check) AS number_check FROM tbl_check;";
	$result01 = mysqli_query($conexion, $max_id);					
	$id_check = 0;
	while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
		$id_check = $row['number_check'];
	}
	$id_check = ($id_check+1);
	
        $total_pay = $A1 - ($c1 + $c2 + $c3);
	$insert_check = "INSERT INTO tbl_check (`id_employee`,`gross`,`social_security`,`medicate`,`federal_withholdings`,`date_start`,`date_end`,`number_check`,`gross_t`,`social_security_t`,`medicate_t`,`federal_withholdings_t`,`route_check`,`total_pay`) VALUES 
	(".$reporte_employee[0]['id_e'].",".number_format($A1, 2, ".", "").",".number_format($c1, 2, ".", "").",".number_format($c2, 2, ".", "").",".number_format($variable_federal, 2, ".", "").",'".sanitizeString($conexion, $_GET['input_date_start'])."','".sanitizeString($conexion, $_GET['input_date_end'])."',".$id_check.",".number_format($A2, 2, ".", "").",".number_format($C1, 2, ".", "").",".number_format($C2, 2, ".", "").",".number_format($C3, 2, ".", "").",'',".$total_pay.")";
	ejecutar($insert_check,$conexion);
	
	$update_year_to_date = " UPDATE tbl_employee_year_to_date SET gross =".number_format($A2, 2, ".", "").",social_security=".number_format($C1, 2, ".", "").",medicate = ".number_format($C2, 2, ".", "").",federal_withholdings=".number_format($C3, 2, ".", "")." WHERE id_employee=".$reporte_employee[0]['id_e'];
	ejecutar($update_year_to_date,$conexion);
	
	$max_id_check = "SELECT MAX(id) AS id_check FROM tbl_check";
	$result_check = mysqli_query($conexion, $max_id_check);
	$id_tbl_check = 0;
	while($row = mysqli_fetch_array($result_check,MYSQLI_ASSOC)){
		$id_tbl_check = $row['id_check'];
	}
	if($identificadores != ''){	
		$j=0;
		while(isset($array_identificadores[$j])){
			$insert_tbl_check_treatment = 'INSERT INTO tbl_check_treatments (`id_check`,`id_treatment`) VALUES ('.$id_tbl_check.','.$array_identificadores[$j].')';
			ejecutar($insert_tbl_check_treatment,$conexion);
                        //################NUEVO CODIGO###########                            
                            $updateTreatments = "UPDATE tbl_treatments SET pay = true WHERE id_treatments = ".$array_identificadores[$j];
                            ejecutar($updateTreatments,$conexion);
                            //############# FIN NUEVO CODIGO ########
			$j++;
		}
	}	
}
	$divTeraphist = '<div id="teraphist" style="display:none;">';
	$divTeraphist .= '<table class="table table-striped" border="1">
			<thead>
			    <tr>
				<th>Identifiquer</th>
				<th>TREATMENT ID</th>
		                <th>LOCATION</th>
		                <th>TREATMENT DATE</th>
				<th>VISIT ID</th>
				<th>PATIENT</th>
				<th>PRI. INS</th>
				<th>PROVIDER</th>
				<th>DIAGNOSES</th>				
			    </tr>
			</thead>
			<tbody>';

				$i=0;						
				while (isset($reporte[$i])){ 
					$divTeraphist .= '<tr>';				
					$divTeraphist .= '<td>'.$reporte[$i]['id_treatments'].'</td>';
					$divTeraphist .= '<td>'.$reporte[$i]['campo_4'].'</td>';
				        $divTeraphist .= '<td>'.$reporte[$i]['campo_2'].'</td>';
				        $divTeraphist .= '<td>'.$reporte[$i]['campo_1'].'</td>';
					$divTeraphist .= '<td>'.$reporte[$i]['campo_3'].'</td>';
					$divTeraphist .= '<td>'.$reporte[$i]['campo_6'].'</td>';
					$divTeraphist .= '<td>'.$reporte[$i]['campo_7'].'</td>';
					$divTeraphist .= '<td>'.$reporte[$i]['campo_9'].'</td>';
					$divTeraphist .= '<td>'.$reporte[$i]['campo_13'].'</td>';					
					$divTeraphist .= '</tr>';
						
					$i++;		
				}			
				
	echo $divTeraphist .= '</tbody>
		   </table>	
	</div>';        

        $html = '        
<body>

    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
                <div class="col-lg-12">      	
                    <img class="img-responsive portfolio-item" src="images/LOGO_1.png" alt="">		
                </div>                    
<!--		    <table class="table table-striped">
		    	<tr><td><button type="button" class="btn btn-primary btn-lg" onclick="imprimir(\'terapias\',\'factura_print\');">PRINT THERAPIES</button><td></tr>
		    </table>-->
		    <div id="payStub">
                    <table border="1" width="100%" bordercolor="#A4A4A4">                      
                      <tr>
                          <th width="80%" class="text-center">COMPANY</th>                        
                        <th rowspan="2">EARNING STATEMENT</th>
                      </tr>
                      <tr>
                        <th class="text-center">KIDWORKS THERAPY</th>                        
                      </tr>
                    </table>
                    <table border="1" width="100%" bordercolor="#A4A4A4">                      
                      <tr>
                        <th >NAME</th>                        
                        <th >SOCIAL SECURITY ID</th>                        
                        <th >EMP ID</th>                        
                        <th >PAY DATE:</th>                                                                                                               
			<th >CHECK NUMBER:</th>
                      </tr>
                      <tr>
                        <td>'.$reporte_employee[0]['pay_to'].'</td>
                        <td>'.$reporte_employee[0]['socialsecurity'].'</td>
                        <td>'.$reporte_employee[0][''].'</td>
                        <td>'.sanitizeString($conexion, $_REQUEST['input_date_start']).' - '.sanitizeString($conexion, $_REQUEST['input_date_end']).'</td>
			<td>'.$id_check.'</td>
                      </tr> 
                    </table>
                    <table border="1" width="100%" bordercolor="#A4A4A4">                      
                      <tr>
                        <th >INCOME</th>                        
                        <th >RATE HOURS</th>                        
                        <th >CURRENT TOTAL</th>                                                                                                               
                        <th >DEDUCTIONS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>                                                                                                               
                        <th >TOTAL</th>                                                                                                               
                        <th >YEAR TO</th>                                                                                                               
                      </tr>                      
                    </table>  
                    <table border="1" width="100%" bordercolor="#A4A4A4">                      
                      <tr>
                        <th width="50%" height="100px" valign="top">
				<table width="100%" border="0" >
					<tr>
			                        <td>Gross</td>
						<td>Wages</td>
						<td>$'.number_format($A1, 2, ".", ",").'</td>
					</tr>';
        				if($_GET['cantHours'] > 0){
                            $html .= '  <tr>
			                        <td>Total of hours</td>
						<td>'.sanitizeString($conexion, $_GET['cantHours']).'</td>
						<td>&nbsp;</td>
					</tr>';
                            
                            		 }
					if($count_tx_in > 0){
                            $html .= '  <tr>
			                        <td>Total de TX IN</td>
						<td>'.$count_tx_in.'</td>
						<td>&nbsp;</td>
					</tr>';
                            
                            		 } if($count_tx_out>0){
                            $html .= '  <tr>
			                        <td>Total de TX OUT</td>						
						<td>'.$count_tx_out.'</td>
						<td>&nbsp;</td>						
					</tr>';
					}
			    		if($count_eval_in > 0){
                            $html .= '  <tr>
			                        <td>Total de Eval IN</td>
						<td>'.$count_eval_in.'</td>
						<td>&nbsp;</td>
					</tr>';
                            
                            		 } if($count_eval_out>0){
                            $html .= '  <tr>
			                        <td>Total de Eval OUT</td>						
						<td>'.$count_eval_out.'</td>
						<td>&nbsp;</td>						
					</tr>';
					}
			    		if($count_transportation > 0){
                            $html .= '  <tr>
			                        <td>Total de Transportation</td>
						<td>'.$count_transportation.'</td>
						<td>&nbsp;</td>
					</tr>';
                            
                            		 } 
			
                            $html .= '	</table>
			</th>                                                                                                                                                                      
			<th width="50%" height="100px" valign="top">
				<table width="100%" border="0" >
					<tr>
			                        <td width="60%">Social Security</td>
						<td width="20%">$'.number_format($c1, 2, ".", ",").'</td>
						<td>$'.number_format($C1, 2, ".", ",").'</td>
					</tr>
					<tr>
			                        <td>Medicate</td>
						<td>$'.number_format($c2, 2, ".", ",").'</td>
						<td>$'.number_format($C2, 2, ".", ",").'</td>
					</tr>
					<tr>
			                        <td>Federal Withholding</td>
						<td>$'.number_format($c3, 2, ".", ",").'</td>
						<td>$'.number_format($C3, 2, ".", ",").'</td>
					</tr>
				</table>			
			</th> 
                      </tr>                      
                    </table>  
                    <table border="1" width="100%" bordercolor="#A4A4A4">                      
                      <tr>
                        <th >YTD GROSS</th>                        
                        <th >YTD DEDUCTIONS</th>                        
                        <th >YTD NET PAY</th>                        
                        <th >CURRENT TOTAL</th>                                                                                                               
                        <th >CURRENT DEDUCTIONS</th>                                                                                                               
                        <th >NET PAY</th>                                                                                                                                                                                                                                                   
                      </tr>  
		      <tr>
                        <td >$'.number_format($A2, 2, ".", ",").'</td>
                        <td >$'.number_format(($C1+$C2+$C3), 2, ".", ",").'</td>
                        <td >$'.number_format(($A2-($C1+$C2+$C3)), 2, ".", ",").'</td>
                        <td >$'.number_format($A1, 2, ".", ",").'</td>
                        <td >$'.number_format(($c1+$c2+$c3), 2, ".", ",").'</td>
                        <td >$'.number_format(($A1-($c1+$c2+$c3)), 2, ".", ",").'</td>                                                                                                                                                                                                                                                   
                      </tr>                    
                    </table>                                     

		    </div>
        </div> 
	
<br>
    </div> <!-- /.container -->
    
        <div class="container">
            <div class="row text-center">
            <label><h4><font color="#A4A4A4">Commercial Invoice</font></h3></label>
                <table border="1" width="100%" height="200px" bordercolor="#A4A4A4"> 
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <br>
	    <div class="row text-center">
		<div class="form-group">												
			<div class="col-sm-2">
				<button type="button" class="btn btn-primary btn-lg" onclick="imprimir(\'payStub\',\'factura_print\');">PRINT</button>
			</div>';
		if($identificadores!=''){
			$html .= '<div class="col-sm-2">
				<button type="button" class="btn btn-primary btn-lg" onclick="imprimir(\'teraphist\',\'terapist_print\');">PRINT THERAPIST</button>
			</div>';
		}
			
		$html.='</div>
            </div>
	    <div class="row text-center">
                
            </div>
        </div>
<!-- /.container -->

</body>';

        echo $html;

        $html_pdf = str_replace('<button type="button" class="btn btn-primary btn-lg" onclick="imprimir(\'payStub\',\'factura_print\');">PRINT</button>','',$html);
                            
                            
       if (!file_exists('../../../employee')) {        
           mkdir('../../../employee', 0777, false);  
       }                            
       
       if (!file_exists('../../../employee/'.$name_employee)) {        
           mkdir('../../../employee/'.$name_employee, 0777, false);  
       }  
       
       if (!file_exists('../../../employee/'.$name_employee.'/factura')) {        
           mkdir('../../../employee/'.$name_employee.'/factura', 0777, false);  
       }          
                                                                                    
        $date_ini = str_replace('-','_',sanitizeString($conexion, $_GET['input_date_start']));
        $date_fin = str_replace('-','_',sanitizeString($conexion, $_GET['input_date_end']));                            
        
       $fecha_actual = date('d_m_Y_h_i_s');             
                            
        require_once("../../../dompdf/dompdf_config.inc.php");

        $dompdf = new DOMPDF();                
        $dompdf->load_html($html_pdf);
        $dompdf->render();
        $pdf = $dompdf->output(); 
        
        $ruta_insercion_pdf = '../../../employee/'.$name_employee.'/factura/'.$id_tbl_check.'_'.$date_ini.'_'.$date_fin.'__'.$fecha_actual.'.pdf';
                
        file_put_contents($ruta_insercion_pdf, $pdf);
        chmod ($ruta_insercion_pdf,0777);                    

	//$insert_pdf = "INSERT INTO tbl_employee_documents (`id_employee`,`route_document`) VALUES ('".$reporte_employee[0]['id_e']."','".$ruta_insercion_pdf."');";
	$update_pdf = "UPDATE tbl_check SET route_check = '".$ruta_insercion_pdf."' WHERE id = ".$id_tbl_check;
	ejecutar($update_pdf,$conexion);  

	//echo '<script>window.opener.location.reload();</script>';
?>

</html>
