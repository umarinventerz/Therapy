 <?php
error_reporting(0);
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
                
                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modificación'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Eliminación'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = ' WHERE id_treatments = '.$_POST["id_treatments"];
                
                } else {                                
                
                    $id_treatments = null;
                    $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }
                
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){
                
                    if(isset($_POST["campo_1"]) && $_POST["campo_1"] != null){ $campo_1 = $_POST["campo_1"]; } else { $campo_1 = 'null'; }
                    if(isset($_POST["campo_2"]) && $_POST["campo_2"] != null){ $campo_2 = $_POST["campo_2"]; } else { $campo_2 = 'null'; }
                    if(isset($_POST["campo_3"]) && $_POST["campo_3"] != null){ $campo_3 = $_POST["campo_3"]; } else { $campo_3 = 'null'; }
                    if(isset($_POST["campo_4"]) && $_POST["campo_4"] != null){ $campo_4 = $_POST["campo_4"]; } else { $campo_4 = 'null'; }
                    if(isset($_POST["campo_5"]) && $_POST["campo_5"] != null){ $campo_5 = $_POST["campo_5"]; } else { $campo_5 = 'null'; }
                    if(isset($_POST["campo_6"]) && $_POST["campo_6"] != null){ $campo_6 = $_POST["patient_name"]; } else { $campo_6 = 'null'; }
                    if(isset($_POST["campo_7"]) && $_POST["campo_7"] != null){ $campo_7 = $_POST["campo_7"]; } else { $campo_7 = 'null'; }
                    if(isset($_POST["campo_8"]) && $_POST["campo_8"] != null){ $campo_8 = $_POST["campo_8"]; } else { $campo_8 = 'null'; }
                    if(isset($_POST["campo_9"]) && $_POST["campo_9"] != null){ $campo_9 = $_POST["therapyst_name"]; } else { $campo_9 = 'null'; }
                    if(isset($_POST["license_number"]) && $_POST["license_number"] != null){ $license_number = $_POST["license_number"]; } else { $license_number = 'null'; }
                    if(isset($_POST["campo_10"]) && $_POST["campo_10"] != null){ $campo_10 = $_POST["campo_10"]; } else { $campo_10 = 'null'; }
                    if(isset($_POST["campo_11"]) && $_POST["campo_11"] != null){ $campo_11 = $_POST["campo_11"]; } else { $campo_11 = 'null'; }
                    if(isset($_POST["campo_12"]) && $_POST["campo_12"] != null){ $campo_12 = $_POST["campo_12"]; } else { $campo_12 = 'null'; }
                    if(isset($_POST["campo_13"]) && $_POST["campo_13"] != null){ $campo_13 = $_POST["campo_13"]; } else { $campo_13 = 'null'; }
                    if(isset($_POST["campo_14"]) && $_POST["campo_14"] != null){ $campo_14 = $_POST["campo_14"]; } else { $campo_14 = 'null'; }
                    if(isset($_POST["campo_15"]) && $_POST["campo_15"] != null){ $campo_15 = $_POST["campo_15"]; } else { $campo_15 = 'null'; }
                    if(isset($_POST["campo_16"]) && $_POST["campo_16"] != null){ $campo_16 = $_POST["campo_16"]; } else { $campo_16 = 'null'; }
                    if(isset($_POST["campo_17"]) && $_POST["campo_17"] != null){ $campo_17 = $_POST["campo_17"]; } else { $campo_17 = 'null'; }
                    if(isset($_POST["campo_18"]) && $_POST["campo_18"] != null){ $campo_18 = $_POST["campo_18"]; } else { $campo_18 = 'null'; }
                    if(isset($_POST["campo_19"]) && $_POST["campo_19"] != null){ $campo_19 = $_POST["campo_19"]; } else { $campo_19 = 'null'; }
                    if(isset($_POST["campo_20"]) && $_POST["campo_20"] != null){ $campo_20 = $_POST["campo_20"]; } else { $campo_20 = 'null'; }
                    if(isset($_POST["campo_21"]) && $_POST["campo_21"] != null){ $campo_21 = $_POST["campo_21"]; } else { $campo_21 = 'null'; }
                    if(isset($_POST["campo_22"]) && $_POST["campo_22"] != null){ $campo_22 = $_POST["campo_22"]; } else { $campo_22 = 'null'; }
                    if(isset($_POST["campo_23"]) && $_POST["campo_23"] != null){ $campo_23 = $_POST["campo_23"]; } else { $campo_23 = 'null'; }
                    if(isset($_POST["campo_24"]) && $_POST["campo_24"] != null){ $campo_24 = $_POST["campo_24"]; } else { $campo_24 = 'null'; }
                    if(isset($_POST["pay"]) && $_POST["pay"] != null){ $pay = $_POST["pay"]; } else { $pay = 'null'; }
                    if(isset($_POST["adults_progress_notes"]) && $_POST["adults_progress_notes"] != null){ $adults_progress_notes = $_POST["adults_progress_notes"]; } else { $adults_progress_notes = 'null'; }
                    if(isset($_POST["pedriatics_progress_notes"]) && $_POST["pedriatics_progress_notes"] != null){ $pedriatics_progress_notes = $_POST["pedriatics_progress_notes"]; } else { $pedriatics_progress_notes = 'null'; }
                    
                                }
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                $tabla = 'tbl_treatments';
                
                if($accion == 'insertar'){
                
                   $sqlTreatmentsVerificar  = " SELECT * FROM tbl_treatments WHERE campo_5 = '".$campo_5."' AND campo_1='".$campo_1."' ;";
                    $resultadoTreatmentsVerificar = ejecutar($sqlTreatmentsVerificar,$conexion);
                    $i = 0;      
                    $treatmentsVerificar = array();

                    while($datos = mysqli_fetch_array($resultadoTreatmentsVerificar)) { 
                         $treatmentsVerificar= $datos['id_treatments'];
                        $i++;
                    }
                    // echo $treatmentsVerificar[$i];
                    // die;
                    if(!isset($treatmentsVerificar[$i])){
                        $insert = " INSERT INTO tbl_treatments(campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,license_number,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,pay,adults_progress_notes,pedriatics_progress_notes) VALUES ('".$campo_1."','".$campo_2."','".$campo_3."','".$campo_4."','".$campo_5."','".$campo_6."','".$campo_7."','".$campo_8."','".$campo_9."','".$license_number."','".$campo_10."','".$campo_11."','".$campo_12."','".$campo_13."','".$campo_14."','".$campo_15."','".$campo_16."','".$campo_17."','".$campo_18."','".$campo_19."','".$campo_20."','".$campo_21."','".$campo_22."','".$campo_23."','".$campo_24."','0','0','0');";
                        $insert = str_replace("'null'", "null", $insert);
                        $resultado = ejecutar($insert,$conexion);
                    }else{
                        $mensaje_almacenamiento = 'Ya existe un registro esta dia para el paciente '.$campo_6;
                        $resultado = true;
                        $color = 'red';
                    }
                     
                }
                

                if($accion == 'modificar'){
                
                $update = " UPDATE tbl_treatments SET campo_1 = '".$campo_1."',campo_2 = '".$campo_2."',campo_3 = '".$campo_3."',campo_4 = '".$campo_4."',campo_5 = '".$campo_5."',campo_6 = '".$campo_6."',campo_7 = '".$campo_7."',campo_8 = '".$campo_8."',campo_9 = '".$campo_9."',license_number = '".$license_number."',campo_10 = '".$campo_10."',campo_11 = '".$campo_11."',campo_12 = '".$campo_12."',campo_13 = '".$campo_13."',campo_14 = '".$campo_14."',campo_15 = '".$campo_15."',campo_16 = '".$campo_16."',campo_17 = '".$campo_17."',campo_18 = '".$campo_18."',campo_19 = '".$campo_19."',campo_20 = '".$campo_20."',campo_21 = '".$campo_21."',campo_22 = '".$campo_22."',campo_23 = '".$campo_23."',campo_24 = '".$campo_24."',pay = '".$pay."',adults_progress_notes = '".$adults_progress_notes."',pedriatics_progress_notes = '".$pedriatics_progress_notes."' ".$where;
                $update = str_replace("'null'", "null", $update);
                $resultado = ejecutar($update,$conexion); 
                
                }
                
             if($accion == 'eliminar'){
                
                $delete = ' DELETE FROM tbl_treatments '.$where;
                $resultado = ejecutar($delete,$conexion);                     
                
                }
                
                 if($resultado) {
                
                     $json_resultado['resultado'] = '<h3><font color="'.(($color == 'red')?'red':'blue').'">'.$mensaje_almacenamiento.'</font></h3>';
                     
                 $json_resultado['mensaje'] = $mensaje_almacenamiento;
                 
                 if($_POST['accion'] == 'eliminar'){
                     $json_resultado['mensaje_data_table'] = '<h5><font color="#FE642E"><b>Eliminado</b></font></h5>';
                 }
             
              
                 } else {
                
                 $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
                    
                 } 
                 
                 echo json_encode($json_resultado);                                  

?>