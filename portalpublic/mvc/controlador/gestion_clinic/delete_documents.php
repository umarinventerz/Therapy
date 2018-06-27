 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
//	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
//		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
//		echo '<script>window.location="../../home/home.php";</script>';
//	}
}

$conexion = conectar();

$id = $_POST['id_document'];
$document = $_POST['document'];

if($document == 'Prescriptions'){
    $table = 'prescription';
    $where = ' id_prescription = '.$id;
    $tableFind = 'tbl_evaluations';
    $whereFind = ' id_prescription ='.$id;
    
}else{
    if($document == 'Evaluation'){
        $table = 'tbl_evaluations';        
        $where = ' id = '.$id;
        $tableFind = 'careplans';        
        $whereFind = ' evaluations_id ='.$id;
    }else{
        if($document == 'POC'){
            $table = 'careplans';
            $where = ' id_careplans = '.$id;
            $tableFind = 'tbl_notes_documentation';        
            $whereFind = ' id_careplans ='.$id;
        }else{
            if($document == 'Note'){
                $table = 'tbl_notes_documentation';            
                $where = ' id_notes = '.$id;
            }else{
                if($document == 'Summary'){
                    $table = 'tbl_summary';            
                    $where = ' id_summary = '.$id;
                }else{
                    if($document == 'Discharge'){
                        $table = 'tbl_discharge';            
                        $where = ' id_discharge = '.$id;
                    }
                }
            }
        }        
    }
}
$result = [];
if(isset($tableFind)){
    $sql  = "SELECT * FROM $tableFind WHERE deleted=0 AND".$whereFind; 
    $resultado = ejecutar($sql,$conexion);                   
    while($datos = mysqli_fetch_assoc($resultado)) { 
        $result = $datos;
    }
}

$result2[] = [];
if($table=='tbl_notes_documentation' || $table=='tbl_evaluations'){
 $sql2  = "SELECT * FROM ".$table." WHERE ".$where; 
    $resultado2 = ejecutar($sql2,$conexion);                   
    while($datos2 = mysqli_fetch_array($resultado2)) { 
        $result2['visit_id'] = $datos2['visit_id'];
    }
}




if(empty($result)){        
    $delete = "UPDATE ".$table." SET deleted = 1, deleted_date = now() WHERE ".$where;
    ejecutar($delete,$conexion);

            if(isset($result2)){        
            $delete2 = "UPDATE tbl_visits SET deleted = 1 WHERE id='".$result2['visit_id']."'  ;";
            ejecutar($delete2,$conexion);
            $json_resultado['mensaje'] = 'ok';
             }



    $json_resultado['mensaje'] = 'ok';  
}else{
    $json_resultado['mensaje'] = 'no';  
}



                       
echo json_encode($json_resultado);

