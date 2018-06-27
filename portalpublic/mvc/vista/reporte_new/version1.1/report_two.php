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
$sql_case_one="Select C.id ,C.Pat_id,C.therapist_id,C.start,V.app_id,EM.*,concat(EM.first_name,',',EM.last_name) as user1,EM.assistant,CD.fecha as date_appoitment,P.*,concat(P.First_name,',',P.Last_name) as patient,D.Name as discipline  from calendar_appointments C LEFT JOIN tbl_visits V ON(V.app_id=C.id) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appointment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=P.discipline_id)WHERE V.deleted='0' AND C.attendance='4'  AND C.start< now()   ";
#AND V.id IS NULL
$resultado=ejecutar($sql_case_one,$conexion);
$i=0;
while($row=mysqli_fetch_array($resultado))
{
    $case_uno[$i]=$row;
    $i++;
}
if ($i==0) {
    $co1 = 0;
}
if($i>0){
    $co1=count($case_uno);
}
for($j=0;$j<count($case_uno);$j++) {
    echo  $case_uno[$j]['therapist_id'];
    echo "  -  ";
    echo  $case_uno[$j]['user1'];
    echo "  -  ";
    echo  $case_uno[$j]['assistant'];
    echo "  -  ";
    echo  $case_uno[$j]['date_appoitment'];
    echo "  -  ";
    echo  $case_uno[$j]['patient'];
    echo "  -  ";
    echo  $case_uno[$j]['discipline'];
    echo "  -  ";


    ?> <br>
<?php }


##query para el segundo caso

$sql_case_two="Select C.id,C.Pat_id,C.therapist_id,C.start,V.app_id,E.visit_id,EM.*,concat(EM.first_name,',',EM.last_name) as user1,EM.assistant,CD.fecha as date_appoitment,P.*,concat(P.First_name,',',P.Last_name) as patient,D.Name as discipline from calendar_appointments C LEFT JOIN tbl_visits V ON(V.app_id=C.id) LEFT JOIN tbl_evaluations E ON(E.visit_id=V.id) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appointment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=P.discipline_id) WHERE V.deleted='0' AND V.id_visit_type ='1' AND E.therapist_signed='0' AND C.start< now()";
$resultado=ejecutar($sql_case_two,$conexion);
$i=0;
while($row=mysqli_fetch_array($resultado))
{
    $case_two[$i]=$row;
    $i++;
}
if ($i==0) {
    $co2 = 0;
}
if($i>0){
    $co2=count($case_two);
}


$sql_case_tree="Select C.id,EM.*,concat(EM.first_name,',',EM.last_name) as user1,EM.assistant,CD.fecha as date_appoitment,P.*,concat(P.First_name,',',P.Last_name) as patient,D.Name as discipline From calendar_appointments C  LEFT JOIN tbl_visits V ON(V.app_id=C.id) LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id) JOIN tbl_signature_note SN ON(SN.id_note=ND.id_notes) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appointment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=P.discipline_id) WHERE V.deleted='0' AND V.id_visit_type='2'  AND C.start< now() ";
#AND COUNT='0'
$resultado=ejecutar($sql_case_tree,$conexion);
$i=0;
while($row=mysqli_fetch_array($resultado))
{
    $case_tree[$i]=$row;
    $i++;
}
if ($i==0) {
    $co3 = 0;
}
if($i>0){
    $co3=count($case_tree);
}

$sql_case_four ="SELECT C.id,EM.*,concat(EM.first_name,',',EM.last_name) as user1,EM.assistant,CD.fecha as date_appoitment,P.*,concat(P.First_name,',',P.Last_name) as patient,D.Name as discipline From calendar_appointments C  LEFT JOIN employee E ON(C.therapist_id=E.id) LEFT JOIN tbl_visits V ON(V.app_id=C.id) LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id) JOIN tbl_signature_note SN ON(SN.id_note=ND.id_notes) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appointment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=P.discipline_id) WHERE E.assistant='1' AND V.deleted='0' AND V.id_visit_type='2' AND C.start<now()";
#count<2
$resultado=ejecutar($sql_case_four,$conexion);
$i=0;
while($row=mysqli_fetch_array($resultado))
{
    $case_four[$i]=$row;
    $i++;
}
if ($i==0) {
    $co4 = 0;
}
if($i>0){
    $co4=count($case_four);
}

echo $co1+$co2+$co3+$co4;

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
        <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">REPORTE 2</font></h3>   </div>

    </div>
    <hr>
    <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Patient</font></label>
        <div class="col-sm-8">
            <select class="form-control" id="patient" name="patient" >
                <option value="user">Select  &nbsp;&nbsp;&nbsp; </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 form-control1-label text-right"><font color="#585858">DOB</font></label>
        <div class="col-sm-8">
            <label class="form-control1" id="DOB" name="DOB" > <?php echo "hola"?>

            </label>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 form-control2-label text-right"><font color="#585858">Phone</font></label>
        <div class="col-sm-8">
            <label class="form-control2" id="phone" name="phone" > <?php echo "hola"?>

            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 form-control3-label text-right"><font color="#585858">Count</font></label>
        <div class="col-sm-8">
            <label class="form-control3" id="count" name="count" > <?php echo "hola"?>

            </label>
        </div>
    </div>

</head>
</html>
