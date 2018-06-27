<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}


$conexion = conectar();
$sql2  = "SELECT * FROM templates where type=2 order by id desc";
$resultado1 = ejecutar($sql2,$conexion); 
$j = 0;      
while($datos = mysqli_fetch_assoc($resultado1)){            
    $template[$j] = $datos;
    $j++;
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
    


    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>    
    <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
    <script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/bootstrap-multiselect.js"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-multiselect.css">


   
</head>

<body>


<!-- NAV BAR  -->
 <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row" >
            <div class="col-lg-12">
                <h1><div align="center"><u>Send text message</u></div></h1>
            </div>
        </div>
        <div class="row" >
            <div class="col-lg-12">
      		<br><br><br>
		<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">

		<h3><?php echo '<b>User:</b> '.$_SESSION['first_name'].' '.$_SESSION['last_name']?></h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <form id="form1" role="form" action="../../controlador/report_mail/sendSms_RingCentral.php" method="post">
		
		
		<div class="row" style="margin : 2px;">
                    
                        <div class="form-group">
                                                           
                            <label><font color="#585858">Type Person</font></label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <select class="form-control" id="id_type_person" name="id_type_person" onchange="cargar_valores_select(this.value);"><option value="">--- SELECT TYPE PERSON ---</option></select>
                                </div>  
                            </div>
                        </div>
                        <div id="tipo_persona_select"></div>                  
                        <div class="form-group">

			    <label for="inputSubject">Subject</label>
                            <div class="row">
                                <div class="col-sm-10 text-center">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
                                </div>
                            </div>
                        </div><br>
		 </div>
                <div id="plantilla">

                            <div class="form-group">

                                <label for="inputSubject">Template</label>
                                <div class="row">
                                    <div class="col-sm-10" id="tipo_persona_select">
                                        <select name="templates" id="templates" class="form-control" onchange="templates_n(this)">
                                            <option value="0">--SELECT--</option>
                                            <option value="Without">Without Template</option>
                                            <option value="nueva">New</option>                                
                                            <?php 
                                                $i=0;
                                                while(isset($template[$i])!=null){
                                            ?>
                                                <option value="<?=$template[$i]['id']?>"><?=$template[$i]['name']?></option>
                                            <?php
                                                $i++;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </div><br>
                    </div>
            <div id="body_sms"></div>
		 
		
		  <div class="row" style="margin : 2px;">
			 <div class="col-xs-2">
                             <button type="button" class="btn btn-primary" onclick="send();">Send message</button>
			</div>	
						
		</div>
        </form>

        

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; THERAPY AID 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
  
</body>
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
	
            
           // LoadSelect2ScriptExt(DemoSelect2);
            autocompletar_select('id_type_persons','type_persons','id_type_person',"Select id_type_persons,type_persons from tbl_doc_type_persons order by id_type_persons ");
            autocompletar_select('user_id','complete_name','id_user',"SELECT *,concat(Last_name,\', \',First_name) as complete_name FROM user_system  order by Last_name ");

    });
    
    function DemoSelect2(){
	//$('#s2_with_tag').select2({placeholder: "Select Status"});	
	$('#id_type_person').select2();	
        //$('#id_person').select2();	
        //$('#id_user').select2();
        
}

    function send(){
        var id_template=$("#id_template").val();         
        var pat_id=$("#Pat_id").val();
        var subject=$("#subject").val();
        var sms=$("#sms").val();        
        
        if(subject===''){
            alert("Please enter a subject");
            return false;
        }
        
        if(id_template===undefined || id_template===''){
            
            alert("Please, select or create a template");
            return false;
        }
        form1.submit();
    }
    function cargar_valores_select(valor){


                    $('#table_reference').val('');
                    if(valor == '1'){ 
                        $('#table_reference').val('patients');
                        $( "#tipo_persona_select" ).load( "ajax_tipo_person.php?variable=1" );
                        
                    }

                    if(valor == '2'){    
                        $('#table_reference').val('employee');
                        $( "#tipo_persona_select" ).load( "ajax_tipo_person.php?variable=2" );                    
                    } 
                    
                    if(valor == '3'){
                        $('#table_reference').val('seguros');
                        $( "#tipo_persona_select" ).load( "ajax_tipo_person.php?variable=3" );                   
                    }

                    if(valor == '4'){
                        $('#table_reference').val('physician');
                        $( "#tipo_persona_select" ).load( "ajax_tipo_person.php?variable=4" );                     
                    } 
                    
                    if(valor == '5'){
                        $('#table_reference').val('tbl_referral');
                        $( "#tipo_persona_select" ).load( "ajax_tipo_person.php?variable=5" );                    
                    }
                
                    if(valor == '6'){
                        $('#table_reference').val('tbl_contacto_persona');
                       $( "#tipo_persona_select" ).load( "ajax_tipo_person.php?variable=6" );                       
                    }
                  
                }
                
        function templates_n(elemento){
            //var id= elemento.selectedValue;
            var id=elemento.options[elemento.selectedIndex].value;
            
            if(id==='0'){
                
                $( "#body_sms" ).html("");
            }else{
                $( "#body_sms" ).load( "message_template.php?templates=si&id_templates="+id);
                
            }

        }
        
        
        
        
         

</script>

</html>
