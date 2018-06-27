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

if (isset($_POST['accion']) && ($_POST['accion'] == 'entrada_seguros') && isset($_POST['val']) && isset($_POST['id'])) {
    $val = sanitizeString($conexion, $_POST['val']);
    $id = sanitizeString($conexion, $_POST['id']);
    $tipo_entrada = $id[0];
    $id = substr($id, 1, strlen($id) - 1);
    $query = '';
    if ($tipo_entrada == 'r')
        $query = "update insurance_prices set Rate_Unit=$val where Id=$id";
    elseif ($tipo_entrada == 'a')  
        $query = "update insurance_prices set Allowed=$val where Id=$id";
    elseif ($tipo_entrada == 'e')  
        $query = "update insurance_prices set Encounter=$val where Id=$id";
    if ($query !== '') ejecutar($query, $conexion);
}

if (isset($_POST['accion']) && ($_POST['accion'] == 'operacion_seguros')) {
    $id = sanitizeString($conexion, $_POST['id']);
    $op = sanitizeString($conexion, $_POST['op']);
    $json_resultado['op'] = $op;
    $query = '';
    if ($op == "S") {
        $cpt = sanitizeString($conexion, $_POST['cpt']);
        $dia = sanitizeString($conexion, $_POST['dia']);
        $mod = sanitizeString($conexion, $_POST['mod']);
        $rul = sanitizeString($conexion, $_POST['rul']);
        $rat = sanitizeString($conexion, $_POST['rat']);
        $all = sanitizeString($conexion, $_POST['all']);
        $ena = sanitizeString($conexion, $_POST['ena']);
        if ($id == "0") 
            $query = "insert into insurance_modifiers_prices(IdInsurance, IdCPT, IdDiag, IdModifier, IdRule, Rate_Unit, Allowed, Enabled) values('0', '$cpt', '$dia', '$mod', '$rul', '$rat', '$all', '$ena')";        
        else 
            $query= "update insurance_modifiers_prices set IdCPT='$cpt', IdDiag='$dia', IdModifier='$mod', IdRule='$rul', Rate_Unit='$rat', Allowed='$all', Enabled='$ena' where Id='$id'";           
    } elseif ($op == "D")
        $query = "delete from insurance_modifiers_prices where Id='$id'";     
    if ($query !== '') {
        ejecutar($query, $conexion);
        $json_resultado['query'] = $query; 
    } 
    if ($op == "S") {
        if ($id == "0") {
            $query = "select max(Id) from insurance_modifiers_prices";
            $result = ejecutar($query, $conexion);
            $id = mysqli_result($result, 0, 0);
        }
        $query = "select imp.Id, c.Discipline, c.cpt, dc.DiagCodeValue, im.Modifier, ir.Rule, imp.Rate_Unit, imp.Allowed, imp.Enabled from insurance_modifiers_prices imp inner join cpt c on c.ID = imp.IdCPT left join diagnosiscodes dc on dc.DiagCodeId = imp.IdDiag inner join insurance_modifiers im on im.Id = imp.IdModifier inner join insurance_rules ir on ir.Id = imp.IdRule where imp.Id='$id' order by imp.Id";
        $json_resultado['query'] = $query;
        $result = ejecutar($query, $conexion);
        $json_resultado['id'] =  mysqli_result($result, 0, 0);
        $json_resultado['dis'] =  mysqli_result($result, 0, 1);
        $json_resultado['cpt'] =  mysqli_result($result, 0, 2);
        $json_resultado['dia'] =  mysqli_result($result, 0, 3);
        $json_resultado['mod'] =  mysqli_result($result, 0, 4);
        $json_resultado['rul'] =  mysqli_result($result, 0, 5);
        $json_resultado['rat'] =  mysqli_result($result, 0, 6);
        $json_resultado['all'] =  mysqli_result($result, 0, 7);
        $json_resultado['ena'] =  mysqli_result($result, 0, 8);
    } elseif ($op == "D") {
        $json_resultado['id'] = $id;
    }
    echo json_encode($json_resultado);
    exit;
}

if($_POST['id_especial_prev'] == '')$_POST['id_especial_prev']='-';
$arrayEspecial = sanitizeString($conexion, $_POST['id_especial_f']);


$id_seguros = null;

                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modificación'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Eliminación'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = ' WHERE ID = '.sanitizeString($conexion, $_POST["id_seguros"]);
                
                } else {                                
                
                $id_seguros = null;
                $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }
             
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){                                                            
                    
                    if(isset($_POST["id_seguros"]) && $_POST["id_seguros"] != null){ $id_seguros = sanitizeString($conexion, $_POST["id_seguros"]); } else { $id_seguros = 'null'; }
                    if(isset($_POST["name"]) && $_POST["name"] != null){ $name = sanitizeString($conexion, $_POST["name"]); } else { $name = 'null'; }
                    if(isset($_POST["address"]) && $_POST["address"] != null){ $address = sanitizeString($conexion, $_POST["address"]); } else { $address = 'null'; }
                    if(isset($_POST["city"]) && $_POST["city"] != null){ $city = sanitizeString($conexion, $_POST["city"]); } else { $city = 'null'; }
                    if(isset($_POST["state"]) && $_POST["state"] != null){ $state = sanitizeString($conexion, $_POST["state"]); } else { $state = 'null'; }
                    if(isset($_POST["zip"]) && $_POST["zip"] != null){ $zip = sanitizeString($conexion, $_POST["zip"]); } else { $zip = 'null'; }
                    if(isset($_POST["phone"]) && $_POST["phone"] != null){ $phone = sanitizeString($conexion, $_POST["phone"]); } else { $phone = 'null'; }
                    if(isset($_POST["fax"]) && $_POST["fax"] != null){ $fax = sanitizeString($conexion, $_POST["fax"]); } else { $fax = 'null'; }
//                    if(isset($_POST["id_reporting_system"]) && $_POST["id_reporting_system"] != null){ $id_reporting_system = sanitizeString($conexion, $_POST["id_reporting_system"]); } else { $id_reporting_system = 'null'; }
                    if(isset($_POST["provider"]) && $_POST["provider"] != null){ $provider = sanitizeString($conexion, $_POST["provider"]); } else { $provider = 'null'; }
                    if(isset($_POST["id_type_provider"]) && $_POST["id_type_provider"] != null){ $id_type_provider = sanitizeString($conexion, $_POST["id_type_provider"]); } else { $id_type_provider = 'null'; }
                    if(isset($_POST["id_claim_ind"]) && $_POST["id_claim_ind"] != null){ $id_claim_ind = sanitizeString($conexion, $_POST["id_claim_ind"]); } else { $id_claim_ind = 'null'; }
                    if(isset($_POST["submitter_id"]) && $_POST["submitter_id"] != null){ $submitter_id = sanitizeString($conexion, $_POST["submitter_id"]); } else { $submitter_id = 'null'; }
                    if(isset($_POST["id_edi_gateway"]) && $_POST["id_edi_gateway"] != null){ $id_edi_gateway = sanitizeString($conexion, $_POST["id_edi_gateway"]); } else { $id_edi_gateway = 'null'; }
                    if(isset($_POST["payer_id"]) && $_POST["payer_id"] != null){ $payer_id = sanitizeString($conexion, $_POST["payer_id"]); } else { $payer_id = 'null'; }                    
                    if(isset($_POST["id_carriers"]) && $_POST["id_carriers"] != null){ $id_carriers = sanitizeString($conexion, $_POST["id_carriers"]); } else { $id_carriers = 'null'; }                    
                     
                    $_POST['tipo_seguros_cadena'] = substr (sanitizeString($conexion, $_POST['tipo_seguros_cadena']), 0, strlen($_POST['tipo_seguros_cadena']) - 1);                    
                    $tipo_seguros_array = explode('|',sanitizeString($conexion, $_POST['tipo_seguros_cadena']));
                    
                }
          
          
            if(isset($name) && $name != null) {

                $sql = 'SELECT insurance FROM seguros WHERE insurance = \''.$name.'\';';

                $resultado = ejecutar($sql,$conexion);

                $i=0;
                while ($row=mysqli_fetch_array($resultado)) {	

                    $name_val = $row['insurance'];                                           

                $i++;        
                } 

            }
            
            if(isset($name_val) && $_POST['accion'] == 'insertar'){
                
                $json_resultado['repetido'] = 'si';
                
            } else {
            
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = sanitizeString($conexion, $_POST["accion"]); } else { $accion = 'null'; }
                
                $tablas = 'seguros';                              
                 
                if($accion == 'insertar'){
                    
                    $insert =" INSERT into seguros (insurance,address,city,state,zip,phone,fax,provider,id_type_provider,id_claim_ind,submitter_id,id_edi_gateway,payer_id,id_carriers)                     
                    values ('".$name."','".$address."','".$city."','".$state."','".$zip."','".$phone."','".$fax."','".$provider."','".$id_type_provider."','".$id_claim_ind."','".$submitter_id."','".$id_edi_gateway."','".$payer_id."','".$id_carriers."');";                  
                    $insert = str_replace("'null'", "null", $insert);
                    $resultado = ejecutar($insert,$conexion);                     
                    
                    
                    $sql = 'SELECT max(ID) as id_seguros FROM seguros;';
                    $resultado = ejecutar($sql,$conexion);
                    $row=mysqli_fetch_array($resultado);
                    $id_seguros = $row['id_seguros'];   
                    
                    $sql = "update insurance_prices set IdInsurance=$id_seguros where IdInsurance=0"; 
                    ejecutar($sql,$conexion);
                } 
                if($accion == 'modificar'){
                    
                    $update =" UPDATE seguros SET insurance = '".$name."', address = '".$address."', city = '".$city."', state = '".$state."', zip = '".$zip."', phone = '".$phone."', fax = '".$fax."', provider = '".$provider."', id_type_provider = '".$id_type_provider."', id_claim_ind = '".$id_claim_ind."', submitter_id = '".$submitter_id."', id_edi_gateway = '".$id_edi_gateway."', payer_id = '".$payer_id."', id_carriers = '".$id_carriers."' ".$where;
                    $update = str_replace("'null'", "null", $update);
                    $resultado = ejecutar($update,$conexion);                     
                    
                }
                    
                if($accion == 'modificar' || $accion == 'eliminar'){
                    
                    $id_seguros = sanitizeString($conexion, $_POST["id_seguros"]);
                    
                    
                    $sql = 'SELECT id_seguros_table FROM tbl_seguros_table WHERE id_seguros = '.$id_seguros.';';
                    $resultado = ejecutar($sql,$conexion);
                    $reporte_id_seguros_table = array();
                                                            
                    $y = 0;      
                    while($datos = mysqli_fetch_assoc($resultado)) {            
                        $reporte_id_seguros_table[$y] = $datos;
                        $y++;
                    }                 
                
                
                    $j=0;
                    while (isset($reporte_id_seguros_table[$j])){
                
                        $delete =" DELETE FROM tbl_seguros_progress WHERE id_seguros_table = ".$reporte_id_seguros_table[$j]['id_seguros_table'].";";
                        $resultado = ejecutar($delete,$conexion);                          
                    
                        $j++;
                    }  
                    //#########################################################################################                    
                    
                    $j=0;
                    while (isset($reporte_id_seguros_table[$j])){
                
                        $delete =" DELETE FROM tbl_seguros_prescription_cp_days WHERE id_seguros_table = ".$reporte_id_seguros_table[$j]['id_seguros_table'].";";
                        $resultado = ejecutar($delete,$conexion);                          
                    
                        $j++;
                    }                                   
                    
                    $j=0;
                    while (isset($reporte_id_seguros_table[$j])){
                
                        $delete =" DELETE FROM tbl_seguros_auth_treat_auth_days WHERE id_seguros_table = ".$reporte_id_seguros_table[$j]['id_seguros_table'].";";
                        $resultado = ejecutar($delete,$conexion);                          
                    
                        $j++;
                    }                                                      
                                
                    $j=0;
                    while (isset($reporte_id_seguros_table[$j])){
                
                        $delete =" DELETE FROM tbl_seguros_auth_treat_visit_remain WHERE id_seguros_table = ".$reporte_id_seguros_table[$j]['id_seguros_table'].";";
                        $resultado = ejecutar($delete,$conexion);                          
                    
                        $j++;
                    }
                    //##################################################################################
                    $delete =" DELETE FROM tbl_seguros_table WHERE id_seguros = ".$id_seguros.";";
                    $resultado = ejecutar($delete,$conexion);                     
                       
                    
                    $selectRelTipoSeguros = 'SELECT id_rel_tipo_seguros FROM tbl_seguros_rel_tipo_seguros WHERE id_seguros = '.$id_seguros.';';
                    $resultado = ejecutar($selectRelTipoSeguros,$conexion);
                                                            
                    $y = 0;      
                    while($datos = mysqli_fetch_assoc($resultado)) {            
                         
                        $sqlLineOfBussines = 'DELETE FROM tbl_seguros_line_of_business_age WHERE id_seguros_rel_tipo_seguros = '.$datos['id_rel_tipo_seguros'];
                        $y++;
                    } 
                    
                    $sql = 'DELETE FROM tbl_seguros_rel_tipo_seguros WHERE id_seguros = '.$id_seguros.';';
                    $resultado = ejecutar($sql,$conexion);     
                    
                    $sqlSelectSpecial = 'SELECT * FROM tbl_seguro_special_relation WHERE id_seguro = '.$id_seguros.';';
                    $resultadoSelectSpecial = ejecutar($sqlSelectSpecial,$conexion);
                                                                                
                    while($datosSelectSpecial = mysqli_fetch_assoc($resultadoSelectSpecial)) {            
                        $sqlDeleteRelation = 'DELETE FROM tbl_seguro_special_relation_discipline WHERE id_seguro_special_relation = '.$datosSelectSpecial['id_seguro_special_relation'];
                        $resultado = ejecutar($sqlDeleteRelation,$conexion);
                    }
                    $sqlSpecial = 'DELETE FROM tbl_seguro_special_relation WHERE id_seguro = '.$id_seguros.';';                    
                    $resultado = ejecutar($sqlSpecial,$conexion); 
                    
                }    
                
                if($accion == 'eliminar'){                                                                           
                    $delete = " DELETE FROM seguros ".$where.';';
                    $resultado = ejecutar($delete,$conexion);                     
                }
               
                if($accion == 'insertar' || $accion == 'modificar'){
                    
                    while ($datos = current($_POST) || current($_POST) != null) {
                                   
                        $pos_key = strpos(key($_POST),'|');                               
                        if ($pos_key !== false) {
                            
                            if($_POST[key($_POST)] != null) {
    
                                $number_visits = null;
                                $array_task = explode('/', key($_POST));
                                $array_person_discipline = explode('|',$array_task[1]);                            
    
                               switch ($array_task[0]) {
                                   
                                   case 'prescription':
                                       $type_task = 1;
                                       $pre_visit = str_replace('prescription', 'prescription_cp', key($_POST));
                                       $pre_visit = str_replace('|', '-', $pre_visit);                                       
                                       $pre_visit = str_replace('/', '-', $pre_visit);
                                       $pre_cp_day = sanitizeString($conexion, $_POST[$pre_visit]);
                                       break;
                                   case 'auth_eval':
                                       $type_task = 2;
                                       break;
                                   case 'doctor_sig':
                                       $type_task = 3;
                                       break;  
                                   case 'auth_treat':
                                       $type_task = 4;
                                       $au_days = str_replace('auth_treat', 'auth_treat_au', key($_POST));
                                       $au_days = str_replace('|', '-', $au_days);                                       
                                       $au_days = str_replace('/', '-', $au_days);
                                       $auth_tre_au_day = sanitizeString($conexion, $_POST[$au_days]);
                                       
                                       $au_visit = str_replace('auth_treat', 'auth_treat_vis', key($_POST));
                                       $au_visit = str_replace('|', '-', $au_visit);                                       
                                       $au_visit = str_replace('/', '-', $au_visit);
                                       $auth_tre_visit = sanitizeString($conexion, $_POST[$au_visit]);
                                       break;                                     
                                   case 'progress':
                                       $type_task = 5;
                                       $pos_visit = str_replace('progress', 'progress_visits', key($_POST));
                                       $pos_visit = str_replace('|', '-', $pos_visit);                                       
                                       $pos_visit = str_replace('/', '-', $pos_visit);
                                       $number_visits = sanitizeString($conexion, $_POST[$pos_visit]);
                                       break;                                                                                                                                              
                                }
                                
                                
                                $sqlFindTypePerson = "SELECT * FROM tbl_seguros_type_person WHERE seguros_type_person like '".$array_person_discipline[0]."%'";
                                $resultadoFindTypePerson = ejecutar($sqlFindTypePerson,$conexion);
                                $row=mysqli_fetch_array($resultadoFindTypePerson);
                                $type_person = $row['id_seguros_type_person'];   
                                
                                $discipline = $array_person_discipline[1];
                                                             
                               
                                $insert =" INSERT into tbl_seguros_table (id_seguros,id_seguros_type_task,id_seguros_type_person,discipline)
                                values ('".$id_seguros."','".$type_task."','".$type_person."','".$discipline."');";                  
                                $resultado = ejecutar($insert,$conexion); 
                                

                                $sql = 'SELECT max(id_seguros_table) as id_seguros_table FROM tbl_seguros_table;';
                                $resultado = ejecutar($sql,$conexion);
                                $row=mysqli_fetch_array($resultado);
                                $id_seguros_table = $row['id_seguros_table'];              


                                if($number_visits != null){

                                    $insert =" INSERT into tbl_seguros_progress (id_seguros_table,visits)
                                    values ('".$id_seguros_table."','".$number_visits."');";                  
                                    $resultado = ejecutar($insert,$conexion);                

                                }
                                
                                if($pre_cp_day != null){

                                    $insertCP =" INSERT into tbl_seguros_prescription_cp_days (id_seguros_table,cp_days_left)
                                    values ('".$id_seguros_table."','".$pre_cp_day."');";                  
                                    $resultado = ejecutar($insertCP,$conexion);                

                                }
                                if($auth_tre_au_day != null){        
                                    $insertCP =" INSERT into tbl_seguros_auth_treat_auth_days (id_seguros_table,auth_days_left)
                                    values ('".$id_seguros_table."','".$auth_tre_au_day."');";                  
                                    $resultado = ejecutar($insertCP,$conexion); 
                                    
                                }
                                if($auth_tre_visit != null){
                                    $insertCP =" INSERT into tbl_seguros_auth_treat_visit_remain (id_seguros_table,visit_remain)
                                    values ('".$id_seguros_table."','".$auth_tre_visit."');";                  
                                    $resultado = ejecutar($insertCP,$conexion); 
                                }

                            }                            
                        }    
                        next($_POST);
                }                        
                
            $sqlDiscipline = "SELECT * FROM discipline";
            $resultadoDiscipline = ejecutar($sqlDiscipline,$conexion);
            $arrayDiscipline = array();
            $i=0;
            while ($row=mysqli_fetch_assoc($resultadoDiscipline)) {	
                $arrayDiscipline[$i] = $row;                     
                $i++;        
            }    
            $i=0;
            if(count($tipo_seguros_array)>0){
                while (isset($tipo_seguros_array[$i])){            
                    if($tipo_seguros_array[$i] != 'on') {
                        $sql = 'INSERT INTO tbl_seguros_rel_tipo_seguros (id_seguros,id_tipo_seguros) values ('.$id_seguros.','.$tipo_seguros_array[$i].');';
                        $resultado = ejecutar($sql,$conexion);        

                        $sqlRelTipoSeguros = 'SELECT max(id_rel_tipo_seguros) as id_rel_tipo_seguros FROM tbl_seguros_rel_tipo_seguros;';
                        $resultadoRelTipoSeguros = ejecutar($sqlRelTipoSeguros,$conexion);
                        $row=mysqli_fetch_array($resultadoRelTipoSeguros);
                        $id_rel_tipo_seguros = $row['id_rel_tipo_seguros'];

                        $r= 0;
                        while(isset($arrayDiscipline[$r])){
                            if(isset($_POST['age_min_'.$tipo_seguros_array[$i].'_'.$arrayDiscipline[$r]['DisciplineId']])){
                                if($_POST['age_min_'.$tipo_seguros_array[$i].'_'.$arrayDiscipline[$r]['DisciplineId']] != '-'){
                                    if($_POST['age_min_'.$tipo_seguros_array[$i].'_'.$arrayDiscipline[$r]['DisciplineId']] != '' && $_POST['age_max_'.$tipo_seguros_array[$i].'_'.$arrayDiscipline[$r]['DisciplineId']] != ''){
                                        $sql = 'INSERT INTO tbl_seguros_line_of_business_age (id_seguros_rel_tipo_seguros,discipline,minimum_age,maximum_age) values ('.$id_rel_tipo_seguros.',\''.$arrayDiscipline[$r]['Name'].'\','.$_POST['age_min_'.$tipo_seguros_array[$i].'_'.$arrayDiscipline[$r]['DisciplineId']].','.$_POST['age_max_'.$tipo_seguros_array[$i].'_'.$arrayDiscipline[$r]['DisciplineId']].');';                        
                                        $resultado = ejecutar($sql,$conexion);        
                                    }
                                }                            
                            }
                            $r++;
                        }

                    }
                    $i++;
                }
            }            
            
            if($arrayEspecial != '-'){
                $g = 0;
                while(isset($arrayEspecial[$g])){
                    $sql = 'INSERT INTO tbl_seguro_special_relation (id_seguro,id_seguro_special) values ('.$id_seguros.','.$arrayEspecial[$g].');';                        
                    $resultado = ejecutar($sql,$conexion);

                    $selectIdMaxEspecial = "SELECT max(id_seguro_special_relation) as max_id FROM tbl_seguro_special_relation;";
                    $resultadoIdMaxEspecial = ejecutar($selectIdMaxEspecial,$conexion);
                    $row=mysqli_fetch_array($resultadoIdMaxEspecial);
                    $idMaxEspecial = $row['max_id'];

                    $sqlDiscipline = "SELECT * FROM discipline";
                    $resultadoDiscipline = ejecutar($sqlDiscipline,$conexion);
                    $arrayDiscipline = array();
                    $i=0;
                    while ($row=mysqli_fetch_assoc($resultadoDiscipline)) {	
                        $arrayDiscipline[$i] = $row;                     
                        $i++;        
                    } 
                    $q = 0;
                    while(isset($arrayDiscipline[$q])){
                        $sqlInsertRelationD = '';
                        if($_POST[$arrayEspecial[$g].'-'.$arrayDiscipline[$q]['Name']] == 1){
                            $sqlInsertRelationD = "INSERT INTO tbl_seguro_special_relation_discipline (id_seguro_special_relation,discipline) "
                            . " VALUES (".$idMaxEspecial.",'".$arrayDiscipline[$q]['Name']."');";
                            $resultado = ejecutar($sqlInsertRelationD,$conexion);
                        }
                        $q++;
                    }

                    $g++;

                }
            }            
            
        }
        
        if($resultado == 'OK') {                                                                                       
        
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
                 
                 //die;
        echo json_encode($json_resultado);                                  

?>