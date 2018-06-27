<?php

session_start();
require_once("conex.php");

$user = $_POST['inputUser'];
$password = $_POST['inputPassword'];
$conexion = conectar();
$query2 = "select user_name, first_name, last_name, user_id, status_id, user_type from user_system where user_name ='".$user."' AND password='".$password."' AND status_id = 1"; //,Company,Insurance_name

$result2 = mysqli_query($conexion, $query2);

die();
$user_id  = '';
while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
	$last_name = $row['last_name']; 
	$first_name = $row['first_name'];
	$user_id = $row['user_id'];
	$status_id = $row['status_id'];
	$user_type = $row['user_type'];
	
}

if($user_id == ''){	
	//$_SESSION['error_login'] = 'User or Password are incorrect!'; 
	//echo '<script>window.location="index2.php";</script>';
	echo "<script type=\"text/javascript\">alert(\"INVALID USER OR PASSWORD\");</script>";
	echo '<script>window.location="index.php";</script>';
}else{
	$_SESSION['last_name'] = $last_name; 
	$_SESSION['first_name'] = $first_name; 
	$_SESSION['user_id'] = $user_id;
	$_SESSION['status_id'] = $status_id; 
	$_SESSION['user_type'] = $user_type;

	if($_SESSION['user_type'] <> '1'){

	echo '<script>window.location="home.php";</script>';
	}else{
		echo '<script>window.location="home_1.php";</script>';
	}
}
?>



