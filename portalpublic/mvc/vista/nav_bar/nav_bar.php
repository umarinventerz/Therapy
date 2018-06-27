<?php
if(!isset($_SESSION)) {
    session_start();
}
require_once("../../../conex.php");
$conexion = conectar();


 $sql_departaments = "select us.user_name, us.first_name, us.last_name, us.user_id, us.status_id, us.user_type, dc.departaments_company, dc.id_departaments_company "
    . "from user_system us "
    . "left join tbl_user_system_departaments_company utdc on utdc.id_user_system = us.user_id "
    . "left join tbl_departaments_company dc on dc.id_departaments_company = utdc.id_departaments_company "
    . "where user_id ='".$_SESSION['user_id']."'";
$resultado_departaments = ejecutar($sql_departaments,$conexion);
$reporte_departaments = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado_departaments)) {            
    $reporte_departaments[$i] = $datos;
    $i++;
}

if(!isset($_GET['id_departaments_company']) && !isset($_SESSION['id_departaments_company']))    
    $_SESSION['id_departaments_company'] = $reporte_departaments[0]['id_departaments_company'];
else{
    if(isset($_GET['id_departaments_company'])){
        $_SESSION['id_departaments_company'] = $_GET['id_departaments_company'];
    }
}
    


 if($_SESSION['user_id'] == 8  ){

$sql_tabs  = "select * from tbl_user_system_tabs ust "
        . "left join tbl_tabs t on t.id_tabs = ust.id_tabs "
        . "left join tbl_departaments_company dc on dc.id_departaments_company = ust.id_departaments_company "
        . "where ust.id_user_system = ".$_SESSION['user_id']." "
        . " AND  ust.id_departaments_company = ".$_SESSION['id_departaments_company']." 
           ORDER BY t.id_tabs DESC";

}
else{
    $sql_tabs  = "select * from tbl_user_system_tabs ust
 left join tbl_tabs t on t.id_tabs = ust.id_tabs 
left join tbl_departaments_company dc on dc.id_departaments_company = ust.id_departaments_company 
where ust.id_user_system = '".$_SESSION['user_id']."'  
AND  ust.id_departaments_company ='".$_SESSION['id_departaments_company']."' 
AND t.tabs != 'Generate Graph'
ORDER BY t.id_tabs asc";

}


#AND t.tabs!= 'Generate Graph'



//$sql_tabs = "select * from tbl_user_system_tabs ust "
//        . "left join tbl_tabs t on t.id_tabs = ust.id_tabs "
 //       . "left join tbl_departaments_company dc on dc.id_departaments_company = ust.id_departaments_company "
 //       . "where ust.id_user_system = ".$_SESSION['user_id']." "
 //       . " AND  ust.id_departaments_company = ".$_SESSION['id_departaments_company']." ORDER BY t.id_tabs";



$resultado_tabs = ejecutar($sql_tabs,$conexion);
$reporte_tabs = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado_tabs)) {            
    $reporte_tabs[$i] = $datos;
    $i++;
}




//die;
?>
<style>
    a{        
        cursor: pointer;
    }
</style>
<link href="../../../css/pnotify.custom.css" rel="stylesheet">
<script src="../../../js/pnotify.custom.js"></script>
<script src="../../../js/funciones.js"></script>
<script>

var div;
var no_mostrar;
    
$(document).ready(function() {
  
    if(document.getElementById('cantidad_tareas')){
        
       div = 'cantidad_tareas';
       no_mostrar = '?&no_mostrar=si'; 
       
    } else {
        
        div = 'cantidad_tareas_hidden';
        no_mostrar = '?&no_mostrar=no';                      
        $("#cantidad_tareas_hidden").css("display", "none");
        
    }  
    
        
    
   setInterval(function(){     
    
        $("#"+div).load("../tareas/consultar_cantidad_tareas.php"+no_mostrar);
   
    },1000);    
  
  
}); 
    

</script>
<style>
.label,.glyphicon { margin-right:5px; }
</style>
<div class="container">
    <nav class="navbar navbar-inverse navbar-fixed-top" style="font-size: 13px" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span><span
                    class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../../vista/home/home.php"><font size="3%">THERAPY AID</font></a>
        </div>
        
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
             <!--  <a class="navbar-brand" href="../../../webmail" target="_blank" style="font-size: 13px"><span class="glyphicon glyphicon-envelope"></span>Mail</a> -->
                <?php
                    $i = 0;
                    while(isset($reporte_tabs[$i])){
                        
                        if($reporte_tabs[$i]['glyphicon']!= null)
                           $span_glyphicon = '<span class="glyphicon '.$reporte_tabs[$i]['glyphicon'].'"></span>';
                        else
                            $span_glyphicon = '';
                        
                        $sql_sub_tabs = " SELECT * FROM tbl_sub_tabs st WHERE id_tabs = ".$reporte_tabs[$i]['id_tabs']."   order by id_sub_tabs";
                        $resultado_sub_tabs = ejecutar($sql_sub_tabs,$conexion);
                        $reporte_sub_tabs = array();

                        $p = 0;      
                        while($datos = mysqli_fetch_assoc($resultado_sub_tabs)) {            
                            $reporte_sub_tabs[$p] = $datos;
                            $p++;
                        }
                        if(count($reporte_sub_tabs) > 0){
                            $r = 0;
                            echo '  <li class="dropdown"><a href="'.$reporte_tabs[$i]['route'].'" class="dropdown-toggle" data-toggle="dropdown">
                                        '.$span_glyphicon.$reporte_tabs[$i]['tabs'].'<b class="caret"></b></a>
                                        <ul class="dropdown-menu">';
                                            while(isset($reporte_sub_tabs[$r])){
                                                echo '<li><a href="'.$reporte_sub_tabs[$r]['route'].'">'.$reporte_sub_tabs[$r]['sub_tab'].'</a></li>';
                                                $r++;
                                            }
                            echo '      </ul>
                                     </li>';
                        }else{
                            if ($reporte_tabs[$i]['id_tabs'] == 21) {
                            echo '<li><a href="'.$reporte_tabs[$i]['route'].'" target="_blank">'.$span_glyphicon.$reporte_tabs[$i]['tabs'].'</a></li>';
                        }else{
                            echo '<li><a href="'.$reporte_tabs[$i]['route'].'">'.$span_glyphicon.$reporte_tabs[$i]['tabs'].'</a></li>';
                        }
                        }
                        
                        $i++;
                        
                    }?>
                <li><div id="cantidad_tareas_hidden"></div></li>
            </ul>


                <?php if(isset($reporte_departaments[0])){
                    echo '<ul class="nav navbar-nav navbar-right">';
                         if(count($reporte_departaments)>1){
                            $t=0;
                            echo '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                            class="glyphicon glyphicon-user"></span>'.$reporte_tabs[0]['departaments_company'].' <b class="caret"></b></a>
                            <ul class="dropdown-menu">';
                            while(isset($reporte_departaments[$t])){
                
                                echo '<li><a href="#" onclick="update_nav_bar('.$reporte_departaments[$t]['id_departaments_company'].')"><span class="glyphicon glyphicon-user"></span>'.$reporte_departaments[$t]['departaments_company'].'</a></li>';

                                $t++;                    
                            }
                            echo '<li><a href="../../../exit.php"><span class="glyphicon glyphicon-off"></span>Exit</a></li>'
                            . '</ul></li>';

                          


                        }else{
                            echo '<li><a href="#"></span>'.$reporte_departaments[0]['departaments_company'].'</a></li>';
                        }
                        echo '</ul>';
                    }
                ?>            
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>
<br>
<script>
    function update_nav_bar(departament){
        window.location = "../../vista/home/home.php?id_departaments_company="+departament;
    }
</script>
