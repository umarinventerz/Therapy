<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$conexion = conectar();
if($_GET['templates']=='si'){
    
        $id_templates=$_GET['id_templates'];
        
        if($id_templates=='nueva'){
            $nueva='si';
            
        }else{
            $nueva='no';
        }
        if($id_templates=='Without'){
            $Without='si';
            
        }else{
            $Without='no';
        }
        if($nueva=='no' && $Without=='no'){

           $sql3  = "SELECT * FROM templates WHERE  id=".$id_templates;

            $resultado3 = ejecutar($sql3,$conexion); 
            $j = 0;      
            while($datos = mysqli_fetch_assoc($resultado3)){            
                $body[$j] = $datos;
                $j++;
            }
            $hidden_variable=$body[0]['variable'];
            $name=$body[0]['name'];
            $cuerpo=$body[0]['description'];  
            $variable=$body[0]['variable'];
            $array_variable=  explode('-', $variable);            
            $id=$body[0]['id'];
            $actualizar='si';
            
        }else{
            $name='';
            $cuerpo='';
            $actualizar='';
            
        }
}
?>
<?php if($Without=='si'){?>

    <input type="hidden" name="id_template" id="id_template" value="Without"/>
                <div id="content"></div>
                <div class="panel panel-primary">
                    <div class="panel-body"><div align="center"><b><u>Without Template</u></b></div><br>
                       <br> 
                        <div id="templates_id">
                            <label for="inputPassword">Body</label><br>
                            <div class="panel panel-danger">

                                    <div class="panel-body1"> 
                                        <div class="row">   

                                            <div class="col-sm-6 text-center">
                                                <TEXTAREA COLS=7 ROWS=5 class="form-control" placeholder="Body" name="text_date_change" id="text_date_change"></TEXTAREA> 
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="first_name_patients" name="first_name_patients" onchange="agregar_vista(1);"/> first_name_patients
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="last_name_patients" name="last_name_patients" onchange="agregar_vista(2);"/> last_name_patients
                                                </div>
                                            </div>                                            
                                        </div><br>                                       
                                            <input type="hidden" name="variable" id="variable" value=""/>
                                       </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div id="body"></div>
                <!--<div id="send" align="center"><button class="btn btn-success" type="button" onclick="send()">Send message</button></div>-->
        
                    <hr>
<?php }else{ ?>
        
                <input type="hidden" name="id_template" id="id_template" value="<?=$id?>"/>
                <div id="content"></div>
                <div class="panel panel-primary">
                    <div class="panel-body"><div align="center"><b><u><?php if($actualizar==''){?>New Template <?php }else{?>Update Template<?php }?></u></b></div><br>
                        <label for="inputPassword">Name</label>
                        <div class="row">
                            <div class="col-sm-4 text-center">
                                 <?php if($actualizar==''){?>
                                    <input type="text" name="name" class="form-control" id="name" value=""/>
                                 <?php }else{?>
                                    <input type="text" name="name" class="form-control" id="name" value="<?=$name?>"/>
                                 <?php }?>
                            </div>
                        </div><br> 
                        <div id="templates_id">
                            <label for="inputPassword">Body</label><br>
                            <div class="panel panel-danger">

                                    <div class="panel-body1"> 
                                        <div class="row">   

                                            <div class="col-sm-6 text-center">
                                                <TEXTAREA COLS=7 ROWS=5 class="form-control" placeholder="Body" name="text_date_change" id="text_date_change"><?=$cuerpo?></TEXTAREA> 
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="first_name_patients" name="first_name_patients" onchange="agregar_vista(1);"<?php if(isset($array_variable)){if(in_array('$first_name_patients',$array_variable)){?>checked=""<?php } } ?>/> first_name_patients
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="last_name_patients" name="last_name_patients" onchange="agregar_vista(2);" <?php if(isset($array_variable)){ if(in_array('$last_name_patients',$array_variable)){?>checked=""<?php } } ?>/> last_name_patients
                                                </div>
                                            </div>                                            
                                        </div><br>
                                        <?php if($actualizar==''){?>
                                            <input type="hidden" name="variable" id="variable"/>
                                        <?php }else{?>
                                            <input type="hidden" name="variable" id="variable" value="<?=$hidden_variable?>"/>
                                        <?php } ?>
                                        <?php if($actualizar=='si'){?>
                                            <div align="center"><button class="btn btn-warning" type="button" onclick="gestion_templates(2)">Modify</button></div><br>
                                        <?php }else{?>
                                            <div align="center"><button class="btn btn-primary" type="button" onclick="gestion_templates(1)">Save</button></div><br>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div id="body"></div>
                <!--<div id="send" align="center"><button class="btn btn-success" type="button" onclick="send()">Send message</button></div>-->
  <?php } ?>      
                    <hr>
<script>

        function agregar_vista(variable){

                if(variable===1){            
                    if($('#first_name_patients').is(':checked')){               
                        var anterior=$("#text_date_change").val();               
                        var patients_first_name=' $first_name_patients';
                        var compilado=anterior+patients_first_name;
                        var valor=$("#variable").val();
                        var variable=$("#variable").val(valor+'-$first_name_patients');                
                        $("#text_date_change").val(compilado);
                    }else{                 
                         var anterior=$("#text_date_change").val();  
                         var quitar = anterior.replace(" $first_name_patients", "");
                         $("#text_date_change").val(quitar);
                         var valor=$("#variable").val();
                         var quitar_variable=valor.replace("-$first_name_patients", ""); 
                         variable=$("#variable").val(quitar_variable);
                    }
                }
                if(variable===2){            
                    if($('#last_name_patients').is(':checked') ){               
                        var anterior=$("#text_date_change").val();               
                        var patients_first_name=' $last_name_patients';
                        var compilado=anterior+patients_first_name;
                        var valor=$("#variable").val();
                        var variable=$("#variable").val(valor+'-$last_name_patients');
                        $("#text_date_change").val(compilado);
                    }else{
                         var anterior=$("#text_date_change").val();  
                         var quitar = anterior.replace(" $last_name_patients", "");                  
                         $("#text_date_change").val(quitar);
                         var valor=$("#variable").val();
                         var quitar_variable=valor.replace("-$last_name_patients", ""); 
                         variable=$("#variable").val(quitar_variable);
                    }
                }
        }
    
        function gestion_templates(accion){ 
            var id_template=$("#id_template").val();
            var name=$("#name").val();
            var description=$("#text_date_change").val();
            var variable=$("#variable").val();
            
            if(name===''){            
                alert("Please, insert the name");
                return false;
            }
            if(description===''){            
                alert("Please, insert the body");
                return false;
            }
            if(accion===1){
                var action='insertar';
            }else{
                action='modificar';
            }

            $.ajax({

                    url: '../../controlador/templates/gestion.php',
                    method: "POST",
                    data: {'name':name,'description':description,'accion':action,'id_template':id_template,'variable':variable,'type':'2'},
                    //async:false,
                    dataType: "json",
                    success: function(data){
                       alert(data.succes);
                       $("#id_template").val(data.id);
                    }
            });

        }

</script>