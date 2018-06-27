                                          
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
            
            if(isset($_GET["Ref_id"]) && $_GET["Ref_id"] != null){ $Ref_id = $_GET["Ref_id"]; } else { $Ref_id = 'true'; }
            if(isset($_GET["type_patient"]) && $_GET["type_patient"] != null){ $type_patient = $_GET["type_patient"]; } else { $type_patient = 'true'; }
            if(isset($_GET['Last_name_First_name']) && $_GET['Last_name_First_name'] != null){ $id_referral = "id_referral = ".$_GET['Last_name_First_name']; } else { $id_referral = 'true'; }
            if(isset($_GET["Sex"]) && $_GET["Sex"] != null){ $Sex = "Sex = '".strtolower($_GET["Sex"]."'"); } else { $Sex = 'true'; }
            if(isset($_GET["DOB"]) && $_GET["DOB"] != null){ $DOB = "DOB = '".strtolower($_GET["DOB"]."'"); } else { $DOB = 'true'; }
            if(isset($_GET["Guardian"]) && $_GET["Guardian"] != null){ $Guardian = "Guardian = ''".strtolower($_GET["Guardian"]."''"); } else { $Guardian = 'true'; }
            if(isset($_GET["Social"]) && $_GET["Social"] != null){ $Social = "Social = ''".strtolower($_GET["Social"]."''"); } else { $Social = 'true'; }
            if(isset($_GET["Address"]) && $_GET["Address"] != null){ $Address = "Address = ''".strtolower($_GET["Address"]."''"); } else { $Address = 'true'; }
            if(isset($_GET["City"]) && $_GET["City"] != null){ $City = "City = ''".strtolower($_GET["City"]."''"); } else { $City = 'true'; }
            if(isset($_GET["State"]) && $_GET["State"] != null){ $State = "State = ''".strtolower($_GET["State"]."''"); } else { $State = 'true'; }
            if(isset($_GET["Zip"]) && $_GET["Zip"] != null){ $Zip = "Zip = ''".strtolower($_GET["Zip"]."''"); } else { $Zip = 'true'; }
            if(isset($_GET["county"]) && $_GET["county"] != null){ $county = "county = ''".strtolower($_GET["county"]."''"); } else { $county = 'true'; }
            if(isset($_GET["E_mail"]) && $_GET["E_mail"] != null){ $E_mail = "E_mail = ''".strtolower($_GET["E_mail"]."''"); } else { $E_mail = 'true'; }
            if(isset($_GET["Phone"]) && $_GET["Phone"] != null){ $Phone = "Phone = ''".strtolower($_GET["Phone"]."''"); } else { $Phone = 'true'; }
            if(isset($_GET["Cell"]) && $_GET["Cell"] != null){ $Cell = "Cell = ''".strtolower($_GET["Cell"]."''"); } else { $Cell = 'true'; }
            if(isset($_GET["PCP"]) && $_GET["PCP"] != null){ $PCP = "PCP = ''".strtolower($_GET["PCP"]."''"); } else { $PCP = 'true'; }
            if(isset($_GET["PCP_NPI"]) && $_GET["PCP_NPI"] != null){ $PCP_NPI = "PCP_NPI = ''".strtolower($_GET["PCP_NPI"]."''"); } else { $PCP_NPI = 'true'; }
            if(isset($_GET["Ref_Physician"]) && $_GET["Ref_Physician"] != null){ $Ref_Physician = "Ref_Physician = ''".strtolower($_GET["Ref_Physician"]."''"); } else { $Ref_Physician = 'true'; }
            if(isset($_GET["Phy_NPI"]) && $_GET["Phy_NPI"] != null){ $Phy_NPI = "Phy_NPI = ''".strtolower($_GET["Phy_NPI"]."''"); } else { $Phy_NPI = 'true'; }
            if(isset($_GET["Pri_Ins"]) && $_GET["Pri_Ins"] != null){ $Pri_Ins = "Pri_Ins = ''".strtolower($_GET["Pri_Ins"]."''"); } else { $Pri_Ins = 'true'; }
            if(isset($_GET["Auth"]) && $_GET["Auth"] != null){ $Auth = "Auth = ''".strtolower($_GET["Auth"]."''"); } else { $Auth = 'true'; }
            if(isset($_GET["Sec_INS"]) && $_GET["Sec_INS"] != null){ $Sec_INS = "Sec_INS = ''".strtolower($_GET["Sec_INS"]."''"); } else { $Sec_INS = 'true'; }
            if(isset($_GET["Auth_2"]) && $_GET["Auth_2"] != null){ $Auth_2 = "Auth_2 = ''".strtolower($_GET["Auth_2"]."''"); } else { $Auth_2 = 'true'; }
            if(isset($_GET["Ter_Ins"]) && $_GET["Ter_Ins"] != null){ $Ter_Ins = "Ter_Ins = ''".strtolower($_GET["Ter_Ins"]."''"); } else { $Ter_Ins = 'true'; }
            if(isset($_GET["Auth_3"]) && $_GET["Auth_3"] != null){ $Auth_3 = "Auth_3 = ''".strtolower($_GET["Auth_3"]."''"); } else { $Auth_3 = 'true'; }
            if(isset($_GET["Mem_n"]) && $_GET["Mem_n"] != null){ $Mem_n = "`Mem_#` = ''".strtolower($_GET["Mem_n"]."''"); } else { $Mem_n = 'true'; }
            if(isset($_GET["Grp_n"]) && $_GET["Grp_n"] != null){ $Grp_n = "`Grp_#` = ''".strtolower($_GET["Grp_n"]."''"); } else { $Grp_n = 'true'; }
            if(isset($_GET["Intake_Agmts"]) && $_GET["Intake_Agmts"] != null){ $Intake_Agmts = "Intake_Agmts = ''".strtolower($_GET["Intake_Agmts"]."''"); } else { $Intake_Agmts = 'true'; }
            if(isset($_GET["Table_name"]) && $_GET["Table_name"] != null){ $Table_name = "Table_name = ''".strtolower($_GET["Table_name"]."''"); } else { $Table_name = 'true'; }
            if(isset($_GET["Thi_Ins"]) && $_GET["Thi_Ins"] != null){ $Thi_Ins = "Thi_Ins = ''".strtolower($_GET["Thi_Ins"]."''"); } else { $Thi_Ins = 'true'; }            
            
            if($_GET["active"] == '1'){ 
                $active = "active = '1'";                 
            } else { 
                if($_GET["active"] == '0'){ 
                    $active = "active = '0'";                     
                }else{ 
                    $active = 'true';                     
                }                
            }
            
            if($_GET["converted"] == '1'){ 
                $convertido = " convertido = '1'";                 
            } else { 
                if($_GET["converted"] == '0'){ 
                    $convertido = " convertido = '0'";                     
                }else{ 
                    $convertido = "true";                     
                }                
            }
            if(isset($_GET["admision_date"]) && $_GET["admision_date"] != null){ $admision_date = "admision_date = ''".strtolower($_GET["admision_date"]."''"); } else { $admision_date = 'true'; }
            

            $conexion = conectar();
                 $joins = '';
            
            $where = " WHERE ".$id_referral." and ".$Ref_id." and ".$Sex." and ".$DOB." and ".$Guardian." and ".$Social." and ".$Address." and ".$City." and ".$State." and ".$Zip." and ".$county." and ".$E_mail." and ".$Phone." and ".$Cell." and ".$PCP." and ".$PCP_NPI." and ".$Ref_Physician." and ".$Phy_NPI." and ".$Pri_Ins." and ".$Auth." and ".$Sec_INS." and ".$Auth_2." and ".$Ter_Ins." and ".$Auth_3." and ".$Mem_n." and ".$Grp_n." and ".$Intake_Agmts." and ".$Table_name." and ".$Thi_Ins." and ".$active." and ".$admision_date." and ".$convertido;

            $sql  = "SELECT * FROM tbl_referral tbl_".$joins.$where;
            
            $resultado = ejecutar($sql,$conexion);

            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
                
                
?>
    <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>        
        
        <script  language="JavaScript" type="text/javascript">
                                      
                $(document).ready(function() {
                        $('#table_kidswork_therapy').dataTable( {                               
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


                function referral_to_patient(id_referral,Last_name,First_name,Ref_id,Sex,DOB,Guardian,Social,Address,City,State,Zip,county,E_mail,Phone,Cell,PCP,PCP_NPI,Ref_Physician,Phy_NPI,Pri_Ins,Auth,Sec_INS,Auth_2,Ter_Ins,Auth_3,Mem_n,Grp_n,Intake_Agmts,Table_name,Thi_Ins,active,admision_date,type_patient,valores_nulos,incrementador){
                    id_referral = replaceAll(id_referral,' ','|');
                            Last_name = replaceAll(Last_name,' ','|');
                            First_name = replaceAll(First_name,' ','|');
                            Ref_id = replaceAll(Ref_id,' ','|');
                            Sex = replaceAll(Sex,' ','|');
                            DOB = replaceAll(DOB,' ','|');
                            Guardian = replaceAll(Guardian,' ','|');
                            Social = replaceAll(Social,' ','|');
                            Address = replaceAll(Address,' ','|');
                            City = replaceAll(City,' ','|');
                            State = replaceAll(State,' ','|');
                            Zip = replaceAll(Zip,' ','|');
                            county = replaceAll(county,' ','|');
                            E_mail = replaceAll(E_mail,' ','|');
                            Phone = replaceAll(Phone,' ','|');
                            Cell = replaceAll(Cell,' ','|');
                            PCP = replaceAll(PCP,' ','|');
                            PCP_NPI = replaceAll(PCP_NPI,' ','|');
                            Ref_Physician = replaceAll(Ref_Physician,' ','|');
                            Phy_NPI = replaceAll(Phy_NPI,' ','|');
                            Pri_Ins = replaceAll(Pri_Ins,' ','|');
                            Auth = replaceAll(Auth,' ','|');
                            Sec_INS = replaceAll(Sec_INS,' ','|');
                            Auth_2 = replaceAll(Auth_2,' ','|');
                            Ter_Ins = replaceAll(Ter_Ins,' ','|');
                            Auth_3 = replaceAll(Auth_3,' ','|');
                            Mem_n = replaceAll(Mem_n,' ','|');
                            Grp_n = replaceAll(Grp_n,' ','|');
                            Intake_Agmts = replaceAll(Intake_Agmts,' ','|');
                            Table_name = replaceAll(Table_name,' ','|');
                            Thi_Ins = replaceAll(Thi_Ins,' ','|');
                            active = replaceAll(active,' ','|');
                            admision_date = replaceAll(admision_date,' ','|');
                            type_patient = replaceAll(type_patient,' ','|');
                            
                    if(valores_nulos == 'si'){
                        swal({
          title: "Confirmación",
          text: "Debe completar el formulario de Registro!",
          type: "warning",
          showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Completar",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                                 
                      window.location.href = "../referral/registrar_referral.php?&id_referral="+id_referral+"&Last_name="+Last_name+"&First_name="+First_name+"&Ref_id="+Ref_id+"&Sex="+Sex+"&DOB="+DOB+"&Guardian="+Guardian+"&Social="+Social+"&Address="+Address+"&City="+City+"&State="+State+"&Zip="+Zip+"&county="+county+"&E_mail="+E_mail+"&Phone="+Phone+"&Cell="+Cell+"&PCP="+PCP+"&PCP_NPI="+PCP_NPI+"&Ref_Physician="+Ref_Physician+"&Phy_NPI="+Phy_NPI+"&Pri_Ins="+Pri_Ins+"&Auth="+Auth+"&Sec_INS="+Sec_INS+"&Auth_2="+Auth_2+"&Ter_Ins="+Ter_Ins+"&Auth_3="+Auth_3+"&Mem_n="+Mem_n+"&Grp_n="+Grp_n+"&Intake_Agmts="+Intake_Agmts+"&Table_name="+Table_name+"&Thi_Ins="+Thi_Ins+"&active="+active+"&admision_date="+admision_date+"&type_patient="+type_patient+'&accion=convertir';   
                   }
        });
                        
                    }else{
                        swal({
          title: "Confirmación",
          text: "Esta seguro que desea pasar a paciente esta persona!",
          type: "warning",
          showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Convertir",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                                 
                      var campos_formulario = '&id_referral='+id_referral+'&Last_name='+Last_name+'&First_name='+First_name+'&Ref_id='+Ref_id+'&accion=convertir&update=no';
                        
                        $.post(
                                "../../controlador/referral/gestionar_referral.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    swal({
                                        title: "<h3><b>La conversion se realizo con exito(Linea 159 vista/referral/result_referral.php)<b></h3>",
                                        type: "success",
                                        //html: "<h4>"+nombres_campos+"</h4>",
                                        showCancelButton: false,
                                        animation: "slide-from-top",
                                        closeOnConfirm: true,
                                        showLoaderOnConfirm: false,
                                      });
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                                    setTimeout(function(){
                                        validar_formulario();
                                    },1500);
                                },
                                "json" 
                                ); 
                   }
        });
                    }             
                    
                }
                
                function Modificar_referral(id_referral,Last_name,First_name,Ref_id,Sex,DOB,Guardian,Social,Address,City,State,Zip,county,E_mail,Phone,Cell,PCP,PCP_NPI,Ref_Physician,Phy_NPI,Pri_Ins,Auth,Sec_INS,Auth_2,Ter_Ins,Auth_3,Mem_n,Grp_n,Intake_Agmts,Table_name,Thi_Ins,active,admision_date,type_patient,id_carriers){
                        id_referral = replaceAll(id_referral,' ','|');
                            Last_name = replaceAll(Last_name,' ','|');
                            First_name = replaceAll(First_name,' ','|');
                            Ref_id = replaceAll(Ref_id,' ','|');
                            Sex = replaceAll(Sex,' ','|');
                            DOB = replaceAll(DOB,' ','|');
                            Guardian = replaceAll(Guardian,' ','|');
                            Social = replaceAll(Social,' ','|');
                            Address = replaceAll(Address,' ','|');
                            City = replaceAll(City,' ','|');
                            State = replaceAll(State,' ','|');
                            Zip = replaceAll(Zip,' ','|');
                            county = replaceAll(county,' ','|');
                            E_mail = replaceAll(E_mail,' ','|');
                            Phone = replaceAll(Phone,' ','|');
                            Cell = replaceAll(Cell,' ','|');
                            PCP = replaceAll(PCP,' ','|');
                            PCP_NPI = replaceAll(PCP_NPI,' ','|');
                            Ref_Physician = replaceAll(Ref_Physician,' ','|');
                            Phy_NPI = replaceAll(Phy_NPI,' ','|');
                            Pri_Ins = replaceAll(Pri_Ins,' ','|');
                            Auth = replaceAll(Auth,' ','|');
                            Sec_INS = replaceAll(Sec_INS,' ','|');
                            Auth_2 = replaceAll(Auth_2,' ','|');
                            Ter_Ins = replaceAll(Ter_Ins,' ','|');
                            Auth_3 = replaceAll(Auth_3,' ','|');
                            Mem_n = replaceAll(Mem_n,' ','|');
                            Grp_n = replaceAll(Grp_n,' ','|');
                            Intake_Agmts = replaceAll(Intake_Agmts,' ','|');
                            Table_name = replaceAll(Table_name,' ','|');
                            Thi_Ins = replaceAll(Thi_Ins,' ','|');
                            active = replaceAll(active,' ','|');
                            admision_date = replaceAll(admision_date,' ','|');
                            type_patient = replaceAll(type_patient,' ','|');
                            
                    
                window.location.href = "../referral/registrar_referral.php?&id_referral="+id_referral+"&Last_name="+Last_name+"&First_name="+First_name+"&Ref_id="+Ref_id+"&Sex="+Sex+"&DOB="+DOB+"&Guardian="+Guardian+"&Social="+Social+"&Address="+Address+"&City="+City+"&State="+State+"&Zip="+Zip+"&county="+county+"&E_mail="+E_mail+"&Phone="+Phone+"&Cell="+Cell+"&PCP="+PCP+"&PCP_NPI="+PCP_NPI+"&Ref_Physician="+Ref_Physician+"&Phy_NPI="+Phy_NPI+"&Pri_Ins="+Pri_Ins+"&Auth="+Auth+"&Sec_INS="+Sec_INS+"&Auth_2="+Auth_2+"&Ter_Ins="+Ter_Ins+"&Auth_3="+Auth_3+"&Mem_n="+Mem_n+"&Grp_n="+Grp_n+"&Intake_Agmts="+Intake_Agmts+"&Table_name="+Table_name+"&Thi_Ins="+Thi_Ins+"&active="+active+"&admision_date="+admision_date+"&type_patient="+type_patient+"&id_carriers="+id_carriers;   
             
                }
    

        function Eliminar_referral(id_referral,incrementador,accion){
                    
                                        swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+id_referral+"?",
          type: "warning",
          showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Eliminar",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                 
                
                   var campos_formulario = '&id_referral='+id_referral+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/referral/gestionar_referral.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '++' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                                    setTimeout(function(){
                                        validar_formulario();
                                    },1500);
                                },
                                "json" 
                                ); 
            
          }
        });
                    
                                                                            
                }
                    

/*modulos_dependientes_j*/

            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 1000);
    
        </script>
        
    <div id="bajar_aqui"></div>
        <br>
            <table align="center" border="0">
                <tr>
                    <td align="center"><b><font size="3">Resultado de la Consulta</font></b></td>
                </tr>
            </table>
        
<div class="col-lg-12">

                        <table id="table_kidswork_therapy" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>                            
            <th>LAST NAME</th>
            <th>FIRST NAME</th>
            <th>REF ID</th>
            <th>SEX</th>
            <th>DOB</th>            
            <th>PHONE</th>
            <th>ACTIVE</th>
            <th>ADMISION DATE</th>
            <th>ACCIÓN</th>
                 
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        //echo recursive_array_search(null,$reporte); die;
                        $valoresNull = 'no';
                        while (isset($reporte[$i])) {
                        
                          
                    echo $color;             
             
             if($reporte[$i]['Last_name'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['Last_name'].'</font></td>';
             if($reporte[$i]['First_name'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['First_name'].'</font></td>';
             if($reporte[$i]['Ref_id'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['Ref_id'].'</font></td>';
             if($reporte[$i]['Sex'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['Sex'].'</font></td>';
             if($reporte[$i]['DOB'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['DOB'].'</font></td>';
             if($reporte[$i]['Phone'] == null){
                 $valoresNull = 'si';
             }             
             echo '<td align="center"><font size="2">'.$reporte[$i]['Phone'].'</font></td>';             
             if($reporte[$i]['active'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['active'].'</font></td>';
             if($reporte[$i]['admision_date'] == null){
                 $valoresNull = 'si';
             }
             echo '<td align="center"><font size="2">'.$reporte[$i]['admision_date'].'</font></td>';             
             echo '<td align="center"><font size="2"><div id="resultado_'.$i.'"><a onclick="Modificar_referral(\''.$reporte[$i]['id_referral'].'\',\''.$reporte[$i]['Last_name'].'\',\''.$reporte[$i]['First_name'].'\',\''.$reporte[$i]['Ref_id'].'\',\''.$reporte[$i]['Sex'].'\',\''.$reporte[$i]['DOB'].'\',\''.$reporte[$i]['Guardian'].'\',\''.$reporte[$i]['Social'].'\',\''.$reporte[$i]['Address'].'\',\''.$reporte[$i]['City'].'\',\''.$reporte[$i]['State'].'\',\''.$reporte[$i]['Zip'].'\',\''.$reporte[$i]['county'].'\',\''.$reporte[$i]['E_mail'].'\',\''.$reporte[$i]['Phone'].'\',\''.$reporte[$i]['Cell'].'\',\''.$reporte[$i]['PCP'].'\',\''.$reporte[$i]['PCP_NPI'].'\',\''.$reporte[$i]['Ref_Physician'].'\',\''.$reporte[$i]['Phy_NPI'].'\',\''.$reporte[$i]['Pri_Ins'].'\',\''.$reporte[$i]['Auth'].'\',\''.$reporte[$i]['Sec_INS'].'\',\''.$reporte[$i]['Auth_2'].'\',\''.$reporte[$i]['Ter_Ins'].'\',\''.$reporte[$i]['Auth_3'].'\',\''.$reporte[$i]['Mem_#'].'\',\''.$reporte[$i]['Grp_#'].'\',\''.$reporte[$i]['Intake_Agmts'].'\',\''.$reporte[$i]['Table_name'].'\',\''.$reporte[$i]['Thi_Ins'].'\',\''.$reporte[$i]['active'].'\',\''.$reporte[$i]['admision_date'].'\',\''.$reporte[$i]['type_patient'].'\',\''.$reporte[$i]['id_carriers'].'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/save.png" alt="Modificar Referral"  title="Modificar Referral" width="25" height="25" border="0" align="absmiddle"></a>&nbsp;<a onclick="Eliminar_referral(\''.$reporte[$i]['id_referral'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar Referral"  title="Eliminar Referral" width="25" height="25" border="0" align="absmiddle"></a>';
             $valoresNull='si';
             if($reporte[$i]['convertido'] == 0)
                 
                 
                echo '<a onclick="referral_to_patient(\''.$reporte[$i]['id_referral'].'\',\''.$reporte[$i]['Last_name'].'\',\''.$reporte[$i]['First_name'].'\',\''.$reporte[$i]['Ref_id'].'\',\''.$reporte[$i]['Sex'].'\',\''.$reporte[$i]['DOB'].'\',\''.$reporte[$i]['Guardian'].'\',\''.$reporte[$i]['Social'].'\',\''.$reporte[$i]['Address'].'\',\''.$reporte[$i]['City'].'\',\''.$reporte[$i]['State'].'\',\''.$reporte[$i]['Zip'].'\',\''.$reporte[$i]['county'].'\',\''.$reporte[$i]['E_mail'].'\',\''.$reporte[$i]['Phone'].'\',\''.$reporte[$i]['Cell'].'\',\''.$reporte[$i]['PCP'].'\',\''.$reporte[$i]['PCP_NPI'].'\',\''.$reporte[$i]['Ref_Physician'].'\',\''.$reporte[$i]['Phy_NPI'].'\',\''.$reporte[$i]['Pri_Ins'].'\',\''.$reporte[$i]['Auth'].'\',\''.$reporte[$i]['Sec_INS'].'\',\''.$reporte[$i]['Auth_2'].'\',\''.$reporte[$i]['Ter_Ins'].'\',\''.$reporte[$i]['Auth_3'].'\',\''.$reporte[$i]['Mem_#'].'\',\''.$reporte[$i]['Grp_#'].'\',\''.$reporte[$i]['Intake_Agmts'].'\',\''.$reporte[$i]['Table_name'].'\',\''.$reporte[$i]['Thi_Ins'].'\',\''.$reporte[$i]['active'].'\',\''.$reporte[$i]['admision_date'].'\',\''.$reporte[$i]['type_patient'].'\',\''.$valoresNull.'\',\''.$i.'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/new.jpg" alt="Modificar Referral"  title="Modificar Referral" width="25" height="25" border="0" align="absmiddle"></a>';

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

