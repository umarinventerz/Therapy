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
<?php
$conexion=conectar();


###query para primer caso

##query para el segundo caso

$sql_case_one_rdos="Select C.id,COUNT(C.id) as coun  from calendar_appointments C   JOIN calendar_appoitment_date CAD ON(CAD.calendar_appoitment_id =C.id) WHERE C.attendance != '4' AND C.Pat_id != 'NULL'  AND C.start< now() GROUP BY C.id ORDER BY coun desc ";
#AND V.id IS NULL
$resultado=ejecutar($sql_case_one_rdos,$conexion);
$i=0;
while($row=mysqli_fetch_array($resultado))
{
    $case_dos_rdos[$i]=$row;
    $i++;
}
if ($i==0) {
    $co1_rdos = 0;
}
if($i>0){
    $co2_rdos=count($case_dos_rdos);


for($j=0;$j<count($case_dos_rdos);$j++) {
    $sql_case_one_rdos = "Select C.id as c_id,P.DOB,P.*,concat(P.Last_name,'.',P.First_name) as pat_name,P.phone  from calendar_appointments C   JOIN calendar_appoitment_date CAD ON(CAD.calendar_appoitment_id =C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) WHERE C.id='" . $case_dos_rdos[$j]['id'] . "' ";
#AND V.id IS NULL
    $resultado = ejecutar($sql_case_one_rdos, $conexion);

    while ($row = mysqli_fetch_array($resultado)) {
        $case_uno_rdos[$j] = $row;

    }
}


}

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

    <!-- ################### PARA EL CALENDARIO EN LAS GRAFICAS######################-->
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript" language="javascript">



    </script>

    <?php# $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
    </div>

    <!-- empezar aqui lo del formulario -->
    <div id="segundo">
    <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">REPORTE 2</font></h3>   </div>

    </div>
    <hr>
    <table class="table table-striped" id="segundo_reporte" style="text-align: center">
        <tr class="active">
            <td><b>Patient</b></td>
            <td><b>DOB</b></td>
            <td><b>Phone</b></td>
            <td><b>Count</b></td>
        </tr>
        <?php if($co2_rdos==0){
            ?>
<tr style="align-content: center"><b>No Hay reportes</b></tr>
        <?php }  ?>
        <?php if($co2_rdos>0){
        for($j=0;$j<$co2_rdos;$j++){
            ?>
            <tr >
                <td><?=$case_uno_rdos[$j]['pat_name']?></td>
                <td><?=$case_uno_rdos[$j]['DOB']?></td>
                <td><?=$case_uno_rdos[$j]['phone']?></td>
                <td><b><?=$case_dos_rdos[$j]['coun']?></b></td>

                                            </tr>


<?php }  }?>

    </div>
</head>
</html>
