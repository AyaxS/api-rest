<?php

$json = file_get_contents('https://xkcd.com/info.0.json');

// El segundo parametro TRUE es para decodificar la informacion como un arreglo y no como un objeto.
$data = json_decode($json, true); 

echo $data['img'].PHP_EOL;