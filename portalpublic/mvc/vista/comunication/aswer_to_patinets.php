<?php
	session_start();
	require_once '../../../conex.php';
	if(!isset($_SESSION['user_id'])){
		echo '<script>alert(\'MUST LOG IN\')</script>';
		echo '<script>window.location="../../../index.php";</script>';
	} else {
		if($_SESSION['user_type'] == 2) {
			echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
			echo '<script>window.location="../../home/home.php";</script>';
		}
	}
	
	$conexion = conectar();
	$email = GetEmployeeEmail($conexion);
	echo "<input id='employee_email' type='hidden' name='email' value='$email'>";
	
	function GetEmployeeEmail($cnx) {
		$user_id = sanitizeString($cnx, $_SESSION['user_id']);
		$query = "SELECT email FROM employee WHERE id = '" . $user_id . "' ;";
		$result = ejecutar($query, $cnx);
		return mysqli_result($result, 0, 0);	
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

		<title>.: KIDWORKS THERAPY :.</title>
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
			function format ( d ) {  
				var form = "<form id='" + d.idr + "'><b>From: </b>" + d.send_to + "<br><b>To: </b>" + d.from + " [" + d.First_name + " " + d.Last_name + "] <br/><textarea style='width:100%' id='t_" + d.id + "'></textarea><br/><input type='hidden' id='h_" + d.id + "' value='" + d.id + "'><input type='submit' value='Send'></form>";	   
				return form;				
			}
						
			$(document).ready(function() {					
				var dt = $('#questions_table').DataTable( {
					"lengthMenu": [[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]],
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "../../controlador/comunications/server_processing.php",
						"data": function ( d ) {
							d.columns[5]['search']['value'] = $('#employee_email').val();
						}
					},	
					"columns": [
						{
							"class":          "details-control",
							"orderable":      false,
							"data":           null,
							"defaultContent": ""
						},
						{ "data": "First_name" },
						{ "data": "Last_name" },
						{ "data": "content" },
						{ "data": "created_at" },
						{ "data": "send_to" },
						{ "data": "from" },
						{ "data": "id" },
						{ "data": "answer" },
					],
					"columnDefs": [
					    {
							"targets": [1], "render": function ( data, type, row ) { return data + ' ' + row['Last_name']; }
						},	
						{
							"targets": [2, 5, 6, 7, 8], "visible": false,
						},
						{
							"targets": [0, 6, 7], "searchable": false,
						},	
						{
							"targets": [0], "render": function ( data, type, row ) { 
								return "<img src='../../../images/" + ((row['answer'] == 1) ? ('chat1.png') : ('chat2.png'))  + "'>";
							}
						},							
					],
					"order": [[ 4, "desc" ]]
				});
				
				var detailRows = [];
 
				$('#questions_table tbody').on( 'click', 'tr td.details-control', function () {
					var tr = $(this).closest('tr');
					var row = dt.row( tr );
					var idx = $.inArray( tr.attr('id'), detailRows );
			 
					if ( row.child.isShown() ) {
						tr.removeClass( 'details' );
						row.child.hide();
						detailRows.splice( idx, 1 );
					}
					else {
						tr.addClass( 'details' );
						if ( idx === -1 ) {
							detailRows.push( tr.attr('id') );
							row.child( format(row.data()) ).show();
							$('#' + row.data().idr).on('submit', function (e) {
								e.preventDefault(e);
								var idform = e.target.id + '';
								var n = idform.substr('row_'.length, idform.length);
								var content = $('#t_' + n).val();
								var id = $('#h_' + n).val();
								if (content != '') {
									$.ajax({
										url: "../../controlador/comunications/server_processing.php",
										method: "POST",
										data: {id: id, content: content},
										success: function (data) { 
											tr.removeClass( 'details' );
											row.child.hide();
											detailRows.splice( idx, 1 );
											dt.draw();
										}
									});
								}
								else 
									swal({title: '<h4><b>The content is empty</b></h4 > ', type: "info"});
								
							}); 	
						}
						else
						  row.child().show();
					}
				} );
				
				dt.on( 'draw', function () {
					$.each( detailRows, function ( i, id ) {
						$('#'+id+' td.details-control').trigger( 'click' );
					} );
				} );

			}); 
		</script>

		<style>		  
			.card {position: relative; width: 120px; height: 120px; margin: 5px; background-color: #F1F1F1; border-radius: 1em;}
			.card-imagen {position: absolute; top: 14px; left: 28px;}
			.card-texto  {position: absolute; width: 100%; bottom: 8px; text-align: center; color: #1F497D;}
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
			<br/>					
			<div class="mysection">
				<h5>MY PATIENTS COMMUNICATIONS</h5>
			</div>		
			<div class="communications">
				<table id="questions_table" class="display" style="width:100%">
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Last Name</th>
							<th>Contents</th>
							<th>Created</th>
							<th>send_to</th>
							<th>from</th>
							<th>id</th>
							<th>answer</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Last Name</th>
							<th>Contents</th>
							<th>Created</th>
							<th>send_to</th>
							<th>from</th>
							<th>id</th>
							<th>answer</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<br/>
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