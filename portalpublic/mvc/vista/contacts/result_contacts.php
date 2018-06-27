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

if(isset($_GET["type_person"]) && $_GET["type_person"] != null && $_GET["type_person"] != 'other'){ $type_person = " and tabla_ref = '". sanitizeString($conexion, $_GET["type_person"])."' "; } 
    else { 
        if($_GET["type_person"]=='other') {
            $type_person = " and tbl_contacto_persona.id_contactos = 'OTHER'";
        } 

            else{ if($_GET["type_person"]==null){
                        $type_person = null;

            } 

           

             }
}


if(isset($_GET["contact"]) && $_GET["contact"] != null){ $contact = " and id_tabla_ref = '". sanitizeString($conexion, $_GET["contact"]) ."' "; } else { $contact = null; }

  
 $having = "";
 $having2 = "";
              if($_GET['type_person'] == 'physician'){
               $having = "left join ". sanitizeString($conexion, $_GET['type_person']) ." on tbl_contactos.id_tabla_ref=physician.Phy_id";
                $having2 = ",physician.name as Associate_To";
              }else{
              if($_GET['type_person'] == 'patients'){
             $having = "left join ". sanitizeString($conexion, $_GET['type_person']) ." on tbl_contactos.id_tabla_ref=patients.Pat_id";
             $having2 = ",concat(patients.Last_name,patients.First_name) as Associate_To";
               }
            if($_GET['type_person'] == 'seguros'){
           $having = "left join ". sanitizeString($conexion, $_GET['type_person']) ." on tbl_contactos.id_tabla_ref=seguros.ID";
           $having2 = ",seguros.insurance as Associate_To";
                 }
                if($_GET['type_person'] == ''){
           $having = "";
           $having2 = "";
       }
              }






 $sql  = "select 
 tbl_contacto_persona.id_persona_contacto,
tbl_contacto_persona.genero,
tbl_contacto_persona.relacion,
tbl_contacto_persona.descripcion,
tbl_contacto_persona.direccion,
tbl_contacto_persona.fecha_nacimiento,
tbl_contacto_persona.persona_contacto,
tbl_contacto_persona.cargo_persona_contacto,
tbl_contacto_persona.email,
tbl_contacto_persona.telefono,
tbl_contacto_persona.fax,

(case 
    when tbl_contactos.tabla_ref='physician' then physician.Name
    when tbl_contactos.tabla_ref='patients' then concat(patients.Last_name,patients.First_name)
    when tbl_contactos.tabla_ref='seguros' then seguros.insurance
                else tbl_contacto_persona.id_contactos
            end) as Associate_To

#".$having2."
 from tbl_contacto_persona 
left join tbl_contactos using(id_contactos)

left join physician on tbl_contactos.id_tabla_ref=physician.Phy_id
left join patients on tbl_contactos.id_tabla_ref=patients.Pat_id
left join seguros on tbl_contactos.id_tabla_ref=seguros.ID

#".$having."
 WHERE
    true ".$type_person.$contact." 

    #GROUP BY persona_contacto

    ;"; 









$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}                
                  
?>




        <script  language="JavaScript" type="text/javascript">

                $(document).ready(function() {
                        $('#table_physician').DataTable({                                
                                dom: 'Bfrtip',
                                "scrollX": true,
                                buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                ]
                        } );
                } );
                           
            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);





             function Modificar_contacts(id_persona_contacto,persona_contacto,cargo_persona_contacto,genero,relacion,descripcion,fecha_nacimiento,direccion,email,telefono,fax,id_contactos,type_person,id_person){

var id_persona_contacto = id_persona_contacto;
var persona_contacto = persona_contacto;
var cargo_persona_contacto = cargo_persona_contacto;
var genero = genero;
var relacion = relacion;
var descripcion = descripcion;
var fecha_nacimiento = fecha_nacimiento;
var direccion =direccion;
var email = email;
var telefono = telefono;
var fax = fax;
var id_contactos = id_contactos;
var type_person = type_person;
var id_person = id_person;

                    
/*tablas_dinamicas_javascript_2*/
                //alert(telefono);
                window.location.href = "../contacts/registrar_contacts.php?&id_persona_contacto="+id_persona_contacto+"&persona_contacto="+persona_contacto+"&cargo_persona_contacto="+cargo_persona_contacto+"&genero="+genero+"&relacion="+relacion+"&descripcion="+descripcion+"&fecha_nacimiento="+fecha_nacimiento+"&direccion="+direccion+"&email="+email+"&id_contactos="+id_contactos+"&telefono="+telefono+"&fax="+fax+"&type_person="+type_person+"&id_person="+id_person;   
             
                }


        function Eliminar_contacts(id_persona_contacto,incrementador,accion){

                                        swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+id_persona_contacto+"?",
          type: "warning",
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {

                   var campos_formulario = '&id_persona_contacto='+id_persona_contacto+'&accion='+accion;
                    // CAMBIAR PHYSICAIN A CONTACTS ----   PRIMERO ARREGLAR GESTIONAR CONTACTS
                        $.post(
                                "../../controlador/contacts/eliminar_persona_contacto.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+Phy_id+' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                                    window.location.reload(true);
                                },
                                "json"
                                );

          }
        });


                }


        </script>    
    <div id="bajar_aqui"></div>
            <table align="center" border="0">
                <tr>
                    <td align="center"><b><font size="3">Resultado de la Consulta</font></b></td>
                </tr>
            </table>
        
<div class="col-lg-12">

    <table id="table_physician" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>

                                <th style="width:10px;" >PERSONA CONTACTO</th>
                                <th style="width:10px;" >CARGO</th>
                                <th style="width:10px;" >GENERO</th>
                                <th style="width:10px;" >RELACIÓN</th>
                                <th style="width:10px;" >DESCRICIÓN</th>
                                <th style="width:10px;" >EMAIL</th>
                                <th style="width:10px;" >TELÉFONO</th>
                                <th style="width:10px;" >FAX</th>   
                                <th style="width:10px;" >Associate To</th>                                                                                                                            
                                <th>ACCIÓN</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {

                    echo $color;
                                                    
                        echo '<td align="center"><font size="2"><b>'.$reporte[$i]['persona_contacto'].'</b></font></td>';
                        
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['cargo_persona_contacto'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['genero'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['relacion'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['descripcion'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['email'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['telefono'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['fax'])).'</font></td>';
                echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte[$i]['Associate_To'])).'</font></td>';
                        

                     echo '<td align="center"><font size="2"><div id="resultado_'.$i.'">'
. '<a onclick="Modificar_contacts(\''.$reporte[$i]['id_persona_contacto'].'\',\''.$reporte[$i]['persona_contacto'].'\',\''.$reporte[$i]['cargo_persona_contacto'].'\',\''.$reporte[$i]['genero'].'\',\''.$reporte[$i]['relacion'].'\',\''.$reporte[$i]['descripcion'].'\',\''.$reporte[$i]['fecha_nacimiento'].'\',\''.$reporte[$i]['direccion'].'\',\''.$reporte[$i]['email'].'\',\''.$reporte[$i]['telefono'].'\',\''.$reporte[$i]['fax'].'\',\''.$reporte[$i]['Associate_To']

                    .'\',\''. sanitizeString($conexion, $_GET["type_person"]) .'\',\'' . sanitizeString($conexion, $_GET["contact"]) .'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modificar Physician"  title="Modificar Physician" style="height: 25px; width: 25px" border="0" align="absmiddle"></a><br><br>
'
. '<a onclick="Eliminar_contacts(\''.$reporte[$i]['id_persona_contacto'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar Physician"  title="Eliminar Physician" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';

            echo '</div></font></td>';
            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $i++;
                        }
                        ?>

                    </tbody>
                </table>
            </div>        
      
               <div class="spacer"></div>                        
               
       
</html>
