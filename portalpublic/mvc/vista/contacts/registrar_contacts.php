<?php
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}

$conexion = conectar();

if(isset($_GET['id_persona_contacto'])) {	
    $id_persona_contacto = sanitizeString($conexion, $_GET['id_persona_contacto']); 
	$accion = 'modificar contacto'; 
	$title = 'Edit';
	$query = "SELECT persona_contacto, cargo_persona_contacto, genero, relacion, descripcion, fecha_nacimiento, direccion, email, telefono, fax, id_contactos, id_carriers FROM tbl_contacto_persona WHERE id_persona_contacto=$id_persona_contacto";
	$result = ejecutar($query, $conexion);
	$persona_contacto = mysqli_result($result, 0, 'persona_contacto');
	$cargo_persona_contacto = mysqli_result($result, 0, 'cargo_persona_contacto');
	$genero = mysqli_result($result, 0, 'genero');
	$relacion = mysqli_result($result, 0, 'relacion');
	$descripcion = mysqli_result($result, 0, 'descripcion');
	$fecha_nacimiento = mysqli_result($result, 0, 'fecha_nacimiento');
	$direccion = mysqli_result($result, 0, 'direccion');
	$email = mysqli_result($result, 0, 'email');
	$telefono = mysqli_result($result, 0, 'telefono');
	$fax = mysqli_result($result, 0, 'fax');
	$id_carriers = mysqli_result($result, 0, 'id_carriers');
	$id_contactos = mysqli_result($result, 0, 'id_contactos');
	
	if ($id_contactos != 'OTHER') {
		$query = "SELECT tabla_ref, id_tabla_ref FROM tbl_contactos WHERE id_contactos=$id_contactos";
		$result = ejecutar($query, $conexion);
		$type_person = mysqli_result($result, 0, 'tabla_ref');
		$id_person = mysqli_result($result, 0, 'id_tabla_ref');
	} else {
		$type_person = 'other';
		$id_person = '';
	}	
} else { 
	$accion = 'insertar contacto';
	$title = 'New';
	$type_person = $id_person = '';	
	$persona_contacto=$cargo_persona_contacto=$genero=$relacion=$descripcion=$fecha_nacimiento=$direccion=$email=$telefono=$fax=$id_carriers='';	
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
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>
    <script src="../../../js/funciones.js"></script>
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
    <link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">
    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
    <script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
    <script src="../../../plugins/tinymce/tinymce.min.js"></script>
    <script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>
    <script src="../../../js/funciones.js" type="text/javascript"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/devoops_ext.js"></script>

<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $('#type_person').change(function(){
            var valor = $(this).val();			
			if ((valor != "") && (valor != "other")) {
				$.post("../../controlador/contacts/gestionar_contacts.php", {accion: "tomar tipo persona", type_person: valor}, function (json, textStatus) {						 
					if (textStatus == "success") {
						$("#id_person").html("<option value=''>Select..</option>");
						$.each(json, function(i, n) {
							var item = json[i];							
							$("#id_person").append("<option value='" + item.value + "'>" + item.display + "</option>");
						});
						$('#id_person').val('<?php echo $id_person?>').change();
					}
				}, 'json');
			} else
				$("#id_person").html("<option value=''>Select..</option>");
        });
        
            $('#type_person').val('<?php echo $type_person?>').change();
			$('#persona_contacto').val('<?php echo $persona_contacto?>');
			$('#cargo_persona_contacto').val('<?php echo $cargo_persona_contacto?>');
			$('#genero').val('<?php echo $genero?>').change();
			$('#relacion').val('<?php echo $relacion?>');
			$('#descripcion').val('<?php echo $descripcion?>');
			$('#fecha_nacimiento').val('<?php echo $fecha_nacimiento?>');
			$('#direccion').val('<?php echo $direccion?>');
			$('#email').val('<?php echo $email?>');
			$('#telefono').val('<?php echo $telefono?>');
			$('#fax').val('<?php echo $fax?>');
			$('#id_carriers').val('<?php echo $id_carriers?>');
        

        LoadSelect2ScriptExt(function(){
            $('#type_person').select2();
            $('#contact').select2();
            $('#id_carriers').select2();
        });

        $('#fecha_nacimiento').datepicker({setDate: new Date()});
        $('#fecha_nacimiento').prop('readonly', false);
    });
function Validar_Formulario_Gestion_Contactos(nombre_formulario) {                                    
    var nombres_campos = '';                
    if($('#type_person').val() == ''){
        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Type Person</td></tr></table>';
    }
    if($('#id_person').val() == '' && $('#type_person').val() != 'other' && $('#type_person').val() != ''){
        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Assign To</td></tr></table>';
    }
    if($('#persona_contacto').val() == ''){
        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Name</td></tr></table>';
    }
    if(nombres_campos != '') {
        swal({
            title: "<h3><b>The following fields are incomplete<b></h3>",
            type: "info",
            html: "<h4>" + nombres_campos + "</h4>",
            showCancelButton: false,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
        });
        return false;
    } else {
        var campos_formulario = $("#form_gestion_contactos").serialize();
        $.post(
             "../../controlador/contacts/gestionar_contacts.php",
             campos_formulario,
             function (resultado_controlador) {
                 mostrar_datos(resultado_controlador);
             },
             "json"
        );
        return false;
    }
}            
                         
function mostrar_datos(resultado_controlador) {                                                                                    
	swal({title: resultado_controlador.mensaje, ext: "¿Deseas ir a la Pantalla de Consultas?", type: "success", showCancelButton: true,   
		  confirmButtonColor: "#3085d6", cancelButtonColor: "#d33", confirmButtonText: "View contacts", closeOnConfirm: false, closeOnCancel: false
	}).then(function(isConfirm) {
		if (isConfirm === true) { 
		 window.location.href = "../contacts/consultar_contacts.php"; 
		}else{
		 window.location.href = "../contacts/registrar_contacts.php"; 
		}
	});             								 
}

</script>

</head>

<body>
	<?php include "../nav_bar/nav_bar.php"; 
    ?>
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
             <form id="form_gestion_contactos" onSubmit="return Validar_Formulario_Gestion_Contactos('form_gestion_contactos');">                    
				<div class="form-group row">                
				 <div class="col-sm-2"></div>
				 <div class="col-sm-8" align="left"><h3><font color="#BDBDBD"><?php echo $title?> Contact</font></h3></div>
				</div>                                  
				<div class="form-group row">   
					 <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Person</font></label>
					 <div class="col-sm-3">
						 <select name="type_person" id="type_person">
							 <option value="">Select..</option>
							 <option value="physician">PHYSICIAN</option>
							 <option value="patients">PATIENT</option>
							 <option value="seguros">SEGURO</option>
							 <option value="other" selected="selected">OTHER</option>
						 </select>
					 </div>
					 <label class="col-sm-2 form-control-label text-right"><font color="#585858">Assign To</font></label>
					 <div class="col-sm-4">
						 <select name="id_person" id="id_person">
							<option value="">Select..</option>
						 </select>
					 </div>
				 </div>
                <hr style="background-color: #D8D8D8; height: 2px;">
				<div class="form-group row">
					<h4><font color="#6E6E6E">Contact Information</font></h4>
				</div>

                 <div class="form-group row">
                     <label class="col-sm-2 form-control-label text-right" for="persona_contacto"><font color="#585858">Name</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="persona_contacto" name="persona_contacto" value="" placeholder="Name">
                     </div>
                     <label class="col-sm-2 form-control-label text-right" for="cargo_persona_contacto"><font color="#585858">Position</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="cargo_persona_contacto" name="cargo_persona_contacto" value="" placeholder="Position">
                     </div>
                 </div>

                 <div class="form-group row">
                     <label class="col-sm-2 form-control-label text-right" for="genero"><font color="#585858">Sex</font></label>
                     <div class="col-sm-3">
                         <select name="genero" id="genero">
						     <option value="">Select..</option>
                             <option value="masculino">Male</option>
                             <option value="femenino">Female</option>
                         </select>
                     </div>
                     <label class="col-sm-2 form-control-label text-right" for="relacion"><font color="#585858">Relation</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="relacion" name="relacion" value="" placeholder="Relation">
                     </div>
                 </div>

                 <div class="form-group row">
                     <label class="col-sm-2 form-control-label text-right" for="descripcion"><font color="#585858">Description</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="descripcion" name="descripcion" value="" placeholder="Description">
                     </div>
                     <label class="col-sm-2 form-control-label text-right" for="fecha_nacimiento"><font color="#585858">DOB</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="" readonly placeholder="DOB">
                     </div>
                 </div>

                 <div class="form-group row">
                     <label class="col-sm-2 form-control-label text-right" for="direccion"><font color="#585858">Address</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="direccion" name="direccion" value="" placeholder="Address">
                     </div>
                     <label class="col-sm-2 form-control-label text-right" for="email"><font color="#585858">E-mail</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="email" name="email" value="" placeholder="E-mail">
                     </div>
                 </div>

                 <div class="form-group row">
                     <label class="col-sm-2 form-control-label text-right" for="telefono"><font color="#585858">Phone</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="telefono" name="telefono" value="" placeholder="Phone">
                     </div>
                     <label class="col-sm-2 form-control-label text-right" for="fax"><font color="#585858">Fax</font></label>
                     <div class="col-sm-3">
                         <input class="form-control" type="edit" id="fax" name="fax" value="" placeholder="Fax">
                     </div>
                 </div>

                 <div class="form-group row">
                    <label class="col-sm-2 form-control-label text-right" for="id_carriers"><font color="#585858">Telephone provider</font></label>
                    <div class="col-sm-3">
                        <select class="populate placeholder" id="id_carriers" name="id_carriers">
							<option value="">Select..</option>
                            <?php
                                $sql  = "Select id_patients_carriers, carrier from tbl_patients_carriers order by id_patients_carriers asc";
                                $conexion = conectar();
                                $resultado = ejecutar($sql,$conexion);
                                while ($row=mysqli_fetch_array($resultado))
                                {
									print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>");
                                }
                            ?>
                        </select>
                    </div>
                </div>
				<input type='hidden' id='id_persona_contacto' name='id_persona_contacto' value='<?php echo $id_persona_contacto?>'>
				<input type='hidden' id='accion' name='accion' value='<?php echo $accion?>'>
				<div class="form-group row">
					<div class="col-sm-2" align="left"></div>
					<div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Add Contact</button> </div>
				</div>
             </form>
            <div id="resultado"></div>
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; THERAPY  AID 2018</p>
                    </div>
                </div>                
            </footer>                     
    </div>
</body>
</html>