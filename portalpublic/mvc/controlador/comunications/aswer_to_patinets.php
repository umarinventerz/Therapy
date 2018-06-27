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
		$query = "SELECT email FROM employee WHERE id = '" . $_SESSION['user_id'] . "' ;";
		$result = ejecutar($query, $cnx);
		return mysqli_result($result, 0, 0);	
	}
?>
<html> 
   <head>
		<title>Paging</title>
		<script src="../../../js/jquery.min.js" type="text/javascript"></script>
		<script src="../../../js/jquery.dataTables.min.js" type="text/javascript"></script>
		<link href="../../../css/jquery.dataTables.min.css" rel="stylesheet">
		<script>	
		
			function format ( d ) {  
				var form = "<form id='" + d.idr + "'><b>From: </b>" + d.send_to + "<br><b>To: </b>" + d.from + " [" + d.First_name + " " + d.Last_name + "] <br/><textarea style='width:100%' id='t_" + d.id + "'></textarea><br/><input type='hidden' id='h_" + d.id + "' value='" + d.id + "'><input type='submit' value='Send'></form>";	   
				return form;				
			}
			
			
			$(document).ready(function() {					
				var dt = $('#questions_table').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "server_processing.php",
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
								return "<img src='../../../images/" + ((row['answer'] == 1) ? ('email2.png') : ('email1.png'))  + "'>";
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
										url: "server_processing.php",
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
									alert("The message is empty");
									//swal({title: '<h4><b>The content is empty</b></h4 > ', type: "info"});
								
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
   </head>
   
   <body>
		<div style="width:80%">
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
   </body>
</html>