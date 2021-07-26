<?php
 /******************************************************************************************
 * Autor: Bernardo Manuel Segura Muñoz
 * Fecha: 04/08/2017
 * Fecha Actualizacion: 04/08/2018
 * Lenguaje: PHP
 * Tipo: Librería para poder generar servicios web Rest en php, sin la necesidad de modificar
         ninguna configuración en el servidor web y de esta manera poder ser usada desde
         una aplicación Frontend y/o cualquier otra tecnología que lo soporten.
 * Descripción: Archivo que contiene clases la cuales permite resolver peticiones GET, POST, PUT, 
 *         y DELETE, de una manera dinámica como acceso remoto sin restricciones para el  
 *         desarrollo de sistemas en entorno web.
 * Nombre: libRest
 * Version: Estable
 ********************************************************************************************/
    class restApi{

        public function __call($method, $args)
          {
              if (isset($this->$method)) {
                  $func = $this->$method;
                  return call_user_func_array($func, $args);
              }
          }

          public function start(){
           
          header("Access-Control-Allow-Origin: *");
          header('Access-Control-Allow-Credentials: true');
          header("Content-Type: application/json; charset=UTF-8");
          header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
          header("Access-Control-Max-Age: 86400");
          header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
     
          switch ($_SERVER['REQUEST_METHOD']) {
          case 'GET'://consulta
                  if(isset($this->get))
                  {
                    $obj = new stdClass();
                    foreach ($_GET as $key => $value) {
                        $obj->$key = $value;
                    }
                      $this->get($obj);  
                  }
                  else
                      $this->response(422,"aviso","Sin acción a tomar");
              break;     
              case 'POST'://inserta
        
                   $obj = json_decode( file_get_contents('php://input'));
                            
                   if ($obj == NULL){
                        if(isset($this->post))
                        {
                          $obj = new stdClass();
                          foreach ($_POST as $key => $value) {
                              $obj->$key = $value;
                          }
                            $this->post($obj);  
                        }
                        else
                            $this->response(422,"aviso","Sin acción a tomar");
                   }else {
                      if(isset($this->post))
                           $this->post($obj); 
                       else
                          $this->response(422,"aviso","Sin acción a tomar");
                   }
              
              break;                
          case 'PUT'://actualiza
                   $obj = json_decode( file_get_contents('php://input'));   
                  
                   if ($obj == NULL){
                       $this->response(422,"error","json nulo. revizar json enviado");                           
                   }else {
                      if(isset($this->put))
                      {
                        foreach ($_GET as $key => $value) {
                            $obj->$key = $value;
                        }
                        $this->put($obj); 
                      }     
                       else
                          $this->response(422,"aviso","Sin acción a tomar");
                   }
              break;      
          case 'DELETE'://elimina
                   if(isset($this->delete))
                   {
                      $obj = new stdClass();
                      foreach ($_GET as $key => $value) {
                          $obj->$key = $value;
                      }
                      $this->delete($obj); 
                    }    
                    else
                        $this->response(422,"aviso","Sin acción a tomar");
              break;
                  
          case 'OPTIONS'://regresa nada, es para la validacion de angular
                  echo '{}';
               break;
                  
          default://metodo NO soportado
             $this->response(422,'error','METODO NO SOPORTADO'); 
              break;
          }
      }
      /**
     * Respuesta al cliente
     * @param int $code Codigo de respuesta HTTP
     * @param String $status indica el estado de la respuesta puede ser "success" o "error"
     * @param String $message Descripcion de lo ocurrido
     */
     public function response($code=200, $estado="", $mensaje="") {
        http_response_code($code);
        if( !empty($status) && !empty($message) ){
            $response = array("estado" => $estado ,"mensaje"=>$mensaje);  
            echo json_encode($response,JSON_PRETTY_PRINT);    
        }            
     }
  }
    
  if (!function_exists('http_response_code')) {
        function http_response_code($code = NULL) {

            if ($code !== NULL) {

                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
                }

                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

                header($protocol . ' ' . $code . ' ' . $text);

                $GLOBALS['http_response_code'] = $code;

            } else {

                $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

            }

            return $code;

        }
    } 
    
/**************Seguridad por Token lineal*************************/  
  function crearEncript($solicita, $duracion=20)
    {
       
      $tiempo = time();
      $tiempoLimite = ($duracion!=-1)?($tiempo + ($duracion * 60)):-1;
      $tiempoLimite .= '';
      $solicita .= '';
      $head = strlen($solicita) . 'x';
      $body = '';
      $total = (strlen($solicita) < strlen($tiempoLimite))? strlen($tiempoLimite) : strlen($solicita);

      if((strlen($head) - 1) == 1)
        {
          $buf = strlen($solicita);
          $head = '0' . $buf;
        }else
        {
          $head = strlen($solicita);
        }
        for($i=0;$i < $total; $i++)
        {
          if(strlen($solicita) > $i)
            $body .= $solicita[$i];
          if(strlen($tiempoLimite) > $i)
            $body .= $tiempoLimite[$i];
          
        }
        $body = bin2hex($head . $body);
        return base64_encode($body);
    }

  function validarEncript($token)
    {  
      $tiempo = time();
      $body = hex2bin((isset($token)?(ctype_xdigit(base64_decode($token))?base64_decode($token):''):'')) . '';
      if($body == '') return false;
      $tamanio = $body[0] . $body[1];
      $tamanio = $tamanio + 0;
      $total = strlen($body);
      $solicita = '';
      $expiracion = '';
      $index = 0;
      $indx = 0;
      for($i=2;$i < $total; $i++)
      {
        if($i%2 == 0 && $index < $tamanio)
        {
          $solicita .= $body[$i];
          $index++;
        }
        else
        {
          if($indx < (($total - 2) - $tamanio))
          {
              $expiracion .= $body[$i];
              $indx++;
          }
          else 
            {
              $solicita .= $body[$i];
              $index++;
            } 
        }             
      }
      if($expiracion <= $tiempo && $expiracion != -1)
      {
        $return = false;
      } 
      else
      {
      	$return = new stdClass();
        $return->dato = $solicita;
        $return->tiempo = $expiracion;
      }
        return $return;
    }

function tiempoExpiracion($tiempoLimite)
	{
		$expiracion = new stdClass();
		
		$diff = abs($tiempoLimite - time());  
		
        $expiracion->years = floor($diff / (365*60*60*24));  
		  
		  
		$expiracion->months = floor(($diff - $expiracion->years * 365*60*60*24) 
		                               / (30*60*60*24));  
		  
		$expiracion->days = floor(($diff - $expiracion->years * 365*60*60*24 -  
		             $expiracion->months*30*60*60*24)/ (60*60*24)); 
		  
		$expiracion->hours = floor(($diff - $expiracion->years * 365*60*60*24  
		       - $expiracion->months*30*60*60*24 - $expiracion->days*60*60*24) 
		                                   / (60*60));  
		  
		  
		$expiracion->minutes = floor(($diff - $expiracion->years * 365*60*60*24  
		         - $expiracion->months*30*60*60*24 - $expiracion->days*60*60*24  
		                          - $expiracion->hours*60*60)/ 60);  
		  
		$expiracion->seconds = floor(($diff - $expiracion->years * 365*60*60*24  
		         - $expiracion->months*30*60*60*24 - $expiracion->days*60*60*24 
		                - $expiracion->hours*60*60 - $expiracion->minutes*60)); 
        
        return $expiracion;                 
	}

    if ( !function_exists( 'hex2bin' ) ) {
        function hex2bin( $str ) {
            $sbin = "";
            $len = strlen( $str );
            for ( $i = 0; $i < $len; $i += 2 ) {
                $sbin .= pack( "H*", substr( $str, $i, 2 ) );
            }

            return $sbin;
        }
    }