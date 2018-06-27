 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}

$conexion = conectar();
                
                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar' || $_POST['accion'] == 'convertir'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modificación'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Eliminación'; }                                        
                    if($_POST['accion'] == 'convertir'){ $mensaje_resultado = 'convertir'; }
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = 'id_referral = '.$_POST["id_referral"];
                
                } else {                                
                
                $id_referral = null;
                $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }
                
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar' || ($_POST['accion'] == 'convertir' && $_POST['update'] != 'no')){
                
                if(isset($_POST["Ref_id"]) && $_POST["Ref_id"] != null){ $Ref_id = $_POST["Ref_id"]; } else { $Ref_id = 'null'; }
                    if(isset($_POST["type_patient"]) && $_POST["type_patient"] != null){ $type_patient = $_POST["type_patient"]; } else { $type_patient = 'null'; }
                    if(isset($_POST["Last_name"]) && $_POST["Last_name"] != null){ $Last_name = $_POST["Last_name"]; } else { $Last_name = 'null'; }
                    if(isset($_POST["First_name"]) && $_POST["First_name"] != null){ $First_name = $_POST["First_name"]; } else { $First_name = 'null'; }                    
                    if(isset($_POST["Sex"]) && $_POST["Sex"] != null){ $Sex = $_POST["Sex"]; } else { $Sex = 'null'; }
                    if(isset($_POST["DOB"]) && $_POST["DOB"] != null){ $DOB = $_POST["DOB"]; } else { $DOB = 'null'; }
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
                    if(isset($_POST["pcp"]) && ($_POST["pcp"] != null && $_POST["pcp"] != '')){ $PCP = $_POST["pcp"]; } else { $PCP = 'null'; }
                    if(isset($_POST["PCP_NPI"]) && $_POST["PCP_NPI"] != null){ $PCP_NPI = $_POST["PCP_NPI"]; } else { $PCP_NPI = 'null'; }
                    if(isset($_POST["Ref_Physician"]) && ($_POST["Ref_Physician"] != nul && $_POST["Ref_Physician"] != '')){ $Ref_Physician = $_POST["Ref_Physician"]; } else { $Ref_Physician = 'null'; }
                    if(isset($_POST["Phy_NPI"]) && $_POST["Phy_NPI"] != null){ $Phy_NPI = $_POST["Phy_NPI"]; } else { $Phy_NPI = 'null'; }
                    if(isset($_POST["Pri_Ins"]) && ($_POST["Pri_Ins"] != null && $_POST["Pri_Ins"] != '')){ $Pri_Ins = $_POST["Pri_Ins"]; } else { $Pri_Ins = 'null'; }
                    if(isset($_POST["Auth"]) && $_POST["Auth"] != null){ $Auth = $_POST["Auth"]; } else { $Auth = 'null'; }
                    if(isset($_POST["Sec_INS"]) && ($_POST["Sec_INS"] != null && $_POST["Sec_INS"] != '')){ $Sec_INS = $_POST["Sec_INS"]; } else { $Sec_INS = 'null'; }
                    if(isset($_POST["Auth_2"]) && $_POST["Auth_2"] != null){ $Auth_2 = $_POST["Auth_2"]; } else { $Auth_2 = 'null'; }
                    if(isset($_POST["Ter_Ins"]) && ($_POST["Ter_Ins"] != null && $_POST["Ter_Ins"] != '')){ $Ter_Ins = $_POST["Ter_Ins"]; } else { $Ter_Ins = 'null'; }
                    if(isset($_POST["Auth_3"]) && $_POST["Auth_3"] != null){ $Auth_3 = $_POST["Auth_3"]; } else { $Auth_3 = 'null'; }
                    if(isset($_POST["Mem_n"]) && $_POST["Mem_n"] != null){ $Mem_n = $_POST["Mem_n"]; } else { $Mem_n = 'null'; }
                    if(isset($_POST["Grp_n"]) && $_POST["Grp_n"] != null){ $Grp_n = $_POST["Grp_n"]; } else { $Grp_n = 'null'; }
                    if(isset($_POST["Intake_Agmts"]) && $_POST["Intake_Agmts"] != null){ $Intake_Agmts = $_POST["Intake_Agmts"]; } else { $Intake_Agmts = 'null'; }
                    if(isset($_POST["Table_name"]) && $_POST["Table_name"] != null){ $Table_name = $_POST["Table_name"]; } else { $Table_name = 'null'; }
                    if(isset($_POST["Thi_Ins"]) && $_POST["Thi_Ins"] != null){ $Thi_Ins = $_POST["Thi_Ins"]; } else { $Thi_Ins = 'null'; }
                    if(isset($_POST["active"]) && $_POST["active"] != null){ $active = 1; } else { $active = '0'; }
                    if(isset($_POST["admision_date"]) && $_POST["admision_date"] != null){ $admision_date = $_POST["admision_date"]; } else { $admision_date = 'null'; }
                    
                                }
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                $tabla = 'tbl_referral';
                
                $sql = 'SELECT Ref_id FROM tbl_referral WHERE Ref_id = \''.$Ref_id.'\';';

                $resultado = ejecutar($sql,$conexion);

                    $i=0;
                    while ($row=mysqli_fetch_array($resultado)) {	

                        $Ref_id_val = $row['Ref_id'];                                           

                    $i++;        
                    } 
                
                
                
                
                if(isset($Ref_id_val) && $_POST['accion'] == 'insertar'){
                
                $json_resultado['repetido'] = 'si';
                
                } else {
                
                if($pcp != 'null'){
                    $sql = 'SELECT Name FROM physician WHERE Phy_id = \''.$PCP.'\';';

                    $resultadopcp = ejecutar($sql,$conexion);

                    $pcp_name;
                    while ($row_pcp=mysqli_fetch_array($resultadopcp)) {	

                        $pcp_name = $row_pcp['Name'];
   
                    }
                }
                 
                                
                
                
                if($Ref_Physician != 'null'){
                    $sql = 'SELECT Name FROM physician WHERE Phy_id = \''.$Ref_Physician.'\';';

                    $resultadoRef_Physician = ejecutar($sql,$conexion);

                    $Ref_Physician_name;
                    while ($row_Ref_Physician_name=mysqli_fetch_array($resultadoRef_Physician)) {	

                        $Ref_Physician_name = $row_Ref_Physician_name['Name'];

                    }
                }        
                 
                
                if($Pri_Ins != 'null'){
                    $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$Pri_Ins.'\';';

                    $resultadoPri_Ins = ejecutar($sql,$conexion);

                    $Pri_Ins_insurance;
                    while ($row_Pri_Ins=mysqli_fetch_array($resultadoPri_Ins)) {	

                        $Pri_Ins_insurance = $row_Pri_Ins['insurance'];

                    }
                }
                if($Sec_INS != 'null'){
                    $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$Sec_INS.'\';';

                    $resultadoSec_INS = ejecutar($sql,$conexion);

                    $Sec_INS_insurance;
                    while ($row_Sec_INS=mysqli_fetch_array($resultadoSec_INS)) {	

                        $Sec_INS_insurance = $row_Sec_INS['insurance'];

                    }
                }
                if($Ter_Ins != 'null'){
                    $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$Ter_Ins.'\';';

                    $resultadoTer_Ins = ejecutar($sql,$conexion);

                    $Ter_Ins_insurance;
                    while ($row_Ter_Ins=mysqli_fetch_array($resultadoTer_Ins)) {	

                        $Ter_Ins_insurance = $row_Ter_Ins['insurance'];

                    }
                }
                
                if($DOB!='null')
                    $date_dob = date("Y-m-d",strtotime($DOB));
                else
                    $date_dob = 'null';
                
                if($Intake_Agmts!='null')
                    $date_Intake_Agmts = date("Y-m-d",strtotime($Intake_Agmts));
                else
                    $date_Intake_Agmts = 'null';
                
                if($admision_date!='null')
                    $admision_date_ = date("Y-m-d",strtotime($admision_date));
                else
                    $admision_date_ = 'null';
                
                if($accion == 'insertar'){
                 $insert = " INSERT INTO tbl_referral(Last_name,First_name,Ref_id,Sex,DOB,Guardian,Social,Address,City,State,Zip,county,E_mail,Phone,Cell,PCP,PCP_NPI,Ref_Physician,Phy_NPI,Pri_Ins,Auth,Sec_INS,Auth_2,Ter_Ins,Auth_3,`Mem_#`,`Grp_#`,Intake_Agmts,Table_name,Thi_Ins,active,admision_date,type_patient,convertido,id_carriers) VALUES ('".$Last_name."','".$First_name."','".$Ref_id."','".$Sex."','".$date_dob."','".$Guardian."','".$Social."','".$Address."','".$City."','".$State."','".$Zip."','".$county."','".$E_mail."','".$Phone."','".$Cell."','".$pcp_name."','".$PCP_NPI."','".$Ref_Physician_name."','".$Phy_NPI."','".$Pri_Ins_insurance."','".$Auth."','".$Sec_INS_insurance."','".$Auth_2."','".$Ter_Ins_insurance."','".$Auth_3."','".$Mem_n."','".$Grp_n."','".$date_Intake_Agmts."','".$Table_name."','".$Thi_Ins."','".$active."',NOW(),'".$type_patient."',0,'".$id_carriers."');";
                 $insert = str_replace("'null'", "null", $insert);
                 $insert = str_replace("''", "null", $insert);
                
                
                $resultado = ejecutar($insert,$conexion); 
                
                $sql  = "SELECT max(id_referral) as identificador FROM tbl_referral;";
                $resultado = ejecutar($sql,$conexion); 
                $j = 0;      
                $id_referral = '';
                while($datos = mysqli_fetch_assoc($resultado)) {            
                    $id_referral = $datos['identificador'];
                    $j++;
                }
                
                $insertAudit = "INSERT INTO tbl_referral_audit (id_referral,date_register,date_converter,user_converter) "
                        . "VALUES ('".$id_referral."','".date('Y-m-d')."',null,null)";
                $resultado = ejecutar($insertAudit,$conexion);
                
                //guardo auditoria en tbl_audit_generales
                $fecha=date('Y-m-d H:i:s');
                $audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$id_referral."','1','".$fecha."')";
                ejecutar($audit_general,$conexion);
                
                }
                
                
                if($accion == 'modificar' || ($accion == 'convertir' && $_POST['update']!= 'no')){
                
                    $update = " UPDATE tbl_referral SET Last_name = '".$Last_name."',First_name = '".$First_name."',Ref_id = '".$Ref_id."',Sex = '".$Sex."',DOB = '".$date_dob."',Guardian = '".$Guardian."',Social = '".$Social."',Address = '".$Address."',City = '".$City."',State = '".$State."',Zip = '".$Zip."',county = '".$county."',E_mail = '".$E_mail."',Phone = '".$Phone."',Cell = '".$Cell."',PCP = '".$pcp_name."',PCP_NPI = '".$PCP_NPI."',Ref_Physician = '".$Ref_Physician_name."',Phy_NPI = '".$Phy_NPI."',Pri_Ins = '".$Pri_Ins_insurance."',Auth = '".$Auth."',Sec_INS = '".$Sec_INS_insurance."',Auth_2 = '".$Auth_2."',Ter_Ins = '".$Ter_Ins_insurance."',Auth_3 = '".$Auth_3."',`Mem_#` = '".$Mem_n."',`Grp_#` = '".$Grp_n."',Intake_Agmts = '".$date_Intake_Agmts."',Table_name = '".$Table_name."',Thi_Ins = '".$Thi_Ins."',active = '".$active."',id_carriers = '".$id_carriers."' WHERE ".$where;
                    $update = str_replace("'null'", "null", $update);
                    $update = str_replace("''", "null", $update);
                    $resultado = ejecutar($update,$conexion); 
                    
                    if(isset($_POST['id_patients'])){
                        //actualizo barcode                
                        $barcode = "UPDATE  tbl_barcodes SET barcode=".$_POST["barcode"]." WHERE id_relation=".$_POST['id_patients']." AND id_type_person=1";
                        ejecutar($barcode,$conexion);
                    }
                }
                
                if($accion == 'eliminar'){
                
                    $delete = ' DELETE FROM tbl_referral WHERE '.$where.';';
                    $resultado = ejecutar($delete,$conexion);                     
                
                }
                
                if($accion == 'convertir'){
                
                        $insertSelect = 'INSERT into patients (Last_name,First_name,Pat_id,Sex,DOB,Guardian,Social,Address,City,State,Zip,county,E_mail,Phone,Cell,PCP,PCP_NPI,Ref_Physician,Phy_NPI,Pri_Ins,Auth,Sec_INS,Auth_2,Ter_Ins,Auth_3,`Mem_#`,`Grp_#`,Intake_Agmts,active,admision_date,discharge_date,id_seguros_type_person)'
                        . '(SELECT Last_name,First_name,Ref_id,Sex,DOB,Guardian,Social,Address,City,State,Zip,county,E_mail,Phone,Cell,PCP,PCP_NPI,Ref_Physician,Phy_NPI,Pri_Ins,Auth,Sec_INS,Auth_2,Ter_Ins,Auth_3,`Mem_#`,`Grp_#`,Intake_Agmts,active,date(now()),\'0000-00-00\',type_patient FROM tbl_referral WHERE id_referral = \''.$_POST['id_referral'].'\')';
                        $resultado = ejecutar($insertSelect,$conexion);  
                    
                        $updateReferral = 'UPDATE tbl_referral SET convertido = 1 WHERE id_referral = \''.$_POST['id_referral'].'\'';
                        $resultadoReferral = ejecutar($updateReferral,$conexion); 
                        
                        $updateReferralAudit = 'UPDATE tbl_referral_audit SET date_converter = \''.date('Y-m-d').'\', user_converter = '.$_SESSION['user_id'];
                        $resultadoReferralAudit = ejecutar($updateReferralAudit,$conexion); 
                    /* Guardar en auditoria y probar*/
                        
                    //consulto el ultimo id
                        
                        $sql_barcode  = "SELECT max(id) as identificador FROM patients;";
                        $resultado_barcode = ejecutar($sql_barcode,$conexion); 
                        $j = 0;      
                        $id_patiens_barcode = '';
                        while($datos_barcode = mysqli_fetch_assoc($resultado_barcode)) {            
                            $id_patiens_barcode = $datos_barcode['identificador'];
                            $j++;
                        }

                        //agregando barcode                
                        $barcode = "INSERT INTO tbl_barcodes (id_relation,barcode,id_type_person) "
                            . "VALUES ('".$id_patiens_barcode."','".$_POST["barcode"]."',1)";
                        ejecutar($barcode,$conexion);

                        //inserto en patients copy
                        $patienst_copy = "INSERT INTO patients_copy (id,Last_name,First_name,Pat_id) "
                            . "VALUES ('".$id_patiens_barcode."', '".$_POST['Last_name']."','".$_POST['First_name']."','".$_POST['Ref_id']."')";
                        ejecutar($patienst_copy,$conexion);
                
                }
                
                 if($resultado) {
                
                     $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';
                     
                 $json_resultado['mensaje'] = $mensaje_almacenamiento;
                 
                 if($_POST['accion'] == 'eliminar'){
                     $json_resultado['mensaje_data_table'] = '<h5><font color="#FE642E"><b>Eliminado</b></font></h5>';
                 }
             
              
                 } else {
                
                 $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
                    
                 } 
                 
                }
                 echo json_encode($json_resultado);                                  

?>