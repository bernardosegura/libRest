# <img src="https://github.com/bernardosegura/libRest/blob/master/img/libRest.svg" height="100px" width="100px"/> 
# LibRest 
Librería para poder generar servicios web Rest en **php**, sin la necesidad de modificar ninguna configuración en el servidor web y de esta manera poder ser usada desde una aplicación Frontend y/o cualquier otra tecnología que lo soporten.

### Metodos Soportados
Permite resolver peticiones GET, POST, PUT y DELETE, de una manera dinámica como acceso remoto sin restricciones para el desarrollo de sistemas en entorno web.

## ¿Que hay sobre la seguridad?
Cuenta con métodos para generar token de manera lineal y que puedan con tiempo de duración, para ser validados y utilizados en cualquier momento sobre la petición.

## Intalación
Simplemente incluye la librería a tu archivo:
> include "**libRest.php**";

## Uso
Para iniciar la api:
> $api = new **restApi()**;

Una vez iniciada se agregan los métodos que se necesitan implementar:
```php
$api->get = function($data) // metodo a implementado (Get)
           {
             //código a ejecutar, en este caso regresa el json obtenido.
    	        echo json_encode($data);
           };
```
Por último iniciamos la api:
> $api->**start()**;

Y a disfrutar de un servicio **Restful** sin ninguna configuración directa en el servidor web.
