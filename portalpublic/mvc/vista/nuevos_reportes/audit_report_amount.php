<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}


$conexion = conectar();
$sql2  = "SELECT A.*,P.Last_name as apellido_patients,P.first_name as nombre_patients,U.Last_name as apellido_user,U.First_name as nombre_user FROM tbl_aud_report_amount A LEFT JOIN patients P ON(P.Pat_id=A.Pat_id) LEFT JOIN user_system U ON(U.user_id=A.user_id) ORDER BY A.id DESC";
$resultado1 = ejecutar($sql2,$conexion); 
$j = 0;      
while($datos = mysqli_fetch_assoc($resultado1)){            
    $respuesta[$j] = $datos;
    $j++;
}

//var_dump($respuesta);die();
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
    


    <link href="../../../plugins/bootstrap-table/src/bootstrap-table.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>    
    <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
    <script src="../../../js/audit_report_amount.js"></script>
    <script src="../../../plugins/bootstrap-table/src/bootstrap-table.js"></script>


   
</head>

<body>


<!-- NAV BAR  -->
 <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row" >
            <div class="col-lg-12">
                <h1><div align="center"><u>Audit report amount</u></div></h1>
            </div>
        </div>
        <div class="row" >
            <div class="col-lg-12">
      		<br><br><br>
		<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">

		<h3><?php echo '<b>User:</b> '.$_SESSION['first_name'].' '.$_SESSION['last_name']?></h3>
            </div>
        </div>
       <table  id="reports_aumont" class="display"                
                data-show-columns="true"
                data-search="true"
                data-url="audit_report_amount.php"
                data-pagination="true"
                data-pageList="[5, 10, 20, 50, 100, 200]"
                data-query-params="queryParams"
                data-heigh="350"
                data-page-size="10">

            <thead>
                <tr>
                    <th data-field="id">
                        <b>Id</b>
                    </th>

                    <th data-field="user">
                        <b>User</b>
                    </th>
                    <th data-field="patients">
                        <b>Patients</b>
                    </th>
                    <th data-field="dicipline">
                        <b>Dicipline</b>
                    </th>
                    <th data-field="type_report">
                        <b>Type report</b>
                    </th>
                    <th data-field="fecha">
                        <b>Date</b>
                    </th>
                    <th data-field="hour">
                        <b>Hour</b>
                    </th>

                </tr>
            </thead>
             <tbody>  

                 <?php 
                    $i=0;
                    while($respuesta[$i]!=null){
                 ?>
                    <tr>
                        <td><?=$respuesta[$i]['id']?></td>
                        <td><?=$respuesta[$i]['apellido_user']." ".$respuesta[$i]['nombre_user']?></td>
                        <td><?=$respuesta[$i]['apellido_patients']." ".$respuesta[$i]['nombre_patients']?></td>
                        <td><?=$respuesta[$i]['dicipline']?></td>
                        <td><?=$respuesta[$i]['type_report']?></td>
                        <td><?php $valor=explode(' ', $respuesta[$i]['created_at']);echo $valor[0];?></td>
                        <td><?=$valor[1]?></td>

                    </tr>
                <?php $i++; } ?>
                
             </tbody>
        </table>

</html>
