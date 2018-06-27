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


                    
                    $usuario = $_SESSION['referenciaUsuario'];
       
         
if(isset($_GET["Name"]) && $_GET["Name"] != null){ $Name = " and Name = '".$_GET["Name"]."' "; } else { $Name = null; }
if(isset($_GET["NPI"]) && $_GET["NPI"] != null){ $NPI = " and NPI = '".$_GET["NPI"]."' "; } else { $NPI = null; }
if(isset($_GET["Taxonomy"]) && $_GET["Taxonomy"] != null){ $Taxonomy = " and Taxonomy = '".$_GET["Taxonomy"]."' "; } else { $Taxonomy = null; }
if(isset($_GET["Organization"]) && $_GET["Organization"] != null){ $Organization = " and Organization = '".$_GET["Organization"]."' "; } else { $Organization = null; }
if(isset($_GET["Address"]) && $_GET["Address"] != null){ $Address = " and Address = '".$_GET["Address"]."' "; } else { $Address = null; }
if(isset($_GET["City"]) && $_GET["City"] != null){ $City = " and City = '".$_GET["City"]."' "; } else { $City = null; }
if(isset($_GET["State"]) && $_GET["State"] != null){ $State = " and State = '".$_GET["State"]."' "; } else { $State = null; }
if(isset($_GET["Zip"]) && $_GET["Zip"] != null){ $Zip = " and Zip = '".$_GET["Zip"]."' "; } else { $Zip = null; }
if(isset($_GET["Contact"]) && $_GET["Contact"] != null){ $Contact = " and Contact = '".$_GET["Contact"]."' "; } else { $Contact = null; }
if(isset($_GET["Email"]) && $_GET["Email"] != null){ $Email = " and Email = '".$_GET["Email"]."' "; } else { $Email = null; }
if(isset($_GET["Office_phone"]) && $_GET["Office_phone"] != null){ $Office_phone = " and Office_phone = '".$_GET["Office_phone"]."' "; } else { $Office_phone = null; }
if(isset($_GET["Mobile_phone"]) && $_GET["Mobile_phone"] != null){ $Mobile_phone = " and Mobile_phone = '".$_GET["Mobile_phone"]."' "; } else { $Mobile_phone = null; }
if(isset($_GET["Fax"]) && $_GET["Fax"] != null){ $Fax = " and Fax = '".$_GET["Fax"]."' "; } else { $Fax = null; }

$conexion = conectar();
$sql  = "SELECT * FROM physician WHERE true ".$Name.$NPI.$Taxonomy.$Organization.$Address.$City.$State.$Zip.$Contact.$Email.$Office_phone.$Mobile_phone.$Mobile_phone.$Fax.';'; 
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}
                
                
?>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
<script>
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
           
                function Modificar_physician(Phy_id,Name,NPI,Taxonomy,Organization,Address,City,State,Zip,Contact,Email,Office_phone,Mobile_phone,Fax,id_carriers
/*tablas_dinamicas_javascript_1*/
){
Name = replaceAll(Name,' ','|');
NPI = replaceAll(NPI,' ','|');
Taxonomy = replaceAll(Taxonomy,' ','|');
Organization = replaceAll(Organization,' ','|');
Address = replaceAll(Address,' ','|');
City = replaceAll(City,' ','|');
State = replaceAll(State,' ','|');
Zip = replaceAll(Zip,' ','|');
Contact = replaceAll(Contact,' ','|');
Email = replaceAll(Email,' ','|');
Office_phone = replaceAll(Office_phone,' ','|');
Mobile_phone = replaceAll(Mobile_phone,' ','|');
Fax = replaceAll(Fax,' ','|');

                    
/*tablas_dinamicas_javascript_2*/

                window.location.href = "../physician/registrar_physician.php?&Phy_id="+Phy_id+"&Name="+Name+"&NPI="+NPI+"&Taxonomy="+Taxonomy+"&Organization="+Organization+"&Address="+Address+"&City="+City+"&State="+State+"&Zip="+Zip+"&Contact="+Contact+"&Email="+Email+"&Office_phone="+Office_phone+"&Mobile_phone="+Mobile_phone+"&Fax="+Fax+"&id_carriers="+id_carriers;   
             
                }


        function Eliminar_physician(Phy_id,incrementador,accion){

                                        swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+Phy_id+"?",
          type: "warning",
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {

                   var campos_formulario = '&Phy_id='+Phy_id+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/physician/gestionar_physician.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+Phy_id+' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);

                                },
                                "json"
                                );

          }
        });


                }

            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

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

                                <th style="width:10px;" >ID PHYSICIAN  </th>
                                <!--imagen_s-->
                                <th>NAME  </th>
                                <th>NPI  </th>
                                <th>TAXONOMY  </th>
                                <th>ORGANIZATION  </th>
                                <th>ADDRESS  </th>
                                <th>CITY  </th>
                                <th>STATE  </th>
                                <th>ZIP  </th>
                                <th>CONTACT  </th>
                                <th>EMAIL  </th>
                                <th>OFFICE PHONE  </th>
                                <th>MOBILE PHONE  </th>
                                <th>FAX  </th>
<!--tablas_dinamicas_consulta-->
                                <th>ACCIÓN</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {

  
                    echo $color;
                  
                        echo '<td align="center"><font size="2"><b>'.$reporte[$i]['Phy_id'].'</b></font></td>';
                        /*imagen_i*/
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Name'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['NPI'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Taxonomy'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Organization'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Address'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['City'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['State'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Zip'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Contact'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Email'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Office_phone'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Mobile_phone'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Fax'].'</font></td>';


                        echo '<td align="center"><font size="2"><div id="resultado_'.$i.'">'
. '<a onclick="Modificar_physician(\''.$reporte[$i]['Phy_id'].'\',\''.$reporte[$i]['Name'].'\',\''.$reporte[$i]['NPI'].'\',\''.$reporte[$i]['Taxonomy'].'\',\''.$reporte[$i]['Organization'].'\',\''.$reporte[$i]['Address'].'\',\''.$reporte[$i]['City'].'\',\''.$reporte[$i]['State'].'\',\''.$reporte[$i]['Zip'].'\',\''.$reporte[$i]['Contact'].'\',\''.$reporte[$i]['Email'].'\',\''.$reporte[$i]['Office_phone'].'\',\''.$reporte[$i]['Mobile_phone'].'\',\''.$reporte[$i]['Fax'].'\',\''.$reporte[$i]['id_carriers']

                    .'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modificar Physician"  title="Modificar Physician" style="height: 25px; width: 25px" border="0" align="absmiddle"></a><br><br>
'
. '<a onclick="Eliminar_physician(\''.$reporte[$i]['Phy_id'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar Physician"  title="Eliminar Physician" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';

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
