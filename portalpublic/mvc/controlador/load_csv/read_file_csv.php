<?php
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}
error_reporting(0);
$conexion = conectar();
$fp = fopen ( "../../../CSV/".$_GET['file'] , "r" );
$pos = 0;

$table = null;


if($_GET['file'] == "InsuranceAuthorizations_dlc.csv" || $_GET['file'] == "InsuranceAuthorizations_kqt.csv" || $_GET['file'] == "InsuranceAuthorizations_dlcquality.csv"){
    $table = 'insurance';
    $campos = "Insurance_name,
      Patient_name,
      Pat_id,
      `Auth_#`,
      CPT,
      Discipline,
      Auth_Start,
      Auth_thru,
      Visits_Auth,
      Visits_remen,
      Units,
      Units_remen,
      Company";
    $camposUpdate = array('Insurance_name',
      'Patient_name',
      'Pat_id',
      '`Auth_#`',
      'CPT',
      'Discipline',
      'Auth_Start',
      'Auth_thru',
      'Visits_Auth',
      'Visits_remen',
      'Units',
      'Units_remen',
      'Company');
    
    $camposUpdate_2 = array('Insurance_name',
      'Patient_name',
      'Pat_id',
      '`Auth_#`',
      'CPT',
      'Discipline',
      'Auth_Start',
      'Auth_thru',
      'Visits_Auth',
      'Visits_remen',
      'Units',
      'Units_remen',
      'Company');    
    if($_GET['file'] == "InsuranceAuthorizations_dlc.csv"){
        $company = "DLC";
    }else{
        if($_GET['file'] == "InsuranceAuthorizations_kqt.csv"){
            $company = "KQT";
        }else{
            $company = "DLCQUALITY";
        }
    }        
}else{
    if($_GET['file'] == "PatientList_dlc.csv" || $_GET['file'] == "PatientList_kqt.csv" || $_GET['file'] == "PatientList_dlcquality.csv"){
       
       if($_GET['file'] == "PatientList_dlc.csv"){
            $company = "DLC";
        }else{
            if($_GET['file'] == "PatientList_kqt.csv"){
                $company = "KQT";
            }else{
                $company = "DLCQUALITY";
            }
          }
        
        $update="UPDATE patients SET active = false WHERE Table_name='".$company."'"; 
        ejecutar($update,$conexion);        

        $table = 'patients';
        $campos = "Last_name,
          First_name,
          Pat_id,
          Sex,
          DOB,
          Guardian,
          Social,
          Address,
          City,
          Street,
          Zip,
          E_mail,
          Phone,
          Cell,
          Ref_Physician,
          Phy_NPI,
          Pri_Ins,
          Auth,
          Sec_INS,
          Auth_2,
          Ter_Ins,
          Auth_3,
          `Mem_#`,
          `Grp_#`,
          Intake_Agmts,
          Table_name";
        $camposUpdate = array('Last_name',
          'First_name',
          'Pat_id',
          'Sex',
          'DOB',
          'Guardian',
          'Social',
          'Address',
          'City',
          'Street',
          'Zip',
          'E_mail',
          'Phone',
          'Cell',
          'Ref_Physician',
          'Phy_NPI',
          'Pri_Ins',
          'Auth',
          'Sec_INS',
          'Auth_2',
          'Ter_Ins',
          'Auth_3',
          '`Mem_#`',
          '`Grp_#`',
          'Intake_Agmts',
          'Table_name');            
    }else{
        if($_GET['file'] == "CarePlans_kqt.csv" || $_GET['file'] == "CarePlans_dlc.csv" || $_GET['file'] == "CarePlans_dlcquality.csv"){
            
            $table = 'careplans';                            
            $campos = "Last_name,
            First_name,
            Patient_ID,
            Discipline,
            Completed,
            MD_signed,
            POC_due,
            Re_Eval_due,
            MD_Eval_signed,
            Company";
            $camposUpdate = array('Last_name',
            'First_name',
            'Patient_ID',
            'Discipline',
            'Completed',
            'MD_signed',
            'POC_due',
            'Re_Eval_due',
            'MD_Eval_signed',
            'Company');
            
            if($_GET['file'] == "CarePlans_dlc.csv"){
                $company = "DLC";
            }else{
                if($_GET['file'] == "CarePlans_kqt.csv"){
                    $company = "KQT";
                }else{
                    $company = "DLCQUALITY";
                }
            }            
        } else {
            
            if(strtolower($_GET['file']) == "treatments.csv"){
              
                $table = 'treatments';
                $campos = 'campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,license_number,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24';
                
                                           


                $sql_update = null;  
                $sql_insert = null;                  
                $insertar = null;
                $modificar = null;
                $ignorar = null;       
                $columnasError = null;
                
                
                $i=0;
                $y=0;
                $u=0;                
                $ignorada = 0;                
                while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) { 
                                        
                    if($i >= 1) {
                                                                        
                        
                        $sql_insert = 'INSERT INTO tbl_treatments ('.$campos.') VALUES (';
                        
                        $r=0;
                        $modificar='no';
                        $valor_where = null;
                        $valor_where_campo_3 = null;
                        $update_campo_15_17_18_19_20_21 = null;
                        while (isset($data[$r])){
                             
                            if($r == 2){
                                $valor_where_campo_3 = $data[$r];                             
                            }
                            if($r == 3){                                
                                $valor_where = $data[$r];                             
                            }
                            if($r == 15){
                                $update_campo_15_17_18_19_20_21 = ' campo_15 = \''.$data[$r].'\'';
                                $campo_15_csv = $data[$r];
                            }
                            if($r == 17){                                
                                $update_campo_15_17_18_19_20_21 .= ',campo_17= \''.$data[$r].'\'';
                                $campo_17_csv = $data[$r];
                            }    
                            
                            if($r == 18){                                
                                $update_campo_15_17_18_19_20_21 .= ',campo_18= \''.utf8_encode($data[$r]).'\'';
                                $campo_18_csv = $data[$r];
                            }   
                            
                            if($r == 19){                                
                                $update_campo_15_17_18_19_20_21 .= ',campo_19= \''.utf8_encode($data[$r]).'\'';
                                $campo_19_csv = $data[$r];
                            }   
                            
                            if($r == 20){                                
                                $update_campo_15_17_18_19_20_21 .= ',campo_20= \''.utf8_encode($data[$r]).'\'';
                                $campo_20_csv = $data[$r];
                            }                            
                            
                            
                            if($r == 21){                                
                                
                                $update_campo_15_17_18_19_20_21 .= ',campo_21= \''.utf8_encode($data[$r]).'\'';
                                $campo_21_csv = $data[$r];
                                
                                
                                $consulta = 'SELECT * FROM tbl_treatments WHERE campo_3 = '.$valor_where_campo_3.' AND campo_4 ='.$valor_where;  
                                $ejecucion_consulta = mysqli_query($conexion, $consulta);
                                $reporte_consulta_id = mysqli_fetch_assoc($ejecucion_consulta);
                                                                                                
                                if(isset($reporte_consulta_id)) {                        
                                    
                                    if($reporte_consulta_id['campo_15'] != $campo_15_csv || $reporte_consulta_id['campo_17'] != $campo_17_csv || $reporte_consulta_id['campo_18'] != $campo_18_csv || $reporte_consulta_id['campo_19'] != $campo_19_csv || $reporte_consulta_id['campo_20'] != $campo_20_csv || $reporte_consulta_id['campo_21'] != $campo_21_csv) {
                                        $modificar = 'si';
                                    $sql_update = "UPDATE tbl_treatments SET ".$update_campo_15_17_18_19_20_21." WHERE campo_3 = '".$valor_where_campo_3."' and campo_4 = '".$valor_where."';";                                  
                                    } else {
                                        $ignorar = 'si';                                    
                                    }
                                
                                } else {
                                    
                                    $insertar = 'si';                                                                                                         
                                        
                                }                                
                                
                                
                            }                               
                            

                            
                            if($data[$r] == '' || $data[$r] == ' ' || $data[$r] == '  ' || $data[$r] == '   '){
                                $sql_insert .= $data[$r] = 'null';
                            } else {               
                                if($r == 5){
                                    $data[$r] = str_replace('-','',$data[$r]);
                                    $data[$r] = str_replace('.','',$data[$r]);
                                    $data[$r] = rtrim($data[$r]);
                                }             
                                $sql_insert .= "'".utf8_decode($data[$r])."'";
                            }


                            if(isset($data[$r+1])){                            
                                $sql_insert .= ',';
                            } else {
                                $sql_insert .= ');';
                            }                               

                            $r++;
                        }                        
                          
                        
                        if($insertar == 'si'){                            
                            ejecutar($sql_insert,$conexion);
                            $insertar = null;
                            $y++;     
                              
                             
                        $consulta = 'SELECT * FROM tbl_treatments WHERE campo_4 ='.$valor_where;  
                        $ejecucion_consulta = mysqli_query($conexion, $consulta);
                        $reporte_consulta_id_row = mysqli_fetch_assoc($ejecucion_consulta);   
                                                                                             
                        $sql_insert_rows = 'INSERT INTO tbl_status_treatments (id_treatments,id_description_status_treatments) values ('.$reporte_consulta_id_row['id_treatments'].',2);';                                
                        ejecutar($sql_insert_rows,$conexion);
                           
                        }                         
                        
                        if($modificar == 'si'){                                                               
                            ejecutar($sql_update,$conexion);                                                       
                            $modificar = null;
                            $u++;     
                            
                            
                        $consulta = 'SELECT * FROM tbl_treatments WHERE campo_4 ='.$valor_where;  
                        $ejecucion_consulta = mysqli_query($conexion, $consulta);
                        $reporte_consulta_id_row = mysqli_fetch_assoc($ejecucion_consulta);  
                        
                        $sql_insert_rows = 'INSERT INTO tbl_status_treatments (id_treatments,id_description_status_treatments) values ('.$reporte_consulta_id_row['id_treatments'].',3);';                                    
                        ejecutar($sql_insert_rows,$conexion);                            
                            
                        } 
                        
                        if($ignorar == 'si'){
                            $ignorada++;
                            $ignorar = null;
                        }                                                 
                                                    
                    }
                  
                    $i++;
                } 
                
            }            
        }                                
    }    
}

//$sql = "INSERT INTO ".$table."(".$campos.") VALUES (";
$i = 1;
$t = 0;
while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
    if($i > 4){    
        $pos = 0; 
        $last_name = '';
        $first_name = ''; 
        $pat_id = '';   
            $sql = 'INSERT INTO '.$table.'('.$campos.') VALUES (';
        $x = 0;
        $Visits_remen = '';
        $insertarCareplans = 'Si';
            foreach($data as $row) {
              
        //echo 'Columna'.$pos.'='.$row.'<br/>';
            if($table == 'careplans'){
                if($pos == 0){
                    list($last_name,$second_part_chain) = explode(', ',$row);
                    $patients = explode(' ',$second_part_chain);                                                
                    if(count($patients) == 2){                            
                        list($first_name,$pat_id) = explode(' ',$second_part_chain);
                        $sql .= '\''.$last_name.'\',\''.$first_name.'\',\''.$pat_id.'\'';
                    }else{
                        $p = 0;
                        while(isset($patients[$p])){
                            if(($p + 1) == count($patients)){
                                $pat_id = $patients[$p];
                            }else{
                                $first_name .= $patients[$p].' ';
                            }                                
                            $p++;    
                        }
                        $sql .= '\''.$last_name.'\',\''.$first_name.'\',\''.$pat_id.'\'';
                    }
                    $valuesCampos[$x] = $last_name;
                    $valuesCampos[$x+1] = $first_name;
                    $valuesCampos[$x+2] = $pat_id;
                    $x = 2;
                    //echo $sql;
                    //die; 
                }else
                if($pos == 1){
                	if($row != ''){
                		$discipline = explode(' ',$row);
                		$sql .= ',\''.$discipline[0].'\'';
                    		$valuesCampos[$x] = $discipline[0];
                	}
                }else
                if(($pos == 2 || $pos == 3 || $pos == 4 || $pos == 5)){
                    if($row != ''){
                        list($month,$day,$year) = explode('/',$row);
                        $row = $year.'-'.$month.'-'.$day;
                        $sql .= ',\''.$row.'\'';
                        $valuesCampos[$x] = $row;                        
                    }else{
                        $sql .= ',\'0001-01-01\'';
                        $valuesCampos[$x] = '0001-01-01';
                    }                    
                                        
                }else
                if($pos == 6){
                    if($row != ''){
                        $row = 1;                    
                    }else{                        
                        $insertarCareplans = 'No';
                    }                    
                    $sql .= ',\''.$row.'\'';
                    $valuesCampos[$x] = $row;
                }else{
                    $sql .= ',\''.$row.'\'';
                    $valuesCampos[$x] = $row;                                    
                }
                
            }else{
                if($table == 'insurance'){
                    if($pos == 0){
                        $sql .= '\''.$row.'\'';
                        $valuesCampos[$x] = $row;
                    }else                    
                    if($pos == 1){
                        list($last_name,$first_name) = explode(',',$row);
                        $sql .= ',\''.$row.'\'';
                        $valuesCampos[$x] = $row;                        
                    }else
                    if($pos == 2){
                        $pat_id = $row;
                        $sql .= ',\''.$row.'\'';
                        $valuesCampos[$x] = $row;
                    }else
                    if($pos == 6 or $pos == 7){
                        if($row != ''){
                            list($month,$day,$year) = explode('/',$row);
                            $row = $year.'-'.$month.'-'.$day;
                            $sql .= ',\''.$row.'\'';
                            $valuesCampos[$x] = $row;
                                                }else{
                                                       $sql .= ',\'0001-01-01\'';
                                                      $valuesCampos[$x] = '0001-01-01';
                                                }
                    }else{                        
                        if($pos == 9){                            
                            $sql .= ',\''.$row.'\'';                                            
                            if($row == '' || $row == ' ' || $row == null){
                                $Visits_remen = 0;
                            }else{
                                $Visits_remen = $row;
                            }
                            $valuesCampos[$x] = $Visits_remen;                            
                        }else{
                            $sql .= ',\''.$row.'\'';                
                            $valuesCampos[$x] = $row;
                        }                                                
                    }                                        
                }else{
                    if($table == 'patients'){
                        $valuesCampos[$x] = $row;                    
                        if($pos == 0){
                            $sql .= '\''.$row.'\'';
                            $last_name = $row;                            
                        }else                    
                        if($pos == 1){
                            $sql .= ',\''.$row.'\'';
                            $first_name = $row;
                        }else
                        if($pos == 2){
                            $sql .= ',\''.$row.'\'';
                            $pat_id = $row;
                        }else{
                            if(($pos == 4 || $pos == 24)){
                                                                if($row != ''){
                                                                    list($month,$day,$year) = explode('/',$row);
                                                                    $row = $year.'-'.$month.'-'.$day;
                                                                    $sql .= ',\''.$row.'\'';
                                                                    $valuesCampos[$x] = $row;
                                                                }else{
                                                                    $sql .= ',\'0001-01-01\'';
                                                                $valuesCampos[$x] = '0001-01-01';                                                                  
                                                                }
                                
                            }else{
                                if($pos == 16){
                                    //$Pri_Ins = $row;
                                    $sql .= ',\''.$row.'\'';
                                }else{
                                    $sql .= ',\''.$row.'\'';
                                }
                            }
                                                        
                        }
                    
                        
                        
                    }
                }
            }
                    
            $pos++;
            $x++;
            }  
           
                 
        if(($table == 'careplans') && $pos == 7){            
            $sqlSelect = "SELECT * FROM ".$table." WHERE ";
            $j = 0;
            $valuesCampos[$x] = $company;                                             
            while(isset($camposUpdate[$j])){
                //if($j == 2 || $j == 3 || $j == 6 || $j == 7 || $j == 9){
                //    if($j == 2)
                //        $whereSelect = $camposUpdate[$j]." = '".$valuesCampos[$j]."'"; 
                //    else
                //        $whereSelect .= " AND ".$camposUpdate[$j]." = '".$valuesCampos[$j]."'";
                //}
                                                                
                if($j == 2 || $j == 3 || $j == 6 || $j == 9){
                    if($j == 2)
                        $whereVerificar = $camposUpdate[$j]." = '".$valuesCampos[$j]."'"; 
                    else
                        $whereVerificar .= " AND ".$camposUpdate[$j]." = '".$valuesCampos[$j]."'";        
                }
                $j++;
            }                

                $selectVerificar = "SELECT * FROM ".$table." WHERE ".$whereVerificar." AND status = 1;";
               $resultadoVerificar = ejecutar($selectVerificar,$conexion);                    
               $row_cnt = mysqli_num_rows($resultadoVerificar);
              
                
                                //######################################################################################
                                // If pat_id, discipline, poc_due and company are equals some register, then i modify
                                //######################################################################################
                if($row_cnt > 0){
                   $update="UPDATE ".$table." SET Completed = '".$valuesCampos[4]."', MD_signed = '".$valuesCampos[5]."', Re_Eval_due = '".$valuesCampos[7]."', MD_Eval_signed = '".$valuesCampos[8]."' WHERE ".$whereVerificar." AND status = 1
                   ;"; 
                    ejecutar($update,$conexion);
                                        $u++;
                }else{      
                        $whereVerificarMaxDate = str_replace("AND POC_due = '".$valuesCampos[6]."'", "" , $whereVerificar);
                        //echo "POC_due '= ".$valuesCampos[6]."'";
                        //echo $whereVerificarMaxDate;                                        
                        $sqlMaxDate = "SELECT max(POC_due) as maxDate FROM ".$table." WHERE ".$whereVerificarMaxDate." AND status = 1;"; 
                        $resultadoVerificarMaxDate = ejecutar($sqlMaxDate,$conexion);                    
                        while ($row=mysqli_fetch_array($resultadoVerificarMaxDate)){ 
                            $maxDate = $row['maxDate'];
                        }                                        
                        if($valuesCampos[6] > $maxDate  || $maxDate == null){
                            if($insertarCareplans == 'Si'){
                                $update="UPDATE ".$table." SET status = 0 WHERE ".$whereVerificarMaxDate." AND status = 1;"; 
                                ejecutar($update,$conexion);
                                $sql .= ',\''.$company.'\');';
                                ejecutar($sql,$conexion);    
                            }else{
                                $ignorada++;
                            }                                                                                                                 
                            $y++;                                    
                        }else{
                            $ignorada++;
                        }                                                                           
                } 
                
            //}else{
                //$sqlUpdate = " UPDATE ".$table." SET Completed = '".$valuesCampos[4]."', MD_signed = '".$valuesCampos[5]."'";
                //$sqlUpdate = $sqlUpdate.' WHERE '.$whereSelect.';';
                //ejecutar($sqlUpdate,$conexion);
                //$u++;
            //}        
            
        }else{
            if(($table == 'insurance') && $pos == 12){
                $sqlSelect = "SELECT * FROM ".$table." WHERE ";
                $j = 0;
                $valuesCampos[$x] = $company;                  
                $h=0;
                while($camposUpdate[$j] != null){
                    if($j == 0 || $j == 2 || $j == 3 || $j == 4 || $j == 5 || $j == 12){
                        if($j == 0) {
                            $whereSelect = $camposUpdate[$j]." = '".$valuesCampos[$j]."'"; 
                            
                            if($camposUpdate[$j] != 'Insurance_name') {
                                $whereSelect_no_insurance_name = $camposUpdate[$j]." = '".$valuesCampos[$j]."'"; 
                            }
                            
                            
                            
                        }
                        else {
                            
                            
                            $whereSelect .= " AND ".$camposUpdate[$j]." = '".$valuesCampos[$j]."'";
                            
                            if($camposUpdate[$j] != 'Insurance_name') {
                                
                                if($h > 0){
                                $whereSelect_no_insurance_name .= " AND ";
                                }
                                $whereSelect_no_insurance_name .= $camposUpdate[$j]." = '".$valuesCampos[$j]."'";                                 
                                $h++;
                            }   
                            
                        }
                    }
                    if($j == 2 || $j == 4 || $j == 5){
                        if($j == 2)
                            $whereVerificar = $camposUpdate[$j]." = '".$valuesCampos[$j]."'"; 
                        else
                            $whereVerificar .= " AND ".$camposUpdate[$j]." = '".$valuesCampos[$j]."'";        
                    }else{
                        if($j == 3){
                            $whereVerificar .= " AND ".$camposUpdate[$j]." <> '".$valuesCampos[$j]."'";
                                //$auth_numberVerificar = $valuesCampos[$j];                        
                        }
                    }
                                         
                    $j++;
                }                                                   
                
                $sqlSelect = $sqlSelect.$whereSelect_no_insurance_name.';';                
                
                
                $whereSelect_no_insurance_name = null;
                
                $result01 = mysqli_query($conexion, $sqlSelect);
                $find = 0;
                while ($row=mysqli_fetch_array($result01)){ 
                    $find = 1;
                }
                if($find == 0){
                    $selectVerificar = "SELECT * FROM ".$table." WHERE ".$whereVerificar." AND status = 1
                    ;";
                    $resultadoVerificar = ejecutar($selectVerificar,$conexion);                                        
                    $row_cnt = mysqli_num_rows($resultadoVerificar);
                    if($row_cnt > 0){
                        $update="UPDATE ".$table." SET status = 0 WHERE ".$whereVerificar." AND status = 1;";
                        ejecutar($update,$conexion);
                    }
                    $sql .= ',\''.$company.'\');';
                    ejecutar($sql,$conexion);
                    $y++;    
                }else{
                    $sqlUpdate = " UPDATE ".$table." SET Visits_remen = ".$valuesCampos[9];                    
                    $sqlUpdate = $sqlUpdate.' WHERE '.$whereSelect.';';
                    ejecutar($sqlUpdate,$conexion);
                    $u++;
                }    
                
            }else{                
                if(($table == 'patients') AND $pos == 25){
                    $sqlSelect = "SELECT * FROM ".$table." WHERE ";
                    $j = 0;
                    $valuesCampos[$x] = $company;                                        
                    while(isset($camposUpdate[$j])){
                        if($j == 2 || $j == 25){
                            if($j == 2)
                                $whereSelect = $camposUpdate[$j]." = '".$valuesCampos[$j]."'"; 
                            else
                                $whereSelect .= " AND ".$camposUpdate[$j]." = '".$valuesCampos[$j]."'";
                        }                     
                        $j++;
                    }
                    $sqlSelect = $sqlSelect.$whereSelect.';';
                    
                    $result01 = mysqli_query($conexion, $sqlSelect);
                    $find = 0;
                    while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
                        
                       $thi_ins = $row['Thi_Ins'];
                       // echo $row['Thi_Ins']; die();
                       /*if($row['Thi_Ins'] != null){
                           
                           if($valuesCampos[16] == $row['Thi_Ins']){
                               
                              
                                $sqlUpdate = " UPDATE ".$table." SET Pri_Ins= '".$row['Thi_Ins']."', Thi_Ins = null";
                                ejecutar($sqlUpdate,$conexion);                               
                               
                               
                           }
                           
                           
                       }*/
                        
                        $find = 1;
                    }
                    
                    
                    
                    
                    if($find == 0){
                        $sql .= ',\''.$company.'\');';
                        ejecutar($sql,$conexion);
                        $y++;    
                    }else{
                    	$sqlUpdate = " UPDATE ".$table." SET active = true".",Pri_Ins= '".$valuesCampos[16]."' WHERE ".$whereSelect.";";
		        ejecutar($sqlUpdate,$conexion);
		        $u++;
			if($thi_ins != null){		
				//echo $valuesCampos[16] .'=='. $thi_ins;
		           	if(trim($valuesCampos[16]) == trim($thi_ins)){
				        $sqlUpdate = " UPDATE ".$table." SET Pri_Ins= '".$valuesCampos[16]."', Thi_Ins = null"." WHERE ".$whereSelect.";";
				        ejecutar($sqlUpdate,$conexion);				        
				}                                                 
                       }
                   }    
                    
                }else{
                    $columnasError[$t]['fila'] = $i;
                    $columnasError[$t]['name'] = $last_name.','.$first_name; 
                    $columnasError[$t]['pat_id'] = $pat_id;  
                    $t++;
                }
                                            
            }
            
        }
    }
    $i++;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link rel="stylesheet" href="../../../css/bootstrap-theme.min.css" type='text/css'>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>

<style type="text/css">
    .bs-example{
        margin: 20px;
    }
</style>
<script>
    
    
</script>
</head>
<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>

    


    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">KIDWORKS
                    <small>Therapy rehab</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-12">
        <?php
            echo '<br>';
            echo $y.' UPLOAD SUCCESSFUL<br/>';
            echo $u.' UPDATE SUCCESSFUL<br/>';
            echo $ignorada.' LINEAS IGNORADAS<br/>';            
            
            echo '<br>';
            echo 'ROWS With Error, Give Patient Name to Administrator:';
            echo '<br>';
            $t = 0;
            while($columnasError[$t]!= null){    
                echo '<br><b>Fila:</b> '.$columnasError[$t]['fila'].' <b>Name Patient:</b> '.$columnasError[$t]['name'].' <b>Pat_ID:</b> '.$columnasError[$t]['pat_id'];
                $t++;
            }
        ?>

        <br/>
        <button type="button" class="btn btn-primary btn-lg" onclick="window.location='../../vista/load_csv/load_csv_form.php';">Return</button>
            </div>

        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">

            
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; THERAPY  AID 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
</body>

</html>
