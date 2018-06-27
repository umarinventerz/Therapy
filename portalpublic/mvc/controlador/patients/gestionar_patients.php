<?php

session_start();
require_once '../../../conex.php';

$conexion = conectar();
                
$Pat_id = null;

                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'insertar'){    
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modification'; }                        
                    if($_POST['accion'] == 'insertar'){ $mensaje_resultado = 'Insert'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactory';                    
                  //  $where = ' WHERE Pat_id = '.$_POST["Pat_id"];
          
                
                } else {                                
                
                $id_patients = null;
                $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }

                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){                                                            
                    
                 
             if (!file_exists("../../../images/avatar")) {
            mkdir("../../../images/avatar", 0777, true);
        }
    $nombre="avatar/".uniqid('cupon-') . '-foto.jpg';
    $source = $_FILES['Photo']['tmp_name'];
    $destination = __DIR__.'../../../../images/'.$nombre;

 move_uploaded_file($source, $destination );

               


                    if(isset($_POST["type_patient"]) && $_POST["type_patient"] != null){ $type_patient = $_POST["type_patient"]; } else { $type_patient = 'null'; }
                    if(isset($_POST["Last_name"]) && $_POST["Last_name"] != null){ $Last_name = $_POST["Last_name"]; } else { $Last_name = 'null'; }
                    if(isset($_POST["First_name"]) && $_POST["First_name"] != null){ $First_name = $_POST["First_name"]; } else { $First_name = 'null'; }
                    if(isset($_POST["Pat_id"]) && $_POST["Pat_id"] != null){ $Pat_id = $_POST["Pat_id"]; } else { $Pat_id = 'null'; }
                    if(isset($_POST["Sex"]) && $_POST["Sex"] != null){ $Sex = $_POST["Sex"]; } else { $Sex = 'null'; }
                    if(isset($_POST["DOB"]) && $_POST["DOB"] != null){ $DOB = date("Y-m-d",strtotime($_POST["DOB"])); } else { $DOB = 'null'; }
                    if(isset($_POST["Guardian"]) && $_POST["Guardian"] != null){ $Guardian = $_POST["Guardian"]; } else { $Guardian = 'null'; }
                    if(isset($_POST["Social"]) && $_POST["Social"] != null){ $Social = $_POST["Social"]; } else { $Social = 'null'; }
                    if(isset($_POST["Address"]) && $_POST["Address"] != null){ $Address = $_POST["Address"]; } else { $Address = 'null'; }
                    if(isset($_POST["City"]) && $_POST["City"] != null){ $City = $_POST["City"]; } else { $City = 'null'; }
                    if(isset($_POST["State"]) && $_POST["State"] != null){ $State = $_POST["State"]; } else { $State = 'null'; }
                    if(isset($_POST["Zip"]) && $_POST["Zip"] != null){ $Zip = $_POST["Zip"]; } else { $Zip = 'null'; }
                    if(isset($_POST["county"]) && $_POST["county"] != null){ $county = $_POST["county"]; } else { $county = 'null'; }
                    if(isset($_POST["E_mail"]) && $_POST["E_mail"] != null){ $E_mail = $_POST["E_mail"]; } else { $E_mail = 'null'; }
                    if(isset($_POST["Phone"]) && $_POST["Phone"] != null){ $Phone = $_POST["Phone"]; } else { $Phone = 'null'; }
                    if(isset($_POST["id_carriers"]) && $_POST["id_carriers"] != null){ $id_carriers = $_POST["id_carriers"]; } else { $id_carriers = 'null'; }
                    if(isset($_POST["Cell"]) && $_POST["Cell"] != null){ $Cell = $_POST["Cell"]; } else { $Cell = 'null'; }                    
                    if(isset($_POST["pcp"]) && $_POST["pcp"] != null){ $pcp = $_POST["pcp"]; } else { $pcp = 'null'; }                    
                    if(isset($_POST["PCP_NPI"]) && $_POST["PCP_NPI"] != null){ $Pcp_NPI = $_POST["PCP_NPI"]; } else { $Pcp_NPI = 'null'; }                    
                    if(isset($_POST["Ref_Physician"]) && $_POST["Ref_Physician"] != null){ $Ref_Physician = $_POST["Ref_Physician"]; } else { $Ref_Physician = 'null'; }
                    if(isset($_POST["Ref_Physician_npi"]) && $_POST["Ref_Physician_npi"] != null){ $Ref_Physician_npi = $_POST["Ref_Physician_npi"]; } else { $Ref_Physician_npi = 'null'; }
                    if(isset($_POST["Pri_Ins"]) && $_POST["Pri_Ins"] != null){ $Pri_Ins = $_POST["Pri_Ins"]; } else { $Pri_Ins = 'null'; }
                    if(isset($_POST["Auth"]) && $_POST["Auth"] != null){ $Auth = $_POST["Auth"]; } else { $Auth = 'null'; }
                    if(isset($_POST["Sec_INS"]) && $_POST["Sec_INS"] != null){ $Sec_INS = $_POST["Sec_INS"]; } else { $Sec_INS = 'null'; }
                    if(isset($_POST["Auth_2"]) && $_POST["Auth_2"] != null){ $Auth_2 = $_POST["Auth_2"]; } else { $Auth_2 = 'null'; }
                    if(isset($_POST["Ter_Ins"]) && $_POST["Ter_Ins"] != null){ $Ter_Ins = $_POST["Ter_Ins"]; } else { $Ter_Ins = 'null'; }
                    if(isset($_POST["Auth_3"]) && $_POST["Auth_3"] != null){ $Auth_3 = $_POST["Auth_3"]; } else { $Auth_3 = 'null'; }
                    if(isset($_POST["Mem_N"]) && $_POST["Mem_N"] != null){ $Mem_N = $_POST["Mem_N"]; } else { $Mem_N = 'null'; }
                    if(isset($_POST["Grp_N"]) && $_POST["Grp_N"] != null){ $Grp_N = $_POST["Grp_N"]; } else { $Grp_N = 'null'; }
                    if(isset($_POST["Intake_Agmts"]) && $_POST["Intake_Agmts"] != null){ $Intake_Agmts = date("Y-m-d",strtotime($_POST["Intake_Agmts"])); } else { $Intake_Agmts = 'null'; }                                        
                    if(isset($_POST["active"]) && $_POST["active"] == 'on'){ $active = 1; } else { $active = 0; }                    
                    if(isset($_POST["admision_date"]) && $_POST["admision_date"] != null){ $admision_date = date("Y-m-d",strtotime($_POST["admision_date"]));} else { $admision_date = 'null'; } 
                    if(isset($_POST["discharge_date"]) && $_POST["discharge_date"] != null){ $discharge_date = date("Y-m-d",strtotime($_POST["discharge_date"]));} else { $discharge_date = 'null'; } 
                  
                                }
                       
        $sql = 'SELECT Pat_id FROM patients WHERE Pat_id = \''.$Pat_id.'\';';

        $resultado = ejecutar($sql,$conexion);

            $i=0;
            while ($row=mysqli_fetch_array($resultado)) {	

                $Pat_id_val = $row['Pat_id'];                                           
                
            $i++;        
            } 
            
            if(isset($Pat_id_val) && $_POST['accion'] == 'insertar'){
                
                $json_resultado['repetido'] = 'si';
                
            } else { 
                if($pcp != 'null'){
                 /*  $sql = 'SELECT Name FROM physician WHERE Phy_id = \''.$pcp.'\';';

                    $resultadopcp = ejecutar($sql,$conexion);

                    $pcp_name;
                    while ($row_pcp=mysqli_fetch_array($resultadopcp)) {   */

                        $pcp_name = ''.$pcp.'';
   
                   // }
                }



                
                if($Ref_Physician != 'null'){
                  /* $sql = 'SELECT Name FROM physician WHERE Phy_id = \''.$Ref_Physician.'\';';

                    $resultadoRef_Physician = ejecutar($sql,$conexion);

                    $Ref_Physician_name;
                    while ($row_Ref_Physician_name=mysqli_fetch_array($resultadoRef_Physician)) {  */

                        $Ref_Physician_name = ''.$Ref_Physician.'';

                    //}
                }        
                 
                
               if($Pri_Ins != 'null'){
                  /* $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$Pri_Ins.'\';';

                    $resultadoPri_Ins = ejecutar($sql,$conexion);

                    $Pri_Ins_insurance;
                    while ($row_Pri_Ins=mysqli_fetch_array($resultadoPri_Ins)) {    */

                        $Pri_Ins_insurance = ''.$Pri_Ins.'';

                   // }
                }
                if($Sec_INS != 'null'){
                  /* $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$Sec_INS.'\';';

                    $resultadoSec_INS = ejecutar($sql,$conexion);

                    $Sec_INS_insurance;
                    while ($row_Sec_INS=mysqli_fetch_array($resultadoSec_INS)) {   */

                        $Sec_INS_insurance = ''.$Sec_INS.'';

                  //  }
                }
                if($Ter_Ins != 'null'){
                    

                        $Ter_Ins_insurance = ''.$Ter_Ins.'';

                    
                }
            
           

                  
                                
                                
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                $tablas = 'patients';                              
                 
                if($accion == 'insertar'){

                 
           



                    if ($Sec_INS=='null') {
                        $Sec_INS_insurance='0';
                    }
                    if ($Ter_Ins=='null') {
                        $Ter_Ins_insurance='0';
                    }
                    if ($Ref_Physician=='null') {
                        $Ref_Physician_name='0';
                    }
                   
                   $insert =" INSERT into patients SET Last_name = '".$Last_name."', First_name = '".$First_name."', Pat_id='".$Pat_id."',  Sex = '".$Sex."', DOB = '".$DOB."', Guardian = '".$Guardian."', Social = '".$Social."', Address = '".$Address."', City = '".$City."', State = '".$State."', Zip = '".$Zip."', county = '".$county."', E_mail = '".$E_mail."', Phone = '".$Phone."', Cell = '".$Cell."', PCP = '".$pcp_name."', PCP_NPI = '".$Pcp_NPI."', Ref_Physician = '".$Ref_Physician_name."', Phy_NPI = '3', Pri_Ins = '".$Pri_Ins_insurance."', Auth = '".$Auth."', Sec_INS = '".$Sec_INS_insurance."', Auth_2 = '".$Auth_2."', Ter_Ins = '".$Ter_Ins_insurance."', Auth_3 = '".$Auth_3."', `Mem_#` = '".$Mem_N."', `Grp_#` = '".$Grp_N."', Intake_Agmts = '".$Intake_Agmts."', active = '".$active."', admision_date = '".$admision_date."', discharge_date = '".$discharge_date."', id_seguros_type_person = '".$type_patient."',id_carriers = '".$id_carriers."',`Table_name`='0', Thi_Ins='0',convertido='0'";
                    $resultado=ejecutar($insert,$conexion);
                    
                  
                       
                    
                     
                
                    //OBTENIENDO PATIENT ID
  $A="SELECT id from patients WHERE Pat_id='".$Pat_id."'";
                    $id=ejecutar($A,$conexion);
                    $id_patients = mysqli_fetch_assoc($id);
                    $id_patients=$id_patients['id'];
                    //agregando barcode                
                     $barcode = "INSERT INTO tbl_barcodes (id_relation,barcode,id_type_person) "
                        . "VALUES ('".$id_patients."','".$_POST["barcode"]."',1)";
                    ejecutar($barcode,$conexion);
//agregando en avatar



                    
                    //inserto en patients copy
$patienst_copy = "INSERT INTO `patients_copy` (`id`, `Last_name`, `First_name`, `Pat_id`, `prescription_st`, `prescription_pt`, `prescription_ot`, `waiting_prescription_st`, `waiting_prescription_pt`, `waiting_prescription_ot`, `eval_auth_st`, `eval_auth_pt`, `eval_auth_ot`, `waiting_auth_eval_st`, `waiting_auth_eval_pt`, `waiting_auth_eval_ot`, `eval_patient_st`, `eval_patient_pt`, `eval_patient_ot`, `doctor_signature_st`, `doctor_signature_pt`, `doctor_signature_ot`, `waiting_signature_st`, `waiting_signature_pt`, `waiting_signature_ot`, `tx_auth_st`, `tx_auth_pt`, `tx_auth_ot`, `waiting_tx_auth_st`, `waiting_tx_auth_pt`, `waiting_tx_auth_ot`, `tx_auth_old_st`, `tx_auth_old_pt`, `tx_auth_old_ot`, `waiting_old_tx_auth_st`, `waiting_old_tx_auth_pt`, `waiting_old_tx_auth_ot`, `ready_treatment_st`, `ready_treatment_pt`, `ready_treatment_ot`, `scheduled_st`, `scheduled_pt`, `scheduled_ot`, `hold_st`, `hold_pt`, `hold_ot`, `progress_adults_st`, `progress_adults_pt`, `progress_adults_ot`, `progress_pedriatics_st`, `progress_pedriatics_pt`, `progress_pedriatics_ot`, `been_seen_st`, `been_seen_pt`, `been_seen_ot`, `discharge_st`, `discharge_pt`, `discharge_ot`) VALUES
('".$id_patients."', '".$Last_name."', '".$First_name."', '".$Pat_id."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";


                    ejecutar($patienst_copy,$conexion);
                     
                    //guardo auditoria en tbl_audit_generales   ""
                  $fecha=date('Y-m-d H:i:s');
                    $audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$Pat_id."','2','".$fecha."')";
                    ejecutar($audit_general,$conexion);


if($nombre!=''){
$fecha=date('Y-m-d H:i:s');     
        $update_avatar="INSERT into avatar (`tipe_user`,`picture`,`identificador`,`created_at`,`updated_at`) 
values ('patients','".$nombre."','".$id_patients."','".$fecha."','".$fecha."');";

ejecutar($update_avatar,$conexion);}

                    echo "<script type=\"text/javascript\">alert(\"Pacient Save\");</script>";
echo '<script>window.location="../../vista/patients/registrar_patients.php";</script>';
                    
                }
                
                if($accion == 'modificar'){
                    
 
                   

                    $update =" UPDATE patients SET Last_name = '".$Last_name."', First_name = '".$First_name."', Sex = '".$Sex."', DOB = '".$DOB."', Guardian = '".$Guardian."', Social = '".$Social."', Address = '".$Address."', City = '".$City."', State = '".$State."', Zip = '".$Zip."', county = '".$county."', E_mail = '".$E_mail."', Phone = '".$Phone."', Cell = '".$Cell."', PCP = '".$pcp_name."', PCP_NPI = '".$Pcp_NPI."', Ref_Physician = '".$Ref_Physician_name."', Phy_NPI = '".$Ref_Physician_npi."', Pri_Ins = '".$Pri_Ins_insurance."', Auth = '".$Auth."', Sec_INS = '".$Sec_INS_insurance."', Auth_2 = '".$Auth_2."', Ter_Ins = '".$Ter_Ins_insurance."', Auth_3 = '".$Auth_3."', `Mem_#` = '".$Mem_N."', `Grp_#` = '".$Grp_N."', Intake_Agmts = '".$Intake_Agmts."', active = '".$active."', admision_date = '".$admision_date."', discharge_date = '".$discharge_date."', id_seguros_type_person = '".$type_patient."', id_carriers = '".$id_carriers."' WHERE id='".$_POST["Pat_id"]."'";
                    $resultado = ejecutar($update,$conexion);                     
                    
      


                $consulta = "SELECT * from  tbl_barcodes WHERE id_relation='".$_POST['id_patients']."' AND id_type_person='1' ";
$result_consulta = ejecutar($consulta,$conexion);
$row_cnt = mysqli_num_rows($result_consulta);
if($row_cnt > 0){
    $barcode = "UPDATE  tbl_barcodes SET barcode='".$_POST['barcode']."' WHERE id_relation='".$_POST['id_patients']."' AND id_type_person='1' ";
                    ejecutar($barcode,$conexion);
}else{
  $barcode = "INSERT INTO tbl_barcodes (id_relation,barcode,id_type_person) "
                        . "VALUES ('".$_POST['id_patients']."','".$_POST["barcode"]."',1)";
                    ejecutar($barcode,$conexion);
}

//insertar el avatar en la base de datos
        $fecha=date('Y-m-d H:i:s');
        //primero reviso si ya existe para si no crearlo
       $buscar="SELECT * FROM avatar WHERE identificador = '".$_POST['id_patients']."'";
       $busqueda=ejecutar($buscar,$conexion);
       $row_cnt = mysqli_num_rows($busqueda);
       if ($row_cnt>0 &&  $source!='') {
           $update_avatar=" UPDATE avatar SET `tipe_user` = 'patients',`picture` = '".$nombre."',`updated_at` = '".$fecha."'"
                . "WHERE identificador = '".$_POST['id_patients']."';";
       }if( $source!=''){
        $update_avatar="INSERT into avatar (`tipe_user`,`picture`,`identificador`,`created_at`,`updated_at`) 
values ('patients','".$nombre."','".$_POST['id_patients']."','".$fecha."','".$fecha."');";}
        else{
            $update_avatar='select * from avatar';
        }
    
ejecutar($update_avatar,$conexion);

echo "<script type=\"text/javascript\">alert(\"Pacient Edited\");</script>";
echo '<script>window.location="../../vista/patients/consultar_patients.php?&consultar=si";</script>';
                    //actualizo barcode
                    
                }                  
                
                if($accion == 'eliminar'){
                    
                    $delete ="DELETE FROM patients where Pat_id='".$_POST['Pat_id']."'";
                    $resultado = ejecutar($delete,$conexion);                     
                    
                }    


                if($resultado == 'OK') {
                

/*tabladinamicascontrolador*/
                     $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';
                     
                 $json_resultado['mensaje'] = $mensaje_almacenamiento;
                 
                 if($_POST['accion'] == 'eliminar'){
                     $json_resultado['mensaje_data_table'] = '<h5><font color="#FE642E"><b>Eliminado</b></font></h5>';
                 }
                
               
                 } else {
                
                 $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
                    
                 } 
                 
                 $json_resultado['repetido'] = 'no';
                 
                 
            }
                 
                 
                 echo json_encode($json_resultado);                                  

?>
