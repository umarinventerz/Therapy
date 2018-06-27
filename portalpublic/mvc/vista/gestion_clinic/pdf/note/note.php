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
$conexion = conectar();
//#$id=8;
//#$conexion = conectar();
$sql_notas="SELECT N.*,concat(P.Last_name,', ',P.First_name) as patient,P.Pat_id,P.DOB,E.npi_number as NPI ,C.*,concat(E.last_name,', ',E.first_name) as employee,
            DI.Name as disciplina,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation, CA.Discipline as discipline_poc,CA.Company as company_poc,
            CA.diagnostic as diagnostic_poc,CA.id_careplans,  V.visit_date,
            CONCAT(P.PCP, ' , NPI ', p.PCP_NPI )    as ppcp ,
            CONCAT(p.dob , ' / ', TIMESTAMPDIFF( YEAR, p.dob, now() ) ,' Yrs.,  '
                  , TIMESTAMPDIFF( MONTH, p.dob, now()) % 12,' Months, '
                  , FLOOR( TIMESTAMPDIFF( DAY, p.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob
                  , e.signature as signature

            FROM tbl_notes_documentation N
            LEFT JOIN tbl_visits V ON(N.visit_id=V.id)
            LEFT JOIN careplans CA ON(N.id_careplans=CA.id_careplans)
            LEFT JOIN patients P ON(N.patient_id=P.id)
            LEFT JOIN companies C ON(CA.Company=C.id) LEFT JOIN diagnosiscodes D ON(CA.diagnostic=D.DiagCodeId)
            LEFT JOIN discipline DI ON(N.discipline_id=DI.DisciplineId)
            LEFT JOIN user_system U ON(U.user_id=N.user_id)
            LEFT JOIN employee E ON(E.id=N.user_id)
        WHERE N.id_notes='".$id."'";

$resultados_note = ejecutar($sql_notas,$conexion);
while($row_note = mysqli_fetch_assoc($resultados_note)) {

    $note[]=$row_note;

}
 $sql_nota2 = "SELECT e.assistant as assistant , e.id as user1, e.supervisor as user2 FROM tbl_notes_documentation n
                                            LEFT JOIN employee e on e.id=n.user_id
                                            where n.id_notes='".$id."'
                                                ";
$resultados_note2 = ejecutar($sql_nota2,$conexion);
while($row_note2 = mysqli_fetch_assoc($resultados_note2)) {

    $therapist_type['assistant']=$row_note2['assistant'];
    $therapist_type['user1']=$row_note2['user1'];
    $therapist_type['user2']=$row_note2['user2'];
}
#$id=23;
#$note[0]=array ("NPI"=>'NPI',"patient"=>'patient',"onotes"=>'onotes',"pnotes"=>'pnotes',"anotes"=>'anotes',"snotes"=>'snote',"duration"=>'duration',"company_name"=>'comapni',"visit_date"=>'visit_date',"therapist_signed"=>'0',"facility_address"=>'facility adre',"date_signed"=>'date_signed',"Pat_id"=>'Pat_id',"name"=>'name',"facility_city"=>'facil city',"facility_state"=>'faci state',"facility_zip"=>'facol zip',"facility_phone"=>'facil phone',"facility_fax"=>'faci fax',"Pat_id"=>'pat id',"from"=>'from',"to"=>'to',"disciplina"=>'disciplina',"diagnostic_relation"=>'diagnostic_relation',"employee"=>'employee',"created"=>'created',"cpt_relation"=>'cpt_relation',"units"=>'units',"cpt_relationunits"=>'cpt_relationunits',"minutes"=>'minutes',"ppcp"=>'ppcp',"pdob"=>'pdob',"status_id"=>1,"ckeditor"=>'dqq');
#echo $note1[0]['patient'];
?>

<style>



    #primeros {width: 100%}
    #primeros .fila #col_1 {text-align:left;width: 33%}
    #primeros .fila #col_2 {text-align:center;width: 33%}
    #primeros .fila #col_3 {text-align:left;width: 33%}

    #primer_bloque {width: 100% }
    #primer_bloque .fila #col_1 {text-align:left;width: 33%}
    #primer_bloque .fila #col_2 {text-align:left;width: 33%}
    #primer_bloque .fila #col_3 {text-align:center;width: 33%}

    #segundo {width: 60%}
    #segundo .fila1 #col_11 {text-align:left;width: 55%}
    #segundo .fila1 #col_21 {text-align:left;width: 55%}
    #segundo .fila1 #col_31 {text-align:left;width: 55%}
    #segundo .fila1 #col_41 {text-align:center;width: 55%}
    #segundo .fila1 #col_51 {text-align:left;}
    #segundo .fila1 #col_61 {text-align:center;width: 55%}
    #segundo .fila1 #col_71 {text-align:left;}

    #central {margin-top:20px; width:100%;}
    #central tr td {padding: 10px; text-align: justify; width:100%;}
    #central .filasing #col_central {text-align:center;width: 90%}

</style>

<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 11pt">
<page_header>

    <div class="panel" style="margin-bottom: 0px;">

        <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">

            <table id="primeros">
                <tr class="fila">
                    <td id="col_1"><img  src="../../../../images/LOGO_1.png" style="width: 60%" ></td>
                    <td id="col_2"><h2>NOTE </h2></td>
                    <td id="col_3" align="left"><h4> <?=$note[0]['company_name'];?></h4></td>
                </tr>

            </table>

        </div>
    </div>

</page_header>


<table id="primer_bloque">
    <tr class="fila">
        <td id="col_1" >
            <p><b>Patients: </b><?php echo $note[0]['patient']."<b> ( ".$note[0]['Pat_id'].")</b>"?><br></p>
            <p><b>Visit Date:</b><?php echo $note[0]['visit_date']?><br></p>
            <p><b>Therapist:</b><?php echo $note[0]['employee']." ".$note[0]['NPI']?></p>
        </td>
        <td id="col_2">
            <p><b>DOB/Age: </b><?php echo $note[0]['pdob']?><br></p>
            <p><b>Duration:</b><?php
            if($note[0]['duration']==15){
                echo "15";
            }
            if($note[0]['duration']==30){
                echo "30";
            }
            if($note[0]['duration']==45){
                echo "45";
            }
            if($note[0]['duration']==60){
                echo "60";
            }
            ?><br></p>
            <p><b>Discipline:</b><?php echo $note[0]['disciplina']?></p>

        </td>
         <td id="col_3">
            <p ><b><?=$note[0]['company_name']; "/n"?></b><br>
                 <?=$note[0]['facility_address']?><br>
               <?=$note[0]['facility_city'].",".$note[0]['facility_state'].",".$note[0]['facility_zip']?><br>
                (P)<?=$note[0]['facility_phone']?><br>
                               (F)<?=$note[0]['facility_fax']?></p>
        </td>
    </tr>


</table>

<br>
<hr>
<div class="row" style="margin-top: 15px;">
    <p align="center"> <b >CPT</b></p>
    <table class="table" id="add_cpt" border="1" align="center" style="text-align: center">
        <tr class="success" bgcolor="gray" >
            <td><b>Cpt</b></td>
            <td><b>Units</b></td>
            <td><b>Location</b></td>
            <td><b>Start</b></td>
            <td><b>End</b></td>
            <td><b>Duration</b></td>
            <td><b>Diagnosis</b></td>
        </tr>
        <?php

        $cpt_consult  = "Select N.*,N.start as start_date,N.end as end_date,C.cpt,L.name as location,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnosis from tbl_note_cpt_relation N left join cpt C ON(N.id_cpt=C.ID)"
            . "     LEFT JOIN tbl_location_appoiments L ON(N.location=L.id) LEFT JOIN diagnosiscodes D ON(N.id_diagnosis=D.DiagCodeId) WHERE N.id_note=".$id;

        $resultado_cpt = ejecutar($cpt_consult,$conexion);

        while ($row_cpt=mysqli_fetch_array($resultado_cpt))
        {
            $cpt_result[]=$row_cpt;
        }
        #  $cpt_result[0]=array("cpt"=>'cpt1',"units"=>'unit1',"location"=>'location1',"start_date"=>'start_date1',"end_date"=>'endate1',"duration"=>'duration1',"diagnosis"=>'diagnosis1');
        # $cpt_result[1]=array("cpt"=>'cpt1',"units"=>'unit1',"location"=>'location1',"start_date"=>'start_date1',"end_date"=>'endate1',"duration"=>'duration1',"diagnosis"=>'diagnosis1');
        for($j=0;$j<count($cpt_result);$j++){

            ?>
            <tr class="info" style="text-align: center">
                <td><?=$cpt_result[$j]['cpt']?></td>
                <td><?=$cpt_result[$j]['units']?></td>
                <td><?=$cpt_result[$j]['location']?></td>
                <td><?=$cpt_result[$j]['start_date']?></td>
                <td><?=$cpt_result[$j]['end_date']?></td>
                <td><?=$cpt_result[$j]['duration']?></td>
                <td><?=$cpt_result[$j]['diagnosis']?></td>
            </tr>
        <?php } ?>


    </table>

</div>
<hr>

    <table id="segundo">

        <tr class="fila1">
            <td id="col_1"><p><b>Subject:</b></p></td>
        </tr>
        <tr class="fila">
            <td id="col_1"><p>

                    <?php for($j=0;$j<=(strlen($note[0]['snotes']));$j=$j+90) { ?>
                        <?php $i=$j+22;

                        $line=substr($note[0]['snotes'],$j,90);
                        echo $line."<br>";

                        if ($j>(strlen($note[0]['snotes']))){
                            break;
                        }
                        ?>

                    <?php } ?>
                </p></td>
        </tr>
        <tr class="fila1">
            <td id="col_1"><p><b>Objective:</b></p></td>
        </tr>
        <tr class="fila">
            <td id="col_1"><p><?php for($j=0;$j<=(strlen($note[0]['onotes']));$j=$j+90) { ?>
                        <?php $i = $j + 22;

                        $line = substr($note[0]['onotes'], $j, 90);
                        echo $line . "<br>";

                        if ($j > (strlen($note[0]['onotes']))) {
                            break;
                        }
                    }?></p></td>
        </tr>
        <tr class="fila1">
            <td id="col_1"><p><b>Assessment:</b></p></td>
        </tr>
        <tr class="fila">
            <td id="col_1"><p><?php for($j=0;$j<=(strlen($note[0]['anotes']));$j=$j+90) { ?>
                        <?php $i = $j + 22;

                        $line = substr($note[0]['anotes'], $j, 90);
                        echo $line . "<br>";

                        if ($j > (strlen($note[0]['anotes']))) {
                            break;
                        }
                    }?></p></td>
        </tr>
        <tr class="fila1">
            <td id="col_1"><p><b>Plan:</b></p></td>
        </tr>
        <tr class="fila">
            <td id="col_1"><p>
                    <?php for($j=0;$j<=(strlen($note[0]['anotes']));$j=$j+90) { ?>
                        <?php $i = $j + 22;

                        $line = substr($note[0]['anotes'], $j, 90);
                        echo $line . "<br>";

                        if ($j > (strlen($note[0]['anotes']))) {
                            break;
                        }
                    }?></p></td>
        </tr>

    </table>

    <hr>

<?php
//consulto si tiene la firma agregada para supervisor
$firma="SELECT count(*) as contador,therapist_signature   from tbl_signature_note
                                 WHERE id_note='".$id."'

                                ";
$resultado_firma = ejecutar($firma,$conexion);
while ($row_firma=mysqli_fetch_array($resultado_firma))
{
    $valor_firma['contador']=$row_firma['contador'];
    $valor_firma['therapist_signature']=$row_firma['therapist_signature'];
}

// firma del supervisor
$firma2="SELECT count(*) as contador,therapist_signature as super  from tbl_signature_note
                                WHERE id_note='".$id."'
                                AND actor='SUPERVISOR'
                                ";
$resultado_firma2 = ejecutar($firma2,$conexion);
while ($row_firma2=mysqli_fetch_array($resultado_firma2))
{
    // $valor_firma['contador']=$row_firma['contador'];
    $valor_firma2['super']=$row_firma2['super'];
}

//consulto el supervisor del terapista
$sql_supervisor="SELECT supervisor from employee WHERE id='".$note[0]['user_id']."' ";
$resultado_supervisor = ejecutar($sql_supervisor,$conexion);
while ($row_supervisor=mysqli_fetch_array($resultado_supervisor))
{
    $valor_supervisor['supervisor']=$row_supervisor['supervisor'];

}
//consulto la firma del supervisor del terapista
$sql_supervisor_firma="SELECT signature from employee WHERE id='".$valor_supervisor['supervisor']."' ";
$resultado_supervisor_firma = ejecutar($sql_supervisor_firma,$conexion);

while ($row_supervisor_firma=mysqli_fetch_array($resultado_supervisor_firma))
{
    $valor_supervisor_firma['signature']=$row_supervisor_firma['signature'];

}

?>

<table id="segundo">
    <?php if($therapist_type['assistant']==1){    ?>
        <tr  class="fila">
            <td id="col_11"> <p><b>Supervisor:</b></p>  </td>
            <td id="col_21">
                <?php    if($valor_firma['contador']>1){?>
                    <img src="../../../../images/sign/<?=$valor_supervisor_firma['signature']?>" style="width: 100px">
                <?php } ?>
            </td>
        </tr>
    <?php }?>





    <?php
    //consulto si tiene la firma agregada para terapista
    $firma_terapista="SELECT count(*) as contador,therapist_signature from tbl_signature_note WHERE id_note='".$id."'
                                AND actor='THERAPIST'";

    $resultado_firma_terapista = ejecutar($firma_terapista,$conexion);
    while ($row_firma_terapista=mysqli_fetch_array($resultado_firma_terapista))
    {
        $valor_firma_terapista['contador']=$row_firma_terapista['contador'];
        $valor_firma_terapista['therapist_signature']=$row_firma_terapista['therapist_signature'];
    }
    ?>
    <tr class="fila"></tr>
    <tr class="fila">
        <td id="col_1"><p><b>Therapist:</b></p></td>
        <td id="col_2">
            <?php
            if($valor_firma_terapista['contador']>0){?>
                <img src="../../../../images/sign/<?=$note[0]['signature']?>" style="width: 100px">
            <?php } ?>
        </td>
    </tr>

</table>
</page>





