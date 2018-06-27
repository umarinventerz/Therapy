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
$id=$_SESSION['user_id'];

$conexion=conectar();


###query para primer caso
$sql_tera="Select assistant from employee where id='".$id."'";
$resultado=ejecutar($sql_tera,$conexion);
while($row=mysqli_fetch_array($resultado))
{
    $terapist=$row;
}
#echo $terapist['assistant'];

if ($terapist['assistant']==1)
{
    $sql_reptree_case_one="Select C.id,P.*,concat(P.Last_name,'.',P.First_name) as pat_name3,CD.fecha as date_appoitment From calendar_appointments C LEFT JOIN employee EM ON (C.therapist_id=EM.id)  LEFT JOIN tbl_visits TV ON (TV.app_id=C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) LEFT JOIN calendar_appoitment_date CD ON (C.id=CD.id) WHERE EM.id='".$id."' AND TV.deleted='0' AND C.attendance='4' AND C.Pat_id != 'NULL' AND C.start<now()";
$resultado=ejecutar($sql_reptree_case_one,$conexion);
    $i=0;
    while($row=mysqli_fetch_array($resultado)){
        $case_rtree_one[$i]=$row;
    $i++;
    }
    echo $i;
    #segundo caso
    $sql_reptree_case_two_count="Select C.id,COUNT(C.id) as contador From calendar_appointments C LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoitment_date CD ON(CD.calendar_appoitment_id=C.id) LEFT JOIN tbl_visits TV ON (TV.app_id=C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) WHERE EM.id='".$id."' AND TV.deleted='0' AND C.attendance='4' AND C.Pat_id != 'NULL' AND C.start<now() GROUP BY C.id ORDER BY contador desc";

    $resultado=ejecutar($sql_reptree_case_two_count,$conexion);
    $i=0;

    while($row=mysqli_fetch_array($resultado)){
        $case_rtree_two_count[$i]=$row;
        $i++;
    }

    if($i>0){
        $num_notes=count($case_rtree_two_count);

        for($j=0;$j<count($num_notes);$j++) {
            $sql_reptree_case_two = "Select C.id,concat(P.Last_name,'.',P.First_name) as pat_name3,CD.fecha as date_appoitment  From calendar_appointments C LEFT JOIN employee EM ON(EM.id=C.therapist_id)  LEFT JOIN tbl_visits TV ON (TV.app_id=C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) LEFT JOIN calendar_appoitment_date CD ON (C.id=CD.id) WHERE C.id='".$case_rtree_two_count[$j]['id']."'";
            $resultado = ejecutar($sql_reptree_case_two, $conexion);
           while ($row = mysqli_fetch_array($resultado)) {
                $case_rtree_two[$j] = $row;

            }
        }
    }
echo $i;
}







#echo $co1+$co2+$co3+$co4;

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

        function validar_formulario(){
            var parametros_formulario = $('#form_consultar_notas').serialize();

            $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
            $("#resultado").load("../notas/result_notas.php?"+parametros_formulario);

            return false;
        }

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

    <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">REPORTE 1</font></h3>   </div>

    </div>
    <hr>
    <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">User</font></label>
        <div class="col-sm-8">
            <select class="form-control" id="user" name="user" >
                <option value="user"><?php  echo $case_uno[1]['user1']?>  &nbsp;&nbsp;&nbsp; </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 form-control1-label text-right"><font color="#585858">Therapist</font></label>
        <div class="col-sm-8">
            <label class="form-control1" id="therapist" name="therapist" >  <?php if ($case_uno[1]['assistant']==0){echo 'SUPERVISOR';}
                if ($case_uno[1]['assistant']==1){echo 'ASSISTANT';} ?>

            </label>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 form-control2-label text-right"><font color="#585858">Date</font></label>
        <div class="col-sm-8">
            <label class="form-control2" id="date" name="date" > <?php echo $case_uno[1]['date_appoitment']?>

            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 form-control3-label text-right"><font color="#585858">Patient</font></label>
        <div class="col-sm-8">
            <label class="form-control3" id="pat" name="pat" > <?php echo $case_uno[1]['patient']?>

            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 form-control4-label text-right"><font color="#585858">Discipline</font></label>
        <div class="col-sm-8">
            <label class="form-control4" id="discipline" name="discipline" > <?php echo $case_uno[1]['discipline']?>

            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 form-control5-label text-right"><font color="#585858">Day</font></label>
        <div class="col-sm-8">
            <label class="form-control5" id="day" name="day" > <?php echo "4"?>

            </label>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 form-control6-label text-right"><font color="#585858">Case</font></label>
        <div class="col-sm-8">
            <label class="form-control6" id="case" name="case" > <?php echo "1"?>

            </label>
        </div>
    </div>


</head>
</html>
