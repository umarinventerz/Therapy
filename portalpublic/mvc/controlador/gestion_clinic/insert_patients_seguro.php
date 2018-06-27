 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}

$conexion = conectar();

$insure=$_POST["insure"];
$member=$_POST["member"];
$group=$_POST["group"];
$status=$_POST["status"];
if($status=='si'){
    $data_status=1;
}else{
    $data_status=0;
}
$patients=$_POST["pacientes"];
$fecha=date('Y-m-d');
$referral=$_POST["referral"];

//consulto si hay una autorizacion activa para ese cliente 
if($referral=='no'){ 
    $sqls  = "select count(*) as contador from tbl_patient_seguros where patient_id=".$patients." AND status='1'"; 
}else{
    $sqls  = "select count(*) as contador from tbl_referral_seguros where patient_id=".$patients." AND status='1'"; 
}
$resultados = ejecutar($sqls,$conexion);

while ($row=mysqli_fetch_array($resultados)) 
{     

    $contador[]=$row['contador'];
}
 if($contador[0]>0 && $data_status==1){
     $cargar_status=0;
     $array=array('success'=>false,'mensaje'=>'There is another Active Insurance');
     echo json_encode($array);
}else{
    
    if($referral=='no'){     
        $sql="INSERT INTO tbl_patient_seguros(patient_id,insure_id,status,member,group_name,created_at)"
                . "VALUES('".$patients."','".$insure."','".$cargar_status."','".$member."','".$group."','".$fecha."')";
    }else{
            $sql="INSERT INTO tbl_referral_seguros(patient_id,insure_id,status,member,group_name,created_at)"
                . "VALUES('".$patients."','".$insure."','".$cargar_status."','".$member."','".$group."','".$fecha."')";
    }
    $resultado = ejecutar($sql,$conexion);

    if($resultado){
        $array=array('success'=>true);
    }else{
        $array=array('success'=>false,'mensaje'=>'An error has occurred, please try again');
    }    
    echo json_encode($array);
}