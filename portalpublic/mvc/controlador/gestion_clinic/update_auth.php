 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}

$conexion = conectar();

$id=$_POST["id_table"];
$id_seguro=$_POST["id_seguro"];
$seguro=$_POST["seguro"];
$cpt=$_POST["cpt"];
$auth=$_POST["auth"];
$status=$_POST["status"];
if($status=='si'){
    $data_status=1;
}else{
    $data_status=0;
}
$discipline=$_POST["discipline"];
$type=$_POST['type'];
$start=$_POST["start"];
$end=$_POST["end"];
$amount=$_POST["amount"];
$type_v_u=$_POST['type_v_u'];
$referral=$_POST["referral"];

//consulto si hay una autorizacion activa para ese cliente 
if($referral=='no'){
    $sqls  = "select count(*) as contador from patient_insurers_auths where id!='".$id."' and  seguro_id=".$id_seguro." AND status='1' AND discipline_id=".$discipline." AND cpt_type='".$type."'";  
}else{
    $sqls  = "select count(*) as contador from tbl_referral_insurers_auths where id!='".$id."' and  seguro_id=".$id_seguro." AND status='1' AND discipline_id=".$discipline." AND cpt_type='".$type."'";  
}
$resultados = ejecutar($sqls,$conexion);

while ($row=mysqli_fetch_array($resultados)) 
{     

    $contador[]=$row['contador'];
}
if($contador[0]>0){

        if($status=='si'){
            $valor='no';
        }else{
            $valor='si';
            $cargar_status=$data_status;
        }
}else{
    $cargar_status=$data_status;
    $valor='si';
}
if($valor=='si'){
    if($referral=='no'){
        $sql="UPDATE patient_insurers_auths SET num_aut=".$auth.", status='".$cargar_status."', discipline_id='".$discipline."', amount='".$amount."'"
                . ", type_visit='".$type_v_u."', start='".$start."', end='".$end."', cpt_type='".$type."', cpt_cpt='".$cpt."' WHERE id=".$id;
    }else{
        $sql="UPDATE tbl_referral_insurers_auths SET num_aut=".$auth.", status='".$cargar_status."', discipline_id='".$discipline."', amount='".$amount."'"
                . ", type_visit='".$type_v_u."', start='".$start."', end='".$end."', cpt_type='".$type."', cpt_cpt='".$cpt."' WHERE id=".$id;
    }
    $resultado = ejecutar($sql,$conexion);
    
    if($resultado){
        $array=array('success'=>true);
    }else{
        $array=array('success'=>false);
    }    
    echo json_encode($array);
    
}else{
    $array=array('success'=>'duplicated');
    echo json_encode($array);
}

