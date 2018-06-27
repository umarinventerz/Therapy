<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
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
    <link href="../../../css/style_v2.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script>
function LoadAjaxContent(url){	
        $('.preloader').show();
	$.ajax({
		mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
		url: url,
		type: 'GET',
		success: function(data) {
			$('#ajax-content').html(data);
			$('.preloader').hide();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alert(errorThrown);
		},
		dataType: "html",
		async: false
	});
}        
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php  $cifra = 100.2534; echo number_format($cifra,2); $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <!-- Page Content -->
    <div >
        <div class="row">
            <div class="col-lg-12">
                <?php include 'menu_vertical.php'; ?>   
            </div>            
        </div>
    </div>
    <!-- /.container -->
</body>
<script>
LoadAjaxContent('contenido_1.php');
</script>

</html>
