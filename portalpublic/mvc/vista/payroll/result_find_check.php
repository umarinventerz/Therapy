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
$date_start = sanitizeString($conexion, $_REQUEST['input_date_start']);
$date_end = sanitizeString($conexion, $_REQUEST['input_date_end']);
//$name_terapist = $_REQUEST['name_terapist'];

list($name_terapist,$time_pay) = explode('-', sanitizeString($conexion, $_REQUEST['name_terapist']));
//$cantHours = $_REQUEST['cantHours'];

//$sql  = "SELECT * FROM tbl_treatments WHERE (str_to_date(campo_1,'%m/%d/%Y')  BETWEEN str_to_date('".$date_start."','%m/%d/%Y') AND str_to_date('".$date_end."','%m/%d/%Y')) and campo_9 = '".$name_terapist."' AND (pay = false or pay = null);";
$sql  = "SELECT *,count(ct.id) as terapias FROM tbl_check c JOIN employee e ON e.id = c.id_employee LEFT JOIN tbl_check_treatments ct ON ct.id_check = c.id WHERE licence_number = '".$name_terapist."' 
 OR concat(e.last_name,', ',e.first_name) LIKE '%".$name_terapist."%'

 GROUP BY c.id;";
$resultado = ejecutar($sql,$conexion);

        $reporte = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i] = $datos;
            $i++;
        }


//
//echo "<pre>";
//print_r($reporte);die;
?>
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

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
<div class="col-lg-12 text-center" >
<div class="form-group has-feedback centered">
		<div class="col-sm-3"></div>		
		<div class="col-sm-3" align="left">
                        <button class="btn btn-primary btn-label-left" onclick=" return mostrar_resultados();">
			    <span><i class="fa fa-clock-o"></i></span>
				    BACK TO THERAPIES
			    </button>
		</div>	
		<div class="col-sm-2" align="left">
                        <button class="btn btn-primary btn-label-left" onclick=" return ver_factura('<?php echo $name_terapist;?>');">
			    <span><i class="fa fa-clock-o"></i></span>
				    VIEW PREVIOUS CHECKS
			    </button>
		</div>																
	</div> 	
   
<?php if($reporte != null){?>
    <div class="col-lg-12">&nbsp;</div>	
            <div class="col-lg-12">
		
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			    <tr>
			
					<th>EMPLOYEE</th>
					<th>NUMBER CHECK</th>
					<th>DATE START</th>
					<th>DATE END</th>
					<th>ROUTE CHECK</th>					
					<th>THERAPIES</th>
			    </tr>
			</thead>
					
					
			<tbody>
			<?php		
			$i=0;						
			while (isset($reporte[$i])){ 
				
				//list($route_file,$name_file) = explode('factura/',$reporte[$i]['route_document']);
				//$array_name_file = explode('_',$name_file);
				//$sql_check = "SELECT * FROM tbl_check WHERE id = ".$array_name_file[0];
				//$resultado_check = ejecutar($sql_check,$conexion);
				//$reporte = array();        
				//$i = 0;      
				//while($datos = mysqli_fetch_assoc($resultado_check)) {            
				//    $reporte[$i] = $datos;
				//    $i++;
				//}
				echo '<tr>';
				
				echo '<td>'.$reporte[$i]['first_name'].' '.$reporte[$i]['last_name'].'</td>';
				echo '<td>'.$reporte[$i]['number_check'].'</td>';
				echo '<td>'.$reporte[$i]['date_start'].'</td>';
				echo '<td>'.$reporte[$i]['date_end'].'</td>';
                                $reporte[$i]['route_check'] = str_replace('../../../', '', $reporte[$i]['route_check']);
				echo '<td><a href="../../../'.$reporte[$i]['route_check'].'" target="_blank">'.$reporte[$i]['route_check'].'</a></td>';				
				if($reporte[$i]['terapias'] != 0)
					echo '<td><a onclick="abrirTerapias('.$reporte[$i]['id_check'].')" target="_blank">'.$reporte[$i]['terapias'].'</a></td>';
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
	<label>Este terapista no tiene facturas para mostrar</label>    
</div>
<?php
} 
?>
</div>


