<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
  echo '<script>alert(\'MUST LOG IN\')</script>';
  echo '<script>window.location="index.php";</script>';
} else {
  if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
    echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
    echo '<script>window.location="home.php";</script>';
  }
} 
$conexion = conectar();
$IdSeguros = 0;
if (isset($_GET['id_seguros'])) {    
    $accion = 'modificar';
    $titulo = 'Modify';
    $IdSeguros = sanitizeString($conexion, $_GET['id_seguros']);
    $generar_input = '<input type="hidden" id="id_seguros" name="id_seguros" value="'. $IdSeguros . '">';
} else {
    $accion = 'insertar';
    $titulo = 'Register';
    $generar_input = null;
}
?>

<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>    
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/bootstrap-multiselect.js"></script>
<script src="../../../js/listas.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="../../../css/bootstrap-multiselect.css">  
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
<!-- Style Bootstrap-->
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
<!--<script src="../../../js/devoops_ext.js"></script>--> 
    
<script type="text/javascript" language="javascript">
    function Validar_Formulario_Gestion_Seguros(nombre_formulario) {                      
        var seguros_table = '';       
        var tipo_seguros = '';
        var nombres_campos = '';         
        $('input:checked').each(function() {
            //if($(this).attr('name') != 'prescription_p' && $(this).attr('name') != 'prescription_a' && $(this).attr('name') != 'auth_eval_p' && $(this).attr('name') != 'auth_eval_a' && $(this).attr('name') != 'doctor_sig_p' && $(this).attr('name') != 'doctor_sig_a' && $(this).attr('name') != 'auth_treat_p' && $(this).attr('name') != 'auth_treat_p' && $(this).attr('name') != 'progress_nt_p' && $(this).attr('name') != 'progress_nt_a') {
            if($(this).attr('name') != undefined) {
                var posicion;
                var posicion_v;
                if($(this).attr('name').substring(0,13) == 'tipo_seguros_') {
                    tipo_seguros += $(this).val()+'|';
                }
                if($(this).attr('name').substring(0,8) == 'progress') {
                    posicion = $(this).attr('name').replace('progress','progress_visits');
                    posicion = posicion.replace('|','-');
                    posicion = posicion.replace('/','-');
                    if($("#"+posicion).val() == 0 || $("#"+posicion).val() == '') {
                        nombres_campos = '<table align="center" border="0" width="400px"><tr><td align="left"> * Progress Notes - Visits (Incorrect)</td></tr></table>';
                    }
                }
                if($(this).attr('name').substring(0,12) == 'prescription') {
                    posicion = $(this).attr('name').replace('prescription','prescription_cp');
                    posicion = posicion.replace('|','-');
                    posicion = posicion.replace('/','-');
                    //alert($("#"+posicion).val());
                    if($("#"+posicion).val() == 0 || $("#"+posicion).val() == '') {
                        nombres_campos = '<table align="center" border="0" width="400px"><tr><td align="left"> * Prescripcion - Cp Day`s Left (Incorrect)</td></tr></table>';
                    }
                }
                if($(this).attr('name').substring(0,10) == 'auth_treat'){
                    posicion = $(this).attr('name').replace('auth_treat','auth_treat_au');
                    posicion = posicion.replace('|','-');
                    posicion = posicion.replace('/','-');
                    //alert($("#"+posicion).val());
                    //return false;
                    if($("#"+posicion).val() == 0 || $("#"+posicion).val() == ''){
                        nombres_campos = '<table align="center" border="0" width="400px"><tr><td align="left"> * Authorization Treatments - Auth Day`s Left (Incorrect)</td></tr></table>';
                    }
                    posicion_v = $(this).attr('name').replace('auth_treat','auth_treat_vis');
                    posicion_v = posicion_v.replace('|','-');
                    posicion_v = posicion_v.replace('/','-');
                    //alert($("#"+vposicion).val());
                    //return false;
                    if($("#"+posicion_v).val() == 0 || $("#"+posicion_v).val() == ''){
                        nombres_campos = '<table align="center" border="0" width="400px"><tr><td align="left"> * Authorization Treatments - Visit Remain (Incorrect)</td></tr></table>';
                    }
                }            
            }
        });        
        $("input:text").each(function(){                                                
            if(this.name.substring(0,8) == 'age_min_' || this.name.substring(0,8) == 'age_max_'){
               if($("#"+this.id).val() == ''){
                   $("#"+this.id).val('-');
               }
            }
        });  
        //        if(tipo_seguros == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Type Insurance</td></tr></table>';
        //
        //        }
        //        
        //  if($('#name').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Name</td></tr></table>';
        //
        //        }
        //        
        //  if($('#address').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Address</td></tr></table>';
        //
        //        }   
        //        
        //  if($('#city').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * City</td></tr></table>';
        //
        //        }    
        //        
        //  if($('#state').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * State</td></tr></table>';
        //
        //        }  
        //        
        //  if($('#zip').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Zip</td></tr></table>';
        //
        //        }  
        //        
        //  if($('#phone').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Phone</td></tr></table>';
        //
        //        } 
        //        
        //  if($('#fax').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Fax</td></tr></table>';
        //
        //        }  
        //        
        ////  if($('#id_reporting_system').val() == ''){
        ////nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Reporting System</td></tr></table>';
        ////
        ////        }          
        //        
        //  if($('#provider').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Provider</td></tr></table>';
        //
        //        }  
        //        
        //  if($('#id_type_provider').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Type Provider</td></tr></table>';
        //
        //        }   
        //        
        //  if($('#id_claim_ind').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Claim Ind</td></tr></table>';
        //
        //        }  
        //        
        //  if($('#submitter_id').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Submitter Id</td></tr></table>';
        //
        //        }         
        //        
        //  if($('#id_edi_gateway').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Edi Gateway</td></tr></table>';
        //
        //        }         
        //        
        //  if($('#payer_id').val() == ''){
        //nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Payer Id</td></tr></table>';
        //
        //        }         
                
        /*tabladinamicasvalidacion*/          
        if(nombres_campos != '') {         
            swal({
                title: "<h3><b>Complete the following fields<b></h3>",          
                type: "info",
                html: "<h4>"+nombres_campos+"</h4>",
                showCancelButton: false,          
                closeOnConfirm: true,
                showLoaderOnConfirm: false,
            });         
                return false;       
        } else {
                //return false;    
                $('input:checked').each(function(){
                    seguros_table += '&'+$(this).attr('name')+'=1';
                });            
                var campos_formulario = $("#form_gestion_seguros").serialize();                     
                $.post("../../controlador/seguros/gestionar_seguros.php",
                        campos_formulario+'&tipo_seguros_cadena='+tipo_seguros+seguros_table,
                        function (resultado_controlador) {                                   
                            if(resultado_controlador.repetido == 'si') {          
                                swal({title: '<h4><b>Insurance is already registered</b></h4>', type: "error"});                          
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
        result_seguros();
        $('#resultado').html(resultado_controlador.resultado);
        swal({title: resultado_controlador.mensaje, text: "Do you Want to view inserted records?", type: "success", showCancelButton: true, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33",  confirmButtonText: "Find",  closeOnConfirm: false, closeOnCancel: false
        }).then(function(isConfirm) {
            if (isConfirm === true) {                       
                window.location.href = '../seguros/consultar_seguros.php?&consultar=si';  
            }
       });                                               
    }
                                          
    $(document).ready(function() {                
        $('#progress_nt_p').click(function() {              
            if($(this).is(':checked') == true){ $('#progress_v_p').attr('readonly',false); } else { $('#progress_v_p').attr('readonly',true); $('#progress_v_p').val(0); }
        });  
        $('#progress_nt_a').click(function(){              
            if($(this).is(':checked') == true){ $('#progress_v_a').attr('readonly',false); } else { $('#progress_v_a').attr('readonly',true); $('#progress_v_a').val(0); }
        });           
        <?php if(!isset($_GET['pediatric']) && !isset($_GET['adult'])) { ?>  
            $('#progress_v_p').attr('readonly',true);
            $('#progress_v_a').attr('readonly',true); 
            $('#progress_v_p').val(0);
            $('#progress_v_a').val(0);                       
        <?php } ?>    
        
        $(".entrada_seguros").change(function() {
            var id = $(this).attr("id") + "";
            var val = '';
            if (id.substring(0, 1) == 'e')
                val = ($(this).is(':checked'))?('1'):('0')
            else    
                val = $(this).val();            
            $.post("../../controlador/seguros/gestionar_seguros.php",
                "accion=entrada_seguros&val=" + val + '&id=' + id,
                function (resultado_controlador) {                                   
                                                     
                },
                "json" 
            ); 
        }); 

        $(".operacion_seguros").click(function(event) {
            //$("#mostrar").html("abc");
            event.preventDefault();
            var id = $(this).attr("id") + "";
            var op = id.substring(0, 1);
            id = id.substring(1, id.length);
            var params = "accion=operacion_seguros&op=" + op + "&id=" + id;
            if (op == "S") {
                var cpt = $("#CPT" + id).val();
                var dia = $("#DIA" + id).val();
                var mod = $("#MOD" + id).val();
                var rul = $("#RUL" + id).val();
                var rat = $("#RAT" + id).val();
                var all = $("#ALL" + id).val();
                var ena = ($("#ENA" + id).is(':checked'))?('1'):('0');
                params = params + "&cpt="+cpt+"&dia="+dia+"&mod="+mod+"&rul="+rul+"&rat="+rat+"&all="+all+"&ena="+ena;
            }
            //$("#mostrar").html(params);

            $.post("../../controlador/seguros/gestionar_seguros.php",
                params,
                function (resultado_controlador) {
                    //$("#mostrar").html(resultado_controlador.query);
                    //$("#mostrar").html("123");
                    //$("#mostrar").html("id:" + resultado_controlador.id + ", op: " + resultado_controlador.op);
                    if (resultado_controlador.op == "D")
                        $("#F" + resultado_controlador.id).remove();
                    else if (resultado_controlador.op == "S") {
                        id = resultado_controlador.id;
                        dis = resultado_controlador.dis;
                        cpt = resultado_controlador.cpt;
                        dia = resultado_controlador.dia;
                        if (!dia) dia = "All";
                        mod = resultado_controlador.mod;
                        rul = resultado_controlador.rul;
                        rat = resultado_controlador.rat;
                        all = resultado_controlador.all;
                        ena = resultado_controlador.ena;
                        //$("#mostrar").html(htmlEntities(AddModifierRow(id, dis, cpt, dia, mod, rul, rat, all, ena)));
                        $("#mostrar").html("id: "+id+", dis: "+dis+", cpt: "+cpt+", cpt: "+cpt+", dia: "+dia+", mod: "+mod+", rul: "+rul+", rat: "+rat+", all: "+all+", ena: "+ena);
                        result = '<tr id="F' + id + '">';
                        result += '<td>' + dis + '</td>';
                        result += '<td>' + cpt + '</td>';
                        result += '<td>' + dia + '</td>';
                        result += '<td>' + mod + '</td>';
                        result += '<td>' + rul + '</td>';
                        result += '<td>$' + rat + '</td>';
                        result += '<td>$' + all +  '</td>';
                        result += '<td><input type="checkbox" class="entrada_seguros" id="' + id + '" ' + ((ena == 1)?('checked="checked"'):('')) + ' /></td>';
                        result += '<td><a class="operacion_seguros" href="" id="D' + id + '"><u>Delete</u></a></td>';
                        result += '</tr>';
                        $("#tabla_modificadores tr:last").after(result);
                    }
                },
                "json"
            ); 
        });

        $("#id_especial_f").change(function() {                     
            var id_especial_show = -1;
            if($("#id_especial_prev").val()=='') {
                $("#id_especial_prev").val($("#id_especial_f").val());
                id_especial_show = $("#id_especial_f").val();
            } else {
                if($("#id_especial_f").val() != null) {
                    var id_especial_cur = $("#id_especial_f").val();
                    var id_especial_cur_string = id_especial_cur.toString();                        
                    var res = id_especial_cur_string.split(",");
                    var l = 0;
                    var id_especial_prev = $("#id_especial_prev").val();
                    var n = 0;
                    while(res[l]!== undefined) {                          
                        n = id_especial_prev.indexOf(res[l]);                          
                        if(n < 0) {
                            id_especial_show = res[l];
                            break;
                        }
                        l++;
                    }
                    $("#id_especial_prev").val($("#id_especial_f").val());
                } else {
                    $("#id_especial_prev").val('');
                }                        
            }
            if(id_especial_show != -1)
                showModalSpecial(id_especial_show);
        });                
    });

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function showModalSpecial(val) {
        $('#modal_seguro_special_'+val).modal({backdrop: 'static', keyboard: false},'show');
    }

    /*function cancelModalSpecial(val) {
        $('#modal_seguro_special_'+val).modal('hide');            
    }*/
           
    function validarModalSpecial(val) {
        var valido = 0;
        $('input:checkbox').each(function() {                                   
            //alert($(this).attr("id"));
            if($(this).attr("id") !== undefined){
                var idDisciplina = $(this).attr("id");
                var arrayIdDisciplina;                    
                arrayIdDisciplina = idDisciplina.split('-');
                if(arrayIdDisciplina[0] == val){
                    if($(this).is(':checked') == true){
                        valido = 1;                            
                    }
                }
            }           
        });                    
        if(valido == 1)     
            $('#modal_seguro_special_'+val).modal('hide');
        else{
            var r = confirm("Debe seleccionar una opcion.... \n Are you sure to continue? La opcion especial feacture se desmarcara");
            if (r == true) {                    
                $('#id_especial_f').multiselect('deselect', val);
                //$("#id_especial_f")[0].selectedIndex = -1
                $('#modal_seguro_special_'+val).modal('hide');
            }
        }                    
    }

    function showModalRangeAge(y,elemento) {  
        if(elemento == 'check') {
            if($("input[name=tipo_seguros_"+y+"]:checked").length == 1){  
                $("input:text").each(function(){                                                
                    if(this.name.substring(0,9) == 'age_min_'+y || this.name.substring(0,9) == 'age_max_'+y){
                        $("#"+this.id).val('');
                    }
                }); 
                $('#modal_tipo_seguros_'+y).modal({backdrop: 'static', keyboard: false},'show');
            }else{
                var r = confirm("Are you sure to continue? Los rangos de edad se perderan");
                if (r == true) {
                    $("input:text").each(function(){                                                
                        if(this.name.substring(0,9) == 'age_min_'+y || this.name.substring(0,9) == 'age_max_'+y){
                            $("#"+this.id).val('');
                        }
                    }); 
                }else{
                    $("#tipo_seguros"+y).prop('checked','checked')
                }
                                    
            }                
        } else {
            if($("input[name=tipo_seguros_"+y+"]:checked").length == 1) {
                $('#modal_tipo_seguros_'+y).modal({backdrop: 'static', keyboard: false},'show');
            } else {
                alert("You must select the checkbox to edit the range of hours");
            }
            
        }    
        $("#from"+y).val(elemento);
    }
           
    function validarModal(tipo_seguros) {
        var close = 'si';
        var mensaje = '';
        var flag = 'no';
        $("input:text").each(function() {                                                
            if(this.name.substring(0,9) == 'age_min_'+tipo_seguros){
                if($("#"+this.id).val() != ''){
                    if($("#"+this.id.replace('min','max')).val() == ''){
                        mensaje = 'Age range is incomplete';
                        close = 'no';
                    }else{
                        flag = 'si';
                    }
                }else{
                    if($("#"+this.id.replace('min','max')).val() != ''){
                        mensaje = 'Age range is incomplete';
                        close = 'no';
                    }
                }
            }
        });        
        if(flag == 'no'){
            mensaje="You must fill in at least one";
        }      
        if(close == 'si' && flag == 'si'){
            $("#cadenaDatos"+tipo_seguros).val('-');
            var primero = 0;
            var valorAnt = '';
            $("input:text").each(function(){                                                
                if(this.name.substring(0,9) == 'age_min_'+tipo_seguros){
                    valorAnt = $("#cadenaDatos"+tipo_seguros).val();
                    if(this.value != ''){
                        if(primero == 0){
                            $("#cadenaDatos"+tipo_seguros).val(valorAnt+this.id+'='+this.value+'+'+this.id.replace('min','max')+'='+$("#"+this.id.replace('min','max')).val());
                            primero = 1; 
                        }                                                               
                        else{
                            $("#cadenaDatos"+tipo_seguros).val(valorAnt+'|'+this.id+'='+this.value+'+'+this.id.replace('min','max')+'='+$("#"+this.id.replace('min','max')).val());
                        }                                    
                    }
                    
                }
            });
            $('#modal_tipo_seguros_'+tipo_seguros).modal('hide');
            $("#from"+tipo_seguros).val('-');
        } else
            alert(mensaje)
    }
           
    function cancelModal(tipo_seguros) {
        if($("#from"+tipo_seguros).val() == 'check') {                   
            document.getElementById("tipo_seguros"+tipo_seguros).checked = false;                   
            $('#modal_tipo_seguros_'+tipo_seguros).modal('hide');
        } else {          
            $("input:text").each(function(){                                                
                if(this.name.substring(0,9) == 'age_min_'+tipo_seguros){
                    $("#"+this.id).val('');
                    $("#"+this.id.replace('min','max')).val('');
                }
            });
            var range = $("#cadenaDatos"+tipo_seguros).val().split('|');
            var i = 0;
            var age = [];
            var valorAgeMin = [];
            var valorAgeMax = [];
            while(range[i]) {                        
                age = range[i].split('+');
                valorAgeMin = age[0].split('=');                        
                //alert(valorAgeMin[0]+"-"+valorAgeMin[1]);
                $("#"+valorAgeMin[0]).val(valorAgeMin[1]);
                valorAgeMax = age[1].split('=');                        
                //alert(valorAgeMax[0]+"-"+valorAgeMax[1]);
                $("#"+valorAgeMax[0]).val(valorAgeMax[1]);
                i++;
            }
            $('#modal_tipo_seguros_'+tipo_seguros).modal('hide');
        }
        $("#from"+tipo_seguros).val('-');
    }
           
    function desbloquerVisits(check,idDesbloquear) {
        if($("input[name=\'"+check.name+"\']:checked").length == 1){  
            $("#"+idDesbloquear).prop("disabled",false);
        } else {
            $("#"+idDesbloquear).prop("disabled",true);
        }
    }
</script>
        
<?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
<br><br>
<div class="container">               


<form id="form_gestion_seguros" onSubmit="return Validar_Formulario_Gestion_Seguros('form_gestion_seguros');">      
    <div class="form-group row">                
        <div class="col-sm-2"></div>
        <div class="col-sm-6" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> Insurance</font></h3></div>                  
        <div class="col-sm-1"><a style="cursor:pointer" onclick="window.location.href = 'consultar_seguros.php'"><img src="../../../images/lupa.png" style="width: 40px" title="View Insurance"></a></div>
        <div class="col-sm-1"><a style="cursor:pointer" onclick="return Validar_Formulario_Gestion_Seguros();"><img src="../../../images/sign-up.png" style="width: 40px" title="Save Insurance"></a></div>
        <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"><img class="img-responsive" src="../../../imagenes/imagen_sistema.jpg" alt="" width="50px" height="50px"></a></div>
    </div>                      
    <div class="form-group row">              
        <div class="col-sm-6 text-center" style="float: none;margin: 0 auto;">
            <label><font color="#585858">NAME</font></label>
        </div>
        <div class="col-sm-6" style="float: none;margin: 0 auto;">
            <input type="text" class="form-control" id="name" name="name" placeholder="NAME" style="text-align: center" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['name'])) { echo str_replace('|',' ', sanitizeString($conexion, $_GET['name'])); }?>">
        </div>  
    </div>
    <hr>
    <?php   
        $sql  = "SELECT * FROM tbl_seguros_tipo_seguros;"; 
        $resultado = ejecutar($sql, $conexion);
        $reporte_tipo_seguro = array();
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte_tipo_seguro[$i] = $datos;
            $i++;
        }
        echo '<div class="form-group text-center">';
        echo '<label><font color="#585858">TYPE INSURANCE</font></label>';
        echo '</div>';
        echo '<div class="form-group row text-center">';    
        if(isset($_GET['id_seguros'])) {
            $sqlTipoSegurosVerificar = 'SELECT * FROM tbl_seguros_rel_tipo_seguros WHERE id_seguros = '.$IdSeguros;
            $resultadoTipoSegurosVerificar = ejecutar($sqlTipoSegurosVerificar,$conexion);
            $reporteTipoSegurosVerificar = array();
            $arrayTipoSegurosVerificar = array();
            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultadoTipoSegurosVerificar)) {            
                    $reporteTipoSegurosVerificar[$i] = $datos['id_tipo_seguros'];
                    $arrayTipoSegurosVerificar = $datos;
                $i++;
            }
        }   
        $y=0;
        while (isset($reporte_tipo_seguro[$y])) {   
            if($y > 0) {
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }                
            $sql_discipline = "SELECT * FROM discipline ";                       
            $data_discipline = ejecutar($sql_discipline,$conexion);                                                                              
            $table_discipline = '<table border=0 aling=center width=100% cellspacing=5px cellpalding=5px>' . '<tr><td aling=center><input type="hidden" value="no_click" id="from'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" name="from'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'"><b>DISCIPLINE</b></td><td align=center><b>MINIMUM AGE</b></td><td align=center><b>MAXIMUM AGE</b></td></tr>';                       
            $_array_discipline = null;           
            $cadenaDatos = '-';
            while($datos = mysqli_fetch_assoc($data_discipline)) {                         
                $minimum_age = '';
                $maximum_age = '';
                if(count($reporteTipoSegurosVerificar) > 0){
                    if(in_array($reporte_tipo_seguro[$y]['id_tipo_seguros'], $reporteTipoSegurosVerificar)){
                                $sqlFindRangeAge = "SELECT * FROM tbl_seguros_line_of_business_age slob "
                                . " LEFT JOIN tbl_seguros_rel_tipo_seguros srts ON slob.id_seguros_rel_tipo_seguros = srts.id_rel_tipo_seguros"
                                . " WHERE srts.id_seguros = ".$IdSeguros." AND srts.id_tipo_seguros = ".$reporte_tipo_seguro[$y]['id_tipo_seguros']." AND slob.discipline = '".$datos['Name']."'";
                                $resultadoFindRangeAge = ejecutar($sqlFindRangeAge,$conexion);
                                $i = 0;      
                                while($datosFindRangeAge = mysqli_fetch_assoc($resultadoFindRangeAge)) {            
                                        $minimum_age = $datosFindRangeAge['minimum_age'];                                                                            
                                        $maximum_age = $datosFindRangeAge['maximum_age'];
                                }
                    }
                }
                if($minimum_age != ''){
                    if($cadenaDatos == '-') $cadenaDatos = '';
                    $cadenaDatos .= 'age_min_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'_'.$datos['DisciplineId'].'='.$minimum_age.'+';
                    $cadenaDatos .= 'age_max_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'_'.$datos['DisciplineId'].'='.$maximum_age.'|';
                }
                $_array_discipline = $datos;
                $exist_discipline = 'si';
                $table_discipline .= '<tr>';
                $table_discipline .= '<td align="center">'.$datos['Name'].'</td>';                
                $table_discipline .= '<td align="center"><input type="text" id="age_min_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'_'.$datos['DisciplineId'].'" name="age_min_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'_'.$datos['DisciplineId'].'" class="form-control" value="'.$minimum_age.'"></td>';
                $table_discipline .= '<td align="center"><input type="text" id="age_max_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'_'.$datos['DisciplineId'].'" name="age_max_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'_'.$datos['DisciplineId'].'" class="form-control" value="'.$maximum_age.'"></td>';
                
                $table_discipline .= '</tr>';                        
            }
            $table_discipline .= '<tr><td colspan="3" align="center"><input type="button" value="Ok" id="button_save" name="button_save" onclick="validarModal('.$reporte_tipo_seguro[$y]['id_tipo_seguros'].')" class="btn btn-primary text-left">'
                    . '&nbsp;<input type="button" value="Cancel" id="button_cancel" name="button_cancel" onclick="cancelModal('.$reporte_tipo_seguro[$y]['id_tipo_seguros'].')" class="btn btn-primary text-left"></td></tr>';
            $table_discipline .= '</table>';
            if($cadenaDatos != '-')
                $cadenaDatos = substr($cadenaDatos, 0, strlen($cadenaDatos) - 1);
            $table_discipline .= '<input type="hidden" name="cadenaDatos'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" id="cadenaDatos'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" value="'.$cadenaDatos.'">';
            if(count($reporteTipoSegurosVerificar) > 0){
                if(in_array($reporte_tipo_seguro[$y]['id_tipo_seguros'], $reporteTipoSegurosVerificar)) $checked = 'checked'; else $checked = '';
            }                
            echo '<input onclick="showModalRangeAge('.$reporte_tipo_seguro[$y]['id_tipo_seguros'].',\'check\');" type="checkbox" name="tipo_seguros_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" id="tipo_seguros'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" value="'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" '.$checked.'>&nbsp;'.$reporte_tipo_seguro[$y]['tipo_seguros'].''
                    . ' <img src="../../../images/pencil.png" width="20px" onclick="showModalRangeAge('.$reporte_tipo_seguro[$y]['id_tipo_seguros'].',\'img\');">'
                    . '<div class="modal fade" id="modal_tipo_seguros_'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'" tabindex="-1" role="dialog" aria-labelledby="'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'Label">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">              
                            <div class="modal-body text-center"> 
                                <img src="../../../images/save.png" width="100" height="70"/><br>
                                <h2 class="modal-title" id="'.$reporte_tipo_seguro[$y]['id_tipo_seguros'].'Label"><font color="#848484"><b>Enter the range of age allowed!</b></font></h2>
                                <br>
                            '.$table_discipline.'
                            </div>
                        </div>
                        </div>
                    </div>
                    </td>';               
            $y++;
        }   
        echo '</div>';     
        $selectSegurosSpecial = 'SELECT * FROM tbl_seguro_special';
        $resultadoSegurosSpecial = ejecutar($selectSegurosSpecial,$conexion);         
        $reporteSegurosSpecial = array();
        $j = 0;
        while($datos = mysqli_fetch_assoc($resultadoSegurosSpecial)){            
            $reporteSegurosSpecial[$j] = $datos;
            $j++;
        }           
    ?> 
    <hr>    
    <div class="form-group row">               
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Address</font></label>            
        <div class="col-sm-8">
            <textarea class="form-control" id="address" name="address" placeholder="Address" cols="30" rows="2" onkeyup="Mayusculas(event, this)"><?php if(isset($_GET['address'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['address'])); }?></textarea>
        </div>
    </div>    
    <div class="form-group row">    
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">City</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="city" name="city" placeholder="City" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['city'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['city'])); }?>">
            </div>            
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">State</font></label>            
            <div class="col-sm-3">
                <input type="text" class="form-control" id="state" name="state" placeholder="State" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['state'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['state'])); }?>">
            </div> 
    </div>
    <div class="form-group row">  
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Zip</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['zip'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['zip'])); }?>">
            </div>              
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Phone</font></label>            
            <div class="col-sm-3">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" onkeyup="Mayusculas(event, this);" value="<?php if(isset($_GET['phone'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['phone'])); }?>">
            </div>  
    </div>
    <div class="form-group row">  
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Fax</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['fax'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['fax'])); }?>">
            </div>

    </div>
    <div class="form-group row"> 
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Provider</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="provider" name="provider" placeholder="Provider" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['provider'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['provider'])); }?>">
            </div>
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Provider</font></label>
        <div class="col-sm-3">
            <select id="id_type_provider" name="id_type_provider">
                <option value="">Seleccione..</option>
            </select>
        </div>   
    </div>
    <div class="form-group row"> 
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Claim Ind</font></label>
            <div class="col-sm-3">
                <select id="id_claim_ind" name="id_claim_ind">
                    <option value="">Seleccione..</option>
                </select>                
            </div>
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Submitter Id</font></label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="submitter_id" name="submitter_id" placeholder="Submitter Id" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['submitter_id'])) { echo str_replace('|',' ',sanitizeString($conexion, $_GET['submitter_id'])); }?>">
        </div>   
    </div>
    <div class="form-group row"> 
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Edi Gateway</font></label>
            <div class="col-sm-3">
                <select id="id_edi_gateway" name="id_edi_gateway">
                    <option value="">Seleccione..</option>
                </select>                
            </div>
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Payer Id</font></label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="payer_id" name="payer_id" placeholder="Payer Id" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['payer_id'])) { echo str_replace('|',' ', sanitizeString($conexion, $_GET['payer_id'])); }?>">
        </div>   
    </div>  
    <div class="form-group row">             
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Especial Feacture</font></label>   
            <div class="col-sm-3">                
                <?php 
                $cadenaSpecial = '';
                if(isset($_GET['id_seguros'])){
                    $selectSegurosSpecial = 'SELECT * FROM tbl_seguro_special_relation t WHERE id_seguro = '.sanitizeString($conexion, $_GET['id_seguros']);
                    $resultadoSegurosSpecial = ejecutar($selectSegurosSpecial,$conexion);
                    $reporteIdSegurosSpecial = array();
                    $i = 0;      
                    while($datosSpecial = mysqli_fetch_assoc($resultadoSegurosSpecial)) {                                  
                        $reporteIdSegurosSpecial[$i]['id_seguro_special'] = $datosSpecial['id_seguro_special'];                                                 
                        $cadenaSpecial .= $datosSpecial['id_seguro_special'].',';
                        $i++;
                    }           
                    $cadenaSpecial = substr ($cadenaSpecial, 0, strlen($cadenaSpecial) - 1);                     
                }
                ?>
                <input type="hidden" name="id_especial_prev" id="id_especial_prev" value="<?= $cadenaSpecial;?>">
                <select name="id_especial_f[]" id="id_especial_f" class="multiple" multiple>                    
                    <?php                                           
                        $i=0;
                        while(isset($reporteSegurosSpecial[$i])!=null){
                            $e = 0;
                            $selected = '';
                            while(isset($reporteIdSegurosSpecial[$e])){
                                if($reporteIdSegurosSpecial[$e]['id_seguro_special'] == $reporteSegurosSpecial[$i]['id_seguro_special']){
                                    $selected = 'selected';   
                                    break;
                                }                                
                                $e++;
                            }                           
                    ?>
                            <option value="<?=$reporteSegurosSpecial[$i]['id_seguro_special']?>" <?=$selected;?>><?=$reporteSegurosSpecial[$i]['description']?></option>
                    <?php
                        $i++;
                        }
                    ?>
                </select>
                
                
            </div>
    </div>
    <?php               
        //$i=0;
        //while(isset($reporteSegurosSpecial[$i])!=null) {
                $w = 0;                
                while(isset($reporteSegurosSpecial[$w])!=null) {
                    $sql_discipline = "SELECT * FROM discipline";                    
                    $data_discipline = ejecutar($sql_discipline,$conexion);
                    $arrayDisciplineSpecial = array();
                    $t = 0;
                    $divModal = '';
                    $c = 1;
                    $divModal.= '<div class="row"> ';
                    $divModal.= '<div class="col-sm-1"></div>';
                    $impInicio = 0;
                    while($datosDiscipline = mysqli_fetch_assoc($data_discipline)){
                        $arrayDisciplineSpecial[$t] = $datosDiscipline; 
                        $checkEspecial = '';
                        if(isset($_GET['id_seguros'])){                            
                            $sql_seguro_especial = 'select ssr.id_seguro_special_relation as id_ssr  from tbl_seguro_special_relation ssr
                            left join seguros s on s.ID = ssr.id_seguro
                            left join tbl_seguro_special_relation_discipline ssrd on ssrd.id_seguro_special_relation = ssr.id_seguro_special_relation
                            where ID = '.sanitizeString($conexion, $_GET['id_seguros']).' and id_seguro_special = '.$reporteSegurosSpecial[$w]['id_seguro_special'].' and discipline = \''.$arrayDisciplineSpecial[$t]['Name'].'\';';
                            $data_seguro_especial = ejecutar($sql_seguro_especial,$conexion);
                            $row=mysqli_fetch_array($data_seguro_especial);
                            if($row['id_ssr']!= null && $row['id_ssr'] != '')
                                $checkEspecial = 'checked';
                            else
                                $checkEspecial = '';     
                        }
                        //echo $checkEspecial.'|';  
                        if($impInicio == 1){
                            $divModal.= '<div class="row"> ';
                            $divModal.= '<div class="col-sm-1"></div>';
                            $impInicio = 0;
                        }                            
                        $divModal.= '<label class="col-sm-3 form-control-label " style="text-align:right"><font color="#585858">'.$arrayDisciplineSpecial[$t]['Name'].'</font></label>';
                        $divModal.= '<div class="col-sm-2" style="text-align:left"><input class="form-control" type="checkbox"  name="'.$reporteSegurosSpecial[$w]['id_seguro_special'].'-'.$arrayDisciplineSpecial[$t]['Name'].'" id="'.$reporteSegurosSpecial[$w]['id_seguro_special'].'-'.$arrayDisciplineSpecial[$t]['Name'].'" value="1" '.$checkEspecial.'></div>';
                        if($c % 2 == 0){
                            $divModal.= '<div class="col-sm-1"></div>';
                            $divModal.= '</div>';  
                            $impInicio = 1;
                        }
                        $c++;
                        $t++;
                    }
                    if($c % 2 == 0){
                        $divModal.= '<div class="col-sm-1"></div>';
                        $divModal.= '</div>';
                    }
                    echo '<div class="modal fade" id="modal_seguro_special_'.$reporteSegurosSpecial[$w]['id_seguro_special'].'" tabindex="-1" role="dialog" aria-labelledby="'.$reporteSegurosSpecial[$w]['id_seguro_special'].'vLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">              
                            <div class="modal-body text-center"> 
                                <img src="../../../images/save.png" width="100" height="70"/><br>
                                <h2 class="modal-title" id="'.$reporteSegurosSpecial[$w]['id_seguro_special'].'aLabel"><font color="#848484"><b>Select the appropriate discipline!</b></font></h2>
                                <br>
                                <small>('.$reporteSegurosSpecial[$w]['description'].')</small>
                               '.$divModal.'
                                <br>   
                               <input type="button" value="Ok" id="button_save" name="button_save" onclick="validarModalSpecial('.$reporteSegurosSpecial[$w]['id_seguro_special'].')" class="btn btn-primary text-left">                         
                         <!--&nbsp;<input type="button" value="Cancel" id="button_cancel" name="button_cancel" onclick="cancelModalSpecial('.$reporteSegurosSpecial[$w]['id_seguro_special'].')" class="btn btn-primary text-left">-->
                            </div>
                          </div>
                        </div>
                    </div>';
                    $w++;
                }
        //$i++;
        //}               
    ?>
    <br/>
    <?php                                          
        if(isset($_GET['id_seguros'])) {
            $selectSegurosTable = 'SELECT * FROM tbl_seguros_table t WHERE id_seguros = '.sanitizeString($conexion, $_GET['id_seguros']);
            $resultadoSegurosTable = ejecutar($selectSegurosTable,$conexion);
            $reporteIdSegurosTable = array();           
            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultadoSegurosTable)) {                                  
                $reporteSegurosTable[$i] = $datos['id_seguros_type_task'].'-'.$datos['id_seguros_type_person'].'-'.$datos['discipline'];                                
                $reporteIdSegurosTable[$i] = $datos['id_seguros_table'];                                
                $i++;
            }                            
        }                       
        $selectDiscipline = 'SELECT *,Name as Discipline FROM discipline';
        $resultadoDiscipline = ejecutar($selectDiscipline,$conexion);
        $reporte_discipline = array();
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultadoDiscipline)) {            
            $reporte_discipline[$i] = $datos;
            $i++;
        }                        
        $selectSegurosTypePerson = 'SELECT id_seguros_type_person,seguros_type_person FROM tbl_seguros_type_person';
        $resultadoSegurosTypePerson = ejecutar($selectSegurosTypePerson,$conexion);
        $reporteSegurosTypePerson = array();
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultadoSegurosTypePerson)) {            
            $reporteSegurosTypePerson[$i] = $datos;
            $reporteSegurosTypePerson[$i]['first_letter'] = $datos['seguros_type_person'][0];
            $i++;
        }
        $l = 1;
        $arrayTypeDocTitle = array('Prescription','Authorization EVAL','Doctor Signature','Authorization Treatment');                            
        $arrayTypeDocColspan = array(4,2,2,6);                            
        $arrayTypeDoc = array('prescription','auth_eval','doctor_sig','auth_treat');                            
        while($l <= 4) {   
            $w = '50%';
            if($l == 2){
                echo '<table border="0" style="width:100%;">';
                echo '<tr><td>'; 
                $w = '80%';
            }
            if($l == 3){
                echo '</td><td>';
                $w = '80%';
            }
            if($l == 4){
                echo '</td></tr></table>';
            }
            echo '<table border="1" style = "width:'.$w.';height:180px;" align = "center" bordercolor="#BDBDBD">';
            echo '<tr>
                <td align="center" colspan = "'.$arrayTypeDocColspan[$l-1].'"><b>'. strtoupper($arrayTypeDocTitle[$l-1]).'</b></td></tr>';
            echo '<tr>';
                $g = 0;
                while(isset($reporteSegurosTypePerson[$g])){
                    
                    echo '<td align="center">
                            '.$reporteSegurosTypePerson[$g]['seguros_type_person'].'
                            </td>';
                    if($l == 1){
                        echo '<td align="center">
                            Cp Day`s Left
                        </td>';
                    }
                    if($l == 4){
                        echo '<td align="center">Auth Day`s Left</td>';
                        echo '<td align="center">Visits Remain</td>';
                    }
                    $g++;
                }                                        
            echo '</tr>';
            $r=0;
            while (isset($reporte_discipline[$r])){
                
                echo '<tr>';
                $g = 0;
                while(isset($reporteSegurosTypePerson[$g])){                                        
                    echo '<td align="center">';
                    if(isset($reporteSegurosTable[0]) && in_array($l.'-'.$reporteSegurosTypePerson[$g]['id_seguros_type_person'].'-'.$reporte_discipline[$r]['Discipline'], $reporteSegurosTable)){ $checked = "checked"; } else { $checked = ""; }
                    
                    if($arrayTypeDoc[($l-1)] == 'prescription'){
                        $sufijo = '_cp';
                    }else{
                        $sufijo = '_au';
                        $onclickExtra = ';desbloquerVisits(this,\''.$arrayTypeDoc[($l-1)].'_vis-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'\')';
                    }                                                                                
                    echo $reporte_discipline[$r]['Discipline'].'&nbsp;<input type="checkbox" onclick= "desbloquerVisits(this,\''.$arrayTypeDoc[($l-1)].$sufijo.'-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'\')'.$onclickExtra.'" id="'.$arrayTypeDoc[($l-1)].'/'.$reporteSegurosTypePerson[$g]['first_letter'].'|'.$reporte_discipline[$r]['Discipline'].'" name="'.$arrayTypeDoc[($l-1)].'/'.$reporteSegurosTypePerson[$g]['first_letter'].'|'.$reporte_discipline[$r]['Discipline'].'" '.$checked.'>';
                    
                    echo '</td>';           
                    if($arrayTypeDoc[($l-1)] == 'prescription'){
                        if($checked == "checked"){
                            $disabled = "";
                            $sqlSegurosTable = "SELECT * FROM tbl_seguros_table t "
                                    . " LEFT JOIN tbl_seguros_prescription_cp_days sp ON sp.id_seguros_table = t.id_seguros_table "
                                    . "WHERE t.`id_seguros` = ".sanitizeString($conexion, $_GET['id_seguros'])." AND "
                                    . "t.`id_seguros_type_task` = 1 AND t.`id_seguros_type_person` = ".$reporteSegurosTypePerson[$g]['id_seguros_type_person']." AND t.`discipline` = '".$reporte_discipline[$r]['Discipline']."'";
                            $resultadoSegurosTable = ejecutar($sqlSegurosTable, $conexion);
                            $reportePrescriptionCpDays = 0;                                    
                            $j = 0;
                            while($datos = mysqli_fetch_assoc($resultadoSegurosTable)){
                                $reportePrescriptionCpDays = $datos['cp_days_left'];
                                $j++;
                            } 
                        }else{
                            $disabled = "disabled";
                            $reportePrescriptionCpDays = 0;
                        }
                        echo '<td align="center">';
                        echo '<input type="text" size="1" maxlength="3" id="'.$arrayTypeDoc[($l-1)].'_cp-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" name="'.$arrayTypeDoc[($l-1)].'_cp-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" class="form-control" style="text-align: center" '.$disabled.' value="'.$reportePrescriptionCpDays.'">';
                        echo '</td>';
                    }
                    if($arrayTypeDoc[($l-1)] == 'auth_treat'){
                        if($checked == "checked"){
                            $disabled = "";
                            $sqlSegurosTable = "SELECT * FROM tbl_seguros_table t "
                                    . " LEFT JOIN tbl_seguros_auth_treat_auth_days sp ON sp.id_seguros_table = t.id_seguros_table "
                                    . "WHERE t.`id_seguros` = ".sanitizeString($conexion, $_GET['id_seguros'])." AND "
                                    . "t.`id_seguros_type_task` = 4 AND t.`id_seguros_type_person` = ".$reporteSegurosTypePerson[$g]['id_seguros_type_person']." AND t.`discipline` = '".$reporte_discipline[$r]['Discipline']."'";
                            $resultadoSegurosTable = ejecutar($sqlSegurosTable, $conexion);
                            $reporteAuthTreatDays = 0;                                    
                            $j = 0;
                            while($datos = mysqli_fetch_assoc($resultadoSegurosTable)){
                                $reporteAuthTreatDays = $datos['auth_days_left'];
                                $j++;
                            } 
                        }else{
                            $disabled = "disabled";
                            $reporteAuthTreatDays = 0;
                        }
                        echo '<td align="center">';
                        echo '<input type="text" size="1" maxlength="3" id="'.$arrayTypeDoc[($l-1)].'_au-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" name="'.$arrayTypeDoc[($l-1)].'_au-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" class="form-control" style="text-align: center" value="'.$reporteAuthTreatDays.'" '.$disabled.'>';
                        echo '</td>';
                        
                        if($checked == "checked"){
                            $disabled = "";
                            $sqlSegurosTable = "SELECT * FROM tbl_seguros_table t "
                                    . " LEFT JOIN tbl_seguros_auth_treat_visit_remain sp ON sp.id_seguros_table = t.id_seguros_table "
                                    . "WHERE t.`id_seguros` = ".sanitizeString($conexion, $_GET['id_seguros'])." AND "
                                    . "t.`id_seguros_type_task` = 4 AND t.`id_seguros_type_person` = ".$reporteSegurosTypePerson[$g]['id_seguros_type_person']." AND t.`discipline` = '".$reporte_discipline[$r]['Discipline']."'";
                            $resultadoSegurosTable = ejecutar($sqlSegurosTable, $conexion);
                            $reporteAuthTreatVisit = 0;                                    
                            $j = 0;
                            while($datos = mysqli_fetch_assoc($resultadoSegurosTable)){
                                $reporteAuthTreatVisit = $datos['visit_remain'];
                                $j++;
                            } 
                        }else{
                            $disabled = "disabled";
                            $reporteAuthTreatVisit = 0;
                        }
                        echo '<td align="center">';
                        echo '<input type="text" size="1" maxlength="3" id="'.$arrayTypeDoc[($l-1)].'_vis-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" name="'.$arrayTypeDoc[($l-1)].'_vis-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" class="form-control" style="text-align: center" value="'.$reporteAuthTreatVisit.'" '.$disabled.'>';
                        echo '</td>';
                    }
                    $g++;
                }                                                                                                                                                
                echo '</tr>';

                $r++;
            }
            echo '</table>';                                
            echo '<br/>';
            $l++;
        }
        //####################################################################################################################################################
        //####################################################################################################################################################
        //####################################################################################################################################################
        //########################################################### PROGRESS NOTE ##########################################################################
        //####################################################################################################################################################
        //####################################################################################################################################################
        //####################################################################################################################################################                            
        echo '<table border="1" style = "width:50%;height:180px;" align = "center" bordercolor="#BDBDBD">';
        echo '<tr>
            <td align="center" colspan = "4">
                <b>PROGRESS NOTE</b>
            </td>                        
        </tr>';
        echo '<tr>';
        $g = 0;
        while(isset($reporteSegurosTypePerson[$g])){
            echo '<td align="center">
                    '. $reporteSegurosTypePerson[$g]['seguros_type_person'].'
                    </td>';                                        
            echo '<td align="center">
                    Visits
                    </td>';
            $g++;
        }                                        
        echo '</tr>';                                                                                                       
        $r=0;
        while (isset($reporte_discipline[$r])){  
            echo '<tr>';
            $g = 0;
            while(isset($reporteSegurosTypePerson[$g])){
                echo '<td align="center">';
                if(isset($reporteSegurosTable[0]) && in_array('5-'.$reporteSegurosTypePerson[$g]['id_seguros_type_person'].'-'.$reporte_discipline[$r]['Discipline'], $reporteSegurosTable)){ 
                    $checked = "checked"; 
                    $disabled = "";
                    $sqlSegurosTable = "SELECT * FROM tbl_seguros_table t "
                            . " LEFT JOIN tbl_seguros_progress sp ON sp.id_seguros_table = t.id_seguros_table "
                            . "WHERE t.`id_seguros` = ".sanitizeString($conexion, $_GET['id_seguros'])." AND "
                            . "t.`id_seguros_type_task` = 5 AND t.`id_seguros_type_person` = ".$reporteSegurosTypePerson[$g]['id_seguros_type_person']." AND t.`discipline` = '".$reporte_discipline[$r]['Discipline']."'";
                    $resultadoSegurosTable = ejecutar($sqlSegurosTable, $conexion);
                    $reporteVisitsPediatric = 0;                                    
                    $j = 0;
                    while($datos = mysqli_fetch_assoc($resultadoSegurosTable)){
                        $reporteVisitsPediatric = $datos['visits'];
                        $j++;
                    }

                } else { 
                    $reporteVisitsPediatric = 0;
                    $checked = "";                                     
                    $disabled = "disabled";
                }
                echo $reporte_discipline[$r]['Discipline'].'&nbsp;<input type="checkbox" onclick= "desbloquerVisits(this,\'progress_visits-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'\')" id="progress/'.$reporteSegurosTypePerson[$g]['first_letter'].'|'.$reporte_discipline[$r]['Discipline'].'" name="progress/'.$reporteSegurosTypePerson[$g]['first_letter'].'|'.$reporte_discipline[$r]['Discipline'].'" '.$checked.'>';
                echo '</td>';
                echo '<td align="center">
                <input type="text" size="1" maxlength="3" id="progress_visits-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" name="progress_visits-'.$reporteSegurosTypePerson[$g]['first_letter'].'-'.$reporte_discipline[$r]['Discipline'].'" class="form-control" style="text-align: center" value="'.$reporteVisitsPediatric.'" '.$disabled.'>
                </td>';

                $g++; 

            }                                
            echo '</tr>';                
            $r++;
        }                                    
        echo '</table>';           
    ?> 
    <br/> 
    <div class="row">
        <?php
            if ($accion == 'insertar') BorrarPrecios($conexion, $IdSeguros);
            if (($accion == 'insertar') || (($accion == 'modificar') && (!ExistenPrecios($conexion, $IdSeguros))))
                CrearPrecios($conexion, $IdSeguros);
            TablaPreciosSeguros($conexion);
            TablaModificadores($conexion);

            function BorrarPrecios($conexion, $Id) {
                $query = "delete from insurance_prices where IdInsurance = 0";
                ejecutar($query, $conexion);
            }

            function CrearPrecios($conexion, $Id) {
                $query = "insert into insurance_prices(IdInsurance, IdCPT) select $Id, ID from cpt";
                ejecutar($query, $conexion);
            }

            function ExistenPrecios($conexion, $Id) {
                $query = "select count(*) from insurance_prices where IdInsurance=$Id";
                $result = ejecutar($query, $conexion);
                return (mysqli_result($result, 0, 0) > 0);
            }

            function TablaPreciosSeguros($conexion) {
                $query = "select ip.Id, cpt.Discipline, cpt.cpt as 'CPT Code', ip.Rate_Unit, Allowed, Encounter from insurance_prices ip inner join cpt on cpt.ID = ip.IdCPT";
                $result = ejecutar($query, $conexion);
                echo '<table class="table table-striped table-bordered table-hover table-responsive">';
                echo '<tr><td align="center" colspan = "5"><b>INSURANCE PRICES</b></td></tr>';
                echo "<tr><th>Discipline</th><th>CPT Code</th><th>Rate/Unit</th><th>Allowed</th><th>Encounter</th></tr>";
                while ($row = $result -> fetch_array() ) {
                    echo "<tr>";
                    echo "<td>$row[1]</td>";
                    echo "<td>$row[2]</td>";
                    echo "<td>$" . EntradaRateUnit ($row[0], $row[3]) . "</td>";
                    echo "<td>$" . EntradaAllowed  ($row[0], $row[4]) . "</td>";
                    echo "<td>"  . EntradaEncounter($row[0], $row[5]) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            function TablaModificadores($conexion) {
                echo '<div id="mostrar"></div>';
                echo '<table id="tabla_modificadores" class="table table-striped table-bordered table-hover table-responsive">';
                echo '<tr><td align="center" colspan = "9"><b>MODIFIERS PRICES</b></td></tr>';
                echo "<tr><th>Discipline</th><th>CPT Code</th><th>Diag.</th><th>Modifier</th><th>Rule</th><th>Rate/Unit</th><th>Allowed</th><th>Enabled</th><th></th></tr>";
                echo AddModifierRowInput($conexion);
                $query = "select imp.Id, cpt.Discipline, cpt.cpt, dc.DiagCodeValue, im.Modifier, ir.Rule, imp.Rate_Unit, imp.Allowed, imp.Enabled from insurance_modifiers_prices imp inner join cpt on cpt.ID = imp.IdCPT left join diagnosiscodes dc on dc.DiagCodeId = imp.IdDiag inner join insurance_modifiers im on im.Id = imp.IdModifier inner join insurance_rules ir on ir.Id = imp.IdRule order by Id";
                $result = ejecutar($query, $conexion);
                while ($row = $result -> fetch_array())
                {
                    if ($row[3] == "") $row[3]  = "All";
                    echo AddModifierRow($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
                }
                echo "</table>";
            }

            function AddModifierRow($id, $dis, $cpt, $dia, $mod, $rul, $rat, $all, $ena) {
                $result = '<tr id="F' . $id . '">';
                $result .= "<td>$dis</td>";
                $result .= "<td>$cpt</td>";
                $result .= "<td>$dia</td>";
                $result .= "<td>$mod</td>";
                $result .= "<td>$rul</td>";
                $result .= "<td>$" . $rat .  "</td>";
                $result .= "<td>$" . $all .  "</td>";
                $result .= "<td>"  . EntradaEnabled($id, $ena) .  "</td>";
                $result .= "<td>" . OpDelete($id) . "</td>";
                $result .= "</tr>";
                return $result;
            }

            function AddModifierRowInput($conexion) {
                $Id = "0";
                $result = "<tr>";
                $DL = DisciplineList($conexion);
                $result .= "<td>" . Selector("DIS" . $Id, $DL) . "</td>";
                $result .= "<td>" . Selector("CPT" . $Id, CPTList($conexion, $DL[0]['Value'])) . "</td>";
                $result .= "<td>" . Selector("DIA" . $Id, DiagList($conexion)) . "</td>";
                $result .= "<td>" . Selector("MOD" . $Id, ModifiersList($conexion)) . "</td>";
                $result .= "<td>" . Selector("RUL" . $Id, RulesList($conexion)) . "</td>";
                $result .= "<td>" . EntradaRateUnitModifier("RAT" . $Id, $row[6]) .  "</td>";
                $result .= "<td>" . EntradaAllowedModifier ("ALL" . $Id, $row[7]) .  "</td>";
                $result .= "<td>" . EntradaEnabled         ("ENA" . $Id, $row[8]) .  "</td>";
                $result .= "<td>" . OpSave($Id) . "</td>";
                $result .= "</tr>";
                return $result;
            }

            function OpEdit($id) {
                return '<a class="operacion_seguros" href="" id="E' . $id . '"><u>Edit</u></a>';
            }

            function OpSave($id) {
                return '<a class="operacion_seguros" href="" id="S' . $id . '"><u>Save</u></a>';
            }

            function OpDelete($id) {
                return '<a class="operacion_seguros" href="" id="D' . $id . '"><u>Delete</u></a>';
            }

            function OpCancel($id) {
                return '<a class="operacion_seguros" href="" id="C' . $id . '"><u>Cancel</u></a>';
            }

            function EntradaRateUnit($id, $value) {
                return '<input type="edit" class="entrada_seguros" id="r' . $id . '" value="' . $value . '" />';
            }

            function EntradaAllowed($id, $value) {
                return '<input type="edit" class="entrada_seguros" id="a' . $id . '" value="' . $value . '" />';
            }

            function EntradaEncounter($id, $value) {
                return '<input type="checkbox" class="entrada_seguros" id="e' . $id . '" ' . (($value == 1)?('checked="checked"'):('')) . ' />';
            }

            function EntradaRateUnitModifier($id, $value) {
                return '<input type="edit" class="entrada_seguros" id="' . $id . '" value="' . $value . '" />';
            }

            function EntradaAllowedModifier($id, $value) {
                return '<input type="edit" class="entrada_seguros" id="' . $id . '" value="' . $value . '" />';
            }

            function EntradaEnabled($id, $value) {
                return '<input type="checkbox" class="entrada_seguros" id="' . $id . '" ' . (($value == 1)?('checked="checked"'):('')) . ' />';
            }

            function DisciplineList($conexion) {
                $list = array();
                $query = "select DisciplineId, Name from discipline order by DisciplineId";
                $result = ejecutar($query, $conexion);
                while ($row = $result -> fetch_array())
                    $list[] = array('Id' => $row[0], 'Value' => $row[1]);
                return $list;
            }

            function CPTList($conexion, $Discipline) {
                $list = array();
                $query = "select ID, cpt from cpt   order by ID";
                $result = ejecutar($query, $conexion);
                while ($row = $result -> fetch_array())
                    $list[] = array('Id' => $row[0], 'Value' => $row[1]);
                return $list;
            }

            function DiagList($conexion) {
                $list = array();
                $query = "select DiagCodeId, DiagCodeValue from diagnosiscodes order by DiagCodeId";
                $result = ejecutar($query, $conexion);
                $list[] = array('Id' => '0', 'Value' => 'All');
                while ($row = $result -> fetch_array())
                    $list[] = array('Id' => $row[0], 'Value' => $row[1]);
                return $list;
            }

            function ModifiersList($conexion) {
                $list = array();
                $query = "select Id, Modifier from insurance_modifiers order by Id";
                $result = ejecutar($query, $conexion);
                while ($row = $result -> fetch_array())
                    $list[] = array('Id' => $row[0], 'Value' => $row[1]);
                return $list;
            }

            function RulesList($conexion) {
                $list = array();
                $query = "select Id, Rule from insurance_rules order by Id";
                $result = ejecutar($query, $conexion);
                while ($row = $result -> fetch_array())
                    $list[] = array('Id' => $row[0], 'Value' => $row[1]);
                return $list;
            }

            function Selector($id, $options) {
                $result = '';
                $result .= '<select class="entrada_seguros" id="' . $id . '">';
                foreach($options as $item)
                    $result .= '<option  value="' . $item['Id'] . '">' . $item['Value'] . '</option>';
                $result .= "</select>";
                return $result;    
            }
        ?>
    </div>
    
    <hr>
    <div class="form-group row">                
        <div class="col-sm-6"></div>                                        
        <div class="col-sm-1"><a style="cursor:pointer" onclick="return Validar_Formulario_Gestion_Seguros();"><img src="../../../images/sign-up.png" style="width: 40px" title="Save Insurance"></a></div>                    
    </div>
    <br><br>                   
    <input type="hidden" id="accion" name="accion" value="<?php echo $accion?>">
    <?php echo $generar_input; ?>
</form> 

<script>                            
    $(".multiple").multiselect({
        buttonWidth: '100%',
        enableCaseInsensitiveFiltering:true,
        includeSelectAllOption: true,
        maxHeight:400,
        nonSelectedText: 'Seleccione',
        selectAllText: 'Seleccionar todos'
    });

    LoadSelect2ScriptExt(function(){

        $('#id_reporting_system').select2();                  
        $('#id_type_provider').select2();                  
        $('#id_claim_ind').select2();                  
        $('#id_edi_gateway').select2();                  

    });

    autocompletar_radio('reporting_system','id_reporting_system','tbl_seguros_reporting_system','selector',<?php if(isset($_GET['id_reporting_system']) && $_GET['id_reporting_system'] != null) { echo sanitizeString($conexion, $_GET['id_reporting_system']); } else { echo 'null'; }?>,null,null,null,'id_reporting_system');   
    autocompletar_radio('type_provider','id_type_provider','tbl_seguros_type_provider','selector',<?php if(isset($_GET['id_type_provider']) && $_GET['id_type_provider'] != null) { echo sanitizeString($conexion, $_GET['id_type_provider']); } else { echo 'null'; } ?>,null,null,null,'id_type_provider');             
    autocompletar_radio('claim_ind','id_claim_ind','tbl_seguros_claim_ind','selector',<?php if(isset($_GET['id_claim_ind']) && $_GET['id_claim_ind'] != null) { echo sanitizeString($conexion, $_GET['id_claim_ind']); } else { echo 'null'; }?>,null,null,null,'id_claim_ind'); 
    autocompletar_radio('edi_gateway','id_edi_gateway','tbl_seguros_edi_gateway','selector',<?php if(isset($_GET['id_edi_gateway']) && $_GET['id_edi_gateway'] != null) { echo sanitizeString($conexion, $_GET['id_edi_gateway']); } else { echo 'null'; }?>,null,null,null,'id_edi_gateway');             
        
    function llenar_campos(valores) {                        
        var tipo_seguros_array;        
        valores = "'"+valores+"'";            
        var posicion = valores.indexOf('|');                      
        if(posicion != '-1'){
            tipo_seguros_array = valores.split("|");
        } else {
            tipo_seguros_array = valores;   
        }           
        var longitud = tipo_seguros_array.length;           
        for (var x = 0; x < longitud; x++) {
            tipo_seguros_array[x] = replaceAll(tipo_seguros_array[x],"'",'');
                $('input:checkbox').each(function(){
                    if(tipo_seguros_array[x] == $(this).val()){                           
                        $(this).prop('checked',true);                           
                    }
                });                    
        }
    }
        
    function result_seguros(){           
        $("#resultado_consulta").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
        $("#resultado_consulta").load("../seguros/result_seguros.php");           
    }  

    $(document).ready(function() {
    result_seguros();             
    });  

    <?php if(isset($_GET['tipo_seguros'])) {?>
        //llenar_campos(<?php echo "'".sanitizeString($conexion, $_GET['tipo_seguros'])."'";?>);
    <?php } ?>
</script>

<div id="resultado" class="text-center"></div>
<div id="resultado_consulta" class="text-center"></div>
