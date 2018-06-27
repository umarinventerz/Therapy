<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$conexion = conectar();
if($_GET['templates']=='no'){
        //capturo la fecha pasada
        $fecha=$_GET['date'];
        //seteo la fecha al formato que tiene la columna date
        $particion=  explode('/', $fecha);       
        $mes=$particion[0];
        $dia=$particion[1]; 
        $anio=$particion[2];                                
        $month=$mes+0;
        $day=$dia+0;
        $array=array($month,$day,$anio);
        $fecha_formateada=implode("/",$array);
        $sql  = "SELECT * 
                    FROM appointments_cs
                    JOIN patients p on p.Pat_id=appointments_cs.Pat_id 
                    and p.active='1'
                    WHERE  true  AND user_last_name <> 'null' and patient_last_name <> 'null'
                    and location='Office'
                            or type = 'Outside Therapy'
                            and type <> 'Pending Authorization'
                            and date='".$fecha_formateada."'
                    GROUP BY appointments_cs.Pat_id
                     ";

        $resultado = ejecutar($sql,$conexion); 
        $j = 0;      
        while($datos = mysqli_fetch_assoc($resultado)){            
            $reporte[$j] = $datos;
            $j++;
        }
       
}
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
<!DOCTYPE html>
<html lang="en">
    
<head>
    
</head>

        <?php
            if($_GET['templates']=='no'){
        ?>

                <div class="form-group">
                    <label><font color="#585858">Patients</font></label>
                    <div class="row">
                        <div class="col-sm-10" id="tipo_persona_select">
                            <select name="id[]" id="id" class="multiple" multiple>                    
                                <?php 
                                    $i=0;
                                    while(isset($reporte[$i])!=null){
                                ?>
                                    <option value="<?=$reporte[$i]['Pat_id']?>"><?=$reporte[$i]['patient_last_name']?>,<?=$reporte[$i]['patient_first_name']?></option>
                                <?php
                                    $i++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>                    
                </div>
             
                <div class="form-group">

                    <label for="inputSubject">Subject</label>
                    <div class="row">
                        <div class="col-sm-10 text-center">
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
                        </div>
                    </div>

               </div><br>




            <?php }else{ ?>  
                    
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
                                                    <input type="checkbox" id="last_name_patients" name="last_name_patients" onchange="agregar_vista(2);" /> last_name_patients
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="first_name_doctor" name="first_name_doctor" onchange="agregar_vista(3);" /> first_name_doctor
                                                </div>

                                            </div>
                                    
                                            <div class="col-sm-6 text-center">
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="last_name_doctor" name="last_name_doctor" onchange="agregar_vista(10);" /> last_name_doctor
                                                </div>
                                                 <div class="col-sm-2 text-center">
                                                     <input type="checkbox" id="date_valor" name="date_valor" onchange="agregar_vista(4);" /> Date
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <input type="checkbox" id="hour" name="hour" onchange="agregar_vista(5);" /> Hour
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <input type="checkbox" id="type_valor" name="type_valor" onchange="agregar_vista(6);" /> Type
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <input type="checkbox" id="phone" name="phone" onchange="agregar_vista(7);" /> Phone
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <input type="checkbox" id="description" name="description" onchange="agregar_vista(8);" /> Description
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <input type="checkbox" id="location" name="location" onchange="agregar_vista(9);" /> Location
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
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="first_name_doctor" name="first_name_doctor" onchange="agregar_vista(3);" <?php if(isset($array_variable)){ if(in_array('$first_name_doctor',$array_variable)){?>checked=""<?php } } ?>/> first_name_doctor
                                                </div>

                                            </div>
                                    
                                            <div class="col-sm-6 text-center">
                                                <div class="col-sm-4 text-center">
                                                    <input type="checkbox" id="last_name_doctor" name="last_name_doctor" onchange="agregar_vista(10);" <?php if(isset($array_variable)){ if(in_array('$last_name_doctor',$array_variable)){?>checked=""<?php } } ?>/> last_name_doctor
                                                </div>
                                                 <div class="col-sm-2 text-center">
                                                     <input type="checkbox" id="date_valor" name="date_valor" onchange="agregar_vista(4);" <?php if(isset($array_variable)){ if(in_array('$date',$array_variable)){?>checked=""<?php } } ?>/> Date
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <input type="checkbox" id="hour" name="hour" onchange="agregar_vista(5);" <?php if(isset($array_variable)){ if(in_array('$hour',$array_variable)){?>checked=""<?php } }?>/> Hour
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <input type="checkbox" id="type_valor" name="type_valor" onchange="agregar_vista(6);" <?php if(isset($array_variable)){ if(in_array('$type',$array_variable)){?>checked=""<?php } } ?>/> Type
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <input type="checkbox" id="phone" name="phone" onchange="agregar_vista(7);" <?php if(isset($array_variable)){ if(in_array('$phone',$array_variable)){?>checked=""<?php } } ?>/> Phone
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <input type="checkbox" id="description" name="description" onchange="agregar_vista(8);" <?php if(isset($array_variable)){ if(in_array('$description',$array_variable)){?>checked=""<?php } } ?>/> Description
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <input type="checkbox" id="location" name="location" onchange="agregar_vista(9);" <?php if(isset($array_variable)){ if(in_array('$location',$array_variable)){?>checked=""<?php } } ?>/> Location
                                                </div>
                                                
                                            
                                            </div>
                                            
                                       
                                        </div>
                                    </div>
                                     
                                </div>
                            </div>
                        <?php if($actualizar==''){?>
                        <input type="hidden" name="variable" id="variable"/>
                        <?php }else{?>
                        <input type="hidden" name="variable" id="variable" value="<?=$hidden_variable?>"/>
                        <?php } ?>
                                    <?php if($actualizar==''){?>
                        <div align="center"><button class="btn btn-primary" type="button" onclick="gestion_templates(1)">Save</button></div><br>
                                    <?php }else{?>
                        <div align="center"><button class="btn btn-warning" type="button" onclick="gestion_templates(2)">Modify</button></div><br>
                                    <?php } ?>
                                
                        </div>
                    </div>


                    
            <?php } } ?>

<script>
    $(".multiple").multiselect({
	 buttonWidth: '100%',
	 enableCaseInsensitiveFiltering:true,
	 includeSelectAllOption: true,
	 maxHeight:400,
	 nonSelectedText: 'Seleccione',
	 selectAllText: 'Seleccionar todos'
    });
    $(document).ready(function() {
            $('#date_change').datepicker({setDate: new Date()});
            $(function () {
              $("#date_change").click(function () {
              $("#date_change").datepicker("setDate", "+1d");
              });
            });
    });
    
    function agregar_vista(variable){
       
        if(variable===1){            
            if($('#first_name_patients').is(':checked') ){               
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
        if(variable===3){            
            if($('#first_name_doctor').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $first_name_doctor';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$first_name_doctor');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $first_name_doctor", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$first_name_doctor", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        
        if(variable===4){            
            if($('#date_valor').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $date';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$date');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $date", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$date", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        if(variable===5){            
            if($('#hour').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $hour';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$hour');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $hour", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$hour", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        if(variable===6){            
            if($('#type_valor').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $type';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$type');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $type", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$type", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        if(variable===7){            
            if($('#phone').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $phone';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$phone');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $phone", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$phone", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        if(variable===8){            
            if($('#description').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $description';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$description');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $description", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$description", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        if(variable===9){            
            if($('#location').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $location';
                var compilado=anterior+patients_first_name;
                var valor=$("#variable").val();
                var variable=$("#variable").val(valor+'-$location');
                $("#text_date_change").val(compilado);
            }else{
                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $location", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$location", ""); 
                 variable=$("#variable").val(quitar_variable);
            }
        }
        if(variable===10){            
            if($('#last_name_doctor').is(':checked') ){               
                var anterior=$("#text_date_change").val();               
                var patients_first_name=' $last_name_doctor';
                var valor=$("#variable").val();
                var compilado=anterior+patients_first_name;
                var variable=$("#variable").val(valor+'-$last_name_doctor');
                $("#text_date_change").val(compilado);
            }else{

                 var anterior=$("#text_date_change").val();  
                 var quitar = anterior.replace(" $last_name_doctor", "");
                 $("#text_date_change").val(quitar);
                 var valor=$("#variable").val();
                 var quitar_variable=valor.replace("-$last_name_doctor", ""); 
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
	        data: {'name':name,'description':description,'accion':action,'id_template':id_template,'variable':variable,'type':'1'},
	        //async:false,
	        dataType: "json",
                success: function(data){
                   alert(data.succes);
                   $("#id_template").val(data.id);
                }
        });
    
    } 
</script>
</html>
