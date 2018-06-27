 <?php
error_reporting(0);
session_start();
include("../../../mail/phpmailer.php");
require_once '../../modelo/ScheduledEmails_RingCentral.php';
require_once '../../modelo/ringcentral.php';
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

$codigo = $_POST['codigo'];

$sqlBarCodes = 'SELECT * FROM tbl_barcodes WHERE barcode = \''.$codigo.'\';';
$resultadoBarCodes = ejecutar($sqlBarCodes,$conexion);
$id_relation  = '';
$id_type_person = '';
$arreglo_barcode=array();

while ($row=mysqli_fetch_array($resultadoBarCodes)) {	
    $id_relation = $row['id_relation'];
    $id_type_person = $row['id_type_person'];
    $arreglo_barcode[]=$row;
    //break;
}


if($id_type_person == 2){
$sql = 'SELECT id_assistance as id_max,hour_of_admission,operacion  '
        . 'FROM tbl_assistance '
        . 'WHERE id_usuario = \''.$id_relation.'\' AND date_of_admission = CURDATE() '
        . 'order by date_datetime desc limit 0,1;';
//die;

$resultado = ejecutar($sql,$conexion);

$id_max = '';
$hour_of_admission_prev = '';
$operacion = '';
while ($row=mysqli_fetch_array($resultado)) {	

    $id_max = $row['id_max'];
    $hour_of_admission_prev = $row['hour_of_admission'];
    $operacion = $row['operacion'];

}

$hour_current_bd = date("H:i:s");

/* Verifica si existe un registro en el dia*/
$sql  = "SELECT * FROM tbl_assistance WHERE date_of_admission = CURDATE() AND id_usuario = ".$id_relation.";";
            
$resultadoToday = ejecutar($sql,$conexion);

$reporteToday = array();

$i = 0;      
while($datosToday = mysqli_fetch_assoc($resultadoToday)) {
    $reporteToday[$i] = $datosToday;
    $i++;
}
/* if $reporteToday == 0 no existen registros en el dia y por lo tanto se debe registrar un in*/
if($id_max == '' || $operacion == 'out' || count($reporteToday) == 0){
    $insert  = "INSERT INTO tbl_assistance (
    id_usuario,
    date_of_admission,
    hour_of_admission,operacion,date_datetime) VALUES ('".$id_relation."',CURDATE(),curtime(),'in',now())";

    $resultado = ejecutar($insert,$conexion);
    
    $resultado_mensaje = '<h3><font color="blue">Admission Register</font></h3>';
    $type_message = 'success';
}else{
    
    list($h,$m,$s) = explode(':',$hour_of_admission_prev);
    $hour_current = date("H");
    $minutes_current = date("i");
    
    if(($minutes_current - $m >= 10) || ($hour_current - $h >= 1)){        
        $insert  = "INSERT INTO tbl_assistance (
        id_usuario,
        date_of_admission,
        hour_of_admission,operacion,date_datetime) VALUES ('".$id_relation."',CURDATE(),curtime(),'out',now())";

        $resultado = ejecutar($insert,$conexion);

        $resultado_mensaje = '<h3><font color="blue">Exit Register</font></h3>';
        $type_message = 'success';
    } else{
        $resultado_mensaje = '<h3><font color="blue">You can`t get out(Message Line 81 of controlador/assistance/register_assistance.php) </font></h3>';
        $type_message = 'error';
    }   
}

$json_resultado['resultado_m'] = $resultado_mensaje;
$json_resultado['type_message'] = $type_message;
$json_resultado['type_person'] = 'employee';

}else{
    /* ######################### INGRESO UN PACIENTE #####################*/
    if($id_type_person == 1){
        

       $sql_physician = "
       SELECT *,STR_TO_DATE(a.hour, '%l:%i %p') as pp FROM  patients p 
        join appointments_cs a  on p.Pat_id=a.Pat_id

        where p.id='".$id_relation."' and str_to_date(a.date,'%m/%d/%Y') = date(now()) and a.type='Therapy' and a.location='Office' 
        order by pp
        #limit 1

        ";
       $resultadophysician = ejecutar($sql_physician,$conexion);

         $reportephysician = array();
        $t = 0;      
      

while($datosphysician = mysqli_fetch_assoc($resultadophysician)) {
                        $reportephysician[$t] = $datosphysician;
                      $user_first_name .= $reportephysician[$t]['user_first_name']."-";
                        $t++;
                    }
      
        for($j=0;$j<count($arreglo_barcode);$j++){        
            
            $sqlPatient  = "SELECT * FROM patients  WHERE id = ".$arreglo_barcode[$j]['id_relation'].";";
          
            //$sqlPatient  = "SELECT * FROM patients  WHERE id = ".$id_relation.";"; 
            $resultadoPatient = ejecutar($sqlPatient,$conexion);

            $reportePatient = array();
            $i = 0;      
            while($datosPatient = mysqli_fetch_assoc($resultadoPatient)) {
                $reportePatient[$i] = $datosPatient;
                $i++;
            }

            $selectVerificarAcceso = "SELECT * FROM tbl_arrive_patients WHERE patient_id ='".$reportePatient[0]['id']."' AND date_today = CURDATE()";
            $resultadoVerificarAcceso = ejecutar($selectVerificarAcceso,$conexion);
            $reporteVerificarAcceso = array();
            $i = 0;      
            while($datosVerificarAcceso = mysqli_fetch_assoc($resultadoVerificarAcceso)) {
                $reporteVerificarAcceso[$i] = $datosVerificarAcceso;
                $i++;
            }   
            if(count($reporteVerificarAcceso) == 0){    
                
                //Pat_id            
                $valor_id=$reportePatient[0]['Pat_id']; 
                
               //consulto la fecha actuaal 
                $fecha= date("m/d/Y");
                //seteo la fecha al formato que tiene la columna date
                $particion=  explode('/', $fecha);       
                $mes=$particion[0];
                $dia=$particion[1]; 
                $anio=$particion[2];
                $month=$mes+0;
                $day=$dia+0;
                $array=array($month,$day,$anio);
                $fecha_formateada=implode("/",$array);          
                 //consulto los datos de la tabla appoints
                $sql_appoints="SELECT id_appointments_cs,user_last_name,user_first_name, STR_TO_DATE(hour, '%l:%i %p') as pp , therapist_phone
                  FROM appointments_cs 
                  WHERE 
                  Pat_id='".$valor_id."' 
                  AND date = '".$fecha_formateada."' 
                  AND user_last_name!='null'
                  and location='Office' 
                  and type!='Pending Authorization'
                  #and (type='Therapy' or type='Not Available')
                order by pp
                #AND STR_TO_DATE(appointments_cs.hour, '%l:%i %p') IN (
                #select MIN(STR_TO_DATE(a.hour, '%l:%i %p')) FROM   appointments_cs a  

                #where a.Pat_id=".$valor_id." and 
                #str_to_date(a.date,'%m/%d/%Y') = date(now()) 
               # and a.type='Therapy' and a.location='Office' )

                  ";

                $array_appints = ejecutar($sql_appoints,$conexion);
                $appoints_data=array(); 
                $i=0;
                $user_first_name='';
                while($array_appoints_id = mysqli_fetch_assoc($array_appints)) {
                    $appoints_data[$i] = $array_appoints_id;
                    $user_first_name .= $appoints_data[$i]['user_first_name']."-";
                    $i++;
                }
                
                if(count($appoints_data)>0){
                
                    $insertArrive = "INSERT INTO tbl_arrive_patients(date_hour,patient_id,patient_name,physician_name,date_today,notified) VALUES "
                    . "(now(),'".str_replace(' ','',$reportePatient[0]['id'])."','".str_replace(' ','',$reportePatient[0]['Last_name'])." ".str_replace(' ','',$reportePatient[0]['First_name'])."','".$user_first_name."',now(),0);";
                    $resultado = ejecutar($insertArrive,$conexion);
                    $resultado_mensaje = 'Ingreso del Paciente '.str_replace(' ','',$reportePatient[0]['Last_name']).", ".str_replace(' ','',$reportePatient[0]['First_name']);
                    $type_message = 'success';             

                   $name=$appoints_data[0]['user_first_name'];
                    $last_name=$appoints_data[0]['user_last_name'];

                    //consulto telefono desde employee

                     $sql_employe="SELECT phone_number FROM employee WHERE first_name='".$name."' AND last_name='".$last_name."'";
                    $array_employee = ejecutar($sql_employe,$conexion);
                    $employee_data=array(); 
                    $i=0;
                    while($array_employee_id = mysqli_fetch_assoc($array_employee)) {
                        $employee_data[$i] = $array_employee_id;
                        $i++;
                    }
                    //telefono terapista
                   $phone_number=$appoints_data[0]['therapist_phone'];           
                    //envio el sms 
                     $programar_email= new ScheduledEmails();
                    $programar_email->send_sms_terapista($phone_number,$resultado_mensaje);

                }else{
                    
                    $resultado_mensaje = 'El paciente '.$reportePatient[0]['Last_name'].", ".$reportePatient[0]['First_name'].' He has no date today';
                    $type_message = 'error';
                }
            }else{

                $resultado_mensaje = 'El paciente '.$reportePatient[0]['Last_name'].", ".$reportePatient[0]['First_name'].' ya ingreso el día de hoy';
                $type_message = 'error';
            }
            
        }        
       
        /*###############################################################################*/
        /*###############################################################################*/
        
        
        /*AGREGAR AQUI EL ENVIO DE MENSAJE DE TEXTO*/
            
        
        /*###############################################################################*/
        /*###############################################################################*/
        
        $json_resultado['resultado_m'] = $resultado_mensaje;
        $json_resultado['type_message'] = $type_message;
        $json_resultado['type_person'] = 'patient';        
    }    
}

 echo json_encode($json_resultado);
 
 