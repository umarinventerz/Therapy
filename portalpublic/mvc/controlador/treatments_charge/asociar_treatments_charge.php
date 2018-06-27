<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}

$user_id = $_SESSION['user_id'];

$conexion = conectar();
           
$date = date('Y-m-d');

$pat_paid = $_POST['pat_paid'];
$ins_paid = $_POST['ins_paid'];
$status = $_POST['status'];
$notes = $_POST['notes'];
$ids_treatments_charge = $_POST['ids_treatments_charge'];
$numero_referencia = $_POST['numero_referencia'];


$array_pat_paid = explode(',',$pat_paid);
$array_ins_paid = explode(',',$ins_paid);
$array_status = explode(',',$status);
$array_notes = explode(',',$notes);
$array_ids_treatments_charge = explode(',',$ids_treatments_charge);


       
    $i=0;
    while (isset($array_pat_paid[$i])){
      
        
        $sql  = " SELECT * FROM tbl_treatments_charge_notes WHERE id_treatments_charge = ".$array_ids_treatments_charge[$i]." AND notes = '".$array_notes[$i]."';";
        $resultado = ejecutar($sql,$conexion);
        $reporte_tch = mysqli_fetch_assoc($resultado);           

        if($reporte_tch == null){                                                
            $insert =" INSERT INTO tbl_treatments_charge_notes (id_treatments_charge,notes,date,user_id) VALUES ('".$array_ids_treatments_charge[$i]."','".$array_notes[$i]."','".$date."','".$user_id."');";
            $resultado = ejecutar($insert,$conexion);                                                                    
        }        
        
        
        $sql  = " SELECT pat_respon, ins_respon FROM tbl_treatments_charges WHERE id_treatments_charges = ".$array_ids_treatments_charge[$i].";";
        $resultado = ejecutar($sql,$conexion);
     
        $s = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$s] = $datos;
            break;
            $s++;
        }              
        
        $pat_respon =(float)(preg_replace('([^0-9.])', '', $reporte[0]['pat_respon']));
        $pat_paid =(float)(preg_replace('([^0-9.])', '', $array_pat_paid[$i]));
        $ins_respon =(float)(preg_replace('([^0-9.])', '', $reporte[0]['ins_respon']));
        $ins_paid =(float)(preg_replace('([^0-9.])', '', $array_ins_paid[$i]));
        $pat_balance = $pat_respon - $pat_paid;           
        $ins_balance = $ins_respon - $ins_paid;
        
        $balance = $pat_balance + $ins_balance;                 
    
        $where = ' WHERE id_treatments_charges = '.$array_ids_treatments_charge[$i];
                
        $update =" UPDATE tbl_treatments_charges SET pat_paid = '$".$pat_paid."', ins_paid = '$".$ins_paid."', status = '".$array_status[$i]."', pat_balance = '$".$pat_balance."', ins_balance = '$".$ins_balance."', balance = '$".$balance."' ".$where;
        $resultado = ejecutar($update,$conexion);            

        $insert =" INSERT INTO tbl_treatments_associated (id_treatments_charges,numero_referencia) VALUES ('".$array_ids_treatments_charge[$i]."','".$numero_referencia."');";
        $resultado = ejecutar($insert,$conexion);          
        
        
        $i++;
    }

            if($status = 2){
                $rS = 'Approved';
            }
            if($status = 3){
                $rS = 'Denied';
            }
            if($status = 1){
                $rS = 'Billed';
            }
        $json_resultado['resultadoStatus'] = $rS;
                
        $json_resultado['resultado'] = 'asociado';
                                  
        echo json_encode($json_resultado);                                  

?>