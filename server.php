<?php
// Exponer datos a través de HTTP GET

// Definimos los recursos disponibles
$allowedResourceTypes = [
    'books',
    'authors',
    'genres',
];

// Validamos que el recurso este disponible.
$resourceType = $_GET['resource_type'];

if(!in_array($resourceType, $allowedResourceTypes)) {
    die;
}

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
    ]
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
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}

/* resurce_type dentro de $_GET es el parametro que usaremos para consultar por la url
* Ejemplo curl http://localhost:8000?resource_type=books -v, es decir, 
* primero debemos ejecutar el comando php -S localhost:8000 server.php
*/