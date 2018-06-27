<?php
session_start();
require_once("../../../conex.php");
$conexion = conectar();
$type_paystub = sanitizeString($conexion, $_REQUEST['type_paystub']);
?>
   <!DOCTYPE html>
<html lang="en">

<head>
    
    <link href="../../../css/bootstrap.min.css" rel="stylesheet"> 
<!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/datatables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/funciones.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/datatables.responsive.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">	
    <!-- End Style-->
    <script>
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
                            var total_pay = 0;
			    var total_deduc = 0;
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            // Total over this page                                
                            total_pay = api
                                .column( 6, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                
                            total_deduc = api
                                .column( 5, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                            // Update footer
                           $( api.column( 6 ).footer() ).html(
                                '$'+number_format(total_pay, 2, '.','')
                            );
				$( api.column( 5 ).footer() ).html(
                                '$'+number_format(total_deduc, 2, '.','')
                            );                               
                        }
		} );                
	} );
    </script>
    </head>

        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>

                        <th>EMPLOYEE</th>					
                        <th>DATE START</th>
                        <th>DATE END</th>
			<th>DATE PAY</th>
                        <th>TYPE OF EMPLOYEE</th>                        
			<th>DEDUCTIONS</th>
                        <th>TOTAL</th>				
                        <th>ROUTE CHECK</th>                        	                        
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total:</th>
                <th style="text-align:center"></th> 		
		<th style="text-align:center"></th>
                <th style="text-align:center"></th>                 
            </tr>
        </tfoot>
        <tbody>
<?php
        $identificadores_pre_check = substr (sanitizeString($conexion, $_REQUEST['identificadores_pre_check']), 0, strlen($_REQUEST['identificadores_pre_check']) - 1);
        $array_ids_pre_check = explode(',',$identificadores_pre_check);
        
        $date_start = date('Y-m-d',strtotime(sanitizeString($conexion, $_REQUEST['input_date_start'])));
        $date_end = date('Y-m-d',strtotime(sanitizeString($conexion, $_REQUEST['input_date_end'])));
       
        $i = 0;    
        $data_table = '';
        while(isset($array_ids_pre_check[$i])) {  
            
            //if($array_ids_pre_check[$i] == 385){
            $sql  = "SELECT *,pc.id as id_pre_check,pc.social_security as social_security,e.social_security as e_social_security,AES_DECRYPT(e.social_security,'kidworks_therapy') as ss FROM tbl_pre_check pc"
                    . " LEFT JOIN employee e ON e.id = pc.id_employee"
                    . " WHERE pc.id = ".$array_ids_pre_check[$i].";";
            $data_pre_check = ejecutar($sql,$conexion);
            
            $j = 0;                        
            $reporte_pre_check = array();
            while($datos = mysqli_fetch_assoc($data_pre_check)) {            
                $reporte_pre_check[$j] = $datos;
                $j++;
            }
                          
            $date_pay = $reporte_pre_check[0]['date_pay'];

            //$date_pay = '2016-11-11';
            $verificarCheck = "SELECT * FROM tbl_check WHERE `id_employee` = ".$reporte_pre_check[0]['id_employee']." AND `pay_date`='".$date_pay."'";
            $data_check = ejecutar($verificarCheck,$conexion);

            $reporte_check = array();

            $p = 0;      
            while($datos = mysqli_fetch_assoc($data_check)) {            
                $reporte_check[$p] = $datos;
                $p++;
            }
            //$check_generado = 'no';
            $C1 = 0;
            $C2 = 0;
            $C3 = 0;
            $A2 = 0;
            if($reporte_check[0]!= null){
                    //$check_generado = 'si';
                    $route = $reporte_check[0]['route_check'];
                    $total_pay = $reporte_check[0]['total_pay'];
            }else{
                    $consulta_year_to_date = "SELECT * FROM tbl_employee_year_to_date WHERE id_Employee =".$reporte_pre_check[0]['id_employee'];
                    $data_year_to_date = ejecutar($consulta_year_to_date,$conexion);
                    $reporte_year_to_Date = array();        
                    $l = 0;      
                    while($datos = mysqli_fetch_assoc($data_year_to_date)) {            
                        $reporte_year_to_Date[$l] = $datos;
                        $l++;
                    }

                    $C1 = $reporte_pre_check[0]['social_security'] + $reporte_year_to_Date[0]['social_security'];
                    $C2 = $reporte_pre_check[0]['medicate'] + $reporte_year_to_Date[0]['medicate'];
                    $C3 = $reporte_pre_check[0]['federal_withholding'] + $reporte_year_to_Date[0]['federal_withholdings'];

                    $max_id = "SELECT MAX(number_check) AS number_check FROM tbl_check;";
                    $result01 = mysqli_query($conexion, $max_id);					
                    $id_check = 0;
                    while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
                            $id_check = $row['number_check'];
                    }
                    $id_check = ($id_check+1);

                    $A2 = $reporte_pre_check[0]['gross'] + $reporte_year_to_Date[0]['gross'];
                    
                    $total_pay = $reporte_pre_check[0]['gross'] - ($reporte_pre_check[0]['social_security'] + $reporte_pre_check[0]['medicate'] + $reporte_pre_check[0]['federal_withholding']);
                                       
                    $insert_check = "INSERT INTO tbl_check (`id_employee`,`gross`,`social_security`,`medicate`,`federal_withholdings`,`date_start`,`date_end`,`number_check`,`gross_t`,`social_security_t`,`medicate_t`,`federal_withholdings_t`,`route_check`,`total_pay`,`pay_date`) VALUES 
                    (".$reporte_pre_check[0]['id_employee'].",".number_format($reporte_pre_check[0]['gross'], 2, ".", "").",".number_format($reporte_pre_check[0]['social_security'], 2, ".", "").",".number_format($reporte_pre_check[0]['medicate'], 2, ".", "").""
                            . ",".number_format($reporte_pre_check[0]['federal_withholding'], 2, ".", "").",'".$reporte_pre_check[0]['date_start']."','".$reporte_pre_check[0]['date_end']."',".$id_check.""
                            . ",".number_format($A2, 2, ".", "").",".number_format($C1, 2, ".", "").",".number_format($C2, 2, ".", "").",".number_format($C3, 2, ".", "").",'',".$reporte_pre_check[0]['total_pay'].",'".$date_pay."')";
                    ejecutar($insert_check,$conexion);
                    
                    $max_id_check = "SELECT MAX(id) AS id_check FROM tbl_check";
                    $result_check = mysqli_query($conexion, $max_id_check);
                    $id_tbl_check = 0;
                    while($row = mysqli_fetch_array($result_check,MYSQLI_ASSOC)){
                            $id_tbl_check = $row['id_check'];
                    }
                    
                    
                    $sql_select = "SELECT id_treatments
                    FROM tbl_pre_check_treatments WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].";";
                    $result_select = mysqli_query($conexion, $sql_select);                    
                    while($row = mysqli_fetch_array($result_select,MYSQLI_ASSOC)){
                            $sql_insert = "INSERT INTO tbl_check_treatments (`id_check`,`id_treatment`) VALUES (".$id_tbl_check.",".$row['id_treatments'].")";                            
                            ejecutar($sql_insert,$conexion);
                    }
                    
                    
                    //##################################################################################
                    //##################CAMBIAR ESTATUS DE TREATMENTS Y EXTRA PAY#######################
                    //##################################################################################
                    
                    $sql_select_extra_pay = "SELECT id_extra_pay
                    FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].";";
                    $result_select_extra_pay = mysqli_query($conexion, $sql_select_extra_pay);                    
                    while($row = mysqli_fetch_array($result_select_extra_pay,MYSQLI_ASSOC)){
                            $sql_insert_extra_pay = "INSERT INTO tbl_check_extra_pay (`id_check`,`id_extra_pay`) VALUES (".$id_tbl_check.",".$row['id_extra_pay'].")";                            
                            ejecutar($sql_insert_extra_pay,$conexion);
                    }
                    
                    $sql_insert_select_extra_pay = "INSERT INTO tbl_check_extra_pay (`id_check`,`id_extra_pay`)
                    SELECT ".$id_tbl_check.",id_extra_pay
                    FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].";";
                    ejecutar($sql_insert_select_extra_pay,$conexion);
                    
                    $update_treatments = "UPDATE tbl_treatments SET pay = 1 WHERE id_treatments IN (SELECT id_treatments
                    FROM tbl_pre_check_treatments WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].")";
                    ejecutar($update_treatments,$conexion);
                    
                    $update_extra_pay_pre_check = "UPDATE tbl_extra_pay SET status_extra_pay = 1 WHERE id_extra_pay IN "
                            . "(SELECT id_extra_pay FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].")";
                    ejecutar($update_extra_pay_pre_check,$conexion);
                    
                    //##################################################################################
                    ////################ FIN CAMBIAR ESTATUS DE TREATMENTS Y EXTRA PAY##################
                    //##################################################################################                    
                    
                    $update_year_to_date = " UPDATE tbl_employee_year_to_date SET gross =".number_format($A2, 2, ".", "").",social_security=".number_format($C1, 2, ".", "").",medicate = ".number_format($C2, 2, ".", "").",federal_withholdings=".number_format($C3, 2, ".", "")." WHERE id_employee=".$reporte_pre_check[0]['id_employee'];
                    ejecutar($update_year_to_date,$conexion);
                    
                    $sql_type_treatments  = "SELECT count(*) as amount ,IF(campo_2 = 11,concat(type,'_IN'),concat(type,'_OUT')) as type_treatments FROM tbl_pre_check pc 
                    LEFT JOIN tbl_pre_check_treatments pct ON pct.id_pre_check = pc.id 
                    LEFT JOIN tbl_treatments t ON t.id_treatments = pct.id_treatments 
                    LEFT JOIN cpt c ON c.cpt = t.campo_11 AND c.discipline = t.campo_10 
                    LEFT JOIN employee e ON e.id = pc.id_employee 
                    LEFT JOIN employee_treatment et ON et.id_employee = e.id 
                    WHERE pc.id = ".$array_ids_pre_check[$i]." group by type_treatments";
                    
                    $result_type_treatments = mysqli_query($conexion, $sql_type_treatments);
                    $count_tx_in = 0;
                    $count_tx_out = 0;
                    $count_eval_in = 0;
                    $count_eval_out = 0;
                    $count_transportation = 0;
                    while($row = mysqli_fetch_array($result_type_treatments,MYSQLI_ASSOC)){
                            if($row['type_treatments'] == 'TX_IN' || $row['type_treatments'] == 'SUPERVISION_IN'){
                                $count_tx_in = $row['amount'];
                            }if($row['type_treatments'] == 'TX_OUT' || $row['type_treatments'] == 'SUPERVISION_OUT'){
                                $count_tx_out = $row['amount'];
                            }if($row['type_treatments'] == 'EVAL_IN'){
                                $count_eval_in = $row['amount'];
                            }if($row['type_treatments'] == 'EVAL_OUT'){
                                $count_eval_out = $row['amount'];
                            }if($row['type_treatments'] == 'TRANSPORTATION_IN' || $row['type_treatments'] == 'TRANSPORTATION_OUT'){
                                $count_transportation = $row['amount'];
                            }
                    }
                    $name_employee = ($reporte_pre_check[0]['first_name'].'_'.$reporte_pre_check[0]['last_name']);                    
                    $route = generateCheckPDF($reporte_pre_check,$id_check,$reporte_pre_check[0]['date_start'],$reporte_pre_check[0]['date_end'],$reporte_pre_check[0]['amount_hours'],$reporte_pre_check[0]['gross'],$reporte_pre_check[0]['social_security'],$C1,$reporte_pre_check[0]['medicate'],$C2,$reporte_pre_check[0]['federal_withholding'],$C3,$A2,$name_employee,$count_tx_in,$count_tx_out,$count_eval_in,$count_eval_out,$count_transportation,$date_pay,$conexion);
                    
            }
                
                       
            
		//if($check_generado != 'si'){
			$data_table .= '<tr>';				
			$data_table .= '<td>'.$reporte_pre_check[0]['first_name'].' '.$reporte_pre_check[0]['last_name'].'</td>';				
			$data_table .= '<td>'.$reporte_pre_check[0]['date_start'].'</td>';
			$data_table .= '<td>'.$reporte_pre_check[0]['date_end'].'</td>';
			$data_table .= '<td>'.$date_pay.'</td>';
			$data_table .= '<td>'.$reporte_pre_check[0]['kind_employee'].'</td>';			
			$data_table .= '<td> $'.number_format(($reporte_pre_check[0]['social_security'] + $reporte_pre_check[0]['medicate'] + $reporte_pre_check[0]['federal_withholding']),2,'.','').'</td>';
			$data_table .= '<td> $'.number_format($reporte_pre_check[0]['total_pay'],2,'.','').'</td>';				            				            			
                        $data_table .= '<td><a href="'.$route.'" target="_blank">'.$route.'</a></td>';
			
			$data_table .= '</tr>';
		//}else{
		//	$data_table .= '<tr>';	
		//	$data_table .= '<td colspan="5"> La factura se encuentra Generada </td>';
		//	$data_table .= '</tr>';			
		//}
            
            //}
            
            $i++;
        }  
        echo $data_table;
        
        
        
        
        function generateCheckPDF($reporte_pre_check,$id_check,$date_start,$date_end,$amountHours,$gross,$social_security,$C1,$medicate,$C2,$federal_withholding,$C3,$A2,$name_employee,$count_tx_in,$count_tx_out,$count_eval_in,$count_eval_out,$count_transportation,$date_pay,$conexion){               

        $html = '        
<body>

    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
                <div class="col-lg-12">      	
                    <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">		
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
                        <td>'.$reporte_pre_check[0]['pay_to'].'</td>
                        <td>'.$reporte_pre_check[0]['ss'].'</td>
                        <td>'.$reporte_pre_check[0]['id_employee'].'</td>
                        <td>'.$date_pay.'</td>
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
						<td>$'.number_format($gross, 2, ".", ",").'</td>
					</tr>';
					if($amountHours > 0){
                            $html .= '  <tr>
			                        <td>Total of hours</td>
						<td>'.$amountHours.'</td>
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
						<td width="20%">$'.number_format($social_security, 2, ".", ",").'</td>
						<td>$'.number_format($C1, 2, ".", ",").'</td>
					</tr>
					<tr>
			                        <td>Medicate</td>
						<td>$'.number_format($medicate, 2, ".", ",").'</td>
						<td>$'.number_format($C2, 2, ".", ",").'</td>
					</tr>
					<tr>
			                        <td>Federal Withholding</td>
						<td>$'.number_format($federal_withholding, 2, ".", ",").'</td>
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
                        <td >$'.number_format($gross, 2, ".", ",").'</td>
                        <td >$'.number_format(($social_security+$medicate+$federal_withholding), 2, ".", ",").'</td>
                        <td >$'.number_format(($gross-($social_security+$medicate+$federal_withholding)), 2, ".", ",").'</td>                                                                                                                                                                                                                                                   
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
        </div>
<!-- /.container -->

</body>';

        //echo $html;

        //$html_pdf = str_replace('<button type="button" class="btn btn-primary btn-lg" onclick="imprimir(\'payStub\',\'factura_print\');">PRINT</button>','',$html);
                            
                            
        if (!file_exists('../../../employee')) {        
            mkdir('../../../employee', 0777, false);  
        }                            

        if (!file_exists('../../../employee/'.$name_employee)) {        
            mkdir('../../../employee/'.$name_employee, 0777, false);  
        }  

        if (!file_exists('../../../employee/'.$name_employee.'/factura')) {        
            @mkdir('../../../employee/'.$name_employee.'/factura', 0777, false);  
        }          
                                                                                    
        $date_ini = $date_start;
        $date_fin = $date_end;                            
        
        $fecha_actual = date('d_m_Y_h_i_s');             
                            
        require_once("../../../dompdf/dompdf_config.inc.php");

        $dompdf = new DOMPDF();                
        $dompdf->load_html($html);
        $dompdf->render();
        $pdf = $dompdf->output(); 
        
	$ruta_insercion_pdf = '../../../employee/'.$name_employee.'/factura/'.$id_check.'_'.$date_ini.'_'.$date_fin.'__'.$fecha_actual.'.pdf';
        //$ruta_insercion_pdf = 'employee/'.$name_employee.'/factura/'.$id_check.'_'.$date_ini.'_'.$date_fin.'__'.$fecha_actual.'.pdf';
                
        @file_put_contents($ruta_insercion_pdf, $pdf);
        @chmod ($ruta_insercion_pdf,0777);                    

	//$insert_pdf = "INSERT INTO tbl_employee_documents (`id_employee`,`route_document`) VALUES ('".$reporte[0]['id_e']."','".$ruta_insercion_pdf."');";
	$update_pdf = "UPDATE tbl_check SET route_check = '".$ruta_insercion_pdf."' WHERE number_check = ".$id_check;
	ejecutar($update_pdf,$conexion);
        
        return $ruta_insercion_pdf;
        }
?>

        <tbody/>
        </table>
