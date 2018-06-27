<?Php
//***************************************
// This is downloaded from www.plus2net.com //
/// You can distribute this code with the link to www.plus2net.com ///
//  Please don't  removetetgergrtg the link to www.plus2net.com ///
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

<meta name="GENERATOR" content="Arachnophilia 4.0">
<meta name="FORMATTER" content="Arachnophilia 4.0">

    <title>.: THERAPY  AID :.</title>
   <link rel="stylesheet" href="css/bootstrap.min.css" type='text/css'/>
    <link href="css/portfolio-item.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>



</head>

<body >
<?Php
// check the login details of the user and stop execution if not logged in


echo "<form action='change-passwordck.php' method=post><input type=hidden name=todo value=change-password>

<table border='0' cellspacing='0' cellpadding='0' align=center>
 <tr bgcolor='#f1f1f1' > <td colspan='2' align='center'><font face='verdana, arial, helvetica' size='2' align='center'>&nbsp;<b>Change  Password</b> </font></td> </tr>

<tr bgcolor='#f1f1f1' > <td ><font face='verdana, arial, helvetica' size='2' align='center'> User Name
</font></td> <td  align='center'>
<input type='text' class='form-control' name='inputUser' id='inputUser' placeholder='UserName'></tr>


<tr bgcolor='#f1f1f1' > <td ><font face='verdana, arial, helvetica' size='2' align='center'> Old Password 
</font></td> <td  align='center'>
<input type ='password' class='form-control' name='old_password' ></font></td></tr>


<tr > <td ><font face='verdana, arial, helvetica' size='2' align='center'> New Password  
</font></td> <td  align='center'><font face='verdana, arial, helvetica' size='2' >
<input type ='password' class='form-control' name='password' ></font></td></tr>

<tr bgcolor='#f1f1f1' > <td ><font face='verdana, arial, helvetica' size='2' align='center'> New Password (Re-enter) 
</font></td> <td  align='center'><font face='verdana, arial, helvetica' size='2' >
<input type ='password' class='form-control' name='password2' ></font></td></tr>

<tr bgcolor='#ffffff' > <td colspan=2 align=center><input class='btn btn-default' type=submit value='CHANGE PASSWORD'>&nbsp;<a class='btn btn-default' href='index.php'>HOME</a></tr></form>

";


echo "</table>";

//require "bottom.php";

?>
<center>


</body>

</html>
