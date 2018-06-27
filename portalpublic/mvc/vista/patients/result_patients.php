<?php

session_start();
require_once '../../../conex.php';
//en lo comentado modifique una location de redireccion cesar
/*if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="home.php";</script>';
    }
}*/
$conexion = conectar(); 
                    
if(isset($_GET["Last_name_First_name"]) && $_GET["Last_name_First_name"] != null){ $Last_name_First_name = " and Last_name = '".$_GET["Last_name_First_name"]."' "; } else { $Last_name_First_name = null; }
if(isset($_GET["Pat_id"]) && $_GET["Pat_id"] != null){ $Pat_id = " and Pat_id = '".$_GET["Pat_id"]."' "; } else { $Pat_id = null; }
if(isset($_GET["Sex"]) && $_GET["Sex"] != null){ $Sex = " and Sex = '".$_GET["Sex"]."' "; } else { $Sex = null; }
if(isset($_GET["DOB"]) && $_GET["DOB"] != null){ $DOB = " and DOB = '".$_GET["DOB"]."' "; } else { $DOB = null; }
if(isset($_GET["Guardian"]) && $_GET["Guardian"] != null){ $Guardian = " and Guardian = '".$_GET["Guardian"]."' "; } else { $Guardian = null; }
if(isset($_GET["Social"]) && $_GET["Social"] != null){ $Social = " and Social = '".$_GET["Social"]."' "; } else { $Social = null; }
if(isset($_GET["Address"]) && $_GET["Address"] != null){ $Address = " and Address = '".$_GET["Address"]."' "; } else { $Address = null; }
if(isset($_GET["City"]) && $_GET["City"] != null){ $City = " and City = '".$_GET["City"]."' "; } else { $City = null; }
if(isset($_GET["Street"]) && $_GET["Street"] != null){ $Street = " and Street = '".$_GET["Street"]."' "; } else { $Street = null; }
if(isset($_GET["Zip"]) && $_GET["Zip"] != null){ $Zip = " and Zip = '".$_GET["Zip"]."' "; } else { $Zip = null; }
if(isset($_GET["county"]) && $_GET["county"] != null){ $county = " and county = '".$_GET["county"]."' "; } else { $county = null; }
if(isset($_GET["E_mail"]) && $_GET["E_mail"] != null){ $E_mail = " and E_mail = '".$_GET["E_mail"]."' "; } else { $E_mail = null; }
if(isset($_GET["Phone"]) && $_GET["Phone"] != null){ $Phone = " and Phone = '".$_GET["Phone"]."' "; } else { $Phone = null; }
if(isset($_GET["Cell"]) && $_GET["Cell"] != null){ $Cell = " and Cell = '".$_GET["Cell"]."' "; } else { $Cell = null; }               
if(isset($_GET["Phy_NPI"]) && $_GET["Phy_NPI"] != null){ $Phy_NPI = " and Phy_NPI = '".$_GET["Phy_NPI"]."' "; } else { $Phy_NPI = null; }
if(isset($_GET["Auth"]) && $_GET["Auth"] != null){ $Auth = " and Auth = '".$_GET["Auth"]."' "; } else { $Auth = null; }
if(isset($_GET["Auth_2"]) && $_GET["Auth_2"] != null){ $Auth_2 = " and Auth_2 = '".$_GET["Auth_2"]."' "; } else { $Auth_2 = null; }
if(isset($_GET["type_patient"]) && $_GET["type_patient"] != null){ $type_patient = " and id_seguros_type_person = '".$_GET["type_patient"]."' "; } else { $type_patient = null; }


                if(isset($_GET["pcp"]) && $_GET["pcp"] != null){ 
                    
               /*    $sql = 'SELECT Name FROM physician WHERE Phy_id = \''.$_GET["pcp"].'\';';

                    $resultadopcp = ejecutar($sql,$conexion);

                    $pcp_name;
                    while ($row_pcp=mysqli_fetch_array($resultadopcp)) {    

                        $pcp_name = $row_pcp['Name'];

                    }*/

                    $pcp = " and PCP = '".$_GET["pcp"]."' ";                 
                } else { 
                    $pcp = null; 
                    
                }  
                                
                
                if(isset($_GET["Ref_Physician"]) && $_GET["Ref_Physician"] != null){ 
                                             
                  /*  $sql = 'SELECT Name FROM physician WHERE Phy_id = \''.$_GET["Ref_Physician"].'\';';

                    $resultadoRef_Physician = ejecutar($sql,$conexion);

                    $Ref_Physician_name;
                    while ($row_Ref_Physician_name=mysqli_fetch_array($resultadoRef_Physician)) {   

                        $Ref_Physician_name = $row_Ref_Physician_name['Name'];

                    }                   */
                    $Ref_Physician = " and Ref_Physician = '".$_GET["Ref_Physician"]."' ";
                        
                } else { 
                    $Ref_Physician = null;                     
                }
                 
                if(isset($_GET["Pri_Ins"]) && $_GET["Pri_Ins"] != null){  
                
                  /*  $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$_GET["Pri_Ins"].'\';';

                    $resultadoPri_Ins = ejecutar($sql,$conexion);

                    $Pri_Ins_insurance;
                    while ($row_Pri_Ins=mysqli_fetch_array($resultadoPri_Ins)) {    

                        $Pri_Ins_insurance = $row_Pri_Ins['insurance'];

                    }*/
                    $Pri_Ins = " and Pri_Ins = '".$_GET["Pri_Ins"]."' ";
                    
                } else { 
                    $Pri_Ins = null;                     
                }
                
                if(isset($_GET["Sec_INS"]) && $_GET["Sec_INS"] != null){  
                
                   /* $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$_GET["Sec_INS"].'\';';

                    $resultadoSec_INS = ejecutar($sql,$conexion);

                    $Sec_INS_insurance;
                    while ($row_Sec_INS=mysqli_fetch_array($resultadoSec_INS)) {    

                        $Sec_INS_insurance = $row_Sec_INS['insurance'];

                    }*/
                    $Sec_INS = " and Sec_INS = '".$_GET["Sec_INS"]."' ";
                    
                } else { 
                    $Sec_INS = null;                     
                }
                
                if(isset($_GET["Ter_Ins"]) && $_GET["Ter_Ins"] != null){                
                   /* $sql = 'SELECT insurance FROM seguros WHERE ID = \''.$_GET["Ter_Ins"].'\';';

                    $resultadoTer_Ins = ejecutar($sql,$conexion);

                    $Ter_Ins_insurance;
                    while ($row_Ter_Ins=mysqli_fetch_array($resultadoTer_Ins)) {    

                        $Ter_Ins_insurance = $row_Ter_Ins['insurance'];

                    }    */
                    $Ter_Ins = " and Ter_Ins = '".$_GET["Ter_Ins"]."' "; 
                    
                } else { 
                    $Ter_Ins = null;                     
                }


if(isset($_GET["Auth_3"]) && $_GET["Auth_3"] != null){ $Auth_3 = " and Auth_3 = '".$_GET["Auth_3"]."' "; } else { $Auth_3 = null; }
if(isset($_GET["Mem_N"]) && $_GET["Mem_N"] != null){ $Mem_N = " and Mem_# = '".$_GET["Mem_N"]."' "; } else { $Mem_N = null; }
if(isset($_GET["Grp_N"]) && $_GET["Grp_N"] != null){ $Grp_N = " and Grp_# = '".$_GET["Grp_N"]."' "; } else { $Grp_N = null; }
if(isset($_GET["Intake_Agmts"]) && $_GET["Intake_Agmts"] != null){ $Intake_Agmts = " and Intake_Agmts = '".$_GET["Intake_Agmts"]."' "; } else { $Intake_Agmts = null; }
if(isset($_GET["active"]) && $_GET["active"] != null){ $active = " and active = '".$_GET["active"]."' "; } else { $active = null; }

   

$sql  = "SELECT * FROM patients WHERE true ".$Last_name_First_name.$Pat_id.$Sex.$DOB.$Guardian.$Social.$Address.$City.$Street.$Zip.$county.$E_mail.$Phone.$Cell.$pcp.$Ref_Physician.$Phy_NPI.$Pri_Ins.$Auth.$Sec_INS.$Auth_2.$Ter_Ins.$Auth_3.$Mem_N.$Grp_N.$Intake_Agmts.$active.$type_patient.';'; 
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
                        $('#table_patients').DataTable({                                
                                dom: 'Bfrtip',
                                buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                ]
                        } );
                } );
                
                
                function Modificar_patients(id,Pat_id,Last_name,First_name,Sex,DOB,Guardian,Social,Address,City,Street,State,Zip,county,E_mail,Phone,Cell,PCP,PCP_NPI,Ref_Physician,Phy_NPI,Pri_Ins,Auth,Sec_INS,Auth_2,Ter_Ins,Auth_3,Mem_N,Grp_N,Intake_Agmts,active,admision_date,discharge_date,type_patient,id_carriers){ 
//Last_name = replaceAll(Last_name,' ','|');
//First_name = replaceAll(First_name,' ','|');
//Sex = replaceAll(Sex,' ','|');
//DOB = replaceAll(DOB,' ','|');
//Guardian = replaceAll(Guardian,' ','|');
//Social = replaceAll(Social,' ','|');
//Address = replaceAll(Address,' ','|');
//City = replaceAll(City,' ','|');
//Street = replaceAll(Street,' ','|');
//Zip = replaceAll(Zip,' ','|');
//county = replaceAll(county,' ','|');
//E_mail = replaceAll(E_mail,' ','|');
//Phone = replaceAll(Phone,' ','|');
//Cell = replaceAll(Cell,' ','|');
//PCP = replaceAll(PCP,' ','|');
//PCP_NPI = replaceAll(PCP_NPI,' ','|');
//Ref_Physician = replaceAll(Ref_Physician,' ','|');
//Phy_NPI = replaceAll(Phy_NPI,' ','|');
//Pri_Ins = replaceAll(Pri_Ins,' ','|');
//Auth = replaceAll(Auth,' ','|');
//Sec_INS = replaceAll(Sec_INS,' ','|');
//Auth_2 = replaceAll(Auth_2,' ','|');
//Ter_Ins = replaceAll(Ter_Ins,' ','|');
//Auth_3 = replaceAll(Auth_3,' ','|');
//Mem_N = replaceAll(Mem_N,' ','|');
//Grp_N = replaceAll(Grp_N,' ','|');
//Intake_Agmts = replaceAll(Intake_Agmts,' ','|');
//active = replaceAll(active,' ','|');
//admision_date = replaceAll(admision_date,' ','|');
//discharge_date = replaceAll(discharge_date,' ','|');
                    
/*tablas_dinamicas_javascript_2*/

                window.location.href = "../patients/registrar_patients.php?&id="+id+"&Pat_id="+Pat_id+"&Last_name="+Last_name+"&First_name="+First_name+"&Sex="+Sex+"&DOB="+DOB+"&Guardian="+Guardian+"&Social="+Social+"&Address="+Address+"&City="+City+"&Street="+Street+"&State="+State+"&Zip="+Zip+"&county="+county+"&E_mail="+E_mail+"&Phone="+Phone+"&Cell="+Cell+"&PCP="+PCP+"&PCP_NPI="+PCP_NPI+"&Ref_Physician_npi="+Ref_Physician+"&Phy_NPI="+Phy_NPI+"&Pri_Ins="+Pri_Ins+"&Auth="+Auth+"&Sec_INS="+Sec_INS+"&Auth_2="+Auth_2+"&Ter_Ins="+Ter_Ins+"&Auth_3="+Auth_3+"&Mem_N="+Mem_N+"&Grp_N="+Grp_N+"&Intake_Agmts="+Intake_Agmts+"&active="+active+"&admision_date="+admision_date+"&discharge_date="+discharge_date+"&type_patient="+type_patient+"&id_carriers="+id_carriers;
/*tablas_dinamicas_javascript*/            
                
             
                }


        function Eliminar_patients(Pat_id,incrementador,accion){

                       swal({
          title: "Confirmation",
          text: "Are you sure you want to delete patient N° "+Pat_id+"?",
          type: "warning",
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {

                   var campos_formulario = '&Pat_id='+Pat_id+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/patients/gestionar_patients.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+Pat_id+' Eliminado');
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
                    <td align="center"><b><font size="3">Query Results</font></b></td>
                </tr>
            </table>
        
<div class="col-lg-34">

    <table id="table_patients" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>

                                <th style="width:10px;" >PAT ID  </th>
                                <!--imagen_s-->
                                <th>LAST NAME  </th>
                                <th>FIRST NAME </th>                                
                                <th>SEX  </th>
                                <th>DOB  </th>
                           <!--tablas_dinamicas_consulta
     <th>GUARDIAN  </th>
                                <th>SOCIAL  </th>
                                <th>ADDRESS  </th>
                                <th>CITY  </th>
                                <th>STREET  </th>
                                <th>ZIP  </th>
                                <th>COUNTY  </th>
                                <th>E MAIL  </th>
                                <th>PHONE  </th>
                                <th>CELL  </th>
                                <th>REF PHYSICIAN  </th>
                                <th>PHY NPI  </th>
                                <th>PRI INS  </th>
                                <th>AUTH  </th>
                                <th>SEC INS  </th>
                                <th>AUTH 2  </th>
                                <th>TER INS  </th>
                                <th>AUTH 3  </th>
                                <th>MEM N  </th>
                                <th>GRP N  </th>
                                <th>INTAKE AGMTS  </th>       -->
                                <th>ACTIVE  </th>
                                <th>ADMISION DATE  </th>
                                <th>DISCHARGE DATE  </th>
<!--tablas_dinamicas_consulta-->
                                <th>ACTION</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {

  
            if ($reporte[$i]['active'] == 't') { $reporte[$i]['active'] = 'SI';} 
            if ($reporte[$i]['active'] == 'f') { $reporte[$i]['active'] = 'NO';}
            
  
                    echo $color;
                            

                        echo '<td align="center"><font size="2"><b>'.$reporte[$i]['Pat_id'].'</b></font></td>';
                        /*imagen_i*/
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Last_name'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['First_name'].'</font></td>';                        
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Sex'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['DOB'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Guardian'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Social'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Address'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['City'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Street'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Zip'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['county'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['E_mail'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Phone'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Cell'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Ref_Physician'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Phy_NPI'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Pri_Ins'].'</font></td>';
                    //    echo '<td align="center"><font size="2">'.$reporte[$i]['Auth'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Sec_INS'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Auth_2'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Ter_Ins'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Auth_3'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Mem_#'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Grp_#'].'</font></td>';
                     //   echo '<td align="center"><font size="2">'.$reporte[$i]['Intake_Agmts'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['active'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['admision_date'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['discharge_date'].'</font></td>';

/*tablas_dinamicas_consulta*/        
                        echo '<td align="center"><font size="2"><div id="resultado_'.$i.'">'
. '<a onclick="Modificar_patients(\''.urlencode($reporte[$i]['id']).'\',\''.urlencode($reporte[$i]['Pat_id']).'\',\''.urlencode($reporte[$i]['Last_name']).'\',\''.urlencode($reporte[$i]['First_name']).'\',\''.urlencode($reporte[$i]['Sex']).'\',\''.urlencode($reporte[$i]['DOB']).'\',\''.urlencode($reporte[$i]['Guardian']).'\',\''.urlencode($reporte[$i]['Social']).'\',\''.urlencode($reporte[$i]['Address']).'\',\''.urlencode($reporte[$i]['City']).'\',\''.urlencode($reporte[$i]['Street']).'\',\''.urlencode($reporte[$i]['State']).'\',\''.urlencode($reporte[$i]['Zip']).'\',\''.urlencode($reporte[$i]['county']).'\',\''.urlencode($reporte[$i]['E_mail']).'\',\''.urlencode($reporte[$i]['Phone']).'\',\''.urlencode($reporte[$i]['Cell']).'\',\''.urlencode($reporte[$i]['PCP']).'\',\''.urlencode($reporte[$i]['PCP_NPI']).'\',\''.urlencode($reporte[$i]['Ref_Physician']).'\',\''.urlencode($reporte[$i]['Phy_NPI']).'\',\''.urlencode($reporte[$i]['Pri_Ins']).'\',\''.urlencode($reporte[$i]['Auth']).'\',\''.urlencode($reporte[$i]['Sec_INS']).'\',\''.urlencode($reporte[$i]['Auth_2']).'\',\''.urlencode($reporte[$i]['Ter_Ins']).'\',\''.urlencode($reporte[$i]['Auth_3']).'\',\''.urlencode($reporte[$i]['Mem_#']).'\',\''.urlencode($reporte[$i]['Grp_#']).'\',\''.urlencode($reporte[$i]['Intake_Agmts']).'\',\''.urlencode($reporte[$i]['active']).'\',\''.urlencode($reporte[$i]['admision_date']).'\',\''.urlencode($reporte[$i]['discharge_date']).'\',\''.urlencode($reporte[$i]['id_seguros_type_person']).'\',\''.urlencode($reporte[$i]['id_carriers'])
/*tablas_dinamicas_modificar*/
                    .'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modify Patients"  title="Modificar Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a><br><br>
'
. '<a onclick="Eliminar_patients(\''.$reporte[$i]['Pat_id'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Delete Patient"  title="Eliminar Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';
/*pdf*/

/*word*/

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