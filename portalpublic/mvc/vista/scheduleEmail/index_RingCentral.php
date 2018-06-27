<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$conexion = conectar();
$sql2  = "SELECT * FROM templates where type=1 order by id desc";
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
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
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
                <h1><div align="center"><u>Appointment change</u></div></h1>
            </div>
        </div>
        <div class="row" >
            <div class="col-lg-12">
      		<br><br><br>
		<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">

		<h3><?php echo '<b>User:</b> '.$_SESSION['first_name'].' '.$_SESSION['last_name']?></h3>
            </div>
        </div>
        
            <form id="form" name="form" method="POST" action="../../controlador/scheduleEmail/scheduleEmails_RingCentral.php">   
		<div class="row" style="margin : 2px;">
                    
                        <div class="form-group">
                                                           
                            <label><font color="#585858">Date</font></label>
                            <div class="row">
                                <div class="col-sm-8">
                                        <input type="text" class="form-control" name="date" id="date">
                                </div>
                            
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-primary" onclick="consultar();">Consultar</button>
                                </div>	

                            </div>
                        </div>
                </div>
        
                <div id="content"></div>

                <div id="templates">

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
                <div id="body"></div>
                <div id="send" align="center"><button class="btn btn-success" type="button" onclick="send()">Send message</button></div>
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
	
        
            $("#templates").hide();
            $("#send").hide();
            $('#date').datepicker({setDate: new Date()});
            $(function () {
              $("#date").click(function () {
              $("#date").datepicker("setDate", "+1d");
              });
            });
           
    });
    
    function consultar(){
        
        var date=$("#date").val();
        if(date===''){
            alert("Please select at date");
            return false;
        }
        $( "#content" ).load( "consult_appointments.php?date="+date+"&templates=no" );
        $("#templates").show();
        $("#send").show();
    }
     
    function templates_n(elemento){
        //var id= elemento.selectedValue;
        var id=elemento.options[elemento.selectedIndex].value;
        
        if(id==='0'){            
            $( "#body" ).html("");
        }else{
            $( "#body" ).load( "consult_appointments.php?templates=si&id_templates="+id);
        }
        
     }
     
     function send(){
         
        var subject=$("#subject").val(); 
        var templates=$("#templates").val();
        var id_template=$("#id_template").val();        
        if(subject===''){
            
            alert("Please, insert a subject");
            return false;
        }else if(id_template===undefined || id_template===''){
            
            alert("Please, select or create a template");
            return false;
        }else{
    
            form.submit();
        
        }
         
     }
        
        
         

</script>

</html>
