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
			<?php if($type_paystub == 'check'){?>
                            <th>ROUTE CHECK</th>
                        <?php }?>			                        
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total:</th>
                <th style="text-align:center"></th>
		<th style="text-align:center"></th>  
		<?php if($type_paystub == 'check'){?>
                    <th style="text-align:center"></th> 
                <?php }?>               
            </tr>
        </tfoot>
        <tbody>
<?php
        
        $total_extra_pay = substr (sanitizeString($conexion, $_REQUEST['total_extra_pay']), 0, strlen($_REQUEST['total_extra_pay']) - 1);        
        $array_total_extra_pay = explode(',',$total_extra_pay);

        $ss = substr (sanitizeString($conexion, $_REQUEST['ss']), 0, strlen($_REQUEST['ss']) - 1); 
        $array_ss = explode(',',$ss);
        $mc = substr (sanitizeString($conexion, $_REQUEST['mc']), 0, strlen($_REQUEST['mc']) - 1); 
        $array_mc = explode(',',$mc);
        $fw = substr (sanitizeString($conexion, $_REQUEST['fw']), 0, strlen($_REQUEST['fw']) - 1); 
        $array_fw = explode(',',$fw);
        
        $selected_extra_pay = substr (sanitizeString($conexion, $_REQUEST['selected_extra_pay']), 0, strlen($_REQUEST['selected_extra_pay']) - 1);
        $selected_extra_pay = str_replace('|','',$selected_extra_pay);
        $array_selected_extra_pay = explode(',',$selected_extra_pay);
        
        $hours = substr (sanitizeString($conexion, $_REQUEST['total_pre_check_hours']), 0, strlen($_REQUEST['total_pre_check_hours']) - 1);
        $employee = substr (sanitizeString($conexion, $_REQUEST['employee']), 0, strlen($_REQUEST['employee']) - 1);
        $array_employee = explode(',',$employee);
        $array_hours = explode(',',$hours);
        
        $date_start = date('Y-m-d', strtotime(sanitizeString($conexion, $_REQUEST['date_start'])));
        $date_end = date('Y-m-d', strtotime(sanitizeString($conexion, $_REQUEST['date_end'])));
        
        $name_employee = str_replace('|',' ',sanitizeString($conexion, $_REQUEST['name_employee']));
        $type_employee = sanitizeString($conexion, $_GET['type_employee']);
        
        //si es un check lo que estoy generando voy a tomar la fecha de pago incluyendo el dia de hoy por si a caso se genera el pago un viernes (incluyo el viernes)
        //si es un pre_check se toma la siguiente independientemente sea viernes

        $sql_event = "SELECT date_start FROM tbl_event WHERE descripcion LIKE '%".$type_employee."%' AND date_start >= date(now()) Limit 1";        
        $result_event = mysqli_query($conexion, $sql_event);
        while($row = mysqli_fetch_array($result_event,MYSQLI_ASSOC)){
                $date_pay = $row['date_start'];
        }
       
        $i = 0;    
        $data_table = '';
        while(isset($array_employee[$i])) {            
            $sql  = "SELECT *,AES_DECRYPT(social_security,'kidworks_therapy') as ss FROM employee emp WHERE id = ".$array_employee[$i].";";
            $data_employee = ejecutar($sql,$conexion); 
            $j = 0;      
            while($datos = mysqli_fetch_assoc($data_employee)) {            
                $reporte_employee[$j] = $datos;
                $j++;
            }
            $name_employee = $reporte_employee[0]['last_name'].', '.$reporte_employee[0]['first_name'];
            
            if($type_paystub == 'pre_check'){                
                $gross = 0;                                
                if($array_hours[$i] != 'X'){
                    if($array_hours[$i] >80){
                            $dif_hour = $array_hours[$i] - 80;
                            $ext_hour = $dif_hour*(1.5*$reporte_employee[0]['amount_salary']);
                            $gross = $reporte_employee[0]['amount_salary']*80;
                            $gross = $gross + $ext_hour;
                    }else{
                            $gross = $reporte_employee[0]['amount_salary']*$array_hours[$i];
                    }                
                    $amountHours = $array_hours[$i];
                }else{
                    $amountHours = 0;
                    $gross = $reporte_employee[0]['amount_salary'];
                }

                $gross = $gross + $array_total_extra_pay[$i];
                
                if ((isset($_REQUEST['ss'])) && ($_REQUEST['ss'] != '')) {
                    $social_security = $array_ss[$i];
                    $medicate = $array_mc[$i];
                    $federal_withholding = $array_fw[$i];;
                } else if($reporte_employee[0]['type_contract'] == '1099'){
                    $social_security = 0;
                    $medicate = 0;
                    $federal_withholding = 0;
                }else{
                    $social_security = number_format(($gross * 6.2/100),2,'.','');
                    $medicate = number_format(($gross * 1.45/100),2,'.','');
                    $sql_federal  = "SELECT dependence_".$reporte_employee[0]['dependencies']." as dependence FROM federal_withholdings fw WHERE '".$gross."'>=at_least and '".$gross."'<but_less_then AND civil_status = '".$reporte_employee[0]['civil_status']."';";
                    $data_federal_withholdings = ejecutar($sql_federal,$conexion);                

                    $reporte_federal_withholdings = array();

                    $k = 0;      
                    while($datos = mysqli_fetch_assoc($data_federal_withholdings)) {            
                        $reporte_federal_withholdings[$k] = $datos;
                        $k++;
                    }
                    $federal_withholding = $reporte_federal_withholdings[0]['dependence'];
                }
                
                
                $sql_delete_pre_check = "DELETE FROM tbl_pre_check WHERE id_employee = ".$array_employee[$i]." AND date_pay='".$date_pay."' AND kind_employee ='".$type_employee."'";
                ejecutar($sql_delete_pre_check,$conexion);
                                
                //echo $total_pay = "$gross - ($social_security + $medicate + $federal_withholding)";
                $total_pay = $gross - ($social_security + $medicate + $federal_withholding);
                
                $sql_pre_check = "INSERT INTO tbl_pre_check (`id_employee`, `total_pay`, `date_start`, `date_end`, `kind_employee`, `date_pay`, `gross`, `social_security`, `medicate`, `federal_withholding`, `amount_hour`) "
                . "VALUES (".$array_employee[$i].",".number_format($total_pay,2,'.','').",'".$date_start."','".$date_end."','".$type_employee."','".$date_pay."',".number_format($gross,2,'.','').",".number_format($social_security,2,'.','').",".number_format($medicate,2,'.','').",".number_format($federal_withholding,2,'.','').",".$amountHours.");";
                ejecutar($sql_pre_check,$conexion);
                
                                
                $max_id_pre_check = "SELECT MAX(id) AS id_pre_check FROM tbl_pre_check";
                $result_pre_check = mysqli_query($conexion, $max_id_pre_check);
                $id_tbl_pre_check = 0;
                while($row = mysqli_fetch_array($result_pre_check,MYSQLI_ASSOC)){
                        $id_tbl_pre_check = $row['id_pre_check'];
                }
                
                $sql_delete_pre_check = "DELETE FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$id_tbl_pre_check;
                ejecutar($sql_delete_pre_check,$conexion);                
                
                if($array_selected_extra_pay[$i] != ''){
                    $array_id_extra_pay = null;
                    $array_id_extra_pay = explode('-',$array_selected_extra_pay[$i]);
                    $e= 0;
                    while($array_id_extra_pay[$e]){
                        $sql_id_extra_pay= "INSERT INTO tbl_pre_check_extra_pay (`id_pre_check`, `id_extra_pay`) "
                        . "VALUES (".$id_tbl_pre_check.",".$array_id_extra_pay[$e].");";
                        ejecutar($sql_id_extra_pay,$conexion);
                        $e++;
                    }
                }

                $date_start_final = $date_start;
                $date_end_final = $date_end;
                $date_pay_final = $date_pay;

            }else{                                  
                
		$verificarCheck = "SELECT * FROM tbl_check WHERE `id_employee` = ".$array_employee[$i]." AND `pay_date` BETWEEN '".$date_start."' AND '".$date_end."'";
                
		$data_check = ejecutar($verificarCheck,$conexion);

		$reporte_check = array();

		$p = 0;      
		while($datos = mysqli_fetch_assoc($data_check)) {            
		    $reporte_check[$p] = $datos;
		    $p++;
		}
		$check_generado = 'no';
		if($reporte_check[0]!= null){
			$check_generado = 'si';
			$route = $reporte_check[0]['route_check'];
			$total_pay = $reporte_check[0]['total_pay'];
            $date_start_final = $reporte_check[0]['date_start'];
            $date_end_final = $reporte_check[0]['date_end'];
            $date_pay_final = $reporte_check[0]['pay_date'];
            $social_security = $reporte_check[0]['social_security'];
            $medicate = $reporte_check[0]['medicate'];
            $federal_withholding = $reporte_check[0]['federal_withholding'];

		}else{
                    
                        $verificarPreCheck = "SELECT *,pc.id as id_pre_check,pc.social_security as social_security FROM tbl_pre_check pc WHERE `id_employee` = ".$array_employee[$i]." AND `date_pay` BETWEEN '".$date_start."' AND '".$date_end."'";
                        $result02 = ejecutar($verificarPreCheck,$conexion);
                        $l = 0;      
                        $reporte_pre_check = array();
		        while($datos2 = mysqli_fetch_assoc($result02)) {            
		            $reporte_pre_check[$l] = $datos2;
		            $l++;
		        }
                    
			$consulta_year_to_date = "SELECT * FROM tbl_employee_year_to_date WHERE id_Employee =".$array_employee[$i];
		        $data_year_to_date = ejecutar($consulta_year_to_date,$conexion);
		        $reporte_employee_year_to_Date = array();        
		        $l = 0;      
		        while($datos = mysqli_fetch_assoc($data_year_to_date)) {            
		            $reporte_employee_year_to_Date[$l] = $datos;
		            $l++;
		        }
		                        
		        $C1 = $reporte_pre_check[0]['social_security'] + $reporte_employee_year_to_Date[0]['social_security'];
		        $C2 = $reporte_pre_check[0]['medicate'] + $reporte_employee_year_to_Date[0]['medicate'];
		        $C3 = $reporte_pre_check[0]['federal_withholding'] + $reporte_employee_year_to_Date[0]['federal_withholdings'];

		        $max_id = "SELECT MAX(number_check) AS number_check FROM tbl_check;";
		        $result01 = mysqli_query($conexion, $max_id);					
		        $id_check = 0;
		        while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
		                $id_check = $row['number_check'];
		        }
		        $id_check = ($id_check+1);
		        
		        $A2 = $reporte_pre_check[0]['gross'] + $reporte_employee_year_to_Date[0]['gross'];
		        
		        $total_pay = $reporte_pre_check[0]['gross'] - ($reporte_pre_check[0]['social_security'] + $reporte_pre_check[0]['medicate'] + $reporte_pre_check[0]['federal_withholding']);
		                                
		        $insert_check = "INSERT INTO tbl_check (`id_employee`,`gross`,`social_security`,`medicate`,`federal_withholdings`,`date_start`,`date_end`,`number_check`,`gross_t`,`social_security_t`,`medicate_t`,`federal_withholdings_t`,`route_check`,`total_pay`,`pay_date`) VALUES 
		        (".$array_employee[$i].",".number_format($reporte_pre_check[0]['gross'], 2, ".", "").",".number_format($reporte_pre_check[0]['social_security'], 2, ".", "").",".number_format($reporte_pre_check[0]['medicate'], 2, ".", "").",".number_format($reporte_pre_check[0]['federal_withholding'], 2, ".", "").",'".$reporte_pre_check[0]['date_start']."','".$reporte_pre_check[0]['date_end']."',".$id_check.",".number_format($A2, 2, ".", "").",".number_format($C1, 2, ".", "").",".number_format($C2, 2, ".", "").",".number_format($C3, 2, ".", "").",'',".$reporte_pre_check[0]['total_pay'].",'".$date_pay."')";
		        ejecutar($insert_check,$conexion);

		        $update_year_to_date = " UPDATE tbl_employee_year_to_date SET gross =".number_format($A2, 2, ".", "").",social_security=".number_format($C1, 2, ".", "").",medicate = ".number_format($C2, 2, ".", "").",federal_withholdings=".number_format($C3, 2, ".", "")." WHERE id_employee=".$array_employee[$i];
		        ejecutar($update_year_to_date,$conexion);
		        
                        $max_id_check = "SELECT MAX(id) AS id_check FROM tbl_check";
                        $result_check = mysqli_query($conexion, $max_id_check);
                        $id_tbl_check = 0;
                        while($row = mysqli_fetch_array($result_check,MYSQLI_ASSOC)){
                                $id_tbl_check = $row['id_check'];
                        }
                        
                        $sql_insert_select_extra_pay = "INSERT INTO tbl_check_extra_pay (`id_check`,`id_extra_pay`) "
                                . "SELECT ".$id_tbl_check.",id_extra_pay "
                                . "FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].";";
                        ejecutar($sql_insert_select_extra_pay,$conexion);
                        
                        $update_extra_pay_pre_check = "UPDATE tbl_extra_pay SET status_extra_pay = 1 WHERE id_extra_pay IN "
                                . "(SELECT id_extra_pay FROM tbl_pre_check_extra_pay WHERE id_pre_check = ".$reporte_pre_check[0]['id_pre_check'].")";
                        ejecutar($update_extra_pay_pre_check,$conexion);
                        
		        $route = generateCheckPDF($reporte_employee,$id_check,$reporte_pre_check[0]['date_start'],$reporte_pre_check[0]['date_end'],number_format($reporte_pre_check[0]['gross'], 2, ".", ""),$reporte_pre_check[0]['amount_hour'],number_format($reporte_pre_check[0]['social_security'], 2, ".", ""),$C1,number_format($reporte_pre_check[0]['medicate'], 2, ".", ""),$C2,number_format($reporte_pre_check[0]['federal_withholding'], 2, ".", ""),$C3,$A2,$name_employee,$date_pay,$conexion);

            $date_start_final = $reporte_pre_check[0]['date_start'];
            $date_end_final = $reporte_pre_check[0]['date_end'];
            $date_pay_final = $reporte_pre_check[0]['date_pay'];
		    $social_security = $reporte_pre_check[0]['social_security'];
            $medicate = $reporte_pre_check[0]['medicate'];
            $federal_withholding = $reporte_pre_check[0]['federal_withholding'];
		}

            }            
			$data_table .= '<tr>';				
			$data_table .= '<td>'.$reporte_employee[0]['first_name'].'</td>';				//.' '.$reporte_employee[0]['last_name']
			$data_table .= '<td>'.$date_start_final.'</td>';
            $data_table .= '<td>'.$date_end_final.'</td>';
            $data_table .= '<td>'.$date_pay_final.'</td>';
			$data_table .= '<td>'.$type_employee.'</td>';	
			$data_table .= '<td> $'.number_format(($social_security + $medicate + $federal_withholding),2,'.','').'</td>';			
			$data_table .= '<td> $'.number_format($total_pay,2,'.','').'</td>';				            				            
			if($type_paystub == 'check'){
				$data_table .= '<td><a href="'.$route.'" target="_blank">'.$route.'</a></td>';
			}
			$data_table .= '</tr>';
		
            $i++;
        }  
        echo $data_table;
        
        
        function generateCheckPDF($reporte_employee,$id_check,$date_start,$date_end,$gross,$cantHours,$social_security,$C1,$medicate,$C2,$federal_withholding,$C3,$A2,$name_employee,$date_pay,$conexion){               

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
                        <td>'.$reporte_employee[0]['pay_to'].'</td>
                        <td>'.$reporte_employee[0]['ss'].'</td>
                        <td>'.$reporte_employee[0][''].'</td>
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
        				if($cantHours > 0){
                            $html .= '  <tr>
			                        <td>Total of hours</td>
						<td>'.$cantHours.'</td>
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
			                        <td>Medicaid</td>
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
            mkdir('../../../employee/'.$name_employee.'/factura', 0777, false);  
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
                
        file_put_contents($ruta_insercion_pdf, $pdf);
        chmod ($ruta_insercion_pdf,0777);                    

	//$insert_pdf = "INSERT INTO tbl_employee_documents (`id_employee`,`route_document`) VALUES ('".$reporte_employee[0]['id_e']."','".$ruta_insercion_pdf."');";
	$update_pdf = "UPDATE tbl_check SET route_check = '".$ruta_insercion_pdf."' WHERE number_check = ".$id_check;
	ejecutar($update_pdf,$conexion);
        
        return $ruta_insercion_pdf;
        }
?>

        <tbody/>
        </table>
