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
#$id=1058;
$conexion = conectar();
$sql="SELECT P.*,D.Name,concat(DI.DiagCodeValue,', ',DI.DiagCodeDescrip) as diagnostic_relation
    FROM prescription P LEFT JOIN discipline D on(P.Discipline=D.DisciplineId)
    LEFT JOIN diagnosiscodes DI ON (P.Diagnostic=DI.DiagCodeId)
    WHERE P.id_prescription='".$id."' ";
$resultados = ejecutar($sql,$conexion);
while($row = mysqli_fetch_assoc($resultados)) {

    $data[]=$row;

}
?>
<style>



    #primeros {width: 90%}
    #primeros .fila #col_1 {text-align:left;width: 30%}
    #primeros .fila #col_2 {text-align:center;width: 30%}
    #primeros .fila #col_3 {text-align:center;width: 60%}

    #segundo {width: 60%}
    #segundo .fila1 #col_11 {text-align:left;width: 55%}
    #segundo .fila1 #col_21 {text-align: left;width: 55%}
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
                        <td id="col_2"><b>Prescription #<?=$id?></b></td>

                    </tr>

                </table>

            </div>
        </div>
<br><br>
    </page_header>


    <table id="primeros">
        <tr class="fila">
            <td id="col_12"><p><b>Patients:</b><?=$data[0]['Patient_name']?></p></td>
          </tr>
           <tr class="fila">
            <td id="col_12"><p><b>Discipline:</b><?=$data[0]['Name']?></p></td>
        </tr>
        <tr class="fila">
    <td id="col_12"><p><b>Company:</b><?=$data[0]['Table_name']?></p></td>
        </tr>
        <tr class="fila">
            <td id="col_12"><p><b>Diagnostic:</b><?=$data[0]['diagnostic_relation']?></p></td>
        </tr>
        <tr class="fila">
            <td id="col_12"><p><b>From:</b><?=$data[0]['Issue_date']?></p></td>
        </tr>

        <tr class="fila">
            <td id="col_12"><p><b>From:</b><?=$data[0]['Issue_date']?></p></td>

        </tr>
        <tr class="fila">
            <td id="col_12"><p><b>To:</b> <?=$data[0]['End_date']?></p></td>
        </tr>
        <tr class="fila">
            <td id="col_12"><p><b>Physician Name:</b> <?=$data[0]['Physician_name']?></p></td>
        </tr>


    </table>





    

</page>