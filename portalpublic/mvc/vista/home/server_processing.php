<?php
session_start();
require_once '../../../conex.php';

if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('MUST LOG IN')</script>";
    echo "<script>window.location='index.php';</script>";
} elseif($_SESSION['user_type'] == 2) {
    echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
    echo "<script>window.location='../../home/home.php';</script>";
}

$conexion = conectar();

if(isset($_POST["id"]) && isset($_POST["content"]) && $_POST["id"] != null  && $_POST["content"] != null) {
	$where = "WHERE id = '" . sanitizeString($conexion, $_POST['id']) . "'";
	$aswer = sanitizeString($conexion, $_POST["content"]);
	$id_questions = sanitizeString($conexion, $_POST["id"]);
	$sql = "SELECT form, Pat_id, send_to FROM comunications_portal_patients WHERE id = '$id_questions';";
	$resultado = ejecutar($sql, $conexion);
	$i = 0;
	$form = array();
	while ($row = mysqli_fetch_array($resultado)) {
		$form[$i] = $row;
		$i++;
	}
    //$to = $form[0]['form'];
    //$from = $form[0]['send_to'];
    //$message = "Dear patient, check the portal for the answer to your question. \r\n KIDWORKS THERAPY";
	$json_resultado = "ok";
	$update = "UPDATE comunications_portal_patients SET aswer_to = '1' $where;";
	$resultado = ejecutar($update, $conexion);
	$date = new DateTime();
	$date1 = $date->format('Y-m-d H:i:s');
	$insert = "INSERT into response_to_comunications_portal_patients (Pat_id, tipe, content, form, send_to, aswer_to, created_at, updated_at)
			values ('".$form[0]['Pat_id']."','aswer','".$aswer."','".$form[0]['send_to']."','".$form[0]['form']."','".$id_questions."','".$date1."','".$date1."');";
	$resultado = ejecutar($insert, $conexion);
    //$subject = "KIDWORKS THERAPY, Answer to your question";
    //$headers = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n" . 'From: $from' . "\r\n";
    //mail($to, $subject, $message, $headers);
	echo json_encode($json_resultado);
	exit;
}

function sanitizeString($cnx, $var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return $cnx->real_escape_string($var);
}

$table = 'comunications_portal_patients';

$primaryKey = 'id';
 
$columns = array(
	array(
        'db' => 'id',
        'dt' => 'idr',
        'formatter' => function( $d, $row ) {
            return 'row_'.$d;
        }
    ),
    array( 'db' => 'First_name',  'dt' => 'First_name' ),
	array( 'db' => 'Last_name',  'dt' => 'Last_name' ),
    array( 'db' => 'content',  'dt' => 'content' ),
    array( 'db' => 'created_at',   'dt' => 'created_at' ),
	array( 'db' => 'send_to',   'dt' => 'send_to' ),
	array( 'db' => 'form',   'dt' => 'from' ),
	array( 'db' => 'id',   'dt' => 'id' ),
	array( 'db' => 'aswer_to',   'dt' => 'answer' ),
);

 
$sql_details = array(
    'user' => USER,
    'pass' => PASS,
    'db'   => BD,
    'host' => HOST
);
 
require( 'ssp.class.php' );

echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns));

?>