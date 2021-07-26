<?php  
//Activamos los mensajes por si el servidor los tiene desactivados, esto en caso de ser necesario.
error_reporting(E_ALL);
ini_set('display_errors', '1');

//Incluimos libreria
include "libRest.php";

 try {
     //Creamos Aplicacion
     $api = new restApi();
     
    //Creamos Método POST si es necesario, los datos deben de ser enviados como aplicación json y con dicho formato.
    $api->post = function($data)
    {
        echo json_encode($data);
         
    };

   $api->start();//Iniciamos Aplicacion 

 } catch(exception $ex)
{
    echo $ex->getMessage();
}
