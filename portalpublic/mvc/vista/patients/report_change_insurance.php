<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
if(isset($_SESSION['name'])){
	$_POST['name'] = trim($_SESSION['name']);
	$_POST['find'] = $_SESSION['find'];
}
$fieldsPatients = array(
'Pat_id',
'Patient_name',
'Sex',
'DOB',
'Guardian',
'Social',
'Address',
'City',
'Street',
'Zip',
'E_mail',
'Phone',
'Cell',
'Ref_Physician',
'Phy_NPI',
'Pri_Ins',
'Auth',
'Sec_INS',
'Auth_2',
'Ter_Ins',
'Auth_3',
'Mem_#',
'Grp_#',
'Intake_Agmts',
'Table_name',
'Thi_Ins');

if($_POST['find'] == " Find "){

$conexion = conectar();
list($nameC,$company) = explode('-',$_POST['name']);
list($last_name,$first_name) = explode(', ',$nameC);
$patient_id = $_POST['patient_id'];
$where = ' WHERE TRUE ';
if($patient_id != ''){
	$where .= " AND Pat_id = '".$patient_id."'"; 
}
if($first_name != ''){
	$where .= " AND first_name = '".$first_name."'"; 
}
if($last_name != ''){
	$where .= " AND last_name = '".$last_name."'"; 
}
if($company != ''){
	$where .= " AND table_name = '".$company."'";
}
if($_POST['name']==''){
	$pos = strpos($_POST['fieldsShow'], 'Patient_name');
	if ($pos != false) {
	    $fieldsShowConcat = str_replace("Patient_name", "concat(Last_name,',',First_name) as Patient_name", $_POST['fieldsShow']);
	    $groupBy = ' group by Pat_id, Table_name';
	    $orderBy = ' order by Patient_name asc';

	}else{
		$fieldsShowConcat = $_POST['fieldsShow'];
	}
	$headTable =  $_POST['fieldsShow'];
	if($headTable[0] == ',')
		$headTable = substr($headTable, 1);
	if($fieldsShowConcat[0] == ',')
		$fields = substr($fieldsShowConcat, 1);
	else
		$fields = $fieldsShowConcat;

	$fieldsArray = explode(',',$fields);
	$u = 0;
	while($fieldsArray[$u]!=null){
		if($fieldsArray[$u]== 'Mem_#')
			$fieldsArray[$u] = '`Mem_#`';
		if($fieldsArray[$u]== 'Grp_#')
			$fieldsArray[$u] = '`Grp_#`';
		$u++;
	}
	$fields = implode(',',$fieldsArray);

}else{
	$headTable =  'Pat_id,Pri_Ins,Patient_name';
	$fields = 'Pat_id,Pri_Ins,concat(Last_name,\', \',First_name) as Patient_name,Table_name as company, Thi_Ins as new_insurance';
}
if($fields == ''){
	$fields = 'Pat_id,Pri_Ins,concat(Last_name,\', \',First_name) as Patient_name,Table_name as company';
}

$sql1="Select ".$fields." from (select  *,concat(Last_name,',',First_name) as Patient_name 
from patients ".$where. "  and Thi_Ins is not null and Pri_Ins!=Thi_Ins   " .$groupBy.$orderBy." ) as r ;";


unset($_SESSION['name']);
unset($_SESSION['find']);
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
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
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
    <script>

	function selectAllFields(){                                   
                    $("input:checkbox").each(function(){
                        if($("input[name=allFields]:checked").length == 1){
                            this.checked = true;
                        }else{
                            this.checked = false;
                        }            
                    });
           }
	
	function findData(){		
		document.getElementById("myForm").submit();
	}
	function loadOrderFieldsShow(valor){
		$("input:checkbox[name="+valor+"]").each(function(){			
			if(this.checked == true){
				if($("#fieldsShow").val() == '')
					$("#fieldsShow").val(valor);					
				else
					$("#fieldsShow").val($("#fieldsShow").val()+','+valor);
			}else{
				var str = $("#fieldsShow").val();				
				var res = str.replace(valor,"");
				res = res.replace(",,", ",");
				$("#fieldsShow").val(res);
			}
        	});		
	}
        function blockCheckBox(){
	
		if($("#name").val() != ''){
			$("input:checkbox").each(function(){        						
				$(this).attr('disabled','disabled');
                	});	
		}else{
			$("input:checkbox").each(function(){        						
				this.disabled = false;
                	});
		}
	}

	function blockCheckBox1(){
		if($("#patient_id").val() != ''){
			$("input:checkbox").each(function(){        						
				$(this).attr('disabled','disabled');				
                	});	
		}else{
			$("input:checkbox").each(function(){  					      						
				this.disabled = false;
                	});
		}
	}   
	function updatePatient(pat_id,company,patient_name){		
		$("#result_u"+pat_id).load("../../controlador/update_patients.php","&Patient_id="+pat_id+"&Company="+company+"&newInsurance="+$("#newInsurance_"+pat_id).val()+"&patient_name="+patient_name);
		alert("Update Succefull");
		window.location="reporte_change_insurance.php";
	}
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>


    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
               <br>			 
			<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-12">
             <form class="form-horizontal" id="myForm" action="report_change_insurance.php" method="post">
		   
		    <div class="col-lg-12">
			<h3 class="page-header">Choose Fields for Report <input name="fieldsShow" id="fieldsShow" type="hidden"></h3>
		    </div>
		     <?php 
			$i = 0; 		
			while($fieldsPatients[$i] != null){
				if($i%3 == 0 && $i != 0){
					echo '</div>';
					echo '<div class="row">';					
				}else{
					if($i == 0){
						echo '<div class="row">';					
					}
				}
				echo '<div class="col-xs-3">
					<div class="input-group">						
						<label><input name="'.$fieldsPatients[$i].'" id="'.$fieldsPatients[$i].'" value="'.$fieldsPatients[$i].'" type="checkbox" onclick="loadOrderFieldsShow(\''.$fieldsPatients[$i].'\');"> '.$fieldsPatients[$i].'</label>						
					</div>
				      </div>';					
				$i++;
			}
			echo '<div class="col-xs-3">
				<div class="input-group">						
					<label><input name="allFields" type="checkbox" onclick="selectAllFields();"> All Fields:</label>						
				</div>
			      </div>'; 
			echo '</div>';
		    ?>
    		    <hr>
		    <div class="row">
			<div class="col-xs-12">
			    <div class="input-group">
				<input id='find' name='find' type='submit' value=" Find " class="btn btn-success btn-lg" onclick="findData();">
	    &nbsp&nbsp           <input name='reset' type='button' value=" Reset " onclick= "window.location.href = 'report_change_insurance.php';" class="btn btn-danger btn-lg">		    
			    </div>
			</div>			
		    </div>
		</form>              
            </div>	    
        </div>
        <!-- /.row -->
	<hr>
        <!-- Related Projects Row -->
        <div class="row">

            <div class="col-lg-12">
		<?php 		
		if($_POST['find'] == ' Find ') {
			
		?>
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			    <tr>
				<?php 
					$headTableArray = explode(',',$headTable);
					$t = 0;
					while($headTableArray[$t] != null){
						echo '<th>'.$headTableArray[$t].'</th>';
						$t++;
					}
				?>	
				<?php if($_POST['name'] != ''){?>
					<th>Add new insurance</th>
					<th>&nbsp;</th>
				<?php }?>
			    </tr>
			</thead>
					
					
			<tbody>
			<?php
			$conexion = conectar();
			$resultado1 = ejecutar($sql1,$conexion);
			//Query de seguros
			$sql2 = "Select insurance from seguros "; 
			$resultado2 = ejecutar($sql2,$conexion);
			while ($row=mysqli_fetch_array($resultado1)){ 
				echo '<tr>';
				$l = 0;	 
				while($headTableArray[$l]!= null){
					echo '<td>'.$row[$headTableArray[$l]].'</td>';
					 $l++;
				}
				if($_POST['name'] != ''){
					if($row['new_insurance'] == ''){						
						echo '  <td><div id="result_i'.trim($row['Pat_id']).'"><select class="form-control" name="newInsurance_'.trim($row['Pat_id']).'" id="newInsurance_'.trim($row['Pat_id']).'"><option>--- SELECT ---</option>';
						//Imprimir las opciones de los seguros
						while ($row=mysqli_fetch_array($resultado2)) 
						{								
							print("<option value='".$row["insurance"]."'>".$row["insurance"]." </option>");
						}
						echo '</select></div></td>';
						echo '  <td><div id="result_u'.trim($row['Pat_id']).'"><button type="button" class="btn btn-info btn-lg" onclick="updatePatient(\''.trim($row['Pat_id']).'\',\''.$row['company'].'\',\''.$row['Patient_name'].'\');">Update</button></div></td>';
					}else{		
						echo '  <td>'.$row['new_insurance'].'</td>';
						echo '  <td>Actualizado</td>';
					}															
				}						
				echo '</tr>';
			}			
			 ?>				
			<tbody/>
			</table> 
		<?php }?>    
            </div>	    
        </div>
        <!-- /.row -->
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
</body>
</html>