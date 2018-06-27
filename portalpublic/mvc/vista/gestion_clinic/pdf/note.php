<?php

session_start();
require_once '../../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}
 ob_start();
 
$id=$_GET['id'];
include('note/note.php');
$content = ob_get_clean();

// convert in PDF
require_once 'html2pdf/html2pdf.class.php';    
$html2pdf = new HTML2PDF('P', 'A4', 'es',true);
$html2pdf->writeHTML($content);
$html2pdf->Output('note.pdf');
    
    
