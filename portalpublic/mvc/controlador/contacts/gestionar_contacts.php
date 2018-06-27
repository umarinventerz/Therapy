<?php

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

$accion = (isset($_POST['accion'])) ? (sanitizeString($conexion, $_POST['accion'])) : ('');
$id_persona_contacto = (isset($_POST['id_persona_contacto'])) ? (sanitizeString($conexion, $_POST['id_persona_contacto'])) : ('');
$type_person = (isset($_POST['type_person'])) ? (sanitizeString($conexion, $_POST['type_person'])) : ('');
$id_person = (isset($_POST['id_person'])) ? (sanitizeString($conexion, $_POST['id_person'])) : ('');
$persona_contacto = (isset($_POST['persona_contacto'])) ? (sanitizeString($conexion, $_POST['persona_contacto'])) : ('');
$cargo_persona_contacto = (isset($_POST['cargo_persona_contacto'])) ? (sanitizeString($conexion, $_POST['cargo_persona_contacto'])) : ('');
$genero = (isset($_POST['genero'])) ? (sanitizeString($conexion, $_POST['genero'])) : ('');
$relacion = (isset($_POST['relacion'])) ? (sanitizeString($conexion, $_POST['relacion'])) : ('');
$descripcion = (isset($_POST['descripcion'])) ? (sanitizeString($conexion, $_POST['descripcion'])) : ('');
$fecha_nacimiento = (isset($_POST['fecha_nacimiento'])) ? (sanitizeString($conexion, $_POST['fecha_nacimiento'])) : ('');
$direccion = (isset($_POST['direccion'])) ? (sanitizeString($conexion, $_POST['direccion'])) : ('');
$email = (isset($_POST['email'])) ? (sanitizeString($conexion, $_POST['email'])) : ('');
$telefono = (isset($_POST['telefono'])) ? (sanitizeString($conexion, $_POST['telefono'])) : ('');
$fax = (isset($_POST['fax'])) ? (sanitizeString($conexion, $_POST['fax'])) : ('');
$id_carriers = (isset($_POST['id_carriers'])) ? (sanitizeString($conexion, $_POST['id_carriers'])) : ('');
$id_contactos = '';

if ($accion == 'insertar contacto') {
	$id_contactos = InsertIdContactos($conexion, $type_person, $id_person);
	$query = "INSERT into tbl_contacto_persona(persona_contacto, cargo_persona_contacto, genero, relacion, descripcion, fecha_nacimiento, direccion, email, telefono, fax, id_contactos, id_carriers) values('$persona_contacto', '$cargo_persona_contacto', '$genero', '$relacion', '$descripcion', '$fecha_nacimiento', '$direccion', '$email', '$telefono', '$fax', '$id_contactos', '$id_carriers');";
	$result = ejecutar($query, $conexion);	
	$json_resultado['mensaje'] = 'Contact saved';
} elseif ($accion == 'modificar contacto') {
	$id_contactos = InsertIdContactos($conexion, $type_person, $id_person);
	$query = "UPDATE tbl_contacto_persona SET persona_contacto='$persona_contacto', cargo_persona_contacto='$cargo_persona_contacto', genero='$genero', relacion='$relacion', descripcion='$descripcion', fecha_nacimiento='$fecha_nacimiento', direccion='$direccion', email = '$email', telefono = '$telefono', fax='$fax', id_contactos='$id_contactos', id_carriers='$id_carriers' WHERE id_persona_contacto='$id_persona_contacto';";
	$result = ejecutar($query, $conexion);	
	$json_resultado['mensaje'] = 'Contact saved';
} elseif ($accion == 'tomar tipo persona') {
	$display = $value = '';
	if ($type_person == "physician") {
		$display = "RTRIM(Name) as display";
		$value = "RTRIM(Phy_id) as value";
	} elseif ($type_person == "patients") {
		$display = "CONCAT(RTRIM(First_name),' ', RTRIM(Last_name)) as display";
		$value = "RTRIM(Pat_id) as value";
	} elseif ($type_person == "seguros") {
		$display = "RTRIM(insurance) as display";
		$value = "RTRIM(ID) as value";
	} 
	if (in_array($type_person, array("physician", "patients", "seguros"))) {
		$query = "SELECT $value, $display from $type_person";
		$result = ejecutar($query, $conexion);
		$i = 0;
		$json_resultado = array();
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$json_resultado[$i] = $row;
			$i++;
		}
	}
}	                                   

echo json_encode($json_resultado);   

function InsertIdContactos($conexion, $type_person, $id_person) {	
	if (in_array($type_person, array("physician", "patients", "seguros")) && $id_person != '') {
		$query = "INSERT into tbl_contactos(tabla_ref, id_tabla_ref) values ('$type_person', '$id_person');"; 
		$result = ejecutar($query, $conexion);
		return mysqli_insert_id($conexion);
	} else
		return "OTHER";
}

?>