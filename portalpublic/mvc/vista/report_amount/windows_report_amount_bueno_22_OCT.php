<?php
session_start();
require_once("../../../conex.php");
require_once("../../../queries.php");
$conexion = conectar();
$typeAge = $_REQUEST['typeAge'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
<title>Report</title>

<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>

<!--<link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->
<script src="../../../js/jquery.min.js"></script>
<script src="../../../js/bootstrap.min.js"></script>
<!--<script type="text/javascript" src="../../../js/bootstrap-filestyle.js"></script>-->

<!--<script src="js/fileinput.js" type="text/javascript"></script>-->
    <script>	
	function updatePrescriptions(pat_id,discipline,company,patient_name,cpt,type){	
		var eval_done;

                if($("input[name=eval_done_"+pat_id+"]:checked").length == 1){
                    eval_done = 1;			    
                }else{
                    eval_done = 0;
                }            
		//alert($("#eval_schedule_"+Pat_id).val());
		$("#result_u"+pat_id).load("../../controlador/prescriptions/update_prescriptions.php","&Patient_id="+pat_id+"&Discipline="+discipline+"&Company="+company+"&evalschedule="+$("#eval_schedule_"+pat_id).val()+"&evalDone="+eval_done+"&patient_name="+patient_name);
		
		alert("Update Succefull");

		window.location="windows_report_amount.php?discipline="+discipline+"&cpt="+cpt+"&type="+type;
	}
	
	function updateAllReadyTreatments(pat_id,discipline,company,patient_name,cpt,type){	
		var all_ready_treatments;

                if($("input[name=all_ready_treatments_"+pat_id+"]:checked").length == 1){
                    all_ready_treatments = 1;			    
                }else{
                    all_ready_treatments = 0;
                }            
		//alert($("#eval_schedule_"+pat_id).val());
		$("#result_all"+pat_id).load("../../controlador/prescriptions/update_prescriptions.php","&Patient_id="+pat_id+"&Discipline="+discipline+"&Company="+company+"&allReadyTreatments="+all_ready_treatments+"&patient_name="+patient_name+"&type="+type);
		alert("Update Succefull");
		//window.location="windows_report_amount.php?discipline="+discipline+"&cpt="+cpt+"&type="+type;
	}
	function composeMail(pat_id,discipline,company,patient_name,cpt,type){
		window.open('../report_mail/compose_mail.php?Patient_id='+pat_id+'&Discipline='+discipline+'&Company='+company+'&patient_name='+patient_name+'&type='+type,'','width=700px,height=900px,noresize');		
		//$("#result_m"+pat_id).load("composeMail.php","&Patient_id="+pat_id+"&Discipline="+discipline+"&Company="+company+"&patient_name="+patient_name);
		//alert("Mail Sent");
		

	}
	function showPatient(name_patient){
		window.open('../patients/search.php?name='+name_patient,'w','width=1300px,height=1000px,noresize');		
	}
    </script>
<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
</style>


</head>
<body>
<div class="bs-example">


            <div>
               <?php echo '<h1 align="center" class="page-header"> '.$_GET['type'].'</h1>'?>
               <?php echo '<h1 align="center">Discipline: <small>'.$_GET['discipline'].'</small></h1>'?>
               <?php echo '<h1 align="center"><small>'.$_GET['typeAge'].'</small></h1>'?>

            </div>
	<?php 	
		$headColumn = '';
		if($_GET['type'] == 'NEED EVALUTATION'){
			//$headColumn = '<th>Eval schedule</th>    <th>Eval Done</th>        <th>&nbsp;</th>';
			$headColumn = '<th>Eval schedule</th>        					  <th>&nbsp;</th>';
		}
		if($_GET['type'] == 'READY FOR TREATMENT'){
			$perfil = $_SESSION['user_type'];
						if($perfil >='5'){

			$headColumn = '<th>Under Treatment</th><th>&nbsp;</th>';
											}

		}

$dayswaiting = '';
		if($_GET['type'] == 'WAITING FOR PRESCRIPTION' || $_GET['type'] == 'WAITING FOR DOCTOR SIGNATURE' || $_GET['type'] == 'WAITING FOR AUTH EVAL' || $_GET['type'] == 'WAITING FOR AUTHORIZATION TX'){
			$dayswaiting .= '<th>DAYS WAITING</th><th>&nbsp;</th>';
		}




		$mail = '';
		if($_GET['type'] == 'POC EXPIRED NO PRESCRIPTION' || $_GET['type'] == 'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL' || $_GET['type'] == 'NOT SIGNED BY DOCTOR YET' || $_GET['type'] == 'ASK FOR AUTHORIZATION FOR TX'){
			$mail .= '<th>Send mail</th>';
		}

		if($_GET['type'] == 'PATIENTS ON HOLD'){
			$STATUS = '<th>STATUS</th><th>&nbsp;</th>';}


		$number_treatments = '';
		if($_GET['type'] == 'PROGRESS NOTES ADULTS' ){
			$number_treatments .= '<th>Number of Treatments</th>';
		}


	?>
    <table class="table table-striped">
        <thead>
            <tr>
				<th>Patient Name</th>
				<th>AGE</th>
				<?php echo $number_treatments;?>
                <th>Company</th>
                <th>Autho_Number</th>
				<th>Auth _ Thru</th>
                <th>Visit_Remain</th>
                <th>POC _ Due</th>
				<th>Insurance</th>
       			 <?php echo $STATUS;?>
                <th>Physician</th>
                <th>Office_phone</th>
                <?php echo $dayswaiting;?>
					<?php echo $headColumn;?>
						<?php echo $mail;?>

          
            </tr>
        </thead>
        <tbody>
            <?php
            	$conexion = conectar();





		if($_GET['type'] == 'auth_expired')
			$query = queryAuthExpired($_GET['cpt'],$_GET['discipline']);
		
		if($_GET['type'] == 'POC EXPIRED NO PRESCRIPTION')
			$query = queryPOCExpired($_GET['cpt'],$_GET['discipline']);
		if($_GET['type'] == 'WAITING FOR PRESCRIPTION')
			$query = queryWAITINGPRESCRIPTION($_GET['cpt'],$_GET['discipline']);

		if($_GET['type'] == 'NOT SIGNED BY DOCTOR YET')
			$query = querynotsigned($_GET['cpt'],$_GET['discipline']);
		if($_GET['type'] == 'WAITING FOR DOCTOR SIGNATURE')
			$query = queryWAITINGDOCTROSIGNED($_GET['cpt'],$_GET['discipline']);


		if($_GET['type'] == 'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL')
			$query = queryAskForAuthEval($_GET['cpt'],$_GET['discipline']);
		if($_GET['type'] == 'WAITING FOR AUTH EVAL')
			$query = queryWAITINGEVAL($_GET['cpt'],$_GET['discipline']);


		if($_GET['type'] == 'NEED EVALUTATION')
			 $query = queryEvaluation($_GET['cpt'],$_GET['discipline']);
//		if($_GET['type'] == 'EVAL SIGNED BY DOCTOR / ASK FOR AUTHO FOR TX')
//			$query = querysignedaskauthotx($_GET['cpt'],$_GET['discipline']);
		


		if($_GET['type'] == 'ASK FOR AUTHORIZATION FOR TX')
			$query = queryAskforAuthTX ($_GET['cpt'],$_GET['discipline']);
		if($_GET['type'] == 'WAITING FOR AUTHORIZATION TX')
			$query = queryWAITINGTX ($_GET['cpt'],$_GET['discipline']);
		




		if($_GET['type'] == 'READY FOR TREATMENT')
			$query = queryreadyforthreatment ($_GET['cpt'],$_GET['discipline']);

		if($_GET['type'] == 'PATIENTS ON HOLD')
			$query = querypatientsonhold ($_GET['cpt'],$_GET['discipline']);



		if($_GET['type'] == 'PROGRESS NOTES ADULTS')
			$query = queryAdultsprogressnotes ($_GET['cpt'],$_GET['discipline']);
		



		$result2 = mysqli_query($conexion, $query);

			$i = 1;
                	while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
							$queryPatientDetail = queryPatientDetail($row['Pat_id'],$_GET['discipline']);
							$datos = "<table border='0'>";
							$resultPatientDetail = mysqli_query($conexion, $queryPatientDetail);
                					while($row1 = mysqli_fetch_array($resultPatientDetail,MYSQLI_ASSOC)){
                						echo "<tr>";
                							$datos .= "<b>CPT:</b>".$row1['CPT']."</br>";
                							$datos .= "<b>Auth Star:</b>".$row1['Auth_Star']."</br>";
                							$datos .= "<b>Auth Thru:</b>".$row1['Auth_thru']."</br>";
                							$datos .= "<b>POC_due:</b>".$row1['POC_due']."</br>";
                							$datos .= "<b>CO:</b>".$row1['CO']."</br>";
                							$datos .= "<b>Discipline:</b>".$row1['Discipline'];
                						echo "</tr>";

    					}
                					$datos .= "</table>";

	   			echo '<tr>
	   				  <td><div id="myModal_'.$row['Pat_id'].'" class="modal fade" style="display: \'none\'">
	   				  	<div class="modal-dialog">
			        			<div class="modal-content">
			        				<div class="modal-header">
								     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								     <h4 class="modal-title">Detail of Patient</h4>
							         </div>
							         <div class="modal-body">
							         <form role="form">
									<div class="form-group">
									    '.$datos.'
									</div>
							          </form>

			        				</div>
			        			</div>
		        			</div>
			        	      </div><a onclick="showPatient(\''.$row['Pat_id'].'-'.$row['Company'].'\')"><b >'.$row['Patient_name'].'</b></a></td>
			        	      <td style="color:blue;font-size: 13pt;">'.$row['AGE'].'</td>';

			       	if($_GET['type'] == 'PROGRESS NOTES ADULTS'){
					 echo  	'<td>'.$row['number_treatments'].'</td>' 	;	
					   }

		      		  echo '<td>'.$row['Company'].'</td>
					  <td>'.$row['Authorization'].'</td>
					  <td>'.$row['Auth_thru'].'</td>
					  <td>'.$row['Visit_remen'].'</td>
					  <td>'.$row['POC_due'].'</td>
					  <td>'.$row['Insurance_name'].'</td> 
					 
					  <td>'.$row['Physician'].'</td>
					  <td>'.$row['Office_phone'].'</td>';
					   if($_GET['type'] == 'PATIENTS ON HOLD'){
					 echo  	'<td>'.$row['STATUS'].'</td>' 	;	
					   }
					  if($_GET['type'] == 'NEED EVALUTATION'){
						if($row['Eval_done'] == '1'){
							$checked = 'checked';
						}else{
							$checked = '';
						}
						//echo '<td><input name="eval_schedule_'.trim($row['Pat_id']).'" id="eval_schedule_'.trim($row['Pat_id']).'" type="date" value="'.$row['Eval_schedule'].'"></td><td><input type="checkbox" name="eval_done_'.trim($row['Pat_id']).'" id="eval_done_'.trim($row['Pat_id']).'" '.$checked.'></td><td><div id="result_u'.trim($row['Pat_id']).'"><button type="button" class="btn btn-info btn-lg" onclick="updatePrescriptions(\''.trim($row['Pat_id']).'\',\''.trim($_GET['discipline']).'\',\''.$row['Company'].'\',\''.$row['Patient_name'].'\',\''.$_GET['cpt'].'\',\''.$_GET['type'].'\');">Update</button></div></td>';

						echo '<td><input name="eval_schedule_'.trim($row['Pat_id']).'" id="eval_schedule_'.trim($row['Pat_id']).'" type="date" value="'.$row['Eval_schedule'].'"></td>
					  	
						<td><div id="result_u'.trim($row['Pat_id']).'"><button type="button" class="btn btn-info btn-lg" onclick="updatePrescriptions(\''.trim($row['Pat_id']).'\',\''.trim($_GET['discipline']).'\',\''.$row['Company'].'\',\''.$row['Patient_name'].'\',\''.$_GET['cpt'].'\',\''.$_GET['type'].'\');">Update</button></div></td>';

					   }
					   
					   if($_GET['type'] == 'READY FOR TREATMENT'){
						/*if($row['Eval_done'] == '1'){
							$checked = 'checked';
						}else{
							$checked = '';
						}*/

						$perfil = $_SESSION['user_type'];
						if($perfil >= '5'){

						echo '<td><input type="checkbox" name="all_ready_treatments_'.trim($row['Patient_id']).'" id="all_ready_treatments_'.trim($row['Patient_id']).'"></td>
						<td><div id="result_all'.trim($row['Patient_id']).'"><button type="button" class="btn btn-info btn-lg" onclick="updateAllReadyTreatments(\''.trim($row['Patient_id']).'\',\''.trim($_GET['discipline']).'\',\''.$row['Company'].'\',\''.$row['Patient_name'].'\',\''.$_GET['cpt'].'\',\''.$_GET['type'].'\');">Update</button></div></td>'; }
					   }
					   
					   if($_GET['type'] == 'POC EXPIRED NO PRESCRIPTION' || $_GET['type'] == 'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL' || $_GET['type'] == 'NOT SIGNED BY DOCTOR YET' || $_GET['type'] == 'ASK FOR AUTHORIZATION FOR TX' || $_GET['type'] == 'PROGRESS NOTES ADULTS'){						
					   	echo '<td><div id="result_m'.trim($row['Pat_id']).'"><button type="button" class="btn btn-info btn-lg" onclick="composeMail(\''.trim($row['Pat_id']).'\',\''.trim($_GET['discipline']).'\',\''.$row['Company'].'\',\''.$row['Patient_name'].'\',\''.$_GET['cpt'].'\',\''.$_GET['type'].'\');">Send Fax</button></div></td>';
					   }

					    else
					   {
					   	 if($_GET['type'] == 'WAITING FOR PRESCRIPTION' || $_GET['type'] == 'WAITING FOR DOCTOR SIGNATURE' || $_GET['type'] == 'WAITING FOR AUTH EVAL' || $_GET['type'] == 'WAITING FOR AUTHORIZATION TX'){		

					   	echo '<td>'.$row['Date_sent'].'</td>';


					   	 if($row['AGE']>='21'){ 
         					if($row['Days_Waiting']>='3') 
							echo '<td style="color:red;font-size: 15pt;font-weight: 900;">'.$row['Days_Waiting'].'</td>'; 
         									  }
          		else if($row['Days_Waiting']>='15')
						echo '<td style="color:darkblue;font-size: 13pt;font-weight: 900;">'.$row['Days_Waiting'].'</td>';
						else echo '<td>'.$row['Days_Waiting'].'</td>';

					  echo ' 	<td><div id="result_m'.trim($row['Pat_id']).'"><button type="button" class="btn btn-info btn-lg" onclick="composeMail(\''.trim($row['Pat_id']).'\',\''.trim($_GET['discipline']).'\',\''.$row['Company'].'\',\''.$row['Patient_name'].'\',\''.$_GET['cpt'].'\',\''.$_GET['type'].'\');">RE_Send Fax</button></div></td>';
					   }
					}

					echo '</tr>';
					$arregloPatient[$i] = $row['Pat_id'];
					$i++;
					}
			    ?>
            												       
        </tbody>
    </table>
    	<button type="button" class="btn btn-primary btn-lg" onclick="window.print();">PRINT</button>
</div>
<script>
	$(document).ready(function(){
		<?php
			$i = 1;
			while($arregloPatient[$i]!= null){
		?>
			$("#myModal_<?php echo trim($arregloPatient[$i]);?>").on('show.bs.modal', function(event){
				var button = $(event.relatedTarget);  // Button that triggered the modal
				var titleData = button.data('title'); // Extract value from data-* attributes
				$(this).find('.modal-title').text(titleData);
		    	});		
	    	<?php
	    		$i++;
	    		}
	    	?>
    	});
	
	

</script>
<script type="text/javascript">

			

		</script>
</body>
</html>
