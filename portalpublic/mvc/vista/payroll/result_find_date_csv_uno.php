<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../home/home.php";</script>';
    }
}
$equidad=0;
$equidad1=0;
if(isset($_GET["input_date_start"]) && $_GET["input_date_start"] != null){  $equidad=2;} else { $campo_1 = 'true'; }
if(isset($_GET["input_date_end"]) && $_GET["input_date_end"] != null){  $equidad1=1;} else { $campo_90 = 'true'; }

$conexion = conectar();
if($equidad==2)
{
    $sql_1 = strtolower($_GET["input_date_start"]);


    $campo_1 = "STR_TO_DATE(date_start , '%Y-%m-%d') > STR_TO_DATE('".$sql_1."','%m/%d/%Y')";

}
if($equidad1==1)
{
    $sql_1 = strtolower($_GET["input_date_end"]);


    $campo_90 ="STR_TO_DATE(date_start , '%Y-%m-%d') <= STR_TO_DATE('".$sql_1."','%m/%d/%Y')";

}






$joins = '';

$where = ' WHERE '.$campo_1." and ".$campo_90.'';

$sql  = "SELECT * FROM tbl_event tbl_".$joins.$where;

$resultado = ejecutar($sql,$conexion);


$reporte = array();

$i = 0;
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte[$i] = $datos;
    $i++;
}


?>
<script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>

<script  language="JavaScript" type="text/javascript">

    $(document).ready(function() {
        $('#table_kidswork_therapy').dataTable( {

            "lengthMenu": [[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]],
            order: [[ 2, "desc" ]],
            "pageLength": 3
        } );
    } );



    function Modificar_treatments(id_treatments,campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,license_number,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,pay,adults_progress_notes,pedriatics_progress_notes){
        campo_1 = replaceAll(campo_1,' ','|');
        campo_2 = replaceAll(campo_2,' ','|');
        campo_3 = replaceAll(campo_3,' ','|');
        campo_4 = replaceAll(campo_4,' ','|');
        campo_5 = replaceAll(campo_5,' ','|');
        campo_6 = replaceAll(campo_6,' ','|');
        campo_7 = replaceAll(campo_7,' ','|');
        campo_8 = replaceAll(campo_8,' ','|');
        campo_9 = replaceAll(campo_9,' ','|');
        license_number = replaceAll(license_number,' ','|');
        campo_10 = replaceAll(campo_10,' ','|');
        campo_11 = replaceAll(campo_11,' ','|');
        campo_12 = replaceAll(campo_12,' ','|');
        campo_13 = replaceAll(campo_13,' ','|');
        campo_14 = replaceAll(campo_14,' ','|');
        campo_15 = replaceAll(campo_15,' ','|');
        campo_16 = replaceAll(campo_16,' ','|');
        campo_17 = replaceAll(campo_17,' ','|');
        campo_18 = replaceAll(campo_18,' ','|');
        campo_19 = replaceAll(campo_19,' ','|');
        campo_20 = replaceAll(campo_20,' ','|');
        campo_21 = replaceAll(campo_21,' ','|');
        campo_22 = replaceAll(campo_22,' ','|');
        campo_23 = replaceAll(campo_23,' ','|');
        campo_24 = replaceAll(campo_24,' ','|');
        pay = replaceAll(pay,' ','|');
        adults_progress_notes = replaceAll(adults_progress_notes,' ','|');
        pedriatics_progress_notes = replaceAll(pedriatics_progress_notes,' ','|');


        window.location.href = "../treatments/registrar_treatments.php?&id_treatments="+id_treatments+"&campo_1="+campo_1+"&campo_2="+campo_2+"&campo_3="+campo_3+"&campo_4="+campo_4+"&campo_5="+campo_5+"&campo_6="+campo_6+"&campo_7="+campo_7+"&campo_8="+campo_8+"&campo_9="+campo_9+"&license_number="+license_number+"&campo_10="+campo_10+"&campo_11="+campo_11+"&campo_12="+campo_12+"&campo_13="+campo_13+"&campo_14="+campo_14+"&campo_15="+campo_15+"&campo_16="+campo_16+"&campo_17="+campo_17+"&campo_18="+campo_18+"&campo_19="+campo_19+"&campo_20="+campo_20+"&campo_21="+campo_21+"&campo_22="+campo_22+"&campo_23="+campo_23+"&campo_24="+campo_24+"&pay="+pay+"&adults_progress_notes="+adults_progress_notes+"&pedriatics_progress_notes="+pedriatics_progress_notes;

    }


    function Eliminar_treatments(id_treatments,incrementador,accion){

        swal({
            title: "Confirmación",
            text: "Desea Eliminar el Registro N° "+id_treatments+"?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Eliminar",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function(isConfirm) {
            if (isConfirm === true) {

                var campos_formulario = '&id_treatments='+id_treatments+'&accion='+accion;

                $.post(
                    "../../controlador/treatments/gestionar_treatments.php",
                    campos_formulario,
                    function (resultado_controlador) {
                        //confirmacion_almacenamiento('Registro N° '+id_treatments+' Eliminado');
                        $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                        setTimeout(function(){
                            validar_formulario();
                        },1500);
                    },
                    "json"
                );

            }
        });


    }


    /*modulos_dependientes_j*/

    $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 1000);

</script>

<div id="bajar_aqui"></div>
<br>
<table align="center" border="0">
    <tr>
        <td align="center"><b><font size="3">Resultado de la Consulta</font></b></td>
    </tr>
</table>

<div class="col-lg-12">

    <table id="table_kidswork_therapy" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th class="col-lg-2">Subject</th>
            <th class="col-lg-2">Description</th>
            <th class="col-lg-2">Date</th>


        </tr>
        </thead>

        <tbody>
        <?php
        $i = 0;
        $color = '<tr class="odd_gradeX">';
        while (isset($reporte[$i])) {




            echo '<td align="center"><font size="2"><b>'.$reporte[$i]['descripcion'].'</b></font></td>';
            echo '<td align="center"><font size="2">'.$reporte[$i]['descrptions'].'</font></td>';
            echo '<td align="center"><font size="2">'.$reporte[$i]['date_start'].'</font></td>';



            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

            echo '</tr>';

            $i++;
        }
        ?>

        </tbody>
    </table>
</div>

<div class="spacer"></div>

</html>



