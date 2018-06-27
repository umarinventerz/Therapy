    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">        
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">    
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    
  <!--  <script src="../../../js/jquery.min.js" type="text/javascript"></script>    -->
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
   
<script>

        function generar_reporte_especifico(){
    
        var nombre_reporte = $('#reporte').val();
        var tabla = $('#tabla').val();
        var joins = $('#joins').val();
        var cantidad = $('#cantidad').val()+' as cantidad';
        var valor = $('#valor').val()+' as valor';
        var where_order_by = $('#where').val();   
        var nombre_columna = $('#valor').val();
        
        

        var nombres_campos = '';
        
        if($('#reporte').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Nombre del Reporte</td></tr></table>';
                                
        }
          if($('#tabla').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Tabla</td></tr></table>';
                                
        }
          if($('#cantidad').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Cantidad</td></tr></table>';
                                
        }
          if($('#valor').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Valor</td></tr></table>';
                                
        }
           
    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,
          animation: "slide-from-top",
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else {         
                                   

        $.post(
                "../../controlador/generate_reports/generate_specific_report.php",
                "&nombre_reporte="+nombre_reporte+"&tabla="+tabla+"&joins="+joins+"&cantidad="+cantidad+"&valor="+valor+"&where_order_by="+where_order_by+"&nombre_columna="+nombre_columna,
                function (resultado_controlador) {
                   
                   if(resultado_controlador.resultado == 'generado'){                                              
                       
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Reporte Generado</b></h3><h4><br> <u><b>QUERY</b></u> <br> <i>"'+resultado_controlador.query+'"</i></h4>'
                        });
                        
                        setTimeout(function(){window.location.reload();},1000);
                       
                   }                 
                   
                   if(resultado_controlador.resultado == 'error_query'){
                   
                        swal({
                          type: 'error',                          
                          html: '<h3><b>El Query Construido Genera Errores</b></h3><h4><br> <u><b>QUERY</b></u> <br> <i>"'+resultado_controlador.query+'"</i></h4>'
                        });
                       
                   }                    
                   
                },
                "json" 
                );
        
        return false;

                         }


        }
                </script>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
</div>

