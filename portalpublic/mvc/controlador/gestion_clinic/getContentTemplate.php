<?php
error_reporting(0);
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
$conexion = conectar();
$editor=$_POST['editor_eval'];
if($_POST['template_id']=='si'){
    $sql="SELECT T.*,C.id as id_components FROM tbl_modal_template t"            
            . " LEFT JOIN tbl_components c ON c.id = t.componentes "
            . " WHERE t.type_modal = ".$_POST['document']." AND id_modal=".$_POST['id_modal'];
    $resultado = ejecutar($sql,$conexion);   
    $reporte = '';
    $i = 1;   
    if($editor!='editor_amendment'){
        while($datos = mysqli_fetch_assoc($resultado)) {
            $reporte.='<div id="elemento'.$datos["id_components"].'">';
            $reporte .= $datos['ckeditor'];
            $reporte.='</div>';
        }
    }else{
       while($datos = mysqli_fetch_assoc($resultado)) {
            $reporte.='<div id="elemento_amend'.$datos["id_components"].'">';
            $reporte .= $datos['ckeditor'];
            $reporte.='</div>';
        } 
    }
}else{
    $sql  = "SELECT * FROM tbl_templates t"
            . " LEFT JOIN tbl_templates_components tc ON tc.id_templates = t.id "
            . " LEFT JOIN tbl_components c ON c.id = tc.id_components "
            . " WHERE t.id = ".$_POST['template_id'].";"; 
    $resultado = ejecutar($sql,$conexion);   
    $reporte = '';
    $i = 1;  
    if($editor!='editor_amendment'){
        while($datos = mysqli_fetch_assoc($resultado)) {
            $reporte.='<div id="elemento'.$datos["id"].'">';
            $reporte .= $datos['components'];
            $reporte.='</div>';
        }
    }else{
        while($datos = mysqli_fetch_assoc($resultado)) {
            $reporte.='<div id="elemento_amend'.$datos["id"].'">';
            $reporte .= $datos['components'];
            $reporte.='</div>';
        }
    }
}

$json_resultado['contentTemplate'] = $reporte;

if($_POST['template_id']!='si'){

    $sql_components="SELECT id_components FROM tbl_templates_components where id_templates=".$_POST['template_id'];
    $result = ejecutar($sql_components,$conexion);  

    while($dat = mysqli_fetch_assoc($result)) {
        $array[]=$dat;

    }
    for($i=0;$i<count($array);$i++){
        $data[]=$array[$i]['id_components'];
    }
    $components="SELECT CC.categorie_name,TCR.categorie_id 
                FROM #tbl_templates_components TC 
                #LEFT JOIN 
                tbl_components C #ON(TC.id_components=C.id)
                JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                WHERE C.document_id=".$_POST['document']." AND C.discipline_id=".$_POST['discipline']."
                GROUP BY CC.categorie_name,TCR.categorie_id 
                order by CC.id_categories asc";
    $resultado_components = ejecutar($components,$conexion);
    $resultado_components1 = ejecutar($components,$conexion);
    $res_components='';
    $template='';
    //$res_components.='<div class="row">';
    //$res_components.='<div>';
    if($editor!='editor_amendment'){
        if($editor=='editor'){
            $tipo_editor='editor';
        }
        if($editor=='editor_poc'){
            $tipo_editor='editor_poc';
        }
        if($editor=='editor_Summary'){
            $tipo_editor='editor_Summary';
        }
        if($editor=='editor_Discharge'){
            $tipo_editor='editor_Discharge';
        }
        $p=0; 
        $res_components.='<ul class="nav nav-tabs">';
        while ($row1=  mysqli_fetch_assoc($resultado_components1)){
            $texto = str_replace(' ', '', $row1["categorie_name"]);
            $texto=preg_replace('([^A-Za-z0-9])', '', $texto);
            if($p==0){
                $res_components.='<li class="active"><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }else{
                $res_components.='<li><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }
            $res_components.='</li>';
            
            $p++;  
        }
        $res_components.='</ul><br>';
        $m=0; 
        $res_components.='<div class="tab-content">';
        while ($row=  mysqli_fetch_assoc($resultado_components)){    
            
            //asocio los componentes a la categoria
            $components_select="SELECT C.id AS id,TCR.categorie_id,C.name,C.components 
                                FROM
                                        # tbl_templates_components TC 
                                        #LEFT JOIN 
                                        tbl_components C #ON(TC.id_components=C.id)
                                        JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                                        JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                                where C.document_id=".$_POST['document']." AND C.discipline_id=".$_POST['discipline']." AND TCR.categorie_id='".$row["categorie_id"]."' ;" ;
            $resultado_components_select = ejecutar($components_select,$conexion);
            $texto1 = str_replace(' ', '', $row["categorie_name"]);
            $texto1=preg_replace('([^A-Za-z0-9])', '', $texto1);
            if($m==0){
                $res_components.='<div class="tab-pane fade in active" id="'.$texto1.'">';
            }else{
                $res_components.='<div class="tab-pane" id="'.$texto1.'">';
            }
            $res_components.='<table  id="category_id'.$m.'" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            $res_components.='<thead>';
            $res_components.='<tr>';
            $res_components.='<th>';
            $res_components.='</th>';
            $res_components.='</tr>';
            $res_components.='</thead>';
            $res_components.='<tbody>';
            while ($rows=  mysqli_fetch_assoc($resultado_components_select)){                
                
                //$res_components.='<div class="col-sm-6">';                
                
                $res_components.='<tr>';
                $res_components.='<td>';
                $res_components.='<div class="col-sm-6" id="div'.$rows["id"].'"><b>'.$rows["name"].'</b></div>';
                if(in_array($rows["id"], $data)){
                    $res_components.='<input type="checkbox" checked id="checkDiv'.$rows["id"].'" onclick="llenarDiv(\'divs_'.$rows['id'].'\',this,\''.$tipo_editor.'\')"';  
                    $template.='<div class="span12" id="clonedivs_'.$rows['id'].'">'.$rows['components'].'</div>';
                }else{
                    $res_components.='<input type="checkbox" id="checkDiv'.$rows["id"].'" onclick="llenarDiv(\'divs_'.$rows['id'].'\',this,\''.$tipo_editor.'\')"';  
                }
                               
                //$res_components.='</div><br>';
                
                $res_components.='<div>';        
                $res_components.='<span style="display:none" id="elemento'.$rows['id'].'"><div class="span12" id="divs_'.$rows['id'].'">'.$rows['components'].'</div></span>';    
                $res_components.='</div>';                
                $res_components.='</div>';
                $res_components.='</td>';
                $res_components.='</tr>';
            }
            
            $res_components.='</tbody>'; 
            $res_components.='</table>';
            $res_components.='</div>';
            
            //$res_components.='</div>';
            $m++;
        }
        $res_components.='</div>';
    }else{
        $p=0; 
        $res_components.='<ul class="nav nav-tabs">';
        while ($row1=  mysqli_fetch_assoc($resultado_components1)){
             //$res_components.='<div clas="col-md-12">';
            //muestro las categoria style="background:#2ba6cb;"
            
            //$res_components.='<div class="well well-sm col-sm-12">';
            $texto = str_replace(' ', '', $row1["categorie_name"]);
            $texto=preg_replace('([^A-Za-z0-9])', '', $texto);
            if($p==0){
                $res_components.='<li class="active"><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }else{
                $res_components.='<li><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }
            $res_components.='</li>';
            
            $p++;  
        }
        $res_components.='</ul><br>';
        $m=0;
        $res_components.='<div class="tab-content">';
        while ($row=  mysqli_fetch_assoc($resultado_components)){    
            
            //asocio los componentes a la categoria
            $components_select="SELECT C.id AS id,TCR.categorie_id,C.name,C.components 
                                FROM
                                        # tbl_templates_components TC 
                                        #LEFT JOIN 
                                        tbl_components C #ON(TC.id_components=C.id)
                                        JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                                        JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                                where C.document_id=".$_POST['document']." AND C.discipline_id=".$_POST['discipline']." AND TCR.categorie_id='".$row["categorie_id"]."' ;" ;
            $resultado_components_select = ejecutar($components_select,$conexion);
            $texto1 = str_replace(' ', '', $row["categorie_name"]);
            $texto1=preg_replace('([^A-Za-z0-9])', '', $texto1);
            
            if($m==0){
                $res_components.='<div class="tab-pane fade in active" id="'.$texto1.'">';
            }else{
                $res_components.='<div class="tab-pane" id="'.$texto1.'">';
            }
            
            $res_components.='<table  id="category_id'.$m.'" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            $res_components.='<thead>';
            $res_components.='<tr>';
            $res_components.='<th>';
            $res_components.='</th>';
            $res_components.='</tr>';
            $res_components.='</thead>';
            $res_components.='<tbody>';
            
            while ($rows=  mysqli_fetch_assoc($resultado_components_select)){
                //$res_components.='<div class="col-sm-6">';
                $res_components.='<tr>';
                $res_components.='<td>';
                
                $res_components.='<div class="col-sm-6" id="div'.$rows["id"].'"><b>'.$rows["name"].'</b></div>';
                if(in_array($rows["id"], $data)){
                    $res_components.='<input type="checkbox" checked id="checkDivAmend'.$rows["id"].'" onclick="llenarDiv(\'divsamend_'.$rows['id'].'\',this,\'editor_amend\')"';  
                    $template.='<div class="span12" id="clonedivsamend_'.$rows['id'].'">'.$rows['components'].'</div>';
                }else{
                    $res_components.='<input type="checkbox" id="checkDivAmend'.$rows["id"].'" onclick="llenarDiv(\'divsamend_'.$rows['id'].'\',this,\'editor_amend\')"';  
                }
                //$res_components.='</div><br>';
                $res_components.='<div>';        
                $res_components.='<span style="display:none" id="elemento_amend'.$rows['id'].'"><div class="span12" id="divsamend_'.$rows['id'].'">'.$rows['components'].'</div></span>';    
                $res_components.='</div>';
                $res_components.='</div>';
                $res_components.='</td>';
                $res_components.='</tr>';
            }
            $res_components.='</tbody>'; 
            $res_components.='</table>';
            $res_components.='</div>';
            $m++;
        }
        $res_components.='</div>';
    }
    //$res_components.='</div>';
    //$res_components.='</div>';
    $json_resultado['contentComponents']=$res_components;
    $json_resultado['contentTemplate']=$template;
    $json_resultado['total_category']=$m;
}else{
    //cuando ya existe un template creado
    $components="SELECT CC.categorie_name,TCR.categorie_id 
                FROM #tbl_templates_components TC 
                #LEFT JOIN 
                tbl_components C #ON(TC.id_components=C.id)
                JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                WHERE C.document_id=".$_POST['document']." AND C.discipline_id=".$_POST['discipline']."
                GROUP BY CC.categorie_name,TCR.categorie_id 
                order by CC.id_categories asc";    
    $resultado_components = ejecutar($components,$conexion);
    $resultado_components1 = ejecutar($components,$conexion);
    $res_components='';
    $template='';
    //$res_components.='<div class="row">';
    //$res_components.='<div">';
    if($editor!='editor_amendment'){
        if($editor=='editor'){
            $tipo_editor='editor';
        }
        if($editor=='editor_poc'){
            $tipo_editor='editor_poc';
        }
        if($editor=='editor_Summary'){
            $tipo_editor='editor_Summary';
        }
        if($editor=='editor_Discharge'){
            $tipo_editor='editor_Discharge';
        }
        $p=0; 
        $res_components.='<ul class="nav nav-tabs">';
        while ($row1=  mysqli_fetch_assoc($resultado_components1)){
            $texto = str_replace(' ', '', $row1["categorie_name"]);
            $texto=preg_replace('([^A-Za-z0-9])', '', $texto);
            if($p==0){
                $res_components.='<li class="active"><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }else{
                $res_components.='<li><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }
            $res_components.='</li>';
            
            $p++;  
        }
        $res_components.='</ul><br>';
        $m=0;
        $res_components.='<div class="tab-content">';
        while ($row=  mysqli_fetch_assoc($resultado_components)){    
           
            $componentes_categoria="SELECT CR.*,C.id,C.name,C.components FROM kidswork_therapy.tbl_components_categories_relation CR
                                    left join tbl_components C on(CR.component_id=C.id) WHERE CR.categorie_id=".$row["categorie_id"]." AND C.discipline_id=".$_POST['discipline']."
                                    AND C.document_id=".$_POST['document'];
            $resultado_components_categoria = ejecutar($componentes_categoria,$conexion); 
            
            //asocio los componentes a la categoria
             $components_select="SELECT C.id,TCR.categorie_id,C.name,C.components,MT.id_modal,MT.type_modal,MT.componentes AS modal_component_id,MT.ckeditor
                                FROM tbl_components C                                
                                LEFT JOIN tbl_modal_template MT ON(C.id=MT.componentes)
                                LEFT JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                                LEFT JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                                where C.document_id=".$_POST['document']." AND C.discipline_id=".$_POST['discipline']." AND type_modal<>0  AND TCR.categorie_id=".$row["categorie_id"]." AND MT.id_modal=".$_POST['id_modal'];
            
            $resultado_components_select = ejecutar($components_select,$conexion); 
            while ($category=  mysqli_fetch_assoc($resultado_components_select)){
                $select_category[]=$category;
            }
            for($i=0;$i<count($select_category);$i++){
                
                $arreglo_select[]=$select_category[$i]['id'];
                $arreglo_ckeditor[]=$select_category[$i]['ckeditor'];
            }      
            if(!isset($k)){
                $k=0;
            }else{
                $k=$k;
            }
            $texto1 = str_replace(' ', '', $row["categorie_name"]);
            $texto1=preg_replace('([^A-Za-z0-9])', '', $texto1);
            if($m==0){
                $res_components.='<div class="tab-pane fade in active" id="'.$texto1.'">';
            }else{
                $res_components.='<div class="tab-pane" id="'.$texto1.'">';
            }
            $res_components.='<table  id="category_id'.$m.'" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            $res_components.='<thead>';
            $res_components.='<tr>';
            $res_components.='<th>';
            $res_components.='</th>';
            $res_components.='</tr>';
            $res_components.='</thead>';
            $res_components.='<tbody>';
            while ($rows=  mysqli_fetch_assoc($resultado_components_categoria)){
                //$res_components.='<div class="col-sm-6">';  
                $res_components.='<tr>';
                $res_components.='<td>';
                
                $res_components.='<div class="col-sm-6" id="div'.$rows["id"].'"><b>'.$rows["name"].'</b></div>';
                
                if(in_array($rows["id"],$arreglo_select)){                    
                //if($rows["modal_component_id"]!='' || $rows["modal_component_id"]!=null){
                    //if($rows["type_modal"]!=0){
                        
                        $res_components.='<input type="checkbox" checked id="checkDiv'.$rows["id"].'" onclick="llenarDiv(\'divs_'.$rows['id'].'\',this,\''.$tipo_editor.'\')"';  
                        $template.='<div class="span12" id="clonedivs_'.$rows['id'].'">'.$arreglo_ckeditor[$k].'</div>';
                    /*}else{
                        $res_components.='<input type="checkbox" id="checkDiv'.$rows["id"].'" onclick="llenarDiv(\'divs_'.$rows['id'].'\',this,\''.$tipo_editor.'\')"';  
                    }*/
                    $k++;
                }else{
                    $res_components.='<input type="checkbox" id="checkDiv'.$rows["id"].'" onclick="llenarDiv(\'divs_'.$rows['id'].'\',this,\''.$tipo_editor.'\')"';  
                }                
                //$res_components.='</div>';
                $res_components.='<div>';        
                $res_components.='<span style="display:none" id="elemento'.$rows['id'].'"><div class="span12" id="divs_'.$rows['id'].'">'.$rows['components'].'</div></span>';    
                $res_components.='</div>';
                $res_components.='</div>';
                $res_components.='</td>';
                $res_components.='</tr>';
                
                
            }            
            $res_components.='</tbody>'; 
            $res_components.='</table>';
            $res_components.='</div>';
            $m++;
            unset($arreglo_select);
            unset($arreglo_ckeditor);
        }
        $res_components.='</div>';
    }else{
            $components_amend="SELECT CC.categorie_name,TCR.categorie_id 
                    FROM #tbl_templates_components TC 
                    #LEFT JOIN 
                    tbl_components C #ON(TC.id_components=C.id)
                    JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                    JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                    WHERE C.document_id=1 AND C.discipline_id=".$_POST['discipline']."
                    GROUP BY CC.categorie_name,TCR.categorie_id 
                    order by CC.id_categories asc";
        $resultado_components_amend = ejecutar($components_amend,$conexion);
        $resultado_components_amend1 = ejecutar($components_amend,$conexion);
        $p=0; 
        $res_components.='<ul class="nav nav-tabs">';
        while ($row1=  mysqli_fetch_assoc($resultado_components_amend1)){
            $texto = str_replace(' ', '', $row1["categorie_name"]);
            $texto=preg_replace('([^A-Za-z0-9])', '', $texto);
            if($p==0){
                $res_components.='<li class="active"><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }else{
                $res_components.='<li><a href="#'.$texto.'" data-toggle="tab">'.$row1["categorie_name"].'</a>';
            }
            $res_components.='</li>';
            
            $p++;  
        }
        $res_components.='</ul><br>';
        $m=0;
        $res_components.='<div class="tab-content">';
        while ($row=  mysqli_fetch_assoc($resultado_components_amend)){    
            
             $componentes_categoria="SELECT CR.*,C.id,C.name,C.components FROM kidswork_therapy.tbl_components_categories_relation CR
                                    left join tbl_components C on(CR.component_id=C.id) WHERE CR.categorie_id=".$row["categorie_id"]." AND C.discipline_id=".$_POST['discipline']."
                                    AND C.document_id=1";
            $resultado_components_categoria = ejecutar($componentes_categoria,$conexion); 
            
            //asocio los componentes a la categoria
            $components_select="SELECT C.id,TCR.categorie_id,C.name,C.components,MT.id_modal,MT.type_modal,MT.componentes AS modal_component_id,MT.ckeditor
                                FROM tbl_components C 
                                LEFT JOIN tbl_modal_template MT ON(C.id=MT.componentes)
                                LEFT JOIN tbl_components_categories_relation TCR ON(C.id=TCR.component_id)
                                LEFT JOIN tbl_component_categories CC ON(TCR.categorie_id=CC.id_categories)
                                where C.document_id=1 AND C.discipline_id=".$_POST['discipline']." AND type_modal=0  AND TCR.categorie_id=".$row["categorie_id"]." AND MT.id_modal=".$_POST['id_modal'];
            $resultado_components_select = ejecutar($components_select,$conexion);
            while ($category=  mysqli_fetch_assoc($resultado_components_select)){
                $select_category[]=$category;
            }
            for($i=0;$i<count($select_category);$i++){
                
                $arreglo_select[]=$select_category[$i]['id'];
                $arreglo_ckeditor[]=$select_category[$i]['ckeditor'];
            }      
            if(!isset($k)){
                $k=0;
            }else{
                $k=$k;
            }
            $texto1 = str_replace(' ', '', $row["categorie_name"]);
            $texto1=preg_replace('([^A-Za-z0-9])', '', $texto1);
            if($m==0){
                $res_components.='<div class="tab-pane fade in active" id="'.$texto1.'">';
            }else{
                $res_components.='<div class="tab-pane" id="'.$texto1.'">';
            }
            $res_components.='<table  id="category_id'.$m.'" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            $res_components.='<thead>';
            $res_components.='<tr>';
            $res_components.='<th>';
            $res_components.='</th>';
            $res_components.='</tr>';
            $res_components.='</thead>';
            $res_components.='<tbody>';
            while ($rows=  mysqli_fetch_assoc($resultado_components_categoria)){
                //$res_components.='<div class="col-sm-6">';   
                $res_components.='<tr>';
                $res_components.='<td>';
                
                $res_components.='<div class="col-sm-6" id="div'.$rows["id"].'"><b>'.$rows["name"].'</b></div>';
                if(in_array($rows["id"],$arreglo_select)){ 
                //if($rows["modal_component_id"]!='' || $rows["modal_component_id"]!=null){
                    //if($rows["type_modal"]==0){
                        $res_components.='<input type="checkbox" checked id="checkDivAmend'.$rows["id"].'" onclick="llenarDiv(\'divsamend_'.$rows['id'].'\',this,\'editor_amend\')"';  
                        $template.='<div class="span12" id="clonedivsamend_'.$rows['id'].'">'.$arreglo_ckeditor[$k].'</div>';
                    /*}else{
                        $res_components.='<input type="checkbox" id="checkDivAmend'.$rows["id"].'" onclick="llenarDiv(\'divsamend_'.$rows['id'].'\',this,\'editor_amend\')"';  
                    }*/
                    $k++;
                }else{
                    $res_components.='<input type="checkbox" id="checkDivAmend'.$rows["id"].'" onclick="llenarDiv(\'divsamend_'.$rows['id'].'\',this,\'editor_amend\')"';  
                }                
                //$res_components.='</div>';
                $res_components.='<div>';        
                $res_components.='<span style="display:none" id="elemento_amend'.$rows['id'].'"><div class="span12" id="divsamend_'.$rows['id'].'">'.$rows['components'].'</div></span>';    
                $res_components.='</div>';
                $res_components.='</div>';
                $res_components.='</td>';
                $res_components.='</tr>';
            }
            $res_components.='</tbody>'; 
            $res_components.='</table>';
            $res_components.='</div>';
            $m++;
            unset($arreglo_select);
            unset($arreglo_ckeditor);
        }
        $res_components.='</div>';
    }
    //$res_components.='</div>';
    //$res_components.='</div>';
    $json_resultado['contentComponents']=$res_components;
    $json_resultado['contentTemplate']=$template;
    $json_resultado['total_category']=$m;
    
}
echo json_encode($json_resultado); 

?>