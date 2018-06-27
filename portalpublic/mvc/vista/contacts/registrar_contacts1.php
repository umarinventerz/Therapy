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



if(isset($_GET['id_persona_contacto'])){ 
    
$accion = 'modificar';
$titulo = 'Modificar';
$generar_input = '<input type="hidden" id="id_persona_contacto" Name="id_persona_contacto" value="'.$_GET['id_persona_contacto'].'">';
} else {
$accion = 'insertar';
$titulo = 'Register';
$generar_input = null;
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
<!-- Include all compiled plugins (below), or include individual files as needed -->
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
             
                 /*imagen_javascript_2*/
             
                 function Validar_Formulario_Gestion_Contactos(nombre_formulario) {              
                      
                 var nombres_campos = '';
                 
             <?php  if(!isset($_GET['id_persona_contacto'])){ ?>
               if($('#id_contactos').val() == ''){
             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Id Contacts</td></tr></table>';
             
                     }
             <?php } ?>
             
               if($('#type_person').val() == ''){
             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Type Person</td></tr></table>';
             
                     }
               if($('#contact').val() == '' && $('#type_person').val() != 'other'){
             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Contact</td></tr></table>';
             
                     }
                      /*if($('#id_carriers').val() == ''){
             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Telephone provider</td></tr></table>';
             
                     }*/
              
                     
             /*tabladinamicasvalidacion*/
             camposInput = document.getElementsByTagName('input');
                    
                     var id_persona_contacto = '';
                    var persona_contacto = '';
                    var cargo_persona_contacto = '';
                    var tipo_contacto = '';
                    var descripcion_contacto = '';   
                    var genero = '';   
                    var relacion = '';   
                    var descripcion = '';
                    var fecha_nacimiento = '';
                    var direccion = '';
                    var email = '';
                    var telefono = '';
                    var fax = '';
                    var contacto_email = '';
                    var contacto_telefono = '';
                    var contacto_fax = '';
                    var id_carriers = '';
                    
                    for(j=0;j< camposInput.length;j++){
                    nombreCampo = camposInput[j].name.substring(0,18);
                        
                        if(nombreCampo=='persona_contacto__'){
                        
                    p = camposInput[j].name.substring(18,20);
                    
                    if($('#persona_contacto__'+p).val() == ''){
                        
                        nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Persona Contacto "+p+"</td></tr></table>";
                        
                    } else {
                       
                        persona_contacto += $("#persona_contacto__"+p).val()+"|";
                        
                            if($('#cargo_persona_contacto__'+p).val() == ''){

                                nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Cargo Persona Contacto "+p+"</td></tr></table>";

                            } else {

                                cargo_persona_contacto += $("#cargo_persona_contacto__"+p).val()+"|";

                                if($('#genero__'+p).val() == ''){

                                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Género "+p+"</td></tr></table>";

                                } else {

                                    genero += $('#genero__'+p).val()+"|";

                                        if($('#relacion__'+p).val() == ''){

                                            nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Relación "+p+"</td></tr></table>";

                                        } else {

                                            relacion += $('#relacion__'+p).val()+"|";

                                                if($('#descripcion__'+p).val() == ''){

                                                    nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Descripción "+p+"</td></tr></table>";

                                                } else {

                                                    descripcion += $('#descripcion__'+p).val()+"|";

                                                      //  if($('#fecha_nacimiento__'+p).val() == ''){

                                                       //     nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Fecha Nacimiento "+p+"</td></tr></table>";

                                                     //   } else {

                                                     //       fecha_nacimiento += $('#fecha_nacimiento__'+p).val()+"|";

                                                                if($('#direccion__'+p).val() == ''){

                                                        nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"Dirección "+p+"</td></tr></table>";

                                                                } else {

                                                                  direccion += $('#direccion__'+p).val()+"|";

                                                                }


                                                      //  }


                                                }


                                        }


                                }



                                }                        
                        
                        
  camposInputContact = document.getElementsByTagName('input');

                                   for(b=0;b< camposInputContact.length;b++){
                                   nombreCampoContact = camposInputContact[b].name.substring(0,31);

                                       if(nombreCampoContact=='descripcion_contacto_email__'+p+'__'){

                                   p_c = camposInputContact[b].name.substring(31,32);

                                   if($('#descripcion_contacto_email__'+p+'__'+p_c).val() == ''){

                                nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"EMAIL "+p_c+"</td></tr></table>";

                                   } else {
                                      
                                      contacto_email += $('#descripcion_contacto_email__'+p+'__'+p_c).val()+"&";


                                            if($('#descripcion_contacto_telefono__'+p+'__'+p_c).val() == ''){

                                                nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"TELÉFONO "+(parseInt(p_c)+parseInt(1))+"</td></tr></table>";

                                            } else {

                                                contacto_telefono += $('#descripcion_contacto_telefono__'+p+'__'+p_c).val()+"&";

                                                    if($('#descripcion_contacto_fax__'+p+'__'+p_c).val() == ''){

                                      nombres_campos += "<table align='center' border='0' width='400px'><tr><td align='left'> * "+"FAX "+(parseInt(p_c)+parseInt(1))+"</td></tr></table>";

                                                    } else {

                                                      contacto_fax += $('#descripcion_contacto_fax__'+p+'__'+p_c).val()+"&";

                                                    }
                                                }
                                            }                                      
                                        }                                        
                                    }                        
                        
                  
                        }
                        

                    contacto_email = contacto_email.slice(0,-1);
                    contacto_telefono = contacto_telefono.slice(0,-1);
                    contacto_fax = contacto_fax.slice(0,-1);

                    contacto_email += '|';
                    contacto_telefono += '|';
                    contacto_fax += '|';  
                    
                        
                    } 
                    


                                                              
                       
                }
              
              
              
              
                   
                $('#c_persona_contacto_c').val(persona_contacto);
                $('#c_cargo_persona_contacto_c').val(cargo_persona_contacto);
                $('#c_genero_c').val(genero);
                $('#c_relacion_c').val(relacion);
                $('#c_descripcion_c').val(descripcion);
                $('#c_tipo_contacto_c').val(tipo_contacto);
                $('#c_descripcion_contacto_c').val(descripcion_contacto);   
                $('#c_fecha_nacimiento_c').val(fecha_nacimiento);   
                $('#c_direccion_c').val(direccion);   
                
               
                
                $('#c_contacto_email_c').val(contacto_email);   
                $('#c_contacto_telefono_c').val(contacto_telefono);   
                $('#c_contacto_fax_c').val(contacto_fax);   
                
                

                 if(nombres_campos != ''){ 
                         
                     swal({
                       title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
                       type: "info",
                       html: "<h4>"+nombres_campos+"</h4>",
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
             
                         swal({
                             title: resultado_controlador.mensaje,
                             text: "¿Deseas ir a la Pantalla de Consultas?",
                             type: "success",
                             showCancelButton: true,   
                             confirmButtonColor: "#3085d6",   
                             cancelButtonColor: "#d33",   
                             confirmButtonText: "Consultar",   
                             closeOnConfirm: false,
                             closeOnCancel: false
                             }).then(function(isConfirm) {
                               if (isConfirm === true) { 
                                 window.location.href = "../contacts/consultar_contacts.php"; 
                               }else{
                                 window.location.href = "../contacts/registrar_contacts.php"; 
                               }
                                 });             
                                                         
                        }
                        
             
                        /*imagen_val*/
                        
             $('#id_contactos').attr('onkeypress','return SoloLetras(event)');
             $('#id_tabla_red').attr('onkeypress','return SoloLetras(event)');
             
             
             /*tabladinamicasfuncion*/
               
                var posicionCampoPc=0;                
                function nueva_persona_contacto(id_person){
                
                    nuevaFilaPc = document.getElementById("nueva_persona_contacto_tabla").insertRow(-1);    
                    nuevaFilaPc.id = 'p_fila'+parseInt(posicionCampoPc + 1);

                    posicionCampoPc++;     
                    
                    var accionPc = null;
                    var botonPc = null;
                    var valor_botonPc = null;
                    if(posicionCampoPc == 1){ accionPc = 'nueva_persona_contacto()'; botonPc = 'success'; valor_botonPc = '+';  } else { accionPc = 'eliminarFila(document.getElementById(\'p_fila'+(posicionCampoPc)+'\'));eliminarFila(document.getElementById(\'s_fila'+(posicionCampoPc)+'\'));eliminarFila(document.getElementById(\'t_fila'+(posicionCampoPc)+'\'));eliminarFila(document.getElementById(\'c_fila'+(posicionCampoPc)+'\'));eliminarFila(document.getElementById(\'q_fila'+(posicionCampoPc)+'\'));$(\'#nuevos_contactos_tipo_contacto'+posicionCampoPc+'\').remove();'; botonPc = 'danger'; valor_botonPc = '<font color="red"><b>-</b></font>'; }                
                
                    if(posicionCampoPc > 1){
                        
                    nuevaFilaPc = document.getElementById("nueva_persona_contacto_tabla").insertRow(-1);    
                    nuevaFilaPc.id=posicionCampoPc;   
                    
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);        
                    nuevaCeldaPc.setAttribute('colspan','4');
                    nuevaCeldaPc.innerHTML='<hr>';
                    
                    nuevaFilaPc = document.getElementById("nueva_persona_contacto_tabla").insertRow(-1);    
                    nuevaFilaPc.id= 's_fila'+posicionCampoPc;   
                        
                    }
                
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','15%');
                    nuevaCeldaPc.innerHTML='<b>Contact Info  N° '+ parseInt(posicionCampoPc)+'</b>';                   
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','25%');
                    nuevaCeldaPc.innerHTML='<input class="form-control" size="10" onkeyup="return Mayusculas(event, this)"  id="persona_contacto__'+posicionCampoPc+'" name="persona_contacto__'+posicionCampoPc+'" placeholder="NAME">';                                           
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','25%');
                    nuevaCeldaPc.innerHTML='<input class="form-control" size="10" onkeyup="return Mayusculas(event, this)"  id="cargo_persona_contacto__'+posicionCampoPc+'" name="cargo_persona_contacto__'+posicionCampoPc+'" placeholder="Position">';                 
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','15%');
                    nuevaCeldaPc.innerHTML='<select name="genero__'+posicionCampoPc+'" id="genero__'+posicionCampoPc+'"><option value="masculino">Male</option><option value="femenino">Female</option></select>';                     
                                
                
                    if(botonPc == 'success'){                        
                        var color_boton = 'style="background:#81BEF7"';                        
                    }
                    
                    if(botonPc == 'danger'){                        
                        var color_boton = 'style="background:#81BEF7"';                        
                    }                    
                    
                    if(id_person == null || id_person == ''){
                        nuevaCeldaPc=nuevaFilaPc.insertCell(-1);                
                        nuevaCeldaPc.setAttribute('align','right');
                        nuevaCeldaPc.setAttribute('width','5%');                     
                        nuevaCeldaPc.innerHTML='<button type="button" class="btn btn-'+botonPc+'" '+color_boton+'   id="btbuscar" onclick="'+accionPc+';">'+valor_botonPc+'</button>';
                    }                                                                        
                    
                    nuevaFilaPc = document.getElementById("nueva_persona_contacto_tabla").insertRow(-1);    
                    nuevaFilaPc.id='t_fila'+posicionCampoPc;                      

                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','15%');
                    nuevaCeldaPc.innerHTML='&nbsp;';                   
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','25%');
                    nuevaCeldaPc.innerHTML='<input class="form-control" size="10" onkeyup="return Mayusculas(event, this)"  id="relacion__'+posicionCampoPc+'" name="relacion__'+posicionCampoPc+'" placeholder="Relation">';                                           
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','25%');
                    nuevaCeldaPc.innerHTML='<input class="form-control" size="10" onkeyup="return Mayusculas(event, this)"  id="descripcion__'+posicionCampoPc+'" name="descripcion__'+posicionCampoPc+'" placeholder="Description">';     
                    nuevaFilaPc = document.getElementById("nueva_persona_contacto_tabla").insertRow(-1);    
                    nuevaFilaPc.id='c_fila'+posicionCampoPc;                     

                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','15%');
                    nuevaCeldaPc.innerHTML='&nbsp;';                   
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','25%');
                    nuevaCeldaPc.innerHTML='<input class="form-control" size="10" onkeyup="return Mayusculas(event, this)"  id="fecha_nacimiento__'+posicionCampoPc+'" name="fecha_nacimiento__'+posicionCampoPc+'" placeholder="DOB" readonly>';                                           
                
                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('align','center');
                    nuevaCeldaPc.setAttribute('width','25%');
                    nuevaCeldaPc.innerHTML='<input class="form-control" size="10" onkeyup="return Mayusculas(event, this)"  id="direccion__'+posicionCampoPc+'" name="direccion__'+posicionCampoPc+'" placeholder="Address">';     

                    nuevaFilaPc = document.getElementById("nueva_persona_contacto_tabla").insertRow(-1);    
                    nuevaFilaPc.id='q_fila'+posicionCampoPc;  

                    nuevaCeldaPc=nuevaFilaPc.insertCell(-1);  
                    nuevaCeldaPc.setAttribute('colspan','4');
                    nuevaCeldaPc.innerHTML='<table width="100%" border="0" bordercolor="#D8D8D8" align="center" id="nuevos_contactos_tipo_contacto'+posicionCampoPc+'" cellpadding="0" cellspacing="0"></table>';                                 
  
                    
                    setTimeout(function(){
                    nuevo_contactos_tipo_contacto(posicionCampoPc,'si');
                    },100);
                    
                    
                    setTimeout(function(){        
                        $('#fecha_nacimiento__'+posicionCampoPc).datepicker({setDate: new Date()});	
                        $('#fecha_nacimiento__'+posicionCampoPc).prop('readonly', false);                      
                    },400);                    

                         LoadSelect2ScriptExt(function(){

                            $('#genero__'+posicionCampoPc).select2();

                           });
                    
                   
                    
                    
                   
    }
               
               
               
                var posicionCampo;
                
                function nuevo_contactos_tipo_contacto(posicion,valor_s){                              
                                                          
                    if(valor_s == 'si'){ posicionCampo = 0; valor_s = 'no'; } else { valor_s = 'no'; }                    

                    nuevaFila = document.getElementById("nuevos_contactos_tipo_contacto"+posicion).insertRow(-1);    
                    nuevaFila.id=posicionCampo;

                    posicionCampo++;                     
                    
                    
                    
                    var accion = null;
                    var boton = null;
                    var valor_boton = null;
                    if(posicionCampo == 1){ accion = 'nuevo_contactos_tipo_contacto('+posicion+')'; boton = 'success'; valor_boton = '+';  } else { accion = 'eliminarFila(this)'; boton = 'danger'; valor_boton = '-'; }                                                                                                          

                    if(posicionCampo == 1){                 
                          
                    nuevaFila = document.getElementById("nuevos_contactos_tipo_contacto"+posicion).insertRow(-1);    
                    nuevaFila.id=posicionCampo;   
                    
                    nuevaCelda=nuevaFila.insertCell(-1);        
                    nuevaCelda.innerHTML='&nbsp;';
                    
                    nuevaFila = document.getElementById("nuevos_contactos_tipo_contacto"+posicion).insertRow(-1);    
                    nuevaFila.id=posicionCampo;                     
                          
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.setAttribute('style','width:100px');
                    nuevaCelda.innerHTML='&nbsp;';                
                
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.setAttribute('style','width:100px');
                    nuevaCelda.innerHTML='<b>EMAIL</b>';
                     
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.setAttribute('style','width:100px');
                    nuevaCelda.innerHTML='<b>PHONE</b>';
                    
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.innerHTML='<b>FAX</b>';
                                  
                     
                    }
                     
                    nuevaFila = document.getElementById("nuevos_contactos_tipo_contacto"+posicion).insertRow(-1);    
                    nuevaFila.id=posicionCampo;   
                    
                    nuevaCelda=nuevaFila.insertCell(-1);        
                    nuevaCelda.innerHTML='&nbsp;';                      
                     
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.setAttribute('style','width:100px');
                    nuevaCelda.innerHTML='<input class="form-control" size="30" style="text-align:center" onkeyup="return Mayusculas(event, this)"  id="descripcion_contacto_email__'+posicion+'__'+posicionCampo+'" name="descripcion_contacto_email__'+posicion+'__'+posicionCampo+'" placeholder="Info'+posicion+'">';
                     
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.setAttribute('style','width:100px');
                    nuevaCelda.innerHTML='<input class="form-control" size="30" style="text-align:center" onkeyup="return Mayusculas(event, this)"  id="descripcion_contacto_telefono__'+posicion+'__'+posicionCampo+'" name="descripcion_contacto_telefono__'+posicion+'__'+posicionCampo+'" placeholder="Info '+posicion+'">';
                    
                    nuevaCelda=nuevaFila.insertCell(-1);  
                    nuevaCelda.setAttribute('align','center');
                    nuevaCelda.setAttribute('width','30%');
                    nuevaCelda.setAttribute('style','width:100px');
                    nuevaCelda.innerHTML='<input class="form-control" size="30" style="text-align:center" onkeyup="return Mayusculas(event, this)"  id="descripcion_contacto_fax__'+posicion+'__'+posicionCampo+'" name="descripcion_contacto_fax__'+posicion+'__'+posicionCampo+'" placeholder="Info '+posicion+'">';                    
                     
                        
                    var valor_marcar;
                    
                    if(posicionCampo == 1 || posicionCampo == 2 || posicionCampo == 3) {
                        
                        valor_marcar = posicionCampo;
                        $('#id_tipo_contacto_email__'+posicion+'__'+posicionCampo).prop('readonly',true);
                        $('#id_tipo_contacto_telefono__'+posicion+'__'+posicionCampo).prop('readonly',true);
                        $('#id_tipo_contacto_fax__'+posicion+'__'+posicionCampo).prop('readonly',true);
                        
                    }   else {
                        
                        valor_marcar = null;
                    }                  
                    
                    
                    
                        
                    
                    
                         LoadSelect2ScriptExt(function(){
                                  
                            $('#id_tipo_contacto_email__'+posicion+'__'+posicionCampo).select2();                            
                            $('#id_tipo_contacto_telefono__'+posicion+'__'+posicionCampo).select2();                            
                            $('#id_tipo_contacto_fax__'+posicion+'__'+posicionCampo).select2();                            
                            
                     
                        });  
                           
                           
                     
                     
                     
                           
                                  
                    
                    
                       }                                                                                                                                    
             
        </script>
</head>

<body>
    
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; 
    if(isset($_GET['id_person'])){
        $title = 'Edit';
    }else{
        $title = 'New';
    }
    ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
             <form id="form_gestion_contactos" onSubmit="return Validar_Formulario_Gestion_Contactos('form_gestion_contactos');">      
              
                             <div class="form-group row">                
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-8" align="left"><h3><font color="#BDBDBD"><?php echo $title?> Contact</font></h3>   </div>                  
                                 <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"></a></div>
                             </div>                    
                
                     <div class="form-group row">   
                         <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Person</font></label>            
                         <div class="col-sm-3">
                             <select name="type_person" id="type_person">
                                 <option value="">Select..</option>
                                 <option value="physician">PHYSICIAN</option>
                                 <option value="patients">PATIENT</option>
                                 <option value="seguros">SEGURO</option>
                                 <option value="other">OTHER</option>

                             </select>
                         </div>
                         <label class="col-sm-2 form-control-label text-right"><font color="#585858">Assign To:</font></label>            
                         <div class="col-sm-4">
                             <select name="contact" id="contact">
                                 <option value="">Select..</option>
                             </select>
                         </div>
                     </div>   
                                      
             <!--imagenes-->
             
             <!--/*tabladinamica*/-->
             
                    
                                <hr style="background-color: #D8D8D8; height: 2px;">
                                <div class="form-group row">
                                    <h4><font color="#6E6E6E">Contacts Information</font></h4>
                                </div>                                 
                              
                                <table width="80%" border="0" bordercolor="#D8D8D8" align="center" id="nueva_persona_contacto_tabla" cellpadding="0" cellspacing="0"></table>  <br>
                                <div class="form-group row">  
                                    <div class="col-sm-3"></div>
                                    <label class="col-sm-2 form-control-label text-right"><font color="#585858">Telephone provider</font></label>
                                    <div class="col-sm-3">
                                        <select class="populate placeholder" id="id_carriers" name="id_carriers">
                                            <option value="">Seleccione</option>
                                            <?php 
                                                $sql  = "Select id_patients_carriers, carrier from tbl_patients_carriers order by id_patients_carriers asc"; 
                                                  $conexion = conectar(); 
                                                  $resultado = ejecutar($sql,$conexion); 
                                                  while ($row=mysqli_fetch_array($resultado))  
                                                  {   
                                                      if(isset($_GET['id_carriers'])){
                                                            if($row["id_patients_carriers"]==$_GET['id_carriers']){
                                                                print("<option value='".$row["id_patients_carriers"]."' selected>".$row["carrier"]."</option>"); 
                                                            }else{
                                                                    print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                                            }
                                                      }else{
                                                          print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                                      }
                                                  }       

                                            ?> 
                                        </select>
                                    </div> 
                                </div>
                                <br>
                                <br> 


     <div class="form-group row">   
         <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Person</font></label>            
                         <div class="col-sm-3">                                       
            <input type="hidden" id="persona_contacto" name="persona_contacto" value="<?php if(isset($_GET['persona_contacto'])) { echo $_GET['persona_contacto'] ; }?>">    
                        </div>
                        
                        
    <input type="hidden" id="id_persona_contacto" name="id_persona_contacto" value="<?php if(isset($_GET['id_persona_contacto'])) { echo $_GET['id_persona_contacto'] ; }?>">    
    <input type="hidden" id="c_persona_contacto_c" name="c_persona_contacto_c">    
    <input type="hidden" id="c_cargo_persona_contacto_c" name="c_cargo_persona_contacto_c">
                                            
    <input type="hidden" id="c_genero_c" name="c_genero_c">
    <input type="hidden" id="c_relacion_c" name="c_relacion_c">
    <input type="hidden" id="c_descripcion_c" name="c_descripcion_c">
                                                                                                                                    
                                            
    <input type="hidden" id="c_tipo_contacto_c" name="c_tipo_contacto_c">
    <input type="hidden" id="c_descripcion_contacto_c" name="c_descripcion_contacto_c">
                                             
    <input type="hidden" id="c_fecha_nacimiento_c" name="c_fecha_nacimiento_c">
    <input type="hidden" id="c_direccion_c" name="c_direccion_c">   
                                            
    <input type="hidden" id="c_contacto_email_c" name="c_contacto_email_c">   
    <input type="hidden" id="c_contacto_telefono_c" name="c_contacto_telefono_c">   
    <input type="hidden" id="c_contacto_fax_c" name="c_contacto_fax_c">   
        </div>                                    
                                <hr style="background-color: #D8D8D8; height: 2px;">           
                          <div class="form-group row">
                             <div class="col-sm-2" align="left"></div>
                             <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Aceptar</button> </div>
                         </div>
               
                 <input type="hidden" id="accion" name="accion" value="<?php echo $accion?>">
                 <!--imagen_campo_modificacion-->    
             
             </form>
                     <script type="text/javascript" language="javascript">
                     
                    $('#type_person').change(function(){
                        
                        var valor = $(this).val();
                        
                        if(valor == 'physician'){                       
                            autocompletar_radio('Name','Phy_id',valor,'selector',null,null,null,null,'contact');                             
                        }
                        
                        if(valor == 'patients'){                       
                            autocompletar_radio('CONCAT(Last_name,\' \', First_name)','rtrim(Pat_id)',valor,'selector',null,null,null,null,'contact');                             
                        }  
                        
                        if(valor == 'seguros'){                       
                            autocompletar_radio('insurance','ID',valor,'selector',null,null,null,null,'contact');                             
                        }                          
                                                                                                                       
                        
                    }); 
                    
                LoadSelect2ScriptExt(function(){

                    $('#type_person').select2();
                    $('#contact').select2();
                    $('#id_carriers').select2();

                });    
                
                function DemoTimePicker(){
                        $('#input_time').timepicker({setDate: new Date()});
                         
                }
                             
                        nueva_persona_contacto('<?php echo $_GET['id_person']?>');
                        <?php if(isset($_GET['id_person'])){?>
                            $('#type_person').val('<?php echo $_GET['type_person']?>').change();
                            setTimeout(function(){
                                $('#contact').val('<?php echo $_GET['id_person']?>').change();                                 
                                $('#persona_contacto__1').val('<?php echo $_GET['persona_contacto']?>');
                                $('#cargo_persona_contacto__1').val('<?php echo $_GET['cargo_persona_contacto']?>');
                                $('#genero__1').val('<?php echo ($_GET['genero'] == 'masculino')?'masculino':'femenino';?>').change();
                                $('#relacion__1').val('<?php echo $_GET['relacion']?>');
                                $('#descripcion__1').val('<?php echo $_GET['descripcion']?>');
                                $('#fecha_nacimiento__1').val('<?php echo $_GET['fecha_nacimiento']?>');
                                $('#direccion__1').val('<?php echo $_GET['direccion']?>');
                                $('#descripcion_contacto_email__1__1').val('<?php echo $_GET['email']?>');
                                $('#descripcion_contacto_telefono__1__1').val('<?php echo $_GET['telefono']?>');
                                $('#descripcion_contacto_fax__1__1').val('<?php echo $_GET['fax']?>');                                  
                            },1000); 
                        <?php }?>
                        
            </script>
                     <div id="resultado"></div>
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; THERAPY  AID 2016</p>
                    </div>
                </div>
                
            </footer>                     
                          