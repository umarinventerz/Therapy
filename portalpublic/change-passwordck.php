<?Php
//***************************************
// This is downloaded from www.plus2net.com //
/// You can distribute this code with the link to www.plus2net.com ///
//  Please don't  remove the link to www.plus2net.com ///
// This is for your learning only not for commercial use. ///////
//The author is not responsible for any type of loss or problem or damage on using this script.//
/// You can use it at your own risk. /////
//*****************************************
session_start();
require_once 'conex.php';
?>
<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" type='text/css'/>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" type='text/css'>
    <link href="css/portfolio-item.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

<style type="text/css">
    .bs-example{
        margin: 20px;
    }
</style>
</head>

<body>
<?Php
$conexion = conectar();
// check the login details of the user and stop execution if not logged in
//require "check.php";
///////Collect the form data /////


$todo = sanitizeString($conexion, $_POST['todo']);
$user = sanitizeString($conexion, $_POST['inputUser']);
$password = sanitizeString($conexion, $_POST['password']);
$password2 = sanitizeString($conexion, $_POST['password2']);
$old_password = sanitizeString($conexion, $_POST['old_password']);






/////////////////////////

if(isset($todo) and $todo=="change-password"){
$status = "OK";
$msg="";

			
$conexion = conectar();
$query2 = "select user_name, first_name, last_name, user_id, status_id, user_type , password from user_system where user_name ='".$user."' AND password='".$old_password."' AND status_id = 1"; //,Company,Insurance_name
$result2 = mysqli_query($conexion, $query2);

while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
	//echo $row['password'];
//echo $password;
//echo $old_password;




if($row['password']<>$old_password){
$msg=$msg."Your old password  is not matching as per our record.<BR>";
$status= "NOTOK";
}					

if ( strlen($password) < 3 or strlen($password) > 10 ){
$msg=$msg."Password must be more than 3 char legth and maximum 8 char lenght<BR>";
$status= "NOTOK";}					

if ( $password <> $password2 ){
$msg=$msg."Both passwords are not matching<BR>";
$status= "NOTOK";}					

    

if($status<>"OK"){ 
	echo '<font face="verdana, arial, helvetica" size="2" color="red"><center>Sorry <br> Failed to change password Contact Site Admin</font></center>';

?>
<div class="col-sm-offset-5 col-sm-2 text-center">
<button  type="button" class="btn btn-primary btn-lg center-block" onclick="window.location='change-password.php';">RETURN</button>
</div>
<?Php

//echo "<font face='Verdana' size='2' color=red>$msg</font><br><center><input type='button' value='Retry' onClick='history.go(-1)'></center>";
}else{ // if all validations are passed.
//$password=$password; // Encrypt the password before storing
//if(mysql_query("update plus_signup set password='$password' where userid='$_SESSION[userid]'")){
$query3 = "update user_system set password='".$password2."' where user_name ='".$user."'";
ejecutar($query3,$conexion);	


echo 'Actualizado';

echo '<font face="verdana, arial, helvetica" size="2" color="green" ><center>Thanks <br> Your password changed successfully. Please keep changing your password for better security</font></center>';
?>
<div class="col-sm-offset-5 col-sm-2 text-center">
<button  type="button" class="btn btn-primary btn-lg center-block" onclick="window.location='index.php';">RETURN</button>
</div>
<?Php

} 
}
} 
?>

<center>


</body>

</html>
