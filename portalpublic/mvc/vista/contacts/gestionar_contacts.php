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

$accion = ((isset($_POST['accion'])) && ($_POST['accion'] != null)) ? (sanitizeString($conexion, $_POST['accion'])) : ('');
$id_persona_contacto = ((isset($_POST['id_persona_contacto'])) && ($_POST['id_persona_contacto'] != null)) ? (sanitizeString($conexion, $_POST['id_persona_contacto'])) : ('');
$type_person = ((isset($_POST['type_person'])) && ($_POST['type_person'] != null)) ? (sanitizeString($conexion, $_POST['type_person'])) : ('');
$id_person = ((isset($_POST['id_person'])) && ($_POST['id_person'] != null)) ? (sanitizeString($conexion, $_POST['id_person'])) : ('');
$persona_contacto = ((isset($_POST['persona_contacto'])) && ($_POST['persona_contacto'] != null)) ? (sanitizeString($conexion, $_POST['persona_contacto'])) : ('');
$cargo_persona_contacto = ((isset($_POST['cargo_persona_contacto'])) && ($_POST['cargo_persona_contacto'] != null)) ? (sanitizeString($conexion, $_POST['cargo_persona_contacto'])) : ('');
$genero = ((isset($_POST['genero'])) && ($_POST['genero'] != null)) ? (sanitizeString($conexion, $_POST['genero'])) : ('');
$relacion = ((isset($_POST['relacion'])) && ($_POST['relacion'] != null)) ? (sanitizeString($conexion, $_POST['relacion'])) : ('');
$descripcion = ((isset($_POST['descripcion'])) && ($_POST['descripcion'] != null)) ? (sanitizeString($conexion, $_POST['descripcion'])) : ('');
$fecha_nacimiento = ((isset($_POST['fecha_nacimiento'])) && ($_POST['fecha_nacimiento'] != null)) ? (sanitizeString($conexion, $_POST['fecha_nacimiento'])) : ('');
$direccion = ((isset($_POST['direccion'])) && ($_POST['direccion'] != null)) ? (sanitizeString($conexion, $_POST['direccion'])) : ('');
$email = ((isset($_POST['email'])) && ($_POST['email'] != null)) ? (sanitizeString($conexion, $_POST['email'])) : ('');
$telefono = ((isset($_POST['telefono'])) && ($_POST['telefono'] != null)) ? (sanitizeString($conexion, $_POST['telefono'])) : ('');
$fax = ((isset($_POST['fax'])) && ($_POST['fax'] != null)) ? (sanitizeString($conexion, $_POST['fax'])) : ('');
$id_carriers = ((isset($_POST['id_carriers'])) && ($_POST['id_carriers'] != null)) ? (sanitizeString($conexion, $_POST['id_carriers'])) : ('');

if ($accion == 'insertar') {
	if ($type_person != "other") {
		$query = "INSERT into tbl_contactos(tabla_ref, id_tabla_ref) values ('$type_person', '$id_person'); "; 
		$result = ejecutar($insert, $conexion);
		$id_contactos = mysqli_insert_id($conexion);
	} else
		$id_contactos = "OTHER";
	$query = "INSERT into tbl_contacto_persona(persona_contacto, cargo_persona_contacto, genero, relacion, descripcion, fecha_nacimiento, direccion, email, telefono, fax, id_contactos, id_carriers) " .                     
             "values($persona_contacto, $cargo_persona_contacto, $genero, $relacion, $descripcion, $fecha_nacimiento, $direccion, $email, $telefono, $fax, $id_contactos, $id_carriers)";
	$result = ejecutar($query, $conexion);	
} elseif ($accion == 'modificar') {
	$query = "UPDATE tbl_contacto_persona " .
	         "SET persona_contacto=$persona_contacto, cargo_persona_contacto=$cargo_persona_contacto, genero=$genero, relacion=$relacion, descripcion=$descripcion, fecha_nacimiento=$fecha_nacimiento, direccion=$direccion, email = $email, telefono = $telefono, fax=$fax, id_contactos=$id_contactos, id_carriers=$id_carriers)";
             "values(, , , , , , , $email, $telefono, $fax, $id_contactos, $id_carriers)";
	$result = ejecutar($query, $conexion);
	
}
	                                   
$json_resultado['mensaje'] = 'Contact saved';
echo json_encode($json_resultado);   

?>