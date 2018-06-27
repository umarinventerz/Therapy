 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}

$conexion = conectar();

$id_table=$_POST["id_table"];
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

// busco nombre de disciplina para ponerla en el reporte de patients_copy
$discipline_sql = "SELECT *,Name  FROM discipline WHERE DisciplineId = '".$discipline."';";
$resultadoDiscipline = ejecutar($discipline_sql,$conexion); 
while($row = mysqli_fetch_array($resultadoDiscipline)){
    $d_name = $row['Name'];
}
// busco el patient ID
 $patient_sql = "SELECT patient_id  
                    FROM  tbl_patient_seguros s 
                    WHERE s.id= '".$id_table."' ";
                    
$resultado_patient = ejecutar($patient_sql,$conexion); 
while($row = mysqli_fetch_array($resultado_patient)){
    $Pat_id = $row['patient_id'];
}

// si es EVAL tient que haber una prescription in progress (validacion para que reportes no lo ponga en EVALUAR el paciente report )
if($type=='EVAL'){
 $sql_rx = " SELECT rx.id_prescription from  prescription rx where deleted=0 and rx.status=1 and Discipline='".$discipline."' and rx.Patient_ID='".$Pat_id."' ; ";
$result_rx = ejecutar($sql_rx,$conexion);
    while($row = mysqli_fetch_array($result_rx)){
    $rx = $row['id_prescription'];
    }
}


    //consulto si hay una autorizacion activa para ese cliente 
    if($referral=='no'){
        $sqls  = "select count(*) as contador from patient_insurers_auths where seguro_id=".$id_table." AND status='1' AND discipline_id=".$discipline." AND cpt_type='".$type."'";  
    }else{
        $sqls  = "select count(*) as contador from tbl_referral_insurers_auths where seguro_id=".$id_table." AND status='1' AND discipline_id=".$discipline." AND cpt_type='".$type."'";  
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


    if( ($type=='EVAL' && $valor=='si' && isset($rx) )  ||   ($type=='TX' && $valor=='si')   ){
   
            if($referral=='no'){
                $sql="INSERT INTO patient_insurers_auths(seguro_id,num_aut,discipline_id,cpt_type,cpt_cpt,amount,type_visit,start,end,status)"
                . "VALUES('".$id_table."','".$auth."','".$discipline."','".$type."','".$cpt."','".$amount."','".$type_v_u."','".$start."','".$end."','".$cargar_status."')";
                
                if($type=='EVAL'){
             $update_new_report= "UPDATE patients_copy SET waiting_auth_eval_".$d_name." ='0' , waiting_auth_eval_date_".$d_name." = '0000-00-00' , eval_patient_".$d_name."='1' 
                            WHERE id='".$Pat_id."'  "; 
                            
                } else {
            $update_new_report= "UPDATE patients_copy SET waiting_tx_auth_".$d_name." ='0' , hold_".$d_name." = '0' 
                                , waiting_tx_auth_date_".$d_name." = '0000-00-00' , ready_treatment_".$d_name."='1' 
                                 WHERE id='".$Pat_id."'  "; 
                            
                }   
                $resultado2 = ejecutar($update_new_report,$conexion);

            }else{
                $sql="INSERT INTO tbl_referral_insurers_auths(seguro_id,num_aut,discipline_id,cpt_type,cpt_cpt,amount,type_visit,start,end,status)"
                . "VALUES('".$id_table."','".$auth."','".$discipline."','".$type."','".$cpt."','".$amount."','".$type_v_u."','".$start."','".$end."','".$cargar_status."')";
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