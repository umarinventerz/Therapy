<?php

session_start();
require_once '../../../conex.php';

$conexion = conectar(); 

//consulto el codigo de barra 
  $barcode_consulta = "SELECT barcode FROM tbl_barcodes WHERE  id_type_person='1' and id_relation ='".$_GET['id']."' ;";
$resultado_codigo = ejecutar($barcode_consulta,$conexion);
$codigo_barra;
while ($row_barcode=mysqli_fetch_array($resultado_codigo)) {	
    $codigo_barra = $row_barcode['barcode'];
   
}

if(isset($_GET['Pat_id'])){ 
    
$accion = 'modificar';
$titulo = 'Edit';
$generar_input = '<input type="hidden" id="Pat_id" name="Pat_id" value="'.$_GET['id'].'">';

$avatar="select picture from avatar WHERE identificador='".$_GET['id']."'";
         $a=ejecutar($avatar,$conexion);
         $navatar=mysqli_fetch_array($a);
         $nombreavatar=$navatar['picture'];
         if ($nombreavatar!='') {
             $nombreavatar="../../../images/".$nombreavatar;
         }else{
            $nombreavatar='';
         }

/*$seletcIdRefPhysicianNpi = 'SELECT p.`Phy_id` FROM physician p WHERE NPI like \'%'.rtrim(ltrim($_GET['Phy_NPI'])).'%\'';
$resultadoIdRefPhysicianNpi = ejecutar($seletcIdRefPhysicianNpi,$conexion);     
while($datos = mysqli_fetch_assoc($resultadoIdRefPhysicianNpi)) {            
    $idRefPhysicianNpi = $datos['Phy_id'];
} 

$seletcIdPCPNPI = 'SELECT p.`Phy_id` FROM physician p WHERE NPI like \'%'.rtrim(ltrim($_GET['PCP_NPI'])).'%\'';
$resultadoIdPCPNPI = ejecutar($seletcIdPCPNPI,$conexion);     
while($datos = mysqli_fetch_assoc($resultadoIdPCPNPI)) {            
    $IdPCPNPI = $datos['Phy_id'];
}

$selectIdPriIns = 'SELECT s.`ID` FROM seguros s WHERE s.insurance like \''.rtrim(ltrim($_GET['Pri_Ins'])).'\'';
$resultadoIdPriIns = ejecutar($selectIdPriIns,$conexion);     
while($datos = mysqli_fetch_assoc($resultadoIdPriIns)) {            
    $idPriIns = $datos['ID'];
}

$selectIdSecIns = 'SELECT s.`ID` FROM seguros s WHERE s.insurance like \''.rtrim(ltrim($_GET['Sec_INS'])).'\'';
$resultadoIdSecIns = ejecutar($selectIdSecIns,$conexion);     
while($datos = mysqli_fetch_assoc($resultadoIdSecIns)) {            
    $idSecIns = $datos['ID'];
}

$selectIdTerIns = 'SELECT s.`ID` FROM seguros s WHERE s.insurance like \''.rtrim(ltrim($_GET['Ter_Ins'])).'\'';
$resultadoIdTerIns = ejecutar($selectIdTerIns,$conexion);     
while($datos = mysqli_fetch_assoc($resultadoIdTerIns)) {            
    $idTerIns = $datos['ID'];
}*/

        
} else {
$accion = 'insertar';
$titulo = 'New';
$generar_input = null;
$nombreavatar='';
}

?>

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
 <!--Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>

<!--<script src="../../../js/devoops_ext.js"></script>--> 
    <script type="text/javascript" language="javascript">

    /*imagen_javascript_2*/
    function check_pat_id() {
        var Pat_id=$('#Pat_id').val();
        var campos_formulario;
        if(Pat_id==''){
            swal({
                title: "<h3><b>Please<b></h3>",
                type: "info",
                html: "<h4>Complete the field firstly</h4>",
                showCancelButton: false,
                closeOnConfirm: true,
                showLoaderOnConfirm: false,
            });



        }else{

            campos_formulario= $('#Pat_id').serialize();
            $.post(
                "../../controlador/patients/check_pat_id.php",
                campos_formulario,
                function (resultado_controlador) {

                    if(resultado_controlador.repetido == 'No hay valores repetidos'){

                        swal({
                            title: '<h4><b>No problems</b></h4>',
                            type: "success"
                        });


                    } if(resultado_controlador.repetido == 'Hay') {

                        swal({
                            title: "<h3><b>Message<b></h3>",
                            type: "info",
                            html: "<h4> It already exist "+resultado_controlador.mensaje+"</h4>",
                            showCancelButton: false,
                            animation: "slide-from-top",
                            closeOnConfirm: true,
                            showLoaderOnConfirm: false,
                        });
                    }

                },
                "json"
            );

            return false;
        }


    }

    function Validar_Formulario_Gestion_Patients(nombre_formulario) {              
         
    var nombres_campos = '';
    
<?php  if(!isset($_GET['Pat_id'])){ ?>
  if($('#Pat_id').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Pat id</td></tr></table>';

        }
<?php } ?>
  if($('#type_patient').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Type Patient</td></tr></table>';

        }
        
  if($('#Last_name').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Last Name</td></tr></table>';

        }
  if($('#First_name').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * First Name</td></tr></table>';

        }
  if($('#Sex').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Sex</td></tr></table>';

        }
  if($('#DOB').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Dob</td></tr></table>';

        }
  if($('#Guardian').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Guardian</td></tr></table>';

        }
  if($('#Social').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Social</td></tr></table>';

        }
  if($('#Address').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Address</td></tr></table>';

        }
  if($('#City').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * City</td></tr></table>';

        }
  if($('#State').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * State</td></tr></table>';

        }
  if($('#Zip').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Zip</td></tr></table>';

        }
  if($('#county').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * County</td></tr></table>';

        }
  if($('#E_mail').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * E Mail</td></tr></table>';

        }
  if($('#Phone').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Phone</td></tr></table>';

        }
  if($('#id_carriers').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Telephone provider</td></tr></table>';

        }
  if($('#Cell').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Cell</td></tr></table>';

        }
  if($('#pcp').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * PCP</td></tr></table>';

        }
  if($('#pcp_npi').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * PCP_NPI</td></tr></table>';

        }        
  if($('#Pri_Ins').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Pri Ins</td></tr></table>';

        }  
  if($('#active').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Active</td></tr></table>';

        }
        
  if($('#admision_date').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Admision Date</td></tr></table>';

        }
        
         if($('#barcode').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Barcode</td></tr></table>';

        }
        
/*tabladinamicasvalidacion*/

    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Please fill following data <b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,          
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else {

                        var campos_formulario = $("#form_gestion_patient").serialize();
                      
                       
                        $.post(
                                "../../controlador/patients/gestionar_patients.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    
        if(resultado_controlador.repetido == 'si'){
            
    swal({
                title: '<h4><b>Patient ID repeated</b></h4>',                
                type: "error"
                });       
            
            
        } else {
                mostrar_datos(resultado_controlador);                                    
                }
                                    
                                },
                                "json" 
                                );
                        
                        return false;
                    }
            }            
            
            function mostrar_datos(resultado_controlador) {                                         
           
            $('#resultado').html(resultado_controlador.resultado);
                      
/*pdf*/

/*word*/


            swal({
                title: resultado_controlador.mensaje,
                text: "Do You Want to Consult the Inserted record?",
                type: "success",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "Consult",   
                closeOnConfirm: false,
                closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                       
                    window.location.href = '../patients/consultar_patients.php?&consultar=si';  
                  }else{
                      
                  }
                    });             
                                            
           }
           
            function consultar_npi(valor,identificador){
              
                $.post(
                    "../../controlador/patients/consultar_npi.php",
                    '&Phy_id='+valor,
                    function (resultado_controlador) {
                       
                       $('#'+identificador).val(resultado_controlador.npi);
                       
                    },
                    "json" 
                );
                        
                        return false;                                              
            }           
           
           

           /*imagen_val*/
           
$('#State').attr('onkeypress','return SoloLetras(event)');
$('#Zip').attr('onkeypress','return SoloLetras(event)');
$('#Ref_Physician').attr('onkeypress','return SoloLetras(event)');
 
        </script>
        
        <script>//script para mostrar el preview de la foto que se sube
    function filePreview(input){
      if(input.files &&input.files[0]){
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);

reader.onload = function(e)
{document.getElementById("image").src = e.target.result;};

}};

</script>

    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>


<form  enctype="multipart/form-data" method="post" role="form" action="../../controlador/patients/gestionar_patients.php" >      
    <input type="hidden" name="id_patients" id="id_patients" value="<?=$_GET['id']?>"/>
                <div class="form-group row">                
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> Patient</font></h3>   </div>                  
                    <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"></a></div>
                </div>                    
   
    
    <div class="form-group row">                              
       <div class="col-sm-2">
              <img class="img-responsive" id="image" src="<?php echo $nombreavatar;?>"" width="150" height="150" /></div>
           
            <div class="col-sm-3 text-center">
                <label><font color="#585858">PATIENT ID</font></label>
            </div>
            <div class="col-sm-3 text-center">
                <label><font color="#585858">TYPE PATIENT</font></label>
            </div> 
            <div class="col-sm-3 text-center">
                <label><font color="#585858">ACTIVE</font></label>
            </div>        
       
    </div>
    
   

        <div class="form-group row">        
           
            <div class="col-lg-2"></div>
            <div class="col-sm-3 text-center">
                <input type="text" class="form-control" id="Pat_id" name="Pat_id" placeholder="PATIENT ID" style="text-align: center" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Pat_id'])) { echo str_replace('|',' ',$_GET['Pat_id']); }?>">
          
            <span><button class="btn-success" type="button" onclick="check_pat_id()"> Ckeck</button></span></div>
             


            <div class="col-sm-3 text-center">

                <select id="type_patient" name="type_patient"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "select * from tbl_seguros_type_person where id_seguros_type_person is not null";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
                //print("<option value='".utf8_decode($row["id_seguros_type_person"])."' >".utf8_decode($row["seguros_type_person"])." </option>");
                                                                if($row["id_seguros_type_person"]==$_GET['type_patient']){
                                                                print("<option value='".$row["id_seguros_type_person"]."' selected>".$row["seguros_type_person"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["id_seguros_type_person"]."'>".$row["seguros_type_person"]."</option>"); 
                                                                        }
                                                            
                                                            }

                                                        ?>    
                </select>

            </div>
             <div class="col-sm-3 text-center"><input type="checkbox" id="active" name="active" <?php if(isset($_GET['active']) && $_GET['active'] == '1') { echo 'checked'; }?>></div>
            <div class="col-lg-2"></div>
        </div>
    
  
    
    
    <hr>
    

          <div class="form-group row">  
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Last Name</font></label>            
<div class="col-sm-3">
    <input type="text" class="form-control" id="Last_name" name="Last_name" placeholder="Last Name" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Last_name'])) { echo str_replace('|',' ',$_GET['Last_name']); }?>">
</div>   



                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">First Name</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="First_name" name="First_name" placeholder="First Name" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['First_name'])) { echo str_replace('|',' ',$_GET['First_name']); }?>"></div>
        </div>
   


        <div class="form-group row">

            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Pacient Photo</font></label>            
<div class="col-sm-3">
    <input type="file"  id="Photo" onchange="filePreview(this)" name="Photo"  placeholder="Photo">
</div>   
           
           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Sex</font></label>
                                            <div class="col-sm-3"><select id="Sex" name="Sex"><option value="">--- SELECT ---</option>
                                                <option value="M" <?php if($_GET['Sex']=='M'){?> selected <?php } ?>>Male</option>
                                                <option value="F" <?php if($_GET['Sex']=='F'){?> selected <?php } ?>>Female</option></select></div>

                                               
           
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Dob</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="DOB" name="DOB" placeholder="Dob" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['DOB'])) { echo str_replace('|',' ',$_GET['DOB']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Guardian</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Guardian" name="Guardian" placeholder="Guardian" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Guardian'])) { echo str_replace('|',' ',$_GET['Guardian']); }?>"></div>
        </div>
    
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Admision Date</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="admision_date" name="admision_date" placeholder="Admision Date" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['admision_date'])) { echo str_replace('|',' ',$_GET['admision_date']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Discharge Date</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="discharge_date" name="discharge_date" placeholder="Discharge Date" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['discharge_date'])) { echo str_replace('|',' ',$_GET['discharge_date']); }?>"></div>
        </div>    
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Social</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Social" name="Social" placeholder="Social" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Social'])) { echo str_replace('|',' ',$_GET['Social']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Address</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Address" name="Address" placeholder="Address" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Address'])) { echo str_replace('|',' ',$_GET['Address']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">City</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="City" name="City" placeholder="City" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['City'])) { echo str_replace('|',' ',$_GET['City']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">State</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="State" name="State" placeholder="State" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['State'])) { echo str_replace('|',' ',$_GET['State']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Zip</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Zip" name="Zip" placeholder="Zip" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Zip'])) { echo str_replace('|',' ',$_GET['Zip']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">County</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="county" name="county" placeholder="County" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['county'])) { echo str_replace('|',' ',$_GET['county']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">E Mail</font></label>
<div class="col-sm-3"><input type="email" class="form-control" id="E_mail" name="E_mail" placeholder="E Mail" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['E_mail'])) { echo str_replace('|',' ',$_GET['E_mail']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Phone</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Phone" name="Phone" placeholder="Phone" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Phone'])) { echo str_replace('|',' ',$_GET['Phone']); }?>"></div>
        </div>
    
    <!--AGREGO COLUMNA DE LINEA TELEFONICA-->
            <div class="form-group row">                          
                
                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Telephone provider</font></label>
                <div class="col-sm-3"><select id="id_carriers" name="id_carriers"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "select * from tbl_patients_carriers where id_patients_carriers is not null group by carrier";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
                //print("<option value='".utf8_decode($row["id_patients_carriers"])."' >".utf8_decode($row["carrier"])." </option>");
                                                               
                                                            if($row["id_patients_carriers"]==$_GET['id_carriers']){
                                                                print("<option value='".$row["id_patients_carriers"]."' selected>".$row["carrier"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                                                        }
                                                            }

                                                        ?>    
                </select></div>
                <label class="col-sm-2 form-control-label text-right"><font color="#585858">PCP</font></label>
                <div class="col-sm-3"><select id="pcp" name="pcp"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "SELECT * FROM physician where Phy_id is not null";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
                //print("<option value='".utf8_decode($row["Phy_id"])."' >".utf8_decode($row["Name"])." </option>");
                                                                if($row["Name"]==$_GET['PCP']){
                                                                print("<option value='".$row["Name"]."' selected>".$row["Name"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["Name"]."'>".$row["Name"]."</option>"); 
                                                                        }
                                                            }
                                                            

                                                        ?>
                </select></div>   
            </div>
    
    
        <div class="form-group row">
        
           

            <label class="col-sm-2 form-control-label text-right"><font color="#585858">PCP_NPI</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="PCP_NPI" name="PCP_NPI" placeholder="PCP_NPI" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['PCP_NPI'])) { echo str_replace('|',' ',$_GET['PCP_NPI']); }?>"></div>
           <label class="col-sm-2 form-control-label text-right"><font color="#585858">Ref Physician</font></label>
            <div class="col-sm-3"><select id="Ref_Physician" name="Ref_Physician"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "SELECT * FROM physician where Phy_id is not null";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
               //print("<option value='".utf8_decode($row["Phy_id"])."' >".utf8_decode($row["Name"])." </option>");
                                                                if($row["Name"]==$_GET['Ref_Physician_npi']){
                                                                print("<option value='".$row["Name"]."' selected>".$row["Name"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["Name"]."'>".$row["Name"]."</option>"); 
                                                                        }
                                                            }
                                                            
                                                            

                                                        ?>
            </select></div>
        </div>    
   
        <div class="form-group row">
           
                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Ref Physician NPI</font></label>
                <div class="col-sm-3"><input type="text" class="form-control" id="Ref_Physician_npi" name="Ref_Physician_npi" placeholder="Phy_NPI" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Phy_NPI'])) { echo str_replace('|',' ',$_GET['Phy_NPI']); }?>"></div>
                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Pri Ins</font></label>
                <div class="col-sm-3"><select id="Pri_Ins" name="Pri_Ins"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "SELECT * FROM seguros where ID is not null";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
               // print("<option value='".utf8_decode($row["ID"])."' >".utf8_decode($row["insurance"])." </option>");
                                                                  if($row["insurance"]==$_GET['Pri_Ins']){
                                                                print("<option value='".$row["insurance"]."' selected>".$row["insurance"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["insurance"]."'>".$row["insurance"]."</option>"); 
                                                                        }
                                                            }
                                                            

                                                        ?>
                </select></div>
        </div>
   
        <div class="form-group row">        
        
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Auth</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Auth" name="Auth" placeholder="Auth" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Auth'])) { echo str_replace('|',' ',$_GET['Auth']); }?>"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Sec Ins</font></label>
            <div class="col-sm-3"><select id="Sec_INS" name="Sec_INS"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "SELECT * FROM seguros where ID is not null";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
                //print("<option value='".utf8_decode($row["ID"])."' >".utf8_decode($row["insurance"])." </option>");
                                                            if($row["insurance"]==$_GET['Sec_INS']){
                                                                print("<option value='".$row["insurance"]."' selected>".$row["insurance"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["insurance"]."'>".$row["insurance"]."</option>"); 
                                                                        }
                                                            }

                                                        ?>
            </select></div>
        </div>
   
        <div class="form-group row">
                                            
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Auth 2</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Auth_2" name="Auth_2" placeholder="Auth 2" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Auth_2'])) { echo str_replace('|',' ',$_GET['Auth_2']); }?>"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Ter Ins</font></label>
            <div class="col-sm-3"><select id="Ter_Ins" name="Ter_Ins"><option value="">--- SELECT ---</option>
<?php
                                          //modifique aqui Cesar
                                 $sql  = "SELECT * FROM seguros where ID is not null";
                                 $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                                                   
                //print("<option value='".utf8_decode($row["ID"])."' >".utf8_decode($row["insurance"])." </option>");
                                                                if($row["insurance"]==$_GET['Ter_Ins']){
                                                                print("<option value='".$row["insurance"]."' selected>".$row["insurance"]."</option>"); 
                                                            }else{
                                                                print("<option value='".$row["insurance"]."'>".$row["insurance"]."</option>"); 
                                                                        }
                                                            }                                                            

                                                        ?>
            </select></div>
        </div>
   
        <div class="form-group row">
                                            
                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Auth 3</font></label>
                <div class="col-sm-3"><input type="text" class="form-control" id="Auth_3" name="Auth_3" placeholder="Auth 3" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Auth_3'])) { echo str_replace('|',' ',$_GET['Auth_3']); }?>"></div>
                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Mem #</font></label>
                <div class="col-sm-3"><input type="text" class="form-control" id="Mem_N" name="Mem_N" placeholder="Mem N" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Mem_N'])) { echo str_replace('|',' ',$_GET['Mem_N']); }?>"></div>
        </div>
   
        <div class="form-group row">
                                            
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Grp #</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Grp_N" name="Grp_N" placeholder="Grp N" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Grp_N'])) { echo str_replace('|',' ',$_GET['Grp_N']); }?>"></div>
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Intake Agmts</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Intake_Agmts" name="Intake_Agmts" placeholder="Intake Agmts" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Intake_Agmts'])) { echo str_replace('|',' ',$_GET['Intake_Agmts']); }?>"></div>
        </div>
   
        <div class="form-group row">
                                     <label class="col-sm-2 form-control-label text-right"><font color="#585858">Cell</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Cell" name="Cell" placeholder="Cell" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Cell'])) { echo str_replace('|',' ',$_GET['Cell']); }?>"></div>         
                     
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Barcode</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode"  value="<?php if(isset($codigo_barra)){echo $codigo_barra;}?>"></div>                                                               
                                              
        </div>
                 
            <div class="form-group row">
                <div class="col-sm-2" align="left"></div>
                <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Accept</button> </div>
            </div>
  
    <input type="hidden" id="accion" name="accion" value="<?php echo $accion?>">

    <?php echo $generar_input;?>

        
        <script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#DOB').datepicker({setDate: new Date()});  
                    $('#DOB').prop('readonly', true);
                    
                    $('#admision_date').datepicker({setDate: new Date()});  
                    $('#admision_date').prop('readonly', true);
                    
                    $('#discharge_date').datepicker({setDate: new Date()}); 
                    $('#discharge_date').prop('readonly', true);     

                     $('#Intake_Agmts').datepicker({setDate: new Date()}); 
                    $('#Intake_Agmts').prop('readonly', true);
                                   
                    
                    
                    $('#Phy_NPI').prop('readonly', true);
                    
                    //$('#Ref_Physician_npi').prop('readonly', true);

            });   
            

            
            $('#pcp').change(function(){
                                
                consultar_npi($(this).val(),'PCP_NPI')
                                                                              
            });
            
            $('#Ref_Physician').change(function(){
                                
                consultar_npi($(this).val(),'Ref_Physician_npi')
                                                                              
            });            
            
            
            
                LoadSelect2ScriptExt(function(){

                    $('#pcp').select2();
                    $('#Ref_Physician').select2();
                    $('#Sex').select2();
                    
                    $('#Pri_Ins').select2();
                    $('#Sec_INS').select2();
                    $('#Ter_Ins').select2();
                    $('#type_patient').select2();
                    $('#id_carriers').select2();
                    

                });             
            
            
<?php  if(isset($_GET['Pat_id'])){?>

           /* autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'pcp');   
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'Ref_Physician'); 
                       
            autocompletar_radio('insurance','ID','seguros','selector','<?php echo $_GET['Pri_Ins'];?>',null,null,null,'Pri_Ins'); 
            autocompletar_radio('insurance','ID','seguros','selector','<?php echo $_GET['Sec_INS'];?>',null,null,null,'Sec_INS'); 
            autocompletar_radio('insurance','ID','seguros','selector','<?php echo $_GET['Ter_Ins'];?>',null,null,null,'Ter_Ins');  
                        
            autocompletar_radio('seguros_type_person','id_seguros_type_person','tbl_seguros_type_person','selector','<?php echo $_GET['type_patient'];?>',null,null,null,'type_patient');                         
            autocompletar_radio('carrier','id_patients_carriers','tbl_patients_carriers','selector','<?php echo $_GET['id_carriers'];?>',null,null,null,'id_carriers');                         
            
            setTimeout(function(){
                $("#Ref_Physician").val(<?php echo $idRefPhysicianNpi;?>).change();
                $("#pcp").val(<?php echo $IdPCPNPI;?>).change();      
                $("#type_patient").val('<?php echo $_GET['type_patient'];?>').change();
                $("#id_carriers").val('<?php echo $_GET['id_carriers'];?>').change();
                $("#Pri_Ins").val(<?php echo $idPriIns;?>).change();
                $("#Sec_INS").val(<?php echo $idSecIns;?>).change();
                $("#Ter_Ins").val(<?php echo $idTerIns;?>).change();
                $("#Sex").val('<?php echo $_GET['Sex'];?>').change();
            },1000); */

            $('#Pat_id').prop('readonly', true);
            

<?php } else { ?>            
      
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'pcp');   
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'Ref_Physician'); 
            
            autocompletar_radio('insurance','ID','seguros','selector',null,null,null,null,'Pri_Ins'); 
            autocompletar_radio('insurance','ID','seguros','selector',null,null,null,null,'Sec_INS'); 
            autocompletar_radio('insurance','ID','seguros','selector',null,null,null,null,'Ter_Ins'); 
            
            autocompletar_radio('seguros_type_person','id_seguros_type_person','tbl_seguros_type_person','selector',null,null,null,null,'type_patient'); 
            autocompletar_radio('carrier','id_patients_carriers','tbl_patients_carriers','selector',null,null,null,null,'id_carriers'); 

<?php } ?>    
       
        
        </script>
        <div id="resultado"></div>