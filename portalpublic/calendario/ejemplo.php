<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="css/calendario.css" type="text/css" rel="stylesheet">
<script src="js/calendar.js" type="text/javascript"></script>
<script src="js/calendar-es.js" type="text/javascript"></script>
<script src="js/calendar-setup.js" type="text/javascript"></script>
</head>
<?php 
$fecha=date('Y-m-d');

if (isset($_POST["fecha_cal"])) 			{	$fecha       		= $_POST["fecha_cal"];			}

echo $fecha; 

?>
<body>
  <form action="" method="post">
<input onchange='submit();' type="text" name="fecha_cal" id="fecha_cal" value="<?php echo $fecha; ?>" /> 
<img src="ima/calendario.png" width="16" height="16" border="0" title="Fecha Inicial" id="lanzador">
<!-- script que define y configura el calendario--> 
<script type="text/javascript"> 
   Calendar.setup({ 
    inputField     :    "fecha_cal",     // id del campo de texto 
     ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto 
     button     :    "lanzador"     // el id del botón que lanzará el calendario 
}); 
</script>
</form>
</body>
</html>
