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
 
                    
if(isset($_GET["patients"]) && $_GET["patients"] != null){ $patients = " and patients.pat_id = '".$_GET["patients"]."' "; } else { $patients = null; }
if(isset($_GET["type_report"]) && $_GET["type_report"] != null){ $type_report = " and type_report = '".$_GET["type_report"]."' "; } else { $type_report = null; }


    $discipline = null;

if(isset($_GET["discipline_st"]) || isset($_GET["discipline_ot"]) || isset($_GET["discipline_pt"])){
    
    $discipline = ' AND discipline IN (';
    
    if(isset($_GET["discipline_st"])){        
        $discipline .= '\''.$_GET["discipline_st"].'\',';        
    }
    
    if(isset($_GET["discipline_ot"])){        
        $discipline .= '\''.$_GET["discipline_ot"].'\',';        
    }
    
    if(isset($_GET["discipline_pt"])){        
        $discipline .= '\''.$_GET["discipline_ot"].'\',';        
    }  
    
    $discipline = substr ($discipline, 0, strlen($discipline) - 1);
    
    $discipline .= ') ';
    
    
}




if($_GET["input_date_start"] != null && $_GET["input_date_end"] != null){
    
$fecha_i = date_create($_GET["input_date_start"]);
$fecha_inicio = date_format($fecha_i,'Y-m-d');

$fecha_f = date_create($_GET["input_date_end"]);
$fecha_fin = date_format($fecha_f,'Y-m-d');    
    
    
$fechas = ' AND date BETWEEN STR_TO_DATE(\''.$fecha_inicio.'\',\'%Y-%m-%d\') AND STR_TO_DATE(\''.$fecha_fin.'\',\'%Y-%m-%d\')';    
    
} else {
    
    $fechas = null;
    
}



   
$conexion = conectar();
$sql  = "   SELECT 
tbl_notes.id_notes, tbl_notes.pat_id, tbl_notes.discipline, tbl_notes.notes, tbl_notes.id_careplans,
tbl_notes.type_report, tbl_notes.date, tbl_notes.user_id,
concat(trim(patients.Last_name),', ',trim(patients.First_name)) as Patient,
concat(trim(user_system.Last_name),', ',trim(user_system.First_name)) as User

 FROM tbl_notes

join patients on tbl_notes.pat_id=patients.Pat_id
join user_system on tbl_notes.user_id=user_system.user_id 

WHERE true ".$patients.$type_report.$discipline.$fechas.'    ;'; 
//$sql  = "SELECT *, Last_name || ' ' || First_name as nombres FROM tbl_notes nt left join patients pat on pat.id = nt.pat_id WHERE true ".$patients.$type_report.$discipline.$fechas.';'; 
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}   

                                   
                
?>
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">    
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    <link href="../../../css/dataTables/dataTables.bootstrap.css"rel="stylesheet" type="text/css">
    <link href="../../../css/dataTables/buttons.dataTables.css"rel="stylesheet" type="text/css">    
    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>    
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/AjaxConn.js" type="text/javascript" ></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>    
    <script src="../../../js/promise.min.js" type="text/javascript"></script>    
    <script src="../../../js/funciones.js" type="text/javascript"></script>        
    <script src="../../../js/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../../../js/resources/shCore.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/buttons.html5.js" type="text/javascript"></script>    

        <script  language="JavaScript" type="text/javascript">

                $(document).ready(function() {
                        $('#table_notes').DataTable({                                
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
                
                               
            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

        </script>    
    <div id="bajar_aqui"></div>
            <table align="center" border="0">
                <tr>
                    <td align="center"><b><font size="3">Resultado de la Consulta</font></b></td>
                </tr>
            </table>
        
<div class="col-lg-12">

    <table id="table_notes" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>

                                <th style="width:10px;" >ID NOTES  </th>                                
                                <th>PATIENT </th>
                                <th>DISCIPLINE  </th>                                
                                <th>NOTES  </th>
                                <th>TYPE REPORT  </th>
                                <th>DATE  </th>
                                <th>USER </th>                               

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) {
  
                    echo $color;
                            

                        echo '<td align="center"><font size="2"><b>'.$reporte[$i]['id_notes'].'</b></font></td>';                        
                        echo '<td align="center"><font size="2">'.$reporte[$i]['Patient'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['discipline'].'</font></td>';                        
                        echo '<td align="center"><font size="2">'.$reporte[$i]['notes'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['type_report'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['date'].'</font></td>';
                        echo '<td align="center"><font size="2">'.$reporte[$i]['User'].'</font></td>';                 
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