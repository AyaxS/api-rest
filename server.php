<?php

//Autenticacion via HTTP
// $user = array_key_exists( 'PHP_AUTH_USER', $_SERVER ) ? $_SERVER['PHP_AUTH_USER'] : '';
// $pwd = array_key_exists( 'PHP_AUTH_PW', $_SERVER ) ? $_SERVER['PHP_AUTH_PW'] : '';

// if( $user !== 'christ' || $pwd !== '1234' ) {
//     die;
// }
//Comando: curl http://christ:1234@localhost:8000/books


//Autenticación vía HMAC
if (
    !array_key_exists('HTTP_X_HASH', $_SERVER) ||
    !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
    !array_key_exists('HTTP_X_UID', $_SERVER)
) {
    die;
}

list( $hash, $uid, $timestamp ) = [
    $_SERVER['HTTP_X_HASH'],
    $_SERVER['HTTP_X_UID'],
    $_SERVER['HTTP_X_TIMESTAMP'],
];

$secret = 'Sh!! secreto!';

$newHash = sha1($uid.$timestamp.$secret);  

if ( $newHash !== $hash ) {
    die;
}

//Comando: curl http://christ:1234@localhost:8000/books -H 'X-HASH: 77b79cc6ad29c58cb0f337de01cb777f9e5e48b3' -H 'X-UID: 1' -H 'X-TIMESTAMP: 1593999645'

// Definimos los recursos disponibles
$allowedResourceTypes = [
    'books',
    'authors',
    'genres',
];

// Validamos que el recurso este disponible.
//$resourceType = $_GET['resource_type'];

//if( !in_array($resourceType, $allowedResourceTypes) ) {
//    die;
//}

// Defino los recursos

$books = [
    1 => [
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'La iliada',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
    3 => [
        'titulo' => 'La Odisea',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
];

//Encabezado
header('Content-Type: application/json');

//Levantamos el id del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

// Generamos la respuesta asumiendo que el pedido es correcto.
switch( strtoupper($_SERVER['REQUEST_METHOD']) ) {
    case 'GET':
        if ( empty( $resourceId ) ) {
            echo json_encode( $books );
        } else {
            if ( array_key_exists( $resourceId, $books ) ) {
                echo json_encode( $books[ $resourceId ] );
            }
        }
        
        break;
    case 'POST':
        //file_get_contents es una funcion que lee un archivo por completo y devuelve su contenido.
        $json = file_get_contents('php://input');

        $books[] = json_decode( $json, true );

        //Funcion que devuelve la key del nuevo libro
        echo array_keys( $books )[ count($books) - 1 ];

        //Este ejemplo se deberia usar para guardar los datos en una base de datos pero lo haremos en el mismo archivo.
        break;
    case 'PUT':
        //Validamos que el recurso exista.
        if (!empty($resourceId) && array_key_exists( $resourceId, $books ) ) {
            //Tomamos la entrada cruda
            $json = file_get_contents('php://input');

            // Transformamos el json a un nuevo elemento.
            $books[ $resourceId ] = json_decode( $json, true );

            //Retornamos la coleccion modificada en formato json
            echo json_encode( $books );
        }
        break;
    case 'DELETE':
        //Validamos que el recurso exista.
        if (!empty($resourceId) && array_key_exists( $resourceId, $books ) ) {
            unset( $books[ $resourceId] );
        }

        echo json_encode( $books );
        break;
}

/* resurce_type dentro de $_GET es el parametro que usaremos para consultar por la url
* Ejemplo curl http://localhost:8000?resource_type=books -v, es decir, 
* primero debemos ejecutar el comando php -S localhost:8000 server.php
*/