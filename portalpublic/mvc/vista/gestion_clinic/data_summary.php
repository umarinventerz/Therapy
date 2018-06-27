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
		echo '<script>window.location="homs.php";</script>';
	}
}
$conexion = conectar();


 $sql  = "SELECT *,s.ckeditor as ckeditorsummary,di.Name as diname,s.created as created_summary,s.signed as signed_summary,s.id_template as id_tem, "
         . " CONCAT(code.DiagCodeValue,' , ',code.DiagCodeDescrip) as diagnostic_name,"
        . " c.company_name as cname ,
            c.facility_address as caddress,
            CONCAT(c.facility_city,', ',c.facility_state,' ',c.facility_zip) AS ccity,
            CONCAT('(P)',' ',c.facility_phone) as cphone,
            CONCAT('(F)',' ',c.facility_fax) as cfax,
            concat(pat.PCP,' , ',pat.PCP_NPI) as pcp,

            CONCAT(pat.dob , ' / ', TIMESTAMPDIFF( YEAR, pat.dob, now() ) ,' Yrs.,  ' 
                      , TIMESTAMPDIFF( MONTH, pat.dob, now()) % 12,' Months, ' 
                      , FLOOR( TIMESTAMPDIFF( DAY, pat.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob"
        . " FROM tbl_summary s"
        . " LEFT JOIN tbl_evaluations e ON e.id = s.id_evaluations "
        . " LEFT JOIN companies c ON c.company_id = e.company "
        . " LEFT JOIN diagnosiscodes code ON code.DiagCodeId = e.diagnostic "
        . " LEFT JOIN discipline di ON di.DisciplineID = e.discipline_id " 
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription "
        . " LEFT JOIN tbl_documents d ON d.id_table_relation = s.id_summary "
        . " LEFT JOIN patients pat ON pat.id = e.patient_id "
        . " WHERE s.id_summary = ".$_GET['id_document'].";"; 
$resultado = ejecutar($sql,$conexion);   
$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte = $datos;
}
$reporte['start_date'] = date('Y-m-d', strtotime($reporte['start_date']));
$reporte['end_date'] = date('Y-m-d', strtotime($reporte['end_date']));
$reporte['created_summary'] = date('Y-m-d', strtotime($reporte['created_summary']));
$reporte['signed_date'] = date('Y-m-d', strtotime($reporte['signed_date']));

$json_resultado['summary'] = $reporte;

echo json_encode($json_resultado); 

?>