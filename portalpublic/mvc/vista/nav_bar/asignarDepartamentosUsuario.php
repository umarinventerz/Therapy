<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
if(isset($_SESSION['name'])){
	$_POST['name'] = trim($_SESSION['name']);
	$_POST['find'] = $_SESSION['find'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: KIDWORKS THERAPY :.</title>

<link href="../../../css/portfolio-item.css" rel="stylesheet">
<link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link href="../../../css/style_v1.css" rel="stylesheet">

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../js/devoops_ext.js"></script>
<script src="../../../js/listas.js"></script>
   
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css"> 	
   
    
    
        <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css">  
        
        <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/promise.min.js"></script> 
        <script type="text/javascript" language="javascript" src="../../../js/jquery.price_format.2.0.min.js"></script> 
    <script type="text/javascript" language="javascript" class="init">
	    
	$(document).ready(function() {
		$('#example').DataTable({
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		} );
	} );

    </script>
    <script>

	function selectAllFields(id_check_select,num,id_check,input_hidden,valor_hidden,parametersShowAll){       
                                                    
            $("input:checkbox").each(function(){
                if($("input[name="+id_check+"]:checked").length == 1){                            
                    //alert(this.name.substring(0,12));
                    if(this.name.substring(0,num) == id_check_select){
                        this.checked = true; 
                    }

                }else{
                    if(this.name.substring(0,num) == id_check_select || id_check == 'allFields'){
                        this.checked = false;                   
                    }
                }            
            });
            if($("input[name="+id_check+"]:checked").length == 1){
                $("#"+input_hidden).val(valor_hidden);
            }else{
                $("#"+input_hidden).val('');
            }
            showDivDepartament();
            if(id_check == 'allFields')
            showAll(parametersShowAll);
                    
        }
	
	function findData(){	
            
                var nombres_campos = '';
                
                if($('#name').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * User </td></tr></table>';
                }                
                if($('#fieldsDepartaments').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Seleccione al menos un departamento</td></tr></table>';
                }
                
                if($("input[name=departaments_5]:checked").length == 1){
                    if($('#div_departaments_5_tabsHidden').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Seleccione un tabs de Administration</td></tr></table>';
                    }
                }
                
                if($("input[name=departaments_2]:checked").length == 1){
                    if($('#div_departaments_2_tabsHidden').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Seleccione un tabs de Back Office</td></tr></table>';
                    }
                }
                
                if($("input[name=departaments_3]:checked").length == 1){
                    if($('#div_departaments_3_tabsHidden').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Seleccione un tabs de Revenew Management</td></tr></table>';
                    }
                }
                
                if($("input[name=departaments_1]:checked").length == 1){
                    if($('#div_departaments_1_tabsHidden').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Seleccione un tabs de Patient Relation Management</td></tr></table>';
                    }
                }
                
                if($("input[name=departaments_4]:checked").length == 1){
                    if($('#div_departaments_4_tabsHidden').val() == ''){
                        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Seleccione un tabs de Electronics Records Management</td></tr></table>';
                    }
                }
                
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
        
                }else{
                    var campos_formulario = $("#myForm").serialize();
                    $.post(
                        "../../controlador/nav_bar/gestionarDepartamentosUsuarios.php",
                        campos_formulario,
                        function (resultado_controlador) {
                           // mostrar_datos(resultado_controlador);  
                           //alert(resultado_controlador.query_1);
                           //alert(resultado_controlador.query_2);
                           swal({
                                type: 'success',                          
                                html: '<h3><b>'+resultado_controlador.mensaje+'</b></h3>',
                                timer: 3000    
                              }
                              ); 
                              setTimeout(function(){location.reload()},1500);
                        },
                        "json" 
                    );
                    return false;  
                }
	}
        
	function loadOrderFieldsShow(valor,input_hidden){
                var cadena;
		$("input:checkbox[name="+valor+"]").each(function(){                        
			if(this.checked == true){
				if($("#"+input_hidden).val() == '')
					$("#"+input_hidden).val('|'+this.value+'|');					
				else{
                                    cadena = $("#"+input_hidden).val()+',|'+this.value+'|';                                    
                                    res = cadena.replace(",,", ",");
                                    $("#"+input_hidden).val(res);
                                }
					
                                
			}else{
				var str = $("#"+input_hidden).val();	
                                //alert(str);
				var res = str.replace('|'+this.value+'|',"");                                
				res = res.replace(",,", ",");
                                if(res.substring(0,1)== ','){
                                    res = res.substring(1,res.length);
                                }
				$("#"+input_hidden).val(res);
			}
        	});		
	}
       
//	function blockCheckBox1(){
//		if($("#patient_id").val() != ''){
//			$("input:checkbox").each(function(){        						
//				$(this).attr('disabled','disabled');				
//                	});	
//		}else{
//			$("input:checkbox").each(function(){  					      						
//				this.disabled = false;
//                	});
//		}
//	}   
//	function updatePatient(pat_id,company,patient_name){		
//		$("#result_u"+pat_id).load("../../controlador/patients/update_patients.php","&Patient_id="+pat_id+"&Company="+company+"&newInsurance="+$("#newInsurance_"+pat_id).val()+"&patient_name="+patient_name);
//		alert("Update Succefull");
//		window.location="search_patient.php";
//	}
        function showAll(parametersShowAll){
            //alert(parametersShowAll);
            if($("input[name=allFields]:checked").length == 1){ 
                var arrayparametersShowAll = parametersShowAll.split(',');
                r=0;
                while(arrayparametersShowAll[r]){
                    $("#"+arrayparametersShowAll[r]).show();
                    r++;
                }                
            }else{
                var arrayparametersShowAll = parametersShowAll.split(',');
                r=0;
                while(arrayparametersShowAll[r]){
                    $("#"+arrayparametersShowAll[r]).hide();
                    r++;
                }
            }
        }
        function showDivDepartament(name,id_div){               
            if($("input[name="+name+"]:checked").length == 1){                
                $("#"+id_div).show();
            }else{
                $("#"+id_div).hide();
            }            
        }
        function changeSelect(valor){
            $('#name').val(valor).change();
        }
        function loadUser(valor){            
            //if(a == 'load'){
                //$('#name').val(valor).change();                
            //}                     
            $("#myForm").closest('form').find("input[type=hidden]").val("");
            $("#myForm").closest('form').find("input[type=checkbox]").prop("checked",false);
            $.post(
                "loadUser.php",
                "&id_user="+valor,
                function (resultado_controlador) { 
                   if(resultado_controlador.resultado_query){
                       swal({
                                type: 'error',                          
                                html: '<h3><b>No posee datos registrados</b></h3>',
                                timer: 3000    
                            });
                        return false;      
                   }else{
                        var t=0;
                        var r=0;
                        while(resultado_controlador.departament[t]){
                            $("#departaments_"+resultado_controlador.departament[t]).prop("checked", true);                       
                            showDivDepartament("departaments_"+resultado_controlador.departament[t],'div_departaments_'+resultado_controlador.departament[t]);
                            loadOrderFieldsShow("departaments_"+resultado_controlador.departament[t],'fieldsDepartaments'); 

                             r=0;
                             while(resultado_controlador.tabs[resultado_controlador.departament[t]][r]){                                 
                                 $("#div_departaments_"+resultado_controlador.departament[t]+'_tabs'+'_'+resultado_controlador.tabs[resultado_controlador.departament[t]][r]).prop("checked", true);
                                 loadOrderFieldsShow('div_departaments_'+resultado_controlador.departament[t]+'_tabs_'+resultado_controlador.tabs[resultado_controlador.departament[t]][r],'div_departaments_'+resultado_controlador.departament[t]+'_tabsHidden');
                                 r++;
                             }
                            t++;
                        }
                   } 
                                      
                },
                "json" 
            );
        }
      
      
//$("#chkStatus").prop("checked", "");
//$("#chkStatus").prop("checked", "checked");
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>


    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
               <br>			 
			<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-lg-12">
                <form class="form-horizontal" id="myForm" onSubmit="return findData();">
		    <div class="col-lg-12">
		        <h3 class="page-header">Departaments by user</h3>
		    </div>
		    <div class="row">	
                        <!--<label class="col-xs-1 form-control-label text-right"><font color="#585858">User:</font></label>-->
			<div class="col-xs-3">
			    <div class="input-group">
				 <select style="width:250px" name='name' id='name' class="populate placeholder" onchange="loadUser(this.value)">
					<option value=''>--- SELECT ---</option>				
				    <?php
					$sql  = "Select * from user_system order by Last_name";
					$conexion = conectar();
					$resultado = ejecutar($sql,$conexion);
					while ($row=mysqli_fetch_array($resultado)){	                                                                                        
                                            print("<option value='".$row["user_id"]."'>".$row["Last_name"]." ".$row["First_name"]." </option>");
					}                                        
				    ?>
					  
			        </select>
			    </div>
			</div>	
                    </div>    
                    <hr>
                    <div class="row">    
                        <div class="col-xs-12">
                            <div class="input-group">
                                    <?php
                                        $sql  = "Select * from tbl_departaments_company order by departaments_company";
                                        $conexion = conectar();
                                        $resultado_1 = ejecutar($sql,$conexion);
                                        //$resultado_1 = $resultado;
                                        while ($row=mysqli_fetch_array($resultado_1)) 
                                        {	                                            
                                            $allDepartaments .=  'div_departaments_'.$row["id_departaments_company"].',';
                                            $allIdDepartaments .=  '|'.$row["id_departaments_company"].'|,';
                                            $i++;                                
                                        }
                                        $allDepartaments = substr ($allDepartaments, 0, strlen($allDepartaments) - 1);
                                        $allIdDepartaments = substr ($allIdDepartaments, 0, strlen($allIdDepartaments) - 1);
                                    ?>
                                    <label><input name="allFields" type="checkbox" onclick="selectAllFields('departaments_',13,'allFields','fieldsDepartaments','<?php echo $allIdDepartaments;?>','<?php echo $allDepartaments;?>');"> All Departaments<input name="fieldsDepartaments" id="fieldsDepartaments" type="hidden"></label>
                            </div>
                        </div>
		    </div>
		  
    		    <hr>
                    <?php 
                        
                        $sql  = "Select * from tbl_departaments_company order by departaments_company";
                        $conexion = conectar();
                        $resultado = ejecutar($sql,$conexion);
                        $i= 0;
                        while ($row=mysqli_fetch_array($resultado)) 
                        {	
                            if($i%3 == 0 && $i != 0){
                                    echo '</div>';
                                    echo '<div class="row">';					
                            }else{
                                    if($i == 0){
                                            echo '<div class="row">';					
                                    }
                            }
                            echo '<div class="col-xs-4">
                                    <div class="input-group">						
                                            <label><input name="departaments_'.$row["id_departaments_company"].'" id="departaments_'.$row["id_departaments_company"].'" value="'.$row["id_departaments_company"].'" type="checkbox" onclick="showDivDepartament(\'departaments_'.$row["id_departaments_company"].'\',\'div_departaments_'.$row["id_departaments_company"].'\');loadOrderFieldsShow(\'departaments_'.$row["id_departaments_company"].'\',\'fieldsDepartaments\');"> '.$row["departaments_company"].'</label>						
                                    </div>
                                  </div>';
                            $array_departaments[$i]['id_departaments_company'] = $row["id_departaments_company"];
                            $array_departaments[$i]['departaments_company'] = $row["departaments_company"];
                            $i++;                                
                        }						 
			echo '</div>';                                                
		    ?>
    		    <hr>
                    <div class="row">	
                        <div class="col-xs-1"></div>
                    <?php
                        $l = 0;
                        while (isset($array_departaments[$l])) 
                        {	                                
                            $sql_tabs = "select t.id_tabs,t.tabs,t.glyphicon,t.route,dc.departaments_company "
                            . "from tbl_tabs t "
                            . "left join tbl_tabs_departaments_company tdc on tdc.id_tabs = t.id_tabs "
                            . "left join tbl_departaments_company dc on dc.id_departaments_company = tdc.id_departaments_company "
                            . "where dc.id_departaments_company ='".$array_departaments[$l]['id_departaments_company']."' ORDER BY t.id_tabs";
                            $resultado_tabs = ejecutar($sql_tabs,$conexion);
                            $reporte_tabs = array();
                           
                            while($datos = mysqli_fetch_assoc($resultado_tabs)) {
                            if($cadena[$array_departaments[$l]['id_departaments_company']] == '')
                                $cadena[$array_departaments[$l]['id_departaments_company']] = '|'.$array_departaments[$l]['id_departaments_company'].'-'.$datos['id_tabs'].'|';
                            else {
                                $cadena[$array_departaments[$l]['id_departaments_company']] .= ',|'.$array_departaments[$l]['id_departaments_company'].'-'.$datos['id_tabs'].'|';
                            }
                                //$reporte_tabs[$array_departaments[$l]] .= '|'.$array_departaments[$l].'-'.$datos['id_tabs'].'|,';                                
                            }
                            //$cadena[$array_departaments[$l]['id_departaments_company']] = 
                            $l++;                                
                        }

                        $i=0;
                        while (isset($array_departaments[$i])) 
                        {	                                
                            $id_div_departaments = 'div_departaments_'.$array_departaments[$i]['id_departaments_company'];
                            $large_string = strlen($id_div_departaments.'_tabs_');
                            
                            
                            
                            echo '<div class="col-xs-2" id="'.$id_div_departaments.'" style="display:none;">
                                    
                                    <input name="'.$id_div_departaments.'_tabs" id="'.$id_div_departaments.'_tabs" type="checkbox" value="" onclick="selectAllFields(\''.$id_div_departaments.'_tabs_\','.$large_string.',\''.$id_div_departaments.'_tabs'.'\',\''.$id_div_departaments.'_tabsHidden\',\''.$cadena[$array_departaments[$i]['id_departaments_company']].'\')">&nbsp;'.$array_departaments[$i]['departaments_company'].'<input name="'.$id_div_departaments.'_tabsHidden" id="'.$id_div_departaments.'_tabsHidden" type="hidden"><hr/>';
                            
                                    $sql_tabs = "select t.id_tabs,t.tabs,t.glyphicon,t.route,dc.departaments_company "
                                    . "from tbl_tabs t "
                                    . "left join tbl_tabs_departaments_company tdc on tdc.id_tabs = t.id_tabs "
                                    . "left join tbl_departaments_company dc on dc.id_departaments_company = tdc.id_departaments_company "
                                    . "where dc.id_departaments_company ='".$array_departaments[$i]['id_departaments_company']."' ORDER BY t.id_tabs";
                                    $resultado_tabs = ejecutar($sql_tabs,$conexion);
                                    $reporte_tabs = array();
                                       
                                    while($datos = mysqli_fetch_assoc($resultado_tabs)) {                                                 
                                        $id_div_tabs = $array_departaments[$i]['departaments_company']."_".$datos['id_tabs'];
                                        echo '<div class="col-xs-12" id="'.$id_div_tabs.'">
                                            <input name="'.$id_div_departaments.'_tabs_'.$datos['id_tabs'].'" id="'.$id_div_departaments.'_tabs_'.$datos['id_tabs'].'" type="checkbox" value="'.$array_departaments[$i]['id_departaments_company'].'-'.$datos['id_tabs'].'" onclick="loadOrderFieldsShow(\''.$id_div_departaments.'_tabs_'.$datos['id_tabs'].'\',\''.$id_div_departaments.'_tabsHidden'.'\');">&nbsp;'.$datos['tabs'].'
                                        </div>';
                                    }
                            echo'</div>';
                            
                            
                            $i++;                                
                        }                        
                    ?>                    		                     
                        <div class="col-xs-1"></div>
                    </div>
                        <!--<table id="departaments" border="1" style="display:none;"><tr><td>Administration</td></tr></table>-->
                    
                    <hr>
                    
		    <div class="row">
			<div class="col-xs-12">
			    <div class="input-group">
				<input id='Accept' name='Accept' type='submit' value=" Accept " class="btn btn-success btn-lg">
	    &nbsp&nbsp           <input name='reset' type='button' value=" Reset " onclick= "window.location.href = 'asignarDepartamentosUsuario.php';" class="btn btn-danger btn-lg">		    
			    </div>
			</div>			
		    </div>
		</form>              
            </div>	    
        </div>
        <!-- /.row -->
	<hr>
        <!-- Related Projects Row -->
        <div class="col-lg-12">

        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                            <th>ID</th>
                            <th>USER NAME</th>
                            <th>TYPE USER</th>                          
                            <th>ACTION</th>                            

                </tr>
            </thead>


            <tbody>
            <?php	
            $sql_consulta  = ""
                    . "SELECT * FROM user_system us "
                    . "left join tbl_user_type ut on ut.id_user_type = us.User_type "
                    . "WHERE us.user_id IN (SELECT distinct(usdc.id_user_system) FROM tbl_user_system_departaments_company usdc )";
                    
            $resultado_consulta = ejecutar($sql_consulta,$conexion);

            $reporte = array();

            $u = 0;      
            while($datos = mysqli_fetch_assoc($resultado_consulta)) {            
                $reporte[$u] = $datos;                              
                $u++;
            } 
            $i=0;						
            while (isset($reporte[$i])){ 				
                
//            $sql_consulta  = "SELECT ruta FROM tbl_documents_business WHERE id_documents_business = ".$reporte[$i]['id_documents_business'];
//                    
//            $resultado_consulta = ejecutar($sql_consulta,$conexion);
//
//            $reporte_ruta = array();
//
//            $u = 0;      
//            while($dato_ruta = mysqli_fetch_assoc($resultado_consulta)) {            
//                $reporte_ruta[$u] = $dato_ruta;                              
//                $u++;
//            }                                          
//            
//            $ruta_archivo = $reporte_ruta[0]['ruta'];
            
            $nombre_archivo_array = explode('/',$reporte_ruta[0]['ruta']);
            $nombre_archivo = end($nombre_archivo_array);
            
            
            
                   
                echo '<tr>';
                echo '<td>'.$reporte[$i]['user_id'].'</td>';
                echo '<td>'.$reporte[$i]['Last_name']." ".$reporte[$i]['First_name'].'</td>';
                echo '<td>'.$reporte[$i]['user_type'].'</td>';               
               //if($_SESSION['user_type'] <> 1 ){
                echo '<td align="center">';
            ?>
                    <!--<a onclick="agregar_nuevo_registro('form_type_documents','<form  id=\'form_type_documents\' name=\'form_type_documents\'><table align=\'center\'><tr><td align=\'left\'>Type Person: <select class=\'populate placeholder\' id=\'tipo_persona_agregar\' name=\'tipo_persona_agregar\'><option value=\'\'>--- SELECT ---</option></select></td></tr><tr><td>&nbsp;</td></tr><tr><td align=\'left\'>Type Document: <input class=\'form-control\' id=\'valor_tipo_documento\' name=\'valor_tipo_documento\'><input id=\'id_type_documents_persons\' name=\'id_type_documents_persons\' type=\'hidden\' value=\'<?php echo $reporte[$i]['id_type_documents_persons'];?>\'></td></tr></table></form>','../../controlador/type_persons_documents/insertar_type_persons_documents.php'); " style="cursor: pointer"><img style="width:50px" src="../../../images/save.png"></a>-->
                    <a onclick="changeSelect(<?php echo $reporte[$i]['user_id'];?>)" style="cursor: pointer"><img style="width:30px" src="../../../images/save.png"></a>
                    <!--<a style="cursor:pointer" data-target="#exampleModal" data-toggle="modal"><img style="width:30px" src="../../../images/save.png"></a>-->
            <?php
                echo '</td>';
            //}
                echo '</tr>';
                $i++;		
            }			
             ?>				
            <tbody/>
            </table> 

</div>
<!--        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">              
      <div class="modal-body text-center"> 
          <img src="../../../images/FusionCharts.png" width="120" height="90"/><br>
          <h2 class="modal-title" id="exampleModalLabel"><font color="#848484"><b>GRAFICADOR!</b></font></h2>
          <br>
          <form id="datos_reporte_especifico" onsubmit="return generar_reporte_especifico(this);">
          <div class="form-group">
              <label for="recipient-name" class="control-label"><font size="3" color="#848484">NOMBRE DEL REPORTE</font></label>
              <input class="form-control" id="reporte" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label"><font size="3" color="#848484">TABLA</font></label>
            <input class="form-control" id="tabla" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label"><font size="3" color="#848484">JOINS</font></label>
            <textarea class="form-control" id="joins" autocomplete="off"></textarea>
          </div> 
          <div class="form-group">
            <label for="message-text" class="control-label"><font size="3" color="#848484">VARIABLES SIN EL ALIAS (AS)</font></label>            
          </div>
              <div class="form-inline">
            <div class="form-group">                
              <input type="text" class="form-control" id="cantidad" placeholder="CANTIDAD" autocomplete="off">
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group">                
              <input type="text" class="form-control" id="valor" placeholder="VALOR" autocomplete="off">
            </div>       
              </div>
              <br>
          <div class="form-group">
            <label for="message-text" class="control-label"><font size="3" color="#848484">WHERE</font></label>
            <textarea class="form-control" id="where" autocomplete="off"></textarea>
          </div>    
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Generar</button>
              <input type="hidden" id="nombre_bd" name="nombre_bd">
              <input type="hidden" id="ip" name="ip">
              <input type="hidden" id="puerto" name="puerto">
              <input type="hidden" id="usuario" name="usuario">
              <input type="hidden" id="clave" name="clave">
              <input type="hidden" id="plantilla" name="plantilla">
            </div>              
        </form>
      </div>

    </div>
  </div>
</div>-->
        <!-- /.row -->
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
</body>
<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
	$('#name').select2();	
}
// Run timepicker

$(document).ready(function() {
	// Create Wysiwig editor for textare
	//TinyMCEStart('#wysiwig_simple', null);
	//TinyMCEStart('#wysiwig_full', 'extreme');
	// Add slider for change test input length
	//FormLayoutExampleInputLength($( ".slider-style" ));
	// Initialize datepicker
	//$('#input_date_licence').datepicker({setDate: new Date()});
	//$('#input_date_finger').datepicker({setDate: new Date()});
	//$('#dob').datepicker({setDate: new Date()});
	//$('#hireDate').datepicker({setDate: new Date()});
	//$('#terminationDate').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	//LoadTimePickerScript(DemoTimePicker);
	// Add tooltip to form-controls
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	// Load example of form validation
	//LoadBootstrapValidatorScript(DemoFormValidator);
	// Add drag-n-drop feature to boxes
	//WinMove();
	//ShowDivEdit();
	//enableField();
});
</script>
</html>
