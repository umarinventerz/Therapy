<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
    echo "<script>alert('Must LOG IN First')</script>";
    echo "<script>window.location='../../../index.php';</script>";
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
        echo "<script>window.location='../../home/home.php';</script>";
    }
}
?>

<html lang="en">


<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>.: THERAPY  AID :.</title>

    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">
    <link href="../../../css/dataTables/dataTables.bootstrap.css"rel="stylesheet" type="text/css">
    <link href="../../../css/dataTables/buttons.dataTables.css"rel="stylesheet" type="text/css">

    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/AjaxConn.js" type="text/javascript" ></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>
    <script src="../../../js/funciones.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../../../js/resources/shCore.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/buttons.html5.js" type="text/javascript"></script>



    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>



    <script>

        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 3, "desc" ]]
            });
        } );

        

        </script>
</head>


<body  >




<!-- Navigation -->
<?php  $cifra = 100.2534; echo number_format($cifra,2); $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
<!-- Page Content -->
<div >
    <div class="row">
        <div class="col-lg-12">
            <?php include 'menu_vertical.php'; ?>
        </div>
    </div>
</div>
<!-- /.container -->




<div class="container">
<div class="col-lg-12">
<table id="example" class="table
">
    <thead>
    <tr>
        <th>Divice</th>
        <th>Informations</th>
        <th>Ubications </th>
        <th>Date </th>




    </tr>
    </thead>

    <tbody>

    <?php
    $conexion = conectar();
    $user_browser = $_SERVER['HTTP_USER_AGENT'];

    $query01 = " select CI.*,LN.url,LOS.ruta from conections_informations CI left join logo_navegador LN on LN.logo_navegador = CI.brouser left join logo_os LOS on LOS.os = CI.os WHERE  user_id ='".$_SESSION['user_id']."' ;";
    $result01 = mysqli_query($conexion, $query01);

    while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
        echo '<tr>	 
						
						<td class="col-sm-3"><img style="width: 10%" src="../../../'.$row['ruta'].' ">'   .$row['os'].'</td>
						
					  <td class="col-sm-3"> <img style="width: 10%" src="../../../images/'.$row['url'].' ">'  .$row['brouser'].'</td>
					 
					  <td class="col-sm-3"><b>'.$row['region'].','.$row['country'].' </b><br> '.$row['city'].' </td>
					 <td class="col-sm-3">'.$row['date'].'</td>
					   
					  
					  
				
					</tr>';
    }

    ?>



    </tbody>













</table>
</div>
</div>
</body>
</html>
