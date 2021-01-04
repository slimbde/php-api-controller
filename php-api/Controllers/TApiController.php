<?php

abstract class TApiController {
  protected $repo;
  protected $method = '';         //GET|POST|PUT|DELETE
  protected $action = '';         //Название метод для выполнения
  protected $requestUri = [];
  protected $requestParams = [];

  private $status = array(
    200 => 'OK',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    500 => 'Internal Server Error',
  );




  public function __construct(ITrainingRepository $repo) {
    $this->repo = $repo;

    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");
    header("Content-Type: application/json");

    //Массив GET параметров разделенных слешем
    $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $this->requestParams = $_REQUEST;

    //Определение метода запроса
    $this->method = $_SERVER['REQUEST_METHOD'];
    if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
      if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE')
        $this->method = 'DELETE';
      else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT')
        $this->method = 'PUT';
      else
        throw new Exception("Unexpected Header");
    }
  }

  public function handle() {
    //Определение действия для обработки
    $this->action = $this->getAction();

    //Если метод(действие) определен в дочернем классе API
    if (method_exists($this, $this->action))
      return $this->{$this->action}();
    else
      throw new RuntimeException('Invalid Method', 405);
  }

  protected function response($data = "Internal Server Error") {
    $code = 500;

    if (is_array($data) && count($data) === 0) {
      $code = 404;
      $data = "Not found";
    }

    if (is_array($data) && count($data) > 0)
      $code = 200;

    header("HTTP/1.1 " . $code . " " . $this->status[$code]);
    return json_encode($data);
  }

  abstract protected function getAction();
}
