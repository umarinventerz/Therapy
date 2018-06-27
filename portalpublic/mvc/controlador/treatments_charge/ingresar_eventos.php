<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}


  #  $descrptions = $_POST['descriptions'];
    
  #      $conexion = conectar();
   #     $insert = "INSERT into tbl_event (date_start,date_end,descripcion,descrptions) values ('".$inicio."','".$fin."','".$descripcion."','".$descrptions."');";
    #    ejecutar($insert,$conexion);

     #       $json_resultado['resultado'] = 'creado';

      #      echo json_encode($json_resultado);

if(isset($_POST['action']) ){

    if ($_POST['action']=='insert')
    {


        $id = $_POST['id_treatment'];
      #  $descrptions = $_POST['descriptions'];




     #   $conexion = conectar();

  #      $update = " UPDATE tbl_event SET "
   #         . "descripcion = '" . $title . "'"
    #        . ",descrptions = '" . $descrptions . "'"
     #       . " WHERE id_event = " . $id . ";";

        $conexion = conectar();
        $consulta = "SELECT * FROM tbl_treatments WHERE id_treatments=".$id;
        $info_pat_id = ejecutar($consulta,$conexion);
        while ($row=mysqli_fetch_array($info_pat_id)) {
            $salida['id_treatments'] = $row['id_treatments'];
            $salida['locations']=$row['campo_2'];
            $salida['pat_id']=$row['campo_5'];
            $salida['pat_name']=$row['campo_6'];
            $salida['insurramce']=$row['campo_7'];
            $salida['terapista_name']=$row['campo_9'];
            $salida['discipline']=$row['campo_10'];
            $salida['durations']=$row['campo_15'];
            $salida['unit']=$row['campo_17'];
            $salida['diagnostic']=$row['campo_16'];
            $salida['cpt']=$row['campo_11'];
            $salida['dos']=$row['campo_1'];

        }

        $consulta1 = "SELECT * FROM patients WHERE Pat_id=".$salida['pat_id'];
        $info_patients = ejecutar($consulta1,$conexion);
        while ($row=mysqli_fetch_array($info_patients)) {
            $patients['Last_name'] = $row['Last_name'];
            $patients['DOB'] = $row['DOB'];
            #$patients['employee_name']=$row['employee_name'];

        }



        #$consulta2 = "SELECT * FROM seguros WHERE insurance=".$salida['insurramce'];
        #$insurrance = ejecutar($consulta2,$conexion);
       # while ($row=mysqli_fetch_array($insurrance)) {
        #    $insurrance_info['insurance'] = $row['insurance'];
        #$insurrance_info['id'] = $row['ID'];
        #$insurrance_info['phone'] = $row['phone'];
        #}

     #   $consulta3 = "SELECT * FROM cpt WHERE  cpt=".$salida['pat_id'];
     #   $info_cpt = ejecutar($consulta3,$conexion);
     #   while ($row=mysqli_fetch_array($info_cpt)) {
     #       $cpt['id'] = $row['ID'];

     #   }
    #    $consulta4 = "SELECT * FROM insurance_prices WHERE IdInsurance= '".$insurrance_info['id']."' AND IdCPT=".$cpt['id'];
    #    $info_insurrance = ejecutar($consulta4,$conexion);
    #    while ($row=mysqli_fetch_array($info_insurrance)) {
    #        $insurrance['Rate_Unit'] = $row['Rate_Unit'];
    #        $insurrance['Allowed'] = $row['Allowed'];
     #       $insurrance['Encounter'] = $row['Encounter'];

     #   }






#########parte de insert
$invoice='3124';
$mod='algo';
$write_off='$0.00';
$pat_respo='$0.00';
$ins_respon='$0.00';
$pat_paid='$0';
$ins_paid='$0';
$pat_balance='$0';
$ins_balance='$0';


$charge='$'.$insurrance['Rate_Unit']*$salida['unit'];

        $balance=$charge;
        $status='1';
        $status_paid='1';

        $insert = "INSERT into tbl_treatments_charges (last_name,pat_id,DOB,treatment_id,DOS,invoice_number,patient_name,pra,insurance_name,phone,patient_id,discipline,cpt_code,mod,units,rate_unit,charge,write_off,pat_respon,ins_respon,pat_paid,ins_paid,pat_balance,ins_balance,balance,status,status_paid) values ('".$patients['Last_name']."','".$salida['pat_id']."','".$patients['DOB']."','".$salida['id_treatments']."','".$salida['dos']."','".$invoice."','".$salida['pat_name']."','".$salida['terapista_name']."','".$salida['insurramce']."','".$insurrance_info['phone']."','".$salida['pat_id']."','".$salida['discipline']."','".$salida['cpt']."','".$mod."','".$salida['unit']."','".$insurrance['Rate_Unit']."','".$charge."','".$write_off."','".$pat_respo."','".$ins_respon."','".$pat_paid."','".$ins_paid."','".$pat_balance."','".$ins_balance."','".$balance."','".$status."','".$status_paid."');";
        ejecutar($insert,$conexion);





#parte de actualizar el tbl_treatment que se le va hacer el  billing , y poner en el campo cuetro el id del billing que le corresponde

        $max_id_treatmant = "SELECT MAX(id_treatments_charges) AS id_treat_charges FROM tbl_treatments_charges";
        $result_note1 = mysqli_query($conexion, $max_id_treatmant);
        $id_tbl_treatments = 0;
        while($row = mysqli_fetch_array($result_note1,MYSQLI_ASSOC)){
            $id_tbl_treatments_charges = $row['id_treat_charges'];
        }













       $json_resultado['resultado'] = 'creado';

        echo json_encode($json_resultado);
    }





}


	