<?php
session_start();
require_once '../../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="home.php";</script>';
    }
}
#$id=1376;
$conexion = conectar();
$sql_poc="SELECT CA.*,C.* FROM careplans CA LEFT JOIN companies C ON(CA.Company=C.id)  WHERE CA.id_careplans=".$id;
        $resultados_poc = ejecutar($sql_poc,$conexion);
        while($row_poc = mysqli_fetch_assoc($resultados_poc)) {

            $data_poc[]=$row_poc;

        }
        $evaluacion_id=$data_poc[0]['evaluations_id'];
        
$appoiment="SELECT E.*,concat(P.Last_name,', ',P.First_name) as patient,P.Pat_id,P.DOB,concat(P.PCP,', ',P.PCP_NPI) AS physician,C.*,concat(U.Last_name,', ',U.First_name) as employee,CP.cpt as cpt_relation,
            DI.Name as disciplina,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation,
            CONCAT(P.PCP, ' , NPI ', p.PCP_NPI )    as ppcp ,
            CONCAT(p.dob , ' / ', TIMESTAMPDIFF( YEAR, p.dob, now() ) ,' Yrs.,  '
                  , TIMESTAMPDIFF( MONTH, p.dob, now()) % 12,' Months, '
                  , FLOOR( TIMESTAMPDIFF( DAY, p.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob

            FROM tbl_evaluations E
            LEFT JOIN patients P ON(E.patient_id=P.id)
            LEFT JOIN cpt CP ON(E.cpt=CP.ID)
            LEFT JOIN companies C ON(E.company=C.id) LEFT JOIN diagnosiscodes D ON(E.diagnostic=D.DiagCodeId)
            LEFT JOIN discipline DI ON(E.discipline_id=DI.DisciplineId)
            LEFT JOIN user_system U ON(U.user_id=id_user)
        WHERE E.id='".$evaluacion_id."' ";

$resultados = ejecutar($appoiment,$conexion);
while($row = mysqli_fetch_assoc($resultados)) {

    $data[]=$row;

}
?>
<style>



    #primeros {width: 90%}
    #primeros .fila #col_1 {text-align:left;width: 30%}
    #primeros .fila #col_2 {text-align:center;width: 30%}
    #primeros .fila #col_3 {text-align:center;width: 60%}

    #segundo {width: 80%}
    #segundo .fila1 #col_11 {text-align:left;width: 50%}
    #segundo .fila1 #col_21 {text-align: left;width: 5%}
    #segundo .fila1 #col_31 {text-align:left;width: 50%}
    #segundo .fila1 #col_41 {text-align:center;width: 50%}
    #segundo .fila1 #col_51 {text-align:left;}
    #segundo .fila1 #col_61 {text-align:center;width: 50%}
    #segundo .fila1 #col_71 {text-align:left;}

    #central {margin-top:20px; width:10%;}
    #central tr td {padding: 10px; text-align: justify; width:50%;}
    #central .filasing #col_central {text-align:center;width: 50%}

</style>
<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 11pt">
    <page_header>

        <div class="panel" style="margin-bottom: 0px;">

            <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">

                <table id="primeros">
                    <tr class="fila">
                        <td id="col_1"><img  src="../../../../images/LOGO_1.png" style="width: 60%" ></td>
                        <td id="col_2"><h2>POC #<?=$id?></h2></td>

                    </tr>

                </table>

            </div>
        </div>

    </page_header>


    <br><br>

    <table id="primeros" >
        <tr class="fila">
            <td id="col_1" >
                <p><b> Patients: </b><?php echo $data[0]['patient']." <b>(".$data[0]['Pat_id'].")</b>"?></p>
            </td>
            <td id="col_2">
                <p><b> Created: </b><?php echo $data[0]['pdob']?></p></td>
            <td id="col_3">
                <p> <b> Companie: </b> <?php echo $data[0]['company_name'];?></p>
            </td>

        </tr>
<tr class="fila">
    <td id="col_1">
        <p><b>From:</b><?=$data_poc[0]['POC_due']?></p>
    </td>
    <td id="col_2">
        <p><b>To:</b><?=$data_poc[0]['Re_Eval_due']?></p>
    </td>
<td id="col_3"><p>
        <?=$data_poc[0]['facility_address']?><br>
        <?=$data_poc[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
        (P) <?=$data_poc[0]['facility_phone']?><br>
        (F) <?=$data_poc[0]['facility_fax']?><br></p></td>

</tr>
        <tr class="fila">
            <td id="col_1"><p><b>Therapist:</b><?=$data[0]['employee']?></p></td>
            <td id="col_2"><p><b>Diag.:</b><?=$data[0]['diagnostic_relation']?> </p></td>
        </tr>

        <tr class="fila">

            <td id="col_1"><p><b>DOB:</b><?php echo $data[0]['DOB']?></p></td>
            <td id="col_2"><p><b>Age:</b><?php echo $data[0]['pdob']?></p></td>
        </tr>

        <tr class="fila">

            <td id="col_1"><p><b>Physician:</b><?php echo $data[0]['physician']?></p></td>

        </tr>

    </table>
<br>
    <hr>
    <label><b>Ckeditor:</b> <br></label>
    <table id="segundo">
        <tr class="fila1">
<td >

                <?php for($j=0;$j<=(strlen($data_poc[0]['ckeditor']));$j=$j+90) { ?>
                    <?php $i=$j+22;

                    $line=substr($data_poc[0]['ckeditor'],$j,90);
                    echo $line."<br>";

                    if ($j>(strlen($data_poc[0]['ckeditor']))){
                        break;
                    }
?>

                <?php } ?>


</td>
   </tr>

        <br>
            <table id="central">
                <tr class="filasing">
                    <td id="col_central">
                    <?php
                    if($data_poc[0]['therapist_signed']==1){?>
                        <img src="../../../../images/sign/<?=$data_poc[0]['therapist_signature']?>" style="width: 100px">
                  <?php } ?>
                    </td>
                </tr>
                    <tr class="filasing">
                    <td id=col_central>
                        <p><b>Therapist Signature</b></p>
                    </td>
                </tr>
            </table>
    </table>
</page>