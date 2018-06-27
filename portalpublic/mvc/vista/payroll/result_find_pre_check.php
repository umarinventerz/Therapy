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
//[type_terapist] => Therapist
//[status_terapist] => 1
$conexion = conectar();
$date_start = sanitizeString($conexion, $_REQUEST['input_date_start']);
$date_end = sanitizeString($conexion, $_REQUEST['input_date_end']);
$type_terapist = sanitizeString($conexion, $_REQUEST['type_terapist']);
list($name_terapist,$time_pay) = explode('-', sanitizeString($conexion, $_REQUEST['name_terapist']));



$sql_event = "SELECT date_start FROM tbl_event WHERE descripcion LIKE '%".$type_terapist."%' AND date_start >= date(now()) Limit 1";                                        
                    
$result_event = mysqli_query($conexion, $sql_event);
while($row = mysqli_fetch_array($result_event,MYSQLI_ASSOC)){
        $date_pay = $row['date_start'];
}

if($_SESSION['user_type'] >= 8){
    if($name_terapist == 'all' || $name_terapist == 'all_therapist'){
        $where_name_terapist = '';
    }else{
        $where_name_terapist = " AND (licence_number LIKE '%".$name_terapist."%' OR concat(e.last_name,', ',e.first_name) LIKE '%".$name_terapist."%')";
    }
   $sql="SELECT *,e.id as id_employee,e.pay_to as Address_To,pc.id as id_pre_check,pc.kind_employee as type_employee,pc.social_security as amount_social_security,count(pct.id) as terapias FROM tbl_pre_check pc 
    JOIN employee e ON e.id = pc.id_employee 
    LEFT JOIN tbl_pre_check_treatments pct ON pct.id_pre_check = pc.id 
    WHERE (date_pay BETWEEN str_to_date('".$date_start."','%m/%d/%Y') AND str_to_date('".$date_end."','%m/%d/%Y') ".$where_name_terapist.")
    group by pc.id ";
}else{
   $sql="SELECT *,e.id as id_employee,e.pay_to as Address_To,pc.id as id_pre_check,pc.kind_employee as type_employee,pc.social_security as amount_social_security,count(pct.id) as terapias FROM tbl_pre_check pc 
    JOIN employee e ON e.id = pc.id_employee 
    LEFT JOIN tbl_pre_check_treatments pct ON pct.id_pre_check = pc.id 
    WHERE (date_pay BETWEEN str_to_date('".$date_start."','%m/%d/%Y') AND str_to_date('".$date_end."','%m/%d/%Y')) AND pc.kind_employee LIKE '%".sanitizeString($conexion, $_REQUEST['type_terapist'])."%' AND pc.id_employee = ".$_SESSION['user_id']." group by pc.id ";        
}

$resultado = ejecutar($sql,$conexion);

        $reporte = array();
        $typeEmployee = '';
        $pay_dates = '';
        $payDateDiferent = '';
        $typeEmployeeDiferent = '';
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i] = $datos;
            //echo $datos['type_employee'].'<br>';
            if($i == 0){
                $typeEmployee = $datos['type_employee'];
                $pay_dates = $datos['pay_date'];
            }else{
                if($typeEmployee != $datos['type_employee']){
                    $typeEmployeeDiferent = 'si';
                }
                if($pay_dates != $datos['pay_date']){
                    $payDateDiferent = 'si';
                }
            }
            
            
            $i++;
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
                                //i = i.replace('$','');                                
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            // Total over this page
                            var pageTotal = api
                                .column( 4, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                
                            var payTotal = api
                                .column( 5, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                                         
                            // Update footer
                            $( api.column( 4 ).footer() ).html(
                                '$'+number_format(pageTotal, 2, '.', '')
                            );   
                            $( api.column( 5 ).footer() ).html(
                                '$'+number_format(payTotal, 2, '.', '')
                            ); 
                        }
		} );
	} );    
        
        function abrirTerapiasPreCheck(id_pre_check){
                    window.open('ver_terapias_pre_check.php?id_pre_check='+id_pre_check,'','width=2500px,height=700px,noresize');
            }
            
        function generate_pre_check(type_paystub){
                var total_pre_check_hours = '';
                var employee = '';
                var id_employee = '';
                //var type_employee_list = '';
                 $('input:hidden').map(function() {                
                    
                    if(this.name.substr(0,12) == 'total_hidden'){                        
                        id_employee = this.name.replace("total_hidden_", "");
                        if($("#cantHours_"+id_employee).length > 0){ 
                            if($("#cantHours_"+id_employee).val() != ''){                                
                                total_pre_check_hours = total_pre_check_hours + $("#cantHours_"+id_employee).val()+',';                                
                                employee = employee + id_employee + ',';                                
                            }
                        }else{
                            if($("#"+this.id).val()!= ''){
                                total_pre_check_hours = total_pre_check_hours +'X'+',';
                                employee = employee + id_employee + ',';                                
                            }                            
                        }                        
                    }                 
                });      
                if(employee == ''){
			alert('Debe generar al menos un calculo para emitir el '+type_paystub);
		}else{
			$("#resultado").load("../../controlador/payroll/generate_pre_check.php?type_paystub="+type_paystub+"&employee="+employee+"&total_pre_check_hours="+total_pre_check_hours+"&date_start="+$("#date_start").val()+"&date_end="+$("#date_end").val()+"&name_employee="+$("#name_employee").val()+"&type_employee="+$("#type_employee").val());
		}                
        }
        
        function generar_factura(input_date_start,input_date_end){
            var valores_pre_check = '';

            $('input:hidden').map(function() {
                    if(this.name.substr(0,13) == 'id_pre_check_'){
                        valores_pre_check += this.value+',';
                    }                    
            });
            //valores_pre_check = '110,';
            alert(valores_pre_check);
            var name_terapist = $('#name_terapist').val();	
            
            var r = confirm("Are you sure that you want to generate the check?");
            if (r == true) {
                $("#resultado").load('../../controlador/payroll/generate_check.php?&identificadores_pre_check='+valores_pre_check+'&name_terapist='+name_terapist+'&input_date_start='+input_date_start+'&input_date_end='+input_date_end);
                //window.open('factura.php?&identificadores_pre_check='+valores_pre_check+'&name_terapist='+name_terapist+'&input_date_start='+input_date_start+'&input_date_end='+input_date_end,'factura','width=900, height=800, scrollbars=1, location=no,left=300,top=50');                 
            }	            
        }
    </script>
</head>
<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    <!-- Page Content -->
    <div class="container" style="width:100%">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">                
				 			  
				</ul>
		      </li>		
		
		<h3>PRE CHECKS</h3>
            </div>
        </div>
<br/>
<div class="row">
<div class="col-lg-12 text-center" > 	
   
<?php if($reporte != null){?>
    <div class="row">
        <div class="col-lg-5"></div>	
        <div class="col-lg-2 text-center">
            <?php
                if($_SESSION['user_type'] >= 8){
            if($typeEmployeeDiferent == 'si'){
                echo '<b>Existen tipos de empleados distintos en el reporte</b>';
            }else{    
                if($payDateDiferent == 'si'){
                    echo '<b>Existen pre checks de distintas fechas de pago en el reporte</b>';
                }else{

                    if($reporte[0]['type_employee'] == 'Therapist'){?>
                    <button class="btn btn-primary btn-label-left" onclick=" return generar_factura('<?php echo $date_start?>','<?php echo $date_end?>');">
                            GENERATE PAYSTUB
                    </button>
                    <?php }else{?>
                    <button class="btn btn-primary btn-label-left" onclick=" return generate_pre_check('check');">			    
                            GENERATE PAYSTUB
                    </button>   
                    <?php }?>
                    <input type="hidden" id="name_employee" name="name_employee" value="<?php echo str_replace(' ','|',sanitizeString($conexion, $_REQUEST['name_terapist']));?>" >
                    <input type="hidden" id="type_employee" name="type_employee" value="<?php echo $reporte[0]['type_employee'];?>" >
                    <input type="hidden" id="date_start" name="date_start" value="<?php echo $date_start;?>" >
                    <input type="hidden" id="date_end" name="date_end" value="<?php echo $date_end;?>" > 
        <?php }
          }                
            }    
            ?>
        </div>        
    </div>
    <div class="col-lg-12">&nbsp;</div>	
            <div class="col-lg-12">
		
                              <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			    <tr>
			
					<th>EMPLOYEE</th>	
                    
                    <?php                    
                     if($_SESSION['user_type'] == 1){
                    echo '<th>THERAPIES</th>';				
					}    
            ?>
                                        <th>DATE START</th>
					<th>DATE END</th>
                                        <th>PAY DATE</th>
					<th>TOTAL DEDUCTIONS</th>					
                                        <th>TOTAL PAY</th>
                                        <th>TYPE OF EMPLOYEE</th>
                                        <th>ADDRESS TO</th>
					<th>THERAPIES</th>                                        
			    </tr>
			</thead>
			<tfoot>
                            <tr>
                                <th colspan="4" style="text-align:right">Total:</th>                                
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>                                                                
                                <th style="text-align:center"></th>
                            </tr>
                        </tfoot>		
					
			<tbody>
			<?php		
			$i=0;						
			while (isset($reporte[$i])){ 				
				echo '<tr>';				
				echo '<td>'.$reporte[$i]['first_name'].' '.$reporte[$i]['last_name'].'</td>';	
                

                if($_SESSION['user_type'] == 1){
                if($reporte[$i]['terapias'] != 0)
                    echo '<td><a onclick="abrirTerapiasPreCheck('.$reporte[$i]['id_pre_check'].')" target="_blank">'.$reporte[$i]['terapias'].'</a></td>';
                else
                    echo '<td>0</td>';
                }

				echo '<td>'.$reporte[$i]['date_start'].'</td>';
				echo '<td>'.$reporte[$i]['date_end'].'</td>';
                                echo '<td>'.$reporte[$i]['date_pay'].'</td>';
                                echo '<td>'.($reporte[$i]['amount_social_security']+$reporte[$i]['medicate']+$reporte[$i]['federal_withholding']).'</td>';
                                $inputCantHours = '';
                                if($reporte[$i]['time_pay'] != 'Bikeweekly' &&  $reporte[$i]['time_pay'] != null)
                                    $inputCantHours = '<input type="hidden" name="cantHours_'.$reporte[$i]['id_employee'].'" id="cantHours_'.$reporte[$i]['id_employee'].'" value="'.($reporte[$i]['gross']/$reporte[$i]['amount_salary']).'">';                                
				echo '<td> '.$reporte[$i]['total_pay'].'</td>';
                                echo '<td>'.$reporte[$i]['type_employee'].$inputCantHours.'<input type="hidden" name="id_pre_check_'.$i.'" name="id_pre_check_'.$reporte[$i]['id_employee'].'" value="'.$reporte[$i]['id_pre_check'].'"><input type="hidden" name="total_hidden_'.$reporte[$i]['id_employee'].'" id="total_hidden_'.$reporte[$i]['id_employee'].'" value="'.$reporte[$i]['total_pay'].'"></td>';
                echo '<td>'.$reporte[$i]['Address_To'].'</td>';   				
				if($reporte[$i]['terapias'] != 0)
					echo '<td><a onclick="abrirTerapiasPreCheck('.$reporte[$i]['id_pre_check'].')" target="_blank">'.$reporte[$i]['terapias'].'</a></td>';
				else
					echo '<td>0</td>';                                
				echo '</tr>';						
				$i++;		
			}			
			 ?>				
			<tbody/>
			</table> 
		 
            </div>	    
        </div>

<?php }else{
?>
<br/>
<br/>
<br/>
<br/>

<div class="col-lg-12 text-center" >
	<label>Este terapista no tiene pre checks para mostrar</label>    
</div>
<?php
} 
?>
</div>
    </div>
    <!-- /.container -->
</body>


