<?php

require_once 'Controllers/UsersController.php';
require_once 'Controllers/WordsController.php';
require_once 'Repositories/UserRepository.php';
require_once 'Repositories/WordRepository.php';


$apis = [
  'users',
  'words'
  //..
];


$status = array(
  200 => 'OK',
  404 => 'Not Found',
  405 => 'Method Not Allowed',
  409 => 'Conflict',
  500 => 'Internal Server Error',
);


try {
  $requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

  //Первые 2 элемента массива URI должны быть "api" и название таблицы
  if ($requestUri[0] !== 'php-api' || !in_array($requestUri[1], $apis))
    throw new RuntimeException('API Not Found', 404);

  $api = null;

  if ($requestUri[1] === 'users')
    $api = new UsersController(new UserRepository());
  else if ($requestUri[1] === 'words')
    $api = new WordsController(new WordRepository());

  echo $api->handle();
} catch (Exception $e) {
  header("HTTP/1.1 " . $e->getCode() . " " . $status[$e->getCode()]);
  //echo json_encode(array('error' => $e->getMessage()));
  echo json_encode($e->getMessage());
}
