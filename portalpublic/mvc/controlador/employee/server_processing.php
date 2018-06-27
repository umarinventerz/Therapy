<?php
session_start();
require_once('../../../conex.php');
require_once('../common/ssp.class.php');
if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('MUST LOG IN')</script>";
    echo "<script>window.location='index.php';</script>";
} elseif($_SESSION['user_type'] == 2) {
    echo "<script>alert('PERMISSION DENIED FOR THIS USER')</script>";
    echo "<script>window.location='../../home/home.php';</script>";
}
$table = 'tbl_pre_check';
$primaryKey = 'id'; 
$columns = array(
    array( 'db' => 'date_pay',  'dt' => 'date_pay' ),
	array( 'db' => 'total_pay',  'dt' => 'total_pay' ),
    array( 'db' => 'gross',  'dt' => 'gross' ),
    array( 'db' => 'social_security',   'dt' => 'social_security' ),
	array( 'db' => 'federal_withholding',   'dt' => 'federal_withholding' ),
	array( 'db' => 'id_employee',   'dt' => 'id_employee' ),
);
$sql_details = array('user' => USER, 'pass' => PASS, 'db'   => BD, 'host' => HOST);
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns));
?>