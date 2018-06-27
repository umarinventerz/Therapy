<?php
require_once("conex.php");

?>

<span class="opcionsistema" onclick="ajax_hideTooltip()"><font color="#855636" size="4px">Patient Info</font></span>
<br />
<?php
$conexion = conectar();   	
$query2 = " select distinct Last_name, Pri_Ins, Pat_id, Table_name as Company from patients 
WHERE Pat_id = '".$_GET['Pat_id']."'	"; //Colocar el query que traiga las fechas
// utilizando como condicion (WHERE) el Patient_id que llega

echo '<h3>'.$_GET['Pat_id'].'</h3>'; //Quitar esta linea

$result2 = mysqli_query($conexion, $query2);
	$i = 0;
	//echo '<table border="1" cellpadding="45" cellspacing="10">';
	while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
		echo '<b>Last Name:</b>'.$row['Last_name'].'</br>';
		echo '<b>Pri Ins:</b>'.$row['Pri_Ins'].'</br>';
		echo '<b>Patient ID:</b>'.$row['Pat_id'].'</br>';
		echo '<b>Company:</b>'.$row['Company'].'</br>';
		$i++;
		break;
	}
	if($i == 0){
		
		echo '<h3>NO  INFO</h3>';
	}
			    ?>	

