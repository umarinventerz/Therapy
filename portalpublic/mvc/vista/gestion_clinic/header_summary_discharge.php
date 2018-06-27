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

$sql  = "SELECT *,d.Name as diname , e.id as id_evaluations,e.status_id as status_eval,pat.Pat_id as pat_id_long, pat.Address, 
            pat.Phone,pat.DOB, pat.PCP, pat.PCP_NPI, c.facility_address, c.tin_number ,
            CONCAT(code.DiagCodeValue,' , ',code.DiagCodeDescrip) as diagnostic_name,
            c.company_name as cname ,
            c.facility_address as caddress,
            CONCAT(c.facility_city,', ',c.facility_state,' ',c.facility_zip) AS ccity,
            CONCAT('(P)',' ',c.facility_phone) as cphone,
            CONCAT('(F)',' ',c.facility_fax) as cfax,
            concat(pat.PCP,' , ',pat.PCP_NPI) as pcp,            

            CONCAT(pat.dob , ' / ', TIMESTAMPDIFF( YEAR, pat.dob, now() ) ,' Yrs.,  ' 
                      , TIMESTAMPDIFF( MONTH, pat.dob, now()) % 12,' Months, ' 
                      , FLOOR( TIMESTAMPDIFF( DAY, pat.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob


            FROM tbl_evaluations e "
                . " INNER JOIN patients pat ON pat.id = patient_id "
                . " LEFT JOIN user_system us ON us.user_id = e.id_user "
                . " LEFT JOIN discipline d ON d.DisciplineID = e.discipline_id "
                . " LEFT JOIN companies c ON c.company_id = e.company "
                . " LEFT JOIN diagnosiscodes code ON code.DiagCodeId = e.diagnostic "
            . "WHERE e.id = ".$_GET['id_evaluations'].";"; 

$resultado_eval = ejecutar($sql,$conexion); 
   
$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado_eval)) {
    $reporte = $datos;
}

$json_resultado['summary'] = $reporte;

echo json_encode($json_resultado); 