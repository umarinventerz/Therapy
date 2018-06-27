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



$conexion = conectar();
                
$id_treatments_charges = $_POST['id_treatments_charges'];
$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');

$where_t = ' WHERE id_treatments_charges = '.$id_treatments_charges;



$sql  = " SELECT * FROM tbl_treatments_charges".$where_t;
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    break;
    $i++;
}                
        $pat_respon =(float)(preg_replace('([^0-9.])', '', $reporte[$i]['pat_respon']));
        $pat_paid =(float)(preg_replace('([^0-9.])', '', $_POST["patient_paid"]));
        $ins_respon =(float)(preg_replace('([^0-9.])', '', $reporte[$i]['ins_respon']));
        $ins_paid =(float)(preg_replace('([^0-9.])', '', $_POST["insurance_paid"]));
        $pat_balance = $pat_respon - $pat_paid;           
        $ins_balance = $ins_respon - $ins_paid;
        
        $balance = $pat_balance + $ins_balance;                  
        
        //ACA FALTA EL CALCULO DEL WRITE_OFF
        

                    if(isset($_POST["patient_paid"]) && $_POST["patient_paid"] != null){ $patient_paid = $pat_paid; } else { $patient_paid = 'null'; }
                    if(isset($_POST["insurance_paid"]) && $_POST["insurance_paid"] != null){ $insurance_paid = $ins_paid; } else { $insurance_paid = 'null'; }
                    if(isset($_POST["status"]) && $_POST["status"] != null){ $status = $_POST["status"]; } else { $status = 'null'; }
                    if(isset($_POST["notes"]) && $_POST["notes"] != null){ $notes = $_POST["notes"]; } else { $notes = 'null'; }
                    
                    if(isset($_POST['reference_number']) && $_POST['reference_number'] != ''){
                        $sqlAssociated  = " SELECT * FROM tbl_treatments_associated ".$where_t;
                        $resultadoSelectAssociated = ejecutar($sqlAssociated,$conexion);
                        while($datos = mysqli_fetch_assoc($resultadoSelectAssociated)) {            
                            $numero_referencia = $datos['numero_referencia'];                            
                        }
                        $updateAssociated =" UPDATE tbl_treatments_associated SET numero_referencia = '".$_POST['reference_number']."' WHERE numero_referencia = ".$numero_referencia;
                        $resultadoAssociated = ejecutar($updateAssociated,$conexion);     
                    }
                    
                    $update =" UPDATE tbl_treatments_charges SET pat_paid = '$".$patient_paid."', ins_paid = '$".$insurance_paid."', status = '".$status."', pat_balance = '$".$pat_balance."', ins_balance = '$".$ins_balance."', balance = '$".$balance."' ".$where_t;
                    $resultado = ejecutar($update,$conexion);     
                    
                    $sql  = " SELECT * FROM tbl_treatments_charge_notes WHERE id_treatments_charge = ".$id_treatments_charges." AND notes = '".$notes."';";
                    $resultado = ejecutar($sql,$conexion);
                    $reporte = mysqli_fetch_assoc($resultado);           
                            
                    if($reporte == null){                                                
                        $insert =" INSERT INTO tbl_treatments_charge_notes (id_treatments_charge,notes,date,user_id) VALUES ('".$id_treatments_charges."','".$notes."','".$date."','".$user_id."');";
                        $resultado = ejecutar($insert,$conexion);                                                                    
                    }
                    
                                  
            $json_resultado['resultado'] = 'almacenado';
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

                 
                 
        echo json_encode($json_resultado);                                  

?>