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
#$id=25;
$appoiment="SELECT E.*,concat(P.Last_name,', ',P.First_name) as patient,P.Pat_id,P.DOB,concat(P.PCP,', ',P.PCP_NPI) AS physician,C.*,concat(U.Last_name,', ',U.First_name) as employee,CP.cpt as cpt_relation,
            DI.Name as disciplina,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation,
            CONCAT(P.PCP, ' , NPI ', p.PCP_NPI )    as ppcp ,
            CONCAT(p.dob , ' / ', TIMESTAMPDIFF( YEAR, p.dob, now() ) ,' Yrs.,  '
                  , TIMESTAMPDIFF( MONTH, p.dob, now()) % 12,' Months, '
                  , FLOOR( TIMESTAMPDIFF( DAY, p.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob
                  , em.npi_number npi_number

            FROM tbl_evaluations E
            LEFT JOIN employee em on em.id=E.id_user 
            LEFT JOIN patients P ON(E.patient_id=P.id)
            LEFT JOIN cpt CP ON(E.cpt=CP.ID)
            LEFT JOIN companies C ON(E.company=C.id) LEFT JOIN diagnosiscodes D ON(E.diagnostic=D.DiagCodeId)
            LEFT JOIN discipline DI ON(E.discipline_id=DI.DisciplineId)
            LEFT JOIN user_system U ON(U.user_id=id_user)
        WHERE E.id='".$id."' ";

$resultados = ejecutar($appoiment,$conexion);
while($row = mysqli_fetch_assoc($resultados)) {

    $data[]=$row;

}

#$npi='npi';
#$data[0]=array ("company_name"=>'comapni',"therapist_signed"=>'0',"facility_address"=>'facility adre',"date_signed"=>'date_signed',"Pat_id"=>'Pat_id',"name"=>'name',"facility_city"=>'facil city',"facility_state"=>'faci state',"facility_zip"=>'facol zip',"facility_phone"=>'facil phone',"facility_fax"=>'faci fax',"Pat_id"=>'pat id',"from"=>'from',"to"=>'to',"disciplina"=>'disciplina',"diagnostic_relation"=>'diagnostic_relation',"employee"=>'employee',"created"=>'created',"cpt_relation"=>'cpt_relation',"units"=>'units',"cpt_relationunits"=>'cpt_relationunits',"minutes"=>'minutes',"ppcp"=>'ppcp',"pdob"=>'pdob',"status_id"=>1,"ckeditor"=>'Es es un texto fijo que puede ir directamente dentro de la vista.');
?>
<style>



#primeros {width: 80%}
#primeros .fila #col_1 {text-align:left;width: 40%}
#primeros .fila #col_2 {text-align:center;width: 30%}
#primeros .fila #col_3 {text-align:center;width: 50%}

#segundo {width: 80%}
#segundo .fila1 #col_11 {text-align:left;width: 50%}
#segundo .fila1 #col_21 {text-align: left;width: 50%}
#segundo .fila1 #col_31 {text-align:left;width: 50%}
#segundo .fila1 #col_41 {text-align:center;width: 50%}
#segundo .fila1 #col_51 {text-align:left;}
#segundo .fila1 #col_61 {text-align:center;width: 50%}
#segundo .fila1 #col_71 {text-align:left;}

#central {margin-top:20px; width:100%;}
#central tr td {padding: 10px; text-align: justify; width:100%;}
#central .filasing #col_central {text-align:center;width: 90%}

#editor {margin-top:20px; width:80%;}
#editor tr td {padding: 10px; text-align: justify; width:80%;}
</style>

<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 11pt">


<page_header>

    <div class="panel" style="margin-bottom: 0px;">

        <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">

            <table id="primeros">
                                <tr class="fila">
                    <td id="col_1"><img  src="../../../../images/LOGO_1.png" style="width: 60%" ></td>
                <td id="col_2"><P><b>Evaluation #<?=$id?></b></P></td>

                </tr>

            </table>

        </div>
        </div>

</page_header>


<table id="primeros" >
    <tr class="fila">
        <td id="col_1" >
            <p><b> Patients: </b><?php echo $data[0]['name'];?></p>
        </td>
        <td id="col_2">
            <p><b> Pat_ID: </b><?php echo $data[0]['Pat_id'];?></p></td>
        <td id="col_3">
            <p> <b> Companie: </b> <?php echo $data[0]['company_name'];?></p>
        </td>

    </tr>


</table>






        <hr>

<table id="segundo">
<tr class="fila1">
 <td id="col_11">
    <p><b>Date From:</b>  <?php echo $data[0]['from']?>  </p>
 </td>

 <td id="col_21">

     <p><b>Eval due:</b><?=$data[0]['to']?></p>
 </td>
    <td id="col_31">
        <p><?=$data[0]['facility_address']?><br>
            <?=$data[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
            (P) <?=$data[0]['facility_phone']?><br>
            (F) <?=$data[0]['facility_fax']?><br></p>
    </td>
</tr>

    <tr class="fila1">

<td id="col_11">
    <p> <b>Discipline:  </b><?php echo   $data[0]['disciplina']?> </p>
</td>
        <td id="col_21">
            <p><b>Diag:  </b><?=   $data[0]['diagnostic_relation']?> </p>
        </td>

    </tr>

    <tr class="fila1">
        <td id="col_11"><p><b>Therapist:  </b><?php echo $data[0]['employee']?></p></td>

        <td id="col_21"><p><b>NPI:  </b> <?=$data[0]['npi_number']?></p> </td>
    </tr>

        <tr class="fila1">
            <td id="col_11"><p><b>Date of Eval:  </b><?php echo $data[0]['created']?></p></td>
            <td id="col_21"><p><b>Cpt:  </b><?=$data[0]['cpt_relation']?> </p></td>
        </tr>

<tr class="fila1">
    <td id="col_11"><p><b>Units:  </b><?=$data[0]['units']?></p></td>
    <td id="col_21"><p><b>Minutes:  </b><?=$data[0]['minutes']?></p></td>

</tr>
    <tr class="fila1">
        <td id="col_11"><p><b>Phy. Referral:  </b><?php echo $data[0]['ppcp']?></p></td>
        <td id="col_21"><p><b>DOB/Age:</b><?php echo $data[0]['pdob']?></p></td>
</tr>

</table>
    <br> <hr>
    <label><b>Ckeditor:</b> <br></label>
    <table id="segundo">
        <tr class="fila1">
            <td><?=$data[0]['ckeditor']?><br>
            </td>
        </tr>        <br>
        <table id="central">
            <tr class="filasing">

                <td id="col_central">
                    <?php
                    if($data[0]['therapist_signed']==1){?>
                        <img src="../../../../images/sign/<?=$data[0]['therapist_signature']?>" style="width: 100px">
                    
                    <?php } ?></td>


            </tr>
            <tr class="filasing">
                <td id="col_central"><p><b> Therapist Signature</b></p></td>
            </tr>
        </table>


        <table id="segundo">
            <tr class="fila1">
                <td id="col_1">
                    <p><b>MD Signed:</b></p>

                </td>

            </tr>
            <tr class="fila1">
                <td id="col_1"><p><b>Date of Signed:  </b><?=$data[0]['date_signed']?></p></td>
            </tr>
        </table>



    </table>

</page>
