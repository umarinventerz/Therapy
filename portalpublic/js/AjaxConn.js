/** AjaxConn - Simple XMLHTTP Interface - eliasbg@gmail.com - 17/Feb/2006
 ** Código Liberado por: Elías Barrientos García
 ** página: eliasbg.dynalias.com/AJAX/paginacion                    
 ** weblog: eliasbg.blogspot.com
**/
function AJAXConn(sDestino, sCargando)
{
    var xmlhttp, bCompleto = false;

    try { 
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
    }
    catch (excepcion) { 
        try { 
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
        }
        catch (excepcion) { 
            try { 
                xmlhttp = new XMLHttpRequest(); 
            }
            catch (excepcion) { 
                xmlhttp = false; 
            }
        }
    }
    if (!xmlhttp) return null;

    this.connect = function(sURL, sMetodo, sVars)    {
        if (!xmlhttp) return false;
        bCompleto = false;
        sMetodo = sMetodo.toUpperCase();

        try {
              if (sMetodo == "GET") {
                xmlhttp.open(sMetodo, sURL+"?"+sVars, true);
                sVars = "";
              }
              else {
                xmlhttp.open(sMetodo, sURL, true);
                xmlhttp.setRequestHeader("Method", "POST "+sURL+" HTTP/1.1");
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              }
              
              xmlhttp.onreadystatechange = function(){
                //    Estado del Objeto:    //
                ST_UNINITIALIZED     = 0;
                ST_LOADING            = 1;
                ST_LOADED            = 2;
                ST_INTERACTIVE        = 3;
                ST_COMPLETE            = 4;

                if (xmlhttp.readyState == ST_LOADING) {
                    document.getElementById(sDestino).innerHTML = sCargando;
                }

                if (xmlhttp.readyState == ST_COMPLETE && !bCompleto) {
                    bCompleto = true;
                    document.getElementById(sDestino).innerHTML = xmlhttp.responseText; 
                }
            };
            xmlhttp.send(sVars);
        }   
        catch(excepcion) { 
            return false; 
        }
        return true;
    };
  return this;
}  
