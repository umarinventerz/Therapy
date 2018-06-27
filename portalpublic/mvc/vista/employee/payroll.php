<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}

$conexion = conectar();
define("REC_COUNT", "24");
$id_employee = sanitizeString($conexion, $_SESSION['user_id']);
$query = "select total_pay, gross, social_security, federal_withholding, date_pay from tbl_pre_check where id_employee = '$id_employee' order by date_pay desc";
$result = ejecutar($query, $conexion);
$i = 0;
$data = array();
while ($row = mysqli_fetch_array($result)) {
	$data[$i] = $row;
	$i++;
}
if (count($data) > REC_COUNT) $data = array_slice($data, 0, REC_COUNT - 1);
$data = array_reverse($data);
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
	
	<script src="../../../js/Chart.min.js"></script>
	<script src="../../../js/jquery.min.js" type="text/javascript"></script>
	<script src="../../../js/jquery.dataTables.min.js" type="text/javascript"></script>
	<link href="../../../css/jquery.dataTables.min.css" rel="stylesheet">
	<script src="../../../js/bootstrap.min.js" type="text/javascript"></script>		
	<link href="../../../css/bootstrap.min.css" rel="stylesheet">
	<script src="../../../js/funciones.js" type="text/javascript"></script>
	<script src="../../../js/listas.js" type="text/javascript" ></script>
	<link href="../../../plugins/select2/select2.css" rel="stylesheet">
			
	<link href="../../../css/style_v1.css" rel="stylesheet">		
	<link href="../../../css/portfolio-item.css" rel="stylesheet">			
	<script src="../../../js/devoops_ext.js" type="text/javascript"></script>						
	<script src="../../../js/promise.min.js" type="text/javascript"></script>		
	<link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
	<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
	<link href="../../../css/sweetalert2.min.css" rel="stylesheet">
	<script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
	<link href="../../../css/css/simple-sidebar.css" rel="stylesheet">
	
	<script>
		$(document).ready(function() {
			var dates = [<?php foreach ($data as $value) echo "'" . $value['date_pay'] . "', "?>];
			//date_format($date,"Y/m/d H:i:s");
			var totalpay = [<?php foreach ($data as $value) echo $value['total_pay'] . ", "?>];
			var gross = [<?php foreach ($data as $value) echo $value['gross'] . ", "?>];
			var social_security = [<?php foreach ($data as $value) echo $value['social_security'] . ", "?>];
			var federal_withholding = [<?php foreach ($data as $value) echo $value['federal_withholding'] . ", "?>];
			var ctx = document.getElementById("payroll");
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: dates,
					datasets: [
						{
							data: totalpay,
							label: "Total pay",
							borderColor: "#3e95cd",
							fill: false
						},
						{
							data: gross,
							label: "Gross",
							borderColor: "#953ecd",
							fill: false
						},
						{
							data:  social_security,
							label: "Social security",
							borderColor: "#95cd3e",
							fill: false
						},
						{
							data:  federal_withholding,
							label: "Federal withholding",
							borderColor: "#eece3e",
							fill: false
						},
					]
				},
				options: {
					legend: { display: true },
					title: {
						display: true,
						text: 'Payroll Graph'
					}
				}
			});
			var dt = $('#payroll_table').DataTable( {
				"lengthMenu": [[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]],
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": '../../controlador/employee/server_processing.php',
					"data": function ( d ) {
						d.columns[5]['search']['value'] = <?php echo $id_employee;?>
					}
				},	
				"columns": [
					{ "data": "date_pay" },
					{ "data": "total_pay" },
					{ "data": "gross" },
					{ "data": "social_security" },
					{ "data": "federal_withholding" },
					{ "data": "id_employee" },
				],
				"columnDefs": [	
					{
						"targets": [5], "visible": false,
					},							
				],
				"order": [[ 0, "desc" ]]
			});
		});
	</script>
	<style>		  
		.mysection h5 {border-bottom-style: solid; border-bottom-width: 2px; color: #1F497D;}
	</style>
</head>
<body>
    <?php include "../nav_bar/nav_bar.php"; ?>
	<div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
		
		<div class="row">
			<canvas id="payroll"></canvas>
		</div>
		<br/>
		<div class="mysection">
			<h5>PAYROLL</h5>
		</div>	
		<br/>				
		<table id="payroll_table" class="table display" style="width:100%">
			<thead>
				<tr>
					<th>Date</th>
					<th>Total pay</th>
					<th>Gross</th>
					<th>Social security</th>
					<th>Federal withholding</th>
					<th>id_employee</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Date</th>
					<th>Total pay</th>
					<th>Gross</th>
					<th>Social security</th>
					<th>Federal withholding</th>
					<th>id_employee</th>
				</tr>
			</tfoot>	
		</table>				
		<br/><br/>
		<footer>
			<div class="row">
				<div class="col-lg-12">
					<p>Copyright &copy; THERAPY  AID 2018</p>
				</div>
			</div>
		</footer>
	</div>	
</body>
</html>