
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
$sql_case_one="Select C.id ,C.Pat_id,C.therapist_id,V.app_id,EM.*,concat(EM.first_name,'  ',EM.last_name) as user1,EM.assistant,CD.start as date_appoitment,P.*,concat(P.First_name,'  ',P.Last_name) as patient,D.Name as discipline  from calendar_appointments C LEFT JOIN tbl_visits V ON(V.app_id=C.id) and V.deleted='0' LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=EM.discipline_id) WHERE true  and V.id is NULL AND C.attendance='4'  AND date(CD.start)< now()   ";
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



##query para el segundo caso

$sql_case_two="Select C.id,C.Pat_id,C.therapist_id,V.app_id,E.visit_id,EM.*,concat(EM.first_name,' ',EM.last_name) as user1,EM.assistant,CD.start as date_appoitment,P.*,concat(P.First_name,'  ',P.Last_name) as patient,D.Name as discipline from calendar_appointments C LEFT JOIN tbl_visits V ON(V.app_id=C.id) AND V.deleted='0' LEFT JOIN tbl_evaluations E ON(E.visit_id=V.id) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=EM.discipline_id) WHERE   V.id_visit_type ='1' AND E.therapist_signed='0' AND date(CD.start)< now()";
$resultado=ejecutar($sql_case_two,$conexion);
$i=0;
#$case1=array("case"=>'2');
while($row=mysqli_fetch_array($resultado))
{
    $case_two[$i]=$row;
    $i++;
}
if ($i==0) {
    $co2 = 0;
}
#echo $i;
if($i>0){
    $co2=count($case_two);
}

#######################TERCERA QUERRY########

$sql_case_tree_count="Select SN.id_note,COUNT(SN.id_note) as contador_test From calendar_appointments C  LEFT JOIN tbl_visits V ON(V.app_id=C.id)  LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id) JOIN tbl_signature_note SN ON(SN.id_note=ND.id_notes)    WHERE  V.deleted='0' and V.id_visit_type='2'  GROUP BY SN.id_note ";
$resultado=ejecutar($sql_case_tree_count,$conexion);
$l=0;
while($row=mysqli_fetch_array($resultado))
{
    $case_tree_coun[$l]=$row;
    $l++;
}
if($l==0) {
$mejorar=0;
    $sql_case_tree = "Select C.id,ND.id_notes,EM.*,concat(EM.first_name,' ',EM.last_name) as user1,EM.assistant,CD.start as date_appoitment,P.*,concat(P.First_name,' ',P.Last_name) as patient,D.Name as discipline From calendar_appointments C  LEFT JOIN tbl_visits V ON(V.app_id=C.id) and V.deleted='0' and V.id_visit_type='2' LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id)  LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=EM.discipline_id) WHERE   date(CD.start)< now()";

    $resultado = ejecutar($sql_case_tree, $conexion);
    $i = 0;
    while ($row = mysqli_fetch_array($resultado)) {
        $case_tree[$i] = $row;
        $i++;
    }
    if ($i == 0) {
        $co3 = 0;
    }
    if ($i > 0) {
        $co3 = count($case_tree);
    }

}

if($l>0)
{

    $i=0;
    for($j=0;$j<$l;$j++) {


        $sql_case_tree = "Select C.id,ND.id_notes,EM.*,concat(EM.first_name,' ',EM.last_name) as user1,EM.assistant,CD.start as date_appoitment,P.*,concat(P.First_name,' ',P.Last_name) as patient,D.Name as discipline From calendar_appointments C  LEFT JOIN tbl_visits V ON(V.app_id=C.id) and V.deleted='0' and V.id_visit_type='2' LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id)  LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=EM.discipline_id) WHERE  ND.id_notes!='" .$case_tree_coun[$j]['id_note']."' and date(CD.start)< now()";

        $resultado = ejecutar($sql_case_tree, $conexion);
        $i = 0;

        while ($row = mysqli_fetch_array($resultado)) {
            $case_tree[$i] = $row;

            $i++;
        }
    }

    if ($i == 0) {
        $co3 = 0;
    }
    if ($i > 0) {
        $co3 = count($case_tree);
    }

    $mejorar=0;
for($j=0;$j<$co3;$j++)
{
    for ($x = 0; $x < $l; $x++) {
        if ( $case_tree[$j]['id_notes'] == $case_tree_coun[$x]['id_note'] )
        {
            $case_tree[$j]['id_notes']='repetido';

            $mejorar++;
        }
    }
}
}
#######################cuaarto caso ##################################
$sql_case_cuatro_count="Select SN.id_note,COUNT(SN.id_note) as contador_test4 From calendar_appointments C LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN tbl_visits V ON(V.app_id=C.id) and V.deleted='0' and V.id_visit_type='2'
LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id)
JOIN tbl_signature_note SN ON(SN.id_note=ND.id_notes)

WHERE EM.assistant='1'   GROUP BY SN.id_note ";

$resultado=ejecutar($sql_case_cuatro_count,$conexion);
$w=0; #paara el cuarto caso
while($row=mysqli_fetch_array($resultado))
{
    $case_cuatro_coun[$w]=$row;
    $w++;
}

##################pa arriba es nuevo
if($w == 0) {
    $sql_case_four = "SELECT C.id,ND.id_notes,EM.*,concat(EM.first_name,' ',EM.last_name) as user1,EM.assistant,CD.start as date_appoitment,P.*,concat(P.First_name,' ',P.Last_name) as patient,D.Name as discipline From calendar_appoinments C  LEFT JOIN tbl_visits V ON(V.app_id=C.id) LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN  CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=EM.discipline_id) WHERE EM.assistant='1' AND V.deleted='0' AND V.id_visit_type='2' AND date(CD.start)<now()";
#count<2calendar_appoitment_date
    $resultado = ejecutar($sql_case_four, $conexion);
    $i = 0;
    while ($row = mysqli_fetch_array($resultado)) {
        $case_four[$i] = $row;
        $i++;
    }
    if ($i == 0) {
        $co4 = 0;
    }
    if ($i > 0) {
        $co4 = count($case_four);
    }
}
if ($w!=0)
{
               $r=0;
    $q=0;
    for($j=0;$j<$w;$j++){
        if($case_cuatro_coun[$j]['contador_test4']<2){

       #     echo $case_cuatro_coun[$j]['id_note'];
            $sql_case_four = "SELECT C.id,ND.id_notes,EM.*,concat(EM.first_name,' ',EM.last_name) as user1,EM.assistant,CD.start as date_appoitment,P.*,concat(P.First_name,' ',P.Last_name) as patient,D.Name as discipline From calendar_appointments C  LEFT JOIN tbl_visits V ON(V.app_id=C.id) LEFT JOIN tbl_notes_documentation ND ON(ND.visit_id=V.id) LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.id) LEFT JOIN patients P ON (P.id = C.Pat_id) LEFT JOIN discipline D ON (D.DisciplineId=EM.discipline_id) WHERE ND.id_notes='".$case_cuatro_coun[$j]['id_note']."' and EM.assistant='1' AND V.deleted='0' AND V.id_visit_type='2' AND date(CD.start)<now()";

            $resultado = ejecutar($sql_case_four, $conexion);
            $i = 0;
            while ($row = mysqli_fetch_array($resultado)) {
                $case_four[$i] = $row;
                $i++;
                $q++;
            }}


        if($case_cuatro_coun[$j]['contador_test4']>1){
            $r++;


        }
        }

    if ($i == 0 || $q==0) {

        $co4 = 0;
    }
    if ($q > 0) {
        $co4 = count($case_four);
    }

}
$total= $co1+$co2+$co3+$co4;
######################################################### segundo caso ########################
#parte del segundo reporte

$sql_case_one_rdos="Select C.id,COUNT(C.id) as coun  from calendar_appointments C   JOIN calendar_appoiment_date CAD ON(CAD.calendar_appoitment_id =C.id) WHERE C.attendance != '4' AND C.Pat_id != 'NULL'  AND CAD.start< now() GROUP BY C.id ORDER BY coun desc ";
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
        $sql_case_one_rdos = "Select C.id as c_id,P.DOB,P.*,concat(P.Last_name,'.',P.First_name) as pat_name,P.phone  from calendar_appointments C   JOIN calendar_appoiment_date CAD ON(CAD.calendar_appoitment_id =C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) WHERE C.id='" . $case_dos_rdos[$j]['id'] . "' ";
#AND V.id IS NULL
        $resultado = ejecutar($sql_case_one_rdos, $conexion);

        while ($row = mysqli_fetch_array($resultado)) {
            $case_uno_rdos[$j] = $row;

        }
    }


}
###################################parte del tercer reporte###############################################

$id3=$_SESSION['user_id'];

$conexion=conectar();


###query para primer caso
$sql_tera="Select assistant from employee where id='".$id3."'";
$resultado=ejecutar($sql_tera,$conexion);
while($row=mysqli_fetch_array($resultado))
{
    $terapist=$row;
}
#echo $terapist['assistant'];

if ($terapist['assistant']==1)
{
    $sql_reptree_case_one="Select C.id,P.*,concat(P.Last_name,'  ',P.First_name) as pat_name3,CD.start as date_appoitment From calendar_appointments C LEFT JOIN employee EM ON (C.therapist_id=EM.id)  LEFT JOIN tbl_visits TV ON (TV.app_id=C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.calendar_appoitment_id) WHERE EM.id='".$id3."' AND TV.deleted='0' AND C.attendance='4' AND C.Pat_id != 'NULL' AND date(CD.start)<now()";
    $resultado=ejecutar($sql_reptree_case_one,$conexion);
    $i=0;
    while($row=mysqli_fetch_array($resultado)){
        $case_rtree_one[$i]=$row;
        $i++;
    }

    #echo $i;
    if($i==0){
        $contador_case_rtree_one=0;
    }
    if($i>0) {
        $contador_case_rtree_one = count($case_rtree_one);
    }
    #segundo caso
    $sql_reptree_case_two_count="Select C.id,COUNT(C.id) as contador From calendar_appointments C LEFT JOIN employee EM ON(EM.id=C.therapist_id) LEFT JOIN calendar_appoiment_date CD ON(CD.calendar_appoitment_id=C.id) LEFT JOIN tbl_visits TV ON (TV.app_id=C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) WHERE EM.id='".$id3."' AND TV.deleted='0' AND C.attendance='4' AND C.Pat_id != 'NULL' AND date(CD.start)<now() GROUP BY C.id ORDER BY contador desc";

    $resultado=ejecutar($sql_reptree_case_two_count,$conexion);
    $i=0;

    while($row=mysqli_fetch_array($resultado)){
        $case_rtree_two_count[$i]=$row;
        $i++;
    }

    if($i>0){
        $num_notes=count($case_rtree_two_count);

        for($j=0;$j<count($num_notes);$j++) {
            $sql_reptree_case_two = "Select C.id,concat(P.Last_name,' ',P.First_name) as pat_name3,CD.start as date_appoitment  From calendar_appointments C LEFT JOIN employee EM ON(EM.id=C.therapist_id)  LEFT JOIN tbl_visits TV ON (TV.app_id=C.id) LEFT JOIN patients P ON (P.id=C.Pat_id) LEFT JOIN calendar_appoiment_date CD ON (C.id=CD.idcalendar_appoitment_id) WHERE C.id='".$case_rtree_two_count[$j]['id']."'";
            $resultado = ejecutar($sql_reptree_case_two, $conexion);
            while ($row = mysqli_fetch_array($resultado)) {
                $case_rtree_two[$j] = $row;

            }
        }
    }

}
if ($terapist['assistant']==0)
{
$contador_case_rtree_one=0;
    $num_notes=0;
}
$total3=$num_notes+$contador_case_rtree_one;


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
    <script>
        $(document).ready(function() {
            $("#segundo").hide();
            $("#tercero").hide();
            $("#primer").hide();
        $("#boton_primer_reporte").click(function(){
            $("#primer").toggle();
            $("#segundo").hide();
            $("#tercero").hide();
        })
            $("#boton_segundo_reporte").click(function(){
                $("#segundo").toggle();
                $("#primer").hide();
                $("#tercero").hide();
            })
            $("#boton_tercer_reporte").click(function(){
                $("#tercero").toggle();
                $("#primer").hide();
                $("#segundo").hide();
            })
        })


    </script>

    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
</div>
    <div  align="center">
        <button id="boton_primer_reporte" type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off" >Primer repoter <span class="badge" style="color: #ff0000"><?php echo ($total-$mejorar)?></span></button>
        <button id="boton_segundo_reporte" type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off" >Segundo reporte  <span class="badge"style="color: #ff0000"><?php echo $co2_rdos?></span></button>
        <button id="boton_tercer_reporte"type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off" >Tercer repoter <span class="badge"style="color: #ff0000"><?php echo $total3?></span></button>

    </div>
    <!-- empezar aqui lo del formulario -->
<div id="primer">
    <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8" align="center"><h3><font color="#BDBDBD">REPORTE 1</font></h3>   </div>

    </div>
    <hr>

        <table class="table table-striped" id="primer_reporte" style="text-align: center">
        <tr class="active">
            <td><b>User</b></td>
            <td><b>Therapist</b></td>
            <td><b>Date</b></td>
            <td><b>Patient</b></td>
            <td><b>Discipline</b></td>
            <td><b>Day</b></td>
            <td><b>Case</b></td>
        </tr>
        <?php if($co1>0){
            for($j=0;$j<$co1;$j++){

                             ?>
            <tr style="color: #2e383c" >
                <td ><?php echo $case_uno[$j]['user1']?></td>
                <td><?php if ($case_uno[$j]['assistant']==0){echo 'SUPERVISOR';}
                    if ($case_uno[$j]['assistant']==1){echo 'ASSISTANT';} ?></td>
                <td><?=$case_uno[$j]['date_appoitment']?></td>
                <td><?=$case_uno[$j]['patient']?></td>
                <td><?=$case_uno[$j]['discipline']?></td>
                <td><?php echo floor(abs(time() - strtotime($case_uno[$j]['date_appoitment']))/(60*60*24)) ;  ?></td>
                <td><b>Patient Present - Visit Not Created</b></td>

            </tr>


        <?php } }?>

        <?php if($co2>0){
            for($j=0;$j<$co2;$j++){

                ?>
                <tr style="color: #2e383c" >
                    <td ><?php echo $case_two[$j]['user1']?></td>
                    <td><?php if ($case_two[$j]['assistant']==0){echo 'SUPERVISOR';}
                        if ($case_two[$j]['assistant']==1){echo 'ASSISTANT';} ?></td>
                    <td><?=$case_two[$j]['date_appoitment']?></td>
                    <td><?=$case_two[$j]['patient']?></td>
                    <td><?=$case_two[$j]['discipline']?></td>
                    <td><?php echo floor(abs(time() - strtotime($case_two[$j]['date_appoitment']))/(60*60*24)) ;  ?></td>
                    <td><b>Evaluation Missing Signature</b></td>

                </tr>


            <?php } }?>

        <?php if($co3>0){
            for($j=0;$j<$co3;$j++){
              $b=0;#obviarlos valores que se me pierden
                if($case_tree[$j]['id_notes']=='repetido')
              {$b=1;}
                if($b==0){
                ?>

                <tr style="color: #2e383c" >
                    <td ><?php echo $case_tree[$j]['user1']?></td>
                    <td><?php if ($case_tree[$j]['assistant']==0){echo 'SUPERVISOR';}
                        if ($case_tree[$j]['assistant']==1){echo 'ASSISTANT';} ?></td>
                    <td><?=$case_tree[$j]['date_appoitment']?></td>
                    <td><?=$case_tree[$j]['patient']?></td>
                    <td><?=$case_tree[$j]['discipline']?></td>
                    <td><?php echo floor(abs(time() - strtotime($case_tree[$j]['date_appoitment']))/(60*60*24)) ;  ?></td>
                    <td><b>NOTE Missing Therapist Signature</b></td>

                </tr>


            <?php } }}?>

        <?php if($co4>0){
            for($j=0;$j<$co4;$j++){

                ?>
                <tr style="color: #2e383c" >
                    <td ><?php echo $case_four[$j]['user1']?></td>
                    <td><?php if ($case_four[$j]['assistant']==0){echo 'SUPERVISOR';}
                        if ($case_four[$j]['assistant']==1){echo 'ASSISTANT';} ?></td>
                    <td><?=$case_four[$j]['date_appoitment']?></td>
                    <td><?=$case_four[$j]['patient']?></td>
                    <td><?=$case_four[$j]['discipline']?></td>
                    <td><?php echo floor(abs(time() - strtotime($case_four[$j]['date_appoitment']))/(60*60*24)) ;  ?></td>
                    <td><b>NOTE Missing SUPERVISOR Signature</b></td>

                </tr>


            <?php } }?>
    </table>


</div>

    <div id="segundo">
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="center"><h3><font color="#BDBDBD">REPORTE 2</font></h3>   </div>

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
</table>
    </div>
    <div id="tercero">
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="center"><h3><font color="#BDBDBD">REPORTE 3</font></h3>   </div>

        </div>
        <hr>
        <table class="table table-striped" id="tercer_reporte" style="text-align: center">
            <tr class="active">
                <td><b>Patient Name</b></td>
                <td><b>Appoitment Date</b></td>
                <td><b>Case</b></td>

            </tr>


            <?php if($contador_case_rtree_one>0){
                for($j=0;$j<$contador_case_rtree_one;$j++){
                    ?>
                    <tr >
                        <td><?=$case_rtree_one[$j]['pat_name3'];?></td>
                        <td><?=$case_rtree_one[$j]['date_appoitment']?></td>
                        <td><b>Note No visit CREATED</b></td>

                    </tr>


                <?php }  }?>

            <?php if($num_notes>0){
                for($j=0;$j<$num_notes;$j++){
                    ?>
                    <tr >
                        <td><?=$case_rtree_two[$j]['pat_name3'] ;?><b>(<?php echo $case_rtree_two_count[$j]['contador']?>)</b></td>
                        <td><?=$case_rtree_two[$j]['date_appoitment']?></td>
                        <td><b>Note without Signature</b></td>

                    </tr>


                <?php }  }?>
</table>
    </div>

    </head>
</html>
